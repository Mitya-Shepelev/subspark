<?php
// Centralized storage configuration and helpers for uploads.
// Loaded from includes/inc.php after connect.php to ensure $serverDocumentRoot is available.

// Guard against direct access
if (!defined('DIZZY_STORAGE_CONFIG_LOADED')) {
    define('DIZZY_STORAGE_CONFIG_LOADED', true);

    // Root directories (prefer app root for subfolder installs)
    $__APP_ROOT = defined('APP_ROOT_PATH') ? APP_ROOT_PATH : realpath(dirname(__DIR__));
    $UPLOADS_ROOT = rtrim($__APP_ROOT . '/uploads', '/');

    // Directory map
    if (!defined('UPLOAD_DIR_FILES'))  define('UPLOAD_DIR_FILES',  $UPLOADS_ROOT . '/files');
    if (!defined('UPLOAD_DIR_REELS'))  define('UPLOAD_DIR_REELS',  $UPLOADS_ROOT . '/reels');
    if (!defined('UPLOAD_DIR_XVIDEOS'))define('UPLOAD_DIR_XVIDEOS',$UPLOADS_ROOT . '/xvideos');
    if (!defined('UPLOAD_DIR_PIXEL'))  define('UPLOAD_DIR_PIXEL',  $UPLOADS_ROOT . '/pixel');
    if (!defined('UPLOAD_DIR_COVERS')) define('UPLOAD_DIR_COVERS', $UPLOADS_ROOT . '/covers');
    if (!defined('UPLOAD_DIR_AVATARS'))define('UPLOAD_DIR_AVATARS',$UPLOADS_ROOT . '/avatars');
    if (!defined('UPLOAD_DIR_SPIMAGES'))define('UPLOAD_DIR_SPIMAGES',$UPLOADS_ROOT . '/spImages');
    if (!defined('UPLOAD_DIR_VIDEOS')) define('UPLOAD_DIR_VIDEOS', $UPLOADS_ROOT . '/videos');

    // Sensible default limit for reels; prefer admin-configured value from inc.php if available
    if (!defined('MAX_VIDEO_DURATION')) {
        $cfgDur = isset($maxVideoDuration) ? (int)$maxVideoDuration : 17;
        define('MAX_VIDEO_DURATION', $cfgDur > 0 ? $cfgDur : 17);
    }

    /**
     * Ensure a directory exists (mkdir -p) with 0755 perms.
     * Returns true if the directory exists or created successfully.
     */
    function ensureDirectory(string $path): bool {
        if (is_dir($path)) { return true; }
        return @mkdir($path, 0755, true);
    }

    /**
     * Ensure all expected upload directories exist.
     * Creates the base uploads folder and subfolders, and places
     * .htaccess if the top-level uploads directory is missing one.
     */
    function ensureUploadDirectories(): void {
        $dirs = [
            dirname(UPLOAD_DIR_FILES), // uploads
            UPLOAD_DIR_FILES,
            UPLOAD_DIR_REELS,
            UPLOAD_DIR_XVIDEOS,
            UPLOAD_DIR_PIXEL,
            UPLOAD_DIR_COVERS,
            UPLOAD_DIR_AVATARS,
            UPLOAD_DIR_SPIMAGES,
            UPLOAD_DIR_VIDEOS,
        ];
        foreach ($dirs as $d) {
            ensureDirectory($d);
        }

        // Security: ensure uploads/.htaccess prevents PHP execution
        $uploadsHtaccess = dirname(UPLOAD_DIR_FILES) . '/.htaccess';
        if (!is_file($uploadsHtaccess)) {
            @file_put_contents($uploadsHtaccess, "Options -Indexes\n<IfModule mod_php7.c>\n  php_flag engine off\n</IfModule>\n<IfModule mod_php8.c>\n  php_flag engine off\n</IfModule>\n<FilesMatch \\\"\\\\.(php|phtml|php3|php4|php5|php7|phps)$\\\">\n  Require all denied\n</FilesMatch>\nRemoveHandler .php .phtml .php3 .php4 .php5 .php7 .phps\n");
        }
    }
}
