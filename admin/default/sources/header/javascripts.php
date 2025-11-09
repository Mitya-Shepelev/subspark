<?php
$jsPath = iN_HelpSecure($base_url) . 'admin/' . iN_HelpSecure($adminTheme) . '/js/';
$version = '?v=' . iN_HelpSecure($version);
?>

<script src="<?php echo $jsPath; ?>jquery-v3.7.1.min.js"></script>
<script src="<?php echo $jsPath; ?>jquery.form.js<?php echo $version; ?>"></script>
<script src="<?php echo $jsPath; ?>share.js<?php echo $version; ?>" defer></script>
<script src="<?php echo $jsPath; ?>autoresize.min.js<?php echo $version; ?>" defer></script>
<script src="<?php echo $jsPath; ?>lightGallery/lightgallery-all.min.js<?php echo $version; ?>" defer></script>
<script src="<?php echo $jsPath; ?>videojs/video.js<?php echo $version; ?>" defer></script>
<script src="<?php echo $jsPath; ?>clipboard/clipboard.min.js<?php echo $version; ?>" defer></script>
<script src="<?php echo $jsPath; ?>scrollBar/jquery.slimscroll.min.js<?php echo $version; ?>" defer></script>
<script src="<?php echo $jsPath; ?>i_admin.js<?php echo $version; ?>" defer></script>

<script>
  window.siteurl = "<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/";
  window.siteurlRedirect = "<?php echo iN_HelpSecure($base_url); ?>admin/";
  window.siteRoot = "<?php echo iN_HelpSecure($base_url); ?>";
</script>
