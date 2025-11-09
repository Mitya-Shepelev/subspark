<?php
// Start session as early as possible to avoid header warnings
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include_once 'includes/connect.php';
include_once 'includes/db.php';
if (isset($pdo) && $pdo instanceof PDO) { DB::init($pdo); }

/**
 * Securely remove a cookie by setting it to expire
 *
 * @param string $name The cookie name
 */
function removeCookie(string $name): void
{
    // Remove cookie with same path and flags used when set
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie($name, '', [
        'expires'  => time() - 31556926,
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

// Check if the session cookie is set
if (isset($_COOKIE[$cookieName])) {
    $hashCookie = trim($_COOKIE[$cookieName]);

    // Check if a matching session exists in the database
    $sessionData = DB::one("SELECT * FROM i_sessions WHERE session_key = ?", [$hashCookie]);

    if ($sessionData) {
        $loginUserID = (int)$sessionData['session_uid'];
        $loginHash = $sessionData['session_key'];

        // Remove the session from DB
        DB::exec("DELETE FROM i_sessions WHERE session_key = ?", [$loginHash]);

        // Clear cookie and destroy session
        removeCookie($cookieName);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }

        header("Location: $base_url");
        exit();
    } else {
        // Session not found, ensure cleanup
        removeCookie($cookieName);

        $safeCookieValue = trim($_COOKIE[$cookieName]);
        DB::exec("DELETE FROM i_sessions WHERE session_key = ?", [$safeCookieValue]);

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        header("Location: $base_url");
        exit();
    }
} else {
    // No cookie found, attempt cleanup just in case
    if (isset($_COOKIE[$cookieName])) {
        removeCookie($cookieName);
        $safeCookieValue = trim($_COOKIE[$cookieName]);
        DB::exec("DELETE FROM i_sessions WHERE session_key = ?", [$safeCookieValue]);
    }

    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
    header("Location: $base_url");
    exit();
}
