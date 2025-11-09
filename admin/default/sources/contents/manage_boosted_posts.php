<?php
$searchUser = '';
$totalBoostedPost = $iN->iN_TotalBoostedPost($userID);
$totalPages = ceil($totalBoostedPost / $paginationLimit);

if (isset($_GET["page-id"])) {
    $pagep = isset($_GET["page-id"]) ? (int)$_GET["page-id"] : 1;
    if (!preg_match('/^[0-9]+$/', $pagep)) {
        $pagep = '1';
    }
} else {
    $pagep = '1';
}

if (isset($_GET['sr'])) {
    $searchUser = isset($_GET['sr']) ? $iN->iN_Secure($_GET['sr']) : '';
}
?>
<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['manage_boosted_posts']) . '(' . $totalBoostedPost . ')'; ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding" id="general_conf">
            <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']); ?></div>
            <?php
            $allBoostedPosts = $iN->iN_ShowAllBoostedPost($userID, $paginationLimit, $pagep);
            if ($allBoostedPosts) {
            ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['boosted_post']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['post_owner']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['createdat']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['boost_price']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['boost_type']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['tobeshown']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['shown']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['status']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['action']); ?></th>
                        </tr>
                        <?php
                        foreach ($allBoostedPosts as $sbData) {
                            $boostID = $sbData['boost_id'] ?? null;
                            $boostOwner = $sbData['iuid_fk'] ?? null;
                            $boostStatus = $sbData['status'] ?? null;
                            $boostCreated = $sbData['started_at'] ?? null;
                            $boostedPostID = $sbData['post_id_fk'] ?? null;
                            $boostType = $sbData['boost_type'] ?? null;
                            $boostTypeData = $iN->iN_GetBoostPostDetails($boostType);
                            $planAmount = $boostTypeData['plan_amount'] ?? null;
                            $planIcon = $boostTypeData['plan_icon'] ?? null;
                            $planNameKey = $boostTypeData['plan_name_key'] ?? null;
                            $planViewTime = $boostTypeData['view_time'] ?? null;
                            $urlSlug = $sbData['url_slug'] ?? null;
                            $sSlugUrl = $base_url . 'post/' . $urlSlug . '_' . $boostedPostID;
                            $crTime = date('Y-m-d H:i:s', $boostCreated);
                            $userAvatar = $iN->iN_UserAvatar($boostOwner, $base_url);
                            $userUserName = $sbData['i_username'] ?? null;
                            $userUserFullName = $sbData['i_user_fullname'] ?? null;
                        ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($boostID); ?></td>
                                <td>
                                    <div class="t_od flex_ c6">
                                        <div class="t_owner_avatar border_two tabing flex_">
                                            <img src="<?php echo iN_HelpSecure($userAvatar, FILTER_VALIDATE_URL); ?>">
                                        </div>
                                        <div class="t_owner_user tabing flex_">
                                            <a class="truncated" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL) . $userUserName; ?>">
                                                <?php echo iN_HelpSecure($userUserFullName); ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify see_post_details_a">
                                        <a href="<?php echo iN_HelpSecure($sSlugUrl, FILTER_VALIDATE_URL); ?>">See Post</a>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73')) . ' ' . TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing">
                                            <?php echo iN_HelpSecure($planAmount) . $currencys[$defaultCurrency]; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="i_sub_not_check_box type_news flex_ tabing_non_justify hannib positionRelative">
                                            <?php echo html_entity_decode($planIcon) . iN_HelpSecure($planNameKey); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing"><?php echo iN_HelpSecure($planViewTime); ?></div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing"><?php echo iN_HelpSecure($iN->iN_CountSeenBoostedPostbyID($boostOwner, $boostID)); ?></div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="bootTim flex_ tabing">
                                            <label class="el-switch el-switch-yellow" for="uPBoost<?php echo iN_HelpSecure($boostID); ?>">
                                                <input type="checkbox" name="stickerStatus" class="uPBoost" id="uPBoost<?php echo iN_HelpSecure($boostID); ?>" data-id="<?php echo iN_HelpSecure($boostID); ?>" data-type="uPBoost" <?php echo iN_HelpSecure($boostStatus) === 'yes' ? 'value="no" checked="checked"' : 'value="yes"'; ?>>
                                                <span class="el-switch-style"></span>
                                            </label>
                                        </div>
                                    </div>
                                </td>
                                <td class="flex_ tabing_non_justify">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="delu del_BoostPopUP border_one transition tabing flex_ delete" id="<?php echo iN_HelpSecure($boostID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php
            } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div><div class="n_c_t">' . $LANG['no_user_found'] . '</div></div>';
            }
            ?>
        </div>

        <div class="i_become_creator_box_footer tabing">
            <?php if ($totalPages >= 1): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep > 3): ?>
                        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=1&sr=<?php echo iN_HelpSecure($searchUser); ?>">1</a></li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php if ($pagep - 2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a></li><?php endif; ?>
                    <?php if ($pagep - 1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a></li><?php endif; ?>

                    <li class="currentpage active"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=<?php echo iN_HelpSecure($pagep); ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                    <?php if ($pagep + 1 < $totalPages + 1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a></li><?php endif; ?>
                    <?php if ($pagep + 2 < $totalPages + 1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>"><?php echo iN_HelpSecure($pagep) + 2; ?></a></li><?php endif; ?>

                    <?php if ($pagep < $totalPages - 2): ?>
                        <li class="dots">...</li>
                        <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=<?php echo $totalPages; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>"><?php echo $totalPages; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages): ?>
                        <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_boosted_posts?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/manageBoostedPostHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>
