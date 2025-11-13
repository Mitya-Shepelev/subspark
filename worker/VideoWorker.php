<?php

/**
 * Video Processing Worker
 * Processes video conversion, thumbnail generation, and reel blurring jobs
 */
class VideoWorker
{
    private string $ffmpegPath;
    private string $ffprobePath;
    private VideoQueue $queue;
    private bool $running = true;

    public function __construct(VideoQueue $queue, string $ffmpegPath, string $ffprobePath)
    {
        $this->queue = $queue;
        $this->ffmpegPath = $ffmpegPath;
        $this->ffprobePath = $ffprobePath;

        // Handle graceful shutdown
        pcntl_signal(SIGTERM, [$this, 'shutdown']);
        pcntl_signal(SIGINT, [$this, 'shutdown']);
    }

    /**
     * Start processing jobs
     */
    public function start(): void
    {
        error_log("[VideoWorker] Worker started (FFmpeg: {$this->ffmpegPath})");

        while ($this->running) {
            // Check for signals
            pcntl_signal_dispatch();

            // Get next job (5 second timeout)
            $job = $this->queue->getNextJob(5);

            if (!$job) {
                continue; // No jobs, wait and try again
            }

            try {
                error_log("[VideoWorker] Processing job {$job->id} (type: {$job->type})");

                $result = $this->processJob($job);

                if ($result) {
                    $this->queue->completeJob($job);
                } else {
                    $this->queue->failJob($job, 'Processing returned false');
                }
            } catch (Exception $e) {
                error_log("[VideoWorker] ERROR processing job {$job->id}: " . $e->getMessage());
                $this->queue->failJob($job, $e->getMessage());
            }
        }

        error_log("[VideoWorker] Worker stopped");
    }

    /**
     * Process a single job
     */
    private function processJob(VideoJob $job): bool
    {
        switch ($job->type) {
            case 'convert':
                return $this->convertVideo($job->data);

            case 'thumbnail':
                return $this->createThumbnail($job->data);

            case 'reel_blur':
                return $this->createBlurredReel($job->data);

            case 'reel_upload':
                return $this->processReelUpload($job->data);

            default:
                error_log("[VideoWorker] Unknown job type: {$job->type}");
                return false;
        }
    }

    /**
     * Convert video to MP4 format
     */
    private function convertVideo(array $data): bool
    {
        $sourcePath = $data['sourcePath'] ?? null;
        $outputDir = $data['outputDir'] ?? null;
        $filenameWithoutExt = $data['filenameWithoutExt'] ?? null;

        if (!$sourcePath || !$outputDir || !$filenameWithoutExt) {
            error_log("[VideoWorker] Missing required fields for convert job");
            return false;
        }

        if (!file_exists($sourcePath)) {
            error_log("[VideoWorker] Source file not found: {$sourcePath}");
            return false;
        }

        $outputPath = rtrim($outputDir, '/') . '/' . $filenameWithoutExt . '.mp4';

        // Check if already MP4 and same path
        $srcExt = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION) ?: '');
        $samePath = (realpath($sourcePath) === realpath($outputPath));

        if ($srcExt === 'mp4' && $samePath) {
            error_log("[VideoWorker] File already MP4, skipping conversion");
            return true;
        }

        $targetPath = $samePath
            ? (rtrim($outputDir, '/') . '/' . $filenameWithoutExt . '__tmp__.mp4')
            : $outputPath;

        $cmd = $this->ffmpegPath . ' -i ' . escapeshellarg($sourcePath)
             . ' -c:v libx264 -preset fast -crf 23 -pix_fmt yuv420p'
             . ' -c:a aac -b:a 128k -strict -2 '
             . escapeshellarg($targetPath) . ' -y 2>&1';

        error_log("[VideoWorker] Running: {$cmd}");
        $output = shell_exec($cmd);

        if (file_exists($targetPath) && filesize($targetPath) > 1024) {
            if ($samePath) {
                @rename($targetPath, $outputPath);
                @unlink($sourcePath);
            } else {
                @unlink($sourcePath);
            }
            error_log("[VideoWorker] Conversion successful: {$outputPath}");
            return true;
        }

        error_log("[VideoWorker] Conversion failed. Output: {$output}");
        return false;
    }

    /**
     * Create video thumbnail
     */
    private function createThumbnail(array $data): bool
    {
        $videoPath = $data['videoPath'] ?? null;

        if (!$videoPath) {
            error_log("[VideoWorker] Missing videoPath for thumbnail job");
            return false;
        }

        if (!file_exists($videoPath)) {
            error_log("[VideoWorker] Video file not found: {$videoPath}");
            return false;
        }

        $directory = dirname($videoPath);
        $filenameWithoutExt = pathinfo($videoPath, PATHINFO_FILENAME);
        $thumbnailPath = $directory . '/' . $filenameWithoutExt . '.jpg';

        $cmd = escapeshellcmd($this->ffmpegPath)
            . ' -ss 00:00:03.000 -i ' . escapeshellarg($videoPath)
            . ' -frames:v 1 -q:v 2 '
            . escapeshellarg($thumbnailPath) . ' -y 2>&1';

        error_log("[VideoWorker] Running: {$cmd}");
        $output = shell_exec($cmd);

        if (file_exists($thumbnailPath) && filesize($thumbnailPath) > 1000) {
            error_log("[VideoWorker] Thumbnail created: {$thumbnailPath}");
            return true;
        }

        error_log("[VideoWorker] Thumbnail creation failed. Output: {$output}");
        return false;
    }

    /**
     * Create blurred reel video
     */
    private function createBlurredReel(array $data): bool
    {
        $inputPath = $data['inputPath'] ?? null;
        $outputPath = $data['outputPath'] ?? null;
        $maxDuration = $data['maxDuration'] ?? 15;

        if (!$inputPath || !$outputPath) {
            error_log("[VideoWorker] Missing paths for reel_blur job");
            return false;
        }

        if (!file_exists($inputPath)) {
            error_log("[VideoWorker] Input file not found: {$inputPath}");
            return false;
        }

        // Get video dimensions and duration
        $probeCmd = $this->ffprobePath
            . ' -v error -select_streams v:0 -show_entries stream=width,height,duration'
            . ' -of json ' . escapeshellarg($inputPath);

        $probeOutput = shell_exec($probeCmd);
        $videoInfo = json_decode($probeOutput, true);

        $width = $videoInfo['streams'][0]['width'] ?? 1080;
        $height = $videoInfo['streams'][0]['height'] ?? 1920;
        $duration = $videoInfo['streams'][0]['duration'] ?? $maxDuration;

        // Crop to 9:16 aspect ratio
        $targetAspect = 9 / 16;
        $currentAspect = $width / $height;

        if ($currentAspect > $targetAspect) {
            $newWidth = (int)($height * $targetAspect);
            $cropFilter = "crop={$newWidth}:{$height}:(in_w-{$newWidth})/2:0";
        } else {
            $newHeight = (int)($width / $targetAspect);
            $cropFilter = "crop={$width}:{$newHeight}:0:(in_h-{$newHeight})/2";
        }

        $cmd = $this->ffmpegPath . ' -i ' . escapeshellarg($inputPath)
             . ' -t ' . escapeshellarg($maxDuration)
             . ' -vf "' . $cropFilter . ',scale=1080:1920"'
             . ' -c:v libx264 -preset fast -crf 23 -pix_fmt yuv420p'
             . ' -c:a aac -b:a 128k -strict -2'
             . ' ' . escapeshellarg($outputPath) . ' -y 2>&1';

        error_log("[VideoWorker] Running: {$cmd}");
        $output = shell_exec($cmd);

        if (file_exists($outputPath) && filesize($outputPath) > 1024) {
            error_log("[VideoWorker] Blurred reel created: {$outputPath}");
            return true;
        }

        error_log("[VideoWorker] Blurred reel creation failed. Output: {$output}");
        return false;
    }

    /**
     * Process full reel upload workflow
     * This mimics the synchronous processing from upload_chunk.php
     */
    private function processReelUpload(array $data): bool
    {
        $userID = $data['userID'] ?? null;
        $fileID = $data['fileID'] ?? null;
        $sourcePath = $data['sourcePath'] ?? null;
        $outputDir = $data['outputDir'] ?? null;
        $todayDir = $data['todayDir'] ?? date('Y-m-d');
        $maxVideoDuration = $data['maxVideoDuration'] ?? 90;

        if (!$userID || !$fileID || !$sourcePath || !$outputDir) {
            error_log("[VideoWorker] Missing required fields for reel_upload job");
            return false;
        }

        if (!file_exists($sourcePath)) {
            error_log("[VideoWorker] Source file not found: {$sourcePath}");
            return false;
        }

        error_log("[VideoWorker] Processing reel upload: fileID={$fileID}, source={$sourcePath}");

        try {
            // Load minimal bootstrap for worker (database, storage, no web-specific code)
            require_once __DIR__ . '/includes/worker_bootstrap.php';
            require_once __DIR__ . '/includes/convertToMp4Format.php';
            require_once __DIR__ . '/includes/convertVideoToBlurredReelsFormat.php';
            require_once __DIR__ . '/includes/createVideoThumbnail.php';

            // 1. Check video duration
            $probeCmd = escapeshellcmd($this->ffprobePath)
                . ' -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 '
                . escapeshellarg($sourcePath);

            $durationOutput = shell_exec($probeCmd);
            $duration = floatval($durationOutput);
            error_log("[VideoWorker] Video duration: {$duration}s, max: {$maxVideoDuration}s");

            if ($duration === 0.0) {
                throw new Exception('Could not read video duration');
            }

            if ($duration > $maxVideoDuration) {
                @unlink($sourcePath);
                throw new Exception("Video exceeds maximum duration of {$maxVideoDuration}s");
            }

            // 2. Convert to MP4 if needed
            $ext = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
            $needsConversion = !in_array($ext, ['mp4']);

            if ($needsConversion) {
                error_log("[VideoWorker] Converting to MP4");
                $uploadDir = dirname($sourcePath);
                $convertedPath = convertToMp4Format($this->ffmpegPath, $sourcePath, $uploadDir);

                if (!$convertedPath || !file_exists($convertedPath)) {
                    throw new Exception('MP4 conversion failed');
                }

                @unlink($sourcePath);
                $sourcePath = $convertedPath;
                error_log("[VideoWorker] MP4 conversion complete: {$sourcePath}");
            }

            // 3. Convert to Reels format (9:16 with blur)
            if (!is_dir($outputDir)) {
                @mkdir($outputDir, 0755, true);
            }

            error_log("[VideoWorker] Converting to reels format");
            $reelsPath = convertVideoToBlurredReelsFormat($this->ffmpegPath, $sourcePath, $outputDir);

            if (!$reelsPath || !file_exists($reelsPath)) {
                throw new Exception('Reels format conversion failed');
            }

            error_log("[VideoWorker] Reels conversion complete: {$reelsPath}");

            // Delete source after successful conversion
            @unlink($sourcePath);

            // 4. Generate thumbnail
            error_log("[VideoWorker] Generating thumbnail");
            $thumbnailPath = createVideoThumbnailInSameDir($this->ffmpegPath, $reelsPath);
            error_log("[VideoWorker] Thumbnail: " . ($thumbnailPath ?: 'none'));

            // 5. Upload to remote storage if configured
            $storageReelsPath = $reelsPath;
            $storageThumbnailPath = $thumbnailPath;

            if (function_exists('storage_is_remote') && storage_is_remote()) {
                error_log("[VideoWorker] Uploading to remote storage");
                require_once __DIR__ . '/includes/object_storage.php';

                // Upload video
                if (file_exists($reelsPath)) {
                    $s3ReelsKey = 'uploads/reels/' . $todayDir . '/' . basename($reelsPath);
                    storage_upload($reelsPath, $s3ReelsKey);
                    $storageReelsPath = $s3ReelsKey;
                    @unlink($reelsPath);
                    error_log("[VideoWorker] Video uploaded to: {$s3ReelsKey}");
                }

                // Upload thumbnail
                if ($thumbnailPath && file_exists($thumbnailPath)) {
                    $s3ThumbKey = 'uploads/reels/' . $todayDir . '/' . basename($thumbnailPath);
                    storage_upload($thumbnailPath, $s3ThumbKey);
                    $storageThumbnailPath = $s3ThumbKey;
                    @unlink($thumbnailPath);
                    error_log("[VideoWorker] Thumbnail uploaded to: {$s3ThumbKey}");
                }
            } else {
                // Convert to relative paths for local storage
                $storageReelsPath = str_replace('/var/www/html/', '', $reelsPath);
                if ($thumbnailPath) {
                    $storageThumbnailPath = str_replace('/var/www/html/', '', $thumbnailPath);
                }
            }

            // 6. Update database with final paths and mark as completed
            error_log("[VideoWorker] Updating database: fileID={$fileID}");
            DB::exec(
                "UPDATE i_user_uploads
                 SET uploaded_file_path = ?,
                     upload_tumbnail_file_path = ?,
                     processing_status = 'completed'
                 WHERE upload_id = ?",
                [$storageReelsPath, $storageThumbnailPath, $fileID]
            );

            error_log("[VideoWorker] Reel upload processing complete: fileID={$fileID}");
            return true;

        } catch (Exception $e) {
            error_log("[VideoWorker] Reel upload failed: " . $e->getMessage());

            // Mark as failed in database
            try {
                DB::exec(
                    "UPDATE i_user_uploads SET processing_status = 'failed' WHERE upload_id = ?",
                    [$fileID]
                );
            } catch (Exception $dbError) {
                error_log("[VideoWorker] Failed to update DB status: " . $dbError->getMessage());
            }

            return false;
        }
    }

    /**
     * Graceful shutdown handler
     */
    public function shutdown(): void
    {
        error_log("[VideoWorker] Received shutdown signal");
        $this->running = false;
    }
}
