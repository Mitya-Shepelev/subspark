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
    if (!file_exists($inputPath) || !is_readable($inputPath)) {
        return null;
    }

    if (!file_exists($outputDir) && !mkdir($outputDir, 0755, true)) {
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

    shell_exec($cmd);

    if (file_exists($outputPath) && filesize($outputPath) > 100000) {
        return $outputPath;
    }

    return null;
}
?>