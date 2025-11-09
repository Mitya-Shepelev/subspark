<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo iN_HelpSecure($siteTitle);?></title>
    <?php
       include __DIR__ . "/layouts/header/meta.php";
       include __DIR__ . "/layouts/header/css.php";
       include __DIR__ . "/layouts/header/javascripts.php";
    ?>
</head>
<body>
<?php if($logedIn == 0){ include __DIR__ . '/layouts/login_form.php'; }?>
<?php include __DIR__ . "/layouts/header/header.php";?>
    <div class="wrapper">
        <div class="i_not_found_page transition">
            <h1><?php echo iN_HelpSecure($LANG['sorry-this-page-not-available']);?></h1>
            <?php echo html_entity_decode($LANG['link-not-found']);?>
            <?php echo html_entity_decode($LANG['not-member-yet']);?>
        </div>
    </div>
</body>
</html>
