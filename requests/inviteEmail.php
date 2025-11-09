<?php
include "../includes/inc.php";
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//Load Composer's autoloader
require '../includes/phpmailer/vendor/autoload.php';
//Create an instance; passing `true` enables exceptions
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

if (isset($_POST['f']) && $logedIn == '1') {
    $type = isset($_POST['f']) ? trim($_POST['f']) : '';
    if($type == 'inviteFriend'){
        if(isset($_POST['invEmail']) && trim($_POST['invEmail']) != ''){
            $sendEmail = trim($_POST['invEmail']);
            if (!filter_var($sendEmail, FILTER_VALIDATE_EMAIL)) {
                exit('404');
            }
             
            $checkEmailExist = $iN->iN_CheckEmailExistForRegister($sendEmail);
            if($checkEmailExist){
                exit('1');
            }
            $inviteRedirectLink = $base_url.'register?ref='.$userName;
            $invitationNot = html_entity_decode($iN->iN_TextReaplacement($LANG['inviteNot'],[$userName, $userFullName, $siteTitle]));
            $wrapperStyle = "width:100%; border-radius:3px; background-color:#fafafa; text-align:center; padding:50px 0; overflow:hidden;";
            $containerStyle = "width:100%; max-width:600px; border:1px solid #e6e6e6; margin:0 auto; background-color:#ffffff; padding:30px; border-radius:3px;";
            $logoBoxStyle = "width:100%; max-width:100px; margin:0 auto 30px auto; overflow:hidden;";
            $imgStyle = "width:100%; overflow:hidden;";
            $textBoxStyle = "width:100%; position:relative; display:inline-block; padding-bottom:10px;";
            $buttonBoxStyle = "width:100%; position:relative; padding:10px; background-color:#20B91A; max-width:350px; margin:0 auto; color:#ffffff !important;";
            $linkStyle = "text-decoration:none; color:#ffffff !important; font-weight:500; font-size:18px; position:relative;";

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
            
                <div style="' . $textBoxStyle . '">
                  ' . $invitationNot . ' :
                </div>
            
                <div style="' . $buttonBoxStyle . '">
                  <a href="' . $inviteRedirectLink . '" style="' . $linkStyle . '">Join Us</a>
                </div>
            
              </div>
            </div>';
                $fromAddress = $smtpEmail;
                if ($smtpOrMail === 'smtp' && !empty($smtpUserName)) {
                    $fromAddress = $smtpUserName;
                } elseif (!$fromAddress) {
                    $host = parse_url($base_url, PHP_URL_HOST) ?: 'localhost';
                    $fromAddress = 'no-reply@' . $host;
                }
                $mail->setFrom($fromAddress, $siteName);
                if (!empty($smtpEmail) && strcasecmp($fromAddress, $smtpEmail) !== 0) {
                    $mail->addReplyTo($smtpEmail, $siteName);
                }
                $send = false;
                $mail->IsHTML(true);
                $mail->addAddress($sendEmail, 'Invitation'); // Add a recipient
                $mail->Subject = preg_replace( '/{.*?}/', $siteTitle , $LANG['you_r_invited']);
                $mail->CharSet = 'utf-8';
                $mail->MsgHTML($body);
            if (iN_safeMailSend($mail, $smtpOrMail, 'invite_email')) {
                $mail->ClearAddresses();
                echo '200';
                return true;
            }else{
                exit('404');
            }
        }else{
            exit('404');
        }
    }
}
?>
