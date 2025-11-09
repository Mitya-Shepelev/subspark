<?php
$totalPages = ceil($totalSubscriptions / $paginationLimit);
if (isset($_GET["page-id"])) {
	$pagep = (int)$_GET["page-id"];
	if ($pagep <= 0) { $pagep = 1; }
} else {
	$pagep = 1;
}
?>
<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('43')); ?><?php echo iN_HelpSecure($LANG['subscriptions']) . '(' . $totalSubscriptions . ')'; ?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['list_subscribed_not']); ?></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container">
         <!--PAYMENTS TABLE HEADER-->
         <div class="i_tab_header flex_">
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['id']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['paid_to']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['date']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['ends_at']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['amount']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['action']);?></div>
           </div>
           <!--/PAYMENTS TABLE HEADER-->
           <!---->
           <div class="i_tab_list_item_container">
       <?php
$payedActiveSubscriptions = $iN->iN_PaymentsSubscriptionsListPage($userID, $paginationLimit, $pagep);
if ($payedActiveSubscriptions) {
	foreach ($payedActiveSubscriptions as $payedSub) {
		$payedSubscriptionID = $payedSub['subscription_id'] ?? NULL;
		$payedSubscriberUidFk = $payedSub['iuid_fk'] ?? NULL;
		$payedSubscribedUidFk = $payedSub['subscribed_iuid_fk'] ?? NULL;
		$payedSubscriberPlanID = $payedSub['plan_id'] ?? NULL;
		$payedSubscriberAvatar = $iN->iN_UserAvatar($payedSubscribedUidFk, $base_url);
		$subscribtionStarted = $payedSub['created'] ?? NULL;
        $subscribtionFinishing = $payedSub['plan_period_end'] ?? NULL;
		$payedAmount = $payedSub['plan_amount'] ?? NULL;
        $planInterval = $payedSub['plan_interval'] ?? NULL;
        $paymentMethod = $payedSub['payment_method'] ?? NULL;
		$payedCurrency = strtoupper($payedSub['plan_amount_currency']);
		$adminEarning = $payedSub['admin_earning'] ?? NULL;
		$netEarning = $payedAmount - $adminEarning;
		$subscriptionStatus = $payedSub['status'];
		$patedUserData = $iN->iN_GetUserDetails($payedSubscribedUidFk);
		$payedSubscriberUserName = $patedUserData['i_username'] ?? NULL;
		$payedSubscriberUserFullName = $patedUserData['i_user_fullname'] ?? NULL;
		$myDateTime = date('d/m/Y', strtotime($subscribtionStarted));
        $myDateTimeLast = date('d/m/Y', strtotime($subscribtionFinishing));
		$getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $payedSubscribedUidFk);
		if ($getFriendStatusBetweenTwoUser == 'flwr') {
			$flwrBtn = 'i_btn_like_item_flw f_p_follow';
			$flwBtnIconText =  $LANG['unfollow'];
		} else if ($getFriendStatusBetweenTwoUser == 'subscriber') {
			$flwrBtn = 'i_btn_unsubscribe';
			$flwBtnIconText =   $LANG['unsubscribe'];
		} else {
			$flwrBtn = 'i_btn_like_item free_follow';
			$flwBtnIconText =   $LANG['follow'];
		}
      if($paymentMethod == 'point'){
         $subButnClass = 'unSubUP';
      }else{
         $subButnClass = 'unSubU';
      }
		?>
      <!--ITEM-->
      <div class="i_tab_list_item flex_">
            <div class="tab_detail_item"><?php echo iN_HelpSecure($payedSubscriptionID);?></div>
            <div class="tab_detail_item truncated">
               <a href="<?php echo iN_HelpSecure($base_url).$payedSubscriberUserName;?>">
               <div class="tabing_non_justify flex_">
                     <div class="tab_subscriber_avatar">
                        <img src="<?php echo iN_HelpSecure($payedSubscriberAvatar);?>">
                     </div>
                     <div class="flex_ truncated"><?php echo iN_HelpSecure($payedSubscriberUserFullName);?></div>
               </div>
               </a>
            </div>
            <div class="tab_detail_item item_mobile"><?php echo $myDateTime;?></div>
            <div class="tab_detail_item item_mobile"><?php echo $myDateTimeLast;?></div>
            <div class="tab_detail_item item_mobile">
               <?php if($paymentMethod == 'point'){?>
                  <div class="i_sub_box_unm_interval"><?php echo iN_HelpSecure($LANG[$planInterval]).': '.round($payedAmount,2).$LANG['points'];?></div>
               <?php }else{?>
                  <div class="i_sub_box_unm_interval"><?php echo iN_HelpSecure($LANG[$planInterval]).': '.$currencys[$defaultCurrency].$payedAmount;?></div>
               <?php } ?>
            </div>
            <div class="tab_detail_item item_mobile"><div class="i_sub_flw"><div class="i_follow flex_ tabing i_fw<?php echo iN_HelpSecure($payedSubscribedUidFk); ?> <?php echo html_entity_decode($flwrBtn); ?> transition <?php echo iN_HelpSecure($subButnClass);?> " id="i_btn_like_item" data-u="<?php echo iN_HelpSecure($payedSubscribedUidFk); ?>"><?php echo html_entity_decode($flwBtnIconText); ?></div></div></div>
      </div>
      <!--/ITEM-->
        <?php }} else {echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div><div class="n_c_t">' . $LANG['no_one_you_subscribed'] . '</div></div>';}?>
        </div>
         <!---->
      </div>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <?php if (ceil($totalSubscriptions / $paginationLimit) > 0): ?>
            <ul class="pagination">
                <?php if ($pagep > 1): ?>
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=<?php echo iN_HelpSecure($pagep) - 1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a></li>
                <?php endif;?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif;?>

                <?php if ($pagep - 2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a></li><?php endif;?>
                <?php if ($pagep - 1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a></li><?php endif;?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep + 1 < ceil($totalSubscriptions / $paginationLimit) + 1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a></li><?php endif;?>
                <?php if ($pagep + 2 < ceil($totalSubscriptions / $paginationLimit) + 1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>"><?php echo iN_HelpSecure($pagep) + 2; ?></a></li><?php endif;?>

                <?php if ($pagep < ceil($totalSubscriptions / $paginationLimit) - 2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=<?php echo ceil($totalSubscriptions / $paginationLimit); ?>"><?php echo ceil($totalSubscriptions / $paginationLimit); ?></a></li>
                <?php endif;?>

                <?php if ($pagep < ceil($totalSubscriptions / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=subscriptions&page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a></li>
                <?php endif;?>
            </ul>
        <?php endif;?>
     </div>
  </div>
</div> 
