<?php

/**
 * Video Queue Helper Functions
 * Provides easy integration with video processing queue from the main app
 */

require_once __DIR__ . '/../worker/VideoJob.php';
require_once __DIR__ . '/../worker/VideoQueue.php';

/**
 * Get Redis connection for video queue
 */
function getVideoQueueRedis(): ?Redis
{
    static $redis = null;

    if ($redis !== null) {
        return $redis;
    }

    $redisHost = getenv('REDIS_HOST') ?: 'localhost';
    $redisPort = (int)(getenv('REDIS_PORT') ?: 6379);
    $redisPassword = getenv('REDIS_PASSWORD') ?: null;
    $redisDb = (int)(getenv('REDIS_DB') ?: 0);

    try {
        $redis = new Redis();
        $redis->connect($redisHost, $redisPort, 2.5); // 2.5 second timeout

        if ($redisPassword) {
            $redis->auth($redisPassword);
        }

        $redis->select($redisDb);

        return $redis;
    } catch (Exception $e) {
        error_log("[VideoQueue] Failed to connect to Redis: " . $e->getMessage());
        return null;
    }
}

/**
 * Queue video conversion job
 *
 * @param string $sourcePath         Full path to source video
 * @param string $outputDir          Directory for output
 * @param string $filenameWithoutExt Output filename without extension
 * @return string|null               Job ID on success, null on failure
 */
function queueVideoConversion(
    string $sourcePath,
    string $outputDir,
    string $filenameWithoutExt
): ?string {
    $redis = getVideoQueueRedis();
    if (!$redis) {
        error_log("[VideoQueue] Redis not available, falling back to sync processing");
        return null;
    }

    $job = new VideoJob([
        'type' => 'convert',
        'data' => [
            'sourcePath' => $sourcePath,
            'outputDir' => $outputDir,
            'filenameWithoutExt' => $filenameWithoutExt,
        ],
    ]);

    $queue = new VideoQueue($redis);
    $success = $queue->addJob($job);

    return $success ? $job->id : null;
}

/**
 * Queue thumbnail creation job
 *
 * @param string $videoPath Full path to video file
 * @return string|null      Job ID on success, null on failure
 */
function queueThumbnailCreation(string $videoPath): ?string
{
    $redis = getVideoQueueRedis();
    if (!$redis) {
        error_log("[VideoQueue] Redis not available, falling back to sync processing");
        return null;
    }

    $job = new VideoJob([
        'type' => 'thumbnail',
        'data' => [
            'videoPath' => $videoPath,
        ],
    ]);

    $queue = new VideoQueue($redis);
    $success = $queue->addJob($job);

    return $success ? $job->id : null;
}

/**
 * Queue blurred reel creation job
 *
 * @param string $inputPath   Full path to input video
 * @param string $outputPath  Full path for output video
 * @param int    $maxDuration Maximum duration in seconds
 * @return string|null        Job ID on success, null on failure
 */
function queueBlurredReelCreation(
    string $inputPath,
    string $outputPath,
    int $maxDuration = 15
): ?string {
    $redis = getVideoQueueRedis();
    if (!$redis) {
        error_log("[VideoQueue] Redis not available, falling back to sync processing");
        return null;
    }

    $job = new VideoJob([
        'type' => 'reel_blur',
        'data' => [
            'inputPath' => $inputPath,
            'outputPath' => $outputPath,
            'maxDuration' => $maxDuration,
        ],
    ]);

    $queue = new VideoQueue($redis);
    $success = $queue->addJob($job);

    return $success ? $job->id : null;
}

/**
 * Get job status
 *
 * @param string $jobId Job ID
 * @return array|null   Status array or null if not found
 */
function getVideoJobStatus(string $jobId): ?array
{
    $redis = getVideoQueueRedis();
    if (!$redis) {
        return null;
    }

    $queue = new VideoQueue($redis);
    return $queue->getJobStatus($jobId);
}

/**
 * Get queue statistics
 *
 * @return array Statistics array with pending, processing, completed, failed counts
 */
function getVideoQueueStats(): array
{
    $redis = getVideoQueueRedis();
    if (!$redis) {
        return [
            'pending' => 0,
            'processing' => 0,
            'completed' => 0,
            'failed' => 0,
        ];
    }

    $queue = new VideoQueue($redis);
    return $queue->getStats();
}

/**
 * Check if async video processing is available
 *
 * @return bool True if Redis is available and worker is configured
 */
function isAsyncVideoProcessingAvailable(): bool
{
    $redis = getVideoQueueRedis();
    return $redis !== null;
}
