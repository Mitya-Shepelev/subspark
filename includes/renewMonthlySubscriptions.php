<?php
ob_start();
session_start();

include_once "connect.php";
include_once "db.php";
include_once "functions.php";
if (isset($pdo) && $pdo instanceof PDO) { DB::init($pdo); }

$iN = new iN_UPDATES($db);
$inc = $iN->iN_Configurations();
$subscriptionType = isset($inc['subscription_type']) ? $inc['subscription_type'] : null;

// Fetch active subscriptions renewing today
$rows = DB::all("SELECT subscription_id, iuid_fk, subscribed_iuid_fk, plan_interval, SUM(user_net_earning) AS calculate
    FROM i_user_subscriptions
    WHERE status IN('active','inactive') AND in_status IN('1','0') AND finished = '0'
      AND plan_interval = 'month'
      AND DATE_FORMAT(plan_period_start, '%Y-%m-%d') = CURDATE()
    GROUP BY subscribed_iuid_fk");

$rows2 = DB::all("SELECT subscription_id, iuid_fk, subscribed_iuid_fk, plan_interval, SUM(user_net_earning) AS calculate
    FROM i_user_subscriptions
    WHERE status = 'inactive' AND in_status = '1' AND finished = '1'
      AND plan_interval = 'month'
      AND DATE(plan_period_start) = CURDATE()
    GROUP BY subscribed_iuid_fk");

// Handle renewals
if (!empty($rows)) {
    foreach ($rows as $row) {
        $subscriptionID = (int)$row['subscription_id'];
        $iuidFK = (int)$row['subscribed_iuid_fk'];
        $subscriberUidFK = (int)$row['iuid_fk'];
        $amountPayable = (float)$row['calculate'];
        $planInterval = $row['plan_interval'];

        // Extend subscription dates
        $startNewEnd = date("Y-m-d H:i:s", strtotime('+1 month'));

        // Normalize plan interval label
        $pInterval = match($planInterval) {
            'week' => 'weekly',
            'month' => 'monthly', // corrected typo
            default => 'yearly'
        };

        if ($subscriptionType === '2') {
            $planData = $iN->iN_GetUserSubscriptionPlanDetails($iuidFK, $pInterval);
            $planAmount = isset($planData['amount']) ? (float)$planData['amount'] : 0;
            $uDat = $iN->iN_GetUserDetails($subscriberUidFK);
            $walletPoint = (float)$uDat['wallet_points'];

            if ($walletPoint >= $planAmount) {
                DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$amountPayable, $iuidFK]);
                DB::exec("UPDATE i_users SET wallet_points = wallet_points - ? WHERE iuid = ?", [$planAmount, $subscriberUidFK]);
                DB::exec("UPDATE i_user_subscriptions SET plan_period_start = ?, plan_period_end = ? WHERE subscription_id = ?", [$startNewEnd, $startNewEnd, $subscriptionID]);
            } else {
                // Downgrade to follower
                DB::exec("UPDATE i_friends SET fr_status = 'flwr' WHERE fr_one = ? AND fr_two = ?", [$subscriberUidFK, $iuidFK]);
                DB::exec("UPDATE i_user_subscriptions SET status = 'declined', finished = '1', in_status = '1' WHERE subscription_id = ?", [$subscriptionID]);
            }

            // Clean up old inactive
            DB::exec("UPDATE i_user_subscriptions SET status = 'declined', finished = '1', in_status = '1' WHERE subscription_id = ? AND status = 'inactive' AND in_status = '1'", [$subscriptionID]);
        } else {
            // Regular plan renew
            DB::exec("UPDATE i_user_subscriptions SET plan_period_start = ?, plan_period_end = ? WHERE subscription_id = ?", [$startNewEnd, $startNewEnd, $subscriptionID]);
            DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$amountPayable, $iuidFK]);
        }
    }
}

// Handle expired/inactive subscriptions
if (!empty($rows2)) {
    foreach ($rows2 as $row) {
        $subscriptionID = (int)$row['subscription_id'];
        $iuidFK = (int)$row['subscribed_iuid_fk'];
        $subscriberUidFK = (int)$row['iuid_fk'];

        if ($subscriptionType === '2') {
            DB::exec("UPDATE i_friends SET fr_status = 'flwr' WHERE fr_one = ? AND fr_two = ?", [$subscriberUidFK, $iuidFK]);
        }
    }
}
?>
