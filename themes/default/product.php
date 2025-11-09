<?php
// Extract product ID from slug URL
$GetTheProductIDFromUrl = substr($slugyUrl, strrpos($slugyUrl, '_') + 1);
if (!$GetTheProductIDFromUrl) {
    $GetTheProductIDFromUrl = strstr($slugyUrl, '_', true);
}

// Use full slug if no underscore found
if (!preg_match('/_/', $slugyUrl)) {
    $GetTheProductIDFromUrl = $slugyUrl;
}

// Retrieve product data by ID
$prdOut = $iN->iN_GetProductDetailsByID($GetTheProductIDFromUrl);

if ($prdOut) {
    $ProductID = $prdOut['pr_id'];
    $ProductName = $prdOut['pr_name'];
    $ProductPrice = $prdOut['pr_price'];
    $ProductFiles = $prdOut['pr_files'];
    $ProductDescription = $prdOut['pr_desc'];
    $ProductDescriptionInfo = $prdOut['pr_desc_info'];
    $ProductOwnerID = $prdOut['iuid_fk'];
    $ProductSlug = $prdOut['pr_name_slug'];
    $ProductType = $prdOut['product_type'];
    $ProductSlotsNumber = $prdOut['pr_slots_number'];
    $SlugUrl = $base_url . 'product/' . $ProductSlug . '_' . $ProductID;

    // User information
    $userProdocutOwnerUsername = $prdOut['i_username'];
    $userProductOwnerFullName = $prdOut['i_user_fullname'];
    $userPostOwnerUserAvatar = $iN->iN_UserAvatar($ProductOwnerID, $base_url);
    $userPostUserVerifiedStatus = $prdOut['user_verified_status'];

    // Verified icon setup
    $userVerifiedStatus = '';
    if ($userPostUserVerifiedStatus == '1') {
        $userVerifiedStatus = '<div class="i_plus_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
    }

    // Page meta details
    $siteTitle = $ProductName;
    $siteDescription = $ProductDescription;
    $metaBaseUrl = $SlugUrl;

    // Retrieve last uploaded file for preview image
    $trimValue = rtrim($ProductFiles, ',');
    $nums = preg_split('/\s*,\s*/', $trimValue);
    $lastFileID = end($nums);

    $fData = $iN->iN_GetUploadedFileDetails($lastFileID);
    if ($fData) {
        $fileUploadID = $fData['upload_id'];
        $fileExtension = $fData['uploaded_file_ext'];
        $filePath = $fData['uploaded_file_path'];
        $filePathTumbnail = $fData['upload_tumbnail_file_path'];

        // Use thumbnail for video preview
        if ($fileExtension == 'mp4') {
            $filePath = $filePathTumbnail;
        }

        // Set meta preview URL using unified storage helper (supports MinIO/S3/Spaces/Wasabi/local)
        if (function_exists('storage_public_url')) {
            $metaBaseUrl = storage_public_url($filePath);
        } else {
            $metaBaseUrl = $base_url . $filePath;
        }
    }
}

// Determine page context
$page = $urlMatch;

// Process file download if user owns product
$GetTheProductIDD = substr($slugyUrl, strrpos($slugyUrl, '-') + 1);
if (isset($GetTheProductIDD) && !empty($GetTheProductIDD)) {
    $gproductID = (int)$GetTheProductIDD;
    $checkProductPurchasedBefore = $iN->iN_CheckItemPurchasedBefore($userID, $gproductID);

    if ($checkProductPurchasedBefore) {
        $productData = $iN->iN_GetProductDetailsByID($gproductID);
        $uProductDownloadableFiles = $productData['pr_downlodable_files'];
        $thefile = $uProductDownloadableFiles;
        $file = $uProductDownloadableFiles;
        $ext = substr($file, strrpos($file, '.') + 1);
        $fake = $slugyUrl . '.' . $ext;

        if (file_exists($thefile)) {
            $iN->download($file, $fake);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php
        include("layouts/header/meta.php");
        include("layouts/header/css.php");
        include("layouts/header/javascripts.php");
    ?>
</head>
<body>
    <?php if ($logedIn == 0) {
        include('layouts/login_form.php');
    } ?>
    <?php include("layouts/header/header.php"); ?>

    <div class="wrapper_product">
        <div class="flex_">
            <?php
                if ($urlMatch == 'product') {
                    include("layouts/product.php");
                }
            ?>
        </div>
    </div>

    <?php if ($prdOut) {
        include("layouts/widgets/sugestedpproducts.php");
    } ?>
</body>
</html>
