<?php
// Logged-in users always see main layout
if ($logedIn == '1') {
    include("themes/$currentTheme/layouts/main.php");
} else {
    // For guests: show landing when explicitly set to '2', otherwise default to main
    if ($landingPageType == '2') {
        include("themes/$currentTheme/landing.php");
    } else {
        include("themes/$currentTheme/layouts/main.php");
    }
}
?>
