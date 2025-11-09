<?php
// Stripe Webhook endpoint for subscription lifecycle events
// - Verifies signature using STRIPE_WEBHOOK_SECRET (set as environment variable)
// - Updates i_user_subscriptions periods and statuses
// - Credits creator wallet on successful renewals

// Minimal bootstrap: DB + functions + Stripe SDK (avoid includes/inc.php to prevent output)
require_once __DIR__ . '/includes/connect.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
if (isset($pdo) && $pdo instanceof PDO) { DB::init($pdo); }
require_once __DIR__ . '/includes/stripe/vendor/autoload.php';

use Stripe\Webhook;
use Stripe\Stripe;

// Prepare helper: JSON response with status code
function respond($code = 200, $payload = null) {
    http_response_code($code);
    if ($payload !== null) {
        header('Content-Type: application/json');
        echo json_encode($payload);
    }
    exit;
}

// Load Stripe API key and webhook secret
$webhookSecret = '';

// Pull Stripe secret/public keys from DB configurations if needed
// Avoids loading inc.php to keep output clean; fetch directly
$stripeSecretKey = null;
try {
    $row = DB::one("SELECT stripe_secret_key, stripe_webhook_secret FROM i_configurations LIMIT 1");
    if ($row) {
        $stripeSecretKey = $row['stripe_secret_key'] ?? null;
        if (!empty($row['stripe_webhook_secret'])) {
            $webhookSecret = $row['stripe_webhook_secret'];
        }
    }
} catch (Exception $e) {
    // ignore; we'll still try to verify signatures
}

// Fallback to environment variable if not found in DB
if (!$webhookSecret) {
    $webhookSecret = getenv('STRIPE_WEBHOOK_SECRET') ?: '';
}

if (!$webhookSecret) {
    // For security, require STRIPE_WEBHOOK_SECRET env var
    // Configure in Apache/Nginx/PHP-FPM: SetEnv STRIPE_WEBHOOK_SECRET your_secret
    respond(400, ['error' => 'Missing STRIPE_WEBHOOK_SECRET']);
}

// Read payload and signature header
$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

// Verify signature and construct event
try {
    $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    respond(400, ['error' => 'Invalid payload']);
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    respond(400, ['error' => 'Invalid signature']);
}

// Optionally set API key for object retrievals
if (!empty($stripeSecretKey)) {
    Stripe::setApiKey($stripeSecretKey);
}

// Instantiate functions helper
$iN = new iN_UPDATES($db);

// Helpers
function fetchSubscriptionByStripeId(string $stripeSubId): ?array {
    return DB::one("SELECT * FROM i_user_subscriptions WHERE payment_subscription_id = ? LIMIT 1", [$stripeSubId]);
}

function updateSubscriptionPeriod(array $row, int $startTs, int $endTs): bool {
    $subscriptionId = (int)$row['subscription_id'];
    $start = date('Y-m-d H:i:s', $startTs);
    $end   = date('Y-m-d H:i:s', $endTs);
    $affected = DB::exec("UPDATE i_user_subscriptions SET plan_period_start = ?, plan_period_end = ?, status = 'active', in_status = 0, finished = '0' WHERE subscription_id = ?",
        [$start, $end, $subscriptionId]
    );
    return $affected > 0;
}

function creditCreatorOnRenew(array $row): bool {
    $creatorId = (int)$row['subscribed_iuid_fk'];
    $net = (float)$row['user_net_earning'];
    if ($net <= 0) { return true; }
    $affected = DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$net, $creatorId]);
    return $affected > 0;
}

function downgradeToFollower(array $row): void {
    $subscriberId = (int)$row['iuid_fk'];
    $creatorId    = (int)$row['subscribed_iuid_fk'];
    DB::exec("UPDATE i_friends SET fr_status = 'flwr' WHERE fr_one = ? AND fr_two = ?", [$subscriberId, $creatorId]);
    $subscriptionId = (int)$row['subscription_id'];
    DB::exec("UPDATE i_user_subscriptions SET status = 'declined', finished = '1', in_status = '1' WHERE subscription_id = ?", [$subscriptionId]);
}

// Idempotency guard for crediting: only credit if period end moved forward
function isNewPeriod(array $row, int $newEndTs): bool {
    $currentEnd = $row['plan_period_end'];
    if (empty($currentEnd)) { return true; }
    $currentEndTs = strtotime($currentEnd);
    return ($newEndTs > $currentEndTs);
}

// Handle events
switch ($event->type) {
    case 'invoice.payment_succeeded': {
        $invoice = $event->data->object; // \Stripe\Invoice
        $stripeSubId = $invoice->subscription ?? null;
        if (!$stripeSubId) { break; }
        $row = fetchSubscriptionByStripeId($stripeSubId);
        if (!$row) { break; }

        // Find the subscription period from invoice line
        $periodStart = null; $periodEnd = null;
        if (isset($invoice->lines) && isset($invoice->lines->data[0]->period)) {
            $periodStart = $invoice->lines->data[0]->period->start ?? null;
            $periodEnd   = $invoice->lines->data[0]->period->end ?? null;
        }

        // Fallback to subscription object if needed
        if ((!$periodStart || !$periodEnd) && !empty($stripeSecretKey)) {
            try {
                $sub = \Stripe\Subscription::retrieve($stripeSubId);
                $periodStart = $sub->current_period_start ?? $periodStart;
                $periodEnd   = $sub->current_period_end ?? $periodEnd;
            } catch (Exception $e) { /* ignore */ }
        }

        if (!$periodStart || !$periodEnd) { break; }

        // Only credit on recurring cycles, not on creation
        $billingReason = $invoice->billing_reason ?? '';
        $isRecurring = ($billingReason === 'subscription_cycle');

        // Update periods if advanced
        if (isNewPeriod($row, (int)$periodEnd)) {
            updateSubscriptionPeriod($row, (int)$periodStart, (int)$periodEnd);
            if ($isRecurring) {
                creditCreatorOnRenew($row);
            }
        }
        break;
    }

    case 'invoice.payment_failed': {
        $invoice = $event->data->object; // \Stripe\Invoice
        $stripeSubId = $invoice->subscription ?? null;
        if (!$stripeSubId) { break; }
        $row = fetchSubscriptionByStripeId($stripeSubId);
        if ($row) { downgradeToFollower($row); }
        break;
    }

    case 'customer.subscription.deleted':
    case 'customer.subscription.canceled':
    case 'customer.subscription.updated': {
        $sub = $event->data->object; // \Stripe\Subscription
        $stripeSubId = $sub->id ?? null;
        if (!$stripeSubId) { break; }
        $row = fetchSubscriptionByStripeId($stripeSubId);
        if (!$row) { break; }

        $status = $sub->status ?? '';
        // Map Stripe status to local logic
        if (in_array($status, ['canceled','unpaid','incomplete_expired','past_due'])) {
            downgradeToFollower($row);
        } elseif (in_array($status, ['active','trialing'])) {
            // Keep active and align period
            if (!empty($sub->current_period_start) && !empty($sub->current_period_end)) {
                if (isNewPeriod($row, (int)$sub->current_period_end)) {
                    updateSubscriptionPeriod($row, (int)$sub->current_period_start, (int)$sub->current_period_end);
                }
            }
        }
        break;
    }

    // Other events can be handled/logged as needed
    default:
        break;
}

// Acknowledge receipt
respond(200, ['received' => true]);
?>
