<?php

/**
 * Converts a given video file to a blurred vertical Reels format (9:16) using FFmpeg.
 *
 * @param string $inputPath  Full path to the source video.
 * @param string $outputDir  Directory where the processed video will be saved.
 * @return string|null       Returns the output path on success, or null on failure.
 */
function convertVideoToBlurredReelsFormat(string $ffmpeg, string $inputPath, string $outputDir): ?string
{
    $logFile = __DIR__ . '/reels_conversion_debug.log';

    if (!file_exists($inputPath) || !is_readable($inputPath)) {
        @file_put_contents($logFile, "[ERROR] Input file not found or not readable: $inputPath\n", FILE_APPEND);
        return null;
    }

    if (!file_exists($outputDir) && !mkdir($outputDir, 0755, true)) {
        @file_put_contents($logFile, "[ERROR] Failed to create output directory: $outputDir\n", FILE_APPEND);
        return null;
    }

    $hash = md5($inputPath . microtime());
    $outputPath = rtrim($outputDir, '/') . '/' . $hash . '_reels_blur.mp4';

    $escapedInput = escapeshellarg($inputPath);
    $escapedOutput = escapeshellarg($outputPath);

    $cmd = "{$ffmpeg} -i {$escapedInput} "
         . "-filter_complex \"[0:v]scale=1080:-1[fg];"
         . "[0:v]scale=1080:1920:force_original_aspect_ratio=increase,"
         . "crop=1080:1920,boxblur=10[bg];"
         . "[bg][fg]overlay=(W-w)/2:(H-h)/2\" "
         . "-c:v libx264 -preset fast -crf 23 -pix_fmt yuv420p "
         . "-c:a aac -b:a 128k -strict -2 "
         . "{$escapedOutput} -y 2>&1";

    @file_put_contents($logFile, "[INFO] Running FFmpeg command:\n$cmd\n", FILE_APPEND);

    $output = shell_exec($cmd);

    @file_put_contents($logFile, "[INFO] FFmpeg output:\n$output\n", FILE_APPEND);

    if (file_exists($outputPath)) {
        $fileSize = filesize($outputPath);
        @file_put_contents($logFile, "[INFO] Output file created: $outputPath (size: $fileSize bytes)\n", FILE_APPEND);

        if ($fileSize > 100000) {
            return $outputPath;
        } else {
            @file_put_contents($logFile, "[ERROR] Output file too small (< 100KB), conversion likely failed\n", FILE_APPEND);
        }
    } else {
        @file_put_contents($logFile, "[ERROR] Output file not created: $outputPath\n", FILE_APPEND);
    }

    return null;
}
?>