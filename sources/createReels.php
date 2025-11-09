<?php 
// Feature gate: disable createReels if reels feature is off
if (isset($reelsFeatureStatus) && (string)$reelsFeatureStatus !== '1') {
    header('Location: ' . route_url(''));
    exit();
}
if ($logedIn === '1') {

    if (isset($_GET['r']) && $_GET['r'] !== '') {
        $fileID = (int)($_GET['r']);

        // Check if the file exists and belongs to the user
        $checkReelsExist = $iN->iN_CheckImageIDExist($fileID, $userID);
        if ($checkReelsExist) {

            // Check if the file is active and usable
            $checkReelsStatus = $iN->iN_CheckUploadStatus($fileID);
            if ($checkReelsStatus) {
                include("themes/$currentTheme/layouts/createReel.php");
                exit;
            }
        }

        // Redirect to 404 if the file doesn't exist or status is invalid
        header('Location: ' . route_url('404'));
        exit;
    } else {
        // Redirect to 404 if the "r" parameter is missing
        header('Location: ' . route_url('404'));
        exit;
    }

} else {
    // Load the landing page depending on configuration
    if ($landingPageType === '1') {
        include("themes/$currentTheme/layouts/main.php");
    } else if ($landingPageType === '2') {
        include("themes/$currentTheme/landing.php");
    }
}
?>
