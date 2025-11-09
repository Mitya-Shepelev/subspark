<?php
$totalProduct = $iN->iN_UserTotalProducts($userID);
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
          <div class="i_settings_wrapper_in inTable">
             <div class="i_settings_wrapper_title">
               <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('158'));?><?php echo iN_HelpSecure($LANG['myProducts']);?></div>
               <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['all_products_you_create']);?></div>
            </div>
            <div class="i_settings_wrapper_items">
               <div class="i_tab_container">
                   <!--PAYMENTS TABLE HEADER-->
                   <div class="i_tab_header flex_">
                       <div class="tab_item tab_detail_item_maxwidth"><?php echo iN_HelpSecure($LANG['id']);?></div>
                       <div class="tab_item"><?php echo iN_HelpSecure($LANG['product_name']);?></div>
                       <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['product_image']);?></div>
                       <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['number_of_sales']);?></div>
                       <div class="tab_item"><?php echo iN_HelpSecure($LANG['product_views']);?></div>
                       <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['amount']);?></div>
                       <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['status']);?></div>
                       <div class="tab_item item_mobile"><?php echo iN_HelpSecure($LANG['action']);?></div>
                   </div>
                   <!--/PAYMENTS TABLE HEADER-->
                   <!---->
                   <div class="i_tab_list_item_container">
                    <?php
                       $ownProducts = $iN->iN_ProductLists($userID, $paginationLimit, $pagep);
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
                              $productType = $prData['product_type'] ?? NULL;
                              $p__style = $productType;
                                if($productType == 'scratch'){
                                    $productType = 'simple_product';
                                    $p__style = 'scratch';
                                }
                              $editProduct = $base_url.'settings?tab=myProducts&editProduct='.$productID;
                    ?>
                        <!--ITEM-->
                        <div class="i_tab_list_item flex_">
                            <div class="tab_detail_item tab_detail_item_maxwidth"><?php echo iN_HelpSecure($productID);?></div>
                            <div class="tab_detail_item truncated">
                                <a href="<?php echo iN_HelpSecure($base_url.'product/'.$productSlugUrl).'_'.$productID;?>">
                                    <div class="flex_ truncated"><?php echo iN_HelpSecure($productName);?></div>
                                </a>
                            </div>
                            <div class="tab_detail_item item_mobile pr_im table_text_align_left">
                                <?php
                                $trimValue = rtrim($productFiles, ',');
                                $explodeFiles = explode(',', $trimValue);
                                $explodeFiles = array_unique($explodeFiles);
                                foreach ($explodeFiles as $explodeProductFile) {
                                    $productFileData = $iN->iN_GetUploadedFileDetails($explodeProductFile);
                                    if ($productFileData) {
                                        $productUploadID = $productFileData['upload_id'];
                                        $productfileExtension = $productFileData['uploaded_file_ext'];
                                        $productfilePath = $productFileData['uploaded_file_path'];
                                        $productTumbnailfilePath = $productFileData['upload_tumbnail_file_path'];
                                        if (function_exists('storage_public_url')) {
                                            $productImageUrl = storage_public_url($productTumbnailfilePath);
                                        } else {
                                            $productImageUrl = $base_url . $productTumbnailfilePath;
                                        }
                                ?>
                                <img src="<?php echo $productImageUrl;?>">
                                <?php } } ?>
                            </div>
                            <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($iN->iN_TotalProductSell($productID));?></div>
                            <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($iN->iN_TotalProductSeen($productID));?></div>
                            <div class="tab_detail_item item_mobile"><?php echo iN_HelpSecure($currencys[$defaultCurrency].$productPrice);?></div>
                            <div class="tab_detail_item item_mobile" id="pr_s_<?php echo iN_HelpSecure($productID);?>">
                                <div class="i_sub_not_check_box type_news relativePosition <?php echo $p__style;?>">
                                    <?php echo iN_HelpSecure($LANG[$productType]);?>
                                </div>
                                <div class="i_sub_not_check_box relativePosition">
                                    <label class="el-switch el-switch-yellow">
                                        <input type="checkbox" name="pr_<?php echo iN_HelpSecure($productID);?>" class="chmdProd" id="pr_i_<?php echo iN_HelpSecure($productID);?>" data-id="<?php echo iN_HelpSecure($productID);?>" <?php echo iN_HelpSecure($productStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"';?>>
                                        <span class="el-switch-style"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="tab_detail_item item_mobile">
                                <!---->
                                <div class="tabing_non_justify">
                                    <div class="delprod border_one transition tabing flex_" id="<?php echo iN_HelpSecure($productID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')).$LANG['delete'];?></div>
                                    <div class="edtprod border_one transition tabing flex_" id="<?php echo iN_HelpSecure($productID);?>"><a class="tabing flex_" href="<?php echo iN_HelpSecure($editProduct);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')).$LANG['edit_user_infos'];?></a></div>
                                </div>
                                <!---->
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
