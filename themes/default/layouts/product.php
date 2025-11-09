<div class="product_wrapper flex_">
<?php
    $productData = $iN->iN_GetProductDetailsByID($GetTheProductIDFromUrl);
    if($productData){
        $uProductID = $productData['pr_id'];
        $checkProductPurchasedBefore = '';
        if($logedIn == 1){
            $checkProductPurchasedBefore = $iN->iN_CheckItemPurchasedBefore($userID, $uProductID);
        }
        $visitorIp = $iN->iN_GetIPAddress();
        if(!empty($visitorIp) && isset($visitorIp) && $visitorIp != ''){
            $lUserID = isset($userID) ? $userID : NULL;
            $iN->iN_InsertVisitor($visitorIp,$uProductID,$lUserID);
        }
        $uProductName = $productData['pr_name'] ?? null;
        $uProductPrice = $productData['pr_price'] ?? null;
        $uProductFiles = $productData['pr_files'] ?? null;
        $uProductDownloadableFiles = $productData['pr_downlodable_files'] ?? null;
        $uProductDescription = $productData['pr_desc'] ?? null;
        $uProductDescriptionInfo = $productData['pr_desc_info'] ?? null;
        $uProductTime = $productData['pr_created_time'] ?? null;
        $uProductOwnerID = $productData['iuid_fk'] ?? null;
        $uProductStatus = $productData['pr_status'] ?? null;
        $uProductSeenTime = $productData['pr_seen_time'] ?? null;
        $uProductNumberOfSales = $productData['pr_number_of_sales'] ?? null;
        $uProductSlug = $productData['pr_name_slug'] ?? null;
        $uProductType = $productData['product_type'] ?? null;
        $p__style = $uProductType;
        $thisProduct = $uProductType;
        if($uProductType == 'scratch'){
            $uProductType = 'simple_product';
            $p__style = 'scratch';
            $thisProduct = 'all';
        }
        $uProductSlotsNumber = $productData['pr_slots_number'] ?? null;
        $uPTime = date('Y-m-d H:i:s',$uProductTime);
        $uSlugUrl = $base_url.'product/'.$uProductSlug.'_'.$uProductID;
        $userProdocutOwnerUsername = $productData['i_username'] ?? null;
        $userPostOwnerUserFullName = $productData['i_user_fullname'] ?? null;
        $userPostOwnerUserGender = $productData['user_gender'] ?? null;
        $userProfileFrame = $productData['user_frame'] ?? null;
        if($fullnameorusername == 'no'){
            $userPostOwnerUserFullName = $userProdocutOwnerUsername;
         }
        $userPostOwnerUserAvatar = $iN->iN_UserAvatar($uProductOwnerID, $base_url);
        $userPostUserVerifiedStatus = $productData['user_verified_status'] ?? null;
        if($userPostOwnerUserGender == 'male'){
            $publisherGender = '<div class="i_plus_g">'.$iN->iN_SelectedMenuIcon('12').'</div>';
        }else if($userPostOwnerUserGender == 'female'){
            $publisherGender = '<div class="i_plus_gf">'.$iN->iN_SelectedMenuIcon('13').'</div>';
        }else if($userPostOwnerUserGender == 'couple'){
            $publisherGender = '<div class="i_plus_g">'.$iN->iN_SelectedMenuIcon('58').'</div>';
        }
        $userVerifiedStatus = '';
        if($userPostUserVerifiedStatus == '1'){
            $userVerifiedStatus = $iN->iN_SelectedMenuIcon('11');
        }
    ?>
<div class="product_details_left">
    <div class="product_images_container">
        <!----->
        <?php
            $trimValue = rtrim($uProductFiles, ',');
            $explodeFiles = explode(',', $trimValue);
            $explodeFiles = array_unique($explodeFiles);
            $countExplodedFiles = $iN->iN_CheckCountFile($uProductFiles);
            foreach ($explodeFiles as $pFile) {
                $fileData = $iN->iN_GetUploadedFileDetails($pFile);
                if($fileData){
                    $fileUploadID = $fileData['upload_id'] ?? null;
                    $fileExtension = $fileData['uploaded_file_ext'] ?? null;
                    $filePath = $fileData['uploaded_file_path'] ?? null;
                    $filePathTumbnail = $fileData['upload_tumbnail_file_path'] ?? null;
                    if($fileExtension == 'mp4'){
                        if (function_exists('storage_public_url')) {
                            $tumbFile = storage_public_url($filePathTumbnail);
                            $filePathUrl = storage_public_url($filePath);
                        } else {
                            $tumbFile =  $base_url . $filePathTumbnail;
                            $filePathUrl = $base_url . $filePath;
                        }
                        echo '
                        <div class="nonePoint" id="video' . $fileUploadID . '">
                            <video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none">
                                <source src="' . $filePathUrl . '" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        ';
                        $srcPath = 'data-poster="' . $tumbFile . '" data-html="#video' . $fileUploadID . '"';
                    }
                }
            }
        ?>
        <!----->
        <!-- Swiper -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper" id="swiperGallery<?php echo iN_HelpSecure($uProductID);?>" data-standalone-gallery="true">
        <?php
            $trimValue = rtrim($uProductFiles, ',');
            $explodeFiles = explode(',', $trimValue);
            $explodeFiles = array_unique($explodeFiles);
            $countExplodedFiles = $iN->iN_CheckCountFile($uProductFiles);
            foreach ($explodeFiles as $pFile) {
                $fileData = $iN->iN_GetUploadedFileDetails($pFile);
                if($fileData){
                    $fileUploadID = $fileData['upload_id'] ?? null;
                    $fileExtension = $fileData['uploaded_file_ext'] ?? null;
                    $filePath = $fileData['uploaded_file_path'] ?? null;
                    $filePathTumbnail = $fileData['upload_tumbnail_file_path'] ?? null;
                    if($fileExtension == 'mp4'){
                        if (function_exists('storage_public_url')) {
                            $tumbFile = storage_public_url($filePathTumbnail);
                            $filePathUrl = storage_public_url($filePath);
                        } else {
                            $tumbFile =  $base_url . $filePathTumbnail;
                            $filePathUrl = $base_url . $filePath;
                        }

                        $srcPath = 'data-poster="' . $tumbFile . '" data-html="#video' . $fileUploadID . '"';
                    }else{
                        if (function_exists('storage_public_url')) {
                            $tumbFile = storage_public_url($filePathTumbnail);
                            $filePathUrl = storage_public_url($filePath);
                        } else {
                            $tumbFile =  $base_url . $filePathTumbnail;
                            $filePathUrl = $base_url . $filePath;
                        }
                        $srcPath = 'data-src="' . $filePathUrl . '"';
                    }
        ?>
            <div class="swiper-slide">
              <?php if ($fileExtension == 'mp4') { ?>
                <a href="#" data-html="#video<?php echo $fileUploadID; ?>" data-poster="<?php echo $tumbFile; ?>">
                  <div class="swiper-img flex_ tabing">
                    <img class="timp" src="<?php echo $tumbFile; ?>">
                  </div>
                </a>
              <?php } else { ?>
                <a href="<?php echo $filePathUrl; ?>">
                  <div class="swiper-img flex_ tabing">
                    <img class="timp" src="<?php echo $tumbFile; ?>">
                  </div>
                </a>
              <?php } ?>
            </div>
        <?php }}?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
        <!-- /Swiper -->
    </div>
     
    <!--Product Description-->
    <div class="product_p_description">
        <div class="product__description"><?php echo iN_HelpSecure($LANG['description']);?></div>
        <div class="product__d_all">
            <?php echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($uProductDescription), $base_url));?>
        </div>
    </div>
    <!--/Product Description-->
</div>
<div class="product_details_right">
    <div class="product_details_right_in">
        <div class="s_p_product_type <?php echo $p__style;?>"><a href="<?php echo iN_HelpSecure($base_url);?>marketplace?cat=<?php echo iN_HelpSecure($thisProduct);?>"><?php echo iN_HelpSecure($LANG[$uProductType]);?></a></div>
        <!--Product Title-->
        <h1 class="h_product_title"><?php echo iN_HelpSecure($uProductName);?></h1>
        <!--Product Title-->
        <!--Product OWNER-->
        <div class="s_p_owner_cont">
            <!---->
            <div class="i_u_details flex_ transition">
                <a href="<?php echo iN_HelpSecure($base_url.$userProdocutOwnerUsername);?>">


                <div class="user_post_user_avatar_plus_product">
        	        <?php if($userProfileFrame){ ?>
                        <div class="frame_out_container_product"><div class="frame_container_product"><img src="<?php echo $base_url.$userProfileFrame;?>"></div></div>
                    <?php }?>
                    <div class="iu_avatar">
                        <img src="<?php echo iN_HelpSecure($userPostOwnerUserAvatar);?>" alt="<?php echo iN_HelpSecure($userPostOwnerUserFullName);?>">
                        <!---->
                        <div class="i_thanks_bubble_cont tip_<?php echo iN_HelpSecure($uProductID); ?>">
                            <div class="i_bubble"><?php echo iN_HelpSecure($userTextForPostTip); ?></div>
                        </div>
                        <!---->
                    </div>
                </div>


                <div class="i_user_nm">
                    <div class="i_unm_product flex_"><?php echo iN_HelpSecure($userPostOwnerUserFullName).$userVerifiedStatus;?></div>
                    <div class="i_see_prof"><?php echo TimeAgo::ago($uPTime, date('Y-m-d H:i:s')); ?></div>
                </div>
                </a>
            </div>
            <!---->
        </div>
        <!--/Product OWNER-->
        <!---->
        <div class="s_p_price"><?php echo $currencys[$defaultCurrency].$uProductPrice;?> <span><?php echo iN_HelpSecure($defaultCurrency);?></span></div>
        <!---->
        <!--BUY PRODUCT-->
        <div class="buy_my_product">
             <div class="buy__myproduct tabing flex_ <?php if($logedIn == 0){echo 'loginForm';};?>" data-id="<?php echo iN_HelpSecure($uProductID);?>"><?php echo $iN->iN_SelectedMenuIcon('155').$LANG['buy_now'];?></div>
        </div>
        <!--BUY PRODUCT-->
        <?php if($checkProductPurchasedBefore && $logedIn == 1){?>
        <!--Purchased Not-->
        <div class="s_p_p_before flex_ tabing_non_justify">
           <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60'));?> <?php echo iN_HelpSecure($LANG['already_purchased_product']);?>
        </div>
        <!--/Purchased Not-->
        <?php }?>
        <!---->
        <?php if($checkProductPurchasedBefore && $logedIn == 1 && !empty($uProductDownloadableFiles)){?>
            <div class="s_p_p_p_download flex_ tabing" data-id="<?php echo iN_HelpSecure($uProductID);?>">
                <a href="<?php echo $base_url;?>product/<?php echo iN_HelpSecure($uProductSlug).'_'.$uProductID.'-'.$uProductID;?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('170')).iN_HelpSecure($LANG['download_your_file']);?>
                </a>
                </div>
        <?php }?>
        <!---->
        <!---->
        <?php if($checkProductPurchasedBefore && $logedIn == 1){?>
        <div class="s_p_live_not">
            <div class="owner_not"><?php echo iN_HelpSecure($LANG['sellers_note']);?></div>
            <div class="owner_not_text">
                <?php echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($uProductDescriptionInfo), $base_url));?>
            </div>
        </div>
        <?php }?>
        <!---->
        <!---->
        <div class="s_p_s_p flex_ tabing_non_justify">
            <div class="s__p flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('155'));?> <?php echo iN_HelpSecure($iN->iN_TotalProductSell($uProductID).' '.$LANG['pp_sales']);?></div>
            <div class="s__p flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('10'));?><?php echo iN_HelpSecure($iN->iN_TotalProductSeen($uProductID));?></div>
        </div>
        <!---->
        <!---->
        <div class="s_share_on_social flex_">
            <div class="s_social flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('169')).iN_HelpSecure($LANG['share_on_social']);?></div>
            <div class="flex_ tabing product_margin_left">
              <div class="on_s flex_ tabing share-btn" data-social="facebook" data-url="<?php echo iN_HelpSecure($uSlugUrl); ?>" data-id="2">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('90')); ?>
              </div>
              <div class="on_s flex_ tabing share-btn" data-social="twitter" data-url="<?php echo iN_HelpSecure($uSlugUrl); ?>" data-id="2">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('34')); ?>
              </div>
              <div class="on_s flex_ tabing share-btn" data-social="whatsapp" data-url="<?php echo iN_HelpSecure($uSlugUrl); ?>" data-id="2">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('147')); ?>
              </div>
            </div>
        </div>
        <!---->
    </div>
    <?php if($logedIn == 1 && $uProductOwnerID == $userID){?>
    <!--Product Page Extra for Creator-->
    <div class="product_details_right_in_top">
        <div class="add_new_product flex_ tabing"><a class="flex_ tabing" href="<?php echo iN_HelpSecure($base_url);?>settings?tab=createaProduct"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('39'));?><?php echo iN_HelpSecure($LANG['add_new_product']);?></a></div>
        <div class="ed_del_prod flex_ tabing_non_justify">
            <div class="edit_prod"><a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts&editProduct=<?php echo iN_HelpSecure($uProductID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27'));?><?php echo iN_HelpSecure($LANG['edit_product']);?></a></div>
            <div class="del_prod delmyprod" id="<?php echo iN_HelpSecure($uProductID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28'));?><?php echo iN_HelpSecure($LANG['delete_product']);?></div>
        </div>
    </div>
    <!--/Product Page Extra for Creator-->
<?php }?>
</div>
<?php if($logedIn == 1 && $uProductOwnerID == $userID){?>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/deleteProductHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
<?php }?>
<?php }else{ ?>
    <div class="i_not_found_page transition i_centered">
        <div class="noPostIcon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('54'));?></div>
        <h1><?php echo iN_HelpSecure($LANG['empty_shared_title']);?></h1>
        <?php echo iN_HelpSecure($LANG['empty_shared_desc']);?>
    </div>
<?php } ?>
</div>
