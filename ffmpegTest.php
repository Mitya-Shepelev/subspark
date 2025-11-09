<?php include_once "includes/inc.php";?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>FFMPEG Tester</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu+Condensed|Ubuntu|PT+Sans+Narrow|Open+Sans:600,700&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?php echo iN_HelpSecure($base_url);?>src/ffmpeg-tester.css">
</head>
<body>
    <div class="fftester-container">
        <h1 class="fftester-title">FFMPEG Tester</h1>
        <hr class="fftester-hr">
        <div class="fftester-image-center">
            <img class="fftester-logo" src="//upload.wikimedia.org/wikipedia/commons/thumb/5/5f/FFmpeg_Logo_new.svg/1280px-FFmpeg_Logo_new.svg.png" alt="FFMPEG Logo">
        </div>
        <hr class="fftester-hr">

        <h3>This script has the function of testing if you have FFMPEG!</h3>
        <h3>Includes PHP version check for compatibility.</h3>
        <h3>If compatible, you can proceed with video conversion features.</h3>

        <?php
        $error = 0;

        function getDataFromUrl($url) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_USERAGENT => "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CONNECTTIMEOUT => 15
            ]);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }

        echo "<h2 class='fftester-subtitle'>Basic Requirements</h2>";

        if (phpversion() < 5.5) {
            echo "<div class='fftester-msg fftester-warning'>Error: PHP version is below 5.5 (Current: " . phpversion() . ")</div>";
            echo "<div class='fftester-msg fftester-note'>To ensure security, update your PHP version.</div>";
            $error++;
        } else {
            echo "<div class='fftester-msg fftester-success'>PHP version is OK: " . phpversion() . "</div>";
        }

        echo "<h2 class='fftester-subtitle'>FFMPEG Requirements</h2>";
        echo "<h4 class='fftester-note'>These features are necessary for video conversion, thumbnail generation, etc.</h4>";

        if (function_exists('exec')) {
            echo "<div class='fftester-msg fftester-success'>exec() is Enabled</div>";
        } else {
            echo "<div class='fftester-msg fftester-warning'>exec() is Disabled. This is required by FFMPEG.</div>";
            $error++;
        }

        if (function_exists('shell_exec')) {
            echo "<div class='fftester-msg fftester-success'>shell_exec() is Enabled</div>";
        } else {
            echo "<div class='fftester-msg fftester-warning'>shell_exec() is Disabled. This is required by FFMPEG.</div>";
            $error++;
        }

        if (function_exists('shell_exec')) {
            // Try multiple portable ways to locate binaries
            $ffmpeg = trim(shell_exec('command -v ffmpeg 2>/dev/null || type -P ffmpeg 2>/dev/null || which ffmpeg 2>/dev/null'));
            $ffprobe = trim(shell_exec('command -v ffprobe 2>/dev/null || type -P ffprobe 2>/dev/null || which ffprobe 2>/dev/null'));
            // Windows fallback
            if (stripos(PHP_OS_FAMILY, 'Windows') !== false) {
                if (!$ffmpeg) { $ffmpeg = trim(shell_exec('where ffmpeg')); }
                if (!$ffprobe) { $ffprobe = trim(shell_exec('where ffprobe')); }
            }

            // Show configured path if any
            if (!empty($ffmpegPath)) {
                echo "<div class='fftester-msg fftester-info'>Configured ffmpeg_path (Admin > Settings): <strong>" . htmlspecialchars($ffmpegPath) . "</strong></div>";
            }

            if (empty($ffmpeg)) {
                echo "<div class='fftester-msg fftester-warning'>FFMPEG not found in PATH.</div>";
                $error++;
            } else {
                echo "<div class='fftester-msg fftester-success'>FFMPEG found at: <strong>" . htmlspecialchars($ffmpeg) . "</strong></div>";
                $versionOutput = [];
                @exec(escapeshellarg($ffmpeg) . ' -version', $versionOutput);
                if (!empty($versionOutput)) {
                    echo "<pre class='fftester-pre'>" . htmlspecialchars(implode("\n", $versionOutput)) . "</pre>";
                }
            }

            echo "<h2 class='fftester-subtitle'>FFPROBE</h2>";
            echo "<h4 class='fftester-note'>FFprobe is required for reading video metadata (duration, codecs, etc.).</h4>";
            if (empty($ffprobe)) {
                echo "<div class='fftester-msg fftester-warning'>FFPROBE not found in PATH.</div>";
                echo "<div class='fftester-msg fftester-note'>If ffprobe is installed but not in PATH, add its full path in your server configuration.</div>";
                $error++;
            } else {
                echo "<div class='fftester-msg fftester-success'>FFPROBE found at: <strong>" . htmlspecialchars($ffprobe) . "</strong></div>";
                $probeOutput = [];
                @exec(escapeshellarg($ffprobe) . ' -version', $probeOutput);
                if (!empty($probeOutput)) {
                    echo "<pre class='fftester-pre'>" . htmlspecialchars(implode("\n", $probeOutput)) . "</pre>";
                }
            }
        }

        echo "<h2 class='fftester-subtitle'>Test Completed</h2>";

        if ($error > 0) {
            echo "<div class='fftester-msg fftester-warning'>Found $error issues. Please resolve them before continuing.</div>";
        } else {
            echo "<div class='fftester-msg fftester-success'>All Requirements Met!</div>";
            echo "<div class='fftester-msg fftester-info'>Your system is fully compatible with FFMPEG.</div>";
        }
        ?>
    </div>
</body>
</html>
