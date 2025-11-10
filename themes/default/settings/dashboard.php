<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_">
         <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('35'));?>
         <?php echo iN_HelpSecure($LANG['dashboard']);?>
       </div>
       <div class="i_moda_header_nt">
         <?php echo html_entity_decode($LANG['can_fined_some_data']);?>
       </div>
    </div>
    <div class="i_settings_wrapper_items">
         <div class="payouts_form_container">
            <!-- Üçlü Özet Kutuları -->
            <div class="chart_row tabing flex_">
                <!-- Kutular: Premium, Sub, Balance -->
                <?php
                $earningBoxes = [
                    ['class' => 'c1', 'icon' => '40', 'title' => 'pc_ce', 'value' => addCommasAndDots($iN->iN_CalculatePremiumEarnings($userID)), 'href' => 'payments', 'hover' => 'premium_question_hover_display'],
                    ['class' => 'c2', 'icon' => '51', 'title' => 'subscription_earnings', 'value' => addCommasAndDots($iN->iN_CalculateSubEarnings($userID)), 'href' => 'subscription_payments', 'hover' => 'subscribe_question_hover_display'],
                    ['class' => 'c3', 'icon' => '77', 'title' => 'balance', 'value' => addCommasAndDots($userWallet), 'href' => 'withdrawal', 'hover' => 'premium_question_hover_display']
                ];
                foreach ($earningBoxes as $box) {
                ?>
                <div class="chart_row_box">
                   <div class="chart_row_box_item <?php echo $box['class']; ?>">
                        <div class="chart_question">
                            <div class="chart_question_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('97'));?></div>
                            <div class="qb">
                                <div class="answer_bubble">
                                    <?php echo iN_HelpSecure($LANG[$box['hover']]);?>
                                </div>
                            </div>
                        </div>
                        <div class="chart_row_box_title tabing_non_justify flex_">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon($box['icon'])).''.iN_HelpSecure($LANG[$box['title']]);?>
                        </div>
                        <div class="chart_row_box_sum">
                            <?php echo $box['value'].' ₽';?>
                        </div>
                        <div class="wmore tabing_non_justify flex_">
                          <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=<?php echo $box['href']; ?>">
                              <?php echo iN_HelpSecure($LANG['view_more']).html_entity_decode($iN->iN_SelectedMenuIcon('98'));?>
                          </a>
                        </div>
                   </div>
                </div>
                <?php } ?>
            </div>

            <div class="i_sub_not"><?php echo iN_HelpSecure($LANG['current_month_earning']);?></div>

            <!-- CHART -->
            <div class="chart_wrapper">
                <canvas id="myChart"></canvas>
            </div>
            <!-- CHART END -->

            <!-- Günlük Kazançlar -->
            <div class="chart_row tabing flex_">
                <?php
                $revenueItems = [
                    ['method' => 'iN_CurrentDayTotalPremiumEarningUser', 'label' => 'revenue_today'],
                    ['method' => 'iN_WeeklyTotalPremiumEarningUser', 'label' => 'revenue_this_week'],
                    ['method' => 'iN_CurrentMonthTotalPremiumEarningUser', 'label' => 'revenue_this_month'],
                    ['method' => 'iN_CalculatePreviousMonthEarning', 'label' => 'revenue_last_month']
                ];
                foreach ($revenueItems as $rev) {
                    $amount = addCommasAndDots($iN->{$rev['method']}($userID));
                    ?>
                    <div class="chart_row_box flex_ tabing column">
                        <div class="revenue_sum_u"><?php echo iN_HelpSecure($amount).' ₽'; ?></div>
                        <div class="revenue_title_u"><?php echo iN_HelpSecure($LANG[$rev['label']]);?></div>
                    </div>
                <?php } ?>
            </div>
         </div>
    </div>
  </div>
</div>
<?php
// Total days in the current month
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));

// Initialize empty arrays to store daily earnings data
$yearMonthTotalySubscriptions = array_fill(0, $daysInMonth, 0);
$yearMonthTotalPointEarnings = array_fill(0, $daysInMonth, 0);
$yearMonthTotalMoneyEarning = array_fill(0, $daysInMonth, 0);

/**
 * Fetch earnings based on SQL query and store in target array
 * 
 * @param string $query SQL query string (with placeholders)
 * @param array  $params Parameters for the SQL query
 * @param array &$targetArray Reference to the target array for storing data
 */
function fetchEarningsByQuery($query, $params, &$targetArray) {
    $rows = DB::all($query, $params);
    foreach ($rows as $row) {
        $dayIndex = (int)$row['dayIndex'];
        $targetArray[$dayIndex] = (float)$row['daily_total'];
    }
}

// Fetch current month's subscription-based earnings
$sqlSubs = "
    SELECT DAY(FROM_UNIXTIME(created)) - 1 AS dayIndex, SUM(user_net_earning) AS daily_total
    FROM i_user_subscriptions
    WHERE MONTH(FROM_UNIXTIME(created)) = MONTH(CURDATE())
      AND YEAR(FROM_UNIXTIME(created)) = YEAR(CURDATE())
      AND (status = 'active' OR in_status = '1')
      AND subscribed_iuid_fk = ?
    GROUP BY dayIndex
";
fetchEarningsByQuery($sqlSubs, [(int)$userID], $yearMonthTotalySubscriptions);

// Fetch point-based earnings (tips, post unlocks, etc.)
$sqlPoints = "
    SELECT DAY(FROM_UNIXTIME(payment_time)) - 1 AS dayIndex, SUM(user_earning) AS daily_total
    FROM i_user_payments
    WHERE MONTH(FROM_UNIXTIME(payment_time)) = MONTH(CURDATE())
      AND YEAR(FROM_UNIXTIME(payment_time)) = YEAR(CURDATE())
      AND payment_status = 'ok'
      AND payment_type IN('post','profile','point','live_stream','tips','live_gift','unlockmessage')
      AND payed_iuid_fk = ?
    GROUP BY dayIndex
";
fetchEarningsByQuery($sqlPoints, [(int)$userID], $yearMonthTotalPointEarnings);

// Fetch product sales earnings
$sqlProducts = "
    SELECT DAY(FROM_UNIXTIME(payment_time)) - 1 AS dayIndex, SUM(user_earning) AS daily_total
    FROM i_user_payments
    WHERE MONTH(FROM_UNIXTIME(payment_time)) = MONTH(CURDATE())
      AND YEAR(FROM_UNIXTIME(payment_time)) = YEAR(CURDATE())
      AND payment_status = 'ok'
      AND payment_type = 'product'
      AND payed_iuid_fk = ?
    GROUP BY dayIndex
";
fetchEarningsByQuery($sqlProducts, [(int)$userID], $yearMonthTotalMoneyEarning);
?>
<!-- Chart.js data injection -->
<script id="chartData" type="application/json">
  <?php echo json_encode([
    'labels' => range(1, $daysInMonth),
    'subscription' => array_values($yearMonthTotalySubscriptions),
    'pointEarnings' => array_values($yearMonthTotalPointEarnings),
    'productEarnings' => array_values($yearMonthTotalMoneyEarning),
    'currency' => ' ₽',
    'labelSub' => iN_HelpSecure($LANG['subscription_earnings']),
    'labelPoint' => iN_HelpSecure($LANG['point_earnings']),
    'labelProduct' => iN_HelpSecure($LANG['product_earning_t']),
  ]); ?>
</script>

<!-- Chart.js library and external dashboard logic -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?php echo $base_url; ?>themes/<?php echo $currentTheme; ?>/js/dashboardChart.js?v=<?php echo iN_HelpSecure(time()); ?>" defer></script>
