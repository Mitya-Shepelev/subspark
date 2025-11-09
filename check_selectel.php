<?php
// Diagnostic script for Selectel S3 configuration
echo "=== Selectel S3 Configuration Check ===\n\n";

// Check environment variables
$envVars = [
    'SELECTEL_STATUS',
    'SELECTEL_BUCKET',
    'SELECTEL_REGION',
    'SELECTEL_KEY',
    'SELECTEL_SECRET',
    'SELECTEL_ENDPOINT',
    'SELECTEL_PUBLIC_BASE',
];

echo "Environment Variables:\n";
foreach ($envVars as $var) {
    $value = getenv($var);
    if ($var === 'SELECTEL_SECRET' || $var === 'SELECTEL_KEY') {
        $display = $value ? (substr($value, 0, 4) . '***') : 'NOT SET';
    } else {
        $display = $value ?: 'NOT SET';
    }
    echo "  {$var}: {$display}\n";
}

echo "\n";

// Check if AWS SDK is available
if (file_exists(__DIR__ . '/includes/vendor/autoload.php')) {
    require_once __DIR__ . '/includes/vendor/autoload.php';
    echo "✅ AWS SDK loaded from includes/vendor\n";
} elseif (file_exists(__DIR__ . '/includes/s3/vendor/autoload.php')) {
    require_once __DIR__ . '/includes/s3/vendor/autoload.php';
    echo "✅ AWS SDK loaded from includes/s3/vendor\n";
} else {
    echo "❌ AWS SDK NOT FOUND!\n";
    exit(1);
}

// Load storage functions
require_once __DIR__ . '/includes/object_storage.php';

echo "\nActive Provider: " . storage_active_provider() . "\n";

$config = storage_provider_config();
echo "\nProvider Configuration:\n";
echo "  Provider: " . ($config['provider'] ?? 'none') . "\n";
echo "  Bucket: " . ($config['bucket'] ?? 'NOT SET') . "\n";
echo "  Region: " . ($config['region'] ?? 'NOT SET') . "\n";
echo "  Endpoint: " . ($config['endpoint'] ?? 'NOT SET') . "\n";
echo "  Public Base: " . ($config['public_base'] ?? 'NOT SET') . "\n";

// Test S3 client initialization
$client = storage_client();
if ($client) {
    echo "\n✅ S3 Client initialized successfully\n";
} else {
    echo "\n❌ Failed to initialize S3 Client\n";
    exit(1);
}

echo "\n=== Configuration check complete ===\n";
