<?php
/*
 | Robust QR generator used by requests/request.php (f=generateQRCode)
 | - Works across servers by avoiding brittle docroot assumptions
 | - Uses endroid/qr-code when available, falls back to phpqrcode otherwise
 | - Optionally overlays the user's avatar (if available & accessible)
 */

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

if ((int)$logedIn === 1) {
    // Build target URL (user profile)
    $url = rtrim($base_url, '/') . '/' . ltrim($userName, '/');

    // Resolve filesystem paths in a server-agnostic way
    $rootPath = dirname(__DIR__); // project root
    $dateDir  = date('Y-m-d');
    $uploadDir = $rootPath . '/uploads/files/' . $dateDir . '/';

    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0755, true);
    }

    // Unique filename
    $qrCodeFile = 'qr_' . uniqid((string)(int)$userID . '_', true) . '.png';
    $fullPath   = $uploadDir . $qrCodeFile;

    // Defaults (kept simple; can be extended later via POST)
    $size   = 512;
    $margin = 10;

    $usedEndroid = false;

    // Try Endroid (preferred)
    try {
        // Load Composer autoload if present
        if (is_file(__DIR__ . '/vendor/autoload.php')) {
            require_once __DIR__ . '/vendor/autoload.php';
        }

        // Build QR with high error correction to better tolerate a logo
        $builder = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(ErrorCorrectionLevel::High)
            ->size($size)
            ->margin($margin)
            ->roundBlockSizeMode(RoundBlockSizeMode::Margin);

        // Optional: center overlay of user avatar if reachable
        // We prefer a local path; otherwise attempt a safe temporary download
        $logoPath = null;
        if (!empty($userAvatar ?? null)) {
            $avatarUrl = (string)$userAvatar;
            // If it starts with base_url, convert to local filesystem path
            if (strpos($avatarUrl, $base_url) === 0) {
                $relative = ltrim(substr($avatarUrl, strlen($base_url)), '/');
                $candidate = $rootPath . '/' . $relative;
                if (is_file($candidate)) {
                    $logoPath = $candidate;
                }
            }
            // Otherwise try to fetch into temp if allow_url_fopen on
            if (!$logoPath && filter_var($avatarUrl, FILTER_VALIDATE_URL)) {
                $context = stream_context_create([
                    'http' => ['timeout' => 3],
                    'https' => ['timeout' => 3]
                ]);
                $tmpFile = sys_get_temp_dir() . '/qr_logo_' . (int)$userID . '_' . md5($avatarUrl) . '.img';
                $data = @file_get_contents($avatarUrl, false, $context);
                if ($data !== false && @file_put_contents($tmpFile, $data)) {
                    $logoPath = $tmpFile;
                }
            }

            if ($logoPath && is_file($logoPath)) {
                // Resize logo to ~20% of QR width and punch out background for readability
                $builder
                    ->logoPath($logoPath)
                    ->logoResizeToWidth((int)round($size * 0.20))
                    ->logoPunchoutBackground(true);
            }
        }

        $result = $builder->build();
        $png = $result->getString();
        @file_put_contents($fullPath, $png);
        $usedEndroid = is_file($fullPath);
    } catch (\Throwable $e) {
        $usedEndroid = false;
    }

    // Fallback to phpqrcode if Endroid not used / failed
    if (!$usedEndroid) {
        // Minimal, stable fallback (no logo)
        @require_once __DIR__ . '/phpqrcode/phpqrcode.php';
        if (class_exists('QRcode')) {
            // ECC H to tolerate future overlays (even though fallback doesn’t overlay)
            $level = 'H';
            $matrixPointSize = max(1, min(10, (int)round($size / 100))); // approx scaling
            QRcode::png($url, $fullPath, $level, $matrixPointSize, $margin);
        }
    }

    // On success: clean up previous QR and persist new path
    if (is_file($fullPath)) {
        // Remove previous QR if stored
        if (!empty($userQrCode)) {
            $oldPath = $rootPath . '/' . ltrim($userQrCode, '/');
            if (is_file($oldPath)) {
                @unlink($oldPath);
            }
        }

        $qrImage = 'uploads/files/' . $dateDir . '/' . $qrCodeFile; // web path (relative)
        DB::exec("UPDATE i_users SET qr_image = ? WHERE iuid = ?", [$qrImage, (int)$userID]);
        echo $qrImage;
        return;
    }

    // If all methods failed, return a 404-like failure code for the JS to handle
    echo '404';
}
?>
