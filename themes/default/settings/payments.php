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
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?><?php echo iN_HelpSecure($LANG['payments']);?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['history_of_all_payments_received']);?></div>
       <div class="i_moda_header_nt"><strong><?php echo iN_HelpSecure($LANG['all_processing_fee_note']);?></strong></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container">
           <!--PAYMENTS TABLE HEADER-->
           <div class="i_tab_header flex_">
               <div class="tab_item tab_detail_item_maxwidth"><?php echo iN_HelpSecure($LANG['id']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['paid_by']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['date']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['amount']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['earning']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['pay_type']);?></div>
           </div>
           <!--/PAYMENTS TABLE HEADER-->
           <!---->
           <div class="i_tab_list_item_container">
            <?php
               $paymentHistory = $iN->iN_PaymentsList($userID, $paginationLimit, $pagep);
               if($paymentHistory){
                  foreach($paymentHistory as $pay){
                    $paymentDataID = $pay['payment_id'] ?? NULL;
                    $paymentDataPayerUserID = $pay['payer_iuid_fk'] ?? NULL;
                    $paymentDataPayedUserID = $pay['payed_iuid_fk'] ?? NULL;
                    $paymentDataPayedProfileID = $pay['payed_profile_id_fk'] ?? NULL;
                    $paymentDataOrderKey = $pay['order_key'] ?? NULL;
                    $paymentDataPaymentType = $pay['payment_type'] ?? NULL;
                    $paymentDataPaymentOption = $pay['payment_option'] ?? NULL;
                    $paymentDataPaymentTime = $pay['payment_time'] ?? NULL;
                    $paymentDataPaymentStatus = $pay['payment_status'] ?? NULL;
                    $paymentDataPaymentAmount = $pay['amount'] ?? NULL;
                    $paymentDataPaymentFee = $pay['fee'] ?? NULL;
                    $paymentDataPaymentAdminEarning = $pay['admin_earning'] ?? NULL;
                    $paymentDataPaymentUserEarning = $pay['user_earning'];
                    $payerUserAvatar = $iN->iN_UserAvatar($paymentDataPayerUserID, $base_url);
                    $payerUserData = $iN->iN_GetUserDetails($paymentDataPayerUserID);
                    $payerUserName = $payerUserData['i_username'] ?? NULL;
                    $payerUserFullName = $payerUserData['i_user_fullname'] ?? NULL;
            ?>
                <!--ITEM-->
                <div class="i_tab_list_item flex_">
                    <div class="tab_detail_item tab_detail_item_maxwidth"><?php echo iN_HelpSecure($paymentDataID);?></div>
                    <div class="tab_detail_item truncated">
                        <a href="<?php echo iN_HelpSecure($base_url).$payerUserName;?>">
                        <div class="tabing_non_justify flex_">
                            <div class="tab_subscriber_avatar">
                                <img src="<?php echo iN_HelpSecure($payerUserAvatar);?>">
                            </div>
                            <div class="flex_ truncated"><?php echo iN_HelpSecure($payerUserFullName);?></div>
                        </div>
                        </a>
                    </div>
                    <div class="tab_detail_item item_mobile"><?php echo gmdate("d/m/Y", $paymentDataPaymentTime);?></div>
                    <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($currencys[$defaultCurrency].$paymentDataPaymentAmount);?></div>
                    <div class="tab_detail_item">
                        <?php
                            $earning = is_numeric($paymentDataPaymentUserEarning) ? $paymentDataPaymentUserEarning : 0;
                            echo iN_HelpSecure($currencys[$defaultCurrency]) . number_format($earning, 2);
                        ?>
                    </div>
                    <div class="tab_detail_item item_mobile"><span class="flex_ tabing ty_<?php echo iN_HelpSecure($paymentDataPaymentType);?>"><?php echo iN_HelpSecure($PAYMENTTYPES[$paymentDataPaymentType]);?></span></div>
               </div>
               <!--/ITEM-->
            <?php }
               }else {
                   echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.html_entity_decode($iN->iN_SelectedMenuIcon('54')).'</div><div class="n_c_t">'.$LANG['nothing_to_show_about_payment_history'].'</div></div>';
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
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=<?php echo iN_HelpSecure($pagep)-1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']);?></a></li>
                <?php endif; ?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($pagep-2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=<?php echo iN_HelpSecure($pagep)-2; ?>"><?php echo iN_HelpSecure($pagep)-2; ?></a></li><?php endif; ?>
                <?php if ($pagep-1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=<?php echo iN_HelpSecure($pagep)-1; ?>"><?php echo iN_HelpSecure($pagep)-1; ?></a></li><?php endif; ?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep+1 < ceil($totalSubscribers / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($pagep)+1; ?></a></li><?php endif; ?>
                <?php if ($pagep+2 < ceil($totalSubscribers / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=<?php echo iN_HelpSecure($pagep)+2; ?>"><?php echo iN_HelpSecure($pagep)+2; ?></a></li><?php endif; ?>

                <?php if ($pagep < ceil($totalSubscribers / $paginationLimit)-2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=<?php echo ceil($totalSubscribers / $paginationLimit); ?>"><?php echo ceil($totalSubscribers / $paginationLimit); ?></a></li>
                <?php endif; ?>

                <?php if ($pagep < ceil($totalSubscribers / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($LANG['next_page']);?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
     </div>
  </div>
</div>
