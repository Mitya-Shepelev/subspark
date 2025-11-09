<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle);?></title>
    <?php
       include("layouts/header/meta.php");
       include("layouts/header/css.php");
       include("layouts/header/javascripts.php");
    ?>
</head>
<body>
<?php if($iN->iN_ShopData($userID, 1) == 'no'){ header('Location: ' . route_url('404')); }?>
<div class="maintenance_container flex_ tabing">
    <div class="maintenance_items">
        <div class="maintenance_img flex_ tabing">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('111'));?>
        </div>
        <div class="maintenance_not">
            <div class="maintenance_title"><?php echo iN_HelpSecure($LANG['maintenance_title']);?></div>
            <div class="maintenance_desc">
               <?php echo iN_HelpSecure($LANG['maintenance_desc']);?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
