<?php
$totalAnnouncement = $iN->iN_TotalAnnouncement();
$totalPages = ceil($totalAnnouncement / $paginationLimit);

if (isset($_GET["page-id"])) {
    $pagep = isset($_GET["page-id"]) ? (int)$_GET["page-id"] : 1;
    if (preg_match('/^[0-9]+$/', $pagep)) {
        $pagep = $pagep;
    } else {
        $pagep = '1';
    }
} else {
    $pagep = '1';
}
?>

<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['manage_announcement']) . '(' . $totalAnnouncement . ')'; ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="new_svg_icon_wrapper margin_bottom_custom_css_js">
                <div class="newpa newstick addNewAnnouncement inline_block">
                    <div class="flex_ tabing_non_justify newSvgCode border_one">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('171')); ?>
                        <?php echo iN_HelpSecure($LANG['create_new_announcement']); ?>
                    </div>
                </div>
            </div>

            <div class="warning_">
                <?php echo iN_HelpSecure($LANG['noway_desc']); ?>
            </div>

            <?php
            $allAnnouncements = $iN->iN_AllCreatedAnnouncement($userID, $paginationLimit, $pagep);
            if ($allAnnouncements) {
            ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['announcement_note']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['number_of_viewers']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['status']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['actions']); ?></th>
                        </tr>

                        <?php
                        foreach ($allAnnouncements as $aData) {
                            $announcementID = $aData['a_id'] ?? null;
                            $announcementNote = $aData['a_text'] ?? null;
                            $announcementStatus = $aData['a_status'] ?? null;
                            $totalViewers = $iN->iN_HowManyUserSeeAnnouncement($userID, $announcementID);
                        ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($announcementID); ?></td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify sim truncated">
                                        <?php echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($announcementNote), $base_url)); ?>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify sim">
                                        <?php echo iN_HelpSecure($totalViewers); ?>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <label class="el-switch el-switch-yellow" for="upAnnon<?php echo iN_HelpSecure($announcementID); ?>">
                                            <input type="checkbox" name="stickerStatus" class="upAnnon" id="upAnnon<?php echo iN_HelpSecure($announcementID); ?>" data-id="<?php echo iN_HelpSecure($announcementID); ?>" data-type="upAnnon" <?php echo iN_HelpSecure($announcementStatus) === 'yes' ? 'value="no" checked="checked"' : 'value="yes"'; ?>>
                                            <span class="el-switch-style"></span>
                                        </label>
                                        <div class="success_tick tabing flex_ sec_one upAnnon<?php echo iN_HelpSecure($announcementID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="flex_ tabing_non_justify">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="delu del_annon border_one transition tabing flex_" id="<?php echo iN_HelpSecure($announcementID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                                        </div>
                                        <div class="seePost edAnnon c2 border_one transition tabing flex_" id="<?php echo iN_HelpSecure($announcementID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_announcement']; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . html_entity_decode($iN->iN_SelectedMenuIcon('54')) . '</div><div class="n_c_t">' . $LANG['no_user_found'] . '</div></div>';
            } ?>
        </div>

        <div class="i_become_creator_box_footer tabing">
            <?php if ($totalPages >= 1): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep > 3): ?>
                        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=1">1</a></li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php if ($pagep - 2 > 0): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep - 1 > 0): ?>
                        <li class="page"><a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a></li>
                    <?php endif; ?>

                    <li class="currentpage active"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>

                    <?php if ($pagep + 1 < $totalPages + 1): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep + 2 < $totalPages + 1): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>"><?php echo iN_HelpSecure($pagep) + 2; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages - 2): ?>
                        <li class="dots">...</li>
                        <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages): ?>
                        <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_stickers?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
