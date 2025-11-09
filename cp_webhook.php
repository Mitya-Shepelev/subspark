<?php
// Minimal bootstrap: DB connection + functions only (avoid inc.php output/side effects)
require_once __DIR__ . '/includes/connect.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
if (isset($pdo) && $pdo instanceof PDO) { DB::init($pdo); }

// Helper to respond and exit
function cp_respond(int $code, array $payload = null) {
    http_response_code($code);
    if ($payload !== null) {
        header('Content-Type: application/json');
        echo json_encode($payload);
    }
    exit;
}

// Read raw body and required headers
$raw = file_get_contents('php://input');
if ($raw === false || $raw === '') { cp_respond(400, ['error' => 'Empty body']); }
$hmacHeader = $_SERVER['HTTP_HMAC'] ?? '';

// Load CoinPayments IPN secret and merchant id from DB
$ipnSecret = '';
$merchantId = '';
$row = DB::one("SELECT coinpayments_ipn_secret, coinpayments_merchand_id FROM i_payment_methods WHERE payment_method_id = 1 LIMIT 1");
if ($row) {
    $ipnSecret = (string)($row['coinpayments_ipn_secret'] ?? '');
    $merchantId = (string)($row['coinpayments_merchand_id'] ?? '');
}
if ($ipnSecret === '' || $merchantId === '') { cp_respond(400, ['error' => 'IPN not configured']); }

// Parse POST
parse_str($raw, $postData);
if (empty($postData)) { $postData = $_POST; }

// Verify IPN mode
if (($postData['ipn_mode'] ?? '') !== 'hmac') { cp_respond(400, ['error' => 'Invalid IPN mode']); }

// Verify merchant
if (($postData['merchant'] ?? '') !== $merchantId) { cp_respond(403, ['error' => 'Bad merchant']); }

// Verify HMAC signature (sha512)
$calcHmac = hash_hmac('sha512', $raw, trim($ipnSecret));
if (!hash_equals($calcHmac, $hmacHeader)) { cp_respond(403, ['error' => 'Bad HMAC']); }

// Process status
$txnID = $postData['txn_id'] ?? null;
if (!$txnID) { cp_respond(400, ['error' => 'Missing txn_id']); }
$status = (int)($postData['status'] ?? 0);

// Instantiate helper for plan lookup
$iN = new iN_UPDATES($db);

// Fetch local payment row
$row = DB::one('SELECT payment_status, credit_plan_id, payer_iuid_fk FROM i_user_payments WHERE order_key = ? LIMIT 1', [$txnID]);
if (!$row) { cp_respond(404, ['error' => 'Order not found']); }

$currentStatus = (string)$row['payment_status'];
$creditPlanID = (int)$row['credit_plan_id'];
$payerUserID  = (int)$row['payer_iuid_fk'];

// Idempotency: if already ok and status >=100, acknowledge
if ($currentStatus === 'ok' && $status >= 100) { cp_respond(200, ['received' => true]); }

// Use transaction for atomicity
DB::begin();
try {
    if ($status >= 100 || $status === 2) {
        // Completed
        $planData   = $iN->GetPlanDetails($creditPlanID);
        $planAmount = isset($planData['plan_amount']) ? (float)$planData['plan_amount'] : 0.0;

        DB::exec("UPDATE i_user_payments SET payment_status = 'ok' WHERE order_key = ?", [$txnID]);

        if ($planAmount > 0 && $payerUserID > 0) {
            $planAmountStr = (string)$planAmount; // wallet_points is varchar in schema
            DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmountStr, $payerUserID]);
        }
    } elseif ($status < 0) {
        DB::exec("UPDATE i_user_payments SET payment_status = 'declined' WHERE order_key = ?", [$txnID]);
    } else {
        DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE order_key = ?", [$txnID]);
    }

    DB::commit();
} catch (Throwable $e) {
    DB::rollBack();
    cp_respond(500, ['error' => 'DB error']);
}

cp_respond(200, ['received' => true]);
?>
