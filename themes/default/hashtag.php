<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>

    <?php
    // Include meta tags, CSS styles, and JavaScript files
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

// Include main header
include("layouts/header/header.php");
?>

<div class="wrapper">
    <?php
    // Set current page
    $page = 'hashtag';

    // Load sidebar, main hashtag content, and right panel
    include("layouts/left_menu.php");
    include("layouts/hashtags.php");
    include("layouts/page_right.php");
    ?>
</div>

</body>
</html>