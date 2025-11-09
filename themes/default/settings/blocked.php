<?php
$totalPages = ceil($totalBlockedUsers / $paginationLimit);
if (isset($_GET["page-id"])) {
    $pagep = (int)$_GET["page-id"];
    if ($pagep <= 0) { $pagep = 1; }
} else {
    $pagep = 1;
}
?>
<div class="settings_main_wrapper">
    <div class="i_settings_wrapper_in i_inline_table">
        <div class="i_settings_wrapper_title">
            <div class="i_settings_wrapper_title_txt flex_">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('64')); ?>
                <?php echo iN_HelpSecure($LANG['blocked']) . '(' . $totalBlockedUsers . ')'; ?>
            </div>
            <div class="i_moda_header_nt">
                <?php echo iN_HelpSecure($LANG['block_page_not']); ?>
            </div>
        </div>
        <div class="i_settings_wrapper_items">
            <div class="i_tab_container i_tab_padding">
                <?php
                $blockedUsersData = $iN->iN_UserBlockedListPage($userID, $paginationLimit, $pagep);
                if ($blockedUsersData) {
                    foreach ($blockedUsersData as $bData) {
                        $blockID = $bData['block_id'] ?? null;
                        $blockedUserID = $bData['blocked_iuid'] ?? null;
                        $blockedUserAvatar = $iN->iN_UserAvatar($blockedUserID, $base_url);
                        $blockedUserData = $iN->iN_GetUserDetails($blockedUserID);
                        $blockedUserName = $blockedUserData['i_username'] ?? null;
                        $blockedUserFullName = $blockedUserData['i_user_fullname'] ?? null;
                        ?>
                        <div class="i_sub_box_container block_id_<?php echo iN_HelpSecure($blockID); ?>">
                            <div class="i_sub_box_wrp flex_">
                                <div class="i_sub_box_avatar">
                                    <img class="isubavatar" src="<?php echo iN_HelpSecure($blockedUserAvatar); ?>">
                                </div>
                                <div class="i_sub_box_name_time">
                                    <div class="i_sub_box_name truncated tab_max_width">
                                        <a href="<?php echo iN_HelpSecure($base_url) . $blockedUserName; ?>" class="truncated">
                                            <?php echo iN_HelpSecure($blockedUserFullName); ?>
                                        </a>
                                    </div>
                                    <div class="i_sub_box_unm">@<?php echo iN_HelpSecure($blockedUserName); ?></div>
                                </div>
                                <div class="i_sub_flw">
                                    <div class="i_follow flex_ tabing transition unblock"
                                         id="<?php echo iN_HelpSecure($blockID); ?>"
                                         data-u="<?php echo iN_HelpSecure($blockedUserID); ?>">
                                        <?php echo iN_HelpSecure($LANG['un_block']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . html_entity_decode($iN->iN_SelectedMenuIcon('54')) . '</div><div class="n_c_t">' . $LANG['perfect_no_blocked_yet'] . '</div></div>';
                }
                ?>
            </div>
        </div>
        <div class="i_become_creator_box_footer tabing">
            <?php if ($totalPages > 0): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>">
                                <?php echo iN_HelpSecure($LANG['preview_page']); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep > 3): ?>
                        <li class="start">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=1">1</a>
                        </li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php if ($pagep - 2 > 0): ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>">
                                <?php echo iN_HelpSecure($pagep) - 2; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep - 1 > 0): ?>
                        <li class="page">
                            <a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>">
                                <?php echo iN_HelpSecure($pagep) - 1; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="currentpage active">
                        <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=<?php echo iN_HelpSecure($pagep); ?>">
                            <?php echo iN_HelpSecure($pagep); ?>
                        </a>
                    </li>

                    <?php if ($pagep + 1 <= $totalPages): ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>">
                                <?php echo iN_HelpSecure($pagep) + 1; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep + 2 <= $totalPages): ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>">
                                <?php echo iN_HelpSecure($pagep) + 2; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages - 2): ?>
                        <li class="dots">...</li>
                        <li class="end">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=<?php echo $totalPages; ?>">
                                <?php echo $totalPages; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages): ?>
                        <li class="next">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=blocked&page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>">
                                <?php echo iN_HelpSecure($LANG['next_page']); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
