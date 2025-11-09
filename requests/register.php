<?php
session_start();
include "../includes/inc.php";
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require '../includes/phpmailer/vendor/autoload.php';
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

$genders = array('male', 'couple', 'female');
// Ensure defined to avoid notices; set to true after successful session creation
$saveLogin = false;
if ($logedIn == '0') {
    // Backwards-compatible CSRF: if token provided, require it; otherwise allow for legacy clients
    if (isset($_POST['csrf_token']) || isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        include_once __DIR__ . '/../includes/csrf.php';
        if (!csrf_validate_from_request()) {
            exit($LANG['invalid_csrf_token']);
        }
    }

    if (empty($_POST['y_email']) || empty($_POST['flname']) || empty($_POST['uusername']) || empty($_POST['y_password']) || empty($_POST['gender'])) {
        echo '1';
    } else {
		$code = md5(rand(1111, 9999) . time());
        $checkUserNameExist = $iN->iN_CheckUsernameExistForRegister($_POST['uusername']);
        $checkEmailExist = $iN->iN_CheckEmailExistForRegister($_POST['y_email']);
        // DB-level uniqueness check (username or email)
        if (DB::col('SELECT 1 FROM i_users WHERE i_username = ? OR i_user_email = ? LIMIT 1', [$_POST['uusername'], $_POST['y_email']])) {
            exit('2'); // keep legacy code semantics
        }
		/*Username Exist*/
		if ($checkUserNameExist) {
			exit('2');
		}
		/*User Email Exist*/
		if ($checkEmailExist === true) {
			exit('3');
		}
		if(isset($_POST['refuser'])){
            if($_POST['refuser'] != '' && !empty($_POST['refuser'])){
               if($_POST['uusername'] != $_POST['refuser']){
                   $refUserName = trim($_POST['refuser']);
                   $checkRefUserNameExist = $iN->iN_CheckUsernameExistForRegister($iN->iN_Secure($refUserName));
					if($checkRefUserNameExist && $affilateSystemStatus == 'yes'){
						$refAvailable = 'ok';
					}else{
                        $refAvailable = 'not';
					}
			   }
			}
		} else{
			$refAvailable = '';
		}
		/*Username Character Lenght*/
		if (strlen($_POST['uusername']) < 5 OR strlen($_POST['uusername']) > 32) {
			exit('4');
		}
		/*Invalid Character*/
		if (!preg_match('/^[\w]+$/', $_POST['uusername'])) {
			exit('5');
		}
		$validUserNames = explode(',', $disallowedUserNames);
		if(in_array(strtolower($_POST['uusername']), $validUserNames)){
		  exit('2');
		}
		/*Invalid Email*/
		if (!filter_var($_POST['y_email'], FILTER_VALIDATE_EMAIL)) {
			exit('6');
		}
		/*Password Short*/
		if (strlen($_POST['y_password']) < 6) {
			exit('7');
		}
		$gender = isset($genders[0]) ? $genders[0] : 'male';
		if (isset($_POST['gender']) && in_array($_POST['gender'], $genders)) {
			$gender = $_POST['gender'];
		}
        $get_ip = $iN->iN_GetIPAddress();
        // Use HTTPS for IP geolocation lookup
        $registerCountryCode = $reigsetrUserTimeZone = $registerUserCity = $registerUserLatitude = $registerUserLongitude = null;
        $rawIpInfo = $iN->iN_fetchDataFromURL("https://ip-api.com/json/$get_ip");
		$rCountryCode = $rUTimeZone = $rUserCity = $rUserLat = $rUserLon = '';
		$getIpInfo = json_decode($rawIpInfo, true);
		if (is_array($getIpInfo) && ($getIpInfo['status'] ?? '') === 'success') {
			if (!empty($getIpInfo['countryCode']) && !empty($getIpInfo['timezone']) && !empty($getIpInfo['city'])) {
				$registerCountryCode = $getIpInfo['countryCode'];
				$reigsetrUserTimeZone = $getIpInfo['timezone'];
				$registerUserCity = $getIpInfo['city'];
				$registerUserLatitude = $getIpInfo['lat'] ?? null;
				$registerUserLongitude = $getIpInfo['lon'] ?? null;
			}
		}
		$registerUserName = $iN->iN_Secure($_POST['uusername']);
		$registerUserFullName = $iN->iN_Secure($_POST['flname']);
		$registerEmail = $iN->iN_Secure($_POST['y_email']);
		$registerGender = $iN->iN_Secure($gender);
		$registerUserName = $iN->iN_Secure($_POST['uusername']);
        // Use strong password hashing
        $registerUserPassword = password_hash($_POST['y_password'], PASSWORD_DEFAULT);
        $time = time();
		$rCountryCode = !empty($registerCountryCode) ? "'$registerCountryCode'" : "NULL";
		$rUTimeZone = !empty($reigsetrUserTimeZone) ? "'$reigsetrUserTimeZone'" : "NULL";
		$rUserCity = !empty($registerUserCity) ? "'$registerUserCity'" : "NULL";
		$rUserLat = !empty($registerUserLatitude) ? "'$registerUserLatitude'" : "NULL";
		$rUserLon = !empty($registerUserLongitude) ? "'$registerUserLongitude'" : "NULL";
        // charset handled by PDO connection
        $defaultLanguage = strtolower($defaultLanguage);
        // Insert user (PDO-only)
        $sql = "INSERT INTO i_users(i_username,i_user_fullname,i_user_email,i_password,user_gender,registered,profile_category,last_login_time,online_offline_status,countryCode,u_timezone,lat,lon,lang) VALUES (?,?,?,?,?,?, 'normal_user', ?, '1', $rCountryCode, $rUTimeZone, $rUserLat, $rUserLon, ?)";
        DB::exec($sql, [
            $registerUserName,
            $registerUserFullName,
            $registerEmail,
            $registerUserPassword,
            $registerGender,
            $time,
            $time,
            $defaultLanguage
        ]);
        $registerUser = true;
		if ($registerUser) {
			$userID = (int) DB::lastId();
			$time = time();
			if($beaCreatorStatus == 'auto_approve'){
				DB::exec("UPDATE i_users SET certification_status = '2', validation_status = '2', condition_status = '2', fees_status = '2' , payout_status = '1' WHERE iuid = ?", [$userID]);
			}
			DB::exec("UPDATE i_users SET last_login_time = ? WHERE iuid = ?", [$time,$userID]);
			DB::exec("INSERT INTO i_friends (fr_one,fr_two,fr_time,fr_status) VALUES (?,?,?,'me')", [$userID,$userID,$time]);
			if($refAvailable == 'ok' && $affilateSystemStatus == 'yes'){
			   $refOwnerData = $iN->iN_GetUserDetailsFromUsername($refUserName);
			   $refOwnerUserID = $iN->iN_Secure($refOwnerData['iuid']);
			   $checkAffiliatedBefore = $iN->iN_CheckUserAffilatedBefore($get_ip,$refOwnerUserID);
			   if($ataAffilateAmount && $checkAffiliatedBefore === true){
				 DB::exec("INSERT INTO i_refUsers (ref_owner_user_id, ref_user_id, ref_type, time, ip) VALUES (?,?,?,?,?)", [$refOwnerUserID,$userID,'reg',$time,$get_ip]);
				 DB::exec("UPDATE i_users SET affilate_earnings = affilate_earnings + ? WHERE iuid = ?", [$ataAffilateAmount,$refOwnerUserID]);
			   }
			}
            // Strong random session key and secure cookie attributes
            $hash = bin2hex(random_bytes(32));
            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
            setcookie($cookieName, $hash, [
                'expires'  => time() + 31556926,
                'path'     => '/',
                'secure'   => $secure,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            DB::exec("INSERT INTO i_sessions (session_uid, session_key, session_time) VALUES (?,?,?)", [$userID, $hash, $time]);
            // Mark login state as saved for post-registration flow
            $saveLogin = true;
			if($autoFollowAdmin == 'yes'){
			    $admins = DB::all("SELECT iuid FROM i_users WHERE userType = '2'");
			    foreach ($admins as $ad) {
			        $AdminID = isset($ad['iuid']) ? (int)$ad['iuid'] : 0;
			        if ($AdminID) { DB::exec("INSERT INTO i_friends (fr_one,fr_two,fr_time,fr_status) VALUES (?,?,?,'flwr')", [$userID,$AdminID,$time]); }
			    }
			}
			$_SESSION['iuid'] = $userID;
			if ($emailSendStatus == '1') {
				DB::exec("UPDATE i_users SET verify_key = ? WHERE iuid = ?", [$code,$userID]);
	            $sendEmail = $registerEmail;
				
				$mailMode = 'mail';
				if ($smtpOrMail === 'smtp' && !empty($smtpHost)) {
				    $mailMode = 'smtp';
				    $mail->isSMTP();
				    $mail->Host = $smtpHost;
				    $mail->SMTPAuth = true;
				    $mail->SMTPKeepAlive = true;
				    $mail->Username = $smtpUserName;
				    $mail->Password = $smtpPassword;
				    $mail->SMTPSecure = $smtpEncryption;
				    $mail->Port = $smtpPort;
				    $mail->SMTPOptions = array(
				        'ssl' => array(
				            'verify_peer' => false,
				            'verify_peer_name' => false,
				            'allow_self_signed' => true,
				        ),
				    );
				} else {
				    $mail->IsMail();
				}

				$instagramIcon = $iN->iN_SelectedMenuIcon('88');
				$facebookIcon = $iN->iN_SelectedMenuIcon('90');
				$twitterIcon = $iN->iN_SelectedMenuIcon('34');
				$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
				$startedFollow = $iN->iN_Secure($LANG['now_following_your_profile']);
				$theCode = $base_url.'verify?v='.$code;
				include_once '../includes/mailTemplates/verificationTemplate.php';
				$body = $bodyVerifyEmail;
				$fromEmail = !empty($smtpEmail) ? $smtpEmail : (!empty($siteEmail) ? $siteEmail : 'no-reply@' . parse_url($base_url, PHP_URL_HOST));
				$mail->setFrom($fromEmail, $siteName);
				$send = false;
				$mail->IsHTML(true);
				$mail->addAddress($sendEmail, '');
				$mail->Subject = $iN->iN_Secure($LANG['verify_your_email']);
				$mail->CharSet = 'utf-8';
				$mail->MsgHTML($body);
				
				if (iN_safeMailSend($mail, $mailMode, 'register_verification')) {
				    $mail->ClearAddresses();
				    exit('8');
				}
				exit('9');
	            }

				// If email verification is not required, finish with success response for the front-end redirect.
				if ($saveLogin && $emailSendStatus != '1') {
					exit('8');
				}
		}
	}
}
?>
