<?php

/**
 * Converts a video file to MP4 format using FFmpeg (H.264 + AAC).
 *
 * @param string $ffmpegPath            Full path to the ffmpeg binary.
 * @param string $sourcePath            Full path to the input video file.
 * @param string $outputDir             Directory where the MP4 will be saved.
 * @param string $filenameWithoutExt    Name for the output file, without extension.
 * @return string|null                  Returns the output path on success, or null on failure.
 */
function convertToMp4Format(
    string $ffmpegPath,
    string $sourcePath,
    string $outputDir,
    string $filenameWithoutExt
): ?string {
    $outputPath = rtrim($outputDir, '/') . '/' . $filenameWithoutExt . '.mp4';

    // If the source is already an MP4 and points to the same destination path,
    // do NOT run ffmpeg with identical input/output (that corrupts the file).
    $srcExt = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION) ?: '');
    $samePath = (realpath($sourcePath) && realpath($outputPath))
        ? (realpath($sourcePath) === realpath($outputPath))
        : ($sourcePath === $outputPath);
    if ($srcExt === 'mp4' && $samePath) {
        return $sourcePath;
    }

    // When input and output are the same path, write to a temp then rename.
    $targetPath = $samePath ? (rtrim($outputDir, '/') . '/' . $filenameWithoutExt . '__tmp__.mp4') : $outputPath;

    $cmd = $ffmpegPath . ' -i ' . escapeshellarg($sourcePath)
         . ' -c:v libx264 -preset fast -crf 23 -pix_fmt yuv420p'
         . ' -c:a aac -b:a 128k -strict -2 '
         . escapeshellarg($targetPath) . ' -y 2>&1';

    shell_exec($cmd);

    // Consider conversion successful for any non-trivial file (>1KB).
    if (file_exists($targetPath) && filesize($targetPath) > 1024) {
        if ($samePath) {
            // Move the temp into place and then remove the source.
            @rename($targetPath, $outputPath);
            @unlink($sourcePath);
            return $outputPath;
        }
        // Remove the original only if we're producing a separate output file.
        @unlink($sourcePath);
        return $targetPath;
    }

    return null;
}
?>
