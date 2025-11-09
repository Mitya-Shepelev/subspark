<?php
// Extract post ID from URL
$GetThePostIDFromUrl = substr($slugyUrl, strrpos($slugyUrl, '_') + 1);
if (!$GetThePostIDFromUrl) {
    $GetThePostIDFromUrl = strstr($slugyUrl, '_', true);
}

// Validate the extracted post ID
if (preg_match('/_/', $slugyUrl)) {
    $GetThePostIDFromUrl = $GetThePostIDFromUrl;
} else {
    $GetThePostIDFromUrl = $slugyUrl;
}

// Get post data using the extracted ID
$postFromData = $iN->iN_GetAllPostDetails($GetThePostIDFromUrl);

if ($postFromData) {
    $metaBaseUrl = $base_url . 'post/' . $slugyUrl;
    $userPostFile = $postFromData['post_file'];
    $userPostID = $postFromData['post_id'];
    $userPostOwnerID = $postFromData['post_owner_id'];
    $slugUrl = $base_url . 'post/' . $postFromData['url_slug'] . '_' . $userPostID;
    $userPostWhoCanSee = $postFromData['who_can_see'];

    // Prepare file list
    $trimValue = rtrim($userPostFile, ',');
    $explodeFiles = array_unique(explode(',', $trimValue));
    $countExplodedFiles = count($explodeFiles);
    $nums = preg_split('/\s*,\s*/', $trimValue);
    $lastFileID = end($nums);

    // Get details for the last uploaded file
    $fileData = $iN->iN_GetUploadedFileDetails($lastFileID);
    if ($fileData) {
        $fileUploadID = $fileData['upload_id'];
        $fileExtension = $fileData['uploaded_file_ext'];
        $filePath = $fileData['uploaded_file_path'];

        // Apply privacy rules for file path
        if ($userPostWhoCanSee != '1' && $logedIn == '1') {
            $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $userPostOwnerID);
            if ($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber') {
                $filePath = $fileData['uploaded_x_file_path'];
            }
        } elseif ($userPostWhoCanSee != '1' && !$logedIn) {
            $filePath = $fileData['uploaded_x_file_path'];
        }

        // Remove file extension for further use
        $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);

        // Determine full file URL via unified helper
        if (function_exists('storage_public_url')) {
            $filePathUrl = storage_public_url($filePath);
        } else {
            $filePathUrl = $base_url . $filePath;
        }

        $videoPlaybutton = '';
        if ($fileExtension == 'mp4') {
            // Set play button HTML for videos
            $videoPlaybutton = '<div class="playbutton">' . $iN->iN_SelectedMenuIcon('55') . '</div>';

            // Replace path with thumbnail if restricted
            if ($userPostWhoCanSee != '1' && $logedIn == '1') {
                $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $userPostOwnerID);
                if ($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber') {
                    $filePath = $fileData['upload_tumbnail_file_path'];
                }
            } elseif ($userPostWhoCanSee != '1' && !$logedIn) {
                $filePath = $fileData['upload_tumbnail_file_path'];
            }

            // Update file URL with the correct path for the video
            if (function_exists('storage_public_url')) {
                $filePathUrl = storage_public_url($filePath);
            } else {
                $filePathUrl = $base_url . $filePath;
            }
        }

        // Override meta URL with file path
        $metaBaseUrl = $filePathUrl;
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
        // Load header components
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
    <div class="wrapper">
        <?php
            include("layouts/left_menu.php");
            if ($urlMatch == 'post') {
                include("layouts/post.php");
            }
            include("layouts/page_right.php");
        ?>
    </div>
</body>
</html>
