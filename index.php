<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Load required core files and application configuration
include_once "includes/inc.php";

// Parse the current request URI and make it BASE-URL relative
$rawRequestUri = $_SERVER['REQUEST_URI'] ?? '/';
$pathOnly      = parse_url($rawRequestUri, PHP_URL_PATH) ?? '/';
$queryOnly     = parse_url($rawRequestUri, PHP_URL_QUERY);

// Normalize paths to ensure consistent leading/trailing slashes
$pathOnlyNorm = '/' . ltrim($pathOnly, '/');
$basePath     = parse_url($base_url, PHP_URL_PATH) ?? '/';
$basePathNorm = '/' . trim($basePath, '/');

// Remove the app base path (e.g., /dizzyv5.3) so routing works in subfolders
if ($basePathNorm !== '/' && strpos($pathOnlyNorm, $basePathNorm) === 0) {
    $relativePath = substr($pathOnlyNorm, strlen($basePathNorm));
    if ($relativePath === '') { $relativePath = '/'; }
    elseif ($relativePath[0] !== '/') { $relativePath = '/' . $relativePath; }
} else {
    $relativePath = $pathOnlyNorm;
}

// Normalize to support /index.php/... form (common on Nginx without rewrites)
$normalizedPath = preg_replace('~^/index\.php(?=/|$)~', '', $relativePath);
$normalizedUri  = $normalizedPath . ($queryOnly ? ('?' . $queryOnly) : '');

$requestUrl  = explode('/', $normalizedUri);
$activePage  = end($requestUrl);
$requestUri  = $normalizedUri;

// Initialize path and parameters
$paramsOffset  = strpos($requestUri, '?');
$requestPath   = $page = '';
$requestParams = [];

// Capture GET parameters if present
if ($paramsOffset > -1) {
    $requestPath = substr($requestUri, 0, $paramsOffset);
    $params      = explode('&', substr($requestUri, $paramsOffset + 1));

    foreach ($params as $value) {
        $keyValue = explode('=', $value);
        $requestParams[$keyValue[0]] = isset($keyValue[1]) ? $keyValue[1] : '';
    }
} else {
    $requestPath = $requestUri;
}

// Update user activity if logged in
if ($logedIn == '1') {
    $updateLastSeen = $iN->iN_UpdateLastSeen($userID);
}

// Maintenance mode check
if ($logedIn == '1' && $userType != '2' && $maintenanceMode == '1') {
    include 'sources/maintenance.php';
    exit();
}

// Force email verification if not verified
if (preg_match('~/([\w.-]+)~u', urldecode($requestUri), $match)) {
    $tag      = $match[1];
    $thePage  = trim($match[1]);

    if ($userEmailVerificationStatus == 'no' && $thePage != 'verify' && !empty($smtpEmail)) {
        if ($userType != '2' && $emailSendStatus == '1') {
            include 'sources/verifyme.php';
            exit();
        }
    }
}

// Special base64-decoded page
if (preg_match('~/([\w.-]+)~u', urldecode($requestUri), $match)) {
    $tag     = $match[1];
    $thePage = trim($match[1]);
    if ($thePage == base64_decode('YmVsZWdhbA==')) {
        include('sources/' . $thePage . '.php');
        exit();
    }
}

$reelPath = strtok($requestUri, '?');

if (preg_match('~^/reels(?:/(\d+))?/?$~', urldecode($reelPath), $match)) {
    // Feature gate for Reels page
    if (isset($reelsFeatureStatus) && (string)$reelsFeatureStatus !== '1') {
        header('Location: ' . route_url(''));
        exit();
    }
    $reelID = isset($match[1]) ? (int)$match[1] : null;
    include 'sources/reel_view.php';
    exit();
}

// Special sharer route
if (preg_match('~/([\w.-]+)~u', urldecode($requestUri), $match)) {
    $tag     = $match[1];
    $thePage = trim($match[1]);
    if ($thePage == 'sharer') {
        include('sources/sharer.php');
        exit();
    }
}

// Admin panel route
if (preg_match('~/(admin)/([\w.-]+)~u', urldecode($requestUri), $match)) {
    if ($userType == '1') {
        header('Location: ' . route_url(''));
        exit();
    } else {
        $tag      = $match[1];
        $pageFor  = trim($match[2]);
        include 'admin/' . $adminTheme . '/index.php';
    }

// Routes with slugs (posts, products, etc.)
} else if (preg_match('~/(photos|videos|albums|post|product)/([\w.-]+)~u', urldecode($requestUri), $match)) {
    $urlMatch   = trim($match[1]);
    $slugyUrl   = trim($match[2]);
    $checkUser  = $iN->iN_CheckUserName($urlMatch);

    if ($urlMatch == 'post') {
        include 'sources/post.php';
    } else if ($urlMatch == 'product') {
        include 'sources/product.php';
    }

// Hashtag, explore, live, creator, etc.
} else if (preg_match('~/(hashtag|explore|creator|purchase|live)/([\w.-_]+)~u', urldecode($requestUri), $match)) {
    $tag           = $match[1];
    $urlMatch      = trim($match[1]);
    $pageFor       = $iN->iN_Secure($iN->url_Hash($match[2]));
    $pageForPage   = trim($match[2]);
    $hst           = null;

    if ($urlMatch != 'live') {
        $hst = $iN->iN_GetHashTagsSearch($pageFor, null, $showingNumberOfPost);
    }

    if ($pageForPage == 'becomeCreator') {
        include 'sources/becomeCreator.php';
    } else if ($pageForPage == 'purchase_point') {
        include 'sources/purchase_point.php';
    } else if ($hst) {
        include 'sources/hashtag.php';
    } else {
        $pageFor = preg_replace('/[ ,]+/', '_', trim($pageFor));
        $checkUsername = $iN->iN_CheckUserName($pageFor);

        if ($checkUsername) {
            $getUserID    = $iN->iN_GetUserDetailsFromUsername($pageFor);
            $lUserID      = $getUserID['iuid'];
            $liveDetails  = $iN->iN_GetLiveStreamingDetails($lUserID);

            if ($liveDetails) {
                include 'sources/live.php';
            } else {
                header('Location: ' . route_url('404'));
            }
        } else {
            header('Location: ' . route_url('404'));
        }
    }

// Static routes & fallback
} else if (preg_match('~/([\w.-]+)~u', $requestUri, $match)) {
    $urlMatch     = trim($match[1]);
    $pageGet      = $_GET['tab']      ?? '';
    $pageCategory = $_GET['cat']      ?? '';
    $pageCreator  = $_GET['creator']  ?? '';
    $checkUsername = $iN->iN_CheckUserName($urlMatch);

    if ($pageGet) {
        include 'sources/settings.php';
    } else if ($pageCreator) {
        include 'sources/creators.php';
    } else if ($checkUsername) {
        include 'sources/profile.php';
    } else if ($pageCategory) {
        include 'sources/marketplace.php';
    } else {
        switch ($match[1]) {
            case 'index':
            case 'index.php':
                include 'sources/home.php';
                break;
            case 'settings':
                include 'sources/settings.php';
                break;
            case 'chat':
            case 'chat.php':
                include 'sources/chat.php';
                break;
            case 'notifications':
                include 'sources/notifications.php';
                break;
            case 'payment-success':
            case 'payment-success.php':
                include 'sources/payment-success.php';
                break;
            case 'payment-failed':
            case 'payment-failed.php':
                include 'sources/payment-failed.php';
                break;
            case 'payment-response':
            case 'payment-response.php':
                include 'sources/payment-response.php';
                break;
            case 'creators':
            case 'creators.php':
                include 'sources/creators.php';
                break;
            case 'marketplace':
            case 'marketplace.php':
                include 'sources/marketplace.php';
                break;
            case 'saved':
            case 'saved.php':
                include 'sources/saved.php';
                break;
            case 'googleLogin':
            case 'googleLogin.php':
                include 'sources/googleLogin.php';
                break;
            case 'twitterLogin':
            case 'twitterLogin.php':
                include 'sources/twitterLogin.php';
                break;
            case 'register':
            case 'register.php':
                include 'sources/register.php';
                break;
            case 'reset_password':
            case 'reset_password.php':
                include 'sources/reset_password.php';
                break;
            case 'live_streams':
            case 'live_streams.php':
                include 'sources/live_streams.php';
                break;
            case 'verify':
            case 'verify.php':
                include 'sources/verify.php';
                break;
            case 'createStory':
            case 'createStory.php':
                include 'sources/createStory.php';
                break;
            case 'createReels':
            case 'createReels.php':
                include 'sources/createReels.php';
                break;
            case 'friends_stories':
            case 'friends_stories.php':
                include 'sources/friends_stories.php';
                break;
            default:
                include 'sources/page.php';
        }
    }
} else if ($requestPath == '/' || $requestPath === '') {
    include "sources/home.php";
    exit();
} else {
    header('HTTP/1.0 404 Not Found');
    echo "<h1>" . iN_HelpSecure($LANG['page-not-found']) . "</h1>";
    echo iN_HelpSecure($LANG['sorry-this-page-not-available']);
}
?>
