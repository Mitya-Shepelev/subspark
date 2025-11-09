<?php
$baseThemePath = iN_HelpSecure($base_url) . 'themes/' . iN_HelpSecure($currentTheme);
?>

<!-- Common Scripts -->
<script src="<?php echo $baseThemePath; ?>/js/jquery-v3.7.1.min.js"></script>
<script src="<?php echo $baseThemePath; ?>/js/jquery.form.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
<script src="<?php echo $baseThemePath; ?>/js/share.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
<script src="<?php echo $baseThemePath; ?>/js/clipboard/clipboard.min.js" defer></script>
<script src="<?php echo $baseThemePath; ?>/js/lightGallery/lightgallery-all.min.js" defer></script>

<?php if ($logedIn == 1): ?>
  <!-- Logged-in User Scripts -->
  <script src="<?php echo $baseThemePath; ?>/js/autoresize.min.js" defer></script>
  <script src="<?php echo $baseThemePath; ?>/js/videojs/video.js" defer></script>
  <script src="<?php echo $baseThemePath; ?>/js/scrollBar/jquery.slimscroll.min.js" defer></script>
  <script src="<?php echo $baseThemePath; ?>/js/character_count.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script> 
  <script src="<?php echo $baseThemePath; ?>/js/inora.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>

  <!-- Dynamic values -->
  <script>
    window.siteurl = "<?php echo iN_HelpSecure($base_url); ?>";
    window.inD = atob("<?php echo isset($fulPage) ? $fulPage : 'MQ=='; ?>");
    window.availableLength = <?php echo iN_HelpSecure($availableLength); ?>;
  </script>

  <?php if ($page === 'profile'): ?>
    <script src="https://js.stripe.com/v3/" defer></script>
  <?php endif; ?>

  <?php if ($oneSignalStatus === 'open'): ?>
    <link rel="manifest" href="<?php echo $base_url; ?>manifestOneSignal.json">
    <script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async defer></script>
    <script>
      window.onesignal_app_id = "<?php echo $oneSignalApi; ?>";
      window.onesignal_user_id = "<?php echo $deviceKey; ?>";
      window.onesignal_request_url = "<?php echo $base_url . 'requests/request.php'; ?>";
    </script>
    <script src="<?php echo $baseThemePath; ?>/js/onesignal-handler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
  <?php endif; ?>

<?php else: ?>
  <!-- Guest User Scripts -->
  <script>
    window.siteurl = "<?php echo iN_HelpSecure($base_url); ?>";
  </script>
  <script src="<?php echo $baseThemePath; ?>/js/inora_do.js?v=s211<?php echo iN_HelpSecure($version); ?>" defer></script>
<?php endif; ?>

<!-- Custom JS if exists -->
<?php
if (!empty($customHeaderJsCode)) {
  $customJsPath = "themes/" . iN_HelpSecure($currentTheme) . "/js/custom-header.js";
  file_put_contents($customJsPath, $customHeaderJsCode);
?>
  <script src="<?php echo $baseThemePath; ?>/js/custom-header.js?v=<?php echo time(); ?>" defer></script>
<?php } ?>

<!-- Swiper.js -->
<script src="<?php echo $baseThemePath; ?>/js/swiper/swiper-bundle.min.js"></script>