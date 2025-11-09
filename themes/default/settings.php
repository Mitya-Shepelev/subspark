<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php
        // Load head meta, styles, and scripts
        include("layouts/header/meta.php");
        include("layouts/header/css.php");
        include("layouts/header/javascripts.php");
    ?>
</head>
<body>
    <?php include("layouts/header/header.php"); ?>

    <div class="wrapper">
        <div class="settings_wrapper">
            <?php
                // Include the settings sidebar
                include("settings/settings_menu.php");

                // Load settings page based on the value of $pageGet
                if ($pageGet) {
                    switch ($pageGet) {
                        case 'my_profile':
                            include("settings/profile_settings.php");
                            break;

                        case 'email_settings':
                            include("settings/email_settings.php");
                            break;

                        case 'payments':
                            include("settings/payments.php");
                            break;

                        case 'payout_methods':
                            include("settings/payout_methods.php");
                            break;

                        case 'fees':
                            include("settings/subscription_fees.php");
                            break;

                        case 'payout_history':
                            include("settings/payout_history.php");
                            break;

                        case 'withdrawal':
                            include("settings/withdrawal.php");
                            break;

                        case 'subscription_payments':
                            include("settings/subscription_payments.php");
                            break;

                        case 'subscribers':
                            include("settings/subscribers.php");
                            break;

                        case 'subscriptions':
                            include("settings/subscriptions.php");
                            break;

                        case 'dashboard':
                            include("settings/dashboard.php");
                            break;

                        case 'blocked':
                            include("settings/blocked.php");
                            break;

                        case 'password':
                            include("settings/password.php");
                            break;

                        case 'preferences':
                            include("settings/preferences.php");
                            break;

                        case 'my_payments':
                            include("settings/my_payments.php");
                            break;

                        case 'block_country':
                            include("settings/block_country.php");
                            break;

                        case 'my_followers':
                            include("settings/my_followers.php");
                            break;

                        case 'im_following':
                            include("settings/im_following.php");
                            break;

                        case 'purchased_points':
                            include("settings/purchased_points.php");
                            break;

                        case 'qrCode':
                            include("settings/qrCodeGenerator.php");
                            break;

                        case 'affiliate':
                            include("settings/affilateSettings.php");
                            break;

                        case 'earned_points':
                            include("settings/earnedPoints.php");
                            break;

                        case 'createaProduct':
                            include("settings/createaProduct.php");
                            break;

                        case 'myProducts':
                            include("settings/myProducts.php");
                            break;

                        case 'mySales':
                            include("settings/mySales.php");
                            break;

                        case 'myPurchasedProducts':
                            include("settings/myPurchasedProducts.php");
                            break;

                        case 'videoCallSet':
                            include("settings/videoCallSet.php");
                            break;

                        case 'myframes':
                            include("settings/myframes.php");
                            break;

                        case 'stories':
                            // Special condition for "stories"
                            if (
                                $whoCanShareStory === 'yes' ||
                                ($whoCanShareStory === 'no' && $feesStatus === '2')
                            ) {
                                include("settings/stories.php");
                            } else {
                                header("Location: " . $base_url . "404");
                                exit;
                            }
                            break;

                        default:
                            // Fallback to default page
                            include("settings/profile_settings.php");
                            break;
                    }
                } else {
                    // If no $pageGet is set, load profile settings
                    include("settings/profile_settings.php");
                }
            ?>
        </div>
    </div>
</body>
</html>