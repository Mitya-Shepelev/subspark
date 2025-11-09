<?php
// Load core application configuration and required constants
include "../includes/inc.php";

// Include thumbnail cropping helper (used for image resizing/cropping logic)
include "../includes/thumbncrop.inc.php";

// Keep legacy provider-specific classes available while refactoring
if ($digitalOceanStatus == '1') {
    // @include_once "../includes/spaces/spaces.php"; // legacy DO; disabled after unification
}
// Initialize a shared S3-compatible client for existing calls
if (!isset($s3)) { $s3 = storage_client(); }


// Include image filtering class (used for contrast, brightness, grayscale etc.)
include "../includes/imageFilter.php";

// Import the ImageFilter class from its namespace
use imageFilter\ImageFilter;

/* -------------------------------------------
 | Email Delivery: PHPMailer Integration
 --------------------------------------------*/

// Import PHPMailer classes into global scope
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer via Composer (make sure it's installed)
require '../includes/phpmailer/vendor/autoload.php';

// Instantiate PHPMailer (true enables exception handling)
$mail = new PHPMailer(true);

if (!function_exists('iN_safeMailSend')) {
    function iN_safeMailSend(PHPMailer $mail, string $mode, string $context = 'mail'): bool {
        try {
            return $mail->send();
        } catch (Exception $e) {
            error_log('[MAIL] ' . $context . ' SMTP failure: ' . $e->getMessage());
            if ($mode === 'smtp') {
                try {
                    $mail->smtpClose();
                    $mail->isMail();
                    return $mail->send();
                } catch (Exception $inner) {
                    error_log('[MAIL] ' . $context . ' mail() fallback failure: ' . $inner->getMessage());
                }
            }
        }
        return false;
    }
}

/* -------------------------------------------
 | Define Application-Wide Constants
 --------------------------------------------*/

// Ensure AJAX responses are clean (no leading BOM/whitespace from includes)
if (function_exists('ob_get_level') && ob_get_level() > 0) {
    @ob_clean();
}

// Lightweight debug logger for this request file
if (!function_exists('rq_debug')) {
    function rq_debug($msg, $ctx = []) {
        $log = __DIR__ . '/../includes/request_debug.log';
        $time = date('c');
        $line = '[' . $time . '] ' . $msg;
        if (!empty($ctx)) {
            $line .= ' | ' . json_encode($ctx, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        $line .= "\n";
        @file_put_contents($log, $line, FILE_APPEND);
    }
}

// Who can view (e.g., followers, subscribers, public etc.)
$whoCanSeeArrays = array('1', '2', '3', '4');

// Block types (user blocks, post blocks etc.)
$blockType = array('1', '2');

// Possible status flags
$statusValue = array('0', '1');

// Available UI themes
$themes = array('light', 'dark');

// Video formats supported if FFMPEG is not available
$nonFfmpegAvailableVideoFormat = array('mp4','MP4','mov','MOV');

// Supported payout methods for the platform
$defaultPayoutMethods = array('paypal', 'bank');

// Available gender options for users
$genders = array('male', 'couple', 'female');

// Common yes/no values
$yesOrNo = array('yes', 'no');

/* -------------------------------------------
 | OpenAI Integration (Chat Completion API)
 --------------------------------------------*/

if ($openAiStatus == '1') {
    /**
     * Call OpenAI Chat Completion API to generate creative responses
     *
     * @param string $userPrompt        User's input message
     * @param string $opanAiKey         API key for authorization
     * @return string                   AI-generated short response (max ~150 tokens)
     */
    function callOpenAI($userPrompt, $opanAiKey) {
        $apiKey = $opanAiKey;
        $url = 'https://api.openai.com/v1/chat/completions';

        $data = [
            "model" => "gpt-4-turbo",
            "messages" => [
                [
                    "role" => "system",
                    "content" => "You are a content creator assistant. Always respond with a creative text, maximum 250 characters."
                ],
                [
                    "role" => "user",
                    "content" => $userPrompt
                ]
            ],
            "temperature" => 0.8,
            "max_tokens" => 150
        ];

        $headers = [
            "Content-Type: application/json",
            "Authorization: Bearer $apiKey"
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['choices'][0]['message']['content'] ?? "no";
    }
}

/* -------------------------------------------
 | Watermark System (Image Branding Layer)
 --------------------------------------------*/

if ($watermarkStatus == 'yes') {
    /**
     * Apply watermark logo and optional link text to image
     */
    function watermark_image($target, $siteWatermarkLogo, $LinkWatermarkStatus, $ourl) {
        include_once "../includes/SimpleImage-master/src/claviska/SimpleImage.php";

        if ($LinkWatermarkStatus == 'yes') {
            try {
                $image = new \claviska\SimpleImage();
                $image
                    ->fromFile($target)
                    ->autoOrient()
                    ->overlay('../' . $siteWatermarkLogo, 'top left', 1, 30, 30)
                    ->text($ourl, array(
                        'fontFile' => '../src/droidsanschinese.ttf',
                        'size' => 15,
                        'color' => 'red',
                        'anchor' => 'bottom right',
                        'xOffset' => -10,
                        'yOffset' => -10
                    ))
                    ->toFile($target, 'image/jpeg');

                return true;
            } catch (Exception $err) {
                return $err->getMessage();
            }
        } else {
            try {
                $image = new \claviska\SimpleImage();
                $image
                    ->fromFile($target)
                    ->autoOrient()
                    ->overlay('../' . $siteWatermarkLogo, 'top left', 1, 30, 30)
                    ->toFile($target, 'image/jpeg');

                return true;
            } catch (Exception $err) {
                return $err->getMessage();
            }
        }
    }

} else if ($LinkWatermarkStatus == 'yes') {
    /**
     * Fallback watermark logic (only link text, no logo)
     */
    function watermark_image($target, $siteWatermarkLogo, $LinkWatermarkStatus, $ourl) {
        include_once "../includes/SimpleImage-master/src/claviska/SimpleImage.php";

        try {
            $image = new \claviska\SimpleImage();
            $image
                ->fromFile($target)
                ->autoOrient()
                ->overlay('../img/transparent.png', 'top left', 1, 30, 30)
                ->text($ourl, array(
                    'fontFile' => '../src/droidsanschinese.ttf',
                    'size' => 15,
                    'color' => '00897b',
                    'anchor' => 'bottom right',
                    'xOffset' => -10,
                    'yOffset' => -10
                ))
                ->toFile($target, 'image/jpeg');

            return true;
        } catch (Exception $err) {
            return $err->getMessage();
        }
    }
}

/**
 * Helper function to remove http:// or https:// from a given URL
 *
 * @param string $url
 * @return string cleaned URL without protocol
 */
function remove_http($url) {
    $disallowed = array('http://', 'https://');
    foreach ($disallowed as $d) {
        if (strpos($url, $d) === 0) {
            return str_replace($d, '', $url);
        }
    }
    return $url;
}

$type = null;
if (isset($_POST['f'])) {
    $type = $iN->iN_Secure($_POST['f']);
} elseif (isset($_GET['f'])) {
    $type = $iN->iN_Secure($_GET['f']);
}
if (isset($_POST['f']) && $logedIn == '1') {
	$loginFormClass = '';
	if ($type == 'topMenu') {
		include "../themes/$currentTheme/layouts/header/header_menu.php";
	}
	if ($type == 'topMessages') {
		$iN->iN_UpdateMessageNotificationStatus($userID);
		include "../themes/$currentTheme/layouts/header/messageNotifications.php";
	}
	if ($type == 'topNotifications') {
		$iN->iN_UpdateNotificationStatus($userID);
		include "../themes/$currentTheme/layouts/header/notifications.php";
	}
	if ($type == 'chooseLanguage') {
		include "../themes/$currentTheme/layouts/popup_alerts/chooseLanguage.php";
	}
	if ($type == "changeMyLang") {
		if (isset($_POST['id'])) {
			$langID = $iN->iN_Secure($_POST['id']);
			$updateUserLanguage = $iN->iN_UpdateLanguage($userID, $langID);
			if ($updateUserLanguage) {
				echo '200';
			} else {
				echo '404';
			}
		}
	}
	if ($type == 'topPoints') {
		$iN->iN_UpdateMessageNotificationStatus($userID);
		include "../themes/$currentTheme/layouts/header/points_box.php";
	}

	if ($type == 'notifications') {
		if (isset($_POST['last'])) {
			$lastID = $iN->iN_Secure($_POST['last']);
			$moreNotifications = $iN->iN_GetMoreNotificationList($userID, $scrollLimit, $lastID);
			if ($moreNotifications) {
				include "../themes/$currentTheme/layouts/loadmore/morenotifications.php";
			} else {
				echo '<div class="nomore"><div class="no_more_in">' . $LANG['no_more_notifications'] . '</div></div>';
			}
		}
	}
	if ($type == 'whoSee') {
		if (isset($_POST['who']) && in_array($_POST['who'], $whoCanSeeArrays)) {
			$whoID = $iN->iN_Secure($_POST['who']);
			$updateWhoCanSee = $iN->iN_UpdateWhoCanSeePost($userID, $whoID);
			if ($updateWhoCanSee) {
				if ($whoID == 1) {
					$UpdatedWhoCanSee = '<div class="form_who_see_icon_set">' . $iN->iN_SelectedMenuIcon('50') . '</div> ' . $LANG['weveryone'];
				} else if ($whoID == 2) {
					$UpdatedWhoCanSee = '<div class="form_who_see_icon_set">' . $iN->iN_SelectedMenuIcon('15') . '</div> ' . $LANG['wfollowers'];
				} else if ($whoID == 3) {
					$UpdatedWhoCanSee = '<div class="form_who_see_icon_set">' . $iN->iN_SelectedMenuIcon('51') . '</div> ' . $LANG['wsubscribers'];
				} else if ($whoID == 4) {
					$UpdatedWhoCanSee = '<div class="form_who_see_icon_set">' . $iN->iN_SelectedMenuIcon('9') . '</div> ' . $LANG['wpremium'];
				}
				echo html_entity_decode($UpdatedWhoCanSee);
			} else {
				echo '403';
			}
		}
	}
	if ($type == 'pw_premium') {
		echo '<div class="point_input_wrapper">
            <input type="text" name="point" class="pointIN" id="point" onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)" placeholder="' . $LANG['write_points'] . '">
            <div class="box_not box_not_padding_left">' . $LANG['point_wanted'] . '</div>
        </div>';
	}


if ($type === 'uploadReel' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Feature toggle: allow only if reels feature is enabled in admin limits
    if (isset($reelsFeatureStatus) && (string)$reelsFeatureStatus !== '1') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => ($LANG['feature_disabled'] ?? 'Reels feature is disabled')]);
        exit;
    }
    $__dbg_id = uniqid('uploadReel_', true);
    rq_debug('uploadReel:start', ['id' => $__dbg_id, 'uid' => $userID]);
    // Log fatals
    register_shutdown_function(function() use ($__dbg_id) {
        $e = error_get_last();
        if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            rq_debug('uploadReel:shutdown_fatal', ['id' => $__dbg_id, 'err' => $e]);
        }
    });
    // Defensive limits: reels processing can be CPU heavy
    @set_time_limit(300);
    @ini_set('memory_limit', '512M');

    // Ensure required server functions are available
    if (!function_exists('shell_exec')) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Server config: shell_exec is disabled. Enable it to process videos.']);
        exit;
    }
    // Set duration limit from configuration (admin limits)
    if (!defined('MAX_VIDEO_DURATION')) {
        $cfgDur = isset($maxVideoDuration) ? (int)$maxVideoDuration : 17;
        define('MAX_VIDEO_DURATION', $cfgDur > 0 ? $cfgDur : 17);
    }
    header('Content-Type: application/json');

    foreach ($_FILES['uploading']['name'] as $iname => $value) {
        $name = stripslashes($_FILES['uploading']['name'][$iname]);
        $size = $_FILES['uploading']['size'][$iname];
        $ext = strtolower(getExtension($name));
        $validFormats = explode(',', $availableFileExtensions);

        $tmp = $_FILES['uploading']['tmp_name'][$iname];
        $mimeType = $_FILES['uploading']['type'][$iname];

        if (!preg_match('/video\/*/', $mimeType)) {
            echo json_encode(['status' => 'error', 'message' => $LANG['only_video_files_allowed']]);
            exit;
        }

        if (!in_array($ext, $validFormats, true)) {
            echo json_encode(['status' => 'error', 'message' => $LANG['invalid_file_format']]);
            exit;
        }

        if (convert_to_mb($size) > $availableUploadFileSize) {
            echo json_encode(['status' => 'error', 'message' => $LANG['file_is_too_large']]);
            exit;
        }

        $microtime = microtime();
        $removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
        $uploadedFileName = 'reel_' . $removeMicrotime . '_' . $userID;
        $filenameWithExt = $uploadedFileName . '.' . $ext;
        $todayDir = date('Y-m-d');
        $uploadDir = $uploadFile . $todayDir . '/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadedPath = $uploadDir . $filenameWithExt;

        if (!move_uploaded_file($tmp, $uploadedPath)) {
            rq_debug('uploadReel:move_uploaded_file_failed', ['tmp' => $tmp, 'dst' => $uploadedPath, 'err' => error_get_last()]);
            echo json_encode(['status' => 'error', 'message' => $LANG['upload_failed']]);
            exit;
        }

        require_once '../includes/convertToMp4Format.php';
        require_once '../includes/convertVideoToBlurredReelsFormat.php';
        require_once '../includes/createVideoThumbnail.php';

        // Resolve ffmpeg binary: prefer configured path, then PATH lookup, then fallback name
        $ffmpegBin = isset($ffmpegPath) && !empty($ffmpegPath) ? $ffmpegPath : '';
        if (!$ffmpegBin && function_exists('shell_exec')) {
            $ffmpegBin = trim(@shell_exec('command -v ffmpeg 2>/dev/null || which ffmpeg 2>/dev/null'));
        }
        if (!$ffmpegBin) { $ffmpegBin = 'ffmpeg'; }

        $convertedDir = '../uploads/files/' . $todayDir;
        $convertedPath = $convertedDir . '/' . $uploadedFileName . '.mp4';

        // Orijinal dosya MP4 değilse dönüştür
        rq_debug('uploadReel:post-move', ['path' => $uploadedPath, 'ext' => $ext]);
        if ($ext !== 'mp4') {
            $converted = convertToMp4Format($ffmpegBin, $uploadedPath, $convertedDir, $uploadedFileName);
            if (!$converted) {
                rq_debug('uploadReel:convertToMp4Format_failed', ['src' => $uploadedPath, 'dstDir' => $convertedDir]);
                echo json_encode(['status' => 'error', 'message' => $LANG['mp4_conversion_failed']]);
                exit;
            }
            $convertedPath = $converted;
            $checkDurationPath = $convertedPath;
        } else {
            // Ensure target dir exists for mp4 straight move
            if (!file_exists($convertedDir)) { @mkdir($convertedDir, 0755, true); }
            @rename($uploadedPath, $convertedPath);
            $checkDurationPath = $convertedPath;
        }
        // ffprobe yolu: config > PATH autodetect > binary name fallback
        $probeBin = isset($ffprobePath) && !empty($ffprobePath) ? $ffprobePath : '';
        if (!$probeBin && function_exists('shell_exec')) {
            $probeBin = trim(@shell_exec('command -v ffprobe 2>/dev/null || which ffprobe 2>/dev/null'));
        }
        if (!$probeBin) { $probeBin = 'ffprobe'; }
        // Süreyi kontrol et
        $ffprobeCmd = escapeshellcmd($probeBin) . ' -v error -show_entries format=duration -of default=noprint_wrappers=1:nokey=1 ' . escapeshellarg($checkDurationPath);
        $durationOutput = shell_exec($ffprobeCmd);
        $duration = floatval($durationOutput);

        // Debug log
        // Write debug into includes (not publicly accessible by .htaccess)
        @file_put_contents(__DIR__ . '/../includes/reels_conversion_debug.log', "CMD: $ffprobeCmd\nDURATION_RAW: $durationOutput\nDURATION: $duration\n", FILE_APPEND);

        if ($duration === 0.0) {
            rq_debug('uploadReel:ffprobe_zero_duration', ['cmd' => $ffprobeCmd, 'raw' => $durationOutput]);
            echo json_encode(['status' => 'error', 'message' => $LANG['unable_to_read_video_duration']]);
            exit;
        }

        if ($duration > MAX_VIDEO_DURATION) {
            unlink($convertedPath);
            echo json_encode([
                'status' => 'error',
                'message' => str_replace('{seconds}', MAX_VIDEO_DURATION, $LANG['video_length_exceeds_limit'])
            ]);
            exit;
        }

        $reelsDir = '../uploads/reels/' . $todayDir;
        if (!file_exists($reelsDir)) {
            mkdir($reelsDir, 0755, true);
        }

        $finalReelsPath = convertVideoToBlurredReelsFormat($ffmpegBin, $convertedPath, $reelsDir);
        if (!$finalReelsPath || !file_exists($finalReelsPath)) {
            rq_debug('uploadReel:convertVideoToBlurredReelsFormat_failed', ['input' => $convertedPath, 'outdir' => $reelsDir, 'out' => $finalReelsPath]);
            echo json_encode(['status' => 'error', 'message' => $LANG['reels_conversion_failed']]);
            exit;
        }

        $relativePath = str_replace('../', '', $finalReelsPath);
        $ext = 'mp4';

        $thumbnailPath = createVideoThumbnailInSameDir($ffmpegBin, $finalReelsPath);
        // Normalize to a storage-relative path (e.g., 'uploads/...') for publishing/DB
        if ($thumbnailPath) {
            $thumbnailPath = str_replace('../', '', $thumbnailPath);
        } else {
            $thumbnailPath = 'uploads/web.png';
        }
        rq_debug('uploadReel:converted', ['video' => $finalReelsPath, 'thumb' => $thumbnailPath]);

        // Publish final reel + thumbnail via unified storage
        $reelPublishKeys = [];
        if (is_file('../' . $relativePath)) { $reelPublishKeys[] = $relativePath; }
        if ($thumbnailPath && is_file('../' . $thumbnailPath)) { $reelPublishKeys[] = $thumbnailPath; }
        if ($reelPublishKeys) {
            try { storage_publish_many($reelPublishKeys, true, true); }
            catch (Throwable $e) { rq_debug('uploadReel:storage_publish_many_exception', ['msg' => $e->getMessage()]); }
        }
        $newUploadId = $iN->iN_INSERTUploadedReelFile($userID, $relativePath, $thumbnailPath, $thumbnailPath, $ext);
        $getUploadedFileID = $iN->iN_GetUploadedFilesIDs($userID, $relativePath);

        $payload = [
            'status' => 'success',
            'file_id' => $getUploadedFileID ?: ['upload_id' => (int)$newUploadId],
            'uploaded_file_path' => $relativePath,
            'upload_thumbnail_file_path' => $thumbnailPath
        ];
        $json = json_encode($payload, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            rq_debug('uploadReel:json_encode_failed', ['err' => json_last_error_msg(), 'payload' => $payload]);
            echo json_encode(['status' => 'error', 'message' => 'json_encode_failed']);
        } else {
            rq_debug('uploadReel:done', ['id' => $__dbg_id, 'upload_id' => $newUploadId, 'rel' => $relativePath]);
            echo $json;
        }
        exit;
    }
}
	/*Video Custom Tumbnail*/
	if($type == 'vTumbnail'){
		if(isset($_POST['id']) && !empty($_POST['id'])){
			$dataID = $iN->iN_Secure($_POST['id']);
			$checkIDExist = $iN->iN_CheckImageIDExist($dataID, $userID);
			if($checkIDExist){
				if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
					foreach ($_FILES['uploading']['name'] as $iname => $value) {
						$name = stripslashes($_FILES['uploading']['name'][$iname]);
						$size = $_FILES['uploading']['size'][$iname];
						$ext = getExtension($name);
						$ext = strtolower($ext);
						$valid_formats = explode(',', $availableVerificationFileExtensions);
						if (in_array($ext, $valid_formats)) {
						if (!(convert_to_mb($size) < $availableUploadFileSize)) { echo iN_HelpSecure($size); continue; } else {
								$microtime = microtime();
								$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
								$UploadedFileName = "image_" . $removeMicrotime . '_' . $userID;
								$getFilename = $UploadedFileName . "." . $ext;
								// Change the image ame
								$tmp = $_FILES['uploading']['tmp_name'][$iname];
								$mimeType = $_FILES['uploading']['type'][$iname];
								$d = date('Y-m-d');
								if (preg_match('/video\/*/', $mimeType)) {
									$fileTypeIs = 'video';
								} else if (preg_match('/image\/*/', $mimeType)) {
									$fileTypeIs = 'Image';
								}
								if (!file_exists($uploadFile . $d)) {
									$newFile = mkdir($uploadFile . $d, 0755);
								}
								if (!file_exists($xImages . $d)) {
									$newFile = mkdir($xImages . $d, 0755);
								}
								if (!file_exists($xVideos . $d)) {
									$newFile = mkdir($xVideos . $d, 0755);
								}
								if (move_uploaded_file($tmp, $uploadFile . $d . '/' . $getFilename)) {
                                  $tumbFilePath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.'.$ext;
								  $thePath = '../uploads/files/'.$d.'/'.$UploadedFileName . '.' . $ext;
								  if (file_exists($thePath)) {
									try {
										$dir = "../uploads/xvideos/" . $d . "/" . $UploadedFileName . '.'.$ext;
										$fileUrl = '../uploads/files/' . $d . '/' . $UploadedFileName . '.'.$ext;
										$image = new ImageFilter();
										$image->load($fileUrl)->pixelation($pixelSize)->saveFile($dir, 100, "jpg");
									} catch (Exception $e) {
										echo '<span class="request_warning">' . $e->getMessage() . '</span>';
									}
								}else{
									exit($LANG['upload_failed']);
								}
										// Unified publish handled below
                                // Publish new thumbnail and optional xvideos preview; then update and echo URL
                                $keys = [];
                                $k1 = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.' . $ext;
                                $k2 = 'uploads/files/' . $d . '/' . $UploadedFileName . '.' . $ext;
                                if (is_file('../' . $k1)) { $keys[] = $k1; }
                                if (is_file('../' . $k2)) { $keys[] = $k2; }
                                if ($keys) { storage_publish_many($keys, true, true); }
                                $UploadSourceUrl = storage_public_url($k2);

                                // Update DB record to point custom thumbnail and respond with public URL
                                $updateTumbData = $iN->iN_UpdateUploadedFiles($userID, $tumbFilePath, $dataID);
                                if($updateTumbData){ echo $UploadSourceUrl; }
								}
							}
						}
					}
				}
			}
		}
	}
if ($type == 'upload') {
    // Unified, simplified upload handler (bypasses legacy provider-specific branches)
    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_FILES['uploading']['name'] as $iname => $value) {
            $name = stripslashes($_FILES['uploading']['name'][$iname]);
            $size = $_FILES['uploading']['size'][$iname];
            $ext = strtolower(getExtension($name));
            $valid_formats = explode(',', $availableFileExtensions);
            if (!in_array($ext, $valid_formats)) { continue; }
            if (convert_to_mb($size) >= $availableUploadFileSize) { echo iN_HelpSecure($size); continue; }

            $microtime = microtime();
            $removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
            $UploadedFileName = 'image_' . $removeMicrotime . '_' . $userID;
            $getFilename = $UploadedFileName . '.' . $ext;

            $tmp = $_FILES['uploading']['tmp_name'][$iname];
            $mimeType = $_FILES['uploading']['type'][$iname];
            $d = date('Y-m-d');

            // Determine file type
            if (preg_match('/video\/*/', $mimeType) || $mimeType === 'application/octet-stream') {
                $fileTypeIs = 'video';
            } else if (preg_match('/image\/*/', $mimeType)) {
                $fileTypeIs = 'Image';
            } else if (preg_match('/audio\/*/', $mimeType)) {
                $fileTypeIs = 'audio';
            } else { $fileTypeIs = 'Image'; }

            // Ensure directories
            if (!file_exists($uploadFile . $d)) { @mkdir($uploadFile . $d, 0755, true); }
            if (!file_exists($xImages . $d)) { @mkdir($xImages . $d, 0755, true); }
            if (!file_exists($xVideos . $d)) { @mkdir($xVideos . $d, 0755, true); }
            $wVideos = rtrim(UPLOAD_DIR_VIDEOS, '/') . '/';
            if (!file_exists($wVideos . $d)) { @mkdir($wVideos . $d, 0755, true); }

            if ($fileTypeIs === 'video' && $ffmpegStatus == '0' && !in_array($ext, $nonFfmpegAvailableVideoFormat)) { exit('303'); }
            if (!move_uploaded_file($tmp, $uploadFile . $d . '/' . $getFilename)) { echo $LANG['something_wrong']; continue; }

            $postTypeIcon = '';
            $pathFile = '';
            $pathXFile = '';
            $tumbnailPath = '';
            $UploadSourceUrl = '';

            if ($fileTypeIs === 'video') {
                $postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('52') . '</div>';
                $sourceFs = $uploadFile . $d . '/' . $getFilename;
                if ($ffmpegStatus == '1') {
                    require_once '../includes/convertToMp4Format.php';
                    require_once '../includes/createVideoThumbnail.php';
                    $convertedFs = convertToMp4Format($ffmpegPath, $sourceFs, $uploadFile . $d, $UploadedFileName);
                    if (!$convertedFs || !file_exists($convertedFs)) { $convertedFs = $sourceFs; }
                    $thumbFs = createVideoThumbnailInSameDir($ffmpegPath, $convertedFs);
                    $pathFile = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
                    $pathXFile = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                    if (!file_exists('../uploads/xvideos/' . $d)) { @mkdir('../uploads/xvideos/' . $d, 0755, true); }
                    $xVideoFirstPath = '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                    $safeCmd = $ffmpegPath . ' -ss 00:00:01 -i ' . escapeshellarg($convertedFs) . ' -c copy -t 00:00:04 ' . escapeshellarg($xVideoFirstPath) . ' 2>&1';
                    shell_exec($safeCmd);
                    $videoTumbnailPath = '../uploads/files/' . $d . '/' . $UploadedFileName . '.png';
                    if (file_exists($videoTumbnailPath)) {
                        try { $dir = '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.jpg'; $image = new ImageFilter(); $image->load($videoTumbnailPath)->pixelation($pixelSize)->saveFile($dir, 100, 'jpg'); } catch (Exception $e) { echo '<span class="request_warning">' . $e->getMessage() . '</span>'; }
                    }
                    $tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                    $thePathM = '../' . $tumbnailPath;
                    if (file_exists($thePathM) && ($watermarkStatus == 'yes' || $LinkWatermarkStatus == 'yes')) { watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url . $userName); }
                    $publishKeys = [];
                    $mp4Key = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
                    $xclipKey = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                    $thumbJpg = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                    $thumbPng = 'uploads/files/' . $d . '/' . $UploadedFileName . '.png';
                    if (is_file('../' . $mp4Key)) { $publishKeys[] = $mp4Key; }
                    if (is_file('../' . $xclipKey)) { $publishKeys[] = $xclipKey; }
                    if (is_file('../' . $thumbJpg)) { $publishKeys[] = $thumbJpg; }
                    if (is_file('../' . $thumbPng)) { $publishKeys[] = $thumbPng; }
                    if (!empty($publishKeys)) { storage_publish_many($publishKeys, true, true); }
                    if (is_file('../' . $thumbJpg)) { $UploadSourceUrl = storage_public_url($thumbJpg); }
                    elseif (is_file('../' . $thumbPng)) { $UploadSourceUrl = storage_public_url($thumbPng); }
                    elseif (is_file('../' . $mp4Key)) { $UploadSourceUrl = storage_public_url($mp4Key); }
                    else { $UploadSourceUrl = $base_url . 'uploads/web.png'; $tumbnailPath = 'uploads/web.png'; }
                    $ext = 'mp4';
                } else {
                    $pathFile = 'uploads/files/' . $d . '/' . $getFilename;
                    $pathXFile = 'uploads/files/' . $d . '/' . $getFilename;
                    storage_publish_many([$pathFile], true, true);
                    $UploadSourceUrl = storage_public_url($pathFile);
                }
            } else if ($fileTypeIs === 'Image') {
                $postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('53') . '</div>';
                $pathFile = 'uploads/files/' . $d . '/' . $getFilename;
                $pixelKey = 'uploads/pixel/' . $d . '/' . $getFilename;
                $resizedFileTwo = '../uploads/files/' . $d . '/' . $UploadedFileName . '__' . $userID . '.' . $ext;

                // Create a resized copy; if it fails, fall back to original
                try {
                    $tb = new ThumbAndCrop();
                    $tb->openImg('../' . $pathFile);
                    $newHeight = $tb->getRightHeight(500);
                    $tb->creaThumb(500, $newHeight);
                    $tb->setThumbAsOriginal();
                    $tb->creaThumb(500, $newHeight);
                    $tb->saveThumb($resizedFileTwo);
                } catch (Exception $e) {
                    // Ignore and fall back to original below
                }

                $tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '__' . $userID . '.' . $ext;
                if (!is_file('../' . $tumbnailPath)) {
                    // Resized file missing; use original path as thumbnail
                    $tumbnailPath = $pathFile;
                }

                if ($ext !== 'gif') {
                    $thePathM = '../' . $pathFile;
                    if ($watermarkStatus == 'yes' || $LinkWatermarkStatus == 'yes') {
                        watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url . $userName);
                    }
                }

                // Generate pixelated preview (best-effort)
                try {
                    $dir = '../' . $pixelKey;
                    if (!file_exists(dirname($dir))) { @mkdir(dirname($dir), 0755, true); }
                    $image = new ImageFilter();
                    $image->load('../' . $pathFile)->pixelation($pixelSize)->saveFile($dir, 100, 'jpg');
                } catch (Exception $e) {
                    echo '<span class="request_warning">' . $e->getMessage() . '</span>';
                }

                // Publish available files and pick a valid URL to show
                storage_publish_many([$pathFile, $pixelKey, $tumbnailPath], true, true);
                $UploadSourceUrl = storage_publish_pick_url([$tumbnailPath, $pathFile]) ?? ($base_url . 'uploads/web.png');
                $pathXFile = $pixelKey;
            } else { // audio
                $postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('53') . '</div>';
                $pathFile = 'uploads/files/' . $d . '/' . $getFilename;
                $tumbnailPath = 'src/audio.png';
                $pathXFile = 'src/audio.png';
                storage_publish_many([$pathFile], true, true);
                $UploadSourceUrl = storage_public_url($pathFile);
            }

            // Save and render
            $insertFileFromUploadTable = $iN->iN_INSERTUploadedFiles($userID, $pathFile, $tumbnailPath, $pathXFile, $ext);
            $getUploadedFileID = $iN->iN_GetUploadedFilesIDs($userID, $pathFile);
            $uploadTumbnail = '';
            if ($fileTypeIs == 'video') {
                $uploadTumbnail = '<div class="v_custom_tumb"><label for="vTumb_' . $getUploadedFileID['upload_id'] . '"><div class="i_image_video_btn"><div class="pbtn pbtn_plus">' . $LANG['custom_tumbnail'] . '</div></div><input type="file" id="vTumb_' . $getUploadedFileID['upload_id'] . '" class="imageorvideo cTumb editAds_file" data-id="' . $getUploadedFileID['upload_id'] . '" name="uploading[]" data-id="tupload"></label></div>';
            }
            if ($fileTypeIs == 'video' || $fileTypeIs == 'Image') {
                echo '<div class="i_uploaded_item iu_f_' . $getUploadedFileID['upload_id'] . ' ' . $fileTypeIs . '" id="' . $getUploadedFileID['upload_id'] . '">' . $postTypeIcon . '<div class="i_delete_item_button" id="' . $getUploadedFileID['upload_id'] . '">' . $iN->iN_SelectedMenuIcon('5') . '</div><div class="i_uploaded_file" id="viTumb' . $getUploadedFileID['upload_id'] . '" style="background-image:url(' . $UploadSourceUrl . ');"><img class="i_file" id="viTumbi' . $getUploadedFileID['upload_id'] . '" src="' . $UploadSourceUrl . '" alt="tumbnail"></div>' . $uploadTumbnail . '</div>';
            } else {
                echo '<div id="playing_' . $getUploadedFileID['upload_id'] . '" class="green-audio-player"><div class="i_uploaded_item nonePoint iu_f_' . $getUploadedFileID['upload_id'] . ' ' . $fileTypeIs . '"  id="' . $getUploadedFileID['upload_id'] . '"></div><audio crossorigin="" preload="none"><source src="' . $UploadSourceUrl . '" type="audio/mp3" /></audio><script>$(function(){ new GreenAudioPlayer("#playing_' . $getUploadedFileID['upload_id'] . '", { stopOthersOnPlay: true, showTooltips: true, showDownloadButton: false, enableKeystrokes: true });});</script></div>';
            }
        }
        // Stop executing legacy upload code below
        exit;
    }
}
		/*DELETE UPLOADED FILE BEFORE PUBLISH*/
	if ($type == 'delete_file') {
		if (isset($_POST['file'])) {
			$fileID = $iN->iN_Secure($_POST['file']);
			$deleteFileFromData = $iN->iN_DeleteFile($userID, $fileID);
			if ($deleteFileFromData) {
				echo '200';
			} else {
				echo '404';
			}
		}
	}
	/*Insert New Reels*/
	if($type == 'insertNewReel'){
	    if (isset($reelsFeatureStatus) && (string)$reelsFeatureStatus !== '1') { exit('reels_disabled'); }
	    rq_debug('insertNewReel:start', ['uid' => $userID, 'raw_txt' => isset($_POST['txt']) ? (string)$_POST['txt'] : null, 'raw_file' => isset($_POST['file']) ? (string)$_POST['file'] : null]);
	    if(isset($_POST['txt']) && isset($_POST['file'])){
	        $text = $iN->iN_Secure($_POST['txt']);
	        $file = $iN->iN_Secure($_POST['file']);
	        if(empty($iN->iN_Secure($text)) && empty($file)){
	           echo '200';
	           exit();
	        }
	        if($file != '' && !empty($file) && $file != 'undefined'){
				$trimValue = rtrim($file, ',');
				$explodeFiles = explode(',', $trimValue);
				$explodeFiles = array_unique($explodeFiles);
				foreach($explodeFiles as $explodeFile){
					$theFileID = $iN->iN_GetUploadedFileDetails($explodeFile);
					$uploadedFileID = isset($theFileID['upload_id']) ? $theFileID['upload_id'] : NULL;
					if(isset($uploadedFileID)){
                       $updateUploadStatus = $iN->iN_UpdateUploadStatus($uploadedFileID);
					}
					if(empty($uploadedFileID)){
					   exit('204');
					}
				}
			}

	        if (!empty($text)) {
				$slug = $iN->url_slugies(mb_substr($text, 0, 55, "utf-8"));
			} else {
				$slug = $iN->random_code(8);
			}
			if ($userWhoCanSeePost == '4') {
				$premiumPointAmount = $iN->iN_Secure($_POST['point']);
				if ($premiumPointAmount == '' || !isset($premiumPointAmount) || empty($premiumPointAmount)) {
					exit('201');
				}
				$number = preg_match("/^(?!\.)(?!.*\.$)(?!.*?\.\.)[0-9.]+$/", $premiumPointAmount, $m);

				$premiumPointAmount = isset($m[0]) ? $m[0] : NULL;
				if(!$premiumPointAmount){
                   exit('201');
				}
			} else { $premiumPointAmount = '';}
			$hashT = $iN->iN_hashtag($text);
			$postFromData = $iN->iN_InsertNewReelsPost($userID, $iN->iN_Secure($text), $slug, $file, $userWhoCanSeePost, $iN->url_Hash($hashT), $iN->iN_Secure($premiumPointAmount), $autoApprovePostStatus);
	        if ($postFromData) {
	            rq_debug('insertNewReel:success', ['post_id' => $postFromData['post_id'] ?? null, 'file' => $file]);
	            echo 'REELS_ID:' . $file;
                exit();
	        }
	        rq_debug('insertNewReel:failed');
	    }
	}
	/*INSERT NEW POST*/
	if ($type == 'newPost') {
		if (isset($_POST['txt']) && isset($_POST['file'])) {
			$text = $iN->iN_Secure($_POST['txt']);
			$file = $iN->iN_Secure($_POST['file']);
			if (empty($iN->iN_Secure($text)) && empty($file)) {
				echo '200';
				exit();
			}
			if($file != '' && !empty($file) && $file != 'undefined'){
				$trimValue = rtrim($file, ',');
				$explodeFiles = explode(',', $trimValue);
				$explodeFiles = array_unique($explodeFiles);
				foreach($explodeFiles as $explodeFile){
					$theFileID = $iN->iN_GetUploadedFileDetails($explodeFile);
					$uploadedFileID = isset($theFileID['upload_id']) ? $theFileID['upload_id'] : NULL;
					if(isset($uploadedFileID)){
                       $updateUploadStatus = $iN->iN_UpdateUploadStatus($uploadedFileID);
					}
					if(empty($uploadedFileID)){
					   exit('204');
					}
				}
			}
			if (!empty($text)) {
				$slug = $iN->url_slugies(mb_substr($text, 0, 55, "utf-8"));
			} else {
				$slug = $iN->random_code(8);
			}
			if ($userWhoCanSeePost == '4') {
				$premiumPointAmount = $iN->iN_Secure($_POST['point']);
				if ($premiumPointAmount == '' || !isset($premiumPointAmount) || empty($premiumPointAmount)) {
					exit('201');
				}
				$number = preg_match("/^(?!\.)(?!.*\.$)(?!.*?\.\.)[0-9.]+$/", $premiumPointAmount, $m);

				$premiumPointAmount = isset($m[0]) ? $m[0] : NULL;
				if(!$premiumPointAmount){
                   exit('201');
				}
			} else { $premiumPointAmount = '';}
			$hashT = $iN->iN_hashtag($text);
			$postFromData = $iN->iN_InsertNewPost($userID, $iN->iN_Secure($text), $slug, $file, $userWhoCanSeePost, $iN->url_Hash($hashT), $iN->iN_Secure($premiumPointAmount), $autoApprovePostStatus);

			if ($postFromData) {
				$userPostID = $postFromData['post_id'];
				$userPostOwnerID = $postFromData['post_owner_id'];
				if($ataNewPostPointAmount && $ataNewPostPointSatus == 'yes' && str_replace(".", "",$iN->iN_TotalEarningPointsInaDay($userID)) < str_replace(".", "",$maximumPointInADay)){
					$iN->iN_InsertNewPoint($userID,$userPostID,$ataNewPostPointAmount);
				}
				$userPostText = isset($postFromData['post_text']) ? $postFromData['post_text'] : NULL;
                if($userPostText){
                   $iN->iN_InsertMentionedUsersForPost($userID, $userPostText, $userPostID, $userName,$userPostOwnerID);
				}
				$userPostFile = $postFromData['post_file'];
				$userPostCreatedTime = $postFromData['post_created_time'];
				$crTime = date('Y-m-d H:i:s', $userPostCreatedTime);
				$userPostWhoCanSee = $postFromData['who_can_see'];
				if($autoApprovePostStatus == 'yes' && $userPostWhoCanSee == '4'){
					$approveNot = $LANG['congratulations_approved'];
					$postApprover = $iN->iN_GetAdminUserID();
					$approveUpdate = $iN->iN_UpdateApprovePostStatusAuto($postApprover, $iN->iN_Secure($userPostID), $iN->iN_Secure($userPostOwnerID), $iN->iN_Secure($approveNot));
				}
				$planIcon  = NULL;
				$checkPostBoosted=  NULL;
				$userPostWantStatus = $postFromData['post_want_status'];
				$userPostWantedCredit = $postFromData['post_wanted_credit'];
				$userPostStatus = $postFromData['post_status'];
				$userPostOwnerUsername = $postFromData['i_username'];
				$userPostOwnerUserFullName = $postFromData['i_user_fullname'];
				$userPostOwnerUserGender = $postFromData['user_gender'];
				$userPostHashTags = isset($postFromData['hashtags']) ? $postFromData['hashtags'] : NULL;
				$getUserPaymentMethodStatus = isset($postFromData['payout_method']) ? $postFromData['payout_method'] : NULL;
				$userPostCommentAvailableStatus = $postFromData['comment_status'];
				$userPostOwnerUserLastLogin = $postFromData['last_login_time'];
                $userProfileCategory = isset($postFromData['profile_category']) ? $postFromData['profile_category'] : NULL;
				$lastSeen = date("c", $userPostOwnerUserLastLogin);
            	$OnlineStatus = date("c", time());
                $oStatus = time() - 35;
                if ($userPostOwnerUserLastLogin > $oStatus) {
                  $timeStatus = '<div class="userIsOnline flex_ tabing">'.$LANG['online'].'</div>';
                } else {
                  $timeStatus = '<div class="userIsOffline flex_ tabing">'.$LANG['offline'].'</div>';
                }
				$userPostPinStatus = $postFromData['post_pined'];
				$slugUrl = $base_url . 'post/' . $postFromData['url_slug'] . '_' . $userPostID;
				$userPostSharedID = isset($postFromData['shared_post_id']) ? $postFromData['shared_post_id'] : NULL;
				$userPostOwnerUserAvatar = $iN->iN_UserAvatar($userPostOwnerID, $base_url);
				$userPostUserVerifiedStatus = $postFromData['user_verified_status'];
				$userProfileFrame = isset($postFromData['user_frame']) ? $postFromData['user_frame'] : NULL;
				if ($userPostOwnerUserGender == 'male') {
					$publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
				} else if ($userPostOwnerUserGender == 'female') {
					$publisherGender = '<div class="i_plus_gf">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
				} else if ($userPostOwnerUserGender == 'couple') {
					$publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
				}
				$userVerifiedStatus = '';
				if ($userPostUserVerifiedStatus == '1') {
					$userVerifiedStatus = '<div class="i_plus_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
				}
				$profileCategory = $pCt = $profileCategoryLink = '';
                if($userProfileCategory && $userPostUserVerifiedStatus == '1'){
                    $profileCategory = $userProfileCategory;
                    if(isset($PROFILE_CATEGORIES[$userProfileCategory])){
                        $pCt = isset($PROFILE_CATEGORIES[$userProfileCategory]) ? $PROFILE_CATEGORIES[$userProfileCategory] : NULL;
                    }else if(isset($PROFILE_SUBCATEGORIES[$userProfileCategory])){
                        $pCt = isset($PROFILE_SUBCATEGORIES[$userProfileCategory]) ? $PROFILE_SUBCATEGORIES[$userProfileCategory] : NULL;
                    }
                    $profileCategoryLink = '<a class="i_p_categoryp flex_ tabing_non_justify" href="'.$base_url.'creators?creator='.$userProfileCategory.'">'.$iN->iN_SelectedMenuIcon('65').$pCt.'</a>- ';
                }
				$onlySubs = '';
				$premiumPost = '';
                if($userPostWhoCanSee == '1'){
                   $onlySubs = '';
                   $premiumPost = '';
                   $subPostTop = '';
                   $wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('50').'</div>';
                }else if($userPostWhoCanSee == '2'){
                   $subPostTop = '';
                   $premiumPost = '';
                   $wCanSee = '<div class="i_plus_subs" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('15').'</div>';
                   $onlySubs = '<div class="com_min_height"></div><div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">'.$iN->iN_SelectedMenuIcon('15').'</div><div class="onlySubs_note">'.preg_replace( '/{.*?}/', $userPostOwnerUserFullName, $LANG['only_followers']).'</div></div></div>';
                }else if($userPostWhoCanSee == '3'){
                   $subPostTop = 'extensionPost';
                   $premiumPost = '<div class="premiumIcon flex_ justify-content-align-items-center">'.$iN->iN_SelectedMenuIcon('40').$LANG['l_premium'].'</div>';
                   $wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('51').'</div>';
                   $onlySubs = '<div class="com_min_height"></div><div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">'.$iN->iN_SelectedMenuIcon('56').'</div><div class="onlySubs_note">'.preg_replace( '/{.*?}/', $userPostOwnerUserFullName, $LANG['only_subscribers']).'</div><div class="fr_subs uSubsModal transition" data-u="'.$userPostOwnerID.'">'.$iN->iN_SelectedMenuIcon('51').$LANG['free_for_subscribers'].'</div></div></div>';
                }else if($userPostWhoCanSee == '4'){
                  $subPostTop = 'extensionPost';
                  $premiumPost = '<div class="premiumIcon flex_ justify-content-align-items-center">'.$iN->iN_SelectedMenuIcon('40').$LANG['l_premium'].'</div>';
                  $wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('9').'</div>';
                  $onlySubs = '<div class="com_min_height"></div><div class="onlyPremium onlyPremium"><div class="onlySubsWrapper"><div class="premium_locked"><div class="premium_locked_icon">'.$iN->iN_SelectedMenuIcon('56').'</div></div><div class="onlySubs_note"><div class="buyThisPost prcsPost" id="'.$userPostID.'">'.preg_replace( '/{.*?}/', $userPostWantedCredit, $LANG['post_credit']).'</div><div class="buythistext prcsPost" id="'.$userPostID.'">'.$LANG['purchase_post'].'</div></div><div class="fr_subs uSubsModal transition" data-u="'.$userPostOwnerID.'">'.$iN->iN_SelectedMenuIcon('51').$LANG['free_for_subscribers'].'</div></div></div>';
                }
				$postStyle = '';
				if (empty($userPostText)) {
					$postStyle = 'nonePoint';
				}
				/*Comment*/
				$getUserComments = $iN->iN_GetPostComments($userPostID, 0);
				$c = '';
				$TotallyPostComment = '';
				if ($c) {
					if ($getUserComments > 0) {
						$CountTheUniqComment = count($CountUniqPostCommentArray);
						$SecondUniqComment = $CountTheUniqComment - 5;
						if ($CountTheUniqComment > 5) {
							$getUserComments = $iN->iN_GetPostComments($userPostID, 5);
						}
					}
				}
				if ($logedIn == 0) {
					$getFriendStatusBetweenTwoUser = '1';
					$checkPostLikedBefore = '';
					$checkUserPurchasedThisPost = '0';
				} else {
					$getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $userPostOwnerID);
					$checkPostLikedBefore = $iN->iN_CheckPostLikedBefore($userID, $userPostID);
					$checkUserPurchasedThisPost = $iN->iN_CheckUserPurchasedThisPost($userID, $userPostID);
				}
				if ($checkPostLikedBefore) {
					$likeIcon = $iN->iN_SelectedMenuIcon('18');
					$likeClass = 'in_unlike';
				} else {
					$likeIcon = $iN->iN_SelectedMenuIcon('17');
					$likeClass = 'in_like';
				}
				if ($userPostCommentAvailableStatus == '1') {
					$commentStatusText = $LANG['disable_comment'];
				} else {
					$commentStatusText = $LANG['enable_comments'];
				}
				$pPinStatus = '';
				$pPinStatusBtn = $iN->iN_SelectedMenuIcon('29') . $LANG['pin_on_my_profile'];
				if ($userPostPinStatus == '1') {
					$pPinStatus = '<div class="i_pined_post" id="i_pined_post_' . $userPostID . '">' . $iN->iN_SelectedMenuIcon('62') . '</div>';
					$pPinStatusBtn = $iN->iN_SelectedMenuIcon('29') . $LANG['post_pined_on_your_profile'];
				}
				$pSaveStatusBtn = $iN->iN_SelectedMenuIcon('22');
				if ($iN->iN_CheckPostSavedBefore($userID, $userPostID) == '1') {
					$pSaveStatusBtn = $iN->iN_SelectedMenuIcon('63');
				}
				$likeSum = $iN->iN_TotalPostLiked($userPostID);
				if ($likeSum > '0') {
					$likeSum = $likeSum;
				} else {
					$likeSum = '';
				}
				$waitingApprove = '';
				if ($userPostStatus == '2') {
					$waitingApprove = '<div class="waiting_approve flex_">' . $iN->iN_SelectedMenuIcon('10') . $LANG['waiting_for_approve'] . '</div>';
					if ($logedIn == 0) {
						echo '<div class="i_post_body nonePoint body_' . $userPostID . '" id="' . $userPostID . '" data-last="' . $userPostID . '" ></div>';
					} else {
						if ($userID == $userPostOwnerID) {
							if (empty($userPostFile)) {
								include "../themes/$currentTheme/layouts/posts/textPost.php";
							} else {
								include "../themes/$currentTheme/layouts/posts/ImagePost.php";
							}
						} else {
							echo '<div class="i_post_body nonePoint body_' . $userPostID . '" id="' . $userPostID . '" data-last="' . $userPostID . '"></div>';
						}
					}
				} else {
					if (empty($userPostFile)) {
						include "../themes/$currentTheme/layouts/posts/textPost.php";
					} else {
						include "../themes/$currentTheme/layouts/posts/ImagePost.php";
					}
				}
			}
		} else {
			echo '15';
		}
	}
	if ($type == 'p_like') {
		if (isset($_POST['post'])) {
			$postID = $iN->iN_Secure($_POST['post']);
			$likePost = $iN->iN_LikePost($userID, $postID);
			$status = 'in_like';
			$pLike = $iN->iN_SelectedMenuIcon('17');
			if ($likePost) {
				$status = 'in_unlike';
				$pLike = $iN->iN_SelectedMenuIcon('18');
				if($iN->iN_CheckPostOwner($userID, $postID) === false && $ataNewPostLikePointSatus == 'yes' && str_replace(".", "",$iN->iN_TotalEarningPointsInaDay($userID)) < str_replace(".", "",$maximumPointInADay)){
					$iN->iN_InsertNewPostLikePoint($userID,$postID,$ataNewPostLikePointAmount);
				}
			}
			if($status == 'in_like'){
				if($iN->iN_CheckPostOwner($userID, $postID) === false && $ataNewPostLikePointSatus == 'yes'){
					$iN->iN_RemovePointPostLikeIfExist($userID,$postID,$ataNewPostLikePointAmount);
				}
			}
			$likeSum = $iN->iN_TotalPostLiked($postID);
			if ($likeSum == 0) {
				$likeSum = '';
			} else {
				$likeSum = $likeSum;
			}
			$data = array(
				'status' => $status,
				'like' => $pLike,
				'likeCount' => $likeSum,
			);
			$iN->iN_insertPostLikeNotification($userID, $postID);
			$result = json_encode($data);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			$GetPostOwnerIDFromPostDetails = $iN->iN_GetAllPostDetails($postID);
			$likedPostOwnerID = $GetPostOwnerIDFromPostDetails['post_owner_id'];
			$uData = $iN->iN_GetUserDetails($likedPostOwnerID);
			$sendEmail = isset($uData['i_user_email']) ? $uData['i_user_email'] : NULL;
			$lUsername = $uData['i_username'];
			$lUserFullName = $uData['i_user_fullname'];
			$emailNotificationStatus = $uData['email_notification_status'];
			$notQualifyDocument = $LANG['not_qualify_document'];
			$slugUrl = $base_url . 'post/' . $GetPostOwnerIDFromPostDetails['url_slug'] . '_' . $postID;
			if ($emailSendStatus == '1' && $userID != $likedPostOwnerID && $emailNotificationStatus == '1' && $status == 'in_unlike') {
				if ($smtpOrMail == 'mail') {
					$mail->IsMail();
				} else if ($smtpOrMail == 'smtp') {
					$mail->isSMTP();
					$mail->Host = $smtpHost; // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;
					$mail->SMTPKeepAlive = true;
					$mail->Username = $smtpUserName; // SMTP username
					$mail->Password = $smtpPassword; // SMTP password
					$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
					$mail->Port = $smtpPort;
					$mail->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true,
						),
					);
				} else {
					return false;
				}
				$instagramIcon = $iN->iN_SelectedMenuIcon('88');
				$facebookIcon = $iN->iN_SelectedMenuIcon('90');
				$twitterIcon = $iN->iN_SelectedMenuIcon('34');
				$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
				$someoneLikedYourPost = $iN->iN_Secure($LANG['someone_liked_yourpost']);
				$clickGoPost = $iN->iN_Secure($LANG['click_go_post']);
				$likedYourPost = $iN->iN_Secure($LANG['liked_your_post']);
				include_once '../includes/mailTemplates/postLikeEmailTemplate.php';
				$body = $bodyPostLikeEmail;
				$mail->setFrom($smtpEmail, $siteName);
				$send = false;
				$mail->IsHTML(true);
				$mail->addAddress($sendEmail, ''); // Add a recipient
				$mail->Subject = $iN->iN_Secure($LANG['someone_liked_yourpost']);
				$mail->CharSet = 'utf-8';
				$mail->Body    = $body;
				if (iN_safeMailSend($mail, $smtpOrMail, 'post_like_notification')) {
					$mail->ClearAddresses();
					return true;
				}
			}
		}
	}
	if ($type == 'p_share') {
		if (isset($_POST['sp'])) {
			$postID = $iN->iN_Secure($_POST['sp']);
			$checkPostIDExist = $iN->iN_CheckPostIDExist($postID);
			if ($checkPostIDExist == '1') {
				$postFromData = $iN->iN_GetAllPostDetails($postID);
				$userPostID = $postFromData['post_id'];
				$userPostOwnerID = $postFromData['post_owner_id'];
				$userPostText = isset($postFromData['post_text']) ? $postFromData['post_text'] : NULL;
				$userPostFile = $postFromData['post_file'];
				$userPostCreatedTime = $postFromData['post_created_time'];
				$crTime = date('Y-m-d H:i:s', $userPostCreatedTime);
				$userPostWhoCanSee = $postFromData['who_can_see'];
				$userPostWantStatus = $postFromData['post_want_status'];
				$userPostWantedCredit = $postFromData['post_wanted_credit'];
				$userPostStatus = $postFromData['post_status'];
				$userPostOwnerUsername = $postFromData['i_username'];
				$userPostOwnerUserFullName = $postFromData['i_user_fullname'];
				if($fullnameorusername == 'no'){
					$userPostOwnerUserFullName = $userPostOwnerUsername;
				}
				$userPostOwnerUserGender = $postFromData['user_gender'];
				$userPostCommentAvailableStatus = $postFromData['comment_status'];
				$userPostOwnerUserLastLogin = $postFromData['last_login_time'];
				$userPostHashTags = isset($postFromData['hashtags']) ? $postFromData['hashtags'] : NULL;
				$userPostSharedID = isset($postFromData['shared_post_id']) ? $postFromData['shared_post_id'] : NULL;
				$userPostOwnerUserAvatar = $iN->iN_UserAvatar($userPostOwnerID, $base_url);
				$userPostUserVerifiedStatus = $postFromData['user_verified_status'];
				if ($userPostOwnerUserGender == 'male') {
					$publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
				} else if ($userPostOwnerUserGender == 'female') {
					$publisherGender = '<div class="i_plus_gf">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
				} else if ($userPostOwnerUserGender == 'couple') {
					$publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
				}
				$userVerifiedStatus = '';
				if ($userPostUserVerifiedStatus == '1') {
					$userVerifiedStatus = '<div class="i_plus_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
				}
				$onlySubs = '';
				if($userPostWhoCanSee == '1'){
					$onlySubs = '';
					$subPostTop = '';
					$wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('50').'</div>';
				 }else if($userPostWhoCanSee == '2'){
					$subPostTop = '';
					$wCanSee = '<div class="i_plus_subs" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('15').'</div>';
					$onlySubs = '<div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">'.$iN->iN_SelectedMenuIcon('15').'</div><div class="onlySubs_note">'.preg_replace( '/{.*?}/', $userPostOwnerUserFullName, $LANG['only_followers']).'</div></div></div>';
				 }else if($userPostWhoCanSee == '3'){
					$subPostTop = 'extensionPost';
					$wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('51').'</div>';
					$onlySubs = '<div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">'.$iN->iN_SelectedMenuIcon('56').'</div><div class="onlySubs_note">'.preg_replace( '/{.*?}/', $userPostOwnerUserFullName, $LANG['only_subscribers']).'</div></div></div>';
				 }else if($userPostWhoCanSee == '4'){
				   $subPostTop = 'extensionPost';
				   $wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('9').'</div>';
				   $onlySubs = '<div class="onlyPremium"><div class="onlySubsWrapper"><div class="premium_locked"><div class="premium_locked_icon">'.$iN->iN_SelectedMenuIcon('56').'</div></div><div class="onlySubs_note"><div class="buyThisPost prcsPost" id="'.$userPostID.'">'.preg_replace( '/{.*?}/', $userPostWantedCredit, $LANG['post_credit']).'</div><div class="buythistext prcsPost" id="'.$userPostID.'">'.$LANG['purchase_post'].'</div></div><div class="fr_subs uSubsModal transition" data-u="'.$userPostOwnerID.'">'.$iN->iN_SelectedMenuIcon('51').$LANG['free_for_subscribers'].'</div></div></div>';
				 }
				$likeSum = $iN->iN_TotalPostLiked($userPostID);
				if ($likeSum > '0') {
					$likeSum = $likeSum;
				} else {
					$likeSum = '1';
				}
				$checkUserPurchasedThisPost = $iN->iN_CheckUserPurchasedThisPost($userID, $userPostID);
				/*Comment*/
				$getUserComments = $iN->iN_GetPostComments($userPostID, 0);
				$c = '';
				$TotallyPostComment = '';
				if ($c) {
					if ($getUserComments > 0) {
						$CountTheUniqComment = count($CountUniqPostCommentArray);
						$SecondUniqComment = $CountTheUniqComment - 5;
						if ($CountTheUniqComment > 5) {
							$getUserComments = $iN->iN_GetPostComments($userPostID, 5);
						}
					}
				}
				if ($logedIn == 0) {
					$getFriendStatusBetweenTwoUser = '1';
					$checkPostLikedBefore = '';
				} else {
					$getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $userPostOwnerID);
					$checkPostLikedBefore = $iN->iN_CheckPostLikedBefore($userID, $userPostID);
				}
				include "../themes/$currentTheme/layouts/posts/sharePost.php";
			} else {
				echo '404';
			}
		}
	}
	/*Insert Re-Share Post*/
	if ($type == 'p_rshare') {
		if (isset($_POST['sp']) && isset($_POST['pt'])) {
			$reSharePostID = $iN->iN_Secure($_POST['sp']);
			$reSharePostNewText = $iN->iN_Secure($_POST['pt']);
			$insertReShare = $iN->iN_ReShare_Post($userID, $reSharePostID, $iN->iN_Secure($reSharePostNewText));
			if ($insertReShare) {
				echo '200';
			} else {
				echo '404';
			}
		}
	}
	/*Show PopUps*/
	if ($type == 'ialert') {
		if (isset($_POST['al'])) {
			$alertType = $iN->iN_Secure($_POST['al']);
			include "../themes/$currentTheme/layouts/popup_alerts/popup_alerts.php";
		}
	}
	/*Show Who Can See Settings In PopUp*/
	if ($type == 'wcs') {
		if (isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$whoSee = $iN->iN_GetAllPostDetails($postID);
			if ($whoSee) {
				$whoCSee = $whoSee['who_can_see'];
				include "../themes/$currentTheme/layouts/posts/whoCanSee.php";
			}
		}
	}
	/*Show Who Can See Settings In PopUp*/
	if ($type == 'whcStory') {
		$checkUserIDExist = $iN->iN_CheckUserExist($userID);
		if ($checkUserIDExist) {
		    include "../themes/$currentTheme/layouts/popup_alerts/chooseWhichStory.php";
		}
	}
	/*Update Post Who Can See Status*/
	if ($type == 'uwcs') {
		if (isset($_POST['wci']) && in_array($_POST['wci'], $whoCanSeeArrays) && isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$WhoCS = $iN->iN_Secure($_POST['wci']);
			$updatePostWhoCanSeeStatus = $iN->iN_UpdatePostWhoCanSee($userID, $postID, $WhoCS);
			if ($updatePostWhoCanSeeStatus) {
				if ($WhoCS == 1) {
					$UpdatedWhoCanSee = $iN->iN_SelectedMenuIcon('50');
				} else if ($WhoCS == 2) {
					$UpdatedWhoCanSee = $iN->iN_SelectedMenuIcon('15');
				} else if ($WhoCS == 3) {
					$UpdatedWhoCanSee = $iN->iN_SelectedMenuIcon('51');
				}
				echo html_entity_decode($UpdatedWhoCanSee);
			} else {
				echo '404';
			}
		}
	}
	/*Show Edit Post In PopUp*/
	if ($type == 'c_editPost') {
		if (isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$getPData = $iN->iN_GetAllPostDetails($postID);
			if ($getPData) {
				$posText = isset($getPData['post_text']) ? $getPData['post_text'] : NULL;
				include "../themes/$currentTheme/layouts/posts/editPost.php";
			} else {
				echo '404';
			}
		}
	}
	/*Save Edited Post*/
	if ($type == 'editS') {
		if (isset($_POST['id']) && isset($_POST['text'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$editedText = $iN->iN_Secure($_POST['text']);
			$editedTextTwo = $iN->iN_Secure($_POST['text']);
			if (empty($editedText)) {
				$status = 'no';
				$data = array(
					'status' => $status,
					'text' => '',
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				exit();
			}
			$editSlug = $iN->url_slugies($editedText);
			$hashT = $iN->iN_hashtag($editedText);
			$saveEditedPost = $iN->iN_UpdatePost($userID, $postID, $editedTextTwo, $iN->url_Hash($editedText), $editSlug);
			if ($saveEditedPost) {
				$getNewPostFromData = $iN->iN_GetAllPostDetails($postID);
				$status = '200';
				$data = array(
					'status' => $status,
					'text' => $iN->sanitize_output($getNewPostFromData['post_text'], $base_url),
				);
				$result = json_encode($data, JSON_UNESCAPED_UNICODE);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			} else {
				$status = '404';
				$data = array(
					'status' => $status,
					'text' => '',
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				exit();
			}
		}
	}
	/*Delete Post Call AlertBox*/
	if ($type == 'ddelPost') {
		if (isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$alertType = $type;
			include "../themes/$currentTheme/layouts/popup_alerts/deleteAlert.php";
		}
	}
	/*Delete Post Call AlertBox*/
	if ($type == 'finishLiveStreaming') {
		include "../themes/$currentTheme/layouts/popup_alerts/closeLiveStreaming.php";
	}
	/*Delete Conversation Call AlertBox*/
	if ($type == 'ddelConv') {
		if (isset($_POST['id'])) {
			$conversationID = $iN->iN_Secure($_POST['id']);
			$alertType = $type;
			include "../themes/$currentTheme/layouts/popup_alerts/deleteConversationAlert.php";
		}
	}
	/*Delete Message Call AlertBox*/
	if ($type == 'ddelMesage') {
		if (isset($_POST['id'])) {
			$messageID = $iN->iN_Secure($_POST['id']);
			$alertType = $type;
			include "../themes/$currentTheme/layouts/popup_alerts/deleteMessageAlert.php";
		}
	}
	/*Delete Story From Database*/
	if($type == 'deleteStorie'){
       if(isset($_POST['id'])){
          $storieID = $iN->iN_Secure($_POST['id']);
		  $checkStorieIDExist = $iN->iN_CheckStorieIDExist($userID, $storieID);
		  if($checkStorieIDExist){
              $sData = $iN->iN_GetUploadedStoriesData($userID, $storieID);
			  $uploadedFileID = $sData['s_id'];
			  $uploadedFilePath = $sData['uploaded_file_path'];
			  $uploadedTumbnailFilePath = $sData['upload_tumbnail_file_path'];
			  $uploadedFilePathX = $sData['uploaded_x_file_path'];
			  $uploadedStoryType = $sData['story_type'];
              if($uploadedStoryType != 'textStory'){
                if ($uploadedFileID) {
                    if (storage_is_remote()) {
                        @storage_delete($uploadedFilePath);
                        @storage_delete($uploadedFilePathX);
                        @storage_delete($uploadedTumbnailFilePath);
                    } else {
                        @unlink('../' . $uploadedFilePath);
                        @unlink('../' . $uploadedFilePathX);
                        @unlink('../' . $uploadedTumbnailFilePath);
                    }
                    $affected = DB::exec("DELETE FROM i_user_stories WHERE s_id = ? AND uid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
                    echo $affected ? '200' : '404';
                } else {
                    $affected = DB::exec("DELETE FROM i_user_stories WHERE s_id = ? AND uid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
                    echo $affected ? '200' : '404';
                }
              }else{
                $affected = DB::exec("DELETE FROM i_user_stories WHERE s_id = ? AND uid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
                echo $affected ? '200' : '404';
              }

		  }
	   }
	}
	/*Delete Post From Database*/
	if ($type == 'deletePost') {
		if (isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
            if(!empty($postID)){
                $getPostFileIDs = $iN->iN_GetAllPostDetails($postID);
                $postFileIDs = isset($getPostFileIDs['post_file']) ? $getPostFileIDs['post_file'] : NULL;
                $trimValue = rtrim((string)$postFileIDs, ',');
                $explodeFiles = $trimValue !== '' ? explode(',', $trimValue) : [];
                $explodeFiles = array_unique($explodeFiles);
                foreach ($explodeFiles as $explodeFile) {
                    $theFileID = $iN->iN_GetUploadedFileDetails($explodeFile);
                    if($theFileID){
                        $uploadedFileID = $theFileID['upload_id'];
                        $uploadedFilePath = $theFileID['uploaded_file_path'];
                        $uploadedTumbnailFilePath = $theFileID['upload_tumbnail_file_path'];
                        $uploadedFilePathX = $theFileID['uploaded_x_file_path'];
                        if (storage_is_remote()) {
                            @storage_delete($uploadedFilePath);
                            @storage_delete($uploadedFilePathX);
                            @storage_delete($uploadedTumbnailFilePath);
                        } else {
                            @unlink('../' . $uploadedFilePath);
                            @unlink('../' . $uploadedFilePathX);
                            @unlink('../' . $uploadedTumbnailFilePath);
                        }
                        DB::exec("DELETE FROM i_user_uploads WHERE upload_id = ? AND iuid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
                    }
                }
                $deleteActivity = $iN->iN_DeletePostActivity($userID, $postID);
                $deleteStoragePost = $iN->iN_DeletePostFromDataifStorage($userID, $postID);
                if($deleteStoragePost){
                    if($ataNewPostPointSatus == 'yes'){$iN->iN_RemovePointIfExist($userID, $postID, $ataNewPostPointAmount);}
                    echo '200';
                    exit;
                }else{
                    echo '404';
                    exit;
                }
            }else if(!empty($postID)){
                $deletePostFromData = $iN->iN_DeletePost($userID, $postID);
                $deleteActivity = $iN->iN_DeletePostActivity($userID, $postID);
                if ($deletePostFromData) {
                    if($ataNewPostPointSatus == 'yes'){$iN->iN_RemovePointIfExist($userID, $postID, $ataNewPostPointAmount);}
                    echo '200';
                    exit;
                } else {
                    echo '404';
                    exit;
                }
            }
        }
	}
	/*Share My Storie*/
	if($type == 'shareMyStorie'){
      if(isset($_POST['id'])){
         $storieID = $iN->iN_Secure($_POST['id']);
		 $storieText = $iN->iN_Secure($_POST['txt']);
		 if($iN->iN_CheckStorieIDExist($userID, $storieID) == 1){
			$insertStorie = $iN->iN_InsertMyStorie($userID,$storieID, $iN->iN_Secure($storieText));
			if($insertStorie){
               echo '200';
			}else{
			   echo '404';
			}
		 }
	  }
	}
	/*Show More Posts*/
	if ($type == 'moreposts') {
		if (isset($_POST['last'])) {
			$page = $type;
			$files = array(
			1 => 'suggestedusers',
			2 => 'ads');
			shuffle($files);

			for ($i = 0; $i < 1; $i++) {
				include "../themes/$currentTheme/layouts/random_boxs/$files[$i].php";
			}
			include "../themes/$currentTheme/layouts/posts/htmlPosts.php";
		}
	}
	/*Show More Saved Posts*/
	if ($type == 'savedpost') {
		if (isset($_POST['last'])) {
			$page = $type;
			include "../themes/$currentTheme/layouts/posts/htmlPosts.php";
		}
	}
	/*Show More Profile Posts*/
	if ($type == 'profile') {
		if (isset($_POST['last']) && isset($_POST['p'])) {
			$p_profileID = $iN->iN_Secure($_POST['p']);
			$pCat = $iN->iN_Secure($_POST['pcat']);
			$page = $type;
			include "../themes/$currentTheme/layouts/posts/htmlPosts.php";
		}
	}
	/*Show More Profile Posts*/
	if ($type == 'hashtag') {
		if (isset($_POST['last']) && isset($_POST['p'])) {
			$pageFor = $iN->iN_Secure($_POST['p']);
			$page = $type;
			include "../themes/$currentTheme/layouts/posts/htmlPosts.php";
		}
	}
	/*Update Post Comment Status*/
	if ($type == 'updateComentStatus') {
		if (isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$updatePostCommentStatus = $iN->iN_UpdatePostCommentStatus($userID, $postID);
			if ($updatePostCommentStatus == '1') {
				$status = '200';
				$text = $iN->iN_SelectedMenuIcon('31') . $LANG['disable_comment'];
			} else {
				$status = '404';
				$text = $iN->iN_SelectedMenuIcon('31') . $LANG['enable_comments'];
			}
			$data = array(
				'status' => $status,
				'text' => $text,
			);
			$result = json_encode($data);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		}
	}
	/*Update Post Comment Status*/
	if ($type == 'pinpost') {
		if (isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$updatePostPinedStatus = $iN->iN_UpdatePostPinedStatus($userID, $postID);
			if ($updatePostPinedStatus == '1') {
				$status = '200';
				$text = '<div class="i_pined_post" id="i_pined_post_' . $postID . '">' . $iN->iN_SelectedMenuIcon('62') . '</div>';
				$btnText = $iN->iN_SelectedMenuIcon('29') . $LANG['post_pined_on_your_profile'];
			} else {
				$status = '404';
				$text = '';
				$btnText = $iN->iN_SelectedMenuIcon('29') . $LANG['pin_on_my_profile'];
			}
			$data = array(
				'status' => $status,
				'text' => $text,
				'btn' => $btnText,
			);
			$result = json_encode($data, JSON_UNESCAPED_UNICODE);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		}
	}
	/*Report Post*/
	if ($type == 'reportPost') {
		if (isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$insertPostReport = $iN->iN_InsertReportedPost($userID, $postID);
			if ($insertPostReport) {
				if ($insertPostReport == 'rep') {
					$status = '200';
					$text = $iN->iN_SelectedMenuIcon('32') . $LANG['unreport'];
				} else {
					$status = '404';
					$text = $iN->iN_SelectedMenuIcon('32') . $LANG['report_this_post'];
				}
			} else {
				$status = '';
				$text = '';
			}
			$data = array(
				'status' => $status,
				'text' => $text,
			);
			$result = json_encode($data, JSON_UNESCAPED_UNICODE);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		}
	}
	/*Save Post From Saved List*/
	if ($type == 'savePost') {
		if (isset($_POST['id'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$insertPostSave = $iN->iN_SavePostInSavedList($userID, $postID);
			if ($insertPostSave) {
				if ($insertPostSave == 'svp') {
					$status = '200';
					$text = $iN->iN_SelectedMenuIcon('63');
				} else {
					$status = '404';
					$text = $iN->iN_SelectedMenuIcon('22');
				}
			} else {
				$status = '';
				$text = '';
			}
			$data = array(
				'status' => $status,
				'text' => $text,
			);
			$result = json_encode($data, JSON_UNESCAPED_UNICODE);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		}
	}
	/*Insert a New Comment*/
	if ($type == 'comment') {
		if (isset($_POST['id']) && isset($_POST['val'])) {
			$postID = $iN->iN_Secure($_POST['id']);
			$value = $iN->iN_Secure($_POST['val']);
			$sticker = $iN->iN_Secure($_POST['sticker']);
			$Gif = $iN->iN_Secure($_POST['gf']);
			if (empty($value) && empty($sticker) && empty($Gif)) {
				$status = '404';
			} else {
				$insertNewComment = $iN->iN_insertNewComment($userID, $postID, $iN->iN_Secure($value), $iN->iN_Secure($sticker), $iN->iN_Secure($Gif));
				if ($insertNewComment) {
					$commentID = $insertNewComment['com_id'];
					$commentedUserID = $insertNewComment['comment_uid_fk'];
					$Usercomment = $insertNewComment['comment'];
					$commentTime = isset($insertNewComment['comment_time']) ? $insertNewComment['comment_time'] : NULL;
					$corTime = date('Y-m-d H:i:s', $commentTime);
					$commentFile = isset($insertNewComment['comment_file']) ? $insertNewComment['comment_file'] : NULL;
					$stickerUrl = isset($insertNewComment['sticker_url']) ? $insertNewComment['sticker_url'] : NULL;
					$gifUrl = isset($insertNewComment['gif_url']) ? $insertNewComment['gif_url'] : NULL;
					$commentedUserIDFk = isset($insertNewComment['iuid']) ? $insertNewComment['iuid'] : NULL;
					$commentedUserName = isset($insertNewComment['i_username']) ? $insertNewComment['i_username'] : NULL;
					$userPostID = $insertNewComment['comment_post_id_fk'];
					if($iN->iN_CheckPostOwner($userID, $postID) === false && $ataNewCommentPointSatus == 'yes' && str_replace(".", "",$iN->iN_TotalEarningPointsInaDay($userID)) < str_replace(".", "",$maximumPointInADay)){
						$iN->iN_InsertNewCommentPoint($userID,$userPostID,$ataNewCommentPointAmount);
					}
					$checkUserIsCreator = $iN->iN_CheckUserIsCreator($commentedUserID);
					$cUType = '';
					if($checkUserIsCreator){
                       $cUType = '<div class="i_plus_public" id="ipublic_'.$commentedUserID.'">'.$iN->iN_SelectedMenuIcon('9').'</div>';
					}
					$commentedUserFullName = isset($insertNewComment['i_user_fullname']) ? $insertNewComment['i_user_fullname'] : NULL;
					if($fullnameorusername == 'no'){
						$commentedUserFullName = $commentedUserName;
					}
					$commentedUserAvatar = $iN->iN_UserAvatar($commentedUserID, $base_url);
					$commentedUserGender = isset($insertNewComment['user_gender']) ? $insertNewComment['user_gender'] : NULL;
					if ($commentedUserGender == 'male') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					} else if ($commentedUserGender == 'female') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					} else if ($commentedUserGender == 'couple') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					}
					$commentedUserLastLogin = isset($insertNewComment['last_login_time']) ? $insertNewComment['last_login_time'] : NULL;
					$commentedUserVerifyStatus = isset($insertNewComment['user_verified_status']) ? $insertNewComment['user_verified_status'] : NULL;
					$cuserVerifiedStatus = '';
					if ($commentedUserVerifyStatus == '1') {
						$cuserVerifiedStatus = '<div class="i_plus_comment_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
					}
					$checkCommentLikedBefore = $iN->iN_CheckCommentLikedBefore($userID, $userPostID, $commentID);
					$commentLikeBtnClass = 'c_in_like';
					$commentLikeIcon = $iN->iN_SelectedMenuIcon('17');
					$commentReportStatus = $iN->iN_SelectedMenuIcon('32') . $LANG['report_comment'];
					if ($checkCommentLikedBefore == '1') {
						$commentLikeBtnClass = 'c_in_unlike';
						$commentLikeIcon = $iN->iN_SelectedMenuIcon('18');
						if ($checkCommentReportedBefore == '1') {
							$commentReportStatus = $iN->iN_SelectedMenuIcon('32') . $LANG['unreport'];
						}
					}
					$stickerComment = '';
					$gifComment = '';
					if ($stickerUrl) {
						$stickerComment = '<div class="comment_file"><img src="' . $stickerUrl . '"></div>';
					}
					if ($gifUrl) {
						$gifComment = '<div class="comment_gif_file"><img src="' . $gifUrl . '"></div>';
					}
					include "../themes/$currentTheme/layouts/posts/comments.php";
					$GetPostOwnerIDFromPostDetails = $iN->iN_GetAllPostDetails($userPostID);
					$commentedPostOwnerID = $GetPostOwnerIDFromPostDetails['post_owner_id'];
					if ($userID != $commentedPostOwnerID) {
						$iN->iN_InsertNotificationForCommented($commentedUserID, $userPostID);
					}
					if($Usercomment){
						$iN->iN_InsertMentionedUsersForComment($userID, $Usercomment, $userPostID, $commentedUserName,$commentedPostOwnerID);
					 }
					$uData = $iN->iN_GetUserDetails($commentedPostOwnerID);
					$sendEmail = isset($uData['i_user_email']) ? $uData['i_user_email'] : NULL;
					$emailNotificationStatus = $uData['email_notification_status'];
					$notQualifyDocument = $LANG['not_qualify_document'];
					if ($emailSendStatus == '1' && $userID != $commentedPostOwnerID && $emailNotificationStatus == '1') {
						if ($smtpOrMail == 'mail') {
							$mail->IsMail();
						} else if ($smtpOrMail == 'smtp') {
							$mail->isSMTP();
							$mail->Host = $smtpHost; // Specify main and backup SMTP servers
							$mail->SMTPAuth = true;
							$mail->SMTPKeepAlive = true;
							$mail->Username = $smtpUserName; // SMTP username
							$mail->Password = $smtpPassword; // SMTP password
							$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
							$mail->Port = $smtpPort;
							$mail->SMTPOptions = array(
								'ssl' => array(
									'verify_peer' => false,
									'verify_peer_name' => false,
									'allow_self_signed' => true,
								),
							);
						} else {
							return false;
						}
						$instagramIcon = $iN->iN_SelectedMenuIcon('88');
						$facebookIcon = $iN->iN_SelectedMenuIcon('90');
						$twitterIcon = $iN->iN_SelectedMenuIcon('34');
						$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
						$commentedBelow = $iN->iN_Secure($LANG['commented_below']);
						$commentE = $iN->iN_Secure($Usercomment);
						include_once '../includes/mailTemplates/commentEmailTemplate.php';
						$body = $bodyCommentEmail;
						$mail->setFrom($smtpUserName, $siteName);
						$send = false;
						$mail->IsHTML(true);
						$mail->addAddress($sendEmail); // Add a recipient
						$mail->Subject = $iN->iN_Secure($LANG['commented_on_your_post']);
			$mail->CharSet = 'utf-8';
			$mail->MsgHTML($body);
			if (iN_safeMailSend($mail, $smtpOrMail, 'comment_like_notification')) {
				$mail->ClearAddresses();
				return true;
			}
					}

				} else {
					echo '404';
				}
			}
		}
	}

	/*Comment Like*/
	if ($type == 'pc_like') {
		if (isset($_POST['post']) && isset($_POST['com'])) {
			$postID = $iN->iN_Secure($_POST['post']);
			$postCommentID = $iN->iN_Secure($_POST['com']);
			$likePostComment = $iN->iN_LikePostComment($userID, $postID, $postCommentID);
			$status = 'c_in_like';
			$pcLike = $iN->iN_SelectedMenuIcon('17');
			if ($likePostComment) {
				$status = 'c_in_unlike';
				$pcLike = $iN->iN_SelectedMenuIcon('18');
				$commentLikedSum = $iN->iN_TotalCommentLiked($postCommentID);
				if($iN->iN_CheckCommentOwner($userID, $postID) === false && $ataNewPostCommentLikePointSatus == 'yes' && str_replace(".", "",$iN->iN_TotalEarningPointsInaDay($userID)) < str_replace(".", "",$maximumPointInADay)){
					$iN->iN_InsertNewPostCommentLikePoint($userID,$postID,$ataNewPostCommentLikePointAmount);
				}
			}
			if($status == 'c_in_like'){
				if($iN->iN_CheckCommentOwner($userID, $postID) === false && $ataNewPostCommentLikePointSatus == 'yes'){
					$iN->iN_RemovePointPostCommentLikeIfExist($userID,$postID,$ataNewPostCommentLikePointAmount);
				}
			}
			$data = array(
				'status' => $status,
				'like' => $pcLike,
				'totalLike' => isset($commentLikedSum) ? $commentLikedSum : '0',
			);
			$result = json_encode($data);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			$cLData = $iN->iN_GetUserIDFromLikedPostID($postCommentID);
			$commendOwnerID = $cLData['comment_uid_fk'];
			if ($userID != $commendOwnerID) {
				$iN->iN_insertCommentLikeNotification($userID, $postID, $postCommentID);
			}
			$GetPostOwnerIDFromPostDetails = $iN->iN_GetAllPostDetails($postID);
			$uData = $iN->iN_GetUserDetails($commendOwnerID);
			$sendEmail = isset($uData['i_user_email']) ? $uData['i_user_email'] : NULL;
			$lUsername = $uData['i_username'];
			$lUserFullName = $uData['i_user_fullname'];
			$emailNotificationStatus = $uData['email_notification_status'];
			$notQualifyDocument = $LANG['not_qualify_document'];
			$slugUrl = $base_url . 'post/' . $GetPostOwnerIDFromPostDetails['url_slug'] . '_' . $postID;
			if ($emailSendStatus == '1' && $userID != $commendOwnerID && $emailNotificationStatus == '1' && $status == 'c_in_unlike') {
				if ($smtpOrMail == 'mail') {
					$mail->IsMail();
				} else if ($smtpOrMail == 'smtp') {
					$mail->isSMTP();
					$mail->Host = $smtpHost; // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;
					$mail->SMTPKeepAlive = true;
					$mail->Username = $smtpUserName; // SMTP username
					$mail->Password = $smtpPassword; // SMTP password
					$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
					$mail->Port = $smtpPort;
					$mail->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true,
						),
					);
				} else {
					return false;
				}
				$instagramIcon = $iN->iN_SelectedMenuIcon('88');
				$facebookIcon = $iN->iN_SelectedMenuIcon('90');
				$twitterIcon = $iN->iN_SelectedMenuIcon('34');
				$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
				$someoneLikedYourPost = $iN->iN_Secure($LANG['someone_liked_your_comment']);
				$clickGoPost = $iN->iN_Secure($LANG['click_go_comment']);
				$likedYourPost = $iN->iN_Secure($LANG['liked_your_comment']);
				include_once '../includes/mailTemplates/postLikeEmailTemplate.php';
				$body = $bodyPostLikeEmail;
				$mail->setFrom($smtpEmail, $siteName);
				$send = false;
				$mail->IsHTML(true);
				$mail->addAddress($sendEmail, ''); // Add a recipient
				$mail->Subject = $iN->iN_Secure($LANG['someone_liked_your_comment']);
				$mail->CharSet = 'utf-8';
				$mail->MsgHTML($body);
				if (iN_safeMailSend($mail, $smtpOrMail, 'comment_notification')) {
					$mail->ClearAddresses();
					return true;
				}
			}
		}
	}
	/*Delete Comment Call AlertBox*/
	if ($type == 'ddelComment') {
		if (isset($_POST['id']) && isset($_POST['pid'])) {
			$commentID = $iN->iN_Secure($_POST['id']);
			$postID = $iN->iN_Secure($_POST['pid']);
			$alertType = $type;
			include "../themes/$currentTheme/layouts/popup_alerts/deleteCommentAlert.php";
		}
	}
	/*Delete Comment*/
	if ($type == 'deletecomment') {
		if (isset($_POST['cid']) && isset($_POST['pid'])) {
			$commentID = $iN->iN_Secure($_POST['cid']);
			$postID = $iN->iN_Secure($_POST['pid']);
			$deleteComment = $iN->iN_DeleteComment($userID, $commentID, $postID);
                if ($deleteComment) {
                    if($ataNewCommentPointSatus == 'yes'){$iN->iN_RemovePointCommentIfExist($userID, $postID, $ataNewCommentPointAmount);}
                    echo '200';
                    exit;
                } else {
                    echo '404';
                    exit;
                }
            }
        }
	/*Report Comment*/
	if ($type == 'reportComment') {
		if (isset($_POST['id']) && isset($_POST['pid'])) {
			$commentID = $iN->iN_Secure($_POST['id']);
			$postID = $iN->iN_Secure($_POST['pid']);
			$insertCommentReport = $iN->iN_InsertReportedComment($userID, $commentID, $postID);
			if ($insertCommentReport) {
				if ($insertCommentReport == 'rep') {
					$status = '200';
					$text = $iN->iN_SelectedMenuIcon('32') . $LANG['unreport'];
				} else {
					$status = '404';
					$text = $iN->iN_SelectedMenuIcon('32') . $LANG['report_comment'];
				}
			} else {
				$status = '';
				$text = '';
			}
			$data = array(
				'status' => $status,
				'text' => $text,
			);
			$result = json_encode($data, JSON_UNESCAPED_UNICODE);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		}
	}
	/*Show Edit Comment In PopUp*/
	if ($type == 'c_editComment') {
		if (isset($_POST['cid']) && isset($_POST['pid'])) {
			$commentID = $iN->iN_Secure($_POST['cid']);
			$postID = $iN->iN_Secure($_POST['pid']);
			$getCData = $iN->iN_GetCommentFromID($userID, $commentID, $postID);
			if ($getCData) {
				$commentText = isset($getCData['comment']) ? $getCData['comment'] : NULL;
				include "../themes/$currentTheme/layouts/posts/editComment.php";
			} else {
				echo '404';
			}
		}
	}
	/*Save Edited Comment*/
	if ($type == 'editSC') {
		if (isset($_POST['cid']) && isset($_POST['pid']) && isset($_POST['text'])) {
			$commentID = $iN->iN_Secure($_POST['cid']);
			$postID = $iN->iN_Secure($_POST['pid']);
			$editedText = $iN->iN_Secure($_POST['text']);
			if (empty($editedText)) {
				$status = 'no';
				$data = array(
					'status' => $status,
					'text' => '',
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				exit();
			}
			$saveEditedComment = $iN->iN_UpdateComment($userID, $postID, $commentID, $iN->iN_Secure($editedText));
			if ($saveEditedComment) {
				$getNewPostFromData = $iN->iN_GetCommentFromID($userID, $commentID, $postID);
				$status = '200';
				$data = array(
					'status' => $status,
					'text' => $iN->sanitize_output($getNewPostFromData['comment'], $base_url),
				);
				$result = json_encode($data, JSON_UNESCAPED_UNICODE);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			} else {
				$status = '404';
				$data = array(
					'status' => $status,
					'text' => '',
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				exit();
			}
		}
	}
	/*Get Emojis*/
	if ($type == 'emoji') {
		if (isset($_POST['id'])) {
			$id = $iN->iN_Secure($_POST['id']);
			$ec = $iN->iN_Secure($_POST['ec']);
			$importID = '';
			if (!empty($ec)) {
				$importID = 'data-id="' . $ec . '"';
			}
			if ($id == 'emojiBox') {
				$importClass = 'emoji_item';
			} else if ($id == 'emojiBoxC') {
				$importClass = 'emoji_item_c';
			}
			include "../themes/$currentTheme/layouts/widgets/emojis.php";
		}
	}
	/*Get Stickers*/
	if ($type == 'stickers') {
		if (isset($_POST['id'])) {
			$id = $iN->iN_Secure($_POST['id']);
			include "../themes/$currentTheme/layouts/widgets/stickers.php";
		}
	}
	/*Get Gifs*/
	if ($type == 'gifList') {
		if (isset($_POST['id'])) {
			$id = $iN->iN_Secure($_POST['id']);
			include "../themes/$currentTheme/layouts/widgets/gifs.php";
		}
	}
	/*Add Sticker*/
	if ($type == 'addSticker') {
		if (isset($_POST['id'])) {
			$stickerID = $iN->iN_Secure($_POST['id']);
			$ID = $iN->iN_Secure($_POST['pi']);
			$getStickerUrlandID = $iN->iN_getSticker($stickerID);
			if ($getStickerUrlandID) {
				$data = array(
					'stickerUrl' => '<div class="in_sticker_wrapper" id="stick_id_' . $getStickerUrlandID['sticker_id'] . '"><img src="' . $getStickerUrlandID['sticker_url'] . '"></div><div class="removeSticker" id="' . $ID . '">' . $iN->iN_SelectedMenuIcon('5') . '</div>',
					'st_id' => $getStickerUrlandID['sticker_id'],
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			}
		}
	}
	/*Get Free Follow PopUP*/
	if ($type == 'follow_free_not') {
		if (isset($_POST['id'])) {
			$uID = $iN->iN_Secure($_POST['id']);
			$checkUserExist = $iN->iN_CheckUserExist($uID);
			if ($checkUserExist) {
				$userDetail = $iN->iN_GetUserDetails($uID);
				$f_userID = $userDetail['iuid'];
				$f_profileAvatar = $iN->iN_UserAvatar($f_userID, $base_url);
				$f_profileCover = $iN->iN_UserCover($f_userID, $base_url);
				$f_username = $userDetail['i_username'];
				$f_userfullname = $userDetail['i_user_fullname'];
				$f_userGender = $userDetail['user_gender'];
				$f_VerifyStatus = $userDetail['user_verified_status'];
				if ($f_userGender == 'male') {
					$fGender = '<div class="i_pr_m">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
				} else if ($f_userGender == 'female') {
					$fGender = '<div class="i_pr_fm">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
				} else if ($f_userGender == 'couple') {
					$fGender = '<div class="i_pr_co">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
				}
				$fVerifyStatus = '';
				if ($f_VerifyStatus == '1') {
					$fVerifyStatus = '<div class="i_pr_vs">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
				}
				$f_profileStatus = $userDetail['profile_status'];
				$f_is_creator = '';
				if ($f_profileStatus == '2') {
					$f_is_creator = '<div class="creator_badge">' . $iN->iN_SelectedMenuIcon('9') . '</div>';
				}
				$fprofileUrl = $base_url . $f_username;
				include "../themes/$currentTheme/layouts/popup_alerts/free_follow_popup.php";
			}
		}
	}
	/*Follow Profile Free*/
	if ($type == 'freeFollow') {
		if (isset($_POST['follow'])) {
			$uID = $iN->iN_Secure($_POST['follow']);
			$checkUserExist = $iN->iN_CheckUserExist($uID);
			if ($checkUserExist) {
				$checkUserFollowing = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $uID);
				if ($checkUserFollowing != 'me') {
					$insertNewFollowingList = $iN->iN_insertNewFollow($userID, $uID);
					if ($insertNewFollowingList == 'flw') {
						$status = '200';
						$not = $insertNewFollowingList;
						$btn = $iN->iN_SelectedMenuIcon('66') . $LANG['unfollow'];
						$iN->iN_InsertNotificationForFollow($userID, $uID);
					} else if ($insertNewFollowingList == 'unflw') {
						$status = '200';
						$not = $insertNewFollowingList;
						$btn = $iN->iN_SelectedMenuIcon('66') . $LANG['follow'];
						$iN->iN_RemoveNotificationForFollow($userID, $uID);
					} else {
						$status = '404';
						$not = '';
						$btn = '';
					}
					$data = array(
						'status' => $status,
						'text' => $not,
						'btn' => $btn,
					);
					$result = json_encode($data);
					echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
					$uData = $iN->iN_GetUserDetails($uID);
					$sendEmail = isset($uData['i_user_email']) ? $uData['i_user_email'] : NULL;
					$lUsername = $uData['i_username'];
					$fuserAvatar = $iN->iN_UserAvatar($uID, $base_url);
					$lUserFullName = $userFullName;
					$emailNotificationStatus = $uData['email_notification_status'];
					$notQualifyDocument = $LANG['not_qualify_document'];
					$slugUrl = $base_url . $lUsername;
					if ($emailSendStatus == '1' && $emailNotificationStatus == '1' && $insertNewFollowingList == 'flw') {
						if ($smtpOrMail == 'mail') {
							$mail->IsMail();
						} else if ($smtpOrMail == 'smtp') {
							$mail->isSMTP();
							$mail->Host = $smtpHost; // Specify main and backup SMTP servers
							$mail->SMTPAuth = true;
							$mail->SMTPKeepAlive = true;
							$mail->Username = $smtpUserName; // SMTP username
							$mail->Password = $smtpPassword; // SMTP password
							$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
							$mail->Port = $smtpPort;
							$mail->SMTPOptions = array(
								'ssl' => array(
									'verify_peer' => false,
									'verify_peer_name' => false,
									'allow_self_signed' => true,
								),
							);
						} else {
							return false;
						}
						$instagramIcon = $iN->iN_SelectedMenuIcon('88');
						$facebookIcon = $iN->iN_SelectedMenuIcon('90');
						$twitterIcon = $iN->iN_SelectedMenuIcon('34');
						$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
						$startedFollow = $iN->iN_Secure($LANG['now_following_your_profile']);
						include_once '../includes/mailTemplates/userFollowingEmailTemplate.php';
						$body = $bodyUserFollowEmailTemplate;
						$mail->setFrom($smtpEmail, $siteName);
						$send = false;
						$mail->IsHTML(true);
						$mail->addAddress($sendEmail, ''); // Add a recipient
						$mail->Subject = $iN->iN_Secure($LANG['now_following_your_profile']);
						$mail->CharSet = 'utf-8';
						$mail->MsgHTML($body);
						if (iN_safeMailSend($mail, $smtpOrMail, 'follow_notification')) {
							$mail->ClearAddresses();
							return true;
						}
					}
				}
			}
		}
	}
	/*Block User PopUp Call*/
	if ($type == 'uBlockNotice') {
		if (isset($_POST['id'])) {
			$iuID = $iN->iN_Secure($_POST['id']);
			$checkUserExist = $iN->iN_CheckUserExist($iuID);
			if ($checkUserExist) {
				$userDetail = $iN->iN_GetUserDetails($iuID);
				$f_userfullname = $userDetail['i_user_fullname'];
				include "../themes/$currentTheme/layouts/popup_alerts/userBlockAlert.php";
			}
		}
	}
	/*Block User*/
	if ($type == 'ublock') {
		if (isset($_POST['id']) && in_array($_POST['blckt'], $blockType)) {
			$uID = $iN->iN_Secure($_POST['id']);
			$uBlockType = $iN->iN_Secure($_POST['blckt']);
			$checkUserExist = $iN->iN_CheckUserExist($uID);
			if ($checkUserExist) {
				if ($uID != $userID) {
					$friendsStatus = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $uID);
					$friendsStatusTwo = $iN->iN_GetRelationsipBetweenTwoUsers($uID, $userID);
					$addBlockList = $iN->iN_InsertBlockList($userID, $uID, $uBlockType);
					if ($addBlockList == 'bAdded') {
						$status = '200';
						$redirect = $base_url . 'settings?tab=blocked';
					} else if ($addBlockList == 'bRemoved') {
						$status = '200';
						$redirect = $base_url . 'settings?tab=blocked';
					} else {
						$status = '404';
						$redirect = '';
					}
					if ($addBlockList == 'bAdded' && $uBlockType == '2') {
						if ($friendsStatus == 'subscriber') {
							\Stripe\Stripe::setApiKey($stripeKey);
							$getSubsData = $iN->iN_GetSubscribeID($userID, $uID);
							$paymentSubscriptionID = $getSubsData['payment_subscription_id'];
							$subscriptionID = $getSubsData['subscription_id'];
							$iN->iN_UpdateSubscriptionStatus($subscriptionID);
							$subscription = \Stripe\Subscription::retrieve($paymentSubscriptionID);
							$subscription->cancel();
							$iN->iN_UnSubscriberUser($userID, $uID);
						} else if ($friendsStatus == 'flwr') {
							$iN->iN_insertNewFollow($userID, $uID);
						}
						if ($friendsStatusTwo == 'subscriber') {
							\Stripe\Stripe::setApiKey($stripeKey);
							$getSubsData = $iN->iN_GetSubscribeID($uID, $userID);
							$paymentSubscriptionID = $getSubsData['payment_subscription_id'];
							$subscriptionID = $getSubsData['subscription_id'];
							$iN->iN_UpdateSubscriptionStatus($subscriptionID);
							$subscription = \Stripe\Subscription::retrieve($paymentSubscriptionID);
							$subscription->cancel();
							$iN->iN_UnSubscriberUser($uID, $userID);
						} else if ($friendsStatusTwo == 'flwr') {
							$iN->iN_insertNewFollow($uID, $userID);
						}
					}
					$data = array(
						'status' => $status,
						'redirect' => $redirect,
					);
					$result = json_encode($data);
					echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				}
			}
		}
	}
	/*Subscribe Modal with Methods*/
	if ($type == 'subsModal') {
		if (isset($_POST['id'])) {
			$iuID = $iN->iN_Secure($_POST['id']);
			$checkUserExist = $iN->iN_CheckUserExist($iuID);
			$p_friend_status = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $iuID);
			if ($checkUserExist && $p_friend_status != 'subscriber') {
				$userDetail = $iN->iN_GetUserDetails($iuID);
				$f_userID = $userDetail['iuid'];
				$f_profileAvatar = $iN->iN_UserAvatar($f_userID, $base_url);
				$f_profileCover = $iN->iN_UserCover($f_userID, $base_url);
				$f_username = $userDetail['i_username'];
				$f_userfullname = $userDetail['i_user_fullname'];
				$f_userGender = $userDetail['user_gender'];
				$f_VerifyStatus = $userDetail['user_verified_status'];
				if ($f_userGender == 'male') {
					$fGender = '<div class="i_pr_m">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
				} else if ($f_userGender == 'female') {
					$fGender = '<div class="i_pr_fm">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
				} else if ($f_userGender == 'couple') {
					$fGender = '<div class="i_pr_co">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
				}
				$fVerifyStatus = '';
				if ($f_VerifyStatus == '1') {
					$fVerifyStatus = '<div class="i_pr_vs">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
				}
				$f_profileStatus = $userDetail['profile_status'];
				$f_is_creator = '';
				if ($f_profileStatus == '2') {
					$f_is_creator = '<div class="creator_badge">' . $iN->iN_SelectedMenuIcon('9') . '</div>';
				}
				$fprofileUrl = $base_url . $f_username;
				if($subscriptionType == '2'){
					include "../themes/$currentTheme/layouts/popup_alerts/becomeSubscriberWithPoint.php";
				}else if($subscriptionType == '1' || $subscriptionType == '3'){
					include "../themes/$currentTheme/layouts/popup_alerts/becomeSubscriber.php";
				}
			}
		}
	}
	/*Credit Card popUp*/
	if ($type == 'creditCard') {
		if (isset($_POST['plan']) && isset($_POST['id'])) {
			$planID = $iN->iN_Secure($_POST['plan']);
			$iuID = $iN->iN_Secure($_POST['id']);
			$checkPlanExist = $iN->iN_CheckPlanExist($planID, $iuID);
			if ($checkPlanExist) {
				$userDetail = $iN->iN_GetUserDetails($iuID);
				$f_userID = $userDetail['iuid'];
				$f_PlanAmount = $checkPlanExist['amount'];
				$f_profileAvatar = $iN->iN_UserAvatar($f_userID, $base_url);
				$f_profileCover = $iN->iN_UserCover($f_userID, $base_url);
				$f_username = $userDetail['i_username'];
				$f_userfullname = $userDetail['i_user_fullname'];
				$f_userGender = $userDetail['user_gender'];
				$f_VerifyStatus = $userDetail['user_verified_status'];
				if ($f_userGender == 'male') {
					$fGender = '<div class="i_pr_m">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
				} else if ($f_userGender == 'female') {
					$fGender = '<div class="i_pr_fm">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
				} else if ($f_userGender == 'couple') {
					$fGender = '<div class="i_pr_co">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
				}
				$fVerifyStatus = '';
				if ($f_VerifyStatus == '1') {
					$fVerifyStatus = '<div class="i_pr_vs">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
				}
				$f_profileStatus = $userDetail['profile_status'];
				$f_is_creator = '';
				if ($f_profileStatus == '2') {
					$f_is_creator = '<div class="creator_badge">' . $iN->iN_SelectedMenuIcon('9') . '</div>';
				}
				$fprofileUrl = $base_url . $f_username;
				include "../themes/$currentTheme/layouts/popup_alerts/payWithCreditCard.php";
			}
		}
	}
	/*Credit Card popUp*/
	if ($type == 'creditPoint') {
		if (isset($_POST['plan']) && isset($_POST['id'])) {
			$planID = $iN->iN_Secure($_POST['plan']);
			$iuID = $iN->iN_Secure($_POST['id']);
			$checkPlanExist = $iN->iN_CheckPlanExist($planID, $iuID);
			if ($checkPlanExist) {
				$userDetail = $iN->iN_GetUserDetails($iuID);
				$planType = $checkPlanExist['plan_type'];
				$f_userID = $userDetail['iuid'];
				$f_PlanAmount = $checkPlanExist['amount'];
				$f_profileAvatar = $iN->iN_UserAvatar($f_userID, $base_url);
				$f_profileCover = $iN->iN_UserCover($f_userID, $base_url);
				$f_username = $userDetail['i_username'];
				$f_userfullname = $userDetail['i_user_fullname'];
				$f_userGender = $userDetail['user_gender'];
				$f_VerifyStatus = $userDetail['user_verified_status'];
				if ($f_userGender == 'male') {
					$fGender = '<div class="i_pr_m">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
				} else if ($f_userGender == 'female') {
					$fGender = '<div class="i_pr_fm">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
				} else if ($f_userGender == 'couple') {
					$fGender = '<div class="i_pr_co">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
				}
				$fVerifyStatus = '';
				if ($f_VerifyStatus == '1') {
					$fVerifyStatus = '<div class="i_pr_vs">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
				}
				$f_profileStatus = $userDetail['profile_status'];
				$f_is_creator = '';
				if ($f_profileStatus == '2') {
					$f_is_creator = '<div class="creator_badge">' . $iN->iN_SelectedMenuIcon('9') . '</div>';
				}
				$fprofileUrl = $base_url . $f_username;

				include "../themes/$currentTheme/layouts/popup_alerts/payWithPoint.php";
			}
		}
	}
	/*Subscribe User (SEND STRIPE AND SAVE DATA)*/
	if ($type == 'subscribeMe') {
		if (isset($_POST['u']) && isset($_POST['pl']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['t'])) {
			$iuID = $iN->iN_Secure($_POST['u']);
			$planID = $iN->iN_Secure($_POST['pl']);
			$subscriberName = $iN->iN_Secure($_POST['name']);
			$subscriberEmail = $iN->iN_Secure($_POST['email']);
			$stripeTokenID = $iN->iN_Secure($_POST['t']);
			$planDetails = $iN->iN_CheckPlanExist($planID, $iuID);
			$payment_id = $statusMsg = $api_error = '';
			$checkAlreadySubscribed = $iN->iN_CheckUserIsInSubscriber($userID, $iuID);
			$p_friend_status = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $iuID);
			if($p_friend_status == 'subscriber'){
               exit($LANG['already_subscribed']);
			}
			if ($planDetails && $p_friend_status != 'subscriber') {
				$planType = $planDetails['plan_type'];
				$amount = $planDetails['amount'];
				$payment_Type = 'stripe';
				if ($planType == 'weekly') {
					$planName = 'Weekly Subscription';
					$planInterval = 'week';
				} else if ($planType == 'monthly') {
					$planName = 'Monthly Subscription';
					$planInterval = 'month';
				} else if ($planType == 'yearly') {
					$planName = 'Yearly Subscription';
					$planInterval = 'year';
				}
				if (empty($stripeTokenID) || $stripeTokenID == '' || !isset($stripeTokenID) || $stripeTokenID == 'undefined') {
					exit($LANG['fill_all_credit_card_details']);
				}
				// Set API key
				\Stripe\Stripe::setApiKey($stripeKey);
				// Add customer to stripe
				try {
					$customer = \Stripe\Customer::create(array(
						'email' => $subscriberEmail,
						'source' => $stripeTokenID,
					));
				} catch (Exception $e) {
					$api_error = $e->getMessage();
				}
				/******/
				if (empty($api_error) && $customer) {
					// Convert price to cents
					$priceCents = round($amount * 100);

					// Create a plan
					try {
						$plan = \Stripe\Plan::create(array(
							"product" => [
								"name" => $planName,
							],
							"amount" => $priceCents,
							"currency" => $stripeCurrency,
							"interval" => $planInterval,
							"interval_count" => 1,
						));
					} catch (Exception $e) {
						$api_error = $e->getMessage();
					}

					if (empty($api_error) && $plan) {

						// Creates a new subscription
						try {
							$subscription = \Stripe\Subscription::create(array(
								"customer" => $customer->id,
								"items" => array(
									array(
										"plan" => $plan->id,
									),
								),
							));
						} catch (Exception $e) {
							$api_error = $e->getMessage();
						}
						if (empty($api_error) && $subscription) {
							// Retrieve subscription data
							$subsData = $subscription->jsonSerialize();
							// Check whether the subscription activation is successful
							if ($subsData['status'] == 'active') {
								// Subscription info
								$subscrID = $subsData['id'];
								$custID = $subsData['customer'];
								$planIDs = $subsData['plan']['id'];
								$planAmount = ($subsData['plan']['amount'] / 100);
								$planCurrency = $subsData['plan']['currency'];
								$planinterval = $subsData['plan']['interval'];
								$planIntervalCount = $subsData['plan']['interval_count'];
								$plancreated = date("Y-m-d H:i:s", $subsData['created']);
								$current_period_start = date("Y-m-d H:i:s", $subsData['current_period_start']);
								$current_period_end = date("Y-m-d H:i:s", $subsData['current_period_end']);
								$planStatus = $subsData['status'];
								$adminEarning = ($adminFee * $planAmount) / 100;
								$userNetEarning = $planAmount - $adminEarning;
								$insertSubscription = $iN->iN_InsertUserSubscription($userID, $iuID, $payment_Type, $subscriberName, $subscrID, $custID, $planIDs, $planAmount, $adminEarning, $userNetEarning, $planCurrency, $planinterval, $planIntervalCount, $subscriberEmail, $plancreated, $current_period_start, $current_period_end, $planStatus);
								if ($insertSubscription) {
									echo '200';
									$uData = $iN->iN_GetUserDetails($iuID);
									$sendEmail = isset($uData['i_user_email']) ? $uData['i_user_email'] : NULL;
									$lUsername = $uData['i_username'];
									$iN->iN_InsertNotificationForSubscribe($userID, $iuID);
									$fuserAvatar = $iN->iN_UserAvatar($iuID, $base_url);
									$lUserFullName = $uData['i_user_fullname'];
									$emailNotificationStatus = $uData['email_notification_status'];
									$morePostForSubscriber = $LANG['share_something_for_subscriber'];
									$slugUrl = $base_url . $lUsername;
									$gotNewSubscriber = $LANG['got_new_subscriber'];
									if ($emailSendStatus == '1' && $emailNotificationStatus == '1') {

										if ($smtpOrMail == 'mail') {
											$mail->IsMail();
										} else if ($smtpOrMail == 'smtp') {
											$mail->isSMTP();
											$mail->Host = $smtpHost; // Specify main and backup SMTP servers
											$mail->SMTPAuth = true;
											$mail->SMTPKeepAlive = true;
											$mail->Username = $smtpUserName; // SMTP username
											$mail->Password = $smtpPassword; // SMTP password
											$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
											$mail->Port = $smtpPort;
											$mail->SMTPOptions = array(
												'ssl' => array(
													'verify_peer' => false,
													'verify_peer_name' => false,
													'allow_self_signed' => true,
												),
											);
										} else {
											return false;
										}
										$instagramIcon = $iN->iN_SelectedMenuIcon('88');
										$facebookIcon = $iN->iN_SelectedMenuIcon('90');
										$twitterIcon = $iN->iN_SelectedMenuIcon('34');
										$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
										$startedFollow = $iN->iN_Secure($LANG['now_following_your_profile']);
										include_once '../includes/mailTemplates/newSubscriberEmailTemplate.php';
										$body = $bodyNewSubscriberEmailTemplate;
										$mail->setFrom($smtpEmail, $siteName);
										$send = false;
										$mail->IsHTML(true);
										$mail->addAddress($sendEmail, ''); // Add a recipient
										$mail->Subject = $iN->iN_Secure($LANG['now_following_your_profile']);
				$mail->CharSet = 'utf-8';
				$mail->MsgHTML($body);
				if (iN_safeMailSend($mail, $smtpOrMail, 'post_purchase_notification')) {
					$mail->ClearAddresses();
					return true;
				}

									}
								} else if (false) {
									echo iN_HelpSecure($LANG['contact_site_administrator']);
								}
							} else {
								echo iN_HelpSecure($LANG['subscription_activation_failed']);
							}
						} else {
							echo iN_HelpSecure($LANG['subscription_creation_failed']) . $api_error;
						}
					} else {
						echo iN_HelpSecure($LANG['plan_creation_failed']) . $api_error;
					}
				} else {
					echo iN_HelpSecure($LANG['invalid_card_details']) . $api_error;
				}
				/******/
			}
		}
	}
	/*Subscribe User (SUBSCRIBE WITH UPLOADED POINTS)*/
	if($type == 'subWithPoints'){
        if(isset($_POST['pl']) && $_POST['pl'] != '' && !empty($_POST['pl']) && isset($_POST['id']) && $_POST['id'] != '' && !empty($_POST['id'])){
			$planID = $iN->iN_Secure($_POST['pl']);
			$iuID = $iN->iN_Secure($_POST['id']);
			$checkPlanExist = $iN->iN_CheckPlanExist($planID, $iuID);
			$planType = isset($checkPlanExist['plan_type']) ? $checkPlanExist['plan_type'] : NULL;
			$planAmount = isset($checkPlanExist['amount']) ? $checkPlanExist['amount'] : NULL;
			if($checkPlanExist && ($userCurrentPoints >= $planAmount)){
				$payment_Type = 'point';
				$adminEarning = $adminFee * $planAmount * $onePointEqual / 100;
				$userNetEarning = $planAmount * $onePointEqual - $adminEarning;
				$planIntervalCount = '1';
				if ($planType == 'weekly') {
					$planName = 'Weekly Subscription';
					$planInterval = 'week';
					$thisTime = strtotime('+7 day', time());
					$plancreated = date("Y-m-d H:i:s");
					$current_period_start = date("Y-m-d H:i:s", $thisTime);
				    $current_period_end = date("Y-m-d H:i:s", $thisTime);
				} else if ($planType == 'monthly') {
					$planName = 'Monthly Subscription';
					$planInterval = 'month';
					$plancreated = date("Y-m-d H:i:s");
					$current_period_start = date("Y-m-d H:i:s", strtotime('+1 month', time()));
				    $current_period_end = date("Y-m-d H:i:s", strtotime('+1 month', time()));
				} else if ($planType == 'yearly') {
					$planName = 'Yearly Subscription';
					$planInterval = 'year';
					$plancreated = date("Y-m-d H:i:s");
					$current_period_start = date("Y-m-d H:i:s", strtotime('+1 month', time()));
				    $current_period_end = date("Y-m-d H:i:s", strtotime('+1 year', time()));
				}
				$uDetails = $iN->iN_GetUserDetails($iuID);
				$subscriberName = $iN->iN_Secure($uDetails['i_user_fullname']);
			    $subscriberEmail = $iN->iN_Secure($uDetails['i_user_email']);
				$UpdateCurrentPoint = $userCurrentPoints - $planAmount;
				$planCurrency = $defaultCurrency;
				$planStatus = 'active';
				$insertSubscription = $iN->iN_InsertUserSubscriptionWithPoint($userID, $iuID, $payment_Type, $subscriberName, $planAmount, $adminEarning, $userNetEarning, $planCurrency, $planInterval, $planIntervalCount, $subscriberEmail, $plancreated, $current_period_start, $current_period_end, $planStatus,$UpdateCurrentPoint);
			    if ($insertSubscription) {
					echo '200';
					$uData = $iN->iN_GetUserDetails($iuID);
					$sendEmail = isset($uData['i_user_email']) ? $uData['i_user_email'] : NULL;
					$lUsername = $uData['i_username'];
					$iN->iN_InsertNotificationForSubscribe($userID, $iuID);
					$fuserAvatar = $iN->iN_UserAvatar($iuID, $base_url);
					$lUserFullName = $uData['i_user_fullname'];
					$emailNotificationStatus = $uData['email_notification_status'];
					$morePostForSubscriber = $LANG['share_something_for_subscriber'];
					$slugUrl = $base_url . $lUsername;
					$gotNewSubscriber = $LANG['got_new_subscriber'];
					if ($emailSendStatus == '1' && $emailNotificationStatus == '1') {
						if ($smtpOrMail == 'mail') {
							$mail->IsMail();
						} else if ($smtpOrMail == 'smtp') {
							$mail->isSMTP();
							$mail->Host = $smtpHost; // Specify main and backup SMTP servers
							$mail->SMTPAuth = true;
							$mail->SMTPKeepAlive = true;
							$mail->Username = $smtpUserName; // SMTP username
							$mail->Password = $smtpPassword; // SMTP password
							$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
							$mail->Port = $smtpPort;
							$mail->SMTPOptions = array(
								'ssl' => array(
									'verify_peer' => false,
									'verify_peer_name' => false,
									'allow_self_signed' => true,
								),
							);
						} else {
							return false;
						}
						$instagramIcon = $iN->iN_SelectedMenuIcon('88');
						$facebookIcon = $iN->iN_SelectedMenuIcon('90');
						$twitterIcon = $iN->iN_SelectedMenuIcon('34');
						$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
						$startedFollow = $iN->iN_Secure($LANG['now_following_your_profile']);
						include_once '../includes/mailTemplates/newSubscriberEmailTemplate.php';
						$body = $bodyNewSubscriberEmailTemplate;
						$mail->setFrom($smtpEmail, $siteName);
						$send = false;
						$mail->IsHTML(true);
						$mail->addAddress($sendEmail, ''); // Add a recipient
						$mail->Subject = $iN->iN_Secure($LANG['now_following_your_profile']);
						$mail->CharSet = 'utf-8';
						$mail->MsgHTML($body);
						if (iN_safeMailSend($mail, $smtpOrMail, 'new_subscriber_notification')) {
							$mail->ClearAddresses();
							return true;
						}
					}
				}else{
					exit('404');
				}
			} else{
				exit('302');
			}
		}
	}

	if ($type == 'uploadVerificationFiles') {
		//$availableFileExtensions
		if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == 'POST') {
			// Unified stories uploader (mirrors 'upload' flow) and exits early
			if (!empty($_FILES['storieimg']['name'])) {
				foreach ($_FILES['storieimg']['name'] as $iname => $value) {
					$name = stripslashes($_FILES['storieimg']['name'][$iname]);
					$size = $_FILES['storieimg']['size'][$iname];
					$ext = strtolower(getExtension($name));
					$valid_formats = explode(',', $availableFileExtensions);
					if (!in_array($ext, $valid_formats)) { echo iN_HelpSecure($LANG['invalid_file_format']); continue; }
					if (convert_to_mb($size) >= $availableUploadFileSize) { echo iN_HelpSecure($size); continue; }

					$microtime = microtime();
					$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
					$UploadedFileName = 'image_' . $removeMicrotime . '_' . $userID;
					$getFilename = $UploadedFileName . '.' . $ext;
					$tmp = $_FILES['storieimg']['tmp_name'][$iname];
					$mimeType = $_FILES['storieimg']['type'][$iname];
					$d = date('Y-m-d');

					// Determine type (stories allow image or video)
					if (preg_match('/video\/*/', $mimeType) || $mimeType === 'application/octet-stream') { $fileTypeIs = 'video'; }
					else if (preg_match('/image\/*/', $mimeType)) { $fileTypeIs = 'Image'; }
					else { echo iN_HelpSecure($LANG['invalid_file_format']); continue; }

					// Ensure directories
					if (!file_exists($uploadFile . $d)) { @mkdir($uploadFile . $d, 0755, true); }
					if (!file_exists($xImages . $d)) { @mkdir($xImages . $d, 0755, true); }
					if (!file_exists($xVideos . $d)) { @mkdir($xVideos . $d, 0755, true); }
					$wVideos = rtrim(UPLOAD_DIR_VIDEOS, '/') . '/';
					if (!file_exists($wVideos . $d)) { @mkdir($wVideos . $d, 0755, true); }

					if ($fileTypeIs === 'video' && $ffmpegStatus == '0' && !in_array($ext, $nonFfmpegAvailableVideoFormat)) { exit('303'); }
					if (!move_uploaded_file($tmp, $uploadFile . $d . '/' . $getFilename)) { echo $LANG['upload_failed']; continue; }

					$pathFile = '';
					$pathXFile = '';
					$tumbnailPath = '';
					$UploadSourceUrl = '';
                    if ($fileTypeIs === 'video') {
                        if ($ffmpegStatus == '1') {
                            require_once '../includes/convertToMp4Format.php';
                            require_once '../includes/createVideoThumbnail.php';

                            // Resolve ffmpeg binary path if not explicitly configured
                            $ffmpegBin = !empty($ffmpegPath) ? $ffmpegPath : '';
                            if ($ffmpegBin === '' && function_exists('shell_exec')) {
                                $ffmpegBin = trim(@shell_exec('command -v ffmpeg 2>/dev/null || which ffmpeg 2>/dev/null'));
                            }
                            if ($ffmpegBin === '') { $ffmpegBin = 'ffmpeg'; }

                            $sourceFs = $uploadFile . $d . '/' . $getFilename;
                            $convertedFs = convertToMp4Format($ffmpegBin, $sourceFs, $uploadFile . $d, $UploadedFileName);
                            if (!$convertedFs || !file_exists($convertedFs)) { $convertedFs = $sourceFs; }

							// 4-second preview and poster
							if (!file_exists('../uploads/xvideos/' . $d)) { @mkdir('../uploads/xvideos/' . $d, 0755, true); }
                            $xVideoFirstPath = '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                            $videoTumbnailFs = createVideoThumbnailInSameDir($ffmpegBin, $convertedFs);
                            $safeClip = $ffmpegBin . ' -ss 00:00:01 -i ' . escapeshellarg($convertedFs) . ' -c copy -t 00:00:04 ' . escapeshellarg($xVideoFirstPath) . ' 2>&1';
                            shell_exec($safeClip);

							$pathFile = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
							$pathXFile = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
							$tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
							$thePathM = '../' . $tumbnailPath;
							if (file_exists($thePathM) && ($watermarkStatus == 'yes' || $LinkWatermarkStatus == 'yes')) {
								watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url . $userName);
							}

							// Publish keys and choose URL
							$publishKeys = [];
							$mp4Key = $pathFile;
							$xclipKey = $pathXFile;
							$thumbJpg = $tumbnailPath;
							if (is_file('../' . $mp4Key)) { $publishKeys[] = $mp4Key; }
							if (is_file('../' . $xclipKey)) { $publishKeys[] = $xclipKey; }
							if (is_file('../' . $thumbJpg)) { $publishKeys[] = $thumbJpg; }
                            // Publish and prefer thumbnail URL; avoid local is_file() after cleanup
                            $published = $publishKeys ? storage_publish_many($publishKeys, true, true) : [];
                            $UploadSourceUrl = $published[ltrim($thumbJpg, '/')] ?? ($published[ltrim($mp4Key, '/')] ?? ($base_url . 'uploads/web.png'));
                            if ($UploadSourceUrl === $base_url . 'uploads/web.png') { $tumbnailPath = 'uploads/web.png'; }
							$ext = 'mp4';
						} else {
							// No ffmpeg: treat as-is
							$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
							$pathXFile = 'uploads/files/' . $d . '/' . $getFilename;
							storage_publish_many([$pathFile], true, true);
							$UploadSourceUrl = storage_public_url($pathFile);
						}
					} else if ($fileTypeIs === 'Image') {
						$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
						$pathXFile = 'uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext;
						$tumbnailPath = $pathFile;
						// Optional watermark on image
						$thePathM = '../' . $pathFile;
						if ($ext !== 'gif' && ($watermarkStatus == 'yes' || $LinkWatermarkStatus == 'yes')) {
							watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url . $userName);
						}
						// Pixelated copy
						try {
							$dir = '../' . $pathXFile;
							if (!file_exists(dirname($dir))) { @mkdir(dirname($dir), 0755, true); }
							$image = new ImageFilter();
							$image->load('../' . $pathFile)->pixelation($pixelSize)->saveFile($dir, 100, 'jpg');
						} catch (Exception $e) { echo '<span class="request_warning">' . $e->getMessage() . '</span>'; }

						storage_publish_many([$pathFile, $pathXFile], true, true);
						$UploadSourceUrl = storage_public_url($tumbnailPath);
					} else {
						echo iN_HelpSecure($LANG['invalid_file_format']);
						continue;
					}

					// Persist and render the story item
					$insertFileFromUploadTable = $iN->iN_insertUploadedSotieFiles($userID, $pathFile, $tumbnailPath, $pathXFile, $ext);
					$getUploadedFileID = $iN->iN_GetUploadedStoriesFilesIDs($userID, $pathFile);
					if ($fileTypeIs == 'Image') {
						echo '
						<!--Storie-->
						<div class="uploaded_storie_container nonePoint body_' . $getUploadedFileID['s_id'] . '">
						<div class="dmyStory" id="' . $getUploadedFileID['s_id'] . '"><div class="i_h_in flex_ ownTooltip" data-label="' . iN_HelpSecure($LANG['delete']) . '">' . html_entity_decode($iN->iN_SelectedMenuIcon('28')) . '</div></div>
						<div class="uploaded_storie_image border_one tabing flex_">
								<img src="' . $UploadSourceUrl . '" id="img' . $getUploadedFileID['s_id'] . '">
						</div>
						<div class="add_a_text">
							<textarea class="add_my_text st_txt_' . $getUploadedFileID['s_id'] . '" placeholder="Do you want to write something about this storie?"></textarea>
						</div>
						<div class="share_story_btn_cnt flex_ tabing transition share_this_story" id="' . $getUploadedFileID['s_id'] . '">
							' . html_entity_decode($iN->iN_SelectedMenuIcon('26')) . '<div class="pbtn">' . iN_HelpSecure($LANG['publish']) . '</div>
						</div>
						</div>
						<!--/Storie-->
						<script type="text/javascript">(function($){"use strict";setTimeout(()=>{var img=document.getElementById("img' . $getUploadedFileID['s_id'] . '"); if(img && img.height>img.width){$("#img' . $getUploadedFileID['s_id'] . '").css("height","100%");} else {$("#img' . $getUploadedFileID['s_id'] . '").css("width","100%");} $(".uploaded_storie_container").show();},2000);})(jQuery);</script>
					';
					} else if ($fileTypeIs == 'video') {
						echo '
						<!--Storie-->
						<div class="uploaded_storie_container body_' . $getUploadedFileID['s_id'] . '">
						<div class="dmyStory" id="' . $getUploadedFileID['s_id'] . '"><div class="i_h_in flex_ ownTooltip" data-label="' . iN_HelpSecure($LANG['delete']) . '">' . html_entity_decode($iN->iN_SelectedMenuIcon('28')) . '</div></div>
						<div class="uploaded_storie_image border_one tabing flex_">
								<video class="lg-video-object" id="v' . $getUploadedFileID['s_id'] . '" controls preload="none" poster="' . $UploadSourceUrl . '">
									<source src="' . storage_public_url($getUploadedFileID['uploaded_file_path']) . '" preload="metadata" type="video/mp4">
									Your browser does not support HTML5 video.
								</video>
						</div>
						<div class="add_a_text">
							<textarea class="add_my_text st_txt_' . $getUploadedFileID['s_id'] . '" placeholder="Do you want to write something about this storie?"></textarea>
						</div>
						<div class="share_story_btn_cnt flex_ tabing transition share_this_story" id="' . $getUploadedFileID['s_id'] . '">
							' . html_entity_decode($iN->iN_SelectedMenuIcon('26')) . '<div class="pbtn">' . iN_HelpSecure($LANG['publish']) . '</div>
						</div>
						</div>
						<!--/Storie-->
					';
					}
				}
				exit; // prevent falling into legacy stories handler
			}
			$theValidateType = $iN->iN_Secure($_POST['c']);
			foreach ($_FILES['uploading']['name'] as $iname => $value) {
				$name = stripslashes($_FILES['uploading']['name'][$iname]);
				$size = $_FILES['uploading']['size'][$iname];
				$ext = getExtension($name);
				$ext = strtolower($ext);
				$valid_formats = explode(',', $availableVerificationFileExtensions);
				if (in_array($ext, $valid_formats)) {
					if (convert_to_mb($size) < $availableUploadFileSize) {
						$microtime = microtime();
						$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
						$UploadedFileName = "image_" . $removeMicrotime . '_' . $userID;
						$getFilename = $UploadedFileName . "." . $ext;
						// Change the image ame
						$tmp = $_FILES['uploading']['tmp_name'][$iname];
						$mimeType = $_FILES['uploading']['type'][$iname];
						$d = date('Y-m-d');
						if (preg_match('/video\/*/', $mimeType)) {
							$fileTypeIs = 'video';
						} else if (preg_match('/image\/*/', $mimeType)) {
							$fileTypeIs = 'Image';
						}
						if (!file_exists($uploadFile . $d)) {
							$newFile = mkdir($uploadFile . $d, 0755);
						}
						if (!file_exists($xImages . $d)) {
							$newFile = mkdir($xImages . $d, 0755);
						}
						if (!file_exists($xVideos . $d)) {
							$newFile = mkdir($xVideos . $d, 0755);
						}
						if (move_uploaded_file($tmp, $uploadFile . $d . '/' . $getFilename)) {
							/*IF FILE FORMAT IS VIDEO THEN DO FOLLOW*/
							if ($fileTypeIs == 'Image') {
								$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
								$pathXFile = 'uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext;
								$postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('53') . '</div>';
								$thePath = '../uploads/files/' . $d . '/'.$UploadedFileName . '.' . $ext;
								if (file_exists($thePath)) {
									try {
										$dir = "../uploads/pixel/" . $d . "/" . $getFilename;
										$fileUrl = '../uploads/files/' . $d . '/' . $UploadedFileName . '.' . $ext;
										$image = new ImageFilter();
										$image->load($fileUrl)->pixelation($pixelSize)->saveFile($dir, 100, "jpg");
									} catch (Exception $e) {
										echo '<span class="request_warning">' . $e->getMessage() . '</span>';
									}
							    }else{
									exit($LANG['upload_failed']);
								}
								// Unified publish for verification image + pixel copy
								$pixelKey = 'uploads/pixel/' . $d . '/' . $getFilename;
								$keysToPublish = [$pathFile, $pixelKey];
                                $UploadSourceUrl = storage_publish_and_url($pathFile, $keysToPublish, true);
                            }
							$insertFileFromUploadTable = $iN->iN_INSERTUploadedFilesForVerification($userID, $pathFile, NULL, $pathXFile, $ext);
							$getUploadedFileID = $iN->iN_GetUploadedFilesIDs($userID, $pathFile);
							/*AMAZON S3*/
							echo '
                    <div class="i_uploaded_item in_' . $theValidateType . ' iu_f_' . $getUploadedFileID['upload_id'] . '" id="' . $getUploadedFileID['upload_id'] . '">
                      ' . $postTypeIcon . '
                      <div class="i_delete_item_button" id="' . $getUploadedFileID['upload_id'] . '">
                          ' . $iN->iN_SelectedMenuIcon('5') . '
                      </div>
                      <div class="i_uploaded_file" style="background-image:url(' . $UploadSourceUrl . ');">
                            <img class="i_file" src="' . $UploadSourceUrl . '" alt="' . $UploadSourceUrl . '">
                      </div>
                    </div>
                ';
						}
					} else {
						echo iN_HelpSecure($size);
					}
				}
			}
		}
	}
	/*Send Account Verificatoun Request*/
	if ($type == 'verificationRequest') {
		if (isset($_POST['cID']) && isset($_POST['cP'])) {
			$cardIDPhoto = $iN->iN_Secure($_POST['cID']);
			$Photo = $iN->iN_Secure($_POST['cP']);
			$checkCardIDPhotoExist = $iN->iN_CheckImageIDExist($cardIDPhoto, $userID);
			$checkPhotoExist = $iN->iN_CheckImageIDExist($Photo, $userID);
			if (empty($cardIDPhoto) && empty($Photo) && empty($checkCardIDPhotoExist) && empty($checkPhotoExist)) {
				echo 'both';
				return false;
			}
			if (empty($cardIDPhoto) && empty($checkCardIDPhotoExist)) {
				echo 'card';
				return false;
			}
			if (empty($Photo) && empty($checkPhotoExist)) {
				echo 'photo';
				return false;
			}
			if ($checkCardIDPhotoExist == '1' && $checkPhotoExist == '1') {
				$InsertNewVerificationRequest = $iN->iN_InsertNewVerificationRequest($userID, $cardIDPhoto, $Photo);
				if ($InsertNewVerificationRequest) {
					echo '200';
				}
			} else {
				echo 'both';
			}
		}
	}
	/*Accept Conditions by Clicking Next Button*/
	if ($type == 'acceptConditions') {
		$conditionsAccept = $iN->iN_AcceptConditions($userID);
		if ($conditionsAccept) {
			echo '200';
		}
	}
	if($type == 'vldcd'){
		if(isset($_POST['code']) && $_POST['code'] != '' && !empty($_POST['code'])){
			$cosCode = $iN->iN_Secure($_POST['code']);
			$vcodeCheck = $iN->iN_PurUCheck($userID, $cosCode, $base_url);
			if($vcodeCheck == base64_decode('b2s=')){
				if($iN->iN_LegDone($cosCode)){
					exit(base64_decode('bmV4dA=='));
				}else{
					exit(base64_decode('RHVyaW5nIHRoZSBpbnN0YWxsYXRpb24gcHJvY2VzcywgYW4gaXNzdWUgaGFzIGFyaXNlbiBjb25jZXJuaW5nIHRoZSBzZXJ2ZXIuIFBsZWFzZSBjcmVhdGUgYSA8YSBocmVmPSJodHRwczovL3N1cHBvcnQuZGl6enlzY3JpcHRzLmNvbS8/cD1jcmVhdGVUaWNrZXQiPnRpY2tldDwvYT4gZm9yIHByb21wdCBhc3Npc3RhbmNlLiBCZWZvcmUgY3JlYXRpbmcgYSB0aWNrZXQsIGtpbmRseSB0YWtlIGEgbW9tZW50IHRvIHJldmlldyBmb3IgYSA8YSBocmVmPSJodHRwczovL3N1cHBvcnQuZGl6enlzY3JpcHRzLmNvbS8/cD1mYXFzIj5xdWljayByZXNwb25zZTwvYT4u'));
				}
			} else{
				exit(base64_decode('RHVyaW5nIHRoZSBpbnN0YWxsYXRpb24gcHJvY2VzcywgYW4gaXNzdWUgaGFzIGFyaXNlbiBjb25jZXJuaW5nIHRoZSBzZXJ2ZXIuIFBsZWFzZSBjcmVhdGUgYSA8YSBocmVmPSJodHRwczovL3N1cHBvcnQuZGl6enlzY3JpcHRzLmNvbS8/cD1jcmVhdGVUaWNrZXQiPnRpY2tldDwvYT4gZm9yIHByb21wdCBhc3Npc3RhbmNlLiBCZWZvcmUgY3JlYXRpbmcgYSB0aWNrZXQsIGtpbmRseSB0YWtlIGEgbW9tZW50IHRvIHJldmlldyBmb3IgYSA8YSBocmVmPSJodHRwczovL3N1cHBvcnQuZGl6enlzY3JpcHRzLmNvbS8/cD1mYXFzIj5xdWljayByZXNwb25zZTwvYT4u'));
			}
		}
	}
	/*Insert Subscription Amount if Amounts are not empty*/
	if ($type == 'setSubscriptionPayments') {
		if (in_array($_POST['wStatus'], $statusValue) && in_array($_POST['mStatus'], $statusValue) && in_array($_POST['yStatus'], $statusValue)) {
			$SubWeekAmount = $iN->iN_Secure($_POST['wSubWeekAmount']);
			$SubMonthAmount = $iN->iN_Secure($_POST['mSubMonthAmount']);
			$SubYearAmount = $iN->iN_Secure($_POST['mSubYearAmount']);
			$weeklySubStatus = $iN->iN_Secure($_POST['wStatus']);
			$monthlySubStatus = $iN->iN_Secure($_POST['mStatus']);
			$yearlySubStatus = $iN->iN_Secure($_POST['yStatus']);
			if (!empty($SubWeekAmount) && $SubWeekAmount !== '') {
				if ($SubWeekAmount >= $subscribeWeeklyMinimumAmount) {
					$iN->iN_InsertWeeklySubscriptionAmountAndStatus($userID, $SubWeekAmount, $weeklySubStatus);
				}
			}
			if (!empty($SubMonthAmount) && $SubMonthAmount !== '') {
				if ($SubMonthAmount >= $subscribeMonthlyMinimumAmount) {
					$iN->iN_InsertMonthlySubscriptionAmountAndStatus($userID, $SubMonthAmount, $monthlySubStatus);
				}
			}
			if (!empty($SubYearAmount) && $SubYearAmount !== '') {
				if ($SubYearAmount >= $subscribeYearlyMinimumAmount) {
					$iN->iN_InsertYearlySubscriptionAmountAndStatus($userID, $SubYearAmount, $yearlySubStatus);
				}
			}
			$updateFeeStatus = $iN->iN_UpdateUserFeeStatus($userID);
			if ($updateFeeStatus) {
				echo '200';
			}
		}
	}
	/*Save Payout Details*/
	if ($type == 'payoutSet') {
		if (in_array($_POST['method'], $defaultPayoutMethods)) {
			$paypalEmail = $iN->iN_Secure($_POST['paypalEmail']);
			$re_paypalEmail = $iN->iN_Secure($_POST['paypalReEmail']);
			$bankAccount = $iN->iN_Secure($_POST['bank']);
			$defaultMethod = $iN->iN_Secure($_POST['method']);
			if($defaultMethod != 'bank'){
				if ($paypalEmail != $re_paypalEmail) {
					echo 'email_warning';
					exit();
				}
			}

			if ($defaultMethod == 'bank' && $bankAccount == '' && empty($bankAccount)) {
				echo 'bank_warning';
				exit();
			}
			if ($defaultMethod == 'paypal' && $paypalEmail == '' && empty($paypalEmail)) {
				echo 'paypal_warning';
				exit();
			}
			if($defaultMethod != 'bank'){
				if (!filter_var($paypalEmail, FILTER_VALIDATE_EMAIL)) {
					echo 'not_valid_email';
					exit();
				}
		    }
			$insertPayout = $iN->iN_SetPayout($userID, $paypalEmail, $bankAccount, $defaultMethod);
			if ($insertPayout) {
				echo '200';
			}
		}
	}
	/*Check Username Exist*/
	if ($type == 'checkusername') {
		if (isset($_POST['username']) && $_POST['username'] != '' && !empty($_POST['username'])) {
			$new_username = $iN->iN_Secure($_POST['username']);
			$checkUsernameExist = $iN->iN_CheckUsernameExist($new_username);
			if ($new_username == $userName) {
				exit();
			} else if (strlen($new_username) < 5) {
				echo '4';
			} else if (!preg_match('/^[\w]+$/', $_POST['username'])) {
				echo '3';
			} else if ($checkUsernameExist == 'no') {
				echo '1';
			} else if ($checkUsernameExist == 'yes') {
				echo '2';
			}
		}
	}
	/*Edit May Page*/
	if ($type == 'editMyPage') {
		$fullname = $iN->iN_Secure($_POST['flname']);
		$newUsername = $iN->iN_Secure($_POST['uname']);
		$gender = $iN->iN_Secure($_POST['gender']);
		$bio = $iN->iN_Secure($_POST['bio']);
		if(isset($_POST['tnot']) && !empty($_POST['tnot']) && $_POST['tnot'] != ''){
			$tipNot = $iN->iN_Secure($_POST['tnot']);
		}else{
			$tipNot = '';
		}
		   $socialNet = $iN->iN_ShowUserSocialSitesList($userID);
           if($socialNet){
               foreach($socialNet as $snet){
                 $sKey = $snet['skey'];
				 $slID = $snet['id'];
				 if(isset($_POST[$sKey]) && !empty($_POST[$sKey]) && $_POST[$sKey] != ''){
					 $mySkey = trim($_POST[$sKey]);
                     if($iN->iN_IsUrl($mySkey) == '1'){
						$exist = DB::one("SELECT 1 FROM i_social_user_profiles WHERE uid_fk = ? AND isw_id_fk = ? LIMIT 1", [(int)$userID, (int)$slID]);
					    if($exist){
						    DB::exec("UPDATE i_social_user_profiles SET s_link = ? WHERE uid_fk = ? AND isw_id_fk = ?", [$mySkey, (int)$userID, (int)$slID]);
					    } else {
						    DB::exec("INSERT INTO i_social_user_profiles(s_link,isw_id_fk, uid_fk) VALUES (?,?,?)", [$mySkey, (int)$slID, (int)$userID]);
					    }
					}
				 }else{
					DB::exec("UPDATE i_social_user_profiles SET s_link = NULL WHERE uid_fk = ? AND isw_id_fk = ?", [(int)$userID, (int)$slID]);
				 }
		       }
	       }
		$birthDay = $iN->iN_Secure($_POST['birthdate']);
		$profileCategory = $iN->iN_Secure($_POST['ctgry']);
		$checkUsernameExist = $iN->iN_CheckUsernameExist($newUsername);
		if (strlen($fullname) < 3 || strlen($fullname) > 30 || empty($fullname)) {
			exit('3');
		}
		if (strlen($newUsername) < 5) {
			$newUsername = $userName;
		} else if (!preg_match('/^[\w]+$/', $newUsername)) {
			$newUsername = $userName;
		} else if ($checkUsernameExist == 'yes') {
			$newUsername = $userName;
		}
		if (strlen($fullname) < 5 || strlen($fullname) > 30) {
			$fullname = $userFullName;
		}
		if(!empty($birthDay) && $birthDay != '' && $birthDay != 'undefined'){
			if ($iN->iN_CalculateUserAge($birthDay) < 18) {
				exit('2');
			}
	    }
		if(!empty($birthDay) && $birthDay != '' && $birthDay != 'undefined'){
           if(!$iN->isDate($birthDay)){
               exit('1');
		   }
		}else{
			$birthDay = NULL;
		}

		if (in_array($gender, $genders) && isset($newUsername) && !empty($newUsername) && $newUsername != '' && isset($fullname) && !empty($fullname) && $fullname != '') {
			$updateMyProfile = $iN->iN_UpdateProfile($userID, $iN->iN_Secure($fullname), $iN->iN_Secure($bio), $iN->iN_Secure($newUsername), $iN->iN_Secure($birthDay), $iN->iN_Secure($profileCategory), $iN->iN_Secure($gender),$iN->iN_Secure($tipNot));
			if ($updateMyProfile) {
				echo '1';
			}
		}
	}
	/*Call Avatar and Cover PopUP*/
	if ($type == 'updateAvatarCover') {
		include "../themes/$currentTheme/layouts/popup_alerts/uploadAvatarCoverPhoto.php";
	}
	/*Upload Croped Image*/
	if ($type == 'coverUpload') {
		if (isset($_POST['image']) && $_POST['image'] != '' && !empty($_POST['image'])) {
			$dataImage = $iN->iN_Secure($_POST['image']);
			$image_array_1 = explode(";", $dataImage);
			$image_array_2 = explode(",", $image_array_1[1]);
			$data = base64_decode($image_array_2[1]);
			$microtime = microtime();
			$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
			$UploadedFileName = "cover_" . $removeMicrotime . '_' . $userID;
			$getFilename = $UploadedFileName . ".png";
			$ext = getExtension($getFilename);
			$valid_formats = explode(',', $availableFileExtensions);
			if (strlen($getFilename)) {
				if (in_array($ext, $valid_formats)) {
					$d = date('Y-m-d');
					if (!file_exists($uploadCover . $d)) {
						$newFile = mkdir($uploadCover . $d, 0755, true);
					}
					if (file_put_contents($uploadCover . $d . '/' . $getFilename, $data)) {
						$pathFile = 'uploads/covers/' . $d . '/' . $getFilename;
						// Unified: publish and build URL for cover
						$relCover = 'uploads/covers/' . $d . '/' . $getFilename;
						$UploadSourceUrl = storage_publish_and_url($relCover, [$relCover], true);
						$coverData = $iN->iN_INSERTUploadedCoverPhoto($userID, $pathFile);
						if ($coverData) {
							$getUploadedFileID = $iN->iN_GetUploadedCoverURL($userID, $coverData);
							$imgUrl = storage_public_url($getUploadedFileID);
							echo $imgUrl;
						} else {
                exit($LANG['something_went_wrong']);
						}
					}
				}
			}

		}
	}
	/*Upload Croped Image*/
	if ($type == 'avatarUpload') {
		if (isset($_POST['image']) && $_POST['image'] != '' && !empty($_POST['image'])) {
			$dataImage = $iN->iN_Secure($_POST['image']);
			$image_array_1 = explode(";", $dataImage);
			$image_array_2 = explode(",", $image_array_1[1]);
			$data = base64_decode($image_array_2[1]);
			$microtime = microtime();
			$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
			$UploadedFileName = "avatar_" . $removeMicrotime . '_' . $userID;
			$getFilename = $UploadedFileName . ".png";
			$ext = getExtension($getFilename);
			$valid_formats = explode(',', $availableFileExtensions);
			if (strlen($getFilename)) {
				if (in_array($ext, $valid_formats)) {
					$d = date('Y-m-d');
					if (!file_exists($uploadAvatar . $d)) {
						$newFile = mkdir($uploadAvatar . $d, 0755, true);
					}
                        if (file_put_contents($uploadAvatar . $d . '/' . $getFilename, $data)) {
                            $pathFile = 'uploads/avatars/' . $d . '/' . $getFilename;
                            // Unified publish to active storage provider
                            $relAvatar = 'uploads/avatars/' . $d . '/' . $getFilename;
                            $UploadSourceUrl = storage_publish_and_url($relAvatar, [$relAvatar], true);
						$coverData = $iN->iN_INSERTUploadedAvatarPhoto($userID, $pathFile);
						if ($coverData) {
							$getUploadedFileID = $iN->iN_GetUploadedAvatarURL($userID, $coverData);
                            $imgUrl = storage_public_url($getUploadedFileID);
							echo $imgUrl;
						} else {
                exit($LANG['something_went_wrong']);
						}
					}
				}
			}

		}
	}
	/*Check Email Valid or Exist*/
	if ($type == 'checkemail') {
		if (isset($_POST['newEmail']) && $_POST['newEmail'] != '' && !empty($_POST['newEmail'])) {
			$newEmail = $iN->iN_Secure($_POST['newEmail']);
			if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
				echo 'no';
				exit();
			} else {
				$checkEmail = $iN->iN_CheckEmail($userID, $newEmail);
				if ($checkEmail) {
					echo '200';
				} else {
					echo '404';
				}
			}
		}
	}
	/*Update Email Address*/
	if ($type == 'editMyEmail') {
		if (isset($_POST['newEmail']) && $_POST['newEmail'] != '' && !empty($_POST['newEmail']) && isset($_POST['currentPass']) && $_POST['currentPass'] != '' && !empty($_POST['currentPass'])) {
			$newEmail = $iN->iN_Secure($_POST['newEmail']);
			$currentPassword = $iN->iN_Secure($_POST['currentPass']);
			$checkEmail = $iN->iN_CheckEmail($userID, $newEmail);
			if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
				echo 'no';
				exit();
			} else if ($newEmail != $userEmail) {
				$Change = $iN->iN_CheckUserPasswordAndUpdateIfIsValid($userID, $currentPassword, $newEmail);
				if ($Change) {
					echo '200';
				} else {
					echo '404';
				}
			} else {
				echo 'same';
			}
		}
	}
	if ($type == 'updatePayoutSet') {
		if (in_array($_POST['method'], $defaultPayoutMethods)) {
			$paypalEmail = $iN->iN_Secure($_POST['paypalEmail']);
			$re_paypalEmail = $iN->iN_Secure($_POST['paypalReEmail']);
			$bankAccount = $iN->iN_Secure($_POST['bank']);
			$defaultMethod = $iN->iN_Secure($_POST['method']);
			if($defaultMethod != 'bank'){
				if ($paypalEmail != $re_paypalEmail) {
					echo 'email_warning';
					exit();
				}
			}
			if ($defaultMethod == 'bank' && $bankAccount == '' && empty($bankAccount)) {
				echo 'bank_warning';
				exit();
			}
			if ($defaultMethod == 'paypal' && $paypalEmail == '' && empty($paypalEmail)) {
				echo 'paypal_warning';
				exit();
			}
			if($defaultMethod != 'bank'){
				if (!filter_var($paypalEmail, FILTER_VALIDATE_EMAIL)) {
					echo 'not_valid_email';
					exit();
				}
		    }
			$insertPayout = $iN->iN_UpdatePayout($userID, $paypalEmail, $bankAccount, $defaultMethod);
			if ($insertPayout) {
				echo '200';
			}
		}
	}
/*Insert Subscription Amount if Amounts are not empty*/
if ($type === 'updateSubscriptionPayments') {
    $normalizeAmount = function ($value) use ($iN) {
        if ($value === null) {
            return null;
        }
        $value = trim((string)$value);
        if ($value === '') {
            return null;
        }
        $normalized = str_replace(',', '.', $value);
        if (!is_numeric($normalized)) {
            return null;
        }
        return $iN->iN_Secure($normalized, 1, false);
    };

    $toFloat = function ($value) {
        if ($value === null || $value === '') {
            return null;
        }
        $normalized = str_replace(',', '.', (string)$value);
        return is_numeric($normalized) ? (float)$normalized : null;
    };

    $weeklySubStatus = isset($_POST['wStatus']) && in_array($_POST['wStatus'], $statusValue, true) ? $_POST['wStatus'] : null;
    $monthlySubStatus = isset($_POST['mStatus']) && in_array($_POST['mStatus'], $statusValue, true) ? $_POST['mStatus'] : null;
    $yearlySubStatus = isset($_POST['yStatus']) && in_array($_POST['yStatus'], $statusValue, true) ? $_POST['yStatus'] : null;

    $SubWeekAmountRaw = isset($_POST['wSubWeekAmount']) ? $_POST['wSubWeekAmount'] : null;
    $SubMonthAmountRaw = isset($_POST['mSubMonthAmount']) ? $_POST['mSubMonthAmount'] : null;
    $SubYearAmountRaw = isset($_POST['mSubYearAmount']) ? $_POST['mSubYearAmount'] : null;

    $SubWeekAmount = $normalizeAmount($SubWeekAmountRaw);
    $SubMonthAmount = $normalizeAmount($SubMonthAmountRaw);
    $SubYearAmount = $normalizeAmount($SubYearAmountRaw);

    $SubWeekAmountFloat = $toFloat($SubWeekAmount ?? $SubWeekAmountRaw);
    $SubMonthAmountFloat = $toFloat($SubMonthAmount ?? $SubMonthAmountRaw);
    $SubYearAmountFloat = $toFloat($SubYearAmount ?? $SubYearAmountRaw);

    $existingWeeklyPlan = $iN->iN_GetUserSubscriptionPlanDetails($userID, 'weekly');
    $existingMonthlyPlan = $iN->iN_GetUserSubscriptionPlanDetails($userID, 'monthly');
    $existingYearlyPlan = $iN->iN_GetUserSubscriptionPlanDetails($userID, 'yearly');

    $weeklyResponse = $monthlyResponse = $yearlyResponse = null;
    $anySuccess = false;

    $weeklyMin = $subscriptionType === '2' ? (float)$minPointFeeWeekly : (float)$subscribeWeeklyMinimumAmount;
    $monthlyMin = $subscriptionType === '2' ? (float)$minPointFeeMonthly : (float)$subscribeMonthlyMinimumAmount;
    $yearlyMin = $subscriptionType === '2' ? (float)$minPointFeeYearly : (float)$subscribeYearlyMinimumAmount;

    if ($weeklySubStatus !== null) {
        if ($weeklySubStatus === '1') {
            if ($SubWeekAmountFloat !== null && $SubWeekAmountFloat >= $weeklyMin) {
                if ($iN->iN_UpdateWeeklySubscriptionAmountAndStatus($userID, $SubWeekAmount, $weeklySubStatus)) {
                    $weeklyResponse = '200';
                    $anySuccess = true;
                }
            } else {
                $weeklyResponse = '404';
            }
        } else {
            $amountToPersist = $SubWeekAmount ?? ($existingWeeklyPlan['amount'] ?? null);
            if ($amountToPersist !== null && $iN->iN_UpdateWeeklySubscriptionAmountAndStatus($userID, $amountToPersist, $weeklySubStatus)) {
                $weeklyResponse = '200';
                $anySuccess = true;
            }
        }
    }

    if ($monthlySubStatus !== null) {
        if ($monthlySubStatus === '1') {
            if ($SubMonthAmountFloat !== null && $SubMonthAmountFloat >= $monthlyMin) {
                if ($iN->iN_UpdateMonthlySubscriptionAmountAndStatus($userID, $SubMonthAmount, $monthlySubStatus)) {
                    $monthlyResponse = '200';
                    $anySuccess = true;
                }
            } else {
                $monthlyResponse = '404';
            }
        } else {
            $amountToPersist = $SubMonthAmount ?? ($existingMonthlyPlan['amount'] ?? null);
            if ($amountToPersist !== null && $iN->iN_UpdateMonthlySubscriptionAmountAndStatus($userID, $amountToPersist, $monthlySubStatus)) {
                $monthlyResponse = '200';
                $anySuccess = true;
            }
        }
    }

    if ($yearlySubStatus !== null) {
        if ($yearlySubStatus === '1') {
            if ($SubYearAmountFloat !== null && $SubYearAmountFloat >= $yearlyMin) {
                if ($iN->iN_UpdateYearlySubscriptionAmountAndStatus($userID, $SubYearAmount, $yearlySubStatus)) {
                    $yearlyResponse = '200';
                    $anySuccess = true;
                }
            } else {
                $yearlyResponse = '404';
            }
        } else {
            $amountToPersist = $SubYearAmount ?? ($existingYearlyPlan['amount'] ?? null);
            if ($amountToPersist !== null && $iN->iN_UpdateYearlySubscriptionAmountAndStatus($userID, $amountToPersist, $yearlySubStatus)) {
                $yearlyResponse = '200';
                $anySuccess = true;
            }
        }
    }

    if ($anySuccess) {
        $iN->iN_UpdateUserFeeStatus($userID);
    }

    $response = array_filter([
        'weekly' => $weeklyResponse,
        'monthly' => $monthlyResponse,
        'yearly' => $yearlyResponse,
    ], function ($value) {
        return $value !== null;
    });

    echo json_encode($response);
}
/*Inser Withdrawal*/
	if ($type == 'makewithDraw') {
		if (isset($_POST['amount']) && !empty($_POST['amount']) && $_POST['amount'] != '') {
			$withdrawalAmount = $iN->iN_Secure($_POST['amount']);
			$checkHavePendingWithdrawal = $iN->iN_CheckUserHavePendingWithdrawal($userID);
			if ($checkHavePendingWithdrawal) {
				echo '5';
				exit();
			}
			if ($withdrawalAmount >= $minimumWithdrawalAmount) {
				if ($userWallet >= $withdrawalAmount) {
					$insertWithdrawal = $iN->iN_InsertWithdrawal($userID, $withdrawalAmount, $payoutMethod, 'withdrawal');
					if ($insertWithdrawal) {
						echo '1';
					} else {
						echo '4';
					}
				} else {
					echo '3';
				}
			} else {
				echo '2';
			}
		}
	}
	if ($type == 'pPurchase') {
		if (isset($_POST['purchase']) && $_POST['purchase'] != '' && !empty($_POST['purchase'])) {
			$purchaseingPostID = $iN->iN_Secure($_POST['purchase']);
			$getPurchasingPostDetails = $iN->iN_GetAllPostDetails($purchaseingPostID);
			if ($getPurchasingPostDetails) {
				$userPostID = $getPurchasingPostDetails['post_id'];
				$userPostFile = $getPurchasingPostDetails['post_file'];
				$userPostOwnerID = $getPurchasingPostDetails['post_owner_id'];
				$userPostOwnerUserAvatar = $iN->iN_UserAvatar($userPostOwnerID, $base_url);
				$userPostOwnerUsername = $getPurchasingPostDetails['i_username'];
				$userPostOwnerUserFullName = $getPurchasingPostDetails['i_user_fullname'];
				$userPostWantedCredit = $getPurchasingPostDetails['post_wanted_credit'];
				include "../themes/$currentTheme/layouts/popup_alerts/purchase_premium_post.php";
			}
		}
	}
/*Purchase Post*/
	if ($type == 'goWallet') {
		if (isset($_POST['p'])) {
			$PurchasePostID = $iN->iN_Secure($_POST['p']);
			$checkPostID = $iN->iN_CheckPostIDExist($PurchasePostID);
			if ($checkPostID) {
				$getPurchasingPostDetails = $iN->iN_GetAllPostDetails($PurchasePostID);
				$userPostID = $getPurchasingPostDetails['post_id'];
				$userPostWantedCredit = $getPurchasingPostDetails['post_wanted_credit'];
				$userPostOwnerID = $getPurchasingPostDetails['post_owner_id'];
				$postType = $getPurchasingPostDetails['post_type'];

				$translatePointToMoney = $userPostWantedCredit * $onePointEqual;
				$adminEarning = $translatePointToMoney * ($adminFee / 100);
				$userEarning = $translatePointToMoney - $adminEarning;

				if ($userCurrentPoints >= $userPostWantedCredit && $userID != $userPostOwnerID) {
					$buyPost = $iN->iN_BuyPost($userID, $userPostOwnerID, $PurchasePostID, $translatePointToMoney, $adminEarning, $userEarning, $adminFee, $userPostWantedCredit);
					if ($buyPost) {
						$approveNot = $LANG['congratulations_you_sold'];
						$iN->iN_SendNotificationForPurchasedPost($userID, $PurchasePostID, $userPostOwnerID,  $approveNot);
						$uData = $iN->iN_GetUserDetails($userPostOwnerID);
						$sendEmail = isset($uData['i_user_email']) ? $uData['i_user_email'] : NULL;
						$lUsername = $uData['i_username'];
						$lUserFullName = $uData['i_user_fullname'];
						$emailNotificationStatus = $uData['email_notification_status'];
						$notQualifyDocument = $LANG['not_qualify_document'];

						if($postType === 'reels'){
						    $slugUrl = $base_url . 'reels/' . $getPurchasingPostDetails['url_slug'];
						    echo iN_HelpSecure($slugUrl);
						}else{
						    $slugUrl = $base_url . 'post/' . $getPurchasingPostDetails['url_slug'] . '_' . $userPostID;
						    echo iN_HelpSecure($slugUrl);
						}
						if ($emailSendStatus == '1' && $userID != $userPostOwnerID && $emailNotificationStatus == '1') {

							if ($smtpOrMail == 'mail') {
								$mail->IsMail();
							} else if ($smtpOrMail == 'smtp') {
								$mail->isSMTP();
								$mail->Host = $smtpHost; // Specify main and backup SMTP servers
								$mail->SMTPAuth = true;
								$mail->SMTPKeepAlive = true;
								$mail->Username = $smtpUserName; // SMTP username
								$mail->Password = $smtpPassword; // SMTP password
								$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
								$mail->Port = $smtpPort;
								$mail->SMTPOptions = array(
									'ssl' => array(
										'verify_peer' => false,
										'verify_peer_name' => false,
										'allow_self_signed' => true,
									),
								);
							} else {
								return false;
							}
							$instagramIcon = $iN->iN_SelectedMenuIcon('88');
							$facebookIcon = $iN->iN_SelectedMenuIcon('90');
							$twitterIcon = $iN->iN_SelectedMenuIcon('34');
							$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
							$someoneBoughtYourPost = $iN->iN_Secure($LANG['someone_bought_your_post']);
							$clickGoPost = $iN->iN_Secure($LANG['click_go_post']);
							$youEarnMoney = $iN->iN_Secure($LANG['you_earn_money']);
							include_once '../includes/mailTemplates/postBoughtEmailTemplate.php';
							$body = $bodyPostBoughtEmailTemplate;
							$mail->setFrom($smtpEmail, $siteName);
							$send = false;
							$mail->IsHTML(true);
							$mail->addAddress($sendEmail, ''); // Add a recipient
							$mail->Subject = $iN->iN_Secure($LANG['someone_bought_your_post']);
				$mail->CharSet = 'utf-8';
				$mail->MsgHTML($body);
				if (iN_safeMailSend($mail, $smtpOrMail, 'post_purchase_notification')) {
					$mail->ClearAddresses();
					return true;
				}

						}
					} else {
						echo $LANG['something_wrong'];
					}
				} else {
					exit (iN_HelpSecure($base_url) . 'purchase/purchase_point');
				}
			}
		}
	}
    /*Choose Payment Method*/
	if ($type == 'choosePaymentMethod') {
		if (isset($_POST['type']) && $_POST['type'] != '' && !empty($_POST['type'])) {
			$planID = $iN->iN_Secure($_POST['type']);
			$checkPlanExist = $iN->CheckPlanExist($planID);
			if ($checkPlanExist) {
				$planData = $iN->GetPlanDetails($planID);
				$planAmount = $planData['amount'];
				$planPoint = $planData['plan_amount'];
				if($stripePaymentCurrency == 'JPY'){
                     $planAmount = $planAmount / 100;
				}
				require_once '../includes/payment/vendor/autoload.php';
				if (!defined('INORA_METHODS_CONFIG')) {
					define('INORA_METHODS_CONFIG', realpath('../includes/payment/paymentConfig.php'));
				}
				$configData = configItem();
				$DataUserDetails = [
					'amounts' => [ // at least one currency amount is required
						$payPalCurrency => $planAmount,
						$iyziCoPaymentCurrency => $planAmount,
						$bitPayPaymentCurrency => $planAmount,
						$autHorizePaymentCurrency => $planAmount,
						$payStackPaymentCurrency => $planAmount,
						$stripePaymentCurrency => $planAmount,
						$razorPayPaymentCurrency => $planAmount,
						$mercadoPagoCurrency => $planAmount
					],
					'order_id' => 'ORDS' . uniqid(), // required in instamojo, Iyzico, Paypal, Paytm gateways
					'customer_id' => 'CUSTOMER' . uniqid(), // required in Iyzico, Paytm gateways
					'item_name' => $LANG['point_purchasing'], // required in Paypal gateways
					'item_qty' => 1,
					'item_id' => 'ITEM' . uniqid(), // required in Iyzico, Paytm gateways
					'payer_email' => $userEmail, // required in instamojo, Iyzico, Stripe gateways
					'payer_name' => $userFullName, // required in instamojo, Iyzico gateways
					'description' => $LANG['point_purchasing_from'], // Required for stripe
					'ip_address' => getUserIpAddr(), // required only for iyzico
					'address' => '3234 Godfrey Street Tigard, OR 97223', // required in Iyzico gateways
					'city' => 'Tigard', // required in Iyzico gateways
					'country' => 'United States', // required in Iyzico gateways
				];
				$PublicConfigs = getPublicConfigItem();

				$configItem = $configData['payments']['gateway_configuration'];

				// Get config data
				$configa = getPublicConfigItem();
				// Get app URL
				$paymentPagePath = getAppUrl();

				$gatewayConfiguration = $configData['payments']['gateway_configuration'];
				// get paystack config data
				$paystackConfigData = $gatewayConfiguration['paystack'];
				// Get paystack callback ur
				$paystackCallbackUrl = getAppUrl($paystackConfigData['callbackUrl']);

				// Get stripe config data
				$stripeConfigData = $gatewayConfiguration['stripe'];
				// Get stripe callback ur
				$stripeCallbackUrl = getAppUrl($stripeConfigData['callbackUrl']);

				// Get razorpay config data
				$razorpayConfigData = $gatewayConfiguration['razorpay'];
				// Get razorpay callback url
				$razorpayCallbackUrl = getAppUrl($razorpayConfigData['callbackUrl']);

				// Get Authorize.Net config Data
				$authorizeNetConfigData = $gatewayConfiguration['authorize-net'];
				// Get Authorize.Net callback url
				$authorizeNetCallbackUrl = getAppUrl($authorizeNetConfigData['callbackUrl']);

				// Individual payment gateway url
				$individualPaymentGatewayAppUrl = getAppUrl('individual-payment-gateways');
				// User Details Configurations FINISHED
				include "../themes/$currentTheme/layouts/popup_alerts/paymentMethods.php";
			}
		}
	}
	if ($type == 'process') {
		require_once '../includes/payment/vendor/autoload.php';
		if (!defined('INORA_METHODS_CONFIG')) {
			define('INORA_METHODS_CONFIG', realpath('../includes/payment/paymentConfig.php'));
		}
		include "../includes/payment/payment-process.php";
	}
/*Get Gifs*/
	if ($type == 'chat_gifs') {
		if (isset($_POST['id'])) {
			$id = $iN->iN_Secure($_POST['id']);
			include "../themes/$currentTheme/layouts/chat/gifs.php";
		}
	}
/*Get Stickers*/
	if ($type == 'chat_stickers') {
		if (isset($_POST['id'])) {
			$id = $iN->iN_Secure($_POST['id']);
			include "../themes/$currentTheme/layouts/chat/stickers.php";
		}
	}
/*Get Stickers*/
	if ($type == 'chat_btns') {
		if (isset($_POST['id'])) {
			$id = $iN->iN_Secure($_POST['id']);
			include "../themes/$currentTheme/layouts/chat/chat_btns.php";
		}
	}
/*Get Emojis*/
	if ($type == 'memoji') {
		if (isset($_POST['id'])) {
			$id = $iN->iN_Secure($_POST['id']);
			$importID = '';
			$importClass = 'emoji_item_m';
			include "../themes/$currentTheme/layouts/chat/emojis.php";
		}
	}
/*Insert New Message*/
	if ($type == 'nmessage') {
		if (isset($_POST['id']) && isset($_POST['val'])) {
			$message = $iN->iN_Secure($_POST['val']);
			$chatID = $iN->iN_Secure($_POST['id']);
			$sticker = $iN->iN_Secure($_POST['sticker']);
			$gifSrc = $iN->iN_Secure($_POST['gif']);
			$fileIDs = $iN->iN_Secure($_POST['fl'] ?? '');
			$trimMoney = $iN->iN_Secure($_POST['mo']);
			$mMoney = trim($trimMoney);
			$file = isset($fileIDs) ? $fileIDs : NULL;
			$checkChatIDExist = $iN->iN_CheckChatIDExist($chatID);
			$getStickerURL = $iN->iN_getSticker($sticker);
			$stickerURL = isset($getStickerURL['sticker_url']) ? $getStickerURL['sticker_url'] : NULL;
			$gifUrl = isset($gifSrc) ? $gifSrc : NULL;
			if(!empty($mMoney) || $mMoney != ''){
				if(empty($message) && empty($file)){
                   exit('403');
				}
				if($minimumPointLimit > $mMoney){
				  exit('404');
				}
			 }
			if (empty($message)) {
				if (empty($stickerURL)) {
					if (empty($gifUrl)) {
						if (empty($file)) {
							exit('404');
						}
					}
				}
			}

			if ($checkChatIDExist) {
				$insertData = $iN->iN_InsertNewMessage($userID, $chatID, $iN->iN_Secure($message), $iN->iN_Secure($stickerURL), $iN->iN_Secure($gifUrl), $iN->iN_Secure($file), $iN->iN_Secure($mMoney));
				/**/
				if ($insertData) {
					$cMessageID = $insertData['con_id'];
					$cUserOne = $insertData['user_one'];
					$cUserTwo = $insertData['user_two'];
					$cMessage = $insertData['message'];
					$mSeenStatus = $insertData['seen_status'];
					$gifMoney = isset($insertData['gifMoney']) ? $insertData['gifMoney'] : NULL;
					$privateStatus = isset($insertData['private_status']) ? $insertData['private_status'] : NULL;
				    $privatePrice = isset($insertData['private_price']) ? $insertData['private_price'] : NULL;
					$cStickerUrl = isset($insertData['sticker_url']) ? $insertData['sticker_url'] : NULL;
					$cGifUrl = isset($insertData['gifurl']) ? $insertData['gifurl'] : NULL;
					$cMessageTime = $insertData['time'];
					$ip = $iN->iN_GetIPAddress();
					$query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
					if ($query && $query['status'] == 'success') {
						date_default_timezone_set($query['timezone']);
					}
					$message_time = date("c", $cMessageTime);
					$convertMessageTime = strtotime($message_time);
					$netMessageHour = date('H:i', $convertMessageTime);
					$cFile = isset($insertData['file']) ? $insertData['file'] : NULL;
					$msgDots = '';
					$imStyle = '';
					$seenStatus = '';
					if ($cUserOne == $userID) {
						$mClass = 'me';
						$msgOwnerID = $cUserOne;
						$lastM = '';
						$timeStyle = 'msg_time_me';
						if (!empty($cFile)) {
							$imStyle = 'mmi_i';
						}
						$seenStatus = '<span class="seenStatus flex_ notSeen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
						if ($mSeenStatus == '1') {
							$seenStatus = '<span class="seenStatus flex_ seen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
						}
					} else {
						$mClass = 'friend';
						$msgOwnerID = $cUserOne;
						$lastM = 'mm_' . $msgOwnerID;
						if (!empty($cFile)) {
							$imStyle = 'mmi_if';
						}
						$timeStyle = 'msg_time_fri';
					}
					$styleFor = '';
					if ($cStickerUrl) {
						$styleFor = 'msg_with_sticker';
						$cMessage = '<img class="mStick" src="' . $cStickerUrl . '">';
					}
					if ($cGifUrl) {
						$styleFor = 'msg_with_gif';
						$cMessage = '<img class="mGifM" src="' . $cGifUrl . '">';
					}
					$msgOwnerAvatar = $iN->iN_UserAvatar($msgOwnerID, $base_url);
					include "../themes/$currentTheme/layouts/chat/newMessage.php";
				}
				/**/
			} else {
				echo '404';
			}
		}
	}
/* Insert Live Message */
if ($type == 'livemessage') {
    if (
        isset($_POST['val']) && !empty($_POST['val']) &&
        isset($_POST['id']) && !empty($_POST['id']) &&
        trim($_POST['val']) !== '' && trim($_POST['id']) !== ''
    ) {
        $liveID = $iN->iN_Secure($_POST['id']);
        $liveMessageRaw = rawurldecode($_POST['val']);
        $liveMessage = $iN->iN_Secure($liveMessageRaw);

        if (empty($liveMessage) || trim($liveMessage) == '') {
            exit('404');
        }

        $lmData = $iN->iN_InsertLiveMessage($liveID, $iN->iN_Secure($liveMessage), $userID);

        if ($lmData) {
            $messageID = $lmData['cm_id'];
            $messageLiveID = $lmData['cm_live_id'];
            $messageLiveUserID = $lmData['cm_iuid_fk'];
            $messageLiveTime = $lmData['cm_time'];
            $liveMessage = rawurldecode($lmData['cm_message']); // decode again from DB (if needed)

            $msgData = $iN->iN_GetUserDetails($messageLiveUserID);
            $msgUserName = $msgData['i_username'];
            $msgUserFullName = $msgData['i_user_fullname'];

            // Only return message block, but avoid double-append in JS
            echo '
            <div class="gElp9 flex_ tabing_non_justify eo2As cUq_' . iN_HelpSecure($messageID) . '" id="' . iN_HelpSecure($messageID) . '">
                <a href="' . iN_HelpSecure($msgUserName) . '">' . iN_HelpSecure($msgUserFullName) . '</a>' . $iN->sanitize_output($liveMessage, $base_url) . '
            </div>';
        }
    }
}
/*Add Sticker*/
	if ($type == 'message_sticker') {
		if (isset($_POST['id']) && isset($_POST['pi'])) {
			$stickerID = $iN->iN_Secure($_POST['id']);
			$chatID = $iN->iN_Secure($_POST['pi']);
			$getStickerUrlandID = $iN->iN_getSticker($stickerID);
			if ($getStickerUrlandID) {
				$data = array(
					'st_id' => $getStickerUrlandID['sticker_id'],
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			}
		}
	}
	if ($type == 'message_image_upload') {
		// Unified message/chat upload (removes provider-specific putObject/SpacesConnect)
		if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach ($_FILES['ciuploading']['name'] as $iname => $value) {
				$name = stripslashes($_FILES['ciuploading']['name'][$iname]);
				$size = $_FILES['ciuploading']['size'][$iname];
				$conID = $iN->iN_Secure($_POST['c']);
				$ext = strtolower(getExtension($name));
				$valid_formats = explode(',', $availableFileExtensions);
				$maxUploadSizeInBytes = $availableUploadFileSize * 1048576;
				if (!in_array($ext, $valid_formats)) { continue; }
				if (!($size > 0 && $size <= $maxUploadSizeInBytes)) { echo iN_HelpSecure($size); continue; }

				$microtime = microtime();
				$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
				$UploadedFileName = 'image_' . $removeMicrotime . '_' . $userID;
				$getFilename = $UploadedFileName . '.' . $ext;
				$tmp = $_FILES['ciuploading']['tmp_name'][$iname];
				$mimeType = $_FILES['ciuploading']['type'][$iname];
				$d = date('Y-m-d');

				if (preg_match('/video\/*/', $mimeType) || $mimeType === 'application/octet-stream') { $fileTypeIs = 'video'; }
				else if (preg_match('/image\/*/', $mimeType)) { $fileTypeIs = 'Image'; }
				else { $fileTypeIs = 'Image'; }

				if (!file_exists($uploadFile . $d)) { @mkdir($uploadFile . $d, 0755, true); }
				if (!file_exists($xImages . $d)) { @mkdir($xImages . $d, 0755, true); }
				if (!file_exists($xVideos . $d)) { @mkdir($xVideos . $d, 0755, true); }

				if (!move_uploaded_file($tmp, $uploadFile . $d . '/' . $getFilename)) { continue; }

				$pathFile = '';
				$pathXFile = '';

				if ($fileTypeIs === 'video') {
					if ($ffmpegStatus == '1') {
						require_once '../includes/convertToMp4Format.php';
						require_once '../includes/createVideoThumbnail.php';
						$sourceFs = $uploadFile . $d . '/' . $getFilename;
						$convertedFs = convertToMp4Format($ffmpegPath, $sourceFs, $uploadFile . $d, $UploadedFileName);
						if (!$convertedFs || !file_exists($convertedFs)) { $convertedFs = $sourceFs; }
						$thumbFs = createVideoThumbnailInSameDir($ffmpegPath, $convertedFs);
						$pathFile = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
						$pathXFile = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
						if (!file_exists('../uploads/xvideos/' . $d)) { @mkdir('../uploads/xvideos/' . $d, 0755, true); }
						$xVideoFirstPath = '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
						$safeCmd = $ffmpegPath . ' -ss 00:00:01 -i ' . escapeshellarg($convertedFs) . ' -c copy -t 00:00:04 ' . escapeshellarg($xVideoFirstPath) . ' 2>&1';
						shell_exec($safeCmd);
						$publishKeys = [];
						$mp4Key = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
						$xclipKey = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
						$thumbJpg = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
						$thumbPng = 'uploads/files/' . $d . '/' . $UploadedFileName . '.png';
						if (is_file('../' . $mp4Key)) { $publishKeys[] = $mp4Key; }
						if (is_file('../' . $xclipKey)) { $publishKeys[] = $xclipKey; }
						if (is_file('../' . $thumbJpg)) { $publishKeys[] = $thumbJpg; }
						if (is_file('../' . $thumbPng)) { $publishKeys[] = $thumbPng; }
						if ($publishKeys) { storage_publish_many($publishKeys, true, true); }
						$ext = 'mp4';
					} else {
						$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
						$pathXFile = $pathFile;
						storage_publish_many([$pathFile], true, true);
					}
				} else { // image
					$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
					$pathXFile = $pathFile;
					storage_publish_many([$pathFile], true, true);
				}

				$insertFileFromUploadTable = $iN->iN_INSERTUploadedMessageFiles($userID, $conID, $pathFile, $pathXFile, $ext);
				$getUploadedFileID = $iN->iN_GetUploadedMessageFilesIDs($userID, $pathFile);
				echo iN_HelpSecure($getUploadedFileID['upload_id']) . ',';
			}
			// Stop executing legacy message upload code below
			exit;
		}
	}

/*Load More Messages*/
	if ($type == 'moreMessage') {
		if (isset($_POST['ch']) && isset($_POST['last'])) {
			$chatID = $iN->iN_Secure($_POST['ch']);
			$lastMessageID = $iN->iN_Secure($_POST['last']);
			$conversationData = $iN->iN_GetChatMessages($userID, $chatID, $lastMessageID, $scrollLimit);
			include "../themes/$currentTheme/layouts/chat/loadMoreMessages.php";
		}
	}
/*Get new Message*/
	if ($type == 'getNewMessage') {
		if (isset($_POST['ci']) && isset($_POST['to']) && isset($_POST['lm'])) {
			$conversationID = $iN->iN_Secure($_POST['ci']);
			$toUser = $iN->iN_Secure($_POST['to']);
			$lastMessage = $iN->iN_Secure($_POST['lm']);
			$insertData = $iN->iN_GetUserNewMessage($userID, $conversationID, $toUser, $lastMessage);
			/**/
			if ($insertData) {
				$cMessageID = $insertData['con_id'];
				$cUserOne = $insertData['user_one'];
				$cUserTwo = $insertData['user_two'];
				$cMessage = $insertData['message'];
				$mSeenStatus = $insertData['seen_status'];
				$gifMoney = isset($insertData['gifMoney']) ? $insertData['gifMoney'] : NULL;
				$privateStatus = isset($insertData['private_status']) ? $insertData['private_status'] : NULL;
				$privatePrice = isset($insertData['private_price']) ? $insertData['private_price'] : NULL;
				$cStickerUrl = isset($insertData['sticker_url']) ? $insertData['sticker_url'] : NULL;
				$cGifUrl = isset($insertData['gifurl']) ? $insertData['gifurl'] : NULL;
				$cMessageTime = $insertData['time'];
				$ip = $iN->iN_GetIPAddress();
				$query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
				if ($query && $query['status'] == 'success') {
					date_default_timezone_set($query['timezone']);
				}
				$message_time = date("c", $cMessageTime);
				$convertMessageTime = strtotime($message_time);
				$netMessageHour = date('H:i', $convertMessageTime);
				$cFile = isset($insertData['file']) ? $insertData['file'] : NULL;
				$msgDots = '';
				$imStyle = '';
				$seenStatus = '';
				if ($cUserOne == $userID) {
					$mClass = 'me';
					$msgOwnerID = $cUserOne;
					$lastM = '';
					$timeStyle = 'msg_time_me';
					if (!empty($cFile)) {
						$imStyle = 'mmi_i';
					}
					$seenStatus = '<span class="seenStatus flex_ notSeen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
					if ($mSeenStatus == '1') {
						$seenStatus = '<span class="seenStatus flex_ seen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
					}
					if($gifMoney){
                        $SGifMoneyText = preg_replace( '/{.*?}/', $cMessage, $LANG['youSendGifMoney']);
                    }
				} else {
					$mClass = 'friend';
					$msgOwnerID = $cUserOne;
					$lastM = 'mm_' . $msgOwnerID;
					if (!empty($cFile)) {
						$imStyle = 'mmi_if';
					}
					if($gifMoney){
                        $msgOwnerFullName = $iN->iN_UserFullName($msgOwnerID);
                        $SGifMoneyText = $iN->iN_TextReaplacement($LANG['sendedGifMoney'],[$msgOwnerFullName , $cMessage]);
                    }
					$timeStyle = 'msg_time_fri';
				}
				$styleFor = '';
				if ($cStickerUrl) {
					$styleFor = 'msg_with_sticker';
					$cMessage = '<img class="mStick" src="' . $cStickerUrl . '">';
				}
				if ($cGifUrl) {
					$styleFor = 'msg_with_gif';
					$cMessage = '<img class="mGifM" src="' . $cGifUrl . '">';
				}
				$msgOwnerAvatar = $iN->iN_UserAvatar($msgOwnerID, $base_url);
				if($privatePrice && $privateStatus == 'closed' && $mClass != 'me'){
                    include "../themes/$currentTheme/layouts/chat/privateMessage.php";
				}else{
					include "../themes/$currentTheme/layouts/chat/newMessage.php";
				}
			}
			/**/
		}
	}
/*Send User Typing*/
	if ($type == 'utyping') {
		if (isset($_POST['ci']) && isset($_POST['to'])) {
			$conversationID = $iN->iN_Secure($_POST['ci']);
			$toUserID = $iN->iN_Secure($_POST['to']);
			$time = time() . '_' . $userID;
			$updateTypingStatus = $iN->iN_UpdateTypingStatus($userID, $conversationID, $time);
		}
	}
/*Check Typeing*/
	if ($type == 'typing') {
		if (isset($_POST['ci']) && isset($_POST['to']) && $_POST['ci'] !== '' && $_POST['to'] !== '' && !empty($_POST['ci']) && !empty($_POST['to'])) {
			$conversationID = $iN->iN_Secure($_POST['ci']);
			$toUser = $iN->iN_Secure($_POST['to']);
			$getTypingStatus = $iN->iN_GetTypingStatus($toUser, $conversationID);
			$messageSeenStatus = $iN->iN_CheckLastMessageSeenOrNot($conversationID, $toUser, $userID);
			$iN->iN_UpdateMessageSeenStatus($conversationID, $toUser, $userID);
			$beforeUnderscore = substr($getTypingStatus, 0, strpos($getTypingStatus, "_"));
			$afterUnderscore = substr($getTypingStatus, strrpos($getTypingStatus, '_') + 1);
			$ip = $iN->iN_GetIPAddress();
			$query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
			if ($query && $query['status'] == 'success') {
				date_default_timezone_set($query['timezone']);
			}
			$getToUserData = $iN->iN_GetUserDetails($toUser);
			$toUserLastLoginTime = $getToUserData['last_login_time'];
			$lastSeen = date("c", $toUserLastLoginTime);
			$OnlineStatus = date("c", $toUserLastLoginTime);
			/*10 Second Ago for Typing*/
			$SecondBefore = time() - 10;
			/*180 Second Ago for Online - Offline Status*/
			$oStatus = time() - 35;
			$timeStatus = '';
			if ($afterUnderscore != $userID) {
				if ($beforeUnderscore > $SecondBefore) {
					$timeStatus = $LANG['typing'];
				} else {
					if ($toUserLastLoginTime > $oStatus) {
						$timeStatus = $LANG['online'];
					} else {
						$timeStatus = $LANG['last_seen'] . date('H:i', strtotime($OnlineStatus));
					}
				}
			} else {
				$timeStatus = $LANG['last_seen'] . date('H:i', strtotime($OnlineStatus));
			}
			$data = array(
				'timeStatus' => $timeStatus,
				'seenStatus' => $messageSeenStatus,
			);
			$result = json_encode($data);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		}
	}
	if ($type == 'allPosts' || $type == 'moreexplore' || $type == 'premiums' || $type == 'morepremium' || $type == 'friends' || $type == 'morepurchased' || $type == 'purchasedpremiums' || $type == 'moreboostedposts' || $type == 'boostedposts' || $type == 'trendposts' || $type == 'moretrendposts') {
		$page = $type;
		include "../themes/$currentTheme/layouts/posts/htmlPosts.php";
	}
	if ($type == 'creators') {
		if (isset($_POST['last']) && isset($_POST['p'])) {
			$pageCreator = $iN->iN_Secure($_POST['p']);
			$lastPostID = $iN->iN_Secure($_POST['last']);
			include "../themes/$currentTheme/layouts/loadmore/moreCreator.php";
		}
	}
/*More Comment*/
	if ($type == 'moreComment') {
		if (isset($_POST['id'])) {
			$userPostID = $iN->iN_Secure($_POST['id']);
			$getUserComments = $iN->iN_GetPostComments($userPostID, 0);
			if ($getUserComments) {
				foreach ($getUserComments as $comment) {
					$commentID = $comment['com_id'];
					$commentedUserID = $comment['comment_uid_fk'];
					$Usercomment = $comment['comment'];
					$commentTime = isset($comment['comment_time']) ? $comment['comment_time'] : NULL;
					$corTime = date('Y-m-d H:i:s', $commentTime);
					$commentFile = isset($comment['comment_file']) ? $comment['comment_file'] : NULL;
					$stickerUrl = isset($comment['sticker_url']) ? $comment['sticker_url'] : NULL;
					$gifUrl = isset($comment['gif_url']) ? $comment['gif_url'] : NULL;
					$commentedUserIDFk = isset($comment['iuid']) ? $comment['iuid'] : NULL;
					$commentedUserName = isset($comment['i_username']) ? $comment['i_username'] : NULL;
					$commentedUserFullName = isset($comment['i_user_fullname']) ? $comment['i_user_fullname'] : NULL;
					$commentedUserAvatar = $iN->iN_UserAvatar($commentedUserID, $base_url);
					$commentedUserGender = isset($comment['user_gender']) ? $comment['user_gender'] : NULL;
					if ($commentedUserGender == 'male') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					} else if ($commentedUserGender == 'female') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					} else if ($commentedUserGender == 'couple') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					}
					$commentedUserLastLogin = isset($comment['last_login_time']) ? $comment['last_login_time'] : NULL;
					$commentedUserVerifyStatus = isset($comment['user_verified_status']) ? $comment['user_verified_status'] : NULL;
					$cuserVerifiedStatus = '';
					if ($commentedUserVerifyStatus == '1') {
						$cuserVerifiedStatus = '<div class="i_plus_comment_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
					}
					$commentLikeBtnClass = 'c_in_like';
					$commentLikeIcon = $iN->iN_SelectedMenuIcon('17');
					$commentReportStatus = $iN->iN_SelectedMenuIcon('32') . $LANG['report_comment'];
					if ($logedIn != 0) {
						$checkCommentLikedBefore = $iN->iN_CheckCommentLikedBefore($userID, $userPostID, $commentID);
						$checkCommentReportedBefore = $iN->iN_CheckCommentReportedBefore($userID, $commentID);
						if ($checkCommentLikedBefore == '1') {
							$commentLikeBtnClass = 'c_in_unlike';
							$commentLikeIcon = $iN->iN_SelectedMenuIcon('18');
						}
						if ($checkCommentReportedBefore == '1') {
							$commentReportStatus = $iN->iN_SelectedMenuIcon('32') . $LANG['unreport'];
						}
					}
					$stickerComment = '';
					$gifComment = '';
					if ($stickerUrl) {
						$stickerComment = '<div class="comment_file"><img src="' . $stickerUrl . '"></div>';
					}
					if ($gifUrl) {
						$gifComment = '<div class="comment_gif_file"><img src="' . $gifUrl . '"></div>';
					}
					$checkUserIsCreator = $iN->iN_CheckUserIsCreator($commentedUserID);
					$cUType = '';
					if($checkUserIsCreator){
						$cUType = '<div class="i_plus_public" id="ipublic_'.$commentedUserID.'">'.$iN->iN_SelectedMenuIcon('9').'</div>';
					}
					include "../themes/$currentTheme/layouts/posts/comments.php";
				}
			}
		}
	}
	if ($type == 'searchCreator') {
		if (isset($_POST['s'])) {
			$searchValue = $iN->iN_Secure($_POST['s']);
			$searchValueFromData = $iN->iN_GetSearchResult($iN->iN_Secure($searchValue), $showingNumberOfPost, $whicUsers);
			include "../themes/$currentTheme/layouts/header/searchResults.php";
		}
	}
/*Create new Conversation*/
	if ($type == 'newMessageMe') {
		if (isset($_POST['user'])) {
			$iuID = $iN->iN_Secure($_POST['user']);
			$checkUserExist = $iN->iN_CheckUserExist($iuID);
			if ($checkUserExist) {
				$getToUserData = $iN->iN_GetUserDetails($iuID);
				$f_userfullname = $getToUserData['i_user_fullname'];
				$f_userAvatar = $iN->iN_UserAvatar($iuID, $base_url);
				$checkConversationStartedBeforeBetweenTheseUsers = $iN->iN_CheckConversationStartedBeforeBetweenUsers($userID, $iuID);
				if (empty($checkConversationStartedBeforeBetweenTheseUsers) || $checkConversationStartedBeforeBetweenTheseUsers = '' || !isset($checkConversationStartedBeforeBetweenTheseUsers)) {
					include "../themes/$currentTheme/layouts/popup_alerts/createMessage.php";
				}
			}
		}
	}
/*Createa New First Message Between Two User*/
	if ($type == 'newfirstMessage') {
		if (isset($_POST['u']) && isset($_POST['fm'])) {
			$user = $iN->iN_Secure($_POST['u']);
			$firstMessage = $iN->iN_Secure($_POST['fm']);
			if (empty($firstMessage) || $firstMessage == '' || !isset($firstMessage) || strlen(trim($firstMessage)) == 0) {
				exit('404');
			}
			$insertNewMessageAndCreateConversation = $iN->iN_CreateConverationAndInsertFirstMessage($userID, $user, $iN->iN_Secure($firstMessage));
			if ($insertNewMessageAndCreateConversation) {
				echo iN_HelpSecure($base_url) . 'chat?chat_width=' . $insertNewMessageAndCreateConversation;
				$userDeviceKey = $iN->iN_GetuserDetails($user);
				$toUserName = $userDeviceKey['i_username'];
				$oneSignalUserDeviceKey = isset($userDeviceKey['device_key']) ? $userDeviceKey['device_key'] : NULL;
				$msgTitle = $iN->iN_Secure($LANG['you_have_a_new_message']);
				$msgBody = $iN->iN_Secure($LANG['click_to_continue_conversation']);
				$URL = iN_HelpSecure($base_url) . 'chat?chat_width=' . $insertNewMessageAndCreateConversation;
				if($oneSignalUserDeviceKey){
				  $iN->iN_OneSignalPushNotificationSend($msgBody, $msgTitle, $url, $oneSignalUserDeviceKey, $oneSignalApi, $oneSignalRestApi);
				}
			} else {
				echo '404';
			}
		}
	}
/*Update Dark to Light or Light to Dark*/
	if ($type == 'updateTheme') {
		if (isset($_POST['theme']) && in_array($_POST['theme'], $themes)) {
			$uTheme = $iN->iN_Secure($_POST['theme']);
			$updateTheme = $iN->iN_UpdateUserTheme($userID, $uTheme);
			if ($updateTheme) {
				echo '200';
			} else {
				echo '404';
			}
		}
	}
/*Get Fixed Mobile Footer Menu*/
	if ($type == 'fixedMenu') {
		include "../themes/$currentTheme/layouts/widgets/mobileFixedMenu.php";
	}
/*Delete Message*/
	if ($type == 'deleteMessage') {
		if (isset($_POST['id']) && isset($_POST['cid'])) {
			$messageID = $iN->iN_Secure($_POST['id']);
			$conversationID = $iN->iN_Secure($_POST['cid']);
			$deleteMessage = $iN->iN_DeleteMessageFromData($userID, $messageID, $conversationID);
			if ($deleteMessage) {
				echo '200';
			} else {
				echo '404';
			}
		}
	}
/*Delete Conversion*/
	if ($type == 'deleteConversation') {
		if (isset($_POST['id']) && isset($_POST['cid'])) {
			$messageID = $iN->iN_Secure($_POST['id']);
			$conversationID = $iN->iN_Secure($_POST['cid']);
			$deleteMessage = $iN->iN_DeleteConversationFromData($userID, $conversationID);
			if ($deleteMessage) {
				echo '200';
			} else {
				echo '404';
			}
		}
	}
/*Search User From Chat*/
	if ($type == 'searchUser') {
		if (isset($_POST['key'])) {
			$sKey = $iN->iN_Secure($_POST['key']);
			$searchUser = $iN->iN_SearchChatUsers($userID, $iN->iN_Secure($sKey));
			if ($searchUser) {
				foreach ($searchUser as $sResult) {
					$resultUserID = $sResult['iuid'];
					$resultUserName = $sResult['i_username'];
					$resultUserFullName = $sResult['i_user_fullname'];
					$profileUrl = $base_url . $resultUserName;
					$resultUserAvatar = $iN->iN_UserAvatar($resultUserID, $base_url);
					include "../themes/$currentTheme/layouts/chat/chatSearch.php";
				}
			}
		}
	}
/*Hide Notification*/
	if ($type == 'hideNotification') {
		if (isset($_POST['id'])) {
			$hideID = $iN->iN_Secure($_POST['id']);
			$hideNot = $iN->iN_HideNotification($userID, $hideID);
			if ($hideNot) {
				echo '200';
			} else {
				echo '404';
			}
		}
	}
/*UN Block User*/
	if ($type == 'unblock') {
		if (isset($_POST['id']) && isset($_POST['u'])) {
			$unBlockID = $iN->iN_Secure($_POST['id']);
			$unBlockUserID = $iN->iN_Secure($_POST['u']);
			$unBlock = $iN->iN_UnBlockUser($userID, $unBlockID, $unBlockUserID);
			if ($unBlock) {
				echo '200';
			} else {
				echo '404';
			}
		}
	}
/*Edit May Page*/
	if ($type == 'editMyPass') {
			$currentPassword = isset($_POST['crn_password']) ? (string)$_POST['crn_password'] : '';
			$newPassword = isset($_POST['nw_password']) ? (string)$_POST['nw_password'] : '';
			$confirmNewPassword = isset($_POST['confirm_pass']) ? (string)$_POST['confirm_pass'] : '';
			if ($currentPassword !== '') {
				$userCurrentPass = $iN->iN_GetUserDetails($userID);
				$storedHash = isset($userCurrentPass['i_password']) ? (string)$userCurrentPass['i_password'] : '';
				if (preg_match('/\s/', $currentPassword) || preg_match('/\s/', $newPassword) || preg_match('/\s/', $confirmNewPassword)) {
					exit('6');
				}
				$isValidCurrent = false;
				if ($storedHash !== '') {
					if (password_verify($currentPassword, $storedHash)) {
						$isValidCurrent = true;
					} else {
						$legacyHash = sha1(md5($currentPassword));
						$legacySanitizedHash = sha1(md5($iN->iN_Secure($currentPassword)));
						if (hash_equals($storedHash, $legacyHash) || hash_equals($storedHash, $legacySanitizedHash)) {
							$isValidCurrent = true;
						}
					}
				}
				if (!$isValidCurrent) {
					exit('1');
				}
				if (strlen($newPassword) < 6 || strlen($confirmNewPassword) < 6 || strlen($currentPassword) < 6) {
					exit('5');
				}
				if ($newPassword === '' || $confirmNewPassword === '') {
					exit('4');
				}
				if ($newPassword !== $confirmNewPassword) {
					exit('2');
				}
				$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
				$updateNewPassword = $iN->iN_UpdatePassword($userID, $newPasswordHash);
				if ($updateNewPassword) {
					echo iN_HelpSecure($base_url) . 'logout.php';
				} else {
					exit('404');
				}
			} else {
				exit('3');
			}
		}
/*Update Preferences*/
	if ($type == 'p_preferences') {
		if (isset($_POST['notit']) && isset($_POST['sType'])) {
			$setValue = $iN->iN_Secure($_POST['notit']);
			$setType = $iN->iN_Secure($_POST['sType']);
			if ($setType == 'email_not') {
				$updateEmailStatus = $iN->iN_UpdateEmailNotificationStatus($userID, $setValue);
				if ($updateEmailStatus) {
					echo '200';
				} else {
					echo '404';
				}
			} else if ($setType == 'message_not') {
				$updateMessageStatus = $iN->iN_UpdateMessageSendStatus($userID, $setValue);
				if ($updateMessageStatus) {
					echo '200';
				} else {
					echo '404';
				}
			} else if ($setType == 'show_hide_profile') {
				$updateShowHideProfile = $iN->iN_UpdateShowHidePostsStatus($userID, $setValue);
				if ($updateShowHideProfile) {
					echo '200';
				} else {
					echo '404';
				}
			} else if($setType == 'who_send_message_not'){
				$updateWhoCanSendMessage = $iN->iN_UpdateWhoCanSendYouAMessage($userID, $setValue);
				if ($updateWhoCanSendMessage) {
					echo '200';
				} else {
					echo '404';
				}
			}
		}
	}
/*Call Paid Live Streaming Box*/
	if ($type == 'paidLive') {
		$liveStreamNotForNonCreators = '<div class="ll_live_not flex_ alignItem">' . html_entity_decode($iN->iN_SelectedMenuIcon('32')) . ' ' . iN_HelpSecure($LANG['only_creators_']) . '</div>';
		if ($certificationStatus == '2' && $validationStatus == '2' && $conditionStatus == '2') {
			include "../themes/$currentTheme/layouts/popup_alerts/createaPaidLiveStreaming.php";
		} else {
			$currentTime = time();
			$finishTime = $currentTime + 60 * $freeLiveTime;
			$l_Time = $iN->iN_GetLastLiveFinishTime($userID);
			include "../themes/$currentTheme/layouts/popup_alerts/createaFreeLiveStreaming.php";
		}
	}
/*Call Free Live Streaming Box*/
	if ($type == 'freeLive') {
		$currentTime = time();
		$finishTime = $currentTime + 60 * $freeLiveTime;
		$l_Time = $iN->iN_GetLastLiveFinishTime($userID);
		$liveStreamNotForNonCreators = '';
		include "../themes/$currentTheme/layouts/popup_alerts/createaFreeLiveStreaming.php";
	}
/*Create a Free Live Streaming*/
	if ($type == 'createFreeLiveStream') {
		if (isset($_POST['lTitle']) && !empty($_POST['lTitle'])) {
			$liveStreamingTitle = $iN->iN_Secure($_POST['lTitle']);
			$rand = rand(1111111, 9999999);
			$channelName = "stream_" . $userID . "_" . $rand;
			if (strlen($liveStreamingTitle) < 5 || strlen($liveStreamingTitle) > 32) {
				$data = array(
					'status' => '4',
					'start' => '',
				);
				$result = json_encode($data, JSON_UNESCAPED_UNICODE);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				exit();
			}
			$createFreeLiveStreaming = $iN->iN_CreateAFreeLiveStreaming($userID, $liveStreamingTitle, $freeLiveTime, $channelName);
			if ($createFreeLiveStreaming) {
				if ($s3Status == 1) {
					//$rect = $iN->iN_StartCloudRecording(1, $s3Region, $s3Bucket, $s3Key, $s3SecretKey, $streamingName, $uid, $liveID, $agoraAppID, $agoraCustomerID, $agoraCertificate);
				}
				$data = array(
					'status' => '200',
					'start' => $base_url . 'live/' . $userName,
				);
				$result = json_encode($data, JSON_UNESCAPED_UNICODE);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			} else {
				$data = array(
					'status' => '404',
					'start' => '',
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				exit();
			}
		} else {
			$data = array(
				'status' => 'require',
				'start' => '',
			);
			$result = json_encode($data);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			exit();
		}
	}
	if ($type == 'l_like') {
		if (isset($_POST['post'])) {
			$postID = $iN->iN_Secure($_POST['post']);
			$likePost = $iN->iN_LiveLike($userID, $postID);
			$status = 'lin_like';
			$pLike = $iN->iN_SelectedMenuIcon('17');
			if ($likePost) {
				$status = 'lin_unlike';
				$pLike = $iN->iN_SelectedMenuIcon('18');
			}
			$likeSum = $iN->iN_TotalLiveLiked($postID);
			if ($likeSum == 0) {
				$likeSum = '';
			} else {
				$likeSum = $likeSum;
			}
			$data = array(
				'status' => $status,
				'like' => $pLike,
				'likeCount' => $likeSum,
			);
			$result = json_encode($data);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		}
	}
/*Create a Free Live Streaming*/
	if ($type == 'createPaidLiveStream') {
		if (isset($_POST['lTitle']) && !empty($_POST['lTitle']) && isset($_POST['pointfee']) && !empty($_POST['pointfee'])) {
			$liveStreamingTitle = $iN->iN_Secure($_POST['lTitle']);
			$liveStreamFee = $iN->iN_Secure($_POST['pointfee']);
			$rand = rand(1111111, 9999999);
			$channelName = "stream_" . $userID . "_" . $rand;
			if (empty($liveStreamFee) || $liveStreamFee < $minimumLiveStreamingFee) {
				$data = array(
					'status' => 'point',
					'start' => '',
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				exit();
			}
			if ($certificationStatus == '2' && $validationStatus == '2' && $conditionStatus == '2') {
				$createPaidLiveStreaming = $iN->iN_CreateAPaidLiveStreaming($userID, $liveStreamingTitle, $freeLiveTime, $channelName, $liveStreamFee);
				if ($createPaidLiveStreaming) {
					$data = array(
						'status' => '200',
						'start' => $base_url . 'live/' . $userName,
					);
					$result = json_encode($data, JSON_UNESCAPED_UNICODE);
					echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				} else {
					$data = array(
						'status' => '404',
						'start' => '',
					);
					$result = json_encode($data);
					echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
					exit();
				}
			} else {
				$data = array(
					'status' => '404',
					'start' => '',
				);
				$result = json_encode($data);
				echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				exit();
			}
		} else {
			$data = array(
				'status' => 'require',
				'start' => '',
			);
			$result = json_encode($data);
			echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
			exit();
		}
	}
/*Purchase Post*/
	if ($type == 'goWalletLive') {
		if (isset($_POST['p']) && isset($_POST['p'])) {
			$purchaseLiveStreamID = $iN->iN_Secure($_POST['p']);
			$checkLiveID = $iN->iN_CheckLiveIDExist($purchaseLiveStreamID);
			if ($checkLiveID) {
				$liveDetails = $iN->iN_GetLiveStreamingDetailsByID($purchaseLiveStreamID);
				$liveID = $liveDetails['live_id'];
				$liveCreatorWantedCredit = $liveDetails['live_credit'];
				$liveCreator = $liveDetails['live_uid_fk'];
				$liveCreatorDetail = $iN->iN_GetUserDetails($liveCreator);
				$liveCreatorUserName = $liveCreatorDetail['i_username'];

				$translatePointToMoney = $liveCreatorWantedCredit * $onePointEqual;
				$adminEarning = $translatePointToMoney * ($adminFee / 100);
				$userEarning = $translatePointToMoney - $adminEarning;

				if ($userCurrentPoints >= $liveCreatorWantedCredit && $userID != $liveCreator) {
					$buyLiveStream = $iN->iN_BuyLiveStreaming($userID, $liveCreator, $liveID, $translatePointToMoney, $adminEarning, $userEarning, $adminFee, $liveCreatorWantedCredit);
					if ($buyLiveStream) {
						echo iN_HelpSecure($base_url) . 'live/' . $liveCreatorUserName;
					} else {
						echo $LANG['something_wrong'];
					}
				} else {
					echo iN_HelpSecure($base_url) . 'purchase/purchase_point';
				}
			}
		}
	}
/*More Paid Live Streamins or Free Paid Live Streamins*/
	if ($type == 'paid' || $type == 'free') {
		if (isset($_POST['last'])) {
			$liveListType = $type;
			include "../themes/$currentTheme/layouts/live/live_list.php";
		}
	}
	if ($type == 'pLivePurchase') {
		if (isset($_POST['purchase']) && $_POST['purchase'] != '' && !empty($_POST['purchase'])) {
			$liveID = $iN->iN_Secure($_POST['purchase']);
			$checkliveExist = $iN->iN_CheckLiveIDExist($liveID);
			if ($checkliveExist) {
				$liData = $iN->iN_GetLiveStreamingDetailsByID($liveID);
				$liveCreatorID = $liData['live_uid_fk'];
				$liveCreatorAvatar = $iN->iN_UserAvatar($liveCreatorID, $base_url);
				$liveCredit = isset($liData['live_credit']) ? $liData['live_credit'] : NULL;
				if ($userID != $liveCreatorID) {
					include "../themes/$currentTheme/layouts/popup_alerts/purchaseLiveStream.php";
				}
			}
		}
	}
	if ($type == 'unSub') {
		if (isset($_POST['u']) && !empty($_POST['u'])) {
			$ui = $iN->iN_Secure($_POST['u']);
			$checkUserExist = $iN->iN_CheckUserExist($ui);
			$friendsStatus = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $ui);
			if ($friendsStatus == 'subscriber') {
				include "../themes/$currentTheme/layouts/popup_alerts/sureUnSubscribe.php";
			}
		}
	}
	if ($type == 'unSubscribe') {
		if (isset($_POST['id'])) {
			$uID = $iN->iN_Secure($_POST['id']);
			$checkUserExist = $iN->iN_CheckUserExist($uID);
			if ($checkUserExist) {
				if ($uID != $userID) {
					$friendsStatus = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $uID);
					$status = '404';
					$redirect = $base_url . 'settings?tab=subscriptions';
					if ($friendsStatus == 'subscriber') {
						if($subscriptionType == '1'){
							\Stripe\Stripe::setApiKey($stripeKey);
							$getSubsData = $iN->iN_GetSubscribeID($userID, $uID);
							$paymentSubscriptionID = $getSubsData['payment_subscription_id'];
							$subscriptionID = $getSubsData['subscription_id'];
							$iN->iN_UpdateSubscriptionStatus($subscriptionID);

							$subscription = \Stripe\Subscription::retrieve($paymentSubscriptionID);
							$subscription->cancel();
							$iN->iN_UnSubscriberUser($userID, $uID,$unSubscribeStyle);
							$status = '200';
						}else if($subscriptionType == '3'){
                            include_once("../includes/authorizeCancelSubs.php");
							$getSubsData = $iN->iN_GetSubscribeID($userID, $uID);
							$paymentSubscriptionID = $getSubsData['payment_subscription_id'];
							$subscriptionID = $getSubsData['subscription_id'];
							$iN->iN_UpdateSubscriptionStatus($subscriptionID);
							$iN->iN_UnSubscriberUser($userID, $uID,$unSubscribeStyle);
							if(!defined('DONT_RUN_SAMPLES'))
                            cancelSubscription($paymentSubscriptionID,$autName, $autKey);
						}
					}
					$data = array(
						'status' => $status,
						'redirect' => $redirect,
					);
					$result = json_encode($data);
					echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				}
			}
		}
	}
	if ($type == 'unSubP') {
		if (isset($_POST['u']) && !empty($_POST['u'])) {
			$ui = $iN->iN_Secure($_POST['u']);
			$checkUserExist = $iN->iN_CheckUserExist($ui);
			$friendsStatus = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $ui);
			if ($friendsStatus == 'subscriber') {
				include "../themes/$currentTheme/layouts/popup_alerts/sureUnSubscribePoint.php";
			}
		}
	}
	if ($type == 'unSubscribePoint') {
		if (isset($_POST['id'])) {
			$uID = $iN->iN_Secure($_POST['id']);
			$checkUserExist = $iN->iN_CheckUserExist($uID);
			if ($checkUserExist) {
				if ($uID != $userID) {
					$friendsStatus = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $uID);
					$status = '404';
					$redirect = $base_url . 'settings?tab=subscriptions';
					if ($friendsStatus == 'subscriber') {
						$getSubsData = $iN->iN_GetSubscribeID($userID, $uID);
						$subscriptionID = $getSubsData['subscription_id'];
						$iN->iN_UpdateSubscriptionStatus($subscriptionID);
						$iN->iN_UnSubscriberUser($userID, $uID,$unSubscribeStyle);
						$status = '200';
					}
					$data = array(
						'status' => $status,
						'redirect' => $redirect,
					);
					$result = json_encode($data);
					echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
				}
			}
		}
	}
	/*Finish Live Streaming*/
	if($type == 'finishLive'){
      if(isset($_POST['lid']) && !empty($_POST['lid']) && $_POST['lid'] != ''){
         $liveID = $iN->iN_Secure($_POST['lid']);
		 $finishLiveStreaming = $iN->iN_FinishLiveStreaming($userID, $liveID);
		 if($finishLiveStreaming){
             echo 'finished';
		 }
	  }
	}
	/*Block Country*/
	if($type == 'bCountry'){
      if(isset($_POST['c']) && array_key_exists($_POST['c'],$COUNTRIES)){
         $blockingCountryCode = $iN->iN_Secure($_POST['c']);
		 $checkCountryCodeBlockedBefore = $iN->iN_CheckCountryBlocked($userID, $blockingCountryCode);
		 if(!$checkCountryCodeBlockedBefore){
            $insertCountryCodeInBlockedList = $iN->iN_InsertCountryInBlockList($userID, $iN->iN_Secure($blockingCountryCode));
			if($insertCountryCodeInBlockedList){
              echo '1';
			}
		 }else{
			$removeCountryCodeInBlockedList = $iN->iN_RemoveCountryInBlockList($userID, $iN->iN_Secure($blockingCountryCode));
			if($removeCountryCodeInBlockedList){
              echo '0';
			}
		 }
	  }
	}
	/*Open Tip Box*/
	if($type == 'p_tips'){
		if(isset($_POST['tip_u']) && !empty($_POST['tip_u']) && $_POST['tip_u'] !== ''){
			$tipingUserID = $iN->iN_Secure($_POST['tip_u']);
			$tipPostID = $iN->iN_Secure($_POST['tpid']);
            $tipingUserDetails = $iN->iN_GetUserDetails($tipingUserID);
			$f_userfullname = $tipingUserDetails['i_user_fullname'];
			include "../themes/$currentTheme/layouts/popup_alerts/sendTipPoint.php";
		}
	}
	/*Send Tip*/
	if($type == 'p_sendTip'){
      if(isset($_POST['tip_u']) && isset($_POST['tipVal']) && $_POST['tip_u'] != '' &&  $_POST['tipVal'] != '' && !empty($_POST['tip_u']) && !empty($_POST['tipVal'])){
         $tiSendingUserID = $iN->iN_Secure($_POST['tip_u']);
		 $tipAmount = $iN->iN_Secure($_POST['tipVal']);
		 $tipPostID = $iN->iN_Secure($_POST['tpid']);
		 $redirect = '';
		 $emountnot = '';
		 $status = '';
		 if($tipAmount < $minimumTipAmount){
            $emountnot = 'notEnough';
		 }else{
			if ($userCurrentPoints >= $tipAmount && $userID != $tiSendingUserID) {

				$netUserEarning = $tipAmount * $onePointEqual;
                $adminEarning = ($adminFee * $netUserEarning) / 100;
				$userNetEarning = $netUserEarning - $adminEarning;

				$UpdateUsersWallet = $iN->iN_UpdateUsersWallets($userID, $tiSendingUserID, $tipAmount, $netUserEarning,$adminFee, $adminEarning, $userNetEarning);
				if($UpdateUsersWallet){
                   $status = 'ok';
				}else{
				   $status = '404';
				}
			 }else{
				$status = '';
				$emountnot = 'notEnouhCredit';
				$redirect =  iN_HelpSecure($base_url) . 'purchase/purchase_point';
			 }
		 }
		 $data = array(
			'status' => $status,
			'redirect' => $redirect,
			'enamount' => $emountnot
		 );
		 $result = json_encode($data);
		 echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		 if($status == 'ok'){
			$userDeviceKey = $iN->iN_GetuserDetails($tiSendingUserID);
			$toUserName = $userDeviceKey['i_username'];
			$oneSignalUserDeviceKey = isset($userDeviceKey['device_key']) ? $userDeviceKey['device_key'] : NULL;
			$msgBody = $iN->iN_Secure($LANG['send_you_a_tip']);
			$msgTitle = $iN->iN_Secure($LANG['tip_earning']).$currencys[$defaultCurrency]. $netUserEarning;
			$URL = $base_url.'settings?tab=dashboard';
			if($oneSignalUserDeviceKey){
			  $iN->iN_OneSignalPushNotificationSend($msgBody, $msgTitle, $url, $oneSignalUserDeviceKey, $oneSignalApi, $oneSignalRestApi);
			}
		 }
	  }
	}
	/*Coin Payment*/
	if($type == 'cop'){
      if(isset($_POST['p']) && !empty($_POST['p']) && $_POST['p'] != ''){
         $pointTypeID = $iN->iN_Secure($_POST['p']);
		 $planData = $iN->GetPlanDetails($pointTypeID);
		 $planAmount = isset($planData['amount']) ? $planData['amount'] : NULL;
		 $planPoint = isset($planData['plan_amount']) ? $planData['plan_amount'] : NULL;
		 if($planAmount){
			require_once('../includes/coinPayment/vendor/autoload.php');
            $currency1 = $defaultCurrency;
			$currency2 = $coinPaymentCryptoCurrency;
			try {
				$cps_api = new CoinpaymentsAPI($coinPaymentPrivateKey, $coinPaymentPublicKey, 'json');
				$information = $cps_api->GetBasicInfo();
				$ipn_url = $base_url.'purchase/purchase_point';
				$cancelUrl = $base_url.'purchase/purchase_point';
				$payBtc = $cps_api->CreateSimpleTransactionWithConversion($planAmount, $currency1, $currency2, $userEmail, $ipn_url, $cancelUrl);
				$txnID = isset($payBtc['result']['txn_id']) ? $payBtc['result']['txn_id'] : NULL;
				$time = time();
				if($txnID){
					DB::exec("INSERT INTO i_user_payments(payer_iuid_fk,order_key,payment_type,payment_option,payment_time,payment_status,credit_plan_id) VALUES (?,?,?,?,?,?,?)",
					    [(int)$userID, (string)$txnID, 'point', 'coinpayment', (int)$time, 'pending', (int)$pointTypeID]
					);
				}else{
					exit($LANG['check_coinpayment_settings']);
				}

			} catch (Exception $e) {
				echo str_replace('{error}', $e->getMessage(), $LANG['generic_error_prefixed']);
				exit();
			}
			if ($information['error'] == 'ok') {
				$redirectUrl = $payBtc['result']['checkout_url'];
				$status = '200';
			}else{
				$redirectUrl = '';
				$status = '404';
			}
			$data = array(
				'status' => $status,
				'redirect' => $redirectUrl
			 );
			 $result = json_encode($data);
			 echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
		 }
	  }
	}
	if ($type == 'subscribeMeAut') {
		if (isset($_POST['u']) && isset($_POST['pl']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['card'])) {
			$iuID = $iN->iN_Secure($_POST['u']);
			$planID = $iN->iN_Secure($_POST['pl']);
			$subscriberName = $iN->iN_Secure($_POST['name']);
			$subscriberEmail = $iN->iN_Secure($_POST['email']);
			$creditCardNumber = $iN->iN_Secure($_POST['card']);
			$expMonth = $iN->iN_Secure($_POST['exm']);
			$expYear = $iN->iN_Secure($_POST['exy']);
			$CardCCV = $iN->iN_Secure($_POST['cccv']);
			$planDetails = $iN->iN_CheckPlanExist($planID, $iuID);
			$expiredData = $expYear.'-'.$expMonth;
			$payment_id = $statusMsg = $api_error = '';
			if ($planDetails) {
				$planType = $planDetails['plan_type'];
				$amount = $planDetails['amount'];
				$planCurrency = $autHorizePaymentCurrency;
				$adminEarning = ($adminFee * $amount) / 100;
				$userNetEarning = $amount - $adminEarning;
				$subscriptionCompleted = $LANG['subscription_description_authorize'];
				$payment_Type = 'authorizenet';
				$planIntervalCount = '1';
				if ($planType == 'weekly') {
					$planName = 'Weekly Subscription';
					$planInterval = 'week';
					$intervalLength = '7';
					$interval_dmy = 'days';
					$plancreated = date("Y-m-d H:i:s");
					$current_period_start = date("Y-m-d H:i:s");
				    $current_period_end = date("Y-m-d H:i:s", strtotime('+7 days'));
				} else if ($planType == 'monthly') {
					$planName = 'Monthly Subscription';
					$planInterval = 'month';
					$intervalLength = '30';
					$interval_dmy = 'days';
					$plancreated = date("Y-m-d H:i:s");
					$current_period_start = date("Y-m-d H:i:s");
				    $current_period_end = date("Y-m-d H:i:s", strtotime('+1 month'));
				} else if ($planType == 'yearly') {
					$planName = 'Yearly Subscription';
					$planInterval = 'year';
					$intervalLength = '365';
					$interval_dmy = 'days';
					$plancreated = date("Y-m-d H:i:s");
					$current_period_start = date("Y-m-d H:i:s");
				    $current_period_end = date("Y-m-d H:i:s", strtotime('+1 year'));
				}

define("AUTHORIZENET_LOG_FILE", "phplog");

function createSubscription($userID,$iuID,$payment_Type,$planID,$planCurrency, $planInterval,$planIntervalCount,$subscriberEmail,$autName, $autKey, $subscriberName,$userName,$intervalLength,$interval_dmy,$creditCardNumber,$expiredData,$amount,$plancreated,$current_period_start,$current_period_end,$adminEarning,$userNetEarning,$subscriptionCompleted)
{
	global $iN;
	/* Create a merchantAuthenticationType object with authentication details
	retrieved from the constants file */
	$merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	$merchantAuthentication->setName($autName);
	$merchantAuthentication->setTransactionKey($autKey);

	// Set the transaction's refId
	$refId = 'ref' . time();

	// Subscription Type Info
	$subscription = new AnetAPI\ARBSubscriptionType();
	$subscription->setName("Sample Subscription");

	$interval = new AnetAPI\PaymentScheduleType\IntervalAType();
	$interval->setLength($intervalLength);
	$interval->setUnit($interval_dmy);

	$paymentSchedule = new AnetAPI\PaymentScheduleType();
	$paymentSchedule->setInterval($interval);
	$paymentSchedule->setStartDate(new DateTime('now'));
	$paymentSchedule->setTotalOccurrences("12");
	$paymentSchedule->setTrialOccurrences("1");

	$subscription->setPaymentSchedule($paymentSchedule);
	$subscription->setAmount($amount);
	$subscription->setTrialAmount("0.00");

	$creditCard = new AnetAPI\CreditCardType();

	$creditCard->setCardNumber($creditCardNumber);
	$creditCard->setExpirationDate($expiredData);

	$payment = new AnetAPI\PaymentType();
	$payment->setCreditCard($creditCard);
	$subscription->setPayment($payment);

	$order = new AnetAPI\OrderType();
	$order->setInvoiceNumber("1234354");
	$order->setDescription($subscriptionCompleted);
	$subscription->setOrder($order);

	$billTo = new AnetAPI\NameAndAddressType();
	$billTo->setFirstName($subscriberName);
	$billTo->setLastName($userName);

	$subscription->setBillTo($billTo);

	$request = new AnetAPI\ARBCreateSubscriptionRequest();
	$request->setmerchantAuthentication($merchantAuthentication);
	$request->setRefId($refId);
	$request->setSubscription($subscription);
	$controller = new AnetController\ARBCreateSubscriptionController($request);

	$response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

	if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") )
	{
		$custID = $response->getSubscriptionId();
		$planStatus = 'active';
		$insertSubscription = $iN->iN_InsertUserSubscription($userID, $iuID, $payment_Type, $subscriberName, $custID, $custID, $planID, $amount, $adminEarning, $userNetEarning, $planCurrency, $planInterval, $planIntervalCount, $subscriberEmail, $plancreated, $current_period_start, $current_period_end, $planStatus);

		 if ($insertSubscription) {
			echo '200';
		} else {
			echo iN_HelpSecure($LANG['contact_site_administrator']);
		}
	}
	else
	{
	echo iN_HelpSecure($LANG['error_invalid_response']) . "\n";
		$errorMessages = $response->getMessages()->getMessage();
	echo iN_HelpSecure($LANG['response_prefix']) . $errorMessages[0]->getText() . "\n";
	}

	return $response;
}

if(!defined('DONT_RUN_SAMPLES'))
	createSubscription($userID,$iuID,$payment_Type,$planID,$planCurrency, $planInterval,$planIntervalCount,$subscriberEmail,$autName, $autKey,$subscriberName,$userName,$intervalLength,$interval_dmy,$creditCardNumber,$expiredData,$amount,$plancreated,$current_period_start,$current_period_end,$adminEarning,$userNetEarning,$subscriptionCompleted);
    }
 }
}
/*Send Tip*/
if($type == 'p_sendGift'){
	if(isset($_POST['tip_u']) && isset($_POST['tipTyp']) && isset($_POST['lid'])){
	   $giftLiveOwnerUserID = $iN->iN_Secure($_POST['tip_u']);
	   $giftTypeID = $iN->iN_Secure($_POST['tipTyp']);
	   $cLiveID = $iN->iN_Secure($_POST['lid']);
	   if($iN->CheckLivePlanExist($giftTypeID) == '1' && $iN->iN_CheckLiveIDExist($cLiveID) == '1'){
	   $getLiveGiftDataFromID = $iN->GetLivePlanDetails($giftTypeID);
	   $liveWantedCoin = isset($getLiveGiftDataFromID['gift_point']) ? $getLiveGiftDataFromID['gift_point'] : NULL;
	   $liveWantedMoney = isset($getLiveGiftDataFromID['gift_money_equal']) ? $getLiveGiftDataFromID['gift_money_equal'] : NULL;
	   $liveAnimationImage = isset($getLiveGiftDataFromID['gift_money_animation_image']) ? $getLiveGiftDataFromID['gift_money_animation_image'] : NULL;
	   $redirect = '';
	   $emountnot = '';
	   $status = '';
	   $liveGiftAnimationUrl = '';
		if ($userCurrentPoints >= $liveWantedCoin && $userID != $giftLiveOwnerUserID) {
			$translatePointToMoney = $liveWantedMoney;
			$adminEarning = $translatePointToMoney * ($adminFee / 100);
			$userEarning = $translatePointToMoney - $adminEarning;
			$liveGiftAnimation = $base_url.$liveAnimationImage;
			$liveGiftAnimationUrl = '<div class="live_animation_wrapper"><div class="live_an_img"><img src="'.$liveGiftAnimation.'"></div></div>';
			$UpdateUsersWallet = $iN->iN_UpdateUsersWalletsForLiveGift($userID,$cLiveID, $giftLiveOwnerUserID, $giftTypeID, $liveWantedCoin,$adminEarning, $userEarning, $liveWantedMoney);
			$liveOwnUserData = $iN->iN_GetUserDetails($userID);
		    $userCurrentPoints = isset($liveOwnUserData['wallet_points']) ? $liveOwnUserData['wallet_points'] : '0';
			if($UpdateUsersWallet){
				$status = 'ok';
			}else{
				$status = '404';
			}
		}else{
			$status = '';
			$emountnot = 'notEnouhCredit';
			$redirect =  iN_HelpSecure($base_url) . 'purchase/purchase_point';
		}
	   $data = array(
		  'status' => $status,
		  'redirect' => $redirect,
		  'enamount' => $emountnot,
		  'giftAnimation' => $liveGiftAnimationUrl,
		  'current_balance' => number_format($userCurrentPoints)
	   );
	   $result = json_encode($data);
	   echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
	   if($status == 'ok'){
           $userDeviceKey = $iN->iN_GetuserDetails($giftLiveOwnerUserID);
		   $toUserName = $userDeviceKey['i_username'];
		   $oneSignalUserDeviceKey = $userDeviceKey['device_key'];
		   $msgBody = $iN->iN_Secure($LANG['send_you_a_gift']);
		   $msgTitle = $iN->iN_Secure($LANG['your_gift_is']).$currencys[$defaultCurrency]. $userEarning;
		   $URL = $base_url.'live'.$toUserName;
		   if($oneSignalUserDeviceKey){
			 $iN->iN_OneSignalPushNotificationSend($msgBody, $msgTitle, $url, $oneSignalUserDeviceKey, $oneSignalApi, $oneSignalRestApi);
		   }
	   }
	}
   }
  }
  if($type == 'sndAgCon'){
     /*SEND CONFIRMATIN EMAIL STARTED*/
	 $code = md5(rand(1111, 9999) . time());

		if ($emailSendStatus == '1') {
			$insertNewCode = $iN->iN_InsertNewVerificationCode($iN->iN_Secure($userID), $iN->iN_Secure($code));
			if ($insertNewCode)
				if ($smtpOrMail == 'mail') {
					$mail->IsMail();
				} else if ($smtpOrMail == 'smtp') {
					$mail->isSMTP();
					$mail->Host = $smtpHost; // Specify main and backup SMTP servers
					$mail->SMTPAuth = true;
					$mail->SMTPKeepAlive = true;
					$mail->Username = $smtpUserName; // SMTP username
					$mail->Password = $smtpPassword; // SMTP password
					$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
					$mail->Port = $smtpPort;
					$mail->SMTPOptions = array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true,
						),
					);
				} else {
					return false;
				}
				$instagramIcon = $iN->iN_SelectedMenuIcon('88');
				$facebookIcon = $iN->iN_SelectedMenuIcon('90');
				$twitterIcon = $iN->iN_SelectedMenuIcon('34');
				$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
				$startedFollow = $iN->iN_Secure($LANG['now_following_your_profile']);
				$theCode = $base_url.'verify?v='.$code;
				include_once '../includes/mailTemplates/verificationTemplate.php';
				$body = $bodyVerifyEmail;
				$mail->setFrom($smtpEmail, $siteName);
				$send = false;
				$mail->IsHTML(true);
				$mail->addAddress($userEmail, ''); // Add a recipient
				$mail->Subject = $iN->iN_Secure($LANG['confirm_email']);
				$mail->CharSet = 'utf-8';
				$mail->MsgHTML($body);
				if (iN_safeMailSend($mail, $smtpOrMail, 'email_confirmation')) {
					$mail->ClearAddresses();
					echo '8';
					return true;
				}
				echo '9';
				return false;

			}
		}
	 /*SEND CONFIRMATION EMAIL FINISHED*/
  }
  /*Insert OneSignal Device Key*/
  if($type == 'device_key'){
	if(isset($_GET['id']) && $_GET['id'] != ''){
		$userDeviceOneSignalKey = $iN->iN_Secure($_GET['id']);
		$InsertOneSignalDeviceKey = $iN->iN_OneSignalDeviceKey($userID, $userDeviceOneSignalKey);
		if($InsertOneSignalDeviceKey){
		   echo '1';
		}else{
		   echo '2';
		}
	}
  }
  /*Remove OneSignal Device key*/
  if($type == 'remove_device_key'){
	$InsertOneSignalDeviceKey = $iN->iN_OneSignalDeviceKeyRemove($userID);
  }
  /*Generate a QR Code*/
  if($type == 'generateQRCode'){
    include("../includes/qr.php");
  }
  // Get Mention Users
	if ($type == 'mfriends') {
		if (isset($_POST['menFriend'])) {
			$searchmUser = $iN->iN_Secure($_POST['menFriend']);
			$GetResultMentionedUser = $iN->iN_SearchMention($userID, $searchmUser);
			if ($GetResultMentionedUser) {
				foreach ($GetResultMentionedUser as $um) {
					 $mentionResultUserID = $um['iuid'];
                     $mentionResultUserUsername = $um['i_username'];
					 $mentionResultUserUserFullName = $um['i_user_fullname'];
					 $mentionResultUserAvatar = $iN->iN_UserAvatar($mentionResultUserID, $base_url);
					echo '
					<div class="i_message_wrapper transition mres_u" data-user="'.$mentionResultUserUsername.'">
						<div class="i_message_owner_avatar">
							<div class="i_message_avatar"><img src="'.$mentionResultUserAvatar.'" alt="newuserhere"></div>
						</div>
						<div class="i_message_info_container">
							<div class="i_message_owner_name">'.$mentionResultUserUserFullName.'</div>
							<div class="i_message_i">@'.$mentionResultUserUsername.'</div>
						</div>
					</div>
					 ';
				}
			}
		}
	}
if ($type == 'stories') {
    // Unified stories uploader: mirror 'upload' flow, avoid provider-specific putObject/SpacesConnect
    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!empty($_FILES['storieimg']['name'])) {
            foreach ($_FILES['storieimg']['name'] as $iname => $value) {
                $name = stripslashes($_FILES['storieimg']['name'][$iname]);
                $size = $_FILES['storieimg']['size'][$iname];
                $ext = strtolower(getExtension($name));
                $valid_formats = explode(',', $availableFileExtensions);
                if (!in_array($ext, $valid_formats)) { echo iN_HelpSecure($LANG['invalid_file_format']); continue; }
                // Safer numeric comparison (convert_to_mb returns formatted string)
                if ((float)convert_to_mb($size) >= (float)$availableUploadFileSize) { echo iN_HelpSecure($size); continue; }

                $microtime = microtime();
                $removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
                $UploadedFileName = 'image_' . $removeMicrotime . '_' . $userID;
                $getFilename = $UploadedFileName . '.' . $ext;
                $tmp = $_FILES['storieimg']['tmp_name'][$iname];
                $err = isset($_FILES['storieimg']['error'][$iname]) ? (int)$_FILES['storieimg']['error'][$iname] : UPLOAD_ERR_OK;
                if ($err !== UPLOAD_ERR_OK) { echo iN_HelpSecure($LANG['upload_failed']); continue; }
                $mimeType = $_FILES['storieimg']['type'][$iname];
                $d = date('Y-m-d');

                // Determine type (stories allow image or video)
                if (preg_match('/video\/*/', $mimeType) || $mimeType === 'application/octet-stream') { $fileTypeIs = 'video'; }
                else if (preg_match('/image\/*/', $mimeType)) { $fileTypeIs = 'Image'; }
                else { echo iN_HelpSecure($LANG['invalid_file_format']); continue; }

                // Ensure project-local uploads directories (align with uploadReel and storage helpers)
                $projUploadsRoot = dirname(__DIR__) . '/uploads';
                $projFilesDir   = $projUploadsRoot . '/files/' . $d;
                $projXImgsDir   = $projUploadsRoot . '/pixel/' . $d;
                $projXVideosDir = $projUploadsRoot . '/xvideos/' . $d;
                if (!is_dir($projFilesDir)) { @mkdir($projFilesDir, 0755, true); }
                if (!is_dir($projXImgsDir)) { @mkdir($projXImgsDir, 0755, true); }
                if (!is_dir($projXVideosDir)) { @mkdir($projXVideosDir, 0755, true); }

                if ($fileTypeIs === 'video' && $ffmpegStatus == '0' && !in_array($ext, $nonFfmpegAvailableVideoFormat)) { exit('303'); }
                if (!move_uploaded_file($tmp, $projFilesDir . '/' . $getFilename)) { echo $LANG['upload_failed']; continue; }

                $pathFile = '';
                $pathXFile = '';
                $tumbnailPath = '';
                $UploadSourceUrl = '';

                if ($fileTypeIs === 'video') {
                    if ($ffmpegStatus == '1') {
                        require_once '../includes/convertToMp4Format.php';
                        require_once '../includes/createVideoThumbnail.php';

                        // Resolve ffmpeg binary path if not configured
                        $ffmpegBin = !empty($ffmpegPath) ? $ffmpegPath : '';
                        if ($ffmpegBin === '' && function_exists('shell_exec')) {
                            $ffmpegBin = trim(@shell_exec('command -v ffmpeg 2>/dev/null || which ffmpeg 2>/dev/null'));
                        }
                        if ($ffmpegBin === '') { $ffmpegBin = 'ffmpeg'; }
                        rq_debug('stories:ffmpeg_bin', ['bin' => $ffmpegBin]);

                        $sourceFs = $projFilesDir . '/' . $getFilename;
                        rq_debug('stories:move_done', ['src' => $tmp, 'dst' => $sourceFs, 'exists' => file_exists($sourceFs), 'size' => @filesize($sourceFs)]);
                        $convertedFs = convertToMp4Format($ffmpegBin, $sourceFs, $projFilesDir, $UploadedFileName);
                        // If conversion failed, check if original was already MP4; otherwise abort to mirror 'uploadReel'
                        if (!$convertedFs || !file_exists($convertedFs)) {
                            $srcExt = strtolower(pathinfo($sourceFs, PATHINFO_EXTENSION));
                            if ($srcExt === 'mp4') {
                                $convertedFs = $sourceFs;
                            } else {
                                rq_debug('stories:convert_failed', ['source' => $sourceFs]);
                                echo iN_HelpSecure($LANG['mp4_conversion_failed'] ?? 'mp4_conversion_failed');
                                // Skip this file but continue with other uploads
                                continue;
                            }
                        }
                        rq_debug('stories:convert_ok', ['out' => $convertedFs, 'exists' => file_exists($convertedFs), 'size' => @filesize($convertedFs)]);

                        // 4-second preview and poster
                        if (!file_exists($projXVideosDir)) { @mkdir($projXVideosDir, 0755, true); }
                        $xVideoFirstPath = $projXVideosDir . '/' . $UploadedFileName . '.mp4';
                        $videoTumbnailFs = createVideoThumbnailInSameDir($ffmpegBin, $convertedFs);
                        rq_debug('stories:thumb', ['thumb' => $videoTumbnailFs, 'exists' => $videoTumbnailFs && file_exists($videoTumbnailFs)]);
                        $safeClip = $ffmpegBin . ' -ss 00:00:01 -i ' . escapeshellarg($convertedFs) . ' -c copy -t 00:00:04 ' . escapeshellarg($xVideoFirstPath) . ' 2>&1';
                        $clipOut = shell_exec($safeClip);
                        rq_debug('stories:clip', ['cmd' => $safeClip, 'xclip' => $xVideoFirstPath, 'exists' => file_exists($xVideoFirstPath)]);

                        // Determine actual file path and extension from the resulting file
                        $convertedBaseName = basename($convertedFs);
                        $pathFile = 'uploads/files/' . $d . '/' . $convertedBaseName;
                        $pathXFile = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                        $tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                        $thePathM = dirname(__DIR__) . '/' . $tumbnailPath;
                        if (file_exists($thePathM) && ($watermarkStatus == 'yes' || $LinkWatermarkStatus == 'yes')) { watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url . $userName); }

                        // Publish keys using returned mapping instead of is_file (works with remote cleanup)
                        $publishKeys = [];
                        $mp4Key = $pathFile;
                        $xclipKey = $pathXFile;
                        $thumbJpg = $tumbnailPath;
                        if (is_file(dirname(__DIR__) . '/' . $mp4Key)) { $publishKeys[] = $mp4Key; }
                        if (is_file(dirname(__DIR__) . '/' . $xclipKey)) { $publishKeys[] = $xclipKey; }
                        if (is_file(dirname(__DIR__) . '/' . $thumbJpg)) { $publishKeys[] = $thumbJpg; }
                        $published = $publishKeys ? storage_publish_many($publishKeys, true, true) : [];
                        rq_debug('stories:publish', ['keys' => $publishKeys, 'map' => $published]);
                        $UploadSourceUrl = $published[ltrim($thumbJpg, '/')] ?? ($published[ltrim($mp4Key, '/')] ?? ($base_url . 'uploads/web.png'));
                        if ($UploadSourceUrl === $base_url . 'uploads/web.png') { $tumbnailPath = 'uploads/web.png'; }
                        // Force extension to mp4 when conversion/original is mp4
                        $ext = 'mp4';
                    } else {
                        // No ffmpeg: treat as-is
                        $pathFile = 'uploads/files/' . $d . '/' . $getFilename;
                        $pathXFile = 'uploads/files/' . $d . '/' . $getFilename;
                        $pub = storage_publish_many([$pathFile], true, true);
                        $UploadSourceUrl = $pub[ltrim($pathFile, '/')] ?? storage_public_url($pathFile);
                        $ext = strtolower(pathinfo($pathFile, PATHINFO_EXTENSION));
                    }
                } else if ($fileTypeIs === 'Image') {
                    // Use project-local paths constructed above
                    $pathFile = 'uploads/files/' . $d . '/' . $getFilename;
                    $pathXFile = 'uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext;
                    $tumbnailPath = $pathFile;
                    // Optional watermark on image
                    $thePathM = dirname(__DIR__) . '/' . $pathFile;
                    if ($ext !== 'gif' && ($watermarkStatus == 'yes' || $LinkWatermarkStatus == 'yes')) {
                        watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url . $userName);
                    }
                    // Pixelated copy
                    try {
                        $dirFs = dirname(__DIR__) . '/' . $pathXFile;
                        if (!file_exists(dirname($dirFs))) { @mkdir(dirname($dirFs), 0755, true); }
                        $image = new ImageFilter();
                        $image->load(dirname(__DIR__) . '/' . $pathFile)->pixelation($pixelSize)->saveFile($dirFs, 100, 'jpg');
                    } catch (Exception $e) { echo '<span class="request_warning">' . $e->getMessage() . '</span>'; }

                        $pub = storage_publish_many([$pathFile, $pathXFile], true, true);
                        $UploadSourceUrl = $pub[ltrim($tumbnailPath, '/')] ?? storage_public_url($tumbnailPath);
                } else {
                    echo iN_HelpSecure($LANG['invalid_file_format']);
                    continue;
                }

                // Persist and render the story item
                $insertFileFromUploadTable = $iN->iN_insertUploadedSotieFiles($userID, $pathFile, $tumbnailPath, $pathXFile, $ext);
                $getUploadedFileID = $iN->iN_GetUploadedStoriesFilesIDs($userID, $pathFile);
                if ($fileTypeIs == 'Image') {
                    echo '<!--Storie--><div class="uploaded_storie_container nonePoint body_' . $getUploadedFileID['s_id'] . '"><div class="dmyStory" id="' . $getUploadedFileID['s_id'] . '"><div class="i_h_in flex_ ownTooltip" data-label="' . iN_HelpSecure($LANG['delete']) . '">' . html_entity_decode($iN->iN_SelectedMenuIcon('28')) . '</div></div><div class="uploaded_storie_image border_one tabing flex_"><img src="' . $UploadSourceUrl . '" id="img' . $getUploadedFileID['s_id'] . '"></div><div class="add_a_text"><textarea class="add_my_text st_txt_' . $getUploadedFileID['s_id'] . '" placeholder="Do you want to write something about this storie?"></textarea></div><div class="share_story_btn_cnt flex_ tabing transition share_this_story" id="' . $getUploadedFileID['s_id'] . '">' . html_entity_decode($iN->iN_SelectedMenuIcon('26')) . '<div class="pbtn">' . iN_HelpSecure($LANG['publish']) . '</div></div></div><!--/Storie--><script type="text/javascript">(function($){"use strict";setTimeout(()=>{var img=document.getElementById("img' . $getUploadedFileID['s_id'] . '"); if(img && img.height>img.width){$("#img' . $getUploadedFileID['s_id'] . '").css("height","100%");} else {$("#img' . $getUploadedFileID['s_id'] . '").css("width","100%");} $(".uploaded_storie_container").show();},2000);})(jQuery);</script>';
                } else if ($fileTypeIs == 'video') {
                    echo '<!--Storie--><div class="uploaded_storie_container body_' . $getUploadedFileID['s_id'] . '"><div class="dmyStory" id="' . $getUploadedFileID['s_id'] . '"><div class="i_h_in flex_ ownTooltip" data-label="' . iN_HelpSecure($LANG['delete']) . '">' . html_entity_decode($iN->iN_SelectedMenuIcon('28')) . '</div></div><div class="uploaded_storie_image border_one tabing flex_"><video class="lg-video-object" id="v' . $getUploadedFileID['s_id'] . '" controls preload="none" poster="' . $UploadSourceUrl . '"><source src="' . storage_public_url($getUploadedFileID['uploaded_file_path']) . '" preload="metadata" type="video/mp4">Your browser does not support HTML5 video.</video></div><div class="add_a_text"><textarea class="add_my_text st_txt_' . $getUploadedFileID['s_id'] . '" placeholder="Do you want to write something about this storie?"></textarea></div><div class="share_story_btn_cnt flex_ tabing transition share_this_story" id="' . $getUploadedFileID['s_id'] . '">' . html_entity_decode($iN->iN_SelectedMenuIcon('26')) . '<div class="pbtn">' . iN_HelpSecure($LANG['publish']) . '</div></div></div><!--/Storie-->';
                } else { echo iN_HelpSecure($LANG['invalid_file_format']); }
            }
            exit; // prevent falling into any legacy stories logic
        }
    }
}

    /*Delete Storie Alert*/
	if($type == 'delete_storie_alert'){
       if(isset($_POST['id']) && $_POST['id'] != ''){
		   $postID = $iN->iN_Secure($_POST['id']);
		   $alertType = $type;
		   $checkStorieIDExist = $iN->iN_CheckStorieIDExist($userID, $postID);
		   if($checkStorieIDExist){
			 include "../themes/$currentTheme/layouts/popup_alerts/deleteStoryAlert.php";
		   }
	   }
	}
	/*Storie Seen*/
	if($type == 'storieSeen'){
     if(isset($_POST['id']) && $_POST['id'] != ''){
         $storieID = $iN->iN_Secure($_POST['id']);
		 $checkStorieID = $iN->iN_CheckStorieIDExistJustID($userID, $storieID);
		 if($checkStorieID){
            $insertSee = $iN->iN_InsertStorieSeen($userID, $storieID);
		 }
	 }
	}
	/*Show StorieViewers*/
	if($type == 'storieViewers'){
		if(isset($_POST['id']) && $_POST['id'] != ''){
			$storieID = $iN->iN_Secure($_POST['id']);
			$checkStorieID = $iN->iN_CheckStorieIDExistJustID($userID, $storieID);
			if($checkStorieID){
				$swData = $iN->iN_GetUploadedStoriesSeenData($userID,$storieID);
				include "../themes/$currentTheme/layouts/popup_alerts/storieViewers.php";
			}
		}

	}
	if ($type == 'pr_upload') {
		//$availableFileExtensions
		if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
			foreach ($_FILES['uploading']['name'] as $iname => $value) {
				$name = stripslashes($_FILES['uploading']['name'][$iname]);
				$size = $_FILES['uploading']['size'][$iname];
				$ext = getExtension($name);
				$ext = strtolower($ext);
				$valid_formats = explode(',', $availableFileExtensions);
				if (in_array($ext, $valid_formats)) {
					if (convert_to_mb($size) < $availableUploadFileSize) {
						$microtime = microtime();
						$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
						$UploadedFileName = "image_" . $removeMicrotime . '_' . $userID;
						$getFilename = $UploadedFileName . "." . $ext;
						// Change the image ame
						$tmp = $_FILES['uploading']['tmp_name'][$iname];
						$mimeType = $_FILES['uploading']['type'][$iname];
						$d = date('Y-m-d');
						if (preg_match('/video\/*/', $mimeType)) {
							$fileTypeIs = 'video';
						} else if (preg_match('/image\/*/', $mimeType)) {
							$fileTypeIs = 'Image';
						}
						if($mimeType == 'application/octet-stream'){
							$fileTypeIs = 'video';
						}
						if (!file_exists($uploadFile . $d)) {
							$newFile = mkdir($uploadFile . $d, 0755);
						}
						if (!file_exists($xImages . $d)) {
							$newFile = mkdir($xImages . $d, 0755);
						}
						if (!file_exists($xVideos . $d)) {
							$newFile = mkdir($xVideos . $d, 0755);
						}
						$wVideos = rtrim(UPLOAD_DIR_VIDEOS, '/') . '/';
						if (!file_exists($wVideos . $d)) {
							$newFile = mkdir($wVideos . $d, 0755);
						}
						if ($fileTypeIs == 'video' && $ffmpegStatus == '0' && !in_array($ext, $nonFfmpegAvailableVideoFormat)) {
							exit('303');
						}
						$uploadTumbnail = '';
						if (move_uploaded_file($tmp, $uploadFile . $d . '/' . $getFilename)) {
							/*IF FILE FORMAT IS VIDEO THEN DO FOLLOW*/
							if ($fileTypeIs == 'video') {
								$postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('52') . '</div>';
								$UploadedFilePath = $base_url . 'uploads/files/' . $d . '/' . $getFilename;
								if ($ffmpegStatus == '1') {
									$convertUrl = '../uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
									$videoTumbnailPath = '../uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
									$xVideoFirstPath = '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
									$textVideoPath = '../uploads/videos/' . $d . '/' . $UploadedFileName . '.mp4';

									$pathFile = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
									$pathXFile = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
									if ($ext == 'mpg') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -c copy -map 0 $convertUrl");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									} else if ($ext == 'mov') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -vcodec copy -acodec copy $convertUrl");
										$cmd = shell_exec("$ffmpegPath -ss 00:00:01.000 -i $convertUrl -vframes 1 $videoTumbnailPath 2>&1");
									} else if ($ext == 'wmv') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -c copy -map 0 $convertUrl");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									} else if ($ext == 'avi') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -vcodec h264 -acodec aac -strict -2 $convertUrl 2>&1");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									} else if ($ext == 'webm') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -crf 1 -c:v libx264 $convertUrl");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									} else if ($ext == 'mpeg') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -c copy -map 0 $convertUrl");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									} else if ($ext == 'flv') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -c:a aac -strict -2 -b:a 128k -c:v libx264 -profile:v baseline $convertUrl");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									} else if ($ext == 'm4v') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -c copy $convertUrl");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									} else if ($ext == 'mkv') {
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -codec copy -strict -2 $convertUrl 2>&1");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									}else if($ext == '3gp'){
										$cmd = shell_exec("$ffmpegPath -i $UploadedFilePath -acodec copy -vcodec copy $convertUrl 2>&1");
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									}else{
										$cmd = shell_exec("$ffmpegPath -i $convertUrl -ss 00:00:01.000 -vframes 1 $videoTumbnailPath 2>&1");
									}

									$up_url = remove_http($base_url).$userName;
									$cmd = shell_exec("$ffmpegPath -ss 00:00:01 -i $convertUrl -c copy -t 00:00:04 $xVideoFirstPath 2>&1");
									if($drawTextStatus == '1'){
										$cmdText = shell_exec("$ffmpegPath -i $convertUrl -vf drawtext=fontfile=../src/droidsanschinese.ttf:text=$up_url:fontcolor=red:fontsize=18:x=10:y=H-th-10 $textVideoPath 2>&1");
									}else{
										$cmdText = shell_exec("$ffmpegPath -i $convertUrl -c:a copy -c:v libx264 -preset superfast -profile:v baseline $textVideoPath 2>&1");
									}
									if ($cmdText) {
										$pathFile = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
									}
									$thePath = '../uploads/files/' . $d . '/'.$UploadedFileName . '.jpg';
									if (file_exists($thePath)) {
										try {
											$dir = "../uploads/xvideos/" . $d . "/" . $UploadedFileName . '.jpg';
											$fileUrl = '../uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
											$image = new ImageFilter();
											$image->load($fileUrl)->pixelation($pixelSize)->saveFile($dir, 100, "jpg");

										} catch (Exception $e) {
											echo '<span class="request_warning">' . $e->getMessage() . '</span>';
										}
									}else{
										exit('You uploaded a video in '.$ext.' video format and ffmpeg could not create a tumbnail from the video.  You need to contact your server administration about this. ');
									}
								} else {
									$cmd = '';
									$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
									$pathXFile = 'uploads/files/' . $d . '/' . $getFilename;
								}
								if ($ffmpegStatus == '1') {
    								$tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
    								$thePathM = '../' . $tumbnailPath;
									if($watermarkStatus == 'yes'){
    								  watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url.$userName);
									}else if($LinkWatermarkStatus == 'yes'){
									  watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url.$userName);
									}
								}
								// Unified object storage publish for video assets
								{
									$publishKeys = [];
									$mp4Key = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
									$xclipKey = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
									$thumbJpg = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
									$thumbPng = 'uploads/files/' . $d . '/' . $UploadedFileName . '.png';
									if (is_file('../' . $mp4Key)) { $publishKeys[] = $mp4Key; }
									if (is_file('../' . $xclipKey)) { $publishKeys[] = $xclipKey; }
									if (is_file('../' . $thumbJpg)) { $publishKeys[] = $thumbJpg; }
									if (is_file('../' . $thumbPng)) { $publishKeys[] = $thumbPng; }
									if (!empty($publishKeys)) { storage_publish_many($publishKeys, true, true); }
									if (is_file('../' . $thumbJpg)) {
										$tumbnailPath = $thumbJpg;
										$UploadSourceUrl = storage_public_url($thumbJpg);
									} elseif (is_file('../' . $thumbPng)) {
										$tumbnailPath = $thumbPng;
										$UploadSourceUrl = storage_public_url($thumbPng);
									} elseif (is_file('../' . $mp4Key)) {
										$UploadSourceUrl = storage_public_url($mp4Key);
									} else {
										$UploadSourceUrl = $base_url . 'uploads/web.png';
										$tumbnailPath = 'uploads/web.png';
									}
								}
								/*CHECK AMAZON S3 AVAILABLE (disabled by unified storage)*/
								if (false && $s3Status == '1') {
                                    $tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                                    $publicAccessErrorShown = false;

                                    $theName = '../uploads/files/' . $d . '/' . $getFilename;
                                    $key = basename($theName);

                                    if ($ffmpegStatus == '1') {
                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $s3Bucket,
                                                'Key' => 'uploads/files/' . $d . '/' . $key,
                                                'Body' => fopen($theName, 'r'),
                                                'CacheControl' => 'max-age=3153600',
                                                // 'ACL' => 'public-read', is intentionally excluded for compatibility
                                            ]);
                                            $fullUploadedVideo = $result->get('ObjectURL');
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            $msg = $e->getAwsErrorMessage();
                                            echo "There was an error uploading the file: $msg<br>";
                                            if (!$publicAccessErrorShown && str_contains($msg, 'Public use of objects is not allowed')) {
                                                echo "<div class='request_warning'>" . $LANG['s3_public_access_warning'] . "</div>";
                                                $publicAccessErrorShown = true;
                                            }
                                        }
                                    } else {
                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $s3Bucket,
                                                'Key' => 'uploads/files/' . $d . '/' . $key,
                                                'Body' => fopen($theName, 'r'),
                                                'CacheControl' => 'max-age=3153600',
                                                // 'ACL' => 'public-read', is intentionally excluded for compatibility
                                            ]);
                                            $fullUploadedVideo = $result->get('ObjectURL');
                                            @unlink($uploadFile . $d . '/' . $getFilename);
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            $msg = $e->getAwsErrorMessage();
                                            echo "There was an error uploading the file: $msg<br>";
                                            if (!$publicAccessErrorShown && str_contains($msg, 'Public use of objects is not allowed')) {
                                                echo "<div class='request_warning'>" . $LANG['s3_public_access_warning'] . "</div>";
                                                $publicAccessErrorShown = true;
                                            }
                                        }
                                    }

                                    if ($cmd) {
                                        $uploads = [
                                            ['path' => '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4', 'target' => 'uploads/xvideos/'],
                                            ['path' => '../uploads/files/' . $d . '/' . $UploadedFileName . '.jpg', 'target' => 'uploads/xvideos/'],
                                            ['path' => '../uploads/files/' . $d . '/' . $UploadedFileName . '.jpg', 'target' => 'uploads/files/'],
                                        ];

                                        foreach ($uploads as $upload) {
                                            $key = basename($upload['path']);
                                            try {
                                                $result = $s3->putObject([
                                                    'Bucket' => $s3Bucket,
                                                    'Key' => $upload['target'] . $d . '/' . $key,
                                                    'Body' => fopen($upload['path'], 'r'),
                                                    'CacheControl' => 'max-age=3153600',
                                                    // 'ACL' => 'public-read', is intentionally excluded for compatibility
                                                ]);
                                                $UploadSourceUrl = $result->get('ObjectURL');
                                                @unlink($upload['path']);
                                            } catch (Aws\S3\Exception\S3Exception $e) {
                                                $msg = $e->getAwsErrorMessage();
                                                echo $LANG['error_uploading_file'] . '<br>';
                                                if (!$publicAccessErrorShown && str_contains($msg, 'Public use of objects is not allowed')) {
                                                    echo "<div class='request_warning'>" . $LANG['s3_public_access_warning'] . "</div>";
                                                    $publicAccessErrorShown = true;
                                                }
                                            }
                                        }
                                    } else {
                                        $UploadSourceUrl = $base_url . 'uploads/web.png';
                                        $tumbnailPath = 'uploads/web.png';
                                        $pathXFile = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.jpg';
                                    }

								} else if (false && $WasStatus == '1') {
                                    $tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                                    $publicAccessErrorShown = false;

                                    $theName = '../uploads/files/' . $d . '/' . $getFilename;
                                    $key = basename($theName);

                                    if ($ffmpegStatus == '1') {
                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $WasBucket,
                                                'Key' => 'uploads/files/' . $d . '/' . $key,
                                                'Body' => fopen($theName, 'r'),
                                                'CacheControl' => 'max-age=3153600',
                                                // 'ACL' => 'public-read', is intentionally excluded for compatibility
                                            ]);
                                            $UploadSourceUrl = $result->get('ObjectURL');
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            $msg = $e->getAwsErrorMessage();
                                            echo $LANG['error_uploading_file'] . '<br>';
                                            if (!$publicAccessErrorShown && str_contains($msg, 'Public use of objects is not allowed')) {
                                                echo "<div class='request_warning'>" . $LANG['wasabi_public_access_warning'] . "</div>";
                                                $publicAccessErrorShown = true;
                                            }
                                        }
                                    } else {
                                        try {
                                            $result = $s3->putObject([
                                                'Bucket' => $WasBucket,
                                                'Key' => 'uploads/files/' . $d . '/' . $key,
                                                'Body' => fopen($theName, 'r'),
                                                'CacheControl' => 'max-age=3153600',
                                                // 'ACL' => 'public-read', is intentionally excluded for compatibility
                                            ]);
                                            $UploadSourceUrl = $result->get('ObjectURL');
                                        } catch (Aws\S3\Exception\S3Exception $e) {
                                            $msg = $e->getAwsErrorMessage();
                                            echo $LANG['error_uploading_file'] . '<br>';
                                            if (!$publicAccessErrorShown && str_contains($msg, 'Public use of objects is not allowed')) {
                                                echo "<div class='request_warning'>" . $LANG['wasabi_public_access_warning'] . "</div>";
                                                $publicAccessErrorShown = true;
                                            }
                                        }
                                    }

                                    if ($cmd) {
                                        $uploads = [
                                            ['path' => '../uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4', 'target' => 'uploads/xvideos/'],
                                            ['path' => '../uploads/files/' . $d . '/' . $UploadedFileName . '.jpg', 'target' => 'uploads/xvideos/'],
                                            ['path' => '../uploads/files/' . $d . '/' . $UploadedFileName . '.jpg', 'target' => 'uploads/files/'],
                                        ];

                                        foreach ($uploads as $upload) {
                                            $key = basename($upload['path']);
                                            try {
                                                $result = $s3->putObject([
                                                    'Bucket' => $WasBucket,
                                                    'Key' => $upload['target'] . $d . '/' . $key,
                                                    'Body' => fopen($upload['path'], 'r'),
                                                    'CacheControl' => 'max-age=3153600',
                                                    // 'ACL' => 'public-read', is intentionally excluded for compatibility
                                                ]);
                                                $UploadSourceUrl = $result->get('ObjectURL');
                                                @unlink($upload['path']);
                                            } catch (Aws\S3\Exception\S3Exception $e) {
                                                $msg = $e->getAwsErrorMessage();
                                                echo "There was an error uploading the file: $msg<br>";
                                                if (!$publicAccessErrorShown && str_contains($msg, 'Public use of objects is not allowed')) {
                                                    echo "<div class='request_warning'>" . $LANG['wasabi_public_access_warning'] . "</div>";
                                                    $publicAccessErrorShown = true;
                                                }
                                            }
                                        }

                                        // Remove local temporary files
                                        @unlink($uploadFile . $d . '/' . $UploadedFileName . '.' . $ext);
                                        @unlink($uploadFile . $d . '/' . $UploadedFileName . '.mp4');
                                        @unlink($uploadFile . $d . '/' . $UploadedFileName . '.jpg');
                                        @unlink($xVideos . $d . '/' . $UploadedFileName . '.mp4');
                                        @unlink($xVideos . $d . '/' . $UploadedFileName . '.jpg');
                                        @unlink($uploadFile . $d . '/' . $getFilename);
                            				@unlink($serverDocumentRoot . '/uploads/videos/' . $d . '/' . $UploadedFileName . '.mp4');
                            			}
                            			// Unified publish keys
                            			$publishKeys = [];
                            			$mp4Key = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
                            			$xclipKey = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                            			$thumbJpg = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                            			if (is_file('../' . $mp4Key)) { $publishKeys[] = $mp4Key; }
                            			if (is_file('../' . $xclipKey)) { $publishKeys[] = $xclipKey; }
                            			if (is_file('../' . $thumbJpg)) { $publishKeys[] = $thumbJpg; }
                            			if ($publishKeys) { storage_publish_many($publishKeys, true, true); }
                            			if (is_file('../' . $thumbJpg)) { $UploadSourceUrl = storage_public_url($thumbJpg); }
                            			elseif (is_file('../' . $mp4Key)) { $UploadSourceUrl = storage_public_url($mp4Key); }
                            			else { $UploadSourceUrl = $base_url . 'uploads/web.png'; $tumbnailPath = 'uploads/web.png'; }
                                } else if (false && $digitalOceanStatus == '1') {
                                	// Initialize DigitalOcean Spaces client once
                                	// removed legacy SpacesConnect client

                                	// Unified: publish original + preview + thumb via storage helpers
                                	$toPublish = [];
                                	$toPublish[] = 'uploads/files/' . $d . '/' . $getFilename;
                                	if ($cmd) {
                                		$toPublish[] = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.mp4';
                                		$toPublish[] = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
                                		$toPublish[] = 'uploads/files/' . $d . '/' . $UploadedFileName . '.mp4';
                                	}
                                	if (function_exists('storage_publish_many')) {
                                		storage_publish_many($toPublish, true, false);
                                		$UploadSourceUrl = storage_publish_pick_url([
                                			'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg',
                                			'uploads/files/' . $d . '/' . $getFilename,
                                		], true) ?? ($base_url . 'uploads/web.png');
                                	} else {
                                		$UploadSourceUrl = $base_url . 'uploads/files/' . $d . '/' . $getFilename;
                                	}
                                } else {
									if ($cmd) {
										$UploadSourceUrl = $base_url . 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
										$tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '.jpg';
										$pathXFile = 'uploads/xvideos/' . $d . '/' . $UploadedFileName . '.jpg';
									} else {
										$UploadSourceUrl = $base_url . 'uploads/web.png';
										$tumbnailPath = 'uploads/web.png';
										$tumbnailPath = $pathFile;
										$pathXFile = 'uploads/web.png';
									}
								}
								$ext = 'mp4';
								/**/
							} else if ($fileTypeIs == 'Image') {
								$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
								$pathXFile = 'uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext;
								$postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('53') . '</div>';
								$tumbnails = $serverDocumentRoot . '/uploads/files/' . $d . '/';
								$pathFilea = $base_url . 'uploads/files/' . $d . '/' . $getFilename;
								$pathFileC = '../uploads/files/' . $d . '/' . $getFilename;
								$width = 500;
								$height = 500;
								$file = $pathFilea;
								//indicate the path and name for the new resized file
								$resizedFile = $tumbnails . $UploadedFileName . '_' . $userID . '.' . $ext;
								$resizedFileTwo = $tumbnails . $UploadedFileName . '__' . $userID . '.' . $ext;
								$tb = new ThumbAndCrop();
								$tb->openImg($pathFileC);
								$newHeight = $tb->getRightHeight(500);
								$tb->creaThumb(500, $newHeight);
								$tb->setThumbAsOriginal();
								$tb->creaThumb(500, $newHeight);
								$tb->saveThumb($resizedFileTwo);

								$thePathM = '../' . $pathFile;
								$tumbnailPath = 'uploads/files/' . $d . '/' . $UploadedFileName . '__' . $userID . '.' . $ext;
								if($ext != 'gif'){
									if($watermarkStatus == 'yes'){
										watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url.$userName);
									  }else if($LinkWatermarkStatus == 'yes'){
										watermark_image($thePathM, $siteWatermarkLogo, $LinkWatermarkStatus, $base_url.$userName);
									  }
								}
								if (file_exists($thePathM)) {
									try {
										$dir = "../uploads/pixel/" . $d . "/" . $getFilename;
										$fileUrl = '../uploads/files/' . $d . '/' . $UploadedFileName . '.' . $ext;
										$image = new ImageFilter();
										$image->load($fileUrl)->pixelation($pixelSize)->saveFile($dir, 100, "jpg");
									} catch (Exception $e) {
										echo '<span class="request_warning">' . $e->getMessage() . '</span>';
									}
							    }
                                // Publish to active storage provider using unified helpers
                                $keysToPublish = [
                                    'uploads/files/' . $d . '/' . $UploadedFileName . '__' . $userID . '.' . $ext,
                                    'uploads/files/' . $d . '/' . $UploadedFileName . '.' . $ext,
                                    'uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext,
                                ];
                                $UploadSourceUrl = storage_publish_and_url(
                                    'uploads/files/' . $d . '/' . $getFilename,
                                    $keysToPublish,
                                    true
                                );
							}
							/**/
							$insertFileFromUploadTable = $iN->iN_INSERTUploadedFiles($userID, $pathFile, $tumbnailPath, $pathXFile, $ext);
							$getUploadedFileID = $iN->iN_GetUploadedFilesIDs($userID, $pathFile);
							DB::exec("UPDATE i_user_uploads SET upload_type = 'product' WHERE upload_id = ?", [(int)$getUploadedFileID['upload_id']]);
							if ($fileTypeIs == 'video') {
								$uploadTumbnail = '
								<div class="v_custom_tumb">
									<label for="vTumb_' . $getUploadedFileID['upload_id'] . '">
										<div class="i_image_video_btn"><div class="pbtn pbtn_plus">' . $LANG['custom_tumbnail'] . '</div>
										<input type="file" id="vTumb_' . $getUploadedFileID['upload_id'] . '" class="imageorvideo cTumb editAds_file" data-id="' . $getUploadedFileID['upload_id'] . '" name="uploading[]" data-id="tupload">
									</label>
								</div>
								';
							}
							if ($fileTypeIs == 'video' || $fileTypeIs == 'Image') {
								/*AMAZON S3*/
								echo '
									<div class="i_uploaded_item iu_f_' . $getUploadedFileID['upload_id'] . ' ' . $fileTypeIs . '" id="' . $getUploadedFileID['upload_id'] . '">
									' . $postTypeIcon . '
									<div class="i_delete_item_button" id="' . $getUploadedFileID['upload_id'] . '">
										' . $iN->iN_SelectedMenuIcon('5') . '
									</div>
									<div class="i_uploaded_file" id="viTumb' . $getUploadedFileID['upload_id'] . '" style="background-image:url(' . $UploadSourceUrl . ');">
											<img class="i_file" id="viTumbi' . $getUploadedFileID['upload_id'] . '" src="' . $UploadSourceUrl . '" alt="tumbnail">
									</div>
									' . $uploadTumbnail . '
									</div>
								';
							}
						}else{
							echo $LANG['something_wrong'];
						}
					} else {
						echo iN_HelpSecure($size);
					}
				}
			}
		}
	}
/*Insert New product*/
if($type == 'createScratch' || $type == 'createBookaZoom'){
   if(isset($_POST['prnm']) && isset($_POST['prprc']) && isset($_POST['prdsc']) && isset($_POST['prdscinf']) && isset($_POST['vals'])){
      $productName = $iN->iN_Secure($_POST['prnm']);
	  $productPrice = $iN->iN_Secure($_POST['prprc']);
	  $productDescription = $iN->iN_Secure($_POST['prdsc']);
	  $productDescriptionInfo = $iN->iN_Secure($_POST['prdscinf']);
	  $productFiles = $iN->iN_Secure($_POST['vals']);
	  $productLimitSlots = $iN->iN_Secure($_POST['lmSlot']);
	  $productAskQuestion = $iN->iN_Secure($_POST['askQ']);
	  $productFiles = implode(',',array_unique(explode(',', $productFiles)));
	    if($productFiles != '' && !empty($productFiles) && $productFiles != 'undefined'){
			$trimValue = rtrim($productFiles, ',');
			$explodeFiles = explode(',', $trimValue);
			$explodeFiles = array_unique($explodeFiles);
			foreach($explodeFiles as $explodeFile){
				$theFileID = $iN->iN_GetUploadedFileDetails($explodeFile);
				$uploadedFileID = isset($theFileID['upload_id']) ? $theFileID['upload_id'] : NULL;
				if(empty($uploadedFileID)){
				    exit('204');
				}
			}
	    }
		if($productLimitSlots == 'ok'){
			$productLimSlots = $iN->iN_Secure($_POST['lSlot']);
			if(preg_replace('/\s+/', '',$productLimSlots) == ''){
				exit(iN_HelpSecure($LANG['please_fill_in_all_informations']).'345');
			}
		}else{$productLimSlots = '';}
		if($productAskQuestion == 'ok'){
			$productQuestion = $iN->iN_Secure($_POST['qAsk']);
			if(preg_replace('/\s+/', '',$productQuestion) == ''){
				exit(iN_HelpSecure($LANG['please_fill_in_all_informations']).'123');
			}
		}else{$productQuestion = '';}

	  if(preg_replace('/\s+/', '',$productName) == '' || preg_replace('/\s+/', '',$productPrice) == '' || preg_replace('/\s+/', '',$productDescription) == '' || preg_replace('/\s+/', '',$productDescriptionInfo) == '' || preg_replace('/\s+/', '',$productFiles) == ''){
         exit(iN_HelpSecure($LANG['please_fill_in_all_informations']));
	  }
	  if($type == 'createScratch'){
         $productType = 'scratch';
	  }else if($type == 'createBookaZoom'){
		$productType = 'bookazoom';
	  }else if($type == 'createartcommission'){
		$productType = 'artcommission';
	  }else if($type == 'createjoininstagramclosefriends'){
		$productType = 'joininstagramclosefriends';
	  }
      $slug = $iN->url_slugies(mb_substr($productName, 0, 55, "utf-8"));
	  $insertNewProduct = $iN->iN_InsertNewProduct($userID, $iN->iN_Secure($productName), $iN->iN_Secure($productPrice), $iN->iN_Secure($productDescription), $iN->iN_Secure($productDescriptionInfo), $iN->iN_Secure($productFiles), $iN->iN_Secure($slug), $iN->iN_Secure($productType), $iN->iN_Secure($productLimSlots), $iN->iN_Secure($productQuestion));
	  if($insertNewProduct){
        exit('200');
	  }else{
		exit('404');
	  }
   }
}
if($type == 'productStatus'){
   if(isset($_POST['mod']) && in_array($_POST['mod'], $statusValue) && isset($_POST['id'])){
        $productID = $iN->iN_Secure($_POST['id']);
	  $newStatus = $iN->iN_Secure($_POST['mod']);
	  $updateProductStatus = $iN->iN_UpdateProductStatus($userID, $productID, $newStatus);
	  if($updateProductStatus){
        exit('200');
	  }else{
		exit('404');
	  }
   }
}
if($type == 'saveEditPr'){
	if(isset($_POST['prnm']) && isset($_POST['prnm']) && isset($_POST['prprc']) && isset($_POST['prdsc']) && isset($_POST['prdscinf'])){
		$productID = $iN->iN_Secure($_POST['prid']);
		$productName = $iN->iN_Secure($_POST['prnm']);
		$productPrice = $iN->iN_Secure($_POST['prprc']);
		$productDescription = $iN->iN_Secure($_POST['prdsc']);
		$productDescriptionInfo = $iN->iN_Secure($_POST['prdscinf']);
		$productLimitSlots = $iN->iN_Secure($_POST['lmSlot']);
		$productAskQuestion = $iN->iN_Secure($_POST['askQ']);
		if($productLimitSlots == 'ok'){
			$productLimSlots = $iN->iN_Secure($_POST['lSlot']);
			if(preg_replace('/\s+/', '',$productLimSlots) == ''){
				exit(iN_HelpSecure($LANG['please_fill_in_all_informations']).'345');
			}
		}else{$productLimSlots = '';}
		if($productAskQuestion == 'ok'){
			$productQuestion = $iN->iN_Secure($_POST['qAsk']);
			if(preg_replace('/\s+/', '',$productQuestion) == ''){
				exit(iN_HelpSecure($LANG['please_fill_in_all_informations']).'123');
			}
		}else{$productQuestion = '';}
		if(preg_replace('/\s+/', '',$productName) == '' || preg_replace('/\s+/', '',$productPrice) == '' || preg_replace('/\s+/', '',$productDescription) == '' || preg_replace('/\s+/', '',$productDescriptionInfo) == ''){
		   exit(iN_HelpSecure($LANG['please_fill_in_all_informations']));
		}
		$slug = $iN->url_slugies(mb_substr($productName, 0, 55, "utf-8"));
		$insertNewProduct = $iN->iN_InsertUpdatedProduct($userID, $iN->iN_Secure($productID),$iN->iN_Secure($productName), $iN->iN_Secure($productPrice), $iN->iN_Secure($productDescription), $iN->iN_Secure($productDescriptionInfo), $iN->iN_Secure($slug), $iN->iN_Secure($productLimSlots), $iN->iN_Secure($productQuestion));
		if($insertNewProduct){
		  exit('200');
		}else{
		  exit('404');
		}
	 }
}
/*Get Free Follow PopUP*/
if ($type == 'delete_product') {
	if (isset($_POST['id'])) {
		$productID = $iN->iN_Secure($_POST['id']);
		$checkproductExist = $iN->iN_CheckProductIDExist($userID, $productID);
		if ($checkproductExist) {
			include "../themes/$currentTheme/layouts/popup_alerts/deleteProduct.php";
		}
	}
}
/*Delete Story From Database*/
if ($type == 'deleteProduct') {
	if (isset($_POST['id'])) {
		$productID = $iN->iN_Secure($_POST['id']);
		if(!empty($productID)){
			$getPostFileIDs = $iN->iN_ProductDetails($userID, $productID);
			$idsA = isset($getPostFileIDs['pr_files']) ? $getPostFileIDs['pr_files'] : '';
			$idsB = isset($getPostFileIDs['post_file']) ? $getPostFileIDs['post_file'] : '';
			$merged = trim($idsA . ',' . $idsB, ',');
			$explodeFiles = $merged !== '' ? array_unique(explode(',', rtrim($merged, ','))) : [];
			foreach ($explodeFiles as $explodeFile) {
				$theFileID = $iN->iN_GetUploadedFileDetails($explodeFile);
				if($theFileID){
					$uploadedFileID = $theFileID['upload_id'];
					$uploadedFilePath = $theFileID['uploaded_file_path'];
					$uploadedTumbnailFilePath = $theFileID['upload_tumbnail_file_path'];
					$uploadedFilePathX = $theFileID['uploaded_x_file_path'];
					if (storage_is_remote()) {
						@storage_delete($uploadedFilePath);
						@storage_delete($uploadedFilePathX);
						@storage_delete($uploadedTumbnailFilePath);
					} else {
						@unlink('../' . $uploadedFilePath);
						@unlink('../' . $uploadedFilePathX);
						@unlink('../' . $uploadedTumbnailFilePath);
					}
                    DB::exec("DELETE FROM i_user_uploads WHERE upload_id = ? AND iuid_fk = ?", [(int)$uploadedFileID, (int)$userID]);
				}
			}
			$deleteStoragePost = $iN->iN_DeleteProductFromDataifStorage($userID, $productID);
			if($deleteStoragePost){ echo '200'; } else { echo '404'; }
		}
	}
}
/*UPload Downloadable File*/
if ($type == 'prd_upload') {
	$availableFileExtensions = 'pdf,zip,PDF,ZIP';
	//$availableFileExtensions
	if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
		foreach ($_FILES['uploading']['name'] as $iname => $value) {
			$name = stripslashes($_FILES['uploading']['name'][$iname]);
			$size = $_FILES['uploading']['size'][$iname];
			$ext = getExtension($name);
			$ext = strtolower($ext);
			$valid_formats = explode(',', $availableFileExtensions);
			if (in_array($ext, $valid_formats)) {
				if (convert_to_mb($size) < $availableUploadFileSize) {
					$microtime = microtime();
					$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
					$UploadedFileName = "file_" . $removeMicrotime . '_' . $userID;
					$getFilename = $UploadedFileName . "." . $ext;
					// Change the image ame
					$tmp = $_FILES['uploading']['tmp_name'][$iname];
					$mimeType = $_FILES['uploading']['type'][$iname];
					$d = date('Y-m-d');

					if (!file_exists($uploadFile . $d)) {
						$newFile = mkdir($uploadFile . $d, 0755);
					}
					$uploadTumbnail = '';
					if (move_uploaded_file($tmp, $uploadFile . $d . '/' . $getFilename)) {
						/*IF FILE FORMAT IS VIDEO THEN DO FOLLOW*/
						$postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('52') . '</div>';
						$UploadedFilePath = $base_url . 'uploads/files/' . $d . '/' . $getFilename;
						$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
						$pathXFile = 'uploads/files/' . $d . '/' . $getFilename;
						// Unified publish for generic file upload
						{
							$fileKey = 'uploads/files/' . $d . '/' . $getFilename;
							$UploadSourceUrl = storage_publish_and_url($fileKey, [$fileKey], true);
						}
						/*CHECK AMAZON S3 AVAILABLE (disabled by unified storage)*/
						if (false && $s3Status == '1') {
							/*Upload Full video*/
							$theName = '../uploads/files/' . $d . '/' . $getFilename;
							$key = basename($theName);

							try {
								$result = $s3->putObject([
									'Bucket' => $s3Bucket,
									'Key' => 'uploads/files/' . $d . '/' . $key,
									'Body' => fopen($theName, 'r+'),
									'ACL' => 'public-read',
									'CacheControl' => 'max-age=3153600',
								]);
								$fullUploadedVideo = $result->get('ObjectURL');
								@unlink($uploadFile . $d . '/' . $getFilename);
							} catch (Aws\S3\Exception\S3Exception $e) {
								echo $LANG['error_uploading_file'] . "\n";
							}
							$status = 'ok';
							$UploadSourceUrl = $UploadedFilePath;
						}else if (false && $WasStatus == '1') {
							/*Upload Full video*/
							$theName = '../uploads/files/' . $d . '/' . $getFilename;
							$key = basename($theName);

							try {
								$result = $s3->putObject([
									'Bucket' => $WasBucket,
									'Key' => 'uploads/files/' . $d . '/' . $key,
									'Body' => fopen($theName, 'r+'),
									'ACL' => 'public-read',
									'CacheControl' => 'max-age=3153600',
								]);
								$fullUploadedVideo = $result->get('ObjectURL');
								@unlink($uploadFile . $d . '/' . $getFilename);
							} catch (Aws\S3\Exception\S3Exception $e) {
								echo $LANG['error_uploading_file'] . "\n";
							}
							$status = 'ok';
							$UploadSourceUrl = $UploadedFilePath;
						} else if (false && $digitalOceanStatus == '1') {
							$theName = '../uploads/files/' . $d . '/' . $getFilename;
							/*IF DIGITALOCEAN AVAILABLE THEN*/
							$my_space = new SpacesConnect($oceankey, $oceansecret, $oceanspace_name, $oceanregion);
							$upload = $my_space->UploadFile($theName, "public");
							if($upload){
								@unlink($uploadFile . $d . '/' . $getFilename);
							}
							/*/IF DIGITAOCEAN AVAILABLE THEN*/
							$status = 'ok';
							$UploadSourceUrl = $UploadedFilePath;
						} else if (false) {
							$status = 'ok';
							$UploadSourceUrl = $UploadedFilePath;
						}
						/**/
						if($ext == 'pdf'){
                           $fileIcon = html_entity_decode($iN->iN_SelectedMenuIcon('166'));
						}else{
						   $fileIcon = html_entity_decode($iN->iN_SelectedMenuIcon('167'));
						}
						if($UploadSourceUrl){
							$data = array(
								'status' => $status,
								'fileUrl' => $UploadSourceUrl,
								'filePath' => $pathFile,
								'fileIcon' => $fileIcon,
								'fileName' => $getFilename
							);
							$result = json_encode($data);
							echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
						}
					}else{
						echo $LANG['something_wrong'];
					}
				} else {
					echo iN_HelpSecure($size);
				}
			}
		}
	}
}
if($type == 'createDigitalDownload'){
	if(isset($_POST['prnm']) && isset($_POST['prprc']) && isset($_POST['prdsc']) && isset($_POST['prdscinf']) && isset($_POST['vals']) && isset($_POST['dFile'])){
		$productName = $iN->iN_Secure($_POST['prnm']);
		$productPrice = $iN->iN_Secure($_POST['prprc']);
		$productDescription = $iN->iN_Secure($_POST['prdsc']);
		$productDescriptionInfo = $iN->iN_Secure($_POST['prdscinf']);
		$productFiles = $iN->iN_Secure($_POST['vals']);
		$productDownloadableFile = $iN->iN_Secure($_POST['dFile']);
		$productFiles = implode(',',array_unique(explode(',', $productFiles)));
		  if($productFiles != '' && !empty($productFiles) && $productFiles != 'undefined'){
			  $trimValue = rtrim($productFiles, ',');
			  $explodeFiles = explode(',', $trimValue);
			  $explodeFiles = array_unique($explodeFiles);
			  foreach($explodeFiles as $explodeFile){
				  $theFileID = $iN->iN_GetUploadedFileDetails($explodeFile);
				  $uploadedFileID = isset($theFileID['upload_id']) ? $theFileID['upload_id'] : NULL;
				  if(empty($uploadedFileID)){
					  exit('204');
				  }
			  }
		  }
		if(preg_replace('/\s+/', '',$productName) == '' || preg_replace('/\s+/', '',$productPrice) == '' || preg_replace('/\s+/', '',$productDescription) == '' || preg_replace('/\s+/', '',$productDescriptionInfo) == '' || preg_replace('/\s+/', '',$productFiles) == '' || preg_replace('/\s+/', '',$productDownloadableFile) == ''){
		   exit(iN_HelpSecure($LANG['please_fill_in_all_informations']));
		}
		$productType = 'digitaldownload';

		$slug = $iN->url_slugies(mb_substr($productName, 0, 55, "utf-8"));
		$insertNewProduct = $iN->iN_InsertNewProductDownloadable($userID, $iN->iN_Secure($productName), $iN->iN_Secure($productPrice), $iN->iN_Secure($productDescription), $iN->iN_Secure($productDescriptionInfo), $iN->iN_Secure($productFiles), $iN->iN_Secure($slug), $iN->iN_Secure($productType), $iN->iN_Secure($productDownloadableFile));
		if($insertNewProduct){
		  exit('200');
		}else{
		  exit('404');
		}
	 }
}
/*Insert New product*/
if($type == 'createliveeventticket' || $type == 'createartcommission' || $type == 'createjoininstagramclosefriends'){
	if(isset($_POST['prnm']) && isset($_POST['prprc']) && isset($_POST['prdsc']) && isset($_POST['prdscinf']) && isset($_POST['vals'])){
	    $productName = $iN->iN_Secure($_POST['prnm']);
	    $productPrice = $iN->iN_Secure($_POST['prprc']);
	    $productDescription = $iN->iN_Secure($_POST['prdsc']);
	    $productDescriptionInfo = $iN->iN_Secure($_POST['prdscinf']);
	    $productFiles = $iN->iN_Secure($_POST['vals']);
	    $productLimitSlots = $iN->iN_Secure($_POST['lmSlot']);
	    $productAskQuestion = $iN->iN_Secure($_POST['askQ']);
	    $productFiles = implode(',',array_unique(explode(',', $productFiles)));
		if($productFiles != '' && !empty($productFiles) && $productFiles != 'undefined'){
			$trimValue = rtrim($productFiles, ',');
			$explodeFiles = explode(',', $trimValue);
			$explodeFiles = array_unique($explodeFiles);
			foreach($explodeFiles as $explodeFile){
				$theFileID = $iN->iN_GetUploadedFileDetails($explodeFile);
				$uploadedFileID = isset($theFileID['upload_id']) ? $theFileID['upload_id'] : NULL;
				if(empty($uploadedFileID)){
					exit('204');
				}
			}
		}
		if($productLimitSlots == 'ok'){
			$productLimSlots = $iN->iN_Secure($_POST['lSlot']);
			if(preg_replace('/\s+/', '',$productLimSlots) == ''){
				exit(iN_HelpSecure($LANG['please_fill_in_all_informations']).'345');
			}
		}else{$productLimSlots = '';}
		if($productAskQuestion == 'ok'){
			$productQuestion = $iN->iN_Secure($_POST['qAsk']);
			if(preg_replace('/\s+/', '',$productQuestion) == ''){
				exit(iN_HelpSecure($LANG['please_fill_in_all_informations']).'123');
			}
		}else{$productQuestion = '';}
	    if(preg_replace('/\s+/', '',$productName) == '' || preg_replace('/\s+/', '',$productPrice) == '' || preg_replace('/\s+/', '',$productDescription) == '' || preg_replace('/\s+/', '',$productDescriptionInfo) == '' || preg_replace('/\s+/', '',$productFiles) == ''){
			exit(iN_HelpSecure($LANG['please_fill_in_all_informations']));
	    }
		if($type == 'createliveeventticket'){
			$productType = 'liveeventticket';
		} else if($type == 'createartcommission'){
			$productType = 'artcommission';
		} else if($type == 'createjoininstagramclosefriends'){
			$productType = 'joininstagramclosefriends';
		}
		$slug = $iN->url_slugies(mb_substr($productName, 0, 55, "utf-8"));
		$insertNewProduct = $iN->iN_InsertNewProductLiveEventTicket($userID, $iN->iN_Secure($productName), $iN->iN_Secure($productPrice), $iN->iN_Secure($productDescription), $iN->iN_Secure($productDescriptionInfo), $iN->iN_Secure($productFiles), $iN->iN_Secure($slug), $iN->iN_Secure($productType), $iN->iN_Secure($productLimSlots), $iN->iN_Secure($productQuestion));
		if($insertNewProduct){
			exit('200');
		}else{
			exit('404');
		}
	}
}

if($type == 'shareMyTextStory'){
   if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['stext']) && !empty($_POST['stext']) && $_POST['stext'] != ''){
      $bgID = $iN->iN_Secure($_POST['id']);
	  $storyText = $iN->iN_Secure($_POST['stext']);
	  if(preg_replace('/\s+/', '',$storyText) == ''){
        exit(iN_HelpSecure($LANG['please_add_text_in_your_story']));
	  }
	  $insertTextStory = $iN->iN_InsertTextStory($userID, $iN->iN_Secure($bgID), $iN->iN_Secure($storyText));
	  if($insertTextStory){
        exit('200');
	  }else{
		exit('404');
	  }
   }
}
if ($type == 'buyProduct') {
	if (isset($_POST['type']) && $_POST['type'] != '' && !empty($_POST['type'])) {
		$productID = $iN->iN_Secure($_POST['type']);
	    $checkproductID = $iN->iN_CheckProductIDExistFromURL($productID);
		if($checkproductID == TRUE){
			$prData = $iN->iN_GetProductDetailsByID($productID);
			$planAmount = $prData['pr_price'];
			$ProductOwnerID = $prData['iuid_fk'];

			if($ProductOwnerID == $userID){
              exit('me');
			}
			$planPoint = '';
			if($stripePaymentCurrency == 'JPY'){
				 $planAmount = $planAmount / 100;
			}
			require_once '../includes/payment/vendor/autoload.php';
			if (!defined('INORA_METHODS_CONFIG')) {
				define('INORA_METHODS_CONFIG', realpath('../includes/payment/paymentConfig.php'));
			}
			$configData = configItem();
			$DataUserDetails = [
				'amounts' => [ // at least one currency amount is required
					$payPalCurrency => $planAmount,
					$iyziCoPaymentCurrency => $planAmount,
					$bitPayPaymentCurrency => $planAmount,
					$autHorizePaymentCurrency => $planAmount,
					$payStackPaymentCurrency => $planAmount,
					$stripePaymentCurrency => $planAmount,
					$razorPayPaymentCurrency => $planAmount,
				],
				'order_id' => 'ORDS' . uniqid(), // required in instamojo, Iyzico, Paypal, Paytm gateways
				'customer_id' => 'CUSTOMER' . uniqid(), // required in Iyzico, Paytm gateways
				'item_name' => $LANG['point_purchasing'], // required in Paypal gateways
				'item_qty' => 1,
				'item_id' => 'ITEM' . uniqid(), // required in Iyzico, Paytm gateways
				'payer_email' => $userEmail, // required in instamojo, Iyzico, Stripe gateways
				'payer_name' => $userFullName, // required in instamojo, Iyzico gateways
				'description' => $LANG['point_purchasing_from'], // Required for stripe
				'ip_address' => getUserIpAddr(), // required only for iyzico
				'address' => '3234 Godfrey Street Tigard, OR 97223', // required in Iyzico gateways
				'city' => 'Tigard', // required in Iyzico gateways
				'country' => 'United States', // required in Iyzico gateways
			];
			$PublicConfigs = getPublicConfigItem();

			$configItem = $configData['payments']['gateway_configuration'];

			// Get config data
			$configa = getPublicConfigItem();
			// Get app URL
			$paymentPagePath = getAppUrl();

			$gatewayConfiguration = $configData['payments']['gateway_configuration'];
			// get paystack config data
			$paystackConfigData = $gatewayConfiguration['paystack'];
			// Get paystack callback ur
			$paystackCallbackUrl = getAppUrl($paystackConfigData['callbackUrl']);

			// Get stripe config data
			$stripeConfigData = $gatewayConfiguration['stripe'];
			// Get stripe callback ur
			$stripeCallbackUrl = getAppUrl($stripeConfigData['callbackUrl']);

			// Get razorpay config data
			$razorpayConfigData = $gatewayConfiguration['razorpay'];
			// Get razorpay callback url
			$razorpayCallbackUrl = getAppUrl($razorpayConfigData['callbackUrl']);

			// Get Authorize.Net config Data
			$authorizeNetConfigData = $gatewayConfiguration['authorize-net'];
			// Get Authorize.Net callback url
			$authorizeNetCallbackUrl = getAppUrl($authorizeNetConfigData['callbackUrl']);

			// Individual payment gateway url
			$individualPaymentGatewayAppUrl = getAppUrl('individual-payment-gateways');
			// User Details Configurations FINISHED
			include "../themes/$currentTheme/layouts/popup_alerts/paymentMethodsForPurchaseProduct.php";
		}
	}
}
if ($type == 'processProduct') {
	require_once '../includes/payment/vendor/autoload.php';
	if (!defined('INORA_METHODS_CONFIG')) {
		define('INORA_METHODS_CONFIG', realpath('../includes/payment/paymentConfig.php'));
	}
	include "../includes/payment/payment-process-product.php";
}
if($type == 'downloadMyProduct'){
   if(isset($_POST['myp']) && !empty($_POST['myp']) && $_POST['myp'] != ''){
      $productID = $iN->iN_Secure($_POST['myp']);
	  $checkProductPurchasedBefore = $iN->iN_CheckItemPurchasedBefore($userID, $productID);
	  if($checkProductPurchasedBefore){
		$productData = $iN->iN_GetProductDetailsByID($productID);
		$uProductDownloadableFiles = $productData['pr_downlodable_files'];
		$thefile = $uProductDownloadableFiles;
		$file = $uProductDownloadableFiles;
		$ext = substr($file, strrpos($file, '.') + 1);
        $fake = 'aa.'.$ext;
		if (file_exists($thefile)) {
			$iN->download($file,$fake);
		}
	  }
   }
}
if($type == 'gotAnnouncement'){
   if(isset($_POST['aid']) && $_POST['aid'] != ''){
       $announceID = $iN->iN_Secure($_POST['aid']);
	   $announcementReaded = $iN->iN_AnnouncementAccepted($userID, $announceID);
	   if($announcementReaded){
         exit('200');
	   }else{
		 exit('404');
	   }
   }
}
if($type == 'mrProduct'){
    if(isset($_POST['last']) && isset($_POST['ty'])){
       $productID = $iN->iN_Secure($_POST['last']);
       $categoryKey = $iN->iN_Secure($_POST['ty']);
       $productData = $iN->iN_AllUserProductPosts($categoryKey, $productID, $showingNumberOfPost);
	   include "../themes/$currentTheme/layouts/loadmore/moreProduct.php";
	}
}
if($type == 'moveMyAffilateBalance'){
  if(isset($_POST['myp']) && $_POST['myp'] != '' && !empty($_POST['myp'])){
	  $moveMyPoint = $iN->iN_MoveMyPoint($userID);
  }
}
/*Open Profile Tip Box*/
if($type == 'p_p_tips'){
	if(isset($_POST['tp_u']) && !empty($_POST['tp_u']) && $_POST['tp_u'] !== ''){
		$tipingUserID = $iN->iN_Secure($_POST['tp_u']);
		$tipingUserDetails = $iN->iN_GetUserDetails($tipingUserID);
		$f_userfullname = $tipingUserDetails['i_user_fullname'];
		include "../themes/$currentTheme/layouts/popup_alerts/sendProfileTipPoint.php";
	}
}
/*Open Profile Frame Box*/
if($type == 'p_p_frame'){
	if(isset($_POST['tp_u']) && !empty($_POST['tp_u']) && $_POST['tp_u'] !== ''){
		$tipingUserID = $iN->iN_Secure($_POST['tp_u']);
		$tipingUserDetails = $iN->iN_GetUserDetails($tipingUserID);
		$f_userfullname = $tipingUserDetails['i_user_fullname'];
		include "../themes/$currentTheme/layouts/popup_alerts/sendProfileFrame.php";
	}
}
if($type == 'p_p_tips_message'){
	if(isset($_POST['tp_u']) && !empty($_POST['tp_u']) && $_POST['tp_u'] !== ''){
		$tipingUserID = $iN->iN_Secure($_POST['tp_u']);
		$tipingUserDetails = $iN->iN_GetUserDetails($tipingUserID);
		$f_userfullname = $tipingUserDetails['i_user_fullname'];
		include "../themes/$currentTheme/layouts/popup_alerts/sendMessageTipPoint.php";
	}
}
/*Send Tip*/
if($type == 'p_sendTipProfile'){
	if(isset($_POST['tip_u']) && isset($_POST['tipVal']) && $_POST['tip_u'] != '' &&  $_POST['tipVal'] != '' && !empty($_POST['tip_u']) && !empty($_POST['tipVal'])){
	   $tiSendingUserID = $iN->iN_Secure($_POST['tip_u']);
	   $tipAmount = $iN->iN_Secure($_POST['tipVal']);
	   $redirect = '';
	   $emountnot = '';
	   $status = '';
	   if($tipAmount < $minimumTipAmount){
		  $emountnot = 'notEnough';
	   }else{
		  if ($userCurrentPoints >= $tipAmount && $userID != $tiSendingUserID) {

			  $netUserEarning = $tipAmount * $onePointEqual;
			  $adminEarning = ($adminFee * $netUserEarning) / 100;
			  $userNetEarning = $netUserEarning - $adminEarning;

			  $UpdateUsersWallet = $iN->iN_UpdateUsersWallets($userID, $tiSendingUserID, $tipAmount, $netUserEarning,$adminFee, $adminEarning, $userNetEarning);
			  if($UpdateUsersWallet){
				 $status = 'ok';
			  }else{
				 $status = '404';
			  }
		   }else{
			  $status = '';
			  $emountnot = 'notEnouhCredit';
			  $redirect =  iN_HelpSecure($base_url) . 'purchase/purchase_point';
		   }
	   }
	   $data = array(
		  'status' => $status,
		  'redirect' => $redirect,
		  'enamount' => $emountnot
	   );
	   $result = json_encode($data);
	   echo preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $result);
	   if($status == 'ok'){
		  $userDeviceKey = $iN->iN_GetuserDetails($tiSendingUserID);
		  $toUserName = $userDeviceKey['i_username'];
		  $oneSignalUserDeviceKey = isset($userDeviceKey['device_key']) ? $userDeviceKey['device_key'] : NULL;
		  $msgBody = $iN->iN_Secure($LANG['send_you_a_tip']);
		  $msgTitle = $iN->iN_Secure($LANG['tip_earning']).$currencys[$defaultCurrency]. $netUserEarning;
		  $URL = $base_url.'settings?tab=dashboard';
		  if($oneSignalUserDeviceKey){
			$iN->iN_OneSignalPushNotificationSend($msgBody, $msgTitle, $url, $oneSignalUserDeviceKey, $oneSignalApi, $oneSignalRestApi);
		  }
	    }
	}
}

/*Send Tip*/
if($type == 'p_sendTipMessage'){
	if(isset($_POST['tip_u']) && isset($_POST['tipVal']) && $_POST['tip_u'] != '' &&  $_POST['tipVal'] != '' && !empty($_POST['tip_u']) && !empty($_POST['tipVal'])){
	   $tiSendingUserID = $iN->iN_Secure($_POST['tip_u']);
	   $tipAmount = $iN->iN_Secure($_POST['tipVal']);
	   $chatID = $iN->iN_Secure($_POST['chID']);
	   $redirect = '';
	   $emountnot = '';
	   $status = '';
	   if($tipAmount < $minimumTipAmount){
		  exit('notEnough');
	   }else{
		  if ($userCurrentPoints >= $tipAmount && $userID != $tiSendingUserID) {

			  $netUserEarning = $tipAmount * $onePointEqual;
			  $adminEarning = ($adminFee * $netUserEarning) / 100;
			  $userNetEarning = $netUserEarning - $adminEarning;

			  $UpdateUsersWallet = $iN->iN_UpdateUsersWallets($userID, $tiSendingUserID, $tipAmount, $netUserEarning,$adminFee, $adminEarning, $userNetEarning);
			  if($UpdateUsersWallet){
				 $status = 'ok';
			  }else{
				 exit('404');
			  }
		   }else{
			  exit('notEnouhCredit');
			  $redirect =  iN_HelpSecure($base_url) . 'purchase/purchase_point';
		   }
	   }

	   if($status == 'ok'){
		  $userDeviceKey = $iN->iN_GetuserDetails($tiSendingUserID);
		  $toUserName = $userDeviceKey['i_username'];
		  $oneSignalUserDeviceKey = isset($userDeviceKey['device_key']) ? $userDeviceKey['device_key'] : NULL;
		  $msgBody = $iN->iN_Secure($LANG['send_you_a_tip']);
		  $msgTitle = $iN->iN_Secure($LANG['tip_earning']).$currencys[$defaultCurrency]. $netUserEarning;
		  $URL = $base_url.'settings?tab=dashboard';
		  if($oneSignalUserDeviceKey){
			$iN->iN_OneSignalPushNotificationSend($msgBody, $msgTitle, $url, $oneSignalUserDeviceKey, $oneSignalApi, $oneSignalRestApi);
		  }
		  $message = $userNetEarning;
		  $sendedGiftMoney = $tipAmount;
		  $insertData = $iN->iN_InsertNewTipMessage($userID, $chatID, $message, $sendedGiftMoney);
			if ($insertData) {
				$cMessageID = $insertData['con_id'];
				$cUserOne = $insertData['user_one'];
				$cUserTwo = $insertData['user_two'];
				$cMessage = $insertData['message'];
				$gifMoney = isset($insertData['gifMoney']) ? $insertData['gifMoney'] : NULL;
				$mSeenStatus = $insertData['seen_status'];
				$cStickerUrl = isset($insertData['sticker_url']) ? $insertData['sticker_url'] : NULL;
				$cGifUrl = isset($insertData['gifurl']) ? $insertData['gifurl'] : NULL;
				$cMessageTime = $insertData['time'];
				$ip = $iN->iN_GetIPAddress();
				$query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
				if ($query && $query['status'] == 'success') {
					date_default_timezone_set($query['timezone']);
				}
				$message_time = date("c", $cMessageTime);
				$convertMessageTime = strtotime($message_time);
				$netMessageHour = date('H:i', $convertMessageTime);
				$cFile = isset($insertData['file']) ? $insertData['file'] : NULL;
				$msgDots = '';
				$imStyle = '';
				$seenStatus = '';
				if ($cUserOne == $userID) {
					$mClass = 'me';
					$msgOwnerID = $cUserOne;
					$lastM = '';
					$timeStyle = 'msg_time_me';
					if (!empty($cFile)) {
						$imStyle = 'mmi_i';
					}
					$seenStatus = '<span class="seenStatus flex_ notSeen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
					if ($mSeenStatus == '1') {
						$seenStatus = '<span class="seenStatus flex_ seen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
					}
					if($gifMoney){
                        $SGifMoneyText = preg_replace( '/{.*?}/', $cMessage, $LANG['youSendGifMoney']);
                    }
				} else {
					$mClass = 'friend';
					$msgOwnerID = $cUserOne;
					$lastM = 'mm_' . $msgOwnerID;
					if (!empty($cFile)) {
						$imStyle = 'mmi_if';
					}
					if($gifMoney){
                        $msgOwnerFullName = $iN->iN_UserFullName($msgOwnerID);
                        $SGifMoneyText = $iN->iN_TextReaplacement($LANG['sendedGifMoney'],[$msgOwnerFullName , $cMessage]);
                    }
					$timeStyle = 'msg_time_fri';
				}
				$styleFor = '';
				if ($cStickerUrl) {
					$styleFor = 'msg_with_sticker';
					$cMessage = '<img class="mStick" src="' . $cStickerUrl . '">';
				}
				if ($cGifUrl) {
					$styleFor = 'msg_with_gif';
					$cMessage = '<img class="mGifM" src="' . $cGifUrl . '">';
				}
				$msgOwnerAvatar = $iN->iN_UserAvatar($msgOwnerID, $base_url);
				include "../themes/$currentTheme/layouts/chat/newMessage.php";
			}

	   }
	}
}
  /*Buy Video Call*/
  if($type == 'buyVideoCall'){
     if(isset($_POST['calledID']) && $_POST['calledID'] !== '' && !empty($_POST['calledID']) && isset($_POST['callName']) && $_POST['callName'] !== '' && !empty($_POST['callName'])){
		$calledUserID = $iN->iN_Secure($_POST['calledID']);
		$videoCallName = $iN->iN_Secure($_POST['callName']);
		$callerDetails = $iN->iN_GetUserDetails($calledUserID);
		$callerUserFullName = $callerDetails['i_user_fullname'];
		$callerUserName = $callerDetails['i_username'];
		$videoCallPrice = $callerDetails['video_call_price'];
		$whoCanVideoCall = $callerDetails['who_can_call'];
		$subStatus = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $calledUserID);
		$checkUserIsCreator = $iN->iN_CheckUserIsCreator($calledUserID);
		$callerUserAvatar = $iN->iN_UserAvatar($calledUserID, $base_url);
		if($isVideoCallFree == 'no'){
			include "../themes/$currentTheme/layouts/popup_alerts/buyVideoCall.php";
		}else if($isVideoCallFree == 'yes'){
			$insertChannelName = $iN->iN_InsertVideoCall($userID, $videoCallName, $calledUserID);
			include "../themes/$currentTheme/layouts/popup_alerts/videoCalling.php";
		}else{
			exit('404');
		}
	 }
  }
  /*Create a video call*/
  if($type == 'createVideoCall'){
      if(isset($_POST['calledID']) && $_POST['calledID'] !== '' && !empty($_POST['calledID']) && isset($_POST['callName']) && $_POST['callName'] !== '' && !empty($_POST['callName'])){
		    $calledUserID = $iN->iN_Secure($_POST['calledID']);
			$videoCallName = $iN->iN_Secure($_POST['callName']);
			$callerDetails = $iN->iN_GetUserDetails($calledUserID);
			$callerUserFullName = $callerDetails['i_user_fullname'];
			$callerUserName = $callerDetails['i_username'];
			$videoCallPrice = $callerDetails['video_call_price'];
			$whoCanVideoCall = $callerDetails['who_can_call'];
			$callerUserAvatar = $iN->iN_UserAvatar($calledUserID, $base_url);
			if($whoCanVideoCall == '0'){
				$insertChannelName = $iN->iN_InsertVideoCall($userID, $videoCallName, $calledUserID);
				include "../themes/$currentTheme/layouts/popup_alerts/videoCalling.php";
			}else{
				if($userCurrentPoints < $videoCallPrice && $userID != $calledUserID && $isVideoCallFree == 'no'){
					exit();
				}else if($isVideoCallFree == 'no'){
					$netUserEarning = $videoCallPrice * $onePointEqual;
					$adminEarning = ($adminFee * $netUserEarning) / 100;
					$userNetEarning = $netUserEarning - $adminEarning;
					$UpdateUsersWallet = $iN->iN_UpdateUsersWalletsForVideoCall($userID, $calledUserID, $videoCallPrice, $netUserEarning,$adminFee, $adminEarning, $userNetEarning);
					if($UpdateUsersWallet){
						$insertChannelName = $iN->iN_InsertVideoCall($userID, $videoCallName, $calledUserID);
						include "../themes/$currentTheme/layouts/popup_alerts/videoCalling.php";
					}else{
						exit('404');
					}
				}else{
					$insertChannelName = $iN->iN_InsertVideoCall($userID, $videoCallName, $calledUserID);
					include "../themes/$currentTheme/layouts/popup_alerts/videoCalling.php";
				}
		    }
	  }
  }
  /*Video Call Alert*/
  if($type == 'videoCallAlert'){
      if(isset($_POST['call']) && !empty($_POST['call']) && $_POST['call'] !== ''){
          $callID = $iN->iN_Secure($_POST['call']);
		  $callDetails = $iN->iN_VideoCallDetails($callID);
		  $callerUserID = $callDetails['caller_uid_fk'];
		  $chatUrl = $callDetails['vc_id'];
		  $callerDetails = $iN->iN_GetUserDetails($callerUserID);
		  $callerUserFullName = $callerDetails['i_user_fullname'];
		  $callerUserName = $callerDetails['i_username'];
		  $callerUserAvatar = $iN->iN_UserAvatar($callerUserID, $base_url);
		  if($fullnameorusername == 'no'){
			$callerUserFullName = $callerUserName;
		  }
		  include "../themes/$currentTheme/layouts/popup_alerts/videocallalert.php";
	  }
  }
  /*Video Call Accept*/
  if($type == 'call_accepted'){
    if(isset($_POST['accID']) && !empty($_POST['accID']) && $_POST['accID'] !== ''){
		$callID = $iN->iN_Secure($_POST['accID']);
		$callDetails = $iN->iN_VideoCallAcceptDetails($callID);
		$chatUrl = $callDetails['chat_id_fk'];
		echo iN_HelpSecure($base_url) . 'chat?chat_width=' . $chatUrl;
	}
  }
if ($type == 'liveVideoMute') {
    if (isset($_POST['chName']) && !empty($_POST['chName'])) {
        $channelName = $iN->iN_Secure($_POST['chName']);

        $call = DB::one("SELECT vc_id, video_muted FROM i_video_call WHERE voice_call_name = ? LIMIT 1", [$channelName]);

        if ($call) {
            $currentStatus = (int)$call['video_muted'];
            $newStatus = $currentStatus === 1 ? 0 : 1;

            $update = DB::exec("UPDATE i_video_call SET video_muted = ? WHERE vc_id = ?", [$newStatus, (int)$call['vc_id']]);

            echo json_encode([
                'status' => $update ? 'success' : 'error',
                'muted' => $newStatus,
                'message' => $update ? 'Updated successfully' : 'Update failed'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Channel not found'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Missing channel name'
        ]);
    }
    exit(); // her durumda 癟覺k
}
  /*Live Call End*/
  if($type == 'liveEnd'){
     if(isset($_POST['chName']) && !empty($_POST['chName']) && $_POST['chName'] !== ''){
        $channelName = $iN->iN_Secure($_POST['chName']);
		$checkAndDeleteCall = $iN->iN_CheckAndDeleteCall($userID, $channelName);
		if($checkAndDeleteCall){
           exit('200');
		}else{
			exit('404');
		}
	 }
  }
  /*Agora: Request new RTC token*/
  if($type == 'agoraNewToken'){
     header('Content-Type: application/json');
     if(isset($_POST['ch']) && $_POST['ch'] !== '' && isset($_POST['host'])){
        $channelName = $iN->iN_Secure($_POST['ch']);
        $asHost = $_POST['host'] === '1' ? true : false;
        // Ensure user is logged in
        if(!isset($userID) || empty($userID)){
            echo json_encode(['status' => 'error', 'message' => $LANG['unauthorized_error']]);
            exit();
        }
        // Generate token
        require_once '../includes/tokenGenerator.php';
        try{
            $token = agora_token_builder($asHost, $channelName, $agoraAppID, $agoraCertificate, $userID);
            echo json_encode(['status' => 'ok', 'token' => $token, 'channel' => $channelName, 'uid' => (int)$userID]);
        }catch(Exception $e){
            echo json_encode(['status' => 'error', 'message' => $LANG['token_generation_failed']]);
        }
        exit();
     }
     echo json_encode(['status' => 'error', 'message' => $LANG['bad_request']]);
     exit();
  }
  /*Video Call Decline*/
  if($type == 'call_declined'){
    if(isset($_POST['accID']) && !empty($_POST['accID']) && $_POST['accID'] !== ''){
		$callID = $iN->iN_Secure($_POST['accID']);
		$callDetails = $iN->iN_VideoCallDeclineDetails($callID);
	}
  }
  /*Update Video Call fee*/
  if($type == 'vCost'){
     if(isset($_POST['vCostFee']) && !empty($_POST['vCostFee']) && $_POST['vCostFee'] !== ''){
          $videoCost = $iN->iN_Secure($_POST['vCostFee']);
		  if($videoCost == '0'){
            exit('not');
		  }
		$insertVideoCost = $iN->iN_UpdateVideoCost($userID, $videoCost);
	 }else{
		 exit('not');
	 }
  }
  if($type == 'moveMyEarnedPoints'){
	if(isset($_POST['myp']) && $_POST['myp'] != '' && !empty($_POST['myp'])){
		$totalEarned = $iN->iN_Secure($_POST['myp']);
		if($totalEarned < 1){
           exit('You don\'t have enough points to calculate yet.');
		}
		$moveMyPoint = $iN->iN_MovePointEarningsToPointBalance($userID, $totalEarned);
		if($moveMyPoint){
			exit('ok');
		}else{
			exit('me');
		}
	}else{
		exit('You don\'t have enough points to calculate yet.');
	}
  }
  /*Unlock Message*/
  if($type == 'unlockMessage'){
    if(isset($_POST['mi']) && !empty($_POST['mi']) && $_POST['mi'] != '' && isset($_POST['ci']) && !empty($_POST['ci']) && $_POST['ci'] != ''){
       $messageID = $iN->iN_Secure($_POST['mi']);
	   $chatID = $iN->iN_Secure($_POST['ci']);
	   $getMData = $iN->iN_GetMessageDetailsByID($messageID, $chatID);
	   $messagePrice = isset($getMData['private_price']) ? $getMData['private_price'] : NULL;
	   $userOne = isset($getMData['user_one']) ? $getMData['user_one'] : NULL;
	   $userTwo = isset($getMData['user_two']) ? $getMData['user_two'] : NULL;
	   if($userOne == $userID){
         $messageOwnerID = $userTwo;
	   }else{
		 $messageOwnerID = $userOne;
	   }
	   if($userCurrentPoints >= $messagePrice){
		    $translatePointToMoney = $messagePrice * $onePointEqual;
			$adminEarning = $translatePointToMoney * ($adminFee / 100);
			$userEarning = $translatePointToMoney - $adminEarning;
			$insertData = $iN->iN_UnLockMessage($userID, $messageID, $chatID, $adminEarning, $userEarning,$messageOwnerID, $translatePointToMoney, $adminFee, $messagePrice);
			if($insertData){
					$cMessageID = $insertData['con_id'];
					$cUserOne = $insertData['user_one'];
					$cUserTwo = $insertData['user_two'];
					$cMessage = $insertData['message'];
					$mSeenStatus = $insertData['seen_status'];
					$gifMoney = isset($insertData['gifMoney']) ? $insertData['gifMoney'] : NULL;
					$privateStatus = isset($insertData['private_status']) ? $insertData['private_status'] : NULL;
				    $privatePrice = isset($insertData['private_price']) ? $insertData['private_price'] : NULL;
					$cStickerUrl = isset($insertData['sticker_url']) ? $insertData['sticker_url'] : NULL;
					$cGifUrl = isset($insertData['gifurl']) ? $insertData['gifurl'] : NULL;
					$cMessageTime = $insertData['time'];
					$ip = $iN->iN_GetIPAddress();
					$query = @unserialize(file_get_contents('http://ip-api.com/php/' . $ip));
					if ($query && $query['status'] == 'success') {
						date_default_timezone_set($query['timezone']);
					}
					$message_time = date("c", $cMessageTime);
					$convertMessageTime = strtotime($message_time);
					$netMessageHour = date('H:i', $convertMessageTime);
					$cFile = isset($insertData['file']) ? $insertData['file'] : NULL;
					$msgDots = '';
					$imStyle = '';
					$seenStatus = '';
					if ($cUserOne == $userID) {
						$mClass = 'me';
						$msgOwnerID = $cUserOne;
						$lastM = '';
						$timeStyle = 'msg_time_me';
						if (!empty($cFile)) {
							$imStyle = 'mmi_i';
						}
						$seenStatus = '<span class="seenStatus flex_ notSeen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
						if ($mSeenStatus == '1') {
							$seenStatus = '<span class="seenStatus flex_ seen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
						}
					} else {
						$mClass = 'friend';
						$msgOwnerID = $cUserOne;
						$lastM = 'mm_' . $msgOwnerID;
						if (!empty($cFile)) {
							$imStyle = 'mmi_if';
						}
						$timeStyle = 'msg_time_fri';
					}
					$styleFor = '';
					if ($cStickerUrl) {
						$styleFor = 'msg_with_sticker';
						$cMessage = '<img class="mStick" src="' . $cStickerUrl . '">';
					}
					if ($cGifUrl) {
						$styleFor = 'msg_with_gif';
						$cMessage = '<img class="mGifM" src="' . $cGifUrl . '">';
					}
					$msgOwnerAvatar = $iN->iN_UserAvatar($msgOwnerID, $base_url);
					include "../themes/$currentTheme/layouts/chat/unLockedMessage.php";
			}else{
			  exit('403');
			}
	   }else{
		  exit('404');
	   }
	}
  }
	/*Show PopUps*/
	if ($type == 'camAlert') {
		if (isset($_POST['al'])) {
			$alertType = $iN->iN_Secure($_POST['al']);
			include "../themes/$currentTheme/layouts/popup_alerts/popup_alerts.php";
		}
	}
	if ($type == 'getBoostList') {
		if(isset($_POST['bp']) && !empty($_POST['bp'])){
           $boostPostID = $iN->iN_Secure($_POST['bp']);
		   include "../themes/$currentTheme/layouts/popup_alerts/getBoostList.php";
		}
	}
	if($type =='boostThisPlan'){
		if(isset($_POST['pbID']) && !empty($_POST['bpID'])){
			$boostPlanID = $iN->iN_Secure($_POST['pbID']);
			$boostPostID = $iN->iN_Secure($_POST['bpID']);
		    $CheckboostIDExist = $iN->CheckBoostPlanExist($boostPlanID);
            if($CheckboostIDExist){
				$boostDetails = $iN->iN_GetBoostPostDetails($boostPlanID);
                $planAmount = $boostDetails['plan_amount'];
				$viewTime = $boostDetails['view_time'];
			    $checkPostBoostedeBefore = $iN->iN_CheckPostBoostedBefore($userID, $boostPostID);
				if($checkPostBoostedeBefore){
                   $getPostDetails = $iN->iN_GetAllPostDetails($boostPostID);
				   $boostedPostSlugUrl = isset($getPostDetails['url_slug']) ? $getPostDetails['url_slug'] : NULL;
				   $redirectThisURL = $base_url.'post/'.$boostedPostSlugUrl.'_'.$boostPostID;
				   echo iN_HelpSecure($redirectThisURL);
				   exit();
				}
				if($planAmount < $userCurrentPoints){
				   $boostInsert = $iN->iN_BoostInsert($userID, $boostPostID, $planAmount,$boostPlanID,$viewTime);
				   if($boostInsert){
						$getPostDetails = $iN->iN_GetAllPostDetails($boostPostID);
						$boostedPostSlugUrl = isset($getPostDetails['url_slug']) ? $getPostDetails['url_slug'] : NULL;
						$redirectThisURL = $base_url.'post/'.$boostedPostSlugUrl.'_'.$boostPostID;
				        echo iN_HelpSecure($redirectThisURL);
				   }
				}else{
					exit('404');
				}
			}
		}
	}
	/*Update Boost Status*/
	if($type == 'updateBoostStatus'){
		if(isset($_POST['bpid']) && !empty($_POST['bpid']) && isset($_POST['mod']) && in_array($_POST['mod'], $yesOrNo)){
		   $bPostID = isset($_POST['bpid']) ? $_POST['bpid'] : NULL;
		   $bpStatus = isset($_POST['mod']) ? $_POST['mod'] : NULL;
           $updateBoostPostStatus = $iN->iN_UpdateBoosPostStatus($userID, $bPostID, $bpStatus);
		   if($updateBoostPostStatus){
              exit('200');
		   }else{
			  exit('404');
		   }
		}
	}
	if ($type == 'uploadPaymentSuccessImage') {
		//$availableFileExtensions
		if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
			$theValidateType = $iN->iN_Secure($_POST['c']);
			foreach ($_FILES['uploading']['name'] as $iname => $value) {
				$name = stripslashes($_FILES['uploading']['name'][$iname]);
				$size = $_FILES['uploading']['size'][$iname];
				$ext = getExtension($name);
				$ext = strtolower($ext);
				$valid_formats = explode(',', $availableVerificationFileExtensions);
				if (in_array($ext, $valid_formats)) {
					if (convert_to_mb($size) < $availableUploadFileSize) {
						$microtime = microtime();
						$removeMicrotime = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', $microtime);
						$UploadedFileName = "image_" . $removeMicrotime . '_' . $userID;
						$getFilename = $UploadedFileName . "." . $ext;
						// Change the image ame
						$tmp = $_FILES['uploading']['tmp_name'][$iname];
						$mimeType = $_FILES['uploading']['type'][$iname];
						$d = date('Y-m-d');
						if (preg_match('/video\/*/', $mimeType)) {
							$fileTypeIs = 'video';
						} else if (preg_match('/image\/*/', $mimeType)) {
							$fileTypeIs = 'Image';
						}
						if (!file_exists($uploadFile . $d)) {
							$newFile = mkdir($uploadFile . $d, 0755);
						}
						if (!file_exists($xImages . $d)) {
							$newFile = mkdir($xImages . $d, 0755);
						}
						if (!file_exists($xVideos . $d)) {
							$newFile = mkdir($xVideos . $d, 0755);
						}
						if (move_uploaded_file($tmp, $uploadFile . $d . '/' . $getFilename)) {
							/*IF FILE FORMAT IS VIDEO THEN DO FOLLOW*/
							if ($fileTypeIs == 'Image') {
								$pathFile = 'uploads/files/' . $d . '/' . $getFilename;
								$pathXFile = 'uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext;
								$postTypeIcon = '<div class="video_n">' . $iN->iN_SelectedMenuIcon('53') . '</div>';
								$thePath = '../uploads/files/' . $d . '/'.$UploadedFileName . '.' . $ext;
								if (file_exists($thePath)) {
									try {
										$dir = "../uploads/pixel/" . $d . "/" . $getFilename;
										$fileUrl = '../uploads/files/' . $d . '/' . $UploadedFileName . '.' . $ext;
										$image = new ImageFilter();
										$image->load($fileUrl)->pixelation($pixelSize)->saveFile($dir, 100, "jpg");
									} catch (Exception $e) {
										echo '<span class="request_warning">' . $e->getMessage() . '</span>';
									}
							    }else{
									exit($LANG['upload_failed']);
								}
								if ($s3Status == '1') {
									/*Upload Video tumbnail*/
									$thevTumbnail = '../uploads/files/' . $d . '/' . $UploadedFileName . '.' . $ext;
									$key = basename($thevTumbnail);
									try {
										$result = $s3->putObject([
											'Bucket' => $s3Bucket,
											'Key' => 'uploads/files/' . $d . '/' . $key,
											'Body' => fopen($thevTumbnail, 'r+'),
											'ACL' => 'public-read',
											'CacheControl' => 'max-age=3153600',
										]);
										$UploadSourceUrl = $result->get('ObjectURL');
										@unlink($uploadFile . $d . '/' . $UploadedFileName . '.' . $ext);
									} catch (Aws\S3\Exception\S3Exception $e) {
										echo $LANG['error_uploading_file'] . "\n";
									}
									$thevTumbnail = '../uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext;
									try {
										$result = $s3->putObject([
											'Bucket' => $s3Bucket,
											'Key' => 'uploads/pixel/' . $d . '/' . $key,
											'Body' => fopen($thevTumbnail, 'r+'),
											'ACL' => 'public-read',
											'CacheControl' => 'max-age=3153600',
										]);
										$UploadSourceUrl = $result->get('ObjectURL');
										@unlink($xImages . $d . '/' . $UploadedFileName . '.' . $ext);
									} catch (Aws\S3\Exception\S3Exception $e) {
										echo $LANG['error_uploading_file'] . "\n";
									}
                        }else if (false && $WasStatus == '1') {
									/*Upload Video tumbnail*/
									$thevTumbnail = '../uploads/files/' . $d . '/' . $UploadedFileName . '.' . $ext;
									$key = basename($thevTumbnail);
									try {
										$result = $s3->putObject([
											'Bucket' => $WasBucket,
											'Key' => 'uploads/files/' . $d . '/' . $key,
											'Body' => fopen($thevTumbnail, 'r+'),
											'ACL' => 'public-read',
											'CacheControl' => 'max-age=3153600',
										]);
										$UploadSourceUrl = $result->get('ObjectURL');
										@unlink($uploadFile . $d . '/' . $UploadedFileName . '.' . $ext);
									} catch (Aws\S3\Exception\S3Exception $e) {
										echo $LANG['error_uploading_file'] . "\n";
									}
									$thevTumbnail = '../uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext;
									try {
										$result = $s3->putObject([
											'Bucket' => $WasBucket,
											'Key' => 'uploads/pixel/' . $d . '/' . $key,
											'Body' => fopen($thevTumbnail, 'r+'),
											'ACL' => 'public-read',
											'CacheControl' => 'max-age=3153600',
										]);
										$UploadSourceUrl = $result->get('ObjectURL');
										@unlink($xImages . $d . '/' . $UploadedFileName . '.' . $ext);
									} catch (Aws\S3\Exception\S3Exception $e) {
										echo $LANG['error_uploading_file'] . "\n";
									}
								}else if($digitalOceanStatus == '1'){
									$thevTumbnail = '../uploads/files/' . $d . '/' . $UploadedFileName . '_' . $userID . '.' . $ext;
									/*IF DIGITALOCEAN AVAILABLE THEN*/
									$my_space = new SpacesConnect($oceankey, $oceansecret, $oceanspace_name, $oceanregion);
									$upload = $my_space->UploadFile($thevTumbnail, "public");
									$thevTumbnail = '../uploads/files/' . $d . '/' . $UploadedFileName . '.' . $ext;
									$my_space = new SpacesConnect($oceankey, $oceansecret, $oceanspace_name, $oceanregion);
									$upload = $my_space->UploadFile($thevTumbnail, "public");
									$thevTumbnail = '../uploads/pixel/' . $d . '/' . $UploadedFileName . '.' . $ext;
									$my_space = new SpacesConnect($oceankey, $oceansecret, $oceanspace_name, $oceanregion);
									$upload = $my_space->UploadFile($thevTumbnail, "public");
									/**/
									@unlink($xImages . $d . '/' . $UploadedFileName . '.' . $ext);
									@unlink($uploadFile . $d . '/' . $UploadedFileName . '.' . $ext);
									if($upload){
										$UploadSourceUrl = 'https://'.$oceanspace_name.'.'.$oceanregion.'.digitaloceanspaces.com/uploads/files/' . $d . '/' . $getFilename;
									 }
									/*/IF DIGITAOCEAN AVAILABLE THEN*/
								 } else {
									$UploadSourceUrl = $base_url . 'uploads/files/' . $d . '/' . $getFilename;
								}
							}
							$insertFileFromUploadTable = $iN->iN_INSERTUploadedScreenShotForPaymentComplete($userID, $pathFile, NULL, $pathXFile, $ext);
							$getUploadedFileID = $iN->iN_GetUploadedFilesIDs($userID, $pathFile);
							/*AMAZON S3*/
							echo '
								<div class="i_uploaded_item in_' . $theValidateType . ' iu_f_' . $getUploadedFileID['upload_id'] . '" id="' . $getUploadedFileID['upload_id'] . '">
								' . $postTypeIcon . '
								<div class="i_delete_item_button" id="' . $getUploadedFileID['upload_id'] . '">
									' . $iN->iN_SelectedMenuIcon('5') . '
								</div>
								<div class="i_uploaded_file" style="background-image:url(' . $UploadSourceUrl . ');">
										<img class="i_file" src="' . $UploadSourceUrl . '" alt="' . $UploadSourceUrl . '">
								</div>
								</div>
							';
						}
					} else {
						echo iN_HelpSecure($size);
					}
				}
			}
		}
	}
	/*Send Account Verificatoun Request*/
	if ($type == 'verificationRequestForBankPayment') {
		if (isset($_POST['cP']) && isset($_POST['pID'])) {
			$cardIDPhoto = $iN->iN_Secure($_POST['cP']);
			$planID = $iN->iN_Secure($_POST['pID']);
			$planData = $iN->GetPlanDetails($planID);
			$planAmount = isset($planData['amount']) ? $planData['amount'] : NULL;
		    $planPoint = isset($planData['plan_amount']) ? $planData['plan_amount'] : NULL;
			$checkCardIDPhotoExist = $iN->iN_CheckImageIDExist($cardIDPhoto, $userID);
			if (empty($cardIDPhoto) && empty($checkCardIDPhotoExist)) {
				echo 'card';
				return false;
			}
			if ($checkCardIDPhotoExist == '1') {
				$InsertNewVerificationRequest = $iN->iN_InsertNewBankPaymentVerificationRequest($userID, $cardIDPhoto, $planAmount, $planPoint,$planID);
				if ($InsertNewVerificationRequest) {
					echo '200';
				}
			} else {
				echo 'both';
			}
		}
	}
	/*Purchase The Frame*/
	if($type == 'buyFrameGift'){
	   if(isset($_POST['type']) && $_POST['type'] != '' && !empty($_POST['type']) && isset($_POST['pUf']) && $_POST['pUf'] != '' && !empty($_POST['pUf'])){
	       $frameID = $iN->iN_Secure($_POST['type']);
	       $purchaseForThisUser = $iN->iN_Secure($_POST['pUf']);
	       $checFrameExist = $iN->CheckFramePlanExist($frameID);
	       $frameData = $iN->GetFramePlanDetails($frameID);
	       $framePrice = isset($frameData['f_price']) ? $frameData['f_price'] : '0';
	       if($checFrameExist && $framePrice < $userCurrentPoints){
	           $insertPurchase = $iN->iN_PurchaseFrame($userID, $purchaseForThisUser, $frameID,$onePointEqual);
	           if($insertPurchase){
	               exit('200');
	           }else{
	               exit('404');
	           }
	       }else {
	       	  exit('505');
	       }
	   }
	}
	/*Update Frame*/
	if($type == 'UpdateMyFrame'){
	    if(isset($_POST['frameID'])){
	        $frameID = $iN->iN_Secure($_POST['frameID']);
	        $updateFrame = $iN->iN_UpdateFrame($userID, $frameID);
	        if($updateFrame){
	            exit('200');
	        }else{
	            exit('400');
	        }
	    }
	}
	if ($type == 'aiBox') {
		include "../themes/$currentTheme/layouts/popup_alerts/aiBox.php";
	}
	if ($type == 'generateAiContent' && $openAiStatus == '1') {
        if (isset($_POST['uPrompt']) && !empty($_POST['uPrompt'])) {
            $userPrompt = trim(strip_tags($_POST['uPrompt']));
            $aiContent = callOpenAI($userPrompt, $opanAiKey);
            if ($aiContent != 'no') {
                $walletDone = $iN->iN_AiUsed($userID, $perAiUse);
                if ($walletDone) {
                    exit($aiContent);
                } else {
                    exit('no_enough_credit');
                }
            } else {
                exit(iN_HelpSecure($LANG['please_check_api_key']));
            }
        }
    }
    if ($type == 'getReelsComment') {
		if (isset($_POST['id'])) {
			$userPostID = $iN->iN_Secure($_POST['id']);
			$getUserComments = $iN->iN_GetPostComments($userPostID, 0);
			if ($getUserComments) {
				foreach ($getUserComments as $comment) {
					$commentID = $comment['com_id'];
					$commentedUserID = $comment['comment_uid_fk'];
					$Usercomment = $comment['comment'];
					$commentTime = isset($comment['comment_time']) ? $comment['comment_time'] : NULL;
					$corTime = date('Y-m-d H:i:s', $commentTime);
					$commentFile = isset($comment['comment_file']) ? $comment['comment_file'] : NULL;
					$stickerUrl = isset($comment['sticker_url']) ? $comment['sticker_url'] : NULL;
					$gifUrl = isset($comment['gif_url']) ? $comment['gif_url'] : NULL;
					$commentedUserIDFk = isset($comment['iuid']) ? $comment['iuid'] : NULL;
					$commentedUserName = isset($comment['i_username']) ? $comment['i_username'] : NULL;
					$commentedUserFullName = isset($comment['i_user_fullname']) ? $comment['i_user_fullname'] : NULL;
					$commentedUserAvatar = $iN->iN_UserAvatar($commentedUserID, $base_url);
					$commentedUserGender = isset($comment['user_gender']) ? $comment['user_gender'] : NULL;
					if ($commentedUserGender == 'male') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					} else if ($commentedUserGender == 'female') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					} else if ($commentedUserGender == 'couple') {
						$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
					}
					$commentedUserLastLogin = isset($comment['last_login_time']) ? $comment['last_login_time'] : NULL;
					$commentedUserVerifyStatus = isset($comment['user_verified_status']) ? $comment['user_verified_status'] : NULL;
					$cuserVerifiedStatus = '';
					if ($commentedUserVerifyStatus == '1') {
						$cuserVerifiedStatus = '<div class="i_plus_comment_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
					}
					$commentLikeBtnClass = 'c_in_like';
					$commentLikeIcon = $iN->iN_SelectedMenuIcon('17');
					$commentReportStatus = $iN->iN_SelectedMenuIcon('32') . $LANG['report_comment'];
					if ($logedIn != 0) {
						$checkCommentLikedBefore = $iN->iN_CheckCommentLikedBefore($userID, $userPostID, $commentID);
						$checkCommentReportedBefore = $iN->iN_CheckCommentReportedBefore($userID, $commentID);
						if ($checkCommentLikedBefore == '1') {
							$commentLikeBtnClass = 'c_in_unlike';
							$commentLikeIcon = $iN->iN_SelectedMenuIcon('18');
						}
						if ($checkCommentReportedBefore == '1') {
							$commentReportStatus = $iN->iN_SelectedMenuIcon('32') . $LANG['unreport'];
						}
					}
					$stickerComment = '';
					$gifComment = '';
					if ($stickerUrl) {
						$stickerComment = '<div class="comment_file"><img src="' . $stickerUrl . '"></div>';
					}
					if ($gifUrl) {
						$gifComment = '<div class="comment_gif_file"><img src="' . $gifUrl . '"></div>';
					}
					$checkUserIsCreator = $iN->iN_CheckUserIsCreator($commentedUserID);
					$cUType = '';
					if($checkUserIsCreator){
						$cUType = '<div class="i_plus_public" id="ipublic_'.$commentedUserID.'">'.$iN->iN_SelectedMenuIcon('9').'</div>';
					}
					include "../themes/$currentTheme/layouts/posts/comments.php";
				}
			} else {
            echo '<div class="no_comments_msg">Henüz yorum yapılmamış.</div>';
        }
    } else {
        echo '<div class="no_comments_msg">Yorumlar şu anda gösterilemiyor.</div>';
    }
}
elseif (isset($_POST['f'])) {
	$loginFormClass = '';
	$type = $iN->iN_Secure($_POST['f']);
	if ($type == 'searchCreator') {
		if (isset($_POST['s'])) {
			$searchValue = $iN->iN_Secure($_POST['s']);
			$searchValueFromData = $iN->iN_GetSearchResult($iN->iN_Secure($searchValue), $showingNumberOfPost, $whicUsers);
			include "../themes/$currentTheme/layouts/header/searchResults.php";
		}
	}
	if ($type == 'forgotPass') {
		if (isset($_POST['email']) && !empty($_POST['email'])) {
			$sendEmail = $iN->iN_Secure($_POST['email']);
			$checkEmailExist = $iN->iN_CheckEmailExistForRegister($iN->iN_Secure($sendEmail));
			if ($checkEmailExist) {
				$code = md5(rand(1111, 9999) . time());
				if ($emailSendStatus == '1') {
					$insertNewCode = $iN->iN_InsertNewForgotPasswordCode($iN->iN_Secure($sendEmail), $iN->iN_Secure($code));
					$activateLink = $base_url . 'reset_password?active=' . $code;
					$wrapperStyle = "width:100%; border-radius:3px; background-color:#fafafa; text-align:center; padding:50px 0; overflow:hidden;";
                    $containerStyle = "width:100%; max-width:600px; border:1px solid #e6e6e6; margin:0 auto; background-color:#ffffff; padding:30px; border-radius:3px;";
                    $logoBoxStyle = "width:100%; max-width:100px; margin:0 auto 30px auto; overflow:hidden;";
                    $imgStyle = "width:100%; display:block;";
                    $titleStyle = "width:100%; position:relative; display:inline-block; padding-bottom:10px;";
                    $buttonBoxStyle = "width:100%; position:relative; padding:10px; background-color:#20B91A; max-width:350px; margin:0 auto; color:#ffffff;";
                    $linkStyle = "text-decoration:none; color:#ffffff; font-weight:500; font-size:18px; display:inline-block;";
					if ($insertNewCode) {
						if ($smtpOrMail == 'mail') {
							$mail->IsMail();
						} else if ($smtpOrMail == 'smtp') {
							$mail->isSMTP();
							$mail->Host = $smtpHost; // Specify main and backup SMTP servers
							$mail->SMTPAuth = true;
							$mail->SMTPKeepAlive = true;
							$mail->Username = $smtpUserName; // SMTP username
							$mail->Password = $smtpPassword; // SMTP password
							$mail->SMTPSecure = $smtpEncryption; // Enable TLS encryption, `ssl` also accepted
							$mail->Port = $smtpPort;
							$mail->SMTPOptions = array(
								'ssl' => array(
									'verify_peer' => false,
									'verify_peer_name' => false,
									'allow_self_signed' => true,
								),
							);
						} else {
							return false;
						}
						$body = '
                            <div style="' . $wrapperStyle . '">
                              <div style="' . $containerStyle . '">

                                <div style="' . $logoBoxStyle . '">
                                  <img src="' . $siteLogoUrl . '" style="' . $imgStyle . '" />
                                </div>

                                <div style="' . $titleStyle . '">
                                  <strong>Forgot your Password?</strong> reset it below:
                                </div>

                                <div style="' . $buttonBoxStyle . '">
                                  <a href="' . $activateLink . '" style="' . $linkStyle . '">
                                    Reset Password
                                  </a>
                                </div>

                              </div>
                            </div>';
						$mail->setFrom($smtpEmail, $siteName);
						$send = false;
						$mail->IsHTML(true);
						$mail->addAddress($sendEmail, ''); // Add a recipient
						$mail->Subject = $iN->iN_Secure($LANG['forgot_password']);
			$mail->CharSet = 'utf-8';
			$mail->MsgHTML($body);
			if (iN_safeMailSend($mail, $smtpOrMail, 'forgot_password')) {
				$mail->ClearAddresses();
				echo '200';
				return true;
			}
					}
				} else {
					echo '3';
				}
			} else {
				echo '2';
			}
		} else {
			exit('1');
		}
	}

	/*Reset Password*/
	if ($type == 'iresetpass') {
		$activationCode = $iN->iN_Secure($_POST['ac']);
		$newPassword = $iN->iN_Secure($_POST['pnew']);
		$confirmNewPassword = $iN->iN_Secure($_POST['repnew']);
		$checkCodeExist = $iN->iN_CheckCodeExist($activationCode);
		if ($checkCodeExist) {
			if (strlen($newPassword) < 6 || strlen($confirmNewPassword) < 6) {
				exit('5');
			}
			if (!empty($newPassword) && $newPassword != '' && isset($newPassword) && !empty($confirmNewPassword) && $confirmNewPassword != '' && isset($confirmNewPassword)) {
				if ($newPassword != $confirmNewPassword) {
					exit('2');
				} else {
					$newPassword = sha1(md5($newPassword));
					$updateNewPassword = $iN->iN_ResetPassword($iN->iN_Secure($activationCode), $iN->iN_Secure($newPassword));
					if ($updateNewPassword) {
						exit('200');
					} else {
						exit('404');
					}
				}
			} else {
				exit('4');
			}
		}
	}
	/*Check Claim*/
	if ($type == 'claim') {
    	if (isset($_POST['clnm']) && !empty($_POST['clnm'])) {
    		$checkUserNameExist = $iN->iN_CheckUsernameExistForRegister($_POST['clnm']);

    		if ($checkUserNameExist) {
    			echo json_encode(['status' => '2']);
    			exit;
    		}

    		if (!preg_match('/^[\w]+$/', $_POST['clnm'])) {
    			echo json_encode(['status' => '5']);
    			exit;
    		}

    		echo json_encode(['status' => '200']);
    		exit;
    	} else {
    		echo json_encode(['status' => '3']);
    		exit;
    	}
    }
}
?>
