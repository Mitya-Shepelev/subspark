<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>

    <?php
    // Include meta tags, CSS files, and JavaScript files
    include("header/meta.php");
    include("header/css.php");
    include("header/javascripts.php");
    ?>
</head>
<body>
<?php 
// Set default page value
$page = 'moreposts'; 

// If user is not logged in, show login form
if ($logedIn == 0) {
    include('login_form.php');
}
?>

<?php 
// Include top header section
include("header/header.php"); 
?>

<div class="wrapper <?php if ($logedIn == 0) { echo 'NotLoginYet'; } ?>">
    <?php
    // If user is logged in, show left sidebar menu
    if ($logedIn != 0) {
        include("left_menu.php");
    }

    // Include story image generator form
    include("storyImageGeneratorForm.php");

    // Include right sidebar content
    include("page_right.php");
    ?>
</div>
</body>
</html>