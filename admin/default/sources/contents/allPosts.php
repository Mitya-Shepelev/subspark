<?php
$totalnApprovedPosts = $iN->iN_CalculateAllPosts();
$totalPages = ceil($totalnApprovedPosts / $paginationLimit);
if (isset($_GET["page-id"])) {
    $pagep = isset($_GET["page-id"]) ? (int)$_GET["page-id"] : 1;
    if (!preg_match('/^[0-9]+$/', $pagep)) {
        $pagep = '1';
    }
} else {
    $pagep = '1';
}
?>
<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['posts']) . '(' . $totalnApprovedPosts . ')'; ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']); ?></div>
            <?php
            $ApprovedPosts = $iN->iN_AllTypePostsList($userID, $paginationLimit, $pagep);
            if ($ApprovedPosts) {
                ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['post_owner']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['post_type']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['post_shared_time']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['status']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['actions']); ?></th>
                        </tr>
                        <?php
                        foreach ($ApprovedPosts as $postData) {
                            $postID = $postData['post_id'];
                            $postOwnerID = $postData['post_owner_id'];
                            $postOwnerAvatar = $iN->iN_UserAvatar($postOwnerID, $base_url);
                            $postOwnerUserName = $postData['i_username'];
                            $postOwnerUserFullName = $postData['i_user_fullname'];
                            $postWhoCanSeeType = $postData['who_can_see'];
                            $postCreatedTime = $postData['post_created_time'];
                            $postStatus = $postData['post_status'];
                            $crTime = date('Y-m-d H:i:s', $postCreatedTime);

                            if ($postWhoCanSeeType == '3') {
                                $postType = '<div class="forsubs flex_ tabing">' . $iN->iN_SelectedMenuIcon('51') . $LANG['subscribers'] . '</div>';
                            } elseif ($postWhoCanSeeType == '4') {
                                $postType = '<div class="forpremiums flex_ tabing">' . $iN->iN_SelectedMenuIcon('40') . $LANG['premium'] . '</div>';
                            } else {
                                $postType = '<div class="foreveryone flex_ tabing">' . $iN->iN_SelectedMenuIcon('50') . $LANG['weveryone'] . '</div>';
                            }

                            if ($postStatus == '0' || $postStatus == '1') {
                                $p_Status = '<div class="p_active flex_ tabing">' . $iN->iN_SelectedMenuIcon('69') . $LANG['active'] . '</div>';
                            } elseif ($postStatus == '2') {
                                $p_Status = '<div class="pe_active flex_ tabing">' . $iN->iN_SelectedMenuIcon('115') . $LANG['pending_approve'] . '</div>';
                            }

                            $seePostButton = $base_url . 'admin/allPosts?post=' . $postID;
                            if ($postWhoCanSeeType == '4') {
                                $seePostButton = $base_url . 'admin/awaiting_approval?post=' . $postID;
                            }
                            ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($postID); ?></td>
                                <td>
                                    <div class="t_od flex_ c6">
                                        <div class="t_owner_avatar border_two tabing flex_"><img src="<?php echo iN_HelpSecure($postOwnerAvatar); ?>"></div>
                                        <div class="t_owner_user tabing flex_">
                                            <a class="truncated" href="<?php echo iN_HelpSecure($base_url) . $postOwnerUserName; ?>"><?php echo iN_HelpSecure($postOwnerUserFullName); ?></a>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify"><?php echo html_entity_decode($postType); ?></div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing">
                                            <?php echo iN_HelpSecure($iN->iN_SelectedMenuIcon('73')) . ' ' . TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify"><?php echo html_entity_decode($p_Status); ?></div>
                                </td>
                                <td class="flex_ tabing">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="delp border_one transition" id="<?php echo iN_HelpSecure($postID); ?>">
                                            <?php echo iN_HelpSecure($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                                        </div>
                                        <div class="seePost c2 border_one transition" id="<?php echo iN_HelpSecure($postID); ?>">
                                            <a href="<?php echo iN_HelpSecure($seePostButton); ?>">
                                                <?php echo iN_HelpSecure($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_post']; ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . iN_HelpSecure($iN->iN_SelectedMenuIcon('54')) . '</div><div class="n_c_t">' . $LANG['no_post_pending_approval'] . '</div></div>';
            } ?>
        </div>

        <div class="i_become_creator_box_footer tabing">
            <?php if ($totalPages > 0): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>">
                                <?php echo iN_HelpSecure($LANG['preview_page']); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if (iN_HelpSecure($pagep) > 3): ?>
                        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=1">1</a></li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php if (iN_HelpSecure($pagep) - 2 > 0): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep - 1 > 0): ?>
                        <li class="page"><a href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a></li>
                    <?php endif; ?>

                    <li class="currentpage active"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                    <?php if ($pagep + 1 < $totalPages + 1): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep + 2 < $totalPages + 1): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>"><?php echo iN_HelpSecure($pagep) + 2; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages - 2): ?>
                        <li class="dots">...</li>
                        <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages): ?>
                        <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
