<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>

    <?php
    // Include meta tags, styles, and JavaScript files
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

// Include site header
include("layouts/header/header.php");
?>

<div class="wrapper">
    <?php
    // Include left menu
    include("layouts/left_menu.php");

    // Load page content based on URL
    if ($urlMatch == 'notifications') {
        include("layouts/notifications.php");
    } else if ($urlMatch == 'post') {
        include("layouts/post.php");
    }

    // Include right sidebar
    include("layouts/page_right.php");
    ?>
</div>

</body>
</html>