<?php

/**
 * Video Processing Job
 * Represents a single video processing task
 */
class VideoJob
{
    public string $id;
    public string $type; // 'convert' | 'thumbnail' | 'reel_blur' | 'reel_upload'
    public array $data;
    public string $status; // 'pending' | 'processing' | 'completed' | 'failed'
    public int $attempts;
    public ?string $error;
    public int $createdAt;
    public int $updatedAt;

    public function __construct(array $jobData)
    {
        $this->id = $jobData['id'] ?? uniqid('job_', true);
        $this->type = $jobData['type'] ?? 'convert';
        $this->data = $jobData['data'] ?? [];
        $this->status = $jobData['status'] ?? 'pending';
        $this->attempts = $jobData['attempts'] ?? 0;
        $this->error = $jobData['error'] ?? null;
        $this->createdAt = $jobData['createdAt'] ?? time();
        $this->updatedAt = $jobData['updatedAt'] ?? time();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'data' => $this->data,
            'status' => $this->status,
            'attempts' => $this->attempts,
            'error' => $this->error,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    public static function fromJson(string $json): ?self
    {
        $data = json_decode($json, true);
        if (!$data) {
            return null;
        }
        return new self($data);
    }
}
