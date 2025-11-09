<?php
$themePath = iN_HelpSecure($base_url) . 'admin/' . iN_HelpSecure($currentTheme) . '/css/';
$ver = '?v=' . iN_HelpSecure($version);
?>

<link rel="stylesheet" type="text/css" href="<?php echo $themePath; ?>style.css<?php echo $ver; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $themePath; ?>lightGallery/lightgallery.css<?php echo $ver; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $themePath; ?>videojscss/video-js.css<?php echo $ver; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $themePath; ?>checkbox/checkbox.css<?php echo $ver; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $themePath; ?>crop/cropmain.css<?php echo $ver; ?>">