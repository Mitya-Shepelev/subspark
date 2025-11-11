<?php

require_once __DIR__ . '/VideoJob.php';

/**
 * Redis-based Video Processing Queue
 * Manages video processing jobs using Redis lists
 */
class VideoQueue
{
    private Redis $redis;
    private string $queueKey = 'video_processing_queue';
    private string $processingKey = 'video_processing_active';
    private string $completedKey = 'video_processing_completed';
    private string $failedKey = 'video_processing_failed';

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    /**
     * Add a video processing job to the queue
     */
    public function addJob(VideoJob $job): bool
    {
        $jobJson = $job->toJson();

        // Store job details in a hash
        $this->redis->hSet("job:{$job->id}", 'data', $jobJson);
        $this->redis->hSet("job:{$job->id}", 'status', $job->status);
        $this->redis->hSet("job:{$job->id}", 'createdAt', $job->createdAt);

        // Add to queue
        $result = $this->redis->rPush($this->queueKey, $job->id);

        error_log("[VideoQueue] Added job {$job->id} to queue (type: {$job->type})");

        return $result !== false;
    }

    /**
     * Get next job from queue (blocking wait)
     */
    public function getNextJob(int $timeout = 5): ?VideoJob
    {
        // Use blocking left pop with timeout
        $result = $this->redis->blPop([$this->queueKey], $timeout);

        if (!$result) {
            return null;
        }

        $jobId = $result[1];

        // Get job data
        $jobJson = $this->redis->hGet("job:{$jobId}", 'data');
        if (!$jobJson) {
            error_log("[VideoQueue] ERROR: Job {$jobId} not found in storage");
            return null;
        }

        $job = VideoJob::fromJson($jobJson);
        if (!$job) {
            error_log("[VideoQueue] ERROR: Failed to decode job {$jobId}");
            return null;
        }

        // Mark as processing
        $job->status = 'processing';
        $job->updatedAt = time();
        $this->updateJob($job);

        // Add to processing set
        $this->redis->sAdd($this->processingKey, $jobId);

        error_log("[VideoQueue] Picked job {$jobId} for processing");

        return $job;
    }

    /**
     * Mark job as completed
     */
    public function completeJob(VideoJob $job): void
    {
        $job->status = 'completed';
        $job->updatedAt = time();
        $this->updateJob($job);

        // Remove from processing, add to completed
        $this->redis->sRem($this->processingKey, $job->id);
        $this->redis->sAdd($this->completedKey, $job->id);

        // Set TTL on job data (keep for 24 hours)
        $this->redis->expire("job:{$job->id}", 86400);

        error_log("[VideoQueue] Job {$job->id} completed successfully");
    }

    /**
     * Mark job as failed
     */
    public function failJob(VideoJob $job, string $error): void
    {
        $job->status = 'failed';
        $job->error = $error;
        $job->attempts++;
        $job->updatedAt = time();
        $this->updateJob($job);

        // Remove from processing, add to failed
        $this->redis->sRem($this->processingKey, $job->id);
        $this->redis->sAdd($this->failedKey, $job->id);

        // Keep failed jobs for 7 days for debugging
        $this->redis->expire("job:{$job->id}", 604800);

        error_log("[VideoQueue] Job {$job->id} failed: {$error}");
    }

    /**
     * Update job data in Redis
     */
    private function updateJob(VideoJob $job): void
    {
        $this->redis->hSet("job:{$job->id}", 'data', $job->toJson());
        $this->redis->hSet("job:{$job->id}", 'status', $job->status);
        $this->redis->hSet("job:{$job->id}", 'updatedAt', $job->updatedAt);
    }

    /**
     * Get job status
     */
    public function getJobStatus(string $jobId): ?array
    {
        $status = $this->redis->hGet("job:{$jobId}", 'status');
        $updatedAt = $this->redis->hGet("job:{$jobId}", 'updatedAt');

        if (!$status) {
            return null;
        }

        return [
            'id' => $jobId,
            'status' => $status,
            'updatedAt' => (int)$updatedAt,
        ];
    }

    /**
     * Get queue statistics
     */
    public function getStats(): array
    {
        return [
            'pending' => $this->redis->lLen($this->queueKey),
            'processing' => $this->redis->sCard($this->processingKey),
            'completed' => $this->redis->sCard($this->completedKey),
            'failed' => $this->redis->sCard($this->failedKey),
        ];
    }
}
