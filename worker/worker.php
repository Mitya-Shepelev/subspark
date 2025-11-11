#!/usr/bin/env php
<?php

/**
 * Video Processing Worker Entry Point
 *
 * This script runs continuously and processes video jobs from Redis queue.
 * It can be run on a separate server/container to offload FFmpeg processing.
 *
 * Usage:
 *   php worker.php
 *
 * Environment Variables:
 *   REDIS_HOST     - Redis server host (default: localhost)
 *   REDIS_PORT     - Redis server port (default: 6379)
 *   REDIS_PASSWORD - Redis password (optional)
 *   REDIS_DB       - Redis database number (default: 0)
 *   FFMPEG_PATH    - Path to ffmpeg binary (default: /usr/bin/ffmpeg)
 *   FFMPEG_PROBE   - Path to ffprobe binary (default: /usr/bin/ffprobe)
 */

require_once __DIR__ . '/VideoJob.php';
require_once __DIR__ . '/VideoQueue.php';
require_once __DIR__ . '/VideoWorker.php';

// Load environment variables
$redisHost = getenv('REDIS_HOST') ?: 'localhost';
$redisPort = (int)(getenv('REDIS_PORT') ?: 6379);
$redisPassword = getenv('REDIS_PASSWORD') ?: null;
$redisDb = (int)(getenv('REDIS_DB') ?: 0);
$ffmpegPath = getenv('FFMPEG_PATH') ?: '/usr/bin/ffmpeg';
$ffprobePath = getenv('FFMPEG_PROBE') ?: '/usr/bin/ffprobe';

echo "===========================================\n";
echo "  SubSpark Video Processing Worker\n";
echo "===========================================\n";
echo "Redis: {$redisHost}:{$redisPort} (DB: {$redisDb})\n";
echo "FFmpeg: {$ffmpegPath}\n";
echo "FFprobe: {$ffprobePath}\n";
echo "===========================================\n\n";

// Check if FFmpeg is available
if (!file_exists($ffmpegPath)) {
    die("ERROR: FFmpeg not found at {$ffmpegPath}\n");
}

// Connect to Redis
try {
    $redis = new Redis();
    $redis->connect($redisHost, $redisPort);

    if ($redisPassword) {
        $redis->auth($redisPassword);
    }

    $redis->select($redisDb);

    echo "✓ Connected to Redis\n";
} catch (Exception $e) {
    die("ERROR: Failed to connect to Redis: " . $e->getMessage() . "\n");
}

// Create queue and worker
$queue = new VideoQueue($redis);
$worker = new VideoWorker($queue, $ffmpegPath, $ffprobePath);

echo "✓ Worker initialized\n";
echo "✓ Waiting for jobs...\n\n";

// Start processing
$worker->start();

echo "\nWorker stopped.\n";
