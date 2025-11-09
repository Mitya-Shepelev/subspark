<?php
if($logedIn == 0){
    header('Location: ' . route_url('404'));
}else{ 
 require_once 'includes/payment/vendor/autoload.php';
 if (!defined('INORA_METHODS_CONFIG')) {
     define('INORA_METHODS_CONFIG', realpath('includes/payment/paymentConfig.php'));
 }
// Get config data
$configData = configItem();
    include("themes/$currentTheme/payment-success.php");
}
$uData = $iN->iN_LatestPaymentPoint($userID);
$payedUserID = isset($uData['payer_iuid_fk']) ? $uData['payer_iuid_fk'] : NULL;
$purchasedPointID = isset($uData['credit_plan_id']) ? $uData['credit_plan_id'] : NULL;
$paymentID = isset($uData['payment_id']) ? $uData['payment_id'] : NULL;
$productID = isset($uData['paymet_product_id']) ? $uData['paymet_product_id'] : NULL;

if(!empty($purchasedPointID)){
    $planData = $iN->GetPlanDetails($purchasedPointID);
    $planPoint = isset($planData['plan_amount']) ? $planData['plan_amount'] : NULL;
    $planMoney = isset($planData['amount']) ? $planData['amount'] : NULL;
    $youBought = $LANG['you_bought_points'];
    $pointPurchaseDetails = $iN->iN_TextReaplacement($LANG['thank_you_for_purchase_not'], [$planPoint, $planMoney]);
    $pointPurchasesuccess = $LANG['bought_point_success'];
    $startUsingYourPoints = $LANG['start_using_your_points'];
    $iN->iN_UpdatePaymentSuccessStatusAmount($userID, $paymentID, $planMoney);

}else if(!empty($productID)){
    $prData = $iN->iN_GetProductDetailsByID($productID);
    $productSlug = isset($prData['pr_name_slug']) ? $prData['pr_name_slug'] : NULL;
    $planPoint = isset($prData['pr_price']) ? $prData['pr_price'] : NULL;
    $planMoney = isset($prData['amount']) ? $prData['amount'] : NULL;
    $youBought = $LANG['you_bought_a_product'];
    $pointPurchaseDetails = $LANG['thank_you_for_purchase_product_not'];
    $pointPurchasesuccess = $LANG['bought_product_success'];
    $startUsingYourPoints = $LANG['check_your_bought_products'];
    header('Location: ' . route_url('product/'.$productSlug.'_'.$productID));
}
$iN->iN_UpdatePaymentSuccessStatus($userID, $paymentID); 

$getPayedUserDetails = $iN->iN_GetUserDetails($payedUserID);
$sendEmail = isset($getPayedUserDetails['i_user_email']) ? $getPayedUserDetails['i_user_email'] : NULL;
$sendName = isset($getPayedUserDetails['i_user_fullname']) ? $getPayedUserDetails['i_user_fullname'] : NULL;

include_once 'includes/mail/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer;

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
$notQualifyDocument = $LANG['not_qualify_document'];
$instagramIcon = $iN->iN_SelectedMenuIcon('88');
$facebookIcon = $iN->iN_SelectedMenuIcon('90');
$twitterIcon = $iN->iN_SelectedMenuIcon('34');
$linkedinIcon = $iN->iN_SelectedMenuIcon('89');
if(!empty($productID)){
    include_once "includes/mailTemplates/productPurchaseMailTemplate.php";
}else{
    include_once "includes/mailTemplates/pointPurchaseMailTemplate.php";
}
$body = $bodyPointPurchased;
$mail->setFrom($smtpUserName, $siteName);
$send = false;
$mail->IsHTML(true);
$mail->addAddress($sendEmail); // Add a recipient
$mail->Subject = preg_replace( '/{.*?}/', $planPoint, $youBought);
$mail->CharSet = 'utf-8';
$mail->MsgHTML($body); 
if ($mail->send()) {
    $mail->ClearAddresses();
    return true;
}

?>
