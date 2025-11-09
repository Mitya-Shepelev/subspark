<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
// If the user is not logged in, show the login form
if($logedIn == 0){ 
    include('login_form.php'); 
} 
?>

<?php 
// Include the top header section
include("header/header.php"); 
?>

<div class="profile_wrapper" id="prw" data-u="<?php echo iN_HelpSecure($p_profileID);?>">
    <?php
    // Set the current page variable
    $page = 'profile';

    // Define the list of allowed profile content categories
    $pCats = array('photos','videos','audios','products','followers','following','subscribers','reels');

    // Check if a valid category is provided via GET and sanitize it
    if(isset($_GET['pcat']) && $_GET['pcat'] != '' && !empty($_GET['pcat']) && in_array($_GET['pcat'], $pCats)){
        $pCat = isset($_GET['pcat']) ? trim($_GET['pcat']) : 'active_page_menu';
    } else {
        $pCat = 'active_page_menu';
    }

    // Include profile information section
    include("profile/profile_infos.php");

    // If user is not logged in and posts are hidden, show access restriction message
    if($logedIn == 0 && $p_showHidePosts == '1'){
        echo '<div class="th_middle"><div class="pageMiddle"><div id="moreType" data-type="'.$page.'">'.$LANG['just_loged_in_user'].'</div></div></div>';
    } else {
        // If category is valid, show the posts
        $pCats = array('photos','videos','audios','products','followers','following','subscribers','reels');
        if(isset($_GET['pcat']) && $_GET['pcat'] != '' && !empty($_GET['pcat']) && in_array($_GET['pcat'], $pCats)){
            $pCat = trim($_GET['pcat']);
            include("posts.php");
        } else {
            include("posts.php");
        }
    }
    ?>
</div>

<!-- Include audio player script -->
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/greenaudioplayer/audioplayer.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
</body>
</html>
