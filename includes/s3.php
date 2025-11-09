<?php
// Load core configuration
include_once 'inc.php';

// Load AWS SDK for PHP
include_once 's3/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

// Create an Amazon S3 client instance
try {
    $s3 = new S3Client([
        'version'     => 'latest',
        'region'      => $s3Region, // e.g., 'us-west-1'
        'credentials' => [
            'key'    => $s3Key,        // defined in inc.php securely
            'secret' => $s3SecretKey,  // defined in inc.php securely
        ],
    ]);
} catch (AwsException $e) {
    // Log the error instead of displaying it directly
    error_log("S3 Client Initialization Failed: " . $e->getMessage());
    // Optionally exit or return a JSON error response
    exit(json_encode([
        'status'  => 'error',
        'message' => 'Failed to connect to Amazon S3. Please check credentials.'
    ], JSON_UNESCAPED_UNICODE));
}