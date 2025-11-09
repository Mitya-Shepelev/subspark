<?php
$totalPages = ceil($totalPointPayments / $paginationLimit);
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
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?><?php echo iN_HelpSecure($LANG['my_payments']);?></div>
       <div class="i_moda_header_nt ntSt"><?php echo html_entity_decode($LANG['history_of_all_payments_you_made']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container">
           <!--PAYMENTS TABLE HEADER-->
           <div class="i_tab_header flex_">
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['id']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['paid_to']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['date']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['amount']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['pay_type']);?></div>
           </div>
           <!--/PAYMENTS TABLE HEADER-->
           <!---->
           <div class="i_tab_list_item_container">
            <?php
               $paymentHistory = $iN->iN_YourPaymentsList($userID, $paginationLimit, $pagep);
               if($paymentHistory){
                  foreach($paymentHistory as $pay){
                    $paymentDataID = $pay['payment_id'] ?? null;
                    $paymentDataPayerUserID = $pay['payer_iuid_fk'] ?? null;
                    $paymentDataPayedUserID = $pay['payed_iuid_fk'] ?? null;
                    $paymentDataPayedProfileID = $pay['payed_profile_id_fk'] ?? null;
                    $payedPostIDfk = $pay['payed_post_id_fk']  ?? null;
                    $paymentDataOrderKey = $pay['order_key'] ?? null;
                    $paymentDataPaymentType = $pay['payment_type'] ?? null;
                    $paymentDataPaymentOption = $pay['payment_option'] ?? null;
                    $paymentDataPaymentTime = $pay['payment_time'] ?? null;
                    $paymentDataPaymentStatus = $pay['payment_status'] ?? null;
                    $paymentDataPaymentAmount = $pay['amount'] ?? null;
                    $paymentDataPaymentFee = $pay['fee'] ?? null;
                    $paymentDataPaymentAdminEarning = $pay['admin_earning'] ?? null;
                    $paymentDataPaymentUserEarning = $pay['user_earning'] ?? null;
                    $payerUserAvatar = $iN->iN_UserAvatar($paymentDataPayerUserID, $base_url);
                    $payerUserData = $iN->iN_GetUserDetails($paymentDataPayedUserID);
                    $payerUserName = $iN->iN_GetUserName($paymentDataPayerUserID);
                    $payerUserFullName = $iN->iN_UserFullName($paymentDataPayerUserID);
                    $postURL = '';
                    if(isset($payedPostIDfk)){
                       $postData = $iN->iN_GetAllPostDetails($payedPostIDfk);
                       if($postData){
                           $postURL = $postData['url_slug'].'_'.$payedPostIDfk;
                       }else{
                           $postURL = '';
                       }
                    }
            ?>
                <!--ITEM-->
                <div class="i_tab_list_item flex_">
                    <div class="tab_detail_item"><?php echo iN_HelpSecure($paymentDataID);?></div>
                    <div class="tab_detail_item truncated">
                        <?php if($payedPostIDfk){?>
                            <?php if($postURL){ ?>
                                <a href="<?php echo iN_HelpSecure($base_url).'post/'.$postURL;?>">
                                    <div class="tabing_non_justify flex_">
                                        <div class="tab_subscriber_avatar">
                                            <img src="<?php echo iN_HelpSecure($payerUserAvatar);?>">
                                        </div>
                                        <div class="flex_ truncated"><?php echo iN_HelpSecure($LANG['see_post']);?></div>
                                    </div>
                                </a>
                            <?php }else{ ?>
                                    <div class="tabing_non_justify flex_">
                                        <div class="tab_subscriber_avatar">
                                            <img src="<?php echo iN_HelpSecure($payerUserAvatar);?>">
                                        </div>
                                        <div class="flex_ truncated"><?php echo iN_HelpSecure($LANG['no_longer_available_this_post']);?></div>
                                    </div>
                            <?php } ?>
                        <?php }else{ ?>
                        <a href="<?php echo iN_HelpSecure($base_url).$payerUserName;?>">
                        <div class="tabing_non_justify flex_">
                            <div class="tab_subscriber_avatar">
                                <img src="<?php echo iN_HelpSecure($payerUserAvatar);?>">
                            </div>
                            <div class="flex_ truncated"><?php echo iN_HelpSecure($payerUserFullName);?></div>
                        </div>
                        </a>
                        <?php } ?>
                    </div>
                    <div class="tab_detail_item item_mobile"><?php echo gmdate("d/m/Y", $paymentDataPaymentTime);?></div>
                    <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($currencys[$defaultCurrency]).$paymentDataPaymentAmount;?></div>
                    <div class="tab_detail_item item_mobile"><span class="flex_ tabing ty_<?php echo iN_HelpSecure($paymentDataPaymentType);?>"><?php echo iN_HelpSecure($PAYMENTTYPES[$paymentDataPaymentType]);?></span></div>
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
        <?php if (ceil($totalPointPayments / $paginationLimit) > 0): ?>
            <ul class="pagination">
                <?php if ($pagep > 1): ?>
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=<?php echo iN_HelpSecure($pagep)-1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']);?></a></li>
                <?php endif; ?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($pagep-2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=<?php echo iN_HelpSecure($pagep)-2; ?>"><?php echo iN_HelpSecure($pagep)-2; ?></a></li><?php endif; ?>
                <?php if ($pagep-1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=<?php echo iN_HelpSecure($pagep)-1; ?>"><?php echo iN_HelpSecure($pagep)-1; ?></a></li><?php endif; ?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep+1 < ceil($totalPointPayments / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($pagep)+1; ?></a></li><?php endif; ?>
                <?php if ($pagep+2 < ceil($totalPointPayments / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=<?php echo iN_HelpSecure($pagep)+2; ?>"><?php echo iN_HelpSecure($pagep)+2; ?></a></li><?php endif; ?>

                <?php if ($pagep < ceil($totalPointPayments / $paginationLimit)-2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=<?php echo ceil($totalPointPayments / $paginationLimit); ?>"><?php echo ceil($totalPointPayments / $paginationLimit); ?></a></li>
                <?php endif; ?>

                <?php if ($pagep < ceil($totalPointPayments / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($LANG['next_page']);?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
     </div>
  </div>
</div>
