<div class="header">
    <div class="i_header_in">
        <div class="i_logo tabing flex_">
            <a href="<?php echo iN_HelpSecure($base_url); ?>">
                <img src="<?php echo iN_HelpSecure($siteLogoUrl); ?>">
            </a>
            <?php if ($page === 'moreposts' && $logedIn == 1) { ?>
                <div class="mobile_hamburger tabing flex_">
                    <div class="i_header_btn_item transition">
                        <div class="i_h_in is_mobile">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('100')); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div class="i_search relativePosition">
            <div class="mobile_back tabing flex_ mobile_srcbtn">
                <div class="i_header_btn_item transition">
                    <div class="i_h_in is_mobile">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('102')); ?>
                    </div>
                </div>
            </div>
            <input type="text" class="i_s_input" id="search_creator" placeholder="<?php echo iN_HelpSecure($LANG['search_creators']); ?>">

            <div class="i_general_box_search_container generalBox search_cont_style">
                <div class="btest">
                    <div class="i_user_details">
                        <div class="i_box_messages_header">
                            <?php echo iN_HelpSecure($LANG['search']); ?>
                        </div>
                        <div class="i_header_others_box sb_items"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="i_header_right">
            <div class="i_one">
                <div class="i_header_btn_item transition search_mobile mobile_srcbtn">
                    <div class="i_h_in">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('101')); ?>
                    </div>
                </div>

                <?php if ($logedIn == 1) { ?>
                    <div class="i_header_btn_item topPoints transition ownTooltip"
                         data-label="<?php echo iN_HelpSecure($LANG['get_point_and_point_balance']); ?>"
                         id="topPoints"
                         data-type="topPoints">
                        <div class="i_h_in">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                        </div>
                    </div>

                    <?php if ($iN->iN_ShopStatus(1) === 'yes') { ?>
                        <div class="i_header_btn_item transition shopi ownTooltip"
                             data-label="<?php echo iN_HelpSecure($LANG['marketplace']); ?>">
                            <a href="<?php echo iN_HelpSecure($base_url) . 'marketplace?cat=all'; ?>">
                                <div class="i_h_in">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('158')); ?>
                                </div>
                            </a>
                        </div>
                    <?php } ?>

                    <div class="i_header_btn_item topMessages transition ownTooltip"
                         data-label="<?php echo iN_HelpSecure($LANG['messenger']); ?>"
                         id="topMessages"
                         data-type="topMessages">
                        <div class="i_h_in">
                            <div class="i_notifications_count msg_not nonePoint">
                                <div class="isum sum_m" data-id=""><?php echo iN_HelpSecure($totalMessageNotifications); ?></div>
                            </div>
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('38')); ?>
                        </div>
                    </div>

                    <div class="i_header_btn_item topNotifications transition ownTooltip"
                         data-label="<?php echo iN_HelpSecure($LANG['notifications']); ?>"
                         id="topNotifications"
                         data-type="topNotifications">
                        <div class="i_h_in">
                            <div class="i_notifications_count not_not nonePoint">
                                <div class="isum sum_not" data-id=""><?php echo iN_HelpSecure($totalNotifications); ?></div>
                            </div>
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('37')); ?>
                        </div>
                    </div>

                    <div class="i_header_btn_item getMenu transition" id="topMenu" data-type="topMenu">
                        <div class="i_h_in">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
                        </div>
                    </div>

                <?php } else { ?>
                    <!-- Before Login -->
                    <div class="i_login loginForm">
                        <?php echo iN_HelpSecure($LANG['login']); ?>
                    </div>
                    <a href="<?php echo iN_HelpSecure($base_url); ?>register">
                        <div class="i_singup"><?php echo iN_HelpSecure($LANG['sign_up']); ?></div>
                    </a>
                    <a href="<?php echo iN_HelpSecure($base_url); ?>creators">
                        <div class="i_language"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('95')); ?></div>
                    </a>
                    <!-- /Before Login -->
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php if ($logedIn == 1) { ?>
    <audio id="notification-sound-mes" class="sound-controls" preload="auto">
        <source src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/mp3/message.mp3" type="audio/mpeg">
    </audio>
    <audio id="notification-sound-not" class="sound-controls aw" preload="auto">
        <source src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/mp3/not.mp3" type="audio/mpeg">
    </audio>
    <audio id="notification-sound-coin" class="sound-controls" preload="auto">
        <source src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/mp3/coin.mp3" type="audio/mpeg">
    </audio>
    <audio id="notification-sound-call" class="sound-controls" preload="auto">
        <source src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/mp3/call.mp3" type="audio/mpeg">
    </audio>
<?php } ?>

<script src="<?php echo iN_HelpSecure($base_url); ?>src/gdpr-cookie.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo $currentTheme; ?>/js/gdpr-handler.js?v=<?php echo time(); ?>" defer></script>

<script>
    window.cookie_title = "<?php echo iN_HelpSecure($LANG['cookie_title']); ?>";
    window.cookie_desc = "<?php echo iN_HelpSecure($LANG['cookie_desc']); ?>";
    window.cookie_accept = "<?php echo iN_HelpSecure($LANG['accept']); ?>";
</script>

<script type="text/javascript">
    var audio = new Audio('<?php echo $base_url; ?>/themes/default/mp3/call.mp3');
</script>