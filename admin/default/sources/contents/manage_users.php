<?php

$searchUser = '';
$totalUsers = $iN->iN_TotalUsers();
$totalPages = ceil($totalUsers / $paginationLimit);

if (isset($_GET["page-id"])) {
    $pagep = (int)$_GET["page-id"];
    if ($pagep <= 0) { $pagep = 1; }
} else {
    $pagep = 1;
}

if (isset($_GET['sr'])) {
    $searchUser = $iN->iN_Secure($_GET['sr']);
}

?>
<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['manage_users']) . '(' . $totalUsers . ')'; ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="i_contents_section flex_ tabing manage_margin_bottom">
                <div class="row_wrapper">
                    <div class="row_item flex_ column border_one c1">
                        <div class="chart_row_box_title flex_ tabing_non_justify">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('12')) . iN_HelpSecure($LANG['male']); ?>
                        </div>
                        <div class="chart_row_box_sum">
                            <span class="count-num"><?php echo iN_HelpSecure($iN->iN_GetTotalUserByGender($userID, 'male')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row_wrapper">
                    <div class="row_item flex_ column border_one c2">
                        <div class="chart_row_box_title flex_ tabing_non_justify">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('13')) . iN_HelpSecure($LANG['female']); ?>
                        </div>
                        <div class="chart_row_box_sum">
                            <span class="count-num"><?php echo iN_HelpSecure($iN->iN_GetTotalUserByGender($userID, 'female')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row_wrapper">
                    <div class="row_item flex_ column border_one c3">
                        <div class="chart_row_box_title flex_ tabing_non_justify">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15')) . iN_HelpSecure($LANG['active_users']); ?>
                        </div>
                        <div class="chart_row_box_sum">
                            <span class="count-num"><?php echo iN_HelpSecure($iN->iN_GetTotalEmailVerifiedUsers($userID, 'yes')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row_wrapper">
                    <div class="row_item flex_ column border_one c4">
                        <div class="chart_row_box_title flex_ tabing_non_justify">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('129')) . iN_HelpSecure($LANG['inactive_users']); ?>
                        </div>
                        <div class="chart_row_box_sum">
                            <span class="count-num"><?php echo iN_HelpSecure($iN->iN_GetTotalEmailVerifiedUsers($userID, 'no')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="i_contents_section flex_ manage_margin_bottom">
                <div class="irow_box_right irow_box_right_style">
                    <div class="rec_not rec_not_style"><?php echo iN_HelpSecure($LANG['search_for_user']); ?></div>
                    <input type="text" class="i_input flex_" id="srcMe" value="<?php echo iN_HelpSecure($searchUser); ?>">
                </div>
                <div class="irow_box_right flex_ tabing irow_box_right_styl">
                    <div class="i_nex_btn_btn search_vl"><?php echo iN_HelpSecure($LANG['search']); ?></div>
                </div>
            </div>

            <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']); ?></div>

            <?php
            $allUsers = $iN->iN_AllTypeOfUsersList($userID, $paginationLimit, $pagep, $searchUser);
            if ($allUsers) {
            ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['user']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['user_type']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['registered_time']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['user_creator_type']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['verification_status']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['actions']); ?></th>
                        </tr>
                        <?php foreach ($allUsers as $uData):
                            $iuID = $uData['iuid'];
                            $userUserName = $uData['i_username'];
                            $userUserFullName = $uData['i_user_fullname'];
                            $userAvatar = $iN->iN_UserAvatar($iuID, $base_url);
                            $registered = date('Y-m-d H:i:s', $uData['registered']);
                            $userUserType = $uData['userType'];
                            $userStatus = $uData['uStatus'];
                            $userCreatorCategory = $uData['profile_category'];
                            $seeEditProfile = $base_url . 'admin/manage_users?user=' . $iuID;
                            $seeProfile = $base_url . $userUserName;
                            $userFeeStatus = $uData['validation_status'];
                            $userCertificationStatus = $uData['certification_status'];
                            $checkFakeUserStatus = $uData['fake_user_status'];

                            $utyp = '<div class="ut tabing flex_ forNormalUser">' . $iN->iN_SelectedMenuIcon('83') . $LANG['normal_user'] . '</div>';
                            if ($userUserType === '2') {
                                $utyp = '<div class="ut tabing flex_ forpremiums">' . $iN->iN_SelectedMenuIcon('121') . $LANG['admin'] . '</div>';
                            }

                            $cType = '-';
                            if (!empty($userCreatorCategory)) {
                                $cat = $PROFILE_CATEGORIES[$userCreatorCategory] ?? $PROFILE_SUBCATEGORIES[$userCreatorCategory] ?? null;
                                if ($cat) {
                                    $cType = '<div class="i_creator_category flex_ tabing">' . $iN->iN_SelectedMenuIcon('65') . $cat . '</div>';
                                }
                            }

                            $verificationStat = '';
                            if ($userCertificationStatus === '2') {
                                $verificationStat = '<div class="ut tabing flex_ forsubs">' . $iN->iN_SelectedMenuIcon('9') . $LANG['premium_user'] . '</div>';
                            } elseif ($userFeeStatus === '1') {
                                $verificationStat = '<div class="ut tabing flex_ forpending bluebg">' . $iN->iN_SelectedMenuIcon('115') . $LANG['verification_pending'] . '</div>';
                            }

                            $vRData = $iN->iN_CheckUserHasVerificationRequest($iuID);
                            if ($vRData && $vRData['request_status'] === '2') {
                                $verificationStat = '<div class="ut tabing flex_ forreject">' . $iN->iN_SelectedMenuIcon('114') . $LANG['request_rejected'] . '</div>';
                            }

                            $fakeType = '';
                            if ($checkFakeUserStatus === '1') {
                                $fakeType = '<div class="ut tabing flex_ fakeUser">' . $LANG['fake_user'] . '</div>';
                            }
                        ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($iuID); ?></td>
                                <td>
                                    <div class="t_od flex_ c6">
                                        <div class="t_owner_avatar border_two tabing flex_">
                                            <img src="<?php echo iN_HelpSecure($userAvatar, FILTER_VALIDATE_URL); ?>">
                                        </div>
                                        <div class="t_owner_user tabing flex_">
                                            <a class="truncated" href="<?php echo iN_HelpSecure($seeProfile, FILTER_VALIDATE_URL); ?>">
                                                <?php echo iN_HelpSecure($userUserFullName); ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify"><?php echo html_entity_decode($utyp); ?></div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73')) . ' ' . TimeAgo::ago($registered, date('Y-m-d H:i:s')); ?></div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify"><?php echo html_entity_decode($cType); ?></div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify"><?php echo html_entity_decode($verificationStat) . html_entity_decode($fakeType); ?></div>
                                </td>
                                <td class="flex_ tabing">
                                    <div class="flex_ tabing_non_justify">
                                        <?php if ($userUserType !== '2'): ?>
                                            <div class="delu del_us border_one transition tabing flex_" id="<?php echo iN_HelpSecure($iuID); ?>">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="seePost c2 border_one transition tabing flex_" id="<?php echo iN_HelpSecure($iuID); ?>">
                                            <a class="tabing flex_" href="<?php echo iN_HelpSecure($seeEditProfile, FILTER_VALIDATE_URL); ?>">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_user_infos']; ?>
                                            </a>
                                        </div>
                                        <div class="seePost c3 border_one transition tabing flex_" id="<?php echo iN_HelpSecure($iuID); ?>">
                                            <a class="tabing flex_" href="<?php echo iN_HelpSecure($seeProfile, FILTER_VALIDATE_URL); ?>" target="_blank">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('83')) . $LANG['see_profile']; ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            <?php
            } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div><div class="n_c_t">' . $LANG['no_user_found'] . '</div></div>';
            }
            ?>
        </div>

        <div class="i_become_creator_box_footer tabing">
            <?php if (ceil($totalUsers / $paginationLimit) > 1): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo iN_HelpSecure($LANG['preview_page']); ?>
                            </a>
                        </li>
                    <?php endif; ?>
        
                    <?php if ($pagep > 3): ?>
                        <li class="start">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=1&sr=<?php echo iN_HelpSecure($searchUser); ?>">1</a>
                        </li>
                        <li class="dots">...</li>
                    <?php endif; ?>
        
                    <?php if ($pagep - 2 > 0): ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo iN_HelpSecure($pagep) - 2; ?>
                            </a>
                        </li>
                    <?php endif; ?>
        
                    <?php if ($pagep - 1 > 0): ?>
                        <li class="page">
                            <a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo iN_HelpSecure($pagep) - 1; ?>
                            </a>
                        </li>
                    <?php endif; ?>
        
                    <li class="currentpage active">
                        <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=<?php echo iN_HelpSecure($pagep); ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                            <?php echo iN_HelpSecure($pagep); ?>
                        </a>
                    </li>
        
                    <?php if ($pagep + 1 < ceil($totalUsers / $paginationLimit) + 1): ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo iN_HelpSecure($pagep) + 1; ?>
                            </a>
                        </li>
                    <?php endif; ?>
        
                    <?php if ($pagep + 2 < ceil($totalUsers / $paginationLimit) + 1): ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo iN_HelpSecure($pagep) + 2; ?>
                            </a>
                        </li>
                    <?php endif; ?>
        
                    <?php if ($pagep < ceil($totalUsers / $paginationLimit) - 2): ?>
                        <li class="dots">...</li>
                        <li class="end">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=<?php echo ceil($totalUsers / $paginationLimit); ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo ceil($totalUsers / $paginationLimit); ?>
                            </a>
                        </li>
                    <?php endif; ?>
        
                    <?php if ($pagep < ceil($totalUsers / $paginationLimit)): ?>
                        <li class="next">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_users?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo iN_HelpSecure($LANG['next_page']); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    window.appBaseUrl = "<?php echo iN_HelpSecure($base_url); ?>";
</script> 
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/manageUsersHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
