<?php
/**
 * Worker Bootstrap
 * Minimal initialization for video worker (CLI context)
 * Loads only essential components: DB connection, storage, video processing
 */

// Set default timezone
date_default_timezone_set('UTC');

// Load database connection
require_once __DIR__ . '/connect.php';
require_once __DIR__ . '/db.php';

// Initialize DB class with PDO instance (CRITICAL!)
if (isset($pdo) && $pdo instanceof PDO) {
    DB::init($pdo);
    error_log("[Worker Bootstrap] DB initialized successfully");
} else {
    error_log("[Worker Bootstrap] ERROR: PDO not available");
    throw new RuntimeException('Database connection failed in worker bootstrap');
}

// Load storage configuration
require_once __DIR__ . '/storage_config.php';

// These variables are needed by storage_config.php
// Load from environment or use defaults
$selectelStatus = getenv('SELECTEL_STATUS') ?: '0';
$minioStatus = getenv('MINIO_STATUS') ?: '0';
$s3Status = getenv('S3_STATUS') ?: '0';
$wasabiStatus = getenv('WASABI_STATUS') ?: '0';
$digitalOceanStatus = getenv('SPACES_STATUS') ?: '0';

// Object storage functions
if (file_exists(__DIR__ . '/object_storage.php')) {
    require_once __DIR__ . '/object_storage.php';
}

// Define APP_ROOT_PATH for compatibility
if (!defined('APP_ROOT_PATH')) {
    define('APP_ROOT_PATH', '/var/www/html');
}

error_log("[Worker Bootstrap] Initialized successfully");
