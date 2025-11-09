<?php
$totalProduct = $iN->iN_UserTotalPurchasedProducts($userID);
$totalPages = ceil($totalProduct / $paginationLimit);
if (isset($_GET["page-id"])) {
    $pagep  = (int)$_GET["page-id"];
    if ($pagep <= 0) { $pagep = 1; }
}else{
    $pagep = 1;
}
?>
<?php
    if(isset($_GET['editProduct']) && $_GET['editProduct'] != '' && !empty($_GET['editProduct'])){
       $editProductID = isset($_GET['editProduct']) ? (int)$_GET['editProduct'] : 0;
       $checkProctExist = $iN->iN_CheckProductIDExist($userID, $editProductID);
       if($checkProctExist){
          include_once("editProduct.php");
       }else{
         header("Location: ".$base_url."settings?tab=myProducts");
       }
    }else{?>
<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('156'));?><?php echo iN_HelpSecure($LANG['myPurchasedProducts']);?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['all_products_you_sell']);?></div>
       <div class="i_moda_header_nt"><strong><?php echo iN_HelpSecure($LANG['all_processing_fee_note']);?></strong></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container">
           <!--PAYMENTS TABLE HEADER-->
           <div class="i_tab_header flex_">
               <div class="tab_item tab_detail_item_maxwidth"><?php echo iN_HelpSecure($LANG['id']);?></div>
               <div class="tab_item"><?php echo iN_HelpSecure($LANG['buyer_name']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['product_name']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['amount']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['payment_processing_fee']);?></div>
               <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['net_earning']);?></div>
           </div>
           <!--/PAYMENTS TABLE HEADER-->
           <!---->
           <div class="i_tab_list_item_container">
            <?php
               $ownProducts = $iN->iN_MyPurchasedProductList($userID, $paginationLimit, $pagep);
               if($ownProducts){
                  foreach($ownProducts as $prData){
                      $productID = $prData['pr_id'] ?? NULL;
                      $productName = $prData['pr_name'] ?? NULL;
                      $productFiles = $prData['pr_files'] ?? NULL;
                      $productCreatedTime = $prData['pr_created_time'] ?? NULL;
                      $productStatus = $prData['pr_status'] ?? NULL;
                      $productSeenTime = $prData['pr_seen_time'] ?? NULL;
                      $productSellTime = $prData['pr_number_of_sales'] ?? NULL;
                      $productSlugUrl = $prData['pr_name_slug'] ?? NULL;
                      $productPrice = $prData['pr_price'] ?? NULL;
                      $editProduct = $base_url.'settings?tab=myProducts&editProduct='.$productID;
                      $productUserEarning = $prData['user_earning'] ?? NULL;
                      $productAdminEarning = $prData['admin_earning'] ?? NULL;
                      $productSalesAmount = $prData['amount'] ?? NULL;
                      $payerUserID = $prData['payed_iuid_fk'] ?? NULL;
                      $customerData = $iN->iN_GetUserDetails($payerUserID);
                      $customerUsername = $customerData['i_username'] ?? NULL;
                      $customerFullName = $customerData['i_user_fullname'] ?? NULL;
                      $payerUserAvatar = $iN->iN_UserAvatar($payerUserID, $base_url);
            ?>
                <!--ITEM-->
                <div class="i_tab_list_item flex_">
                    <div class="tab_detail_item tab_detail_item_maxwidth"><?php echo iN_HelpSecure($productID);?></div>
                    <div class="tab_detail_item truncated">
                        <a href="<?php echo iN_HelpSecure($base_url).$customerUsername;?>">
                            <div class="tabing_non_justify flex_">
                                <div class="tab_subscriber_avatar">
                                    <img src="<?php echo iN_HelpSecure($payerUserAvatar);?>">
                                </div>
                                <div class="flex_ truncated"><?php echo iN_HelpSecure($customerFullName);?></div>
                            </div>
                        </a>
                    </div>
                    <div class="tab_detail_item truncated">
                        <a href="<?php echo iN_HelpSecure($base_url.'product/'.$productSlugUrl).'_'.$productID;?>">
                            <div class="flex_ truncated"><?php echo iN_HelpSecure($productName);?></div>
                        </a>
                    </div>
                    <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($currencys[$defaultCurrency].$productSalesAmount);?></div>
                    <div class="tab_detail_item item_mobile" id="pr_s_<?php echo iN_HelpSecure($productID);?>">
                        <?php echo iN_HelpSecure($currencys[$defaultCurrency].$productAdminEarning);?>
                    </div>
                    <div class="tab_detail_item item_mobile" id="pr_s_<?php echo iN_HelpSecure($productID);?>">
                        <?php echo iN_HelpSecure($currencys[$defaultCurrency].$productUserEarning);?>
                    </div>
               </div>
               <!--/ITEM-->
            <?php }
               }else {
                       echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.html_entity_decode($iN->iN_SelectedMenuIcon('54')).'</div><div class="n_c_t">'.$LANG['nothing_to_show_about_product'].'</div></div>';
               }
            ?>
           </div>
           <!---->
        </div>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <?php if (ceil($totalProduct / $paginationLimit) > 1): ?>
            <ul class="pagination">
                <?php if ($pagep > 1): ?>
                <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=<?php echo iN_HelpSecure($pagep)-1 ?>"><?php echo iN_HelpSecure($LANG['preview_page']);?></a></li>
                <?php endif; ?>

                <?php if ($pagep > 3): ?>
                <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=1">1</a></li>
                <li class="dots">...</li>
                <?php endif; ?>

                <?php if ($pagep-2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=<?php echo iN_HelpSecure($pagep)-2; ?>"><?php echo iN_HelpSecure($pagep)-2; ?></a></li><?php endif; ?>
                <?php if ($pagep-1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=<?php echo iN_HelpSecure($pagep)-1; ?>"><?php echo iN_HelpSecure($pagep)-1; ?></a></li><?php endif; ?>

                <li class="currentpage active"><a  class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                <?php if ($pagep+1 < ceil($totalProduct / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($pagep)+1; ?></a></li><?php endif; ?>
                <?php if ($pagep+2 < ceil($totalProduct / $paginationLimit)+1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=<?php echo iN_HelpSecure($pagep)+2; ?>"><?php echo iN_HelpSecure($pagep)+2; ?></a></li><?php endif; ?>

                <?php if ($pagep < ceil($totalProduct / $paginationLimit)-2): ?>
                <li class="dots">...</li>
                <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=<?php echo ceil($totalProduct / $paginationLimit); ?>"><?php echo ceil($totalProduct / $paginationLimit); ?></a></li>
                <?php endif; ?>

                <?php if ($pagep < ceil($totalProduct / $paginationLimit)): ?>
                <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&page-id=<?php echo iN_HelpSecure($pagep)+1; ?>"><?php echo iN_HelpSecure($LANG['next_page']);?></a></li>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
     </div>
  </div>
</div>
<?php }?>
