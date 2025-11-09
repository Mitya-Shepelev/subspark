<?php

$totalUsers = $iN->iN_TotalUsersWithdrawals();
$totalPages = ceil($totalUsers / $paginationLimit);

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
            <?php echo iN_HelpSecure($LANG['manage_withdrawals']) . '(' . $totalUsers . ')'; ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']); ?></div>
            <?php
            $allUsers = $iN->iN_PayoutWithdrawalAndSubscriptionHistory($userID, $paginationLimit, $pagep, 'withdrawal');
            if ($allUsers) {
            ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <td><div class="tab_item"><?php echo iN_HelpSecure($LANG['id']); ?></div></td>
                            <td><div class="tab_item"><?php echo iN_HelpSecure($LANG['user']); ?></div></td>
                            <td><div class="tab_item"><?php echo iN_HelpSecure($LANG['amount']); ?></div></td>
                            <td><div class="tab_item"><?php echo iN_HelpSecure($LANG['date']); ?></div></td>
                            <td><div class="tab_item"><?php echo iN_HelpSecure($LANG['payment_method']); ?></div></td>
                            <td><div class="tab_item"><?php echo iN_HelpSecure($LANG['status']); ?></div></td>
                            <td><div class="tab_item"><?php echo iN_HelpSecure($LANG['details']); ?></div></td>
                            <td><div class="tab_item"><?php echo iN_HelpSecure($LANG['actions']); ?></div></td>
                        </tr>
                        <?php
                        foreach ($allUsers as $payoutData) {
                            $payoutUserID = $payoutData['iuid_fk'] ?? null;
                            $payoutID = $payoutData['payout_id'] ?? null;
                            $payoutAmount = $payoutData['amount'] ?? null;
                            $payoutTime = $payoutData['payout_time'] ?? null;
                            $payoutMethod = $payoutData['method'] ?? null;
                            $payoutStatus = $payoutData['status'] ?? null;
                            $patmentType = $payoutData['payment_type'] ?? null;
                            $myDateTime = date('d/m/Y', $payoutTime);
                            $userAvatar = $iN->iN_UserAvatar($payoutUserID, $base_url);
                            $userDetails = $iN->iN_GetUserDetails($payoutUserID);
                            $userUserName = $userDetails['i_username'] ?? null;
                            $userUserFullName = $userDetails['i_user_fullname'] ?? null;
                            $pStatus = '';
                            if ($payoutStatus == 'pending') {
                                $pS = $LANG['pending'];
                                $psColor = 'withdrawalColorPending';
                                $pStatus = '<div class="seePost c3 border_one transition tabing flex_ mark_as_paid" id="' . $payoutID . '">' . $iN->iN_SelectedMenuIcon('128') . $LANG['paid'] . '</div>';
                                $pStatusDecline = '<div class="seePost c4 border_one transition tabing flex_ decline_this" id="' . $payoutID . '">' . $iN->iN_SelectedMenuIcon('129') . $LANG['decline'] . '</div>';
                            } elseif ($payoutStatus == 'payed') {
                                $psColor = 'withdrawalColorPaid';
                                $pS = $LANG['paid'];
                                $pStatusDecline = '';
                            } elseif ($payoutStatus == 'declined') {
                                $pS = $LANG['declined'];
                                $psColor = 'withdrawalColorDeclined';
                                $pStatusDecline = '';
                            }
                        ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($payoutID); ?></td>
                                <td>
                                    <div class="t_od flex_ c6">
                                        <div class="t_owner_avatar border_two tabing flex_">
                                            <img src="<?php echo iN_HelpSecure($userAvatar, FILTER_VALIDATE_URL); ?>">
                                        </div>
                                        <div class="t_owner_user tabing flex_">
                                            <a class="truncated" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL) . $userUserName; ?>"><?php echo iN_HelpSecure($userUserFullName); ?></a>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing">
                                            <?php echo iN_HelpSecure($payoutAmount) . $currencys[$defaultCurrency]; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing">
                                            <?php echo iN_HelpSecure($myDateTime); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="tim flex_ tabing">
                                            <?php echo iN_HelpSecure($payoutMethod); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="forpending flex_ tabing <?php echo $psColor; ?>">
                                            <?php echo iN_HelpSecure($pS); ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="seeDetail show_withdraw_details border_one transition tabing flex_ delete" id="<?php echo iN_HelpSecure($payoutID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('48')) . $LANG['details']; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="flex_ tabing_non_justify">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="delu del_upout border_one transition tabing flex_ delete" id="<?php echo iN_HelpSecure($payoutID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                                        </div>
                                        <?php echo html_entity_decode($pStatusDecline);
                                        echo html_entity_decode($pStatus); ?>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div><div class="n_c_t">' . $LANG['no_withdrawal_request_yet'] . '</div></div>';
            } ?>
        </div>
        <div class="i_become_creator_box_footer tabing">
            <?php if (ceil($totalUsers / $paginationLimit) > 0) : ?>
                <ul class="pagination">
                    <?php if ($pagep > 1) : ?>
                        <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a></li>
                    <?php endif; ?>
                    <?php if ($pagep > 3) : ?>
                        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=1">1</a></li>
                        <li class="dots">...</li>
                    <?php endif; ?>
                    <?php if ($pagep - 2 > 0) : ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a></li>
                    <?php endif; ?>
                    <?php if ($pagep - 1 > 0) : ?>
                        <li class="page"><a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a></li>
                    <?php endif; ?>
                    <li class="currentpage active"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a></li>
                    <?php if ($pagep + 1 < ceil($totalUsers / $paginationLimit) + 1) : ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a></li>
                    <?php endif; ?>
                    <?php if ($pagep + 2 < ceil($totalUsers / $paginationLimit) + 1) : ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>"><?php echo iN_HelpSecure($pagep) + 2; ?></a></li>
                    <?php endif; ?>
                    <?php if ($pagep < ceil($totalUsers / $paginationLimit) - 2) : ?>
                        <li class="dots">...</li>
                        <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=<?php echo ceil($totalUsers / $paginationLimit); ?>"><?php echo ceil($totalUsers / $paginationLimit); ?></a></li>
                    <?php endif; ?>
                    <?php if ($pagep < ceil($totalUsers / $paginationLimit)) : ?>
                        <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/manage_withdrawals?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
