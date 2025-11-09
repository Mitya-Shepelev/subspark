<?php
$totalPages = ceil($totalSubscribers / $paginationLimit);
if (isset($_GET["page-id"])) {
    $pagep  = (int)$_GET["page-id"];
    if ($pagep <= 0) { $pagep = 1; }
}else{
    $pagep = 1;
}
?>
<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in inTable">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?><?php echo iN_HelpSecure($LANG['subscription_payments']);?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['history_of_all_subscriptions_received']);?></div>
    </div>
    <div class="i_settings_wrapper_items">

       <div class="i_tab_container">
           <!--PAYMENTS TABLE HEADER-->
           <div class="i_tab_header flex_">
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['id']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['subscriber']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['started_at']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['ends_at']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['amount']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['earning']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['status']);?></div>
           </div>
           <!--/PAYMENTS TABLE HEADER-->
           <!---->
           <div class="i_tab_list_item_container">
            <?php
               $payedActiveSubscriptions = $iN->iN_PaymentsSubscriptionsList($userID, $paginationLimit, $pagep);
               if($payedActiveSubscriptions){
                  foreach($payedActiveSubscriptions as $payedSub){
                    $payedSubscriptionID = $payedSub['subscription_id'] ?? NULL;
                    $payedSubscriberUidFk = $payedSub['iuid_fk'] ?? NULL;
                    $payedSubscribedUidFk = $payedSub['subscribed_iuid_fk'] ?? NULL;
                    $payedSubscriberPlanID = $payedSub['plan_id'] ?? NULL;
                    $paymentMethod = $payedSub['payment_method'] ?? NULL;
                    $userNetEarningC = $payedSub['user_net_earning'] ?? NULL;
                    $payedSubscriberAvatar = $iN->iN_UserAvatar($payedSubscriberUidFk, $base_url);
                    $subscribtionStarted = $payedSub['created'] ?? NULL;
                    $subscribtionEnd = $payedSub['plan_period_end'] ?? NULL;
                    $payedAmount = $payedSub['plan_amount'] ?? NULL;
                    $payedCurrency = strtoupper($payedSub['plan_amount_currency']);
                    $adminEarning = $payedSub['admin_earning'] ?? NULL;
                    $netEarning = $payedAmount - $adminEarning;
                    $subscriptionStatus = $payedSub['status'];
                    $patedUserData = $iN->iN_GetUserDetails($payedSubscriberUidFk);
                    $payedSubscriberUserName = $patedUserData['i_username'] ?? NULL;
                    $payedSubscriberUserFullName = $patedUserData['i_user_fullname'] ?? NULL;
                    $myDateTime = date('d/m/Y', strtotime($subscribtionStarted));
                    $myDateTimeEnd = date('d/m/Y', strtotime($subscribtionEnd));
            ?>
                <!--ITEM-->
                <div class="i_tab_list_item flex_">
                    <div class="tab_detail_item"><?php echo iN_HelpSecure($payedSubscriptionID);?></div>
                    <div class="tab_detail_item truncated">
                        <a href="<?php echo iN_HelpSecure($base_url.$payedSubscriberUserName);?>">
                        <div class="tabing_non_justify flex_">
                            <div class="tab_subscriber_avatar">
                                <img src="<?php echo iN_HelpSecure($payedSubscriberAvatar);?>">
                            </div>
                            <div class="flex_ truncated"><?php echo iN_HelpSecure($payedSubscriberUserFullName);?></div>
                        </div>
                        </a>
                    </div>
                    <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($myDateTime);?></div>
                    <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($myDateTimeEnd);?></div>
                    <div class="tab_detail_item item_mobile">
                        <?php
                        if($paymentMethod == 'stripe'){
                            echo iN_HelpSecure($currencys[$payedCurrency]).$payedAmount;
                        }else{
                            echo round($payedAmount,2).'<span class="table_span">'.$LANG['points'].'</span>';
                        }?>
                    </div>
                    <div class="tab_detail_item">
                        <?php
                        if($paymentMethod == 'stripe'){
                            echo iN_HelpSecure($currencys[$payedCurrency]).number_format($netEarning, 2);
                        }else{
                            echo iN_HelpSecure($currencys[$payedCurrency]).number_format($userNetEarningC, 2);
                        }?>
                    </div>
                    <div class="tab_detail_item"><div class="tabing flex_ <?php echo iN_HelpSecure($subscriptionStatus);?>"><?php echo iN_HelpSecure($LANG[$subscriptionStatus]);?></div></div>
               </div>
               <!--/ITEM-->
            <?php }
               }else{
                  echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.html_entity_decode($iN->iN_SelectedMenuIcon('54')).'</div><div class="n_c_t">'.$LANG['nothing_to_show_about_subscribed_your_profile'].'</div></div>';
               }
            ?>
           </div>
           <!---->
        </div>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <?php if (ceil($totalSubscribers / $paginationLimit) > 0): ?>
            <ul class="pagination">
                <?php if ($pagep > 1): ?>
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=<?php echo iN_HelpSecure($pagep)-1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']);?></a></li>
                <?php endif; ?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($pagep-2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=<?php echo iN_HelpSecure($pagep)-2; ?>"><?php echo iN_HelpSecure($pagep)-2; ?></a></li><?php endif; ?>
                <?php if ($pagep-1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=<?php echo iN_HelpSecure($pagep)-1; ?>"><?php echo iN_HelpSecure($pagep)-1; ?></a></li><?php endif; ?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep+1 < ceil($totalSubscribers / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($pagep)+1; ?></a></li><?php endif; ?>
                <?php if ($pagep+2 < ceil($totalSubscribers / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=<?php echo iN_HelpSecure($pagep)+2; ?>"><?php echo iN_HelpSecure($pagep)+2; ?></a></li><?php endif; ?>

                <?php if ($pagep < ceil($totalSubscribers / $paginationLimit)-2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=<?php echo ceil($totalSubscribers / $paginationLimit); ?>"><?php echo ceil($totalSubscribers / $paginationLimit); ?></a></li>
                <?php endif; ?>

                <?php if ($pagep < ceil($totalSubscribers / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($LANG['next_page']);?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
     </div>
  </div>
</div> 
