<?php
if($logedIn == 0){
    $userID = '0';
}
$profileData = $iN->iN_GetUserDetailsFromUsername($urlMatch);
$p_username = $profileData['i_username'];
$p_userfullname = $profileData['i_user_fullname'];
if($fullnameorusername == 'no'){
   $p_userfullname = $p_username;
 }
$p_profileID = $profileData['iuid'];
if($p_profileID != $userID){
   $get_ip = $iN->iN_GetIPAddress();
   $getIpInfo = $iN->iN_fetchDataFromURL("http://ip-api.com/json/$get_ip");
	$rCountryCode = $rUTimeZone = $rUserCity = $rUserLat = $rUserLon = '';
	$getIpInfo = json_decode($getIpInfo, true);
   $registerCountryCode = isset($getIpInfo['countryCode']) ? $getIpInfo['countryCode'] : NULL;
   $CheckCode = $iN->iN_CheckCountryBlocked($p_profileID, $registerCountryCode);
   if($CheckCode){
      include("sources/404.php");
      exit();
   }
}
$p_profileAvatar = $iN->iN_UserAvatar($p_profileID, $base_url);
$p_profileCover = $iN->iN_UserCover($p_profileID, $base_url);

// OPTIMIZATION: Get all relationship data in ONE query instead of 5-6 separate queries
$relationships = $iN->iN_GetProfileRelationships($userID, $p_profileID);
$p_friend_status = $relationships['friendship_status'];
$p_subscription_type = $relationships['subscription_type'];
$p_userGender = $profileData['user_gender'];
$p_VerifyStatus = $profileData['user_verified_status'];
$p_lastSeen = $profileData['last_login_time'];
$p_registered = $profileData['registered'];
$p_who_can_message = $profileData['who_can_message'];
$p_showHidePosts = $profileData['show_hide_posts'];
$p_MessageCanSend = $profileData['message_status'];
$p_profileCategory = $profileData['profile_category'];
$pCertificationStatus = $profileData['certification_status'];
$pValidationStatus = $profileData['validation_status'];
$feesStatus = $profileData['fees_status'];
$p_frame = isset($profileData['user_frame']) ? $profileData['user_frame'] : NULL;

$pCategory = '';
if(isset($PROFILE_CATEGORIES[$p_profileCategory])){
   $pCt = isset($PROFILE_CATEGORIES[$p_profileCategory]) ? $PROFILE_CATEGORIES[$p_profileCategory] : NULL;
}else if(isset($PROFILE_SUBCATEGORIES[$p_profileCategory])){
   $pCt = isset($PROFILE_SUBCATEGORIES[$p_profileCategory]) ? $PROFILE_SUBCATEGORIES[$p_profileCategory] : NULL;
}
if($p_profileCategory){
  $pCategory = '<div class="i_creator_category"><a href="'.$base_url.'creators?creator='.$p_profileCategory.'">'.$iN->iN_SelectedMenuIcon('65').$pCt.'</a></div>';
}
$p_profileBio = isset($profileData['u_bio']) ? $profileData['u_bio'] : NULL;
$siteTitle = $p_userfullname;
$siteDescription = $p_profileBio;
$metaBaseUrl = $p_profileAvatar;
$registeredTime = date('d/m/Y', $p_registered);
$p_lsTime = '<div class="i_p_lpt_offline">'.$LANG['joined'].' '.$registeredTime.'</div>';
$p_lastSeenTimeStatus = $profileData['online_offline_status'];
$lastLoginDateTime = date("c", $p_lastSeen);
$p_crTime = date('Y-m-d H:i:s',$p_lastSeen);
$lastSeenTreeMinutesAgo = time() - 180; 
$pTime = '';
if($p_lastSeenTimeStatus == '1'){
   if($p_lastSeen > $lastSeenTreeMinutesAgo){
      $pTime = '<div class="i_p_lpt_online">'.$LANG['online_now'].'</div>';
   }else{
      $pTime = '<div class="i_p_lpt_offline">'.TimeAgo::ago($p_crTime , date('Y-m-d H:i:s')).'</div>';
   }
}
if($p_userGender == 'male'){
   $pGender = '<div class="i_pr_m">'.$iN->iN_SelectedMenuIcon('12').'</div>';
}else if($p_userGender == 'female'){
   $pGender = '<div class="i_pr_fm">'.$iN->iN_SelectedMenuIcon('13').'</div>';
}else if($p_userGender == 'couple'){
   $pGender = '<div class="i_pr_co">'.$iN->iN_SelectedMenuIcon('58').'</div>';
}
$pVerifyStatus = '';
if($p_VerifyStatus == '1'){
    $pVerifyStatus = '<div class="i_pr_vs">'.$iN->iN_SelectedMenuIcon('11').'</div>';
}
$p_profileStatus = $profileData['profile_status'];
$p_is_creator = '';
if($p_profileStatus == '2'){
  $p_is_creator = '<div class="creator_badge">'.$iN->iN_SelectedMenuIcon('9').'</div>';
}
$profileUrl = $base_url.$p_username;

// OPTIMIZATION: Get all profile stats in ONE query instead of 9 separate queries
$profileStats = $iN->iN_GetProfileStats($p_profileID);
$totalPost = $profileStats['total_posts'];
$totalImagePost = $profileStats['total_images'];
$totalVideoPosts = $profileStats['total_videos'];
$totalReelsPosts = $profileStats['total_reels'];
$totalAudioPosts = $profileStats['total_audios'];
$totalProducts = $profileStats['total_products'];
$totalFollowingUsers = $profileStats['total_following'];
$totalFollowerUsers = $profileStats['total_followers'];
$totalSubscribers = $profileStats['total_subscribers'];

// Use is_creator from relationships query
$checkUserisCreator = $relationships['is_creator'];
if($p_friend_status == 'flwr'){
   $flwrBtn = 'i_btn_like_item_flw f_p_follow';
   $flwBtnIconText = $iN->iN_SelectedMenuIcon('66').$LANG['unfollow'];
}else{
   if($logedIn == 0){
      $flwrBtn = 'i_btn_like_item loginForm';
      $flwBtnIconText = $iN->iN_SelectedMenuIcon('66').$LANG['follow'];
   }else{
      $flwrBtn = 'i_btn_like_item free_follow';
      $flwBtnIconText = $iN->iN_SelectedMenuIcon('66').$LANG['follow'];
   }
}
if($p_friend_status != 'subscriber'){
   if($logedIn == 0){
     $subscBTN = 'loginForm';
   }else{
      $subscBTN = 'uSubsModal';
   }
}
$blockBtn = 'ublknot';
$sendMessage = '';
if($p_MessageCanSend == '1'){
   $sendMessage = '<div class="i_btn_item transition"><div class="newMessageMe flex_ tabing ownTooltip" data-label="'.iN_HelpSecure($LANG['send_message']).'" id="'.$p_profileID.'">'.$iN->iN_SelectedMenuIcon('38').'</div></div>';
   if($logedIn == 1){
     // Use conversation_id from relationships query
     $checkChatStartedBefore = $relationships['conversation_id'];
     if($checkChatStartedBefore){
         if($p_who_can_message == '1' && $p_friend_status != 'subscriber'){
            if($checkUserisCreator){
               $sendMessage = '<div class="i_btn_item transition"><div class="uSubsModal flex_ tabing ownTooltip" data-label="'.iN_HelpSecure($LANG['send_message']).'" id="'.$p_profileID.'" data-u="'.$p_profileID.'">'.$iN->iN_SelectedMenuIcon('38').'</div></div>';
            }else{
               $sendMessage = '';
            }
         }else{
            $sendMessage = '<div class="i_btn_item transition tabing ownTooltip" data-label="'.iN_HelpSecure($LANG['send_message']).'"><a href="'.$base_url.'chat?chat_width='.$checkChatStartedBefore.'">'.$iN->iN_SelectedMenuIcon('38').'</a></div>';
         }
     }
   }
}

if($logedIn == 0){
   $blockBtn = 'loginForm';
   $sendMessage = '<div class="i_btn_item transition"><a class="loginForm">'.$iN->iN_SelectedMenuIcon('38').'</a></div>';
}
$blockedType ='';
if($userID != $p_profileID){
   // Use block status from relationships query
   $checkUserinBlockedList = $relationships['blocked_by_me'];
   $checkVisitedProfileBlockedVisitor = $relationships['blocked_by_them'];
   if($checkUserinBlockedList){
      $blockedType = $relationships['block_type'];
      $sendMessage = '';
   }else if($checkVisitedProfileBlockedVisitor){
      $blockedType = $relationships['block_type'];
      $sendMessage = '';
   }
}
if($blockedType == '2'){
   include("sources/404.php");
} else {
    $page = 'profile';
   include("themes/$currentTheme/layouts/profile.php");
}
?>
