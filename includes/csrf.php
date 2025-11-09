<?php
// Lightweight CSRF helper with backwards-compatible optional enforcement

if (session_status() !== PHP_SESSION_ACTIVE) {
    @session_start();
}

function csrf_get_token(): string {
    if (empty($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_token_field(string $name = 'csrf_token'): string {
    $t = htmlspecialchars(csrf_get_token(), ENT_QUOTES, 'UTF-8');
    $n = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    return '<input type="hidden" name="' . $n . '" value="' . $t . '">';
}

function csrf_validate(?string $token): bool {
    if (!isset($_SESSION['csrf_token'])) { return false; }
    if (!is_string($token)) { return false; }
    return hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_validate_from_request(string $param = 'csrf_token'): bool {
    $hdr = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
    $tok = $_POST[$param] ?? $_GET[$param] ?? $hdr;
    if ($tok === null) { return false; }
    return csrf_validate($tok);
}
?>

