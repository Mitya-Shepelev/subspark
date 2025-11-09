<?php 
include_once "includes/inc.php";
if(isset($_GET['page']) && !empty($_GET['page']) && $_GET['page'] != ''){
   $pageID = trim($_GET['page']);
   $pageQrCode = isset($_GET['qr']) ? trim($_GET['qr']) : '';
   $decodePageID = base64_decode($pageID);
   $getData = $iN->iN_GetUserDetailsFromUsername($decodePageID);
   if($getData){
      $userProfileID = isset($getData['iuid']) ? $getData['iuid'] : NULL;
      $pageLink = isset($getData['i_username']) ? $getData['i_username'] : NULL;
      $userFullName = isset($getData['i_user_fullname']) ? $getData['i_user_fullname'] : NULL;
      $pageDescription = isset($getData['u_bio']) ? $getData['u_bio'] : $userFullName.' | '.$LANG['meta_description_profile'];
      $pageImage = $iN->iN_UserAvatar($userProfileID, $base_url); 
      $pageUrl = $base_url.$pageLink; 
      $pageTitle = $userFullName . ' | ' .$siteTitle; 
   }
   if(isset($pageQrCode) && !empty($pageQrCode) && $pageQrCode != ''){
     $userQrCode = isset($getData['qr_image']) ? $getData['qr_image'] : NULL;
     if($userQrCode){
        $pageImage = $base_url.$userQrCode;
        $pageDescription = $userFullName.' | '.$LANG['meta_description_qr_code'];
     }else{
        $pageImage = $pageImage;  
     }
   }
   $redirectURL = $base_url.$pageLink;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteTitle;?></title> 
    
    <!-- Primary Meta Tags --> 
<meta name="title" content="<?php echo $siteTitle;?>">
<meta name="description" content="<?php echo $siteDescription;?>">
<meta name="keywords" content="<?php echo $siteKeyWords;?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?php echo $pageUrl;?>">
<meta property="og:title" content="<?php echo $pageTitle;?>">
<meta property="og:description" content="<?php echo $pageDescription;?>"> 
<meta property="og:image" content="<?php echo urldecode($pageImage);?>">
<meta property="og:image:url" content="<?php echo urldecode($pageImage);?>">

<!-- Twitter Meta Tags -->
<meta name="twitter:card" content="<?php echo urldecode($pageImage);?>">
<meta property="twitter:domain" content="<?php echo $pageUrl;?>">
<meta property="twitter:url" content="<?php echo $pageUrl;?>">
<meta name="twitter:title" content="<?php echo $pageTitle;?>">
<meta name="twitter:description" content="<?php echo $pageDescription;?>">
<meta name="twitter:image" content="<?php echo urldecode($pageImage);?>">
</head>

<body> 
<script type="text/javascript"> window.onload = function() { window.location.replace("<?php echo $redirectURL;?>");}</script>
</body>

</html>
