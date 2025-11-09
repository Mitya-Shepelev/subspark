<?php
/**
 * Remove encoded HTML entities from the given string.
 *
 * @param string $string
 * @return string
 */
function cleanString($string) {
    return preg_replace("/&#?[a-z0-9]+;/i", "", $string);
}

/**
 * Sanitize and secure a string output for display.
 *
 * @param string  $string           The input string to sanitize.
 * @param int     $censored_words   Not used in current version, reserved for future content moderation.
 * @param bool    $br               Whether to convert new lines to <br> tags.
 * @param int     $strip            Whether to apply stripslashes (1 = yes).
 * @param bool    $cleanString      Whether to remove encoded HTML entities.
 * @param bool    $validate_url     Whether to validate string as a URL.
 * @param string  $allowed_tags     List of allowed HTML tags.
 *
 * @return string|false             Sanitized string or false if URL validation fails.
 */
function iN_HelpSecure($string, $censored_words = 0, $br = true, $strip = 0, $cleanString = true, $validate_url = false, $allowed_tags = '<br><span><strong><b><i>') {
    if (!is_string($string)) {
        $string = (string) $string;
    }

    $string = trim($string);

    if ($validate_url && !filter_var($string, FILTER_VALIDATE_URL)) {
        return false;
    }

    if ($cleanString) {
        $string = preg_replace("/&#?[a-z0-9]+;/i", "", $string);
    }

    $string = strip_tags($string, $allowed_tags);

    if ($br) {
        $string = str_replace(["\r\n", "\n\r", "\r", "\n"], " <br>", $string);
    } else {
        $string = str_replace(["\r\n", "\n\r", "\r", "\n"], "", $string);
    }

    if ($strip == 1) {
        $string = stripslashes($string);
    }

    $string = str_replace('&amp;#', '&#', $string);

    return $string;
}

/**
 * Prepare content safely for display inside a <textarea> element.
 *
 * Converts HTML <br> tags to newline characters and escapes special characters.
 *
 * @param string $string The raw input string (e.g. from database)
 * @return string Sanitized and formatted string for textarea
 */
function iN_SecureTextareaOutput($string) {
    if (!is_string($string)) {
        $string = (string) $string;
    }

    // Convert <br> to actual newline characters
    $string = str_replace(['<br>', '<br/>', '<br />'], "\n", $string);

    // Convert special characters to HTML entities to prevent XSS
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
function iN_HelpSecureUrl($url) {
    return iN_HelpSecure($url, 0, false, 0, true, true, '');
}

/**
 * Build an application URL that works on both Apache and Nginx.
 * On Nginx servers where .htaccess is ignored, we prefix routes with index.php/.
 * Assets should continue to use $base_url directly.
 */
function route_url(string $path = ''): string {
    $base = rtrim($GLOBALS['base_url'] ?? '/', '/');
    $force = getenv('FORCE_INDEX_IN_URLS');
    $preferIndex = false;
    if ($force !== false) {
        $preferIndex = in_array(strtolower((string)$force), ['1','true','yes','on'], true);
    } else {
        $server = $_SERVER['SERVER_SOFTWARE'] ?? '';
        $preferIndex = stripos($server, 'nginx') !== false;
    }
    $path = ltrim($path, '/');
    if ($preferIndex) {
        return $base . '/index.php' . ($path !== '' ? '/' . $path : '/');
    }
    return $base . '/' . $path;
}
?>
