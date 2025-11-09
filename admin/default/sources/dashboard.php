<div class="i_contents_container">
    <div class="i_total_earning flex_ column tabing__justify">
      <div class="net_earn_title flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('76'));?><?php echo iN_HelpSecure($LANG['net_admin_earnings']);?> </div>
      <div class="net_earn"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span class="count-num"><?php $sum=(float)$iN->iN_TotalSubscriptionEarnings($userID)+(float)$iN->iN_TotalPremiumEarnings($userID)+(float)$iN->iN_TotalBoostEarnings($userID); echo addCommasAndDots($sum);?></span></div>
    </div>
    <!---->
    <div class="i_contents_section flex_ tabing">
       <!---->
       <div class="row_wrapper">
           <div class="row_item flex_ column border_one c1">
               <div class="chart_row_box_title flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51'));?><?php echo iN_HelpSecure($LANG['subscriptions']);?></div>
               <div class="chart_row_box_sum"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span class="count-num"><?php echo addCommasAndDots($iN->iN_TotalSubscriptionEarnings($userID));?></span></div>
               <div class="wmore tabing_non_justify flex_"><a href="<?php echo iN_HelpSecure($base_url).'admin/manage_subscriptions';?>"><?php echo iN_HelpSecure($LANG['view_more']);?><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('98'));?></a></div>
           </div>
       </div>
       <!---->
       <!---->
       <div class="row_wrapper">
           <div class="row_item flex_ column border_one c2">
               <div class="chart_row_box_title flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?><?php echo iN_HelpSecure($LANG['all_point_earning']);?></div>
               <div class="chart_row_box_sum"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span class="count-num"><?php echo addCommasAndDots($iN->iN_TotalPremiumEarnings($userID));?></span></div>
               <div class="wmore tabing_non_justify flex_"><a href="<?php echo iN_HelpSecure($base_url).'admin/point_earnings';?>"><?php echo iN_HelpSecure($LANG['view_more']);?><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('98'));?></a></div>
           </div>
       </div>
       <!---->
       <!---->
        <div class="row_wrapper">
           <div class="row_item flex_ column border_one c7">
               <div class="chart_row_box_title flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('177'));?><?php echo iN_HelpSecure($LANG['boost_earnings']);?></div>
               <div class="chart_row_box_sum"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span class="count-num"><?php echo addCommasAndDots($iN->iN_TotalBoostEarnings($userID));?></span></div>
               <div class="wmore tabing_non_justify flex_"><a href="<?php echo iN_HelpSecure($base_url).'admin/manage_boosted_posts';?>"><?php echo iN_HelpSecure($LANG['view_more']);?><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('98'));?></a></div>
           </div>
       </div>
       <!---->
       <!---->
       <div class="row_wrapper">
           <div class="row_item flex_ column border_one c3">
               <div class="chart_row_box_title flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15'));?><?php echo iN_HelpSecure($LANG['users']);?></div>
               <div class="chart_row_box_sum"><span class="count-num"><?php echo addCommasAndDots($iN->iN_TotalUsers());?></span></div>
               <div class="wmore tabing_non_justify flex_"><a href="<?php echo iN_HelpSecure($base_url).'admin/manage_users';?>"><?php echo iN_HelpSecure($LANG['view_more']);?><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('98'));?></a></div>
           </div>
       </div>
       <!---->
       <!---->
       <div class="row_wrapper">
           <div class="row_item flex_ column border_one c4">
               <div class="chart_row_box_title flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('110'));?><?php echo iN_HelpSecure($LANG['posts']);?></div>
               <div class="chart_row_box_sum"><span class="count-num"><?php echo addCommasAndDots($iN->iN_TotalUserPosts());?></span></div>
               <div class="wmore tabing_non_justify flex_"><a href="<?php echo iN_HelpSecure($base_url).'admin/allPosts';?>"><?php echo iN_HelpSecure($LANG['view_more']);?><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('98'));?></a></div>
           </div>
       </div>
       <!---->
    </div>
    <!---->
    <div class="i_contents_section_two flex_ tabing column c5 border_one">
        <div class="i_sub_not"><?php echo iN_HelpSecure($LANG['statistic_of_the_month']);?></div>
        <div class="statistic_chart"><canvas id="myChart"></canvas></div>
        <div class="revenues flex_ tabing">
            <div class="revenue_box flex_ tabing column">
                <div class="revenue_sum"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span class="count-num"><?php $t=(float)$iN->iN_CurrentDayTotalPremiumEarning()+ (float)$iN->iN_CurrentDayTotalSubscriptionEarning()+ (float)$iN->iN_CurrentDayTotalBoostEarning(); echo addCommasAndDots($t);?></span></div>
                <div class="revenue_title"><?php echo iN_HelpSecure($LANG['revenue_today']);?></div>
            </div>
            <div class="revenue_box flex_ tabing column">
                <div class="revenue_sum"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span class="count-num"><?php $tw=(float)$iN->iN_WeeklyTotalPremiumEarning() + (float)$iN->iN_WeeklyTotalSubscriptionEarning() + (float)$iN->iN_WeeklyTotalBoostEarning(); echo addCommasAndDots($tw);?></span></div>
                <div class="revenue_title"><?php echo iN_HelpSecure($LANG['revenue_this_week']);?></div>
            </div>
            <div class="revenue_box flex_ tabing column">
                <div class="revenue_sum"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span class="count-num"><?php $tm=(float)$iN->iN_CurrentMonthTotalPremiumEarning() + (float)$iN->iN_CurrentMonthTotalSubscriptionEarning() + (float)$iN->iN_CurrentMonthTotalBoostEarning(); echo addCommasAndDots($tm);?></span></div>
                <div class="revenue_title"><?php echo iN_HelpSecure($LANG['revenue_this_month']);?></div>
            </div>
        </div>
    </div>
    <div class="i_contents_section_three flex_ tabing">
        <!---->
        <div class="section_three_item">
          <div class="section_three_box border_one flex_ column">
              <div class="i_box_header flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15'));?><?php echo iN_HelpSecure($LANG['new_registered_users']);?></div>
              <div class="i_box_container flex_ column">
                <?php
                $latestUsers = $iN->iN_NewRegisteredUsers();
                if($latestUsers){
                    foreach($latestUsers as $lu){
                        $latestUserID = $lu['iuid'];
                        $latestUserAvatar = $iN->iN_UserAvatar($latestUserID, $base_url);
                        $latestUserUserName = $lu['i_username'];
                        $latestUserFullName = $lu['i_user_fullname'];
                        $latestUserRegistered = $lu['registered'];
                        $crTime = date('Y-m-d H:i:s',$latestUserRegistered);
                ?>
                <!---->
                <div class="i_box_item border_one transition" id="<?php echo iN_HelpSecure($latestUserID);?>">
                <a class="flex_ transition tabing_non_justify" href="<?php echo iN_HelpSecure($base_url).$iN->iN_Secure($latestUserUserName);?>">
                    <div class="i_box_item_user_avatar flex_ tabing border_two"><img src="<?php echo iN_HelpSecure($latestUserAvatar);?>"></div>
                    <div class="i_box_item_user_details">
                        <div class="i_box_item_user_name truncated"><?php echo html_entity_decode($iN->iN_Secure($latestUserFullName));?></div>
                        <div class="i_box_item_user_reg_time_unm">@<?php echo iN_HelpSecure($iN->iN_Secure($latestUserUserName));?> &middot; <?php echo TimeAgo::ago($crTime , date('Y-m-d H:i:s'));?></div>
                    </div>
                </a>
                </div>
                <!---->
                <?php } } ?>
              </div>
          </div>
        </div>
        <!---->
        <!---->
        <div class="section_three_item">
          <div class="section_three_box border_one flex_ column">
              <div class="i_box_header flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51'));?><?php echo iN_HelpSecure($LANG['latest_subscriptions']);?></div>
              <div class="i_box_container flex_ column">
                <?php
                $subscriptionUsers = $iN->iN_LatestPaymentsSubscriptionsList();
                if($subscriptionUsers){
                    foreach($subscriptionUsers as $payedSub){
                        $payedSubscriptionID = $payedSub['subscription_id'];
                        $payedSubscriberUidFk = $payedSub['iuid_fk'];
                        $payedSubscribedUidFk = $payedSub['subscribed_iuid_fk'];
                        $payedSubscriberPlanID = $payedSub['plan_id'];
                        $payedSubscriberAvatar = $iN->iN_UserAvatar($payedSubscriberUidFk, $base_url);
                        $payedSubscribedAvatar = $iN->iN_UserAvatar($payedSubscribedUidFk, $base_url);
                        $subscribtionStarted = $payedSub['created'];
                        $payedAmount = $payedSub['plan_amount'];
                        $payedCurrency = strtoupper($payedSub['plan_amount_currency']);
                        $adminEarning = $payedSub['admin_earning'];
                        $netEarning = $payedAmount - $adminEarning;
                        $subscriptionStatus = $payedSub['status'];
                        $patedUserData = $iN->iN_GetUserDetails($payedSubscriberUidFk);
                        $payedSubscriberUserName = $patedUserData['i_username'];
                        $payedSubscriberUserFullName = $patedUserData['i_user_fullname'];
                        $myDateTime = date('d/m/Y', strtotime($subscribtionStarted));
                        $paUserData = $iN->iN_GetUserDetails($payedSubscribedUidFk);
                        $payerSubscriberUserName = $paUserData['i_username'];
                        $payerSubscriberUserFullName = $paUserData['i_user_fullname'];
                ?>
                <!---->
                <div class="i_box_item border_one flex_ transition tabing_non_justify" id="<?php echo iN_HelpSecure($payedSubscriptionID);?>">
                    <div class="i_box_item_user_avatar_sub flex_ tabing">
                        <div class="i_subscriber_user_avatar flex_ tabing border_two"><img src="<?php echo iN_HelpSecure($payedSubscriberAvatar);?>"></div>
                        <div class="i_subsciption_user_avatar border_two flex_ tabing">
                            <img src="<?php echo iN_HelpSecure($payedSubscribedAvatar);?>">
                        </div>
                    </div>
                    <div class="i_box_item_user_details">
                        <div class="i_box_item_user_name"><?php echo html_entity_decode($iN->iN_TextReaplacement($LANG['subscribed_u'],[$payedSubscriberUserName, $payedSubscriberUserFullName,$payerSubscriberUserName,$payerSubscriberUserFullName]));?></div>
                        <div class="i_box_item_user_reg_time_unm">&middot; <?php echo iN_HelpSecure($myDateTime);?></div>
                    </div>
                </div>
                <!---->
                <?php } } ?>
              </div>
          </div>
        </div>
        <!---->
        <!---->
        <div class="section_three_item">
          <div class="section_three_box border_one flex_ column">
              <div class="i_box_header flex_ tabing_non_justify"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?><?php echo iN_HelpSecure($LANG['latest_content_payments']);?></div>
              <div class="i_box_container flex_ column">
                <?php
                $contentPayments = $iN->iN_LatestContentPaymentsList();
                if($contentPayments){
                    foreach($contentPayments as $pay){
                        $paymentDataID = $pay['payment_id'];
                        $paymentDataPayerUserID = $pay['payer_iuid_fk'];
                        $paymentDataPayedUserID = $pay['payed_iuid_fk'];
                        $paymentDataPayedPostID = $pay['payed_post_id_fk'];
                        $postSlug = $iN->iN_GetAllPostDetails($paymentDataPayedPostID);
                        $slug = isset($postSlug['url_slug']) ? $postSlug['url_slug'] : NULL;
                        $paymentDataPayedProfileID = $pay['payed_profile_id_fk'];
                        $paymentDataOrderKey = $pay['order_key'];
                        $paymentDataPaymentType = $pay['payment_type'];
                        $paymentDataPaymentOption = $pay['payment_option'];
                        $paymentDataPaymentTime = $pay['payment_time'];
                        $paymentDataPaymentStatus = $pay['payment_status'];
                        $paymentDataPaymentAmount = $pay['amount'];
                        $paymentDataPaymentFee = $pay['fee'];
                        $paymentDataPaymentAdminEarning = $pay['admin_earning'];
                        $paymentDataPaymentUserEarning = $pay['user_earning'];
                        $payerUserAvatar = $iN->iN_UserAvatar($paymentDataPayerUserID, $base_url);
                        $payedUserAvatar = $iN->iN_UserAvatar($paymentDataPayedUserID, $base_url);
                        $payerUserData = $iN->iN_GetUserDetails($paymentDataPayerUserID);
                        $payerUserName = isset($payerUserData['i_username']) ? $payerUserData['i_username'] : NULL;
                        $payerUserFullName = isset($payerUserData['i_user_fullname']) ? $payerUserData['i_user_fullname'] : NULL;
                        $paUserData = $iN->iN_GetUserDetails($paymentDataPayedUserID);
                        $payedUserName = $paUserData['i_username'];
                        $payedUserFullName = $paUserData['i_user_fullname'];
                        $buyTime = date('Y-m-d H:i:s',$paymentDataPaymentTime);
                ?>
                <!---->
                <div class="i_box_item border_one flex_ transition tabing_non_justify" id="<?php echo iN_HelpSecure($paymentDataID);?>">
                    <div class="i_box_item_user_avatar_sub flex_ tabing">
                        <div class="i_subscriber_user_avatar flex_ tabing border_two"><img src="<?php echo iN_HelpSecure($payerUserAvatar);?>"></div>
                        <div class="i_subsciption_user_avatar border_two flex_ tabing">
                            <img src="<?php echo iN_HelpSecure($payedUserAvatar);?>">
                        </div>
                    </div>
                    <div class="i_box_item_user_details">
                        <div class="i_box_item_user_name"><?php echo html_entity_decode($iN->iN_TextReaplacement($LANG['payedcontent_u'],[$payerUserName, $payerUserFullName,$payedUserName,$payedUserFullName]));?></div>
                        <div class="i_box_item_user_reg_time_unm">&middot; <?php echo TimeAgo::ago($buyTime , date('Y-m-d H:i:s'));?></div>
                    </div>
                </div>
                <!---->
                <?php } } ?>
              </div>
          </div>
        </div>
        <!---->
    </div>
</div>
<?php

$yearMonthTotalySubscriptions = $yearMonthTotalPointEarnings = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
$rowsSubs = DB::all("SELECT DAY(created) - 1 AS dayIndex, SUM(admin_earning) AS daily_total FROM i_user_subscriptions WHERE MONTH(created) = MONTH(CURDATE()) AND YEAR(created) = YEAR(CURDATE()) AND status IN('active','inactive') AND in_status IN('1','0') AND finished = '0' GROUP BY DAY(created) ORDER BY DAY(created)");
foreach ($rowsSubs as $row) { $yearMonthTotalySubscriptions[(int)$row['dayIndex']] = (float)$row['daily_total']; }
$rowsPoints = DB::all("SELECT DAY(FROM_UNIXTIME(payment_time)) - 1 AS dayIndex, SUM(admin_earning) AS daily_total FROM i_user_payments WHERE MONTH(FROM_UNIXTIME(payment_time)) = MONTH(CURDATE()) AND YEAR(FROM_UNIXTIME(payment_time)) = YEAR(CURDATE()) AND payment_status = 'ok' AND admin_earning IS NOT NULL GROUP BY DAY(FROM_UNIXTIME(payment_time)) ORDER BY DAY(FROM_UNIXTIME(payment_time))");
foreach ($rowsPoints as $row) { $yearMonthTotalPointEarnings[(int)$row['dayIndex']] = (float)$row['daily_total']; }
function days_in_month($month, $year){
    // calculate number of days in a month
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}
?>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/countNumber/jquery.waypoints.js?v=<?php echo iN_HelpSecure($version);?>"></script>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/countNumber/jquery.countup.js?v=<?php echo iN_HelpSecure($version);?>"></script>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/chartJs/chart.js?v=<?php echo iN_HelpSecure($version);?>"></script>
