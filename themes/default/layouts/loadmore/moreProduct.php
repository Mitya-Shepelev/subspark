<?php
if (!empty($productData)) {
    foreach ($productData as $oprod) {
        $sProductID = $oprod['pr_id'];
        $sProductName = $oprod['pr_name'];
        $sProductPrice = $oprod['pr_price'];
        $sProductFiles = $oprod['pr_files'];
        $sProductOwnerID = $oprod['iuid_fk'];
        $sProductSlug = $oprod['pr_name_slug'];
        $sProductType = $oprod['product_type'];
        $p__style = $sProductType;

        if ($sProductType === 'scratch') {
            $sProductType = 'simple_product';
            $p__style = 'scratch';
        }

        $sProductSlotsNumber = $oprod['pr_slots_number'];
        $sSlugUrl = $base_url . 'product/' . $sProductSlug . '_' . $sProductID;
        $strimValue = rtrim($sProductFiles, ',');
        $snums = preg_split('/\s*,\s*/', $strimValue);
        $slastFileID = end($snums);
        $sproductDataImage = '';

        $spfData = $iN->iN_GetUploadedFileDetails($slastFileID);
        if ($spfData) {
            $sfilePath = $spfData['uploaded_file_path'];
            if (function_exists('storage_public_url')) {
                $sproductDataImage = storage_public_url($sfilePath);
            } else {
                $sproductDataImage = $base_url . $sfilePath;
            }
        }
        ?>

        <div class="s_p_product_container mor inline_block" data-last="<?php echo iN_HelpSecure($sProductID); ?>" id="<?php echo iN_HelpSecure($sProductID); ?>">
            <a href="<?php echo iN_HelpSecure($sSlugUrl); ?>">
                <div class="s_p_product_wrapper">
                    <div class="product_image flex_ tabing">
                        <img class="timp" src="<?php echo iN_HelpSecure($sproductDataImage); ?>" alt="<?php echo iN_HelpSecure($sProductName); ?>">
                    </div>
                    <div class="s_p_details">
                        <div class="s_p_title" title="<?php echo iN_HelpSecure($sProductName); ?>">
                            <?php echo iN_HelpSecure($sProductName); ?>
                        </div>
                        <div class="s_p_product_type <?php echo iN_HelpSecure($p__style); ?>">
                            <?php echo iN_HelpSecure($LANG[$sProductType]); ?>
                        </div>
                        <div class="s_p_price">
                            <?php echo iN_HelpSecure($currencys[$defaultCurrency]) . iN_HelpSecure($sProductPrice); ?>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <?php
    }
} else {
    ?>
    <div class="s_p_product_container nmr i_display_content">
        <div class="s_p_product_wrapper flex_ tabing product_wrapper_styl inline_block">
            <?php echo iN_HelpSecure($LANG['no_more_product_shown']); ?>
        </div>
    </div>
<?php
}
?>
