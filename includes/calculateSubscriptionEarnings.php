<?php 
$lastDayOfTheMonth =  date('t');
$today = date("d");  
    include_once "inc.php";  
    $rows = DB::all("SELECT subscription_id,subscribed_iuid_fk,plan_interval,plan_period_start,plan_period_end, SUM(user_net_earning) AS calculate FROM i_user_subscriptions WHERE status IN('active','inactive') AND in_status IN('1','0') AND finished = '0' GROUP BY subscribed_iuid_fk");

    if(!empty($rows)){
        foreach($rows as $row){  
            $subscriptionID = $row['subscription_id'];
            $iuidFK = $row['subscribed_iuid_fk'];
            $amountPayable = $row['calculate'];
            $planInterval = $row['plan_interval'];
            $time = strtotime('+15 day', time()); 
            $userDetail = $iN->iN_GetUserDetails($iuidFK);
            $payoutMethod = $userDetail['payout_method']; 
            $planPeriodStart = $row['plan_period_start'];
            $planPeriodEnd = $row['plan_period_end'];
            if($planInterval == 'week'){
                $HowManyWeeks = date( 'W', strtotime($planPeriodEnd) ) - date( 'W', strtotime($planPeriodStart) ); 
                echo $HowManyWeeks;
            } 
        }  
    } 
$HowManyWeeks = date( 'W', strtotime( '2022-04-18 23:59:00' ) ) - date( 'W', strtotime( '2022-04-01 00:00:00' ) );
?>  
