<?php
include("../includes/inc.php");

function set_secure_cookie($name, $value) {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie($name, $value, [
        'expires'  => time() + 31556926, // 1 year
        'path'     => '/',
        'secure'   => $secure,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
}

if (isset($_POST['username']) && isset($_POST['password'])) {
    // Backwards-compatible CSRF: if token provided, require it; otherwise allow for legacy clients
    if (isset($_POST['csrf_token']) || isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        include_once __DIR__ . '/../includes/csrf.php';
        if (!csrf_validate_from_request()) {
            exit($LANG['invalid_csrf_token']);
        }
    }
    $usernameInput = trim($_POST['username']);
    $passwordInput = (string)($_POST['password']);

    // Fetch user by username or email (PDO-only)
    $row = DB::one("SELECT iuid,i_username,i_user_email,i_password FROM i_users WHERE i_username = ? OR i_user_email = ? LIMIT 1", [$usernameInput, $usernameInput]);
    
    $isValid = false;
    if ($row) {
        $userID = (int)$row['iuid'];
        $userUsername = $row['i_username'];
        $storedHash = (string)$row['i_password'];

        // New scheme: password_hash
        if (strlen($storedHash) >= 60 && (str_starts_with($storedHash, '$2y$') || str_starts_with($storedHash, '$argon2'))) {
            $isValid = password_verify($passwordInput, $storedHash);
        } else {
            // Legacy check: sha1(md5(password))
            $legacy = sha1(md5($passwordInput));
            $isValid = hash_equals($storedHash, $legacy);
            // On legacy success, upgrade hash
            if ($isValid) {
                $newHash = password_hash($passwordInput, PASSWORD_DEFAULT);
                DB::exec("UPDATE i_users SET i_password = ? WHERE iuid = ?", [$newHash, $userID]);
            }
            }
        }

        if ($isValid) {
            $time = time();
            // Update last login time
            DB::exec("UPDATE i_users SET last_login_time = ? WHERE iuid = ?", [$time, $userID]);

            // Generate strong session key
            $hash = bin2hex(random_bytes(32));
            set_secure_cookie($cookieName, $hash);

            DB::exec("INSERT INTO i_sessions (session_uid, session_key, session_time) VALUES (?,?,?)", [$userID, $hash, $time]);

            $_SESSION['iuid'] = $userID;
            echo 'go_inside';
            exit;
        }
    
    exit($LANG['invalid_username_or_password']);
} else {
    exit($LANG['please_fill_all_fields']);
}
?>
