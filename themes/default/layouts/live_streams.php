<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>

    <?php
    // Meta tags, CSS files, and JavaScript includes
    include 'header/meta.php';
    include 'header/css.php';
    include 'header/javascripts.php';
    ?>
</head>
<body>
    <?php
    // Define default page and include login form if not logged in
    $page = 'moreposts';
    if ($logedIn === 0) {
        include 'login_form.php';
    }

    // Include header section
    include 'header/header.php';
    ?>

    <div class="wrapper <?php echo $logedIn === 0 ? 'NotLoginYet' : ''; ?>">
        <?php
        // If user is logged in, show sidebar
        if ($logedIn !== 0) {
            include 'left_menu.php';
        }

        // Main content area
        include 'live_streaming_list.php';
        include 'page_right.php';
        ?>
    </div>
</body>
</html>