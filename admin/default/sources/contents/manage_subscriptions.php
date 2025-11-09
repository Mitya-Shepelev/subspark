<?php

$totalnSubscriptions = $iN->iN_CalculateAllSubscriptions();
$totalPages = ceil($totalnSubscriptions / $paginationLimit);

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
            <?php echo iN_HelpSecure($LANG['manage_subscriptions']) . '(' . $totalnSubscriptions . ')'; ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="i_contents_section flex_ tabing manage_margin_bottom">
                <div class="row_wrapper">
                    <div class="row_item flex_ column border_one c1">
                        <div class="chart_row_box_title flex_ tabing_non_justify">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')) . iN_HelpSecure($LANG['all_subscriptions']); ?>
                        </div>
                        <div class="chart_row_box_sum">
                            <span class="count-num"><?php echo iN_HelpSecure($iN->iN_CalculateAllSubscriptions()); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row_wrapper">
                    <div class="row_item flex_ column border_one c2">
                        <div class="chart_row_box_title flex_ tabing_non_justify">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')) . iN_HelpSecure($LANG['active_subscriptions']); ?>
                        </div>
                        <div class="chart_row_box_sum">
                            <span class="count-num"><?php echo iN_HelpSecure($iN->iN_CalculateAllActiveSubscriptions('active')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row_wrapper">
                    <div class="row_item flex_ column border_one c3">
                        <div class="chart_row_box_title flex_ tabing_non_justify">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')) . iN_HelpSecure($LANG['inactive_subscriptions']); ?>
                        </div>
                        <div class="chart_row_box_sum">
                            <span class="count-num"><?php echo iN_HelpSecure($iN->iN_CalculateAllActiveSubscriptions('inactive')); ?></span>
                        </div>
                    </div>
                </div>

                <div class="row_wrapper">
                    <div class="row_item flex_ column border_one c4">
                        <div class="chart_row_box_title flex_ tabing_non_justify">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')) . iN_HelpSecure($LANG['declined_subscriptions']); ?>
                        </div>
                        <div class="chart_row_box_sum">
                            <span class="count-num"><?php echo iN_HelpSecure($iN->iN_CalculateAllActiveSubscriptions('declined')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']); ?></div>

            <?php
            $ApprovedPosts = $iN->iN_SubscriptionsListData($userID, $paginationLimit, $pagep);
            if ($ApprovedPosts) {
            ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['subscriber']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['subscribed_creator']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['subscription_date']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['reneval_date']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['subscription_type']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['subscription_status']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['admin_earned']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['user_earned']); ?></th>
                        </tr>
                        <?php
                        foreach ($ApprovedPosts as $payedSub) {
                            $payedSubscriptionID = $payedSub['subscription_id'];
                            $payedSubscriberUidFk = $payedSub['iuid_fk'];
                            $payedSubscribedUidFk = $payedSub['subscribed_iuid_fk'];
                            $subscriptionPlanInterval = $payedSub['plan_interval'];
                            $subscriptionStatus = $payedSub['status'];
                            $subscriptionAdminEarned = $payedSub['admin_earning'];
                            $subscriptionUserEarned = $payedSub['user_net_earning'];
                            $subscritionReneval = date('d/m/Y', strtotime($payedSub['plan_period_end']));
                            $subscribtionStarted = date('d/m/Y', strtotime($payedSub['created']));
                            $payedAmount = $payedSub['plan_amount'];
                            $adminEarning = $payedSub['admin_earning'];
                            $netEarning = $payedAmount - $adminEarning;

                            $payedSubscriberAvatar = $iN->iN_UserAvatar($payedSubscriberUidFk, $base_url);
                            $payedSubscribedAvatar = $iN->iN_UserAvatar($payedSubscribedUidFk, $base_url);

                            $patedUserData = $iN->iN_GetUserDetails($payedSubscriberUidFk);
                            $payedSubscriberUserName = $patedUserData['i_username'];
                            $payedSubscriberUserFullName = $patedUserData['i_user_fullname'];

                            $paUserData = $iN->iN_GetUserDetails($payedSubscribedUidFk);
                            $payerSubscriberUserName = $paUserData['i_username'];
                            $payerSubscriberUserFullName = $paUserData['i_user_fullname'];

                            $subscriptionPlanInterval = match ($subscriptionPlanInterval) {
                                'week' => '<div class="sWeekly c2">' . $LANG['weekly'] . '</div>',
                                'month' => '<div class="sWeekly c3">' . $LANG['monthly'] . '</div>',
                                default => '<div class="sWeekly c4">' . $LANG['yearly'] . '</div>',
                            };

                            $subscriptionStatus = match ($subscriptionStatus) {
                                'active' => '<div class="sWeekly c8">' . $LANG['active'] . '</div>',
                                'inactive' => '<div class="sWeekly c7">' . $LANG['inactive'] . '</div>',
                                default => '<div class="sWeekly c4">' . $LANG['declined'] . '</div>',
                            };
                        ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($payedSubscriptionID); ?></td>
                                <td>
                                    <div class="t_od flex_ c6">
                                        <div class="t_owner_avatar border_two tabing flex_">
                                            <img src="<?php echo iN_HelpSecure($payedSubscriberAvatar); ?>">
                                        </div>
                                        <div class="t_owner_user tabing flex_">
                                            <a class="truncated" href="<?php echo iN_HelpSecure($base_url) . $payedSubscriberUserName; ?>">
                                                <?php echo iN_HelpSecure($payedSubscriberUserFullName); ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="t_od flex_ c6">
                                        <div class="t_owner_avatar border_two tabing flex_">
                                            <img src="<?php echo iN_HelpSecure($payedSubscribedAvatar); ?>">
                                        </div>
                                        <div class="t_owner_user tabing flex_">
                                            <a class="truncated" href="<?php echo iN_HelpSecure($base_url) . $payerSubscriberUserName; ?>">
                                                <?php echo iN_HelpSecure($payerSubscriberUserFullName); ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo iN_HelpSecure($subscribtionStarted); ?></div></td>
                                <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo iN_HelpSecure($subscritionReneval); ?></div></td>
                                <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo html_entity_decode($subscriptionPlanInterval); ?></div></td>
                                <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo html_entity_decode($subscriptionStatus); ?></div></td>
                                <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo iN_HelpSecure($currencys[$defaultCurrency]) . iN_HelpSecure($subscriptionAdminEarned); ?></div></td>
                                <td class="see_post_details"><div class="flex_ tabing_non_justify"><?php echo iN_HelpSecure($currencys[$defaultCurrency]) . iN_HelpSecure($subscriptionUserEarned); ?></div></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php
            } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . iN_HelpSecure($iN->iN_SelectedMenuIcon('54')) . '</div><div class="n_c_t">' . $LANG['no_post_pending_approval'] . '</div></div>';
            }
            ?>
        </div>

        <div class="i_become_creator_box_footer tabing">
            <?php if ($totalPages > $paginationLimit): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($LANG['preview_page']); ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if (iN_HelpSecure($pagep) > 3): ?>
                        <li class="start">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=1">1</a>
                        </li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php if (iN_HelpSecure($pagep) - 2 > 0): ?>
                        <li class="page">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=<?php echo iN_HelpSecure($pagep) - 2; ?>"><?php echo iN_HelpSecure($pagep) - 2; ?></a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep - 1 > 0): ?>
                        <li class="page">
                            <a href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=<?php echo iN_HelpSecure($pagep) - 1; ?>"><?php echo iN_HelpSecure($pagep) - 1; ?></a>
                        </li>
                    <?php endif; ?>

                    <li class="currentpage active">
                        <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=<?php echo iN_HelpSecure($pagep); ?>"><?php echo iN_HelpSecure($pagep); ?></a>
                    </li>

                    <?php if ($pagep + 1 < $totalPages + 1): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($pagep) + 1; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep + 2 < $totalPages + 1): ?>
                        <li class="page"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=<?php echo iN_HelpSecure($pagep) + 2; ?>"><?php echo iN_HelpSecure($pagep) + 2; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages - 2): ?>
                        <li class="dots">...</li>
                        <li class="end"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=<?php echo $totalPages; ?>"><?php echo $totalPages; ?></a></li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages): ?>
                        <li class="next">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_subscriptions?page-id=<?php echo iN_HelpSecure($pagep) + 1; ?>"><?php echo iN_HelpSecure($LANG['next_page']); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
