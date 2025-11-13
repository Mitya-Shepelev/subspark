<?php
/**
 * Chunked video upload handler for UpChunk library
 * Receives video chunks via PUT requests and assembles them
 */

// Load core application
require_once "../includes/inc.php";

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
header('Access-Control-Allow-Headers: Content-Type, Content-Range, X-Upload-ID');

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

// Check reels feature is enabled
if (!isset($reelsFeatureStatus) || $reelsFeatureStatus !== '1') {
    http_response_code(403);
    echo json_encode(['error' => 'Reels feature is disabled']);
    exit;
}

// Get Content-Range header
$contentRange = isset($_SERVER['HTTP_CONTENT_RANGE']) ? $_SERVER['HTTP_CONTENT_RANGE'] : '';
$uploadID = isset($_SERVER['HTTP_X_UPLOAD_ID']) ? $_SERVER['HTTP_X_UPLOAD_ID'] : '';

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

// Generate or validate upload ID based on user and file size
// This ensures all chunks from the same upload get the same ID
if (empty($uploadID)) {
    $uploadID = md5($userID . '_' . $totalSize);
}
error_log("[upload_chunk] Upload ID: $uploadID (userID=$userID, totalSize=$totalSize)");

// Create temp directory for chunks
$tempDir = APP_ROOT_PATH . '/includes/temp/chunks/' . $uploadID;
if (!is_dir($tempDir)) {
    @mkdir($tempDir, 0755, true);
}

// Read chunk data from PHP input stream
$chunkData = file_get_contents('php://input');

error_log("[upload_chunk] Received chunk $chunkStart-$chunkEnd, size: " . strlen($chunkData) . " bytes, expected: $chunkSize bytes");

if (strlen($chunkData) !== $chunkSize) {
    error_log("[upload_chunk] CHUNK SIZE MISMATCH! Got " . strlen($chunkData) . " expected $chunkSize");
    http_response_code(400);
    echo json_encode(['error' => 'Chunk size mismatch']);
    exit;
}

// Save chunk
$chunkFile = $tempDir . '/chunk_' . $chunkStart . '_' . $chunkEnd;
$bytesWritten = @file_put_contents($chunkFile, $chunkData);
if ($bytesWritten === false) {
    error_log("[upload_chunk] FAILED to save chunk to: $chunkFile");
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save chunk']);
    exit;
}
error_log("[upload_chunk] Chunk saved successfully: $chunkFile ($bytesWritten bytes)");

// Check if upload is complete
$isComplete = ($chunkEnd + 1) >= $totalSize;

if ($isComplete) {
    // Assemble all chunks into final file
    $tempFile = $tempDir . '/final_video.tmp';
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

    // Process the complete video file
    try {
        error_log("[upload_chunk] Starting video processing. Memory: " . memory_get_usage(true) . " bytes");

        // Determine file extension
        $ext = 'mp4'; // default extension

        // Create final upload directory
        $todayDir = date('Y-m-d');
        $uploadDir = APP_ROOT_PATH . '/uploads/files/' . $todayDir;
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $finalFilename = 'reel_' . time() . '_' . $userID . '.' . $ext;
        $finalPath = $uploadDir . '/' . $finalFilename;

        // Move temp file to final location
        error_log("[upload_chunk] Moving temp file to: $finalPath");
        if (!@rename($tempFile, $finalPath)) {
            throw new Exception('Failed to move file to final location');
        }
        error_log("[upload_chunk] File moved successfully. Size: " . filesize($finalPath) . " bytes");

        // Check if async video processing is enabled
        $useAsyncProcessing = getenv('USE_ASYNC_VIDEO_PROCESSING') === '1';

        if ($useAsyncProcessing) {
            error_log("[upload_chunk] Async processing enabled, checking Redis availability");
            require_once APP_ROOT_PATH . '/includes/video_queue_helper.php';

            if (isAsyncVideoProcessingAvailable()) {
                error_log("[upload_chunk] Using ASYNC processing via Redis queue");

                // Insert preliminary record into database with 'processing' status
                $uploadTime = time();
                DB::exec(
                    "INSERT INTO i_user_uploads (iuid_fk, upload_type, uploaded_file_path, uploaded_file_ext, upload_tumbnail_file_path, upload_time, processing_status)
                     VALUES (?, ?, ?, ?, ?, ?, ?)",
                    [(int)$userID, 'reels', '', 'mp4', '', $uploadTime, 'processing']
                );

                $fileID = DB::lastId();
                error_log("[upload_chunk] DB record created with fileID=$fileID, status=processing");

                // Queue the video processing job
                $reelsDir = APP_ROOT_PATH . '/uploads/reels/' . $todayDir;
                if (!is_dir($reelsDir)) {
                    @mkdir($reelsDir, 0755, true);
                }

                // Queue the job with all necessary data
                $redis = getVideoQueueRedis();
                require_once APP_ROOT_PATH . '/worker/VideoJob.php';
                require_once APP_ROOT_PATH . '/worker/VideoQueue.php';

                $job = new VideoJob([
                    'type' => 'reel_upload',
                    'data' => [
                        'userID' => $userID,
                        'fileID' => $fileID,
                        'sourcePath' => $finalPath,
                        'outputDir' => $reelsDir,
                        'todayDir' => $todayDir,
                        'maxVideoDuration' => isset($maxVideoDuration) ? (int)$maxVideoDuration : 90,
                    ],
                ]);

                $queue = new VideoQueue($redis);
                $success = $queue->addJob($job);

                if ($success) {
                    error_log("[upload_chunk] Job queued successfully: {$job->id}");

                    // Cleanup temp directory
                    @rmdir($tempDir);

                    // Return success immediately WITHOUT waiting for FFmpeg
                    http_response_code(202); // 202 Accepted - processing in background
                    echo json_encode([
                        'success' => true,
                        'file_id' => $fileID,
                        'job_id' => $job->id,
                        'status' => 'processing',
                        'message' => $LANG['video_processing_in_background'] ?? 'Video is being processed. You will be notified when ready.'
                    ]);
                    exit;
                } else {
                    error_log("[upload_chunk] Failed to queue job, falling back to sync processing");
                    // Fall through to synchronous processing
                }
            } else {
                error_log("[upload_chunk] Redis not available, falling back to sync processing");
                // Fall through to synchronous processing
            }
        } else {
            error_log("[upload_chunk] Async processing disabled, using SYNC processing");
        }

        // Now process with FFmpeg (same as standard upload)
        // Check video duration
        $probeBin = isset($ffprobePath) && !empty($ffprobePath) ? $ffprobePath : '/opt/homebrew/bin/ffprobe';

        error_log("[upload_chunk] Running FFprobe to check duration");
        $ffprobeCmd = escapeshellcmd($probeBin)
            . ' -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '
            . escapeshellarg($finalPath) . ' 2>&1';

        $durationOutput = shell_exec($ffprobeCmd);
        $duration = floatval($durationOutput);
        error_log("[upload_chunk] Video duration: {$duration}s, max allowed: {$maxVideoDuration}s");

        if ($duration === 0.0) {
            @unlink($finalPath);
            throw new Exception($LANG['unable_to_read_video_duration'] ?? 'Could not read video duration');
        }

        // Check duration limit
        $maxDuration = isset($maxVideoDuration) ? (int)$maxVideoDuration : 90;
        if ($duration > $maxDuration) {
            @unlink($finalPath);
            throw new Exception(str_replace('{seconds}', $maxDuration, $LANG['video_length_exceeds_limit'] ?? 'Video exceeds maximum duration'));
        }

        // Convert to MP4 if needed
        $needsConversion = !in_array(strtolower($ext), ['mp4']);
        if ($needsConversion && function_exists('convertToMp4Format')) {
            error_log("[upload_chunk] Converting to MP4 format");
            require_once APP_ROOT_PATH . '/includes/convertToMp4Format.php';
            $ffmpegBin = isset($ffmpegPath) && !empty($ffmpegPath) ? $ffmpegPath : '/opt/homebrew/bin/ffmpeg';
            $convertedPath = convertToMp4Format($ffmpegBin, $finalPath, $uploadDir);

            if (!$convertedPath || !file_exists($convertedPath)) {
                throw new Exception('MP4 conversion failed');
            }

            @unlink($finalPath); // Delete original
            $finalPath = $convertedPath;
            error_log("[upload_chunk] MP4 conversion complete");
        }

        // Convert to Reels format (9:16 with blur)
        $reelsDir = APP_ROOT_PATH . '/uploads/reels/' . $todayDir;
        if (!is_dir($reelsDir)) {
            @mkdir($reelsDir, 0755, true);
        }

        error_log("[upload_chunk] Starting reels format conversion. Memory: " . memory_get_usage(true) . " bytes");
        require_once APP_ROOT_PATH . '/includes/convertVideoToBlurredReelsFormat.php';
        $ffmpegBin = isset($ffmpegPath) && !empty($ffmpegPath) ? $ffmpegPath : '/opt/homebrew/bin/ffmpeg';
        $reelsPath = convertVideoToBlurredReelsFormat($ffmpegBin, $finalPath, $reelsDir);

        if (!$reelsPath || !file_exists($reelsPath)) {
            error_log("[upload_chunk] Reels conversion FAILED - no output file");
            throw new Exception('Reels conversion failed');
        }
        error_log("[upload_chunk] Reels conversion complete: $reelsPath. Memory: " . memory_get_usage(true) . " bytes");

        // Delete original MP4 after successful reels conversion
        @unlink($finalPath);

        // Generate thumbnail
        error_log("[upload_chunk] Generating thumbnail");
        require_once APP_ROOT_PATH . '/includes/createVideoThumbnail.php';
        $thumbnailPath = createVideoThumbnailInSameDir($ffmpegBin, $reelsPath);
        error_log("[upload_chunk] Thumbnail generated: " . ($thumbnailPath ?: 'none'));

        // Upload to S3 if needed
        if (function_exists('storage_is_remote') && storage_is_remote()) {
            error_log("[upload_chunk] Remote storage enabled, uploading to S3/Selectel");
            require_once APP_ROOT_PATH . '/includes/object_storage.php';

            if (file_exists($reelsPath)) {
                $localReelsPath = $reelsPath;
                $s3ReelsKey = 'uploads/reels/' . $todayDir . '/' . basename($reelsPath);
                error_log("[upload_chunk] Uploading video to: $s3ReelsKey");
                storage_upload($reelsPath, $s3ReelsKey);
                $reelsPath = $s3ReelsKey; // Store S3 key in DB
                @unlink($localReelsPath); // Delete local file after S3 upload
                error_log("[upload_chunk] Video uploaded to remote storage");
            }

            if ($thumbnailPath && file_exists($thumbnailPath)) {
                $localThumbPath = $thumbnailPath;
                $s3ThumbKey = 'uploads/reels/' . $todayDir . '/' . basename($thumbnailPath);
                error_log("[upload_chunk] Uploading thumbnail to: $s3ThumbKey");
                storage_upload($thumbnailPath, $s3ThumbKey);
                $thumbnailPath = $s3ThumbKey;
                @unlink($localThumbPath); // Delete local thumbnail after S3 upload
                error_log("[upload_chunk] Thumbnail uploaded to remote storage");
            }
        } else {
            error_log("[upload_chunk] Using local storage");
            // For local storage, store relative path
            $reelsPath = str_replace(APP_ROOT_PATH . '/', '', $reelsPath);
            if ($thumbnailPath) {
                $thumbnailPath = str_replace(APP_ROOT_PATH . '/', '', $thumbnailPath);
            }
        }

        // Insert into database
        $uploadTime = time();
        error_log("[upload_chunk] About to insert to DB: userID=$userID, reelsPath=$reelsPath");

        try {
            DB::exec(
                "INSERT INTO i_user_uploads (iuid_fk, upload_type, uploaded_file_path, uploaded_file_ext, upload_tumbnail_file_path, upload_time)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [(int)$userID, 'reels', $reelsPath, 'mp4', $thumbnailPath, $uploadTime]
            );

            $fileID = DB::lastId();
            error_log("[upload_chunk] DB insert success! fileID=$fileID");
        } catch (Exception $dbError) {
            error_log("[upload_chunk] DB insert FAILED: " . $dbError->getMessage());
            throw $dbError;
        }

        // Cleanup temp directory
        @rmdir($tempDir);

        // Success response
        error_log("[upload_chunk] Sending success response with fileID=$fileID");
        http_response_code(201);

        $responseData = [
            'success' => true,
            'file_id' => $fileID,
            'uploaded_file_path' => $reelsPath,
            'thumbnail_path' => $thumbnailPath,
            'duration' => round($duration, 2)
        ];

        error_log("[upload_chunk] Response data: " . json_encode($responseData));
        echo json_encode($responseData);

    } catch (Exception $e) {
        // Log detailed error information
        error_log("[upload_chunk] EXCEPTION: " . $e->getMessage());
        error_log("[upload_chunk] File: " . $e->getFile() . " Line: " . $e->getLine());
        error_log("[upload_chunk] Memory peak: " . memory_get_peak_usage(true) . " bytes");
        error_log("[upload_chunk] Stack trace: " . $e->getTraceAsString());

        // Cleanup on error
        @unlink($tempFile);
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
