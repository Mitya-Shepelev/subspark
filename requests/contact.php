<?php 
include("../includes/inc.php"); 
if(isset($_POST['f'])){
    $type = isset($_POST['f']) ? trim($_POST['f']) : '';
    if($type == 'newContact'){ 
       if(isset($_POST['email_fullname']) && isset($_POST['contact_email']) && isset($_POST['content'])){
         $contacterFullName = trim($_POST['email_fullname']);
         $contacterEmail = trim($_POST['contact_email']);
         $contactMessage = trim($_POST['content']);
            if(ctype_space($contacterFullName) || !isset($contacterFullName) || empty($contacterFullName) || !isset($contacterEmail) || empty($contacterEmail) || !isset($contactMessage) || empty($contactMessage)){
               exit('404');
            }  
            if (!filter_var($contacterEmail, FILTER_VALIDATE_EMAIL)) {
                exit('2');
            }
            if($captchaStatus == 'yes'){
               if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])){  
                  $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$captcha_secret_key.'&response='.$_POST['g-recaptcha-response']); 
                  $responseData = json_decode($verifyResponse); 
                  if($responseData->success){}else{exit('405');} 
               }else{
                  exit('405');
               }
            }else{
               $checkUserSendedEmailBefore = $iN->iN_CheckAlreadyHaveMail($iN->iN_Secure($contacterEmail),$iN->iN_GetIPAddress());
               if($checkUserSendedEmailBefore){
                 exit('1');
               }
            }
            $insertContactMessage = $iN->iN_InsertUserContactMessage($iN->iN_Secure($contacterFullName),$iN->iN_Secure($contacterEmail),$iN->iN_Secure($contactMessage),$iN->iN_GetIPAddress()); 
            if($insertContactMessage){
               echo '200';
            } else{
              echo '404';
            }
       } 
    }
}
?>
