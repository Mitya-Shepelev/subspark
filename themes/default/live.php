<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php
    include "layouts/header/meta.php";
    include "layouts/header/css.php";
    include "layouts/header/javascripts.php";
    ?>
    <?php
    if ($agoraStatus == '1') { ?>
    <link href="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/video/video-js.css" rel="stylesheet" />
    <!-- If you'd like to support IE8 (for Video.js versions prior to v7) -->
    <script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/video/videojs-ie8.min.js"></script>
    <script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/video/videoa.js"></script>
    <?php }?> 
</head>
<body>
<?php include "layouts/header/header.php";?>
    <?php
if ($liveType == 'free') {
	include "layouts/live/freeLive.php";
} else {
	include "layouts/live/paidLive.php";
}
?>
</body>
</html>