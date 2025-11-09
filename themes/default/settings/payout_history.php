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
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('85'));?><?php echo iN_HelpSecure($LANG['payout_history']);?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['history_of_all_payouts_received']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container">
           <!--PAYMENTS TABLE HEADER-->
           <div class="i_tab_header border_top flex_">
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['amount']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['date']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['payout_method']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['type']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['status']);?></div>
           </div>
           <!--/PAYMENTS TABLE HEADER-->
           <!---->
           <div class="i_tab_list_item_container">
            <?php
               $payoutHistoryData = $iN->iN_PayoutHistory($userID, $paginationLimit, $pagep);
               if($payoutHistoryData){
                  foreach($payoutHistoryData as $payoutData){
                    $payoutUserID = $payoutData['iuid_fk'];
                    $payoutID = $payoutData['payout_id'];
                    $payoutAmount = $payoutData['amount'];
                    $payoutTime = $payoutData['payout_time'];
                    $payoutMethod = $payoutData['method'];
                    $payoutStatus = $payoutData['status'];
                    $patmentType = $payoutData['payment_type'];
                    $myDateTime = date('d/m/Y', $payoutTime);
            ?>
                <!--ITEM-->
                <div class="i_tab_list_item flex_">
                    <div class="tab_detail_item"><?php echo iN_HelpSecure($currencys[$defaultCurrency].$payoutAmount);?></div>
                    <div class="tab_detail_item"><?php echo iN_HelpSecure($myDateTime);?></div>
                    <div class="tab_detail_item"><?php echo iN_HelpSecure($payoutMethod);?></div>
                    <div class="tab_detail_item"><?php echo iN_HelpSecure($LANG[$patmentType]);?></div>
                    <!--<div class="tab_detail_item">invoice</div>-->
                    <div class="tab_detail_item"><div class="tabing flex_ <?php echo iN_HelpSecure($payoutStatus);?>"><?php echo iN_HelpSecure($LANG[$payoutStatus]);?></div></div>
               </div>
               <!--/ITEM-->
            <?php }
               }else{
                  echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.html_entity_decode($iN->iN_SelectedMenuIcon('54')).'</div><div class="n_c_t">'.$LANG['nothing_to_show_about_payout_history'].'</div></div>';
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
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=<?php echo iN_HelpSecure($pagep)-1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']);?></a></li>
                <?php endif; ?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($pagep-2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=<?php echo iN_HelpSecure($pagep)-2; ?>"><?php echo iN_HelpSecure($pagep)-2; ?></a></li><?php endif; ?>
                <?php if ($pagep-1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=<?php echo iN_HelpSecure($pagep)-1; ?>"><?php echo iN_HelpSecure($pagep)-1; ?></a></li><?php endif; ?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep+1 < ceil($totalSubscribers / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($pagep)+1; ?></a></li><?php endif; ?>
                <?php if ($pagep+2 < ceil($totalSubscribers / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=<?php echo iN_HelpSecure($pagep)+2; ?>"><?php echo iN_HelpSecure($pagep)+2; ?></a></li><?php endif; ?>

                <?php if ($pagep < ceil($totalSubscribers / $paginationLimit)-2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=<?php echo ceil($totalSubscribers / $paginationLimit); ?>"><?php echo ceil($totalSubscribers / $paginationLimit); ?></a></li>
                <?php endif; ?>

                <?php if ($pagep < ceil($totalSubscribers / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($LANG['next_page']);?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
     </div>
  </div>
</div>
