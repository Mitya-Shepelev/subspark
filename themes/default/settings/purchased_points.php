<?php
$totalPurchasedPoints = $iN->iN_UserTotalPointPurchase($userID);
$totalPages = ceil($totalPurchasedPoints / $paginationLimit);
if (isset($_GET["page-id"])) {
    $pagep  = (int)$_GET["page-id"];
    if ($pagep <= 0) { $pagep = 1; }
}else{
    $pagep = 1;
}
?>
<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?><?php echo iN_HelpSecure($LANG['purchased_point_list']);?></div>
       <div class="i_moda_header_nt"><?php echo html_entity_decode($LANG['purchase_point_list_not']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container">
           <!--PAYMENTS TABLE HEADER-->
           <div class="i_tab_header flex_">
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['id']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['type_of_point_purchased']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['date']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['amount']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['status']);?></div>
           </div>
           <!--/PAYMENTS TABLE HEADER-->
           <!---->
           <div class="i_tab_list_item_container">
            <?php
               $paymentHistory = $iN->iN_YourPointPaymentsHistoryList($userID, $paginationLimit, $pagep);
               if($paymentHistory){
                  foreach($paymentHistory as $pay){
                    $paymentDataID = $pay['payment_id'] ?? NULL;
                    $paymentDataPayerUserID = $pay['payer_iuid_fk'] ?? NULL;
                    $paymentDataOrderKey = $pay['order_key'] ?? NULL;
                    $paymentDataPaymentType = $pay['payment_type'] ?? NULL;
                    $paymentDataPaymentOption = $pay['payment_option'] ?? NULL;
                    $paymentDataPaymentTime = $pay['payment_time'] ?? NULL;
                    $paymentDataPaymentStatus = $pay['payment_status'] ?? NULL;
                    $paymentDataCreditPlanID = $pay['credit_plan_id'] ?? NULL;
                    $getPlanData = $iN->GetPlanDetails($paymentDataCreditPlanID);
                    $pointPurchased = $getPlanData['plan_name_key'] ?? NULL;
                    $planPointAmount = $getPlanData['plan_amount'] ?? NULL;
                    $planAmount = $getPlanData['amount'] ?? NULL;
                    if($paymentDataPaymentStatus == 'pending'){
                        $pStatu = $LANG['payment_waiting_to_be_complete'];
                    }else if($paymentDataPaymentStatus == 'declined'){
                        $pStatu = $LANG['point_payment_cancelled'];
                    }else{
                        $pStatu = $LANG['point_payment_complete'];
                    }
            ?>
                <!--ITEM-->
                <div class="i_tab_list_item flex_">
                    <div class="tab_detail_item"><?php echo iN_HelpSecure($paymentDataID);?></div>
                    <div class="tab_detail_item truncated">
                       <?php echo isset($LANG[$pointPurchased]) ? $LANG[$pointPurchased] : $pointPurchased;?>
                    </div>
                    <div class="tab_detail_item item_mobile"><?php echo gmdate("d/m/Y", $paymentDataPaymentTime);?></div>
                    <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($currencys[$defaultCurrency]).$planAmount;?></div>
                    <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($pStatu);?></div>
               </div>
               <!--/ITEM-->
            <?php }
               }else{
                   echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.html_entity_decode($iN->iN_SelectedMenuIcon('54')).'</div><div class="n_c_t">'.$LANG['nothing_to_show_about_payment_history'].'</div></div>';
               }
            ?>
           </div>
           <!---->
        </div>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <?php if (ceil($totalPurchasedPoints / $paginationLimit) > 0): ?>
            <ul class="pagination">
                <?php if ($pagep > 1): ?>
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=<?php echo iN_HelpSecure($pagep)-1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']);?></a></li>
                <?php endif; ?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($pagep-2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=<?php echo iN_HelpSecure($pagep)-2; ?>"><?php echo iN_HelpSecure($pagep)-2; ?></a></li><?php endif; ?>
                <?php if ($pagep-1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=<?php echo iN_HelpSecure($pagep)-1; ?>"><?php echo iN_HelpSecure($pagep)-1; ?></a></li><?php endif; ?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep+1 < ceil($totalPurchasedPoints / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($pagep)+1; ?></a></li><?php endif; ?>
                <?php if ($pagep+2 < ceil($totalPurchasedPoints / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=<?php echo iN_HelpSecure($pagep)+2; ?>"><?php echo iN_HelpSecure($pagep)+2; ?></a></li><?php endif; ?>

                <?php if ($pagep < ceil($totalPurchasedPoints / $paginationLimit)-2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=<?php echo ceil($totalPurchasedPoints / $paginationLimit); ?>"><?php echo ceil($totalPurchasedPoints / $paginationLimit); ?></a></li>
                <?php endif; ?>

                <?php if ($pagep < ceil($totalPurchasedPoints / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($LANG['next_page']);?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
     </div>
  </div>
</div>
