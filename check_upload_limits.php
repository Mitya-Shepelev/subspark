<?php
/**
 * Diagnostic script to check upload limits and configuration
 * Access via: https://your-domain.com/check_upload_limits.php
 */

header('Content-Type: text/plain; charset=utf-8');

echo "=== Upload Limits Diagnostic ===\n\n";

// PHP Configuration
echo "PHP Configuration:\n";
echo "  upload_max_filesize: " . ini_get('upload_max_filesize') . "\n";
echo "  post_max_size: " . ini_get('post_max_size') . "\n";
echo "  memory_limit: " . ini_get('memory_limit') . "\n";
echo "  max_execution_time: " . ini_get('max_execution_time') . "s\n";
echo "  max_input_time: " . ini_get('max_input_time') . "s\n";
echo "  max_file_uploads: " . ini_get('max_file_uploads') . "\n";

// Convert to bytes for comparison
function parse_size($size) {
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
    $size = preg_replace('/[^0-9\.]/', '', $size);
    if ($unit) {
        return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    }
    return round($size);
}

$upload_max = parse_size(ini_get('upload_max_filesize'));
$post_max = parse_size(ini_get('post_max_size'));
$memory_limit = parse_size(ini_get('memory_limit'));

echo "\n";
echo "Converted to bytes:\n";
echo "  upload_max_filesize: " . number_format($upload_max) . " bytes (" . round($upload_max/1024/1024, 2) . " MB)\n";
echo "  post_max_size: " . number_format($post_max) . " bytes (" . round($post_max/1024/1024, 2) . " MB)\n";
echo "  memory_limit: " . number_format($memory_limit) . " bytes (" . round($memory_limit/1024/1024, 2) . " MB)\n";

echo "\n";

// Recommendations
$effective_limit = min($upload_max, $post_max);
echo "Effective upload limit: " . round($effective_limit/1024/1024, 2) . " MB\n";

if ($post_max < $upload_max) {
    echo "⚠️  WARNING: post_max_size is smaller than upload_max_filesize!\n";
}

if ($memory_limit < $post_max) {
    echo "⚠️  WARNING: memory_limit is smaller than post_max_size!\n";
}

echo "\n";

// Server Information
echo "Server Information:\n";
echo "  PHP Version: " . PHP_VERSION . "\n";
echo "  Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
echo "  Operating System: " . PHP_OS . "\n";
echo "  Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'Unknown') . "\n";

echo "\n";

// Check if we're behind a proxy
if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) || isset($_SERVER['HTTP_X_REAL_IP'])) {
    echo "⚠️  Running behind a proxy (Nginx Proxy Manager?)\n";
    echo "  Make sure proxy has: client_max_body_size 128M;\n";
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        echo "  X-Forwarded-For: " . $_SERVER['HTTP_X_FORWARDED_FOR'] . "\n";
    }
    if (isset($_SERVER['HTTP_X_REAL_IP'])) {
        echo "  X-Real-IP: " . $_SERVER['HTTP_X_REAL_IP'] . "\n";
    }
}

echo "\n";

// Upload directory check
$upload_dir = dirname(__FILE__) . '/uploads';
if (is_dir($upload_dir)) {
    echo "Upload Directory:\n";
    echo "  Path: " . $upload_dir . "\n";
    echo "  Exists: ✅ Yes\n";
    echo "  Writable: " . (is_writable($upload_dir) ? '✅ Yes' : '❌ No') . "\n";

    // Check disk space
    $free_space = @disk_free_space($upload_dir);
    if ($free_space !== false) {
        echo "  Free space: " . round($free_space/1024/1024/1024, 2) . " GB\n";
    }
} else {
    echo "❌ Upload directory not found: " . $upload_dir . "\n";
}

echo "\n";

// Test file upload form
echo "To test actual file upload, use the form at the bottom of this page.\n";
echo "\n=== End of diagnostic ===\n";

// If accessed via browser, show HTML form
if (php_sapi_name() !== 'cli' && !isset($_GET['text'])) {
    echo "\n\n";
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Upload Test</title>
        <style>
            body { font-family: monospace; padding: 20px; background: #f5f5f5; }
            .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
            .result { background: #000; color: #0f0; padding: 15px; margin: 20px 0; white-space: pre-wrap; }
            form { margin-top: 20px; }
            input[type="file"] { margin: 10px 0; }
            button { background: #007bff; color: white; padding: 10px 20px; border: none; cursor: pointer; }
            button:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>File Upload Test</h2>
            <div class="result"><?php
                // Re-output the diagnostic
                ob_start();
                include(__FILE__);
                ob_end_clean();
            ?></div>

            <?php if (isset($_FILES['test_file'])): ?>
                <div style="background: #dff0d8; padding: 15px; margin: 20px 0; border-radius: 5px;">
                    <h3>Upload Result:</h3>
                    <?php if ($_FILES['test_file']['error'] === UPLOAD_ERR_OK): ?>
                        <p>✅ <strong>Success!</strong></p>
                        <p>File: <?php echo htmlspecialchars($_FILES['test_file']['name']); ?></p>
                        <p>Size: <?php echo number_format($_FILES['test_file']['size']); ?> bytes (<?php echo round($_FILES['test_file']['size']/1024/1024, 2); ?> MB)</p>
                        <p>Type: <?php echo htmlspecialchars($_FILES['test_file']['type']); ?></p>
                    <?php else: ?>
                        <p>❌ <strong>Upload failed!</strong></p>
                        <p>Error code: <?php echo $_FILES['test_file']['error']; ?></p>
                        <p>Error message: <?php
                            $errors = [
                                UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize (' . ini_get('upload_max_filesize') . ')',
                                UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE in HTML form',
                                UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                                UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload',
                            ];
                            echo $errors[$_FILES['test_file']['error']] ?? 'Unknown error';
                        ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <h3>Test File Upload:</h3>
                <input type="file" name="test_file" required>
                <br>
                <button type="submit">Upload Test File</button>
            </form>

            <p style="margin-top: 20px; color: #666; font-size: 12px;">
                Note: This test only checks if PHP can receive the file.
                Files are not saved to disk in this test.
            </p>
        </div>
    </body>
    </html>
<?php
}
