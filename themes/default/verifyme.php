<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php
        // Load meta tags, CSS, and JavaScript includes
        include("layouts/header/meta.php");
        include("layouts/header/css.php");
        include("layouts/header/javascripts.php");
        $themePathVerify = iN_HelpSecure($base_url) . 'themes/' . iN_HelpSecure($currentTheme); 
    ?> 
    <link rel="stylesheet" href="<?php echo $themePathVerify; ?>/scss/verifyme.css?v=<?php echo iN_HelpSecure($version); ?>">
</head>
<body>
    <?php include("layouts/header/header.php"); ?>

    <div class="wrapper">
        <div class="i_not_found_page transition verif">
            <h1><?php echo iN_HelpSecure($LANG['please_verify_not']); ?></h1>
            <p><?php echo html_entity_decode($LANG['to_receive_confirmation_email']); ?></p>

            <div class="new_s_one new_s_first tabing new_verify">
                <div class="flex_ alignItem tabing sendmeagainconfirm">
                    <?php 
                        echo iN_HelpSecure($LANG['send_confirmation_email']) . 
                             html_entity_decode($iN->iN_SelectedMenuIcon('98')); 
                    ?>
                </div>
            </div>
        </div>
    </div>
<script src="<?php echo $base_url; ?>themes/<?php echo $currentTheme; ?>/js/sendConfirmAgain.js?v=<?php echo iN_HelpSecure(time());?>" defer></script>
<script id="verify-lang-data" type="application/json">
    <?php
    echo json_encode([
        'check_email_address' => iN_HelpSecure($LANG['check_email_address']),
        'confirmation_email_error' => iN_HelpSecure($LANG['confirmation_email_error'])
    ], JSON_UNESCAPED_UNICODE);
    ?>
</script>
</body>
</html>