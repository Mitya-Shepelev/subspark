<?php
// Load global configuration and environment variables
include_once 'inc.php';

// Load Composer dependencies
require_once __DIR__ . '/vendor/autoload.php';

# Import AWS SDK classes
use Aws\S3\S3Client;
use Aws\S3\MultipartUploader;
use Aws\Exception\MultipartUploadException;
use Aws\Exception\AwsException;
use Aws\Exception\CredentialsException;

try {
    /**
     * Initialize S3 client for Wasabi S3-compatible object storage
     * - Uses custom endpoint format for Wasabi region
     * - Uses path-style requests (Wasabi requires it)
     */
    $s3 = new S3Client([
        'version'                 => 'latest',
        'region'                  => $WasRegion, // e.g., 'us-east-1'
        'endpoint'                => 'https://s3.' . $WasRegion . '.wasabisys.com',
        'use_path_style_endpoint' => true,
        'credentials'             => [
            'key'    => $WasKey,        // Set securely via config or env
            'secret' => $WasSecretKey,  // Set securely via config or env
        ],
    ]);
} catch (CredentialsException $e) {
    // Failed to load credentials
    error_log("S3 Credential Error: " . $e->getMessage());
    exit(json_encode([
        'status'  => 'error',
        'message' => 'Failed to authenticate with Wasabi S3 credentials.'
    ], JSON_UNESCAPED_UNICODE));
} catch (AwsException $e) {
    // General AWS exception
    error_log("S3 Client Initialization Error: " . $e->getMessage());
    exit(json_encode([
        'status'  => 'error',
        'message' => 'Could not initialize Wasabi S3 client.'
    ], JSON_UNESCAPED_UNICODE));
}
?>