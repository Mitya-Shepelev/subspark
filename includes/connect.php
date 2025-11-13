<?php
// +------------------------------------------------------------------------+
// | @author Mustafa Öztürk (mstfoztrk)
// | @author_url 1: http://www.duhovit.com
// | @author_url 2: http://codecanyon.net/user/mstfoztrk
// | @author_email: socialmaterial@hotmail.com
// +------------------------------------------------------------------------+
// | dizzy Support Creators Content Script
// | Copyright (c) 2021 mstfoztrk. All rights reserved.
// +------------------------------------------------------------------------+

// --------------------------------------------------------------------------
// ✅ DATABASE CONFIGURATION
// --------------------------------------------------------------------------
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'subspark');

// --------------------------------------------------------------------------
// ✅ DATABASE CONNECTION (PDO-only)
// --------------------------------------------------------------------------
// Allow environment overrides for deployment flexibility
$__db_server = getenv('DB_SERVER') ?: DB_SERVER;
$__db_user   = getenv('DB_USERNAME') ?: DB_USERNAME;
$__db_pass   = getenv('DB_PASSWORD') ?: DB_PASSWORD;
$__db_name   = getenv('DB_DATABASE') ?: DB_DATABASE;

// Maintain legacy variable for compatibility (no mysqli usage anymore)
$db = null;

try {
    $dsn = "mysql:host={$__db_server};dbname={$__db_name};charset=utf8mb4";
    $pdo = new PDO($dsn, $__db_user, $__db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (Throwable $e) {
    $error = $e->getMessage();
    echo '<pre>
====================================================
❌ DATABASE CONNECTION ERROR (PDO)
====================================================

We were unable to connect to your MySQL database using the
credentials provided in the connect.php file.

Error details:
' . htmlspecialchars($error) . '

Possible causes:
- Incorrect database name
- Invalid username or password
- Database does not exist on the server
- Database server is not running or refusing connection

✅ To fix this, open the file:
  /includes/connect.php

And check the following values:
  - DB_SERVER
  - DB_USERNAME
  - DB_PASSWORD
  - DB_DATABASE

Once you enter the correct credentials, reload the page.
====================================================
</pre>';
    exit();
}

// --------------------------------------------------------------------------
// ✅ BASE URL DETECTION
// --------------------------------------------------------------------------
// Skip URL detection in CLI mode (worker processes)
if (PHP_SAPI === 'cli') {
    // CLI mode: get base URL from environment variable
    $base_url = getenv('APP_URL') ?: getenv('BASE_URL') ?: 'http://localhost/';
    $base_url = rtrim($base_url, '/') . '/';
} else {
    // Web mode: detect base URL robustly (works behind Nginx/proxies and with custom ports)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    // Honor proxy headers if present
    $forwardProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
    $forwardHost  = $_SERVER['HTTP_X_FORWARDED_HOST']  ?? null;
    $forwardPort  = $_SERVER['HTTP_X_FORWARDED_PORT']  ?? null;

    $scheme = $forwardProto ?: $protocol;
    $host   = $forwardHost ?: ($_SERVER['HTTP_HOST'] ?? 'localhost');
    if ($forwardPort && strpos($host, ':') === false) {
        $host .= ':' . $forwardPort;
    }

    $scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
    $rootPath = rtrim(preg_replace('/(\/requests|\/includes|\/themes|\/langs|\/src|\/ajax|\/admin|\/panel).*/i', '', $scriptName), '/');
    $base_url = $scheme . '://' . $host . $rootPath . '/';

    // Allow explicit override via env (e.g., fastcgi_param APP_URL http://example.com/)
    $appUrlOverride = getenv('APP_URL') ?: getenv('BASE_URL');
    if ($appUrlOverride) {
        $base_url = rtrim($appUrlOverride, '/') . '/';
    }
}

// --------------------------------------------------------------------------
// ✅ FILE SYSTEM PATHS
// --------------------------------------------------------------------------
// Absolute document root (web server) — still useful for some contexts
$serverDocumentRoot = (PHP_SAPI === 'cli') ? null : realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

// Absolute application root (handles subfolder installs)
$appRootPath = realpath(dirname(__DIR__)); // e.g., .../htdocs/dizzyv5.3
if (!defined('APP_ROOT_PATH')) {
    define('APP_ROOT_PATH', $appRootPath);
}

// All upload directories are relative to the application root, not DOCUMENT_ROOT
$uploadRoot     = $appRootPath . '/uploads';
$uploadFile     = $uploadRoot . '/files/';
$xVideos        = $uploadRoot . '/xvideos/';
$xImages        = $uploadRoot . '/pixel/';
$uploadCover    = $uploadRoot . '/covers/';
$uploadAvatar   = $uploadRoot . '/avatars/';
$uploadIconLogo = $appRootPath . '/img/';
$uploadAdsImage = $uploadRoot . '/spImages/';

// --------------------------------------------------------------------------
// ✅ META BASE & COOKIE
// --------------------------------------------------------------------------
$metaBaseUrl = $base_url;
$cookieName = 'dizzy';

// No mysqli shutdown needed in PDO-only mode
