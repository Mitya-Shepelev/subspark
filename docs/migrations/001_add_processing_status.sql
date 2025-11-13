-- Migration: Add processing_status column for async video processing
-- Date: 2024-11-13
-- Description: Adds processing_status column to track async video processing state

-- Add column if it doesn't exist
ALTER TABLE i_user_uploads
ADD COLUMN IF NOT EXISTS processing_status ENUM('processing', 'completed', 'failed') DEFAULT 'completed' AFTER upload_time;

-- For existing records, set as 'completed' (they were processed synchronously)
UPDATE i_user_uploads
SET processing_status = 'completed'
WHERE processing_status IS NULL;

-- Add index for faster queries on processing status
CREATE INDEX IF NOT EXISTS idx_processing_status ON i_user_uploads(processing_status);

-- Add index for user + processing status queries
CREATE INDEX IF NOT EXISTS idx_user_processing ON i_user_uploads(iuid_fk, processing_status);
