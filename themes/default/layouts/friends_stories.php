<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle);?></title>
    <?php
       include("header/meta.php");
       include("header/css.php");
       include("header/javascripts.php");
    ?>
</head>
<body>
<?php $page = 'moreposts'; if($logedIn == 0){ include('login_form.php'); }?>
<?php include("header/header.php");?>
    <div class="wrapper <?php if($logedIn == 0){echo 'NotLoginYet';}?>">
           <?php
              if($logedIn != 0){ include("left_menu.php");}
              include("friends_stories_list.php");
              include("page_right.php");
           ?>
    </div>
<?php if($logedIn != 0){?>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/sw/lib/hammer.min.js"></script>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/sw/story-view.js?v=3<?php echo iN_HelpSecure($version); ?>"></script>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/sw/storyHandler.js?v=l<?php echo iN_HelpSecure($version); ?>"></script>
<?php }?>
</body>
</html>