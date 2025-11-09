<?php
$totalTransactions = $iN->iN_UserTotalTransactionsPoints($userID);
$totalPages = ceil($totalTransactions / $paginationLimit);

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
            <?php echo iN_HelpSecure($LANG['all_point_earning']) . '(' . $totalTransactions . ')'; ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <?php
            $ApprovedPosts = $iN->iN_TransactionsListPoints($userID, $paginationLimit, $pagep);
            if ($ApprovedPosts) {
            ?>
            <div class="i_overflow_x_auto">
                <table class="border_one">
                    <tr>
                        <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['payer']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['paid_to']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['date']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['amount']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['pay_type']); ?></th>
                        <th><?php echo iN_HelpSecure($LANG['status']); ?></th>
                    </tr>
                    <?php
                    foreach ($ApprovedPosts as $pay) {
                        $paymentDataID = $pay['payment_id'] ?? null;
                        $paymentDataPayerUserID = $pay['payer_iuid_fk'] ?? null;
                        $paymentDataPayedUserID = $pay['payed_iuid_fk'] ?? null;
                        $paymentDataPayedProfileID = $pay['payed_profile_id_fk'] ?? null;
                        $payedPostIDfk = $pay['payed_post_id_fk'] ?? null;
                        $paymentDataOrderKey = $pay['order_key'] ?? null;
                        $paymentDataPaymentType = $pay['payment_type'] ?? null;
                        $paymentDataPaymentOption = $pay['payment_option'] ?? null;
                        $paymentDataPaymentTime = $pay['payment_time'] ?? null;
                        $crTime = date('Y/m/d', $paymentDataPaymentTime);
                        $paymentDataPaymentStatus = $pay['payment_status'] ?? null;
                        $paymentDataPaymentAmount = $pay['amount'] ?? null;
                        $paymentDataPaymentFee = $pay['fee'] ?? null;
                        $paymentDataPaymentAdminEarning = $pay['admin_earning'] ?? null;
                        $paymentDataPaymentUserEarning = $pay['user_earning'] ?? null;
                        $payerUserAvatar = $iN->iN_UserAvatar($paymentDataPayerUserID, $base_url);
                        $payerUserName = $iN->iN_GetUserName($paymentDataPayerUserID);
                        $payerUserFullName = $iN->iN_UserFullName($paymentDataPayerUserID);

                        if ($paymentDataPayedUserID) {
                            $payedUserAvatar = $iN->iN_UserAvatar($paymentDataPayedUserID, $base_url);
                            $payedUserName = $iN->iN_GetUserName($paymentDataPayedUserID);
                            $payedUserFullName = $iN->iN_UserFullName($paymentDataPayedUserID);
                        }

                        $postURL = '';
                        if ($payedPostIDfk) {
                            $postData = $iN->iN_GetAllPostDetails($payedPostIDfk);
                            $postURL = $postData['url_slug'] . '_' . $payedPostIDfk;
                        }

                        if ($paymentDataPaymentStatus == 'pending') {
                            if ($paymentDataPaymentOption == 'bank') {
                                $pStat = '<div class="flex_ tabing forpending c1">' . iN_HelpSecure($LANG['pending']) . '</div><div class="seePost c3 border_one transition tabing flex_ check_payment point_earning_point_left" id="' . $paymentDataID . '">' . $LANG['check_bank_payment'] . '</div>';
                            } else {
                                $pStat = '<div class="flex_ tabing fordecs c4">' . iN_HelpSecure($LANG['pending']) . '</div>';
                            }
                        } elseif ($paymentDataPaymentStatus == 'ok') {
                            $pStat = '<div class="flex_ tabing fordecs c2">' . iN_HelpSecure($LANG['success']) . '</div>';
                        } else {
                            $pStat = '<div class="flex_ tabing fordecs c4">' . iN_HelpSecure($LANG['declined']) . '</div>';
                        }

                        $paymentDataCreditPlanID = $pay['credit_plan_id'] ?? null;
                        if ($paymentDataCreditPlanID) {
                            $planAmountData = $iN->GetPlanDetails($paymentDataCreditPlanID);
                            $paymentDataPaymentAmount = $planAmountData['amount'] ?? null;
                        }
                    ?>
                    <tr class="transition trhover">
                        <td><?php echo iN_HelpSecure($paymentDataID); ?></td>
                        <td>
                            <div class="t_od flex_ c6">
                                <div class="t_owner_avatar border_two tabing flex_"><img src="<?php echo iN_HelpSecure($payerUserAvatar); ?>"></div>
                                <div class="t_owner_user tabing flex_"><a class="truncated" href="<?php echo iN_HelpSecure($base_url) . $payerUserName; ?>"><?php echo iN_HelpSecure($payerUserFullName); ?></a></div>
                            </div>
                        </td>
                        <td>
                            <?php if (!$paymentDataPayedUserID) { ?>
                                <div class="t_od flex_ c6 t_od_s"><?php echo iN_HelpSecure($LANG['paid_for_himself']); ?></div>
                            <?php } else { ?>
                                <div class="t_od flex_ c6">
                                    <div class="t_owner_avatar border_two tabing flex_"><img src="<?php echo iN_HelpSecure($payedUserAvatar); ?>"></div>
                                    <div class="t_owner_user tabing flex_"><a class="truncated" href="<?php echo iN_HelpSecure($base_url) . $payedUserName; ?>"><?php echo iN_HelpSecure($payedUserFullName); ?></a></div>
                                </div>
                            <?php } ?>
                        </td>
                        <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo $crTime; ?></div></td>
                        <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo iN_HelpSecure($currencys[$defaultCurrency]) . $paymentDataPaymentAmount; ?></div></td>
                        <td class="see_post_details"><div class="flex_ tabing_non_justify"><span class="flex_ tabing ty_<?php echo iN_HelpSecure($paymentDataPaymentType); ?>"><?php echo iN_HelpSecure($PAYMENTTYPES[$paymentDataPaymentType]); ?></span></div></td>
                        <td class="see_post_details flex_ tabing"><?php echo html_entity_decode($pStat); ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php
            } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div><div class="n_c_t">' . $LANG['no_transactions_yet'] . '</div></div>';
            }
            ?>
        </div>
        <div class="i_become_creator_box_footer tabing">
            <?php if ($totalPages >= 1): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=<?php echo $pagep - 1; ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep > 3): ?>
                        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=1">1</a></li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php if ($pagep - 2 > 0): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=<?php echo $pagep - 2; ?>"><?php echo $pagep - 2; ?></a></li><?php endif; ?>
                    <?php if ($pagep - 1 > 0): ?><li class="page"><a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=<?php echo $pagep - 1; ?>"><?php echo $pagep - 1; ?></a></li><?php endif; ?>

                    <li class="currentpage active"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=<?php echo $pagep; ?>"><?php echo $pagep; ?></a></li>

                    <?php if ($pagep + 1 < $totalPages + 1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=<?php echo $pagep + 1; ?>"><?php echo $pagep + 1; ?></a></li><?php endif; ?>
                    <?php if ($pagep + 2 < $totalPages + 1): ?><li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=<?php echo $pagep + 2; ?>"><?php echo $pagep + 2; ?></a></li><?php endif; ?>

                    <?php if ($pagep < $totalPages - 2): ?>
                        <li class="dots">...</li>
                        <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages): ?>
                        <li class="next"><a class="transition" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>admin/point_earnings?page-id=<?php echo $pagep + 1; ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a></li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
