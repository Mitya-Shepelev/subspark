<div class="rightSticky">
    <div class="i_right_container">
        <div class="rightSidebar_in">
            <div class="leftSidebarWrapper leftSidebarWrapper_mobile">
                <div class="btest">
                    <?php
                    // Show 'Become a Creator' box if eligible
                    if ($conditionStatus === '0' && $beaCreatorStatus === 'request') {
                        include __DIR__ . '/widgets/becomeCreator.php';
                    }

                    // Top Inoras widget
                    include __DIR__ . '/widgets/topinoras.php';

                    // Friends activity only if user is logged in
                    if ($logedIn === '1') {
                        include __DIR__ . '/widgets/friendsActivity.php';
                    }

                    // Other public widgets
                    if ($logedIn === '1') {
                        include __DIR__ . '/widgets/inviteWithEmail.php';
                    }
                    include __DIR__ . '/widgets/sponsored.php';
                    include __DIR__ . '/widgets/suggestedProducts.php';
                    include __DIR__ . '/widgets/suggested_creators.php';
                    ?>

                    <div class="footer_container">
                        <?php include __DIR__ . '/footer.php'; ?>
                    </div>

                    <div class="footer_social_links_container flex_">
                        <?php include __DIR__ . '/footerSocialLinks.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Mouse Icon -->
    <div class="i_yesScrollable">
        <div class="mouse_scroll">
            <div class="mouse">
                <div class="wheel"></div>
            </div>
            <div>
                <span class="m_scroll_arrows unu"></span>
                <span class="m_scroll_arrows doi"></span>
                <span class="m_scroll_arrows trei"></span>
            </div>
        </div>
    </div>
</div>

<?php if ($logedIn === '1') { ?>
    <!-- Web Worker for notifications or background tasks -->
    <script src="<?php echo iN_HelpSecure($base_url); ?>src/worker.js"></script>
<?php } ?>

<script>
    // Expose login and creator status to JS
    window.userIsLoggedIn = <?php echo $logedIn === '1' ? 'true' : 'false'; ?>;
    window.userIsCreator = <?php echo ($logedIn === '1' && $userType === '2') ? 'true' : 'false'; ?>;
</script>

<!-- Right Sidebar JS Handlers -->
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/rightSidebarHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/greenaudioplayer/audioplayer.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
