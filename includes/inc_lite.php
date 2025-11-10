<?php
/**
 * Lightweight Initialization for AJAX Requests
 *
 * This file provides minimal initialization for AJAX/API endpoints,
 * loading only essential components to improve performance.
 *
 * OPTIMIZATION: Reduces initial load time by 100-200ms compared to full inc.php
 *
 * What's loaded:
 * - Database connection and PDO setup
 * - Core functions class
 * - Session management
 * - CSRF protection
 *
 * What's NOT loaded (saves ~100-200ms):
 * - Stripe library (load via loadStripe() when needed)
 * - Full configuration (load specific values as needed)
 * - Page list
 * - Language packs (load only when rendering views)
 * - Premium plans and payment methods
 * - Theme settings
 */

ob_start();
session_start();

// Database connection and base setup
include_once "connect.php";

// Error reporting: environment-aware
$env = 'development';
if (defined('APP_ENV')) {
    $env = APP_ENV;
} elseif (($v = getenv('APP_ENV'))) {
    $env = $v;
} elseif (isset($_SERVER['APP_ENV'])) {
    $env = $_SERVER['APP_ENV'];
} else {
    $envPath = dirname(__DIR__) . '/.env';
    if (is_file($envPath)) {
        $envContent = @file_get_contents($envPath);
        if ($envContent !== false && preg_match('/^APP_ENV\s*=\s*([\w\-\"]+)/mi', $envContent, $m)) {
            $env = trim($m[1], "\"' ");
        }
    }
}
$env = strtolower((string)$env);

if ($env === 'production') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
} else {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Core required files only
include_once "functions.php";
include_once "csrf.php";
include_once "db.php";
include_once "helper.php";

// Stripe library lazy-loader function
if (!function_exists('loadStripe')) {
    function loadStripe() {
        static $loaded = false;
        if (!$loaded) {
            require_once __DIR__ . '/stripe/vendor/autoload.php';
            $loaded = true;
        }
    }
}

// Class initialization
$iN = new iN_UPDATES($db);
$pdo && DB::init($pdo);

// Load minimal configuration (only what's needed for most AJAX requests)
$inc = $iN->iN_Configurations();

// Set default timezone
date_default_timezone_set('UTC');

/**
 * Session & Cookie Check
 * Lightweight user authentication check
 */
$hash = isset($_COOKIE[$cookieName]) ? $_COOKIE[$cookieName] : NULL;
$sessionUserID = isset($_SESSION['iuid']) ? $_SESSION['iuid'] : NULL;

if (!empty($hash)) {
    $row = DB::one("SELECT session_uid FROM i_sessions WHERE session_key = ? LIMIT 1", [$hash]);
    $sessionUserID = $row['session_uid'] ?? null;

    if (empty($sessionUserID)) {
        header("Location: " . route_url('logout.php'));
        exit;
    } else {
        $_SESSION['iuid'] = $sessionUserID;
    }
}

// Determine if user is logged in
$logedIn = !empty($sessionUserID) ? '1' : '0';
$userID = $sessionUserID;

// Get essential user data for logged-in users only
if ($logedIn == '1') {
    $userData = DB::one("SELECT iuid, i_username, user_type, uStatus FROM i_users WHERE iuid = ? LIMIT 1", [(int)$userID]);
    $userID = $userData['iuid'] ?? null;
    $userName = $userData['i_username'] ?? null;
    $userType = $userData['user_type'] ?? null;
    $userStatus = $userData['uStatus'] ?? null;

    // Check if user account is active
    if ($userStatus != '1' && $userStatus != '3') {
        header("Location: " . route_url('logout.php'));
        exit;
    }
} else {
    $userID = null;
    $userName = null;
    $userType = null;
}

// Extract commonly used config values
$stripeStatus = isset($inc['stripe_status']) ? $inc['stripe_status'] : '0';
$stripeKey = isset($inc['stripe_secret_key']) ? $inc['stripe_secret_key'] : NULL;
$stripePublicKey = isset($inc['stripe_publishable_key']) ? $inc['stripe_publishable_key'] : NULL;
$subscriptionType = isset($inc['subscription_type']) ? $inc['subscription_type'] : NULL;
$defaultLanguage = $inc['default_language'] ?? 'eng';

?>
