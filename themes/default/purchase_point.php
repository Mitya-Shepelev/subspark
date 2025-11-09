<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php
        // Load meta tags, styles and scripts
        include("layouts/header/meta.php");
        include("layouts/header/css.php");
        include("layouts/header/javascripts.php");
    ?>
</head>
<body>
    <?php
        // Show login form if user is not logged in
        if ($logedIn == 0) {
            include('layouts/login_form.php');
        }

        // Include main site header
        include("layouts/header/header.php");
    ?>

    <div class="wrapper bCreatorBg">
        <div class="premium_plans_container">
            <h1><?php echo iN_HelpSecure($LANG['supply_of_wallet']); ?></h1>
            <h2><?php echo iN_HelpSecure($LANG['purchase_of_points']); ?></h2>

            <div class="buyCreditWrapper flex_ tabing">
                <?php
                // Display credit plans if available
                if ($purchasePointPlanTable) {
                    foreach ($purchasePointPlanTable as $planData) {
                        $planID = $planData['plan_id'] ?? null;
                        $planName = $planData['plan_name_key'] ?? null;
                        $planCreditAmount = $planData['plan_amount'] ?? null;
                        $planAmount = $planData['amount'] ?? null;
                ?>
                    <!-- Credit Plan Box -->
                    <div class="credit_plan_box <?php echo iN_HelpSecure($logedIn) == '0' ? 'loginForm' : 'buyPoint'; ?>" id="<?php echo iN_HelpSecure($planID); ?>">
                        <div class="plan_box tabing flex_" id="p_i_<?php echo iN_HelpSecure($planID); ?>">
                            <!-- Plan Name -->
                            <div class="plan_name flex_">
                                <?php echo isset($LANG[$planName]) ? $LANG[$planName] : $planName; ?>
                            </div>

                            <!-- Plan Details -->
                            <div class="plan_value">
                                <div class="plan_price tabing">
                                    <div class="plan_price_in">
                                        <?php echo number_format($planCreditAmount); ?>
                                        <span class="plan_point_icon">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="plan_point tabing flex_">
                                    <?php echo iN_HelpSecure($LANG['points']); ?>
                                </div>

                                <!-- Purchase Button -->
                                <div class="purchaseButton flex_ tabing">
                                    <?php echo iN_HelpSecure($LANG['purchase']); ?>
                                    <strong class="purchaseButton_wrap tabing flex_">
                                        <?php echo number_format($planCreditAmount); ?>
                                        <span class="prcsic">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                        </span>
                                    </strong>
                                    <div class="foramount">
                                        <?php echo iN_HelpSecure($LANG['for']) . ' ' . $currencys[$defaultCurrency] . number_format($planAmount); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Credit Plan Box -->
                <?php
                    }
                }
                ?>
            </div>

            <!-- Footer Section -->
            <div class="general_page_footer">
                <?php include_once("layouts/footer.php"); ?>
            </div>
        </div>
    </div>
</body>
</html>