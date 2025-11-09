<?php
$sgProduct = $iN->iN_SuggestedProductWidget($showingNumberOfProduct);
   if($sgProduct){
echo '
<div class="sp_wrp">
<div class="suggested_products">
<div class="i_right_box_header">
   '.iN_HelpSecure($LANG['latest_products']).'
</div>
<div class="sp_products">
';
      foreach($sgProduct as $sgData){
        $sProductID = $sgData['pr_id'];
        $sProductName = $sgData['pr_name'];
        $sProductPrice = $sgData['pr_price'];
        $sProductFiles = $sgData['pr_files'];
        $sProductOwnerID = $sgData['iuid_fk'];
        $sProductSlug = $sgData['pr_name_slug'];
        $sProductType = $sgData['product_type'];
        $p__style = $sProductType;
        $thisProduct = $sProductType;
        if($sProductType == 'scratch'){
            $sProductType = 'simple_product';
            $p__style = 'scratch';
            $thisProduct = 'all';
        }
        $sProductSlotsNumber = $sgData['pr_slots_number'];
        $sSlugUrl = $base_url.'product/'.$sProductSlug.'_'.$sProductID;
        $strimValue = rtrim($sProductFiles,',');
        $snums = preg_split('/\s*,\s*/', $strimValue);
        $slastFileID = end($snums);
        $spfData = $iN->iN_GetUploadedFileDetails($slastFileID);
        if($spfData){
            $sfileUploadID = $spfData['upload_id'];
            $sfileExtension = $spfData['uploaded_file_ext'];
            $sfilePath = $spfData['uploaded_file_path'];
            if($sfileExtension == 'mp4'){
               $sfilePath = $spfData['upload_tumbnail_file_path'];
            }
            if (function_exists('storage_public_url')) {
                $sproductDataImage = storage_public_url($sfilePath);
            } else {
                $sproductDataImage = $base_url . $sfilePath;
            }
        }
?>
    <div class="sp_product_wrapper">
        <div class="sp_product_container">
            <a href="<?php echo iN_HelpSecure($base_url.'product/'.$sProductSlug).'_'.$sProductID;?>">
                <div class="sp_product_img">
                    <div class="s_p_product_type mypType <?php echo $p__style;?>"><a href="<?php echo iN_HelpSecure($base_url);?>marketplace?cat=<?php echo iN_HelpSecure($thisProduct);?>"><?php echo iN_HelpSecure($LANG[$sProductType]);?></a></div>
                    <img src="<?php echo iN_HelpSecure($sproductDataImage);?>">
                </div>
            </a>
            <div class="sp_product_det">
                <div class="sp_product_name"><a href="<?php echo iN_HelpSecure($base_url.'product/'.$sProductSlug.'_'.$sProductID);?>"><?php echo iN_HelpSecure($sProductName);?></a></div>
                <div class="sp_product_price"><?php echo iN_HelpSecure($currencys[$defaultCurrency].$sProductPrice);?></div>
            </div>
        </div>
    </div>
<?php
 }
echo '</div></div></div>';
   }
?>
