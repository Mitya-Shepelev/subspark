<?php

$totalSocial = $iN->iN_TotalWebsiteSocialNetworks();
$totalPages = ceil($totalSocial / $paginationLimit);

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
            <?php echo iN_HelpSecure($LANG['manage_website_social_profiles']); ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="new_svg_icon_wrapper margin_bottom_custom_css_js">
                <div class="newpa newstick inline_block">
                    <div class="flex_ tabing_non_justify newSvgCode newCreate border_one" data-type="newWebSiteSocialSite">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('126')); ?>
                        <?php echo iN_HelpSecure($LANG['add_new_social_site']); ?>
                    </div>
                </div>
            </div>
            <div class="warning_">
                <?php echo iN_HelpSecure($LANG['noway_desc']); ?>
            </div>
            <?php
            $allSocials = $iN->iN_AllWebsiteSocialNetworkList($userID, $paginationLimit, $pagep);
            if ($allSocials) {
            ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['s_icon']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['social_link_ex']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['skey']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['status']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['action']); ?></th>
                        </tr>
                        <?php
                        foreach ($allSocials as $sData) {
                            $socialID = $sData['id'] ?? null;
                            $socialKey = $sData['skey'] ?? null;
                            $socialLink = $sData['place_holder'] ?? null;
                            $socialStatus = $sData['status'] ?? null;
                            $socialIcon = $sData['social_icon'] ?? null;
                        ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($socialID); ?></td>
                                <td class="see_post_details">
                                    <div class="site_svg_icon flex_ tabing">
                                        <?php echo html_entity_decode($socialIcon); ?>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify sim truncated">
                                        <?php echo iN_HelpSecure($socialLink); ?>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify sim truncated">
                                        <?php echo iN_HelpSecure($socialKey); ?>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <label class="el-switch el-switch-yellow" for="upwSocial<?php echo iN_HelpSecure($socialID); ?>">
                                            <input type="checkbox" name="stickerStatus" class="upwSocial" id="upwSocial<?php echo iN_HelpSecure($socialID); ?>" data-id="<?php echo iN_HelpSecure($socialID); ?>" data-type="upwSocial" <?php echo iN_HelpSecure($socialStatus) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"'; ?>>
                                            <span class="el-switch-style"></span>
                                        </label>
                                        <div class="success_tick tabing flex_ sec_one upwSocial<?php echo iN_HelpSecure($socialID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="flex_ tabing_non_justify">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="delu newCreate border_one transition tabing flex_" data-type="deleteSocialSiteW" id="<?php echo iN_HelpSecure($socialID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                                        </div>
                                        <div class="seePost c2 border_one transition tabing flex_ newCreate" data-type="editWSocialLink" id="<?php echo iN_HelpSecure($socialID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_user_infos']; ?>
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
            <?php if (ceil($totalSocial / $paginationLimit) > 1) : ?>
                <ul class="pagination">
                    <?php if ($pagep > 1) : ?>
                        <li class="prev">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep > 3) : ?>
                        <li class="start">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=1">1</a>
                        </li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php if ($pagep - 2 > 0) : ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep - 1 > 0) : ?>
                        <li class="page">
                            <a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="currentpage active">
                        <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a>
                    </li>

                    <?php if ($pagep + 1 < ceil($totalSocial / $paginationLimit) + 1) : ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep + 2 < ceil($totalSocial / $paginationLimit) + 1) : ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>"><?php echo iN_HelpSecure($pagep) + 2; ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep < ceil($totalSocial / $paginationLimit) - 2) : ?>
                        <li class="dots">...</li>
                        <li class="end">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=<?php echo ceil($totalSocial / $paginationLimit); ?>"><?php echo ceil($totalSocial / $paginationLimit); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep < ceil($totalSocial / $paginationLimit)) : ?>
                        <li class="next">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_website_social_profiles?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
