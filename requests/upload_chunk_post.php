<?php
/**
 * Chunked file upload handler for posts (images, videos, audio)
 * Receives file chunks via PUT requests and assembles them
 */

// Load core application
require_once "../includes/inc.php";

// Include required helpers
require_once "../includes/thumbncrop.inc.php";
require_once "../includes/imageFilter.php";
use imageFilter\ImageFilter;

/* -------------------------------------------
 | Watermark System (Image Branding Layer)
 --------------------------------------------*/
if (!function_exists('watermark_image')) {
    if ($watermarkStatus == 'yes') {
        function watermark_image($target, $siteWatermarkLogo, $LinkWatermarkStatus, $ourl) {
            include_once "../includes/SimpleImage-master/src/claviska/SimpleImage.php";

            if ($LinkWatermarkStatus == 'yes') {
                try {
                    $image = new \claviska\SimpleImage();
                    $image
                        ->fromFile($target)
                        ->autoOrient()
                        ->overlay('../' . $siteWatermarkLogo, 'top left', 1, 30, 30)
                        ->text($ourl, array(
                            'fontFile' => '../src/droidsanschinese.ttf',
                            'size' => 15,
                            'color' => 'red',
                            'anchor' => 'bottom right',
                            'xOffset' => -10,
                            'yOffset' => -10
                        ))
                        ->toFile($target, 'image/jpeg');
                    return true;
                } catch (Exception $err) {
                    return $err->getMessage();
                }
            } else {
                try {
                    $image = new \claviska\SimpleImage();
                    $image
                        ->fromFile($target)
                        ->autoOrient()
                        ->overlay('../' . $siteWatermarkLogo, 'top left', 1, 30, 30)
                        ->toFile($target, 'image/jpeg');
                    return true;
                } catch (Exception $err) {
                    return $err->getMessage();
                }
            }
        }
    } else if ($LinkWatermarkStatus == 'yes') {
        function watermark_image($target, $siteWatermarkLogo, $LinkWatermarkStatus, $ourl) {
            include_once "../includes/SimpleImage-master/src/claviska/SimpleImage.php";

            try {
                $image = new \claviska\SimpleImage();
                $image
                    ->fromFile($target)
                    ->autoOrient()
                    ->overlay('../img/transparent.png', 'top left', 1, 30, 30)
                    ->text($ourl, array(
                        'fontFile' => '../src/droidsanschinese.ttf',
                        'size' => 15,
                        'color' => '00897b',
                        'anchor' => 'bottom right',
                        'xOffset' => -10,
                        'yOffset' => -10
                    ))
                    ->toFile($target, 'image/jpeg');
                return true;
            } catch (Exception $err) {
                return $err->getMessage();
            }
        }
    } else {
        function watermark_image($target, $siteWatermarkLogo, $LinkWatermarkStatus, $ourl) {
            return true; // no-op
        }
    }
}

// Enable error logging to file only (don't display to break JSON)
error_reporting(E_ALL);
ini_set('display_errors', '0');  // Don't output errors (would break JSON)
ini_set('log_errors', '1');      // Log to file instead
ini_set('error_log', APP_ROOT_PATH . '/requests/error_log');

// Disable time limit for long video processing and uploads
set_time_limit(0);  // No time limit
ini_set('max_execution_time', '0');

// Set JSON response header
header('Content-Type: application/json');

// Enable CORS if needed
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Content-Range, X-Upload-ID, X-File-Name, X-File-Type');

// Handle OPTIONS preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only accept PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Check if user is logged in
if ($logedIn !== '1' || empty($userID)) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get Content-Range header
$contentRange = isset($_SERVER['HTTP_CONTENT_RANGE']) ? $_SERVER['HTTP_CONTENT_RANGE'] : '';
$uploadID = isset($_SERVER['HTTP_X_UPLOAD_ID']) ? $_SERVER['HTTP_X_UPLOAD_ID'] : '';
$fileName = isset($_SERVER['HTTP_X_FILE_NAME']) ? $_SERVER['HTTP_X_FILE_NAME'] : 'unknown';
$fileMimeType = isset($_SERVER['HTTP_X_FILE_TYPE']) ? $_SERVER['HTTP_X_FILE_TYPE'] : 'application/octet-stream';

// Validate Content-Range header format: "bytes start-end/total"
if (empty($contentRange) || !preg_match('/bytes (\d+)-(\d+)\/(\d+)/', $contentRange, $matches)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid Content-Range header']);
    exit;
}

$chunkStart = (int)$matches[1];
$chunkEnd = (int)$matches[2];
$totalSize = (int)$matches[3];
$chunkSize = $chunkEnd - $chunkStart + 1;

// Validate file size
if (convert_to_mb($totalSize) >= $availableUploadFileSize) {
    http_response_code(413);
    echo json_encode(['error' => 'File too large']);
    exit;
}

// Get file extension
$ext = strtolower(getExtension($fileName));
$valid_formats = explode(',', $availableFileExtensions);
if (!in_array($ext, $valid_formats)) {
    http_response_code(400);
    echo json_encode(['error' => 'Unsupported file format']);
    exit;
}

// Generate or validate upload ID based on user and file size
if (empty($uploadID)) {
    $uploadID = md5($userID . '_' . $totalSize . '_' . $fileName);
}
error_log("[upload_chunk_post] Upload ID: $uploadID (userID=$userID, totalSize=$totalSize, fileName=$fileName)");

// Create temp directory for chunks
$tempDir = APP_ROOT_PATH . '/includes/temp/chunks/' . $uploadID;
if (!is_dir($tempDir)) {
    @mkdir($tempDir, 0755, true);
}

// Read chunk data from PHP input stream
$chunkData = file_get_contents('php://input');

error_log("[upload_chunk_post] Received chunk $chunkStart-$chunkEnd, size: " . strlen($chunkData) . " bytes, expected: $chunkSize bytes");

if (strlen($chunkData) !== $chunkSize) {
    error_log("[upload_chunk_post] CHUNK SIZE MISMATCH! Got " . strlen($chunkData) . " expected $chunkSize");
    http_response_code(400);
    echo json_encode(['error' => 'Chunk size mismatch']);
    exit;
}

// Save chunk
$chunkFile = $tempDir . '/chunk_' . $chunkStart . '_' . $chunkEnd;
$bytesWritten = @file_put_contents($chunkFile, $chunkData);
if ($bytesWritten === false) {
    error_log("[upload_chunk_post] FAILED to save chunk to: $chunkFile");
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save chunk']);
    exit;
}
error_log("[upload_chunk_post] Chunk saved successfully: $chunkFile ($bytesWritten bytes)");

// Check if upload is complete
$isComplete = ($chunkEnd + 1) >= $totalSize;

if ($isComplete) {
    // Assemble all chunks into final file
    $tempFile = $tempDir . '/final_file.tmp';
    $finalFile = fopen($tempFile, 'wb');

    if (!$finalFile) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create final file']);
        exit;
    }

    // Get all chunks sorted by start position
    $chunks = glob($tempDir . '/chunk_*');
    usort($chunks, function($a, $b) {
        preg_match('/chunk_(\d+)_(\d+)/', basename($a), $aMatch);
        preg_match('/chunk_(\d+)_(\d+)/', basename($b), $bMatch);
        return (int)$aMatch[1] - (int)$bMatch[1];
    });

    // Assemble chunks
    foreach ($chunks as $chunk) {
        $data = file_get_contents($chunk);
        fwrite($finalFile, $data);
        @unlink($chunk); // Delete chunk after merging
    }
    fclose($finalFile);

    // Verify file size
    $actualSize = filesize($tempFile);
    if ($actualSize !== $totalSize) {
        @unlink($tempFile);
        http_response_code(500);
        echo json_encode([
            'error' => 'File size mismatch',
            'expected' => $totalSize,
            'actual' => $actualSize
        ]);
        exit;
    }

    // Process the complete file
    try {
        error_log("[upload_chunk_post] Starting file processing. Memory: " . memory_get_usage(true) . " bytes");

        // Generate unique filename
        $microtime = microtime();
        $removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
        $UploadedFileName = 'image_' . $removeMicrotime . '_' . $userID;
        $d = date('Y-m-d');

        // Determine file type
        if (preg_match('/video\/*/', $fileMimeType) || $fileMimeType === 'application/octet-stream') {
            $fileTypeIs = 'video';
        } else if (preg_match('/image\/*/', $fileMimeType)) {
            $fileTypeIs = 'Image';
        } else if (preg_match('/audio\/*/', $fileMimeType)) {
            $fileTypeIs = 'audio';
        } else {
            $fileTypeIs = 'Image';
        }

        error_log("[upload_chunk_post] File type detected: $fileTypeIs, MIME: $fileMimeType");

        // Ensure directories
        $uploadFile = APP_ROOT_PATH . '/uploads/files/';
        $xImages = APP_ROOT_PATH . '/uploads/images/';
        $xVideos = APP_ROOT_PATH . '/uploads/xvideos/';

        if (!file_exists($uploadFile . $d)) { @mkdir($uploadFile . $d, 0755, true); }
        if (!file_exists($xImages . $d)) { @mkdir($xImages . $d, 0755, true); }
        if (!file_exists($xVideos . $d)) { @mkdir($xVideos . $d, 0755, true); }

        // Check FFmpeg for video
        if ($fileTypeIs === 'video' && $ffmpegStatus == '0' && !in_array($ext, $nonFfmpegAvailableVideoFormat)) {
            @unlink($tempFile);
            throw new Exception('FFmpeg is required but not enabled');
        }

        // Move temp file to upload directory
        $getFilename = $UploadedFileName . '.' . $ext;
        $finalPath = $uploadFile . $d . '/' . $getFilename;

        error_log("[upload_chunk_post] Moving temp file to: $finalPath");
        if (!@rename($tempFile, $finalPath)) {
            throw new Exception('Failed to move file to final location');
        }
        error_log("[upload_chunk_post] File moved successfully. Size: " . filesize($finalPath) . " bytes");

        $postTypeIcon = '';
        $pathFile = '';
        $pathXFile = '';
        $tumbnailPath = '';
        $UploadSourceUrl = '';

        // Process based on file type
        if ($fileTypeIs === 'video') {
            error_log("[upload_chunk_post] Processing video file");
            $postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('52') . '</div>';
            $sourceFs = $finalPath;

            if ($ffmpegStatus == '1') {
                require_once '../includes/convertToMp4Format.php';
                require_once '../includes/createVideoThumbnail.php';

                $ffmpegBin = isset($ffmpegPath) && !empty($ffmpegPath) ? $ffmpegPath : '/opt/homebrew/bin/ffmpeg';

                // Convert to MP4
                error_log("[upload_chunk_post] Converting to MP4 format");
                $convertedFs = convertToMp4Format($ffmpegBin, $sourceFs, $uploadFile . $d, $UploadedFileName);
                if (!$convertedFs || !file_exists($convertedFs)) {
                    $convertedFs = $sourceFs;
                }

                // Create thumbnail
                error_log("[upload_chunk_post] Creating video thumbnail");
                $thumbFs = createVideoThumbnailInSameDir($ffmpegBin, $convertedFs);

                $pathFile = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
                $pathXFile = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';

                // Create preview clip (first 4 seconds)
                if (!file_exists('../uploads/xvideos/' . $d)) {
                    @mkdir('../uploads/xvideos/' . $d, 0755, true);
                }
                $xVideoFirstPath = '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                $safeCmd = $ffmpegBin . ' -ss 00:00:01 -i ' . escapeshellarg($convertedFs) . ' -c copy -t 00:00:04 ' . escapeshellarg($xVideoFirstPath) . ' 2>&1';
                shell_exec($safeCmd);

                // Create pixelated thumbnail
                $videoTumbnailPath = '../uploads/files/' . $d . '/' . $UploadedFileName . '.png';
                if (file_exists($videoTumbnailPath)) {
                    try {
                        $dir = '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.jpg';
                        $image = new ImageFilter();
                        $image->load($videoTumbnailPath)->pixelation($pixelSize)->saveFile($dir, 100, 'jpg');
                    } catch (Exception $e) {
                        error_log("[upload_chunk_post] Pixelation failed: " . $e->getMessage());
                    }
                }

                $tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                $thePathM = '../' . $tumbnailPath;

                // Apply watermark if enabled
                if (file_exists($thePathM) && ($watermarkStatus == 'yes' || $LinkWatermarkStatus == 'yes')) {
                    watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url . $userName);
                }

                // Prepare files for publishing to storage
                $publishKeys = [];
                $mp4Key = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
                $xclipKey = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                $thumbJpg = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                $thumbPng = 'uploads/files/' . $d . '/' . $UploadedFileName . '.png';

                // Check which files exist BEFORE publishing (and potential deletion)
                $hasThumbJpg = is_file('../' . $thumbJpg);
                $hasThumbPng = is_file('../' . $thumbPng);
                $hasMp4 = is_file('../' . $mp4Key);
                $hasXclip = is_file('../' . $xclipKey);

                if ($hasMp4) { $publishKeys[] = $mp4Key; }
                if ($hasXclip) { $publishKeys[] = $xclipKey; }
                if ($hasThumbJpg) { $publishKeys[] = $thumbJpg; }
                if ($hasThumbPng) { $publishKeys[] = $thumbPng; }

                // Determine source URL and thumbnail path BEFORE files are deleted
                if ($hasThumbJpg) {
                    $UploadSourceUrl = storage_public_url($thumbJpg);
                    $tumbnailPath = $thumbJpg;
                } elseif ($hasThumbPng) {
                    $UploadSourceUrl = storage_public_url($thumbPng);
                    $tumbnailPath = $thumbPng;
                } elseif ($hasMp4) {
                    $UploadSourceUrl = storage_public_url($mp4Key);
                    $tumbnailPath = $mp4Key;
                } else {
                    $UploadSourceUrl = $base_url . 'uploads/web.png';
                    $tumbnailPath = 'uploads/web.png';
                }

                // Now publish (and delete local files if remote storage is enabled)
                if (!empty($publishKeys)) {
                    error_log("[upload_chunk_post] Publishing " . count($publishKeys) . " files to storage");
                    storage_publish_many($publishKeys, true, true);
                }

                $ext = 'mp4';
                error_log("[upload_chunk_post] Video processing complete");
            } else {
                $pathFile = 'uploads/files/' . $d . '/' . $getFilename;
                $pathXFile = 'uploads/files/' . $d . '/' . $getFilename;
                storage_publish_many([$pathFile], true, true);
                $UploadSourceUrl = storage_public_url($pathFile);
            }

        } else if ($fileTypeIs === 'Image') {
            error_log("[upload_chunk_post] Processing image file");
            $postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('53') . '</div>';
            $pathFile = 'uploads/files/' . $d . '/' . $getFilename;
            $pixelKey = 'uploads/pixel/' . $d . '/' . $getFilename;
            $resizedFileTwo = '../uploads/files/' . $d . '/' . $UploadedFileName . '__' . $userID . '.' . $ext;

            // Create a resized copy; if it fails, fall back to original
            try {
                $tb = new ThumbAndCrop();
                $tb->openImg('../' . $pathFile);
                $newHeight = $tb->getRightHeight(500);
                $tb->creaThumb(500, $newHeight);
                $tb->setThumbAsOriginal();
                $tb->creaThumb(500, $newHeight);
                $tb->saveThumb($resizedFileTwo);
            } catch (Exception $e) {
                error_log("[upload_chunk_post] Resize failed: " . $e->getMessage());
            }

            $tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '__' . $userID . '.' . $ext;
            if (!is_file('../' . $tumbnailPath)) {
                $tumbnailPath = $pathFile;
            }

            if ($ext !== 'gif') {
                $thePathM = '../' . $pathFile;
                if ($watermarkStatus == 'yes' || $LinkWatermarkStatus == 'yes') {
                    watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url . $userName);
                }
            }

            // Generate pixelated preview
            try {
                $dir = '../' . $pixelKey;
                if (!file_exists(dirname($dir))) { @mkdir(dirname($dir), 0755, true); }
                $image = new ImageFilter();
                $image->load('../' . $pathFile)->pixelation($pixelSize)->saveFile($dir, 100, 'jpg');
            } catch (Exception $e) {
                error_log("[upload_chunk_post] Pixelation failed: " . $e->getMessage());
            }

            // Determine source URL BEFORE publishing (which may delete local files)
            $UploadSourceUrl = storage_publish_pick_url([$tumbnailPath, $pathFile]) ?? ($base_url . 'uploads/web.png');
            $pathXFile = $pixelKey;

            // Now publish available files to storage
            storage_publish_many([$pathFile, $pixelKey, $tumbnailPath], true, true);

            error_log("[upload_chunk_post] Image processing complete");

        } else { // audio
            error_log("[upload_chunk_post] Processing audio file");
            $postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('53') . '</div>';
            $pathFile = 'uploads/files/' . $d . '/' . $getFilename;
            $tumbnailPath = 'src/audio.png';
            $pathXFile = 'src/audio.png';
            storage_publish_many([$pathFile], true, true);
            $UploadSourceUrl = storage_public_url($pathFile);
        }

        // Save to database
        error_log("[upload_chunk_post] Saving to database: pathFile=$pathFile, tumbnailPath=$tumbnailPath");
        $insertFileFromUploadTable = $iN->iN_INSERTUploadedFiles($userID, $pathFile, $tumbnailPath, $pathXFile, $ext);
        $getUploadedFileID = $iN->iN_GetUploadedFilesIDs($userID, $pathFile);

        if (!$getUploadedFileID || !isset($getUploadedFileID['upload_id'])) {
            throw new Exception('Failed to get uploaded file ID');
        }

        $uploadID_db = $getUploadedFileID['upload_id'];
        error_log("[upload_chunk_post] DB insert success! uploadID=$uploadID_db");

        // Build response HTML (same format as request.php)
        $uploadTumbnail = '';
        if ($fileTypeIs == 'video') {
            $uploadTumbnail = '<div class="v_custom_tumb"><label for="vTumb_' . $uploadID_db . '"><div class="i_image_video_btn"><div class="pbtn pbtn_plus">' . $LANG['custom_tumbnail'] . '</div></div><input type="file" id="vTumb_' . $uploadID_db . '" class="imageorvideo cTumb editAds_file" data-id="' . $uploadID_db . '" name="uploading[]" data-id="tupload"></label></div>';
        }

        $htmlResponse = '';
        if ($fileTypeIs == 'video' || $fileTypeIs == 'Image') {
            $htmlResponse = '<div class="i_uploaded_item iu_f_' . $uploadID_db . ' ' . $fileTypeIs . '" id="' . $uploadID_db . '">' . $postTypeIcon . '<div class="i_delete_item_button" id="' . $uploadID_db . '">' . $iN->iN_SelectedMenuIcon('5') . '</div><div class="i_uploaded_file" id="viTumb' . $uploadID_db . '" style="background-image:url(' . $UploadSourceUrl . ');"><img class="i_file" id="viTumbi' . $uploadID_db . '" src="' . $UploadSourceUrl . '" alt="tumbnail"></div>' . $uploadTumbnail . '</div>';
        } else {
            $htmlResponse = '<div id="playing_' . $uploadID_db . '" class="green-audio-player"><div class="i_uploaded_item nonePoint iu_f_' . $uploadID_db . ' ' . $fileTypeIs . '"  id="' . $uploadID_db . '"></div><audio crossorigin="" preload="none"><source src="' . $UploadSourceUrl . '" type="audio/mp3" /></audio><script>$(function(){ new GreenAudioPlayer("#playing_' . $uploadID_db . '", { stopOthersOnPlay: true, showTooltips: true, showDownloadButton: false, enableKeystrokes: true });});</script></div>';
        }

        // Cleanup temp directory
        @rmdir($tempDir);

        // Success response
        error_log("[upload_chunk_post] Sending success response with uploadID=$uploadID_db");
        http_response_code(201);

        $responseData = [
            'success' => true,
            'upload_id' => $uploadID_db,
            'html' => $htmlResponse,
            'file_type' => $fileTypeIs,
            'source_url' => $UploadSourceUrl
        ];

        error_log("[upload_chunk_post] Response data: " . json_encode($responseData));
        echo json_encode($responseData);

    } catch (Exception $e) {
        // Log detailed error information
        error_log("[upload_chunk_post] EXCEPTION: " . $e->getMessage());
        error_log("[upload_chunk_post] File: " . $e->getFile() . " Line: " . $e->getLine());
        error_log("[upload_chunk_post] Memory peak: " . memory_get_peak_usage(true) . " bytes");
        error_log("[upload_chunk_post] Stack trace: " . $e->getTraceAsString());

        // Cleanup on error
        if (isset($tempFile) && file_exists($tempFile)) {
            @unlink($tempFile);
        }
        @rmdir($tempDir);

        http_response_code(500);
        echo json_encode([
            'error' => $e->getMessage()
        ]);
    }

} else {
    // Chunk received, send success response with upload ID
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'upload_id' => $uploadID,
        'received_bytes' => $chunkEnd + 1,
        'total_bytes' => $totalSize,
        'progress' => round((($chunkEnd + 1) / $totalSize) * 100, 2)
    ]);
}
