<?php
$totalnApprovedPosts = $iN->iN_CalculateAllUnReadReportedComments();
$totalPages = ceil($totalnApprovedPosts / $paginationLimit);

if (isset($_GET["page-id"])) {
    $pagep = isset($_GET["page-id"]) ? (int)$_GET["page-id"] : 1;
    $pagep = preg_match('/^[0-9]+$/', $pagep) ? $pagep : '1';
} else {
    $pagep = '1';
}
?>
<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['reported_posts']); ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']); ?></div>
            <?php
            $reportedPostList = $iN->iN_AllTypeReportedCommentList($userID, $paginationLimit, $pagep);
            if ($reportedPostList) {
            ?>
            <div class="i_overflow_x_auto">
                <table class="border_one">
                    <tr>
                        <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['reporter']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['reported_post']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['report_time']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['report_status']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['actions']); ?></th>
                    </tr>
                    <?php
                    foreach ($reportedPostList as $rData) {
                        $qID = $rData['p_report_id'] ?? null;
                        $reportedPostID = $rData['reported_comment'] ?? null;
                        $reportedPostIDFk = $rData['comment_post_id_fk'] ?? null;
                        $rUserID = $rData['iuid_fk'] ?? null;
                        $qContacttime = $rData['report_time'] ?? null;
                        $qContactReadStatus = $rData['report_status'] ?? null;
                        $crTime = date('Y-m-d H:i:s', $qContacttime);
                        $p_Status = $qContactReadStatus == '0' 
                            ? '<div class="p_active flex_ tabing">' . $iN->iN_SelectedMenuIcon('115') . $LANG['not_answered'] . '</div>' 
                            : '<div class="pe_active flex_ tabing">' . $iN->iN_SelectedMenuIcon('69') . $LANG['q_answered'] . '</div>';
                        $userDetail = $iN->iN_GetUserDetails($rUserID);
                        $rPostUserAvatar = $iN->iN_UserAvatar($rUserID, $base_url);
                        $rUserName = $userDetail['i_username'] ?? null;
                        $rUserFullName = $userDetail['i_user_fullname'] ?? null;
                        $postDetails = $iN->iN_GetAllPostDetails($reportedPostID);
                    ?>
                    <tr class="transition trhover">
                        <td><?php echo iN_HelpSecure($qID); ?></td>
                        <td>
                            <div class="t_od flex_ c6">
                                <div class="t_owner_avatar border_two tabing flex_">
                                    <img src="<?php echo iN_HelpSecure($rPostUserAvatar, FILTER_VALIDATE_URL); ?>">
                                </div>
                                <div class="t_owner_user tabing flex_">
                                    <a class="truncated" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL) . $rUserName; ?>"><?php echo iN_HelpSecure($rUserFullName); ?></a>
                                </div>
                            </div>
                        </td>
                        <td class="see_post_details" id="<?php echo iN_HelpSecure($qID); ?>">
                            <div class="flex_ tabing_non_justify plink_">
                                <a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL) . 'post/' . iN_HelpSecure($reportedPostIDFk); ?>#i_user_comments_<?php echo iN_HelpSecure($reportedPostID); ?>" target="blank_">
                                    <div class="pe_active flex_ tabing">
                                        <?php echo $iN->iN_SelectedMenuIcon('10') . $LANG['check_reported_comment']; ?>
                                    </div>
                                </a>
                            </div>
                        </td>
                        <td class="see_post_details">
                            <div class="flex_ tabing_non_justify">
                                <div class="tim flex_ tabing">
                                    <?php echo iN_HelpSecure($iN->iN_SelectedMenuIcon('73')) . ' ' . TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
                                </div>
                            </div>
                        </td>
                        <td class="see_post_details">
                            <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                                <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['not_checked']); ?></div>
                                <label class="el-switch el-switch-yellow" for="rcCheckStatus<?php echo $qID; ?>">
                                    <input type="checkbox" name="rcCheckStatus" class="rcchmdReport q<?php echo $qID; ?>" id="rcCheckStatus<?php echo $qID; ?>" data-id="<?php echo $qID; ?>" <?php echo iN_HelpSecure($qContactReadStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
                                    <span class="el-switch-style"></span>
                                </label>
                                <input type="hidden" name="rcCheckStatus" class="rcCheckStatus" value="<?php echo iN_HelpSecure($qContactReadStatus); ?>">
                                <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['checked']); ?></div>
                                <div class="success_tick tabing flex_ sec_one rcCheckStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?></div>
                            </div>
                        </td>
                        <td class="flex_ tabing">
                            <div class="flex_ tabing_non_justify">
                                <div class="delrc border_one transition" id="<?php echo iN_HelpSecure($qID); ?>">
                                    <?php echo $iN->iN_SelectedMenuIcon('28') . $LANG['delete']; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php
            } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . iN_HelpSecure($iN->iN_SelectedMenuIcon('54')) . '</div><div class="n_c_t">' . $LANG['no_question_pending'] . '</div></div>';
            }
            ?>
        </div>

        <div class="i_become_creator_box_footer tabing">
            <?php if ($totalPages > 0): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep > 3): ?>
                        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=1">1</a></li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php if ($pagep - 2 > 0): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a></li>
                    <?php endif; ?>
                    <?php if ($pagep - 1 > 0): ?>
                        <li class="page"><a href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a></li>
                    <?php endif; ?>

                    <li class="currentpage active"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                    <?php if ($pagep + 1 <= $totalPages): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/allPosts?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a></li>
                    <?php endif; ?>
                    <?php if ($pagep + 2 <= $totalPages): ?>
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
