<?php
if($logedIn === '0'){
    $userID = '';
    $BoostedPost = $iN->iN_ShowBoostedPostNoneLogin();
    $loginFormClass = 'loginForm';
}else{
   $BoostedPost = $iN->iN_ShowBoostedPost($userID, $showingNumberOfPost);
   $loginFormClass = '';
}
if($BoostedPost){
   foreach($BoostedPost as $postFromData){
    $userPostID = $postFromData['post_id'];
    if($page == 'purchasedpremiums' || $page == 'morepurchased'){
      $userPostID = $postFromData['payment_id'] ?? null;
    }else if($page == 'moreboostedposts'){
      $userPostID = $postFromData['boost_id'] ?? null;
    }
    $userPostOwnerID = $postFromData['post_owner_id'] ?? null;
    $userPostText = $postFromData['post_text'] ?? null;
    $userPostFile = $postFromData['post_file'];
    $userPostCreatedTime = $postFromData['post_created_time'] ?? null;
    $crTime = date('Y-m-d H:i:s',$userPostCreatedTime);
    $userPostWhoCanSee = $postFromData['who_can_see'] ?? null;
    $userPostWantStatus = $postFromData['post_want_status'] ?? null;
    $userPostWantedCredit = $postFromData['post_wanted_credit'] ?? null;
    $userPostStatus = $postFromData['post_status'] ?? null;
    $userPostOwnerUsername = $postFromData['i_username'] ?? null;
    $userPostOwnerUserFullName = $postFromData['i_user_fullname'] ?? null;
    $userProfileFrame = $postFromData['user_frame'] ?? null;
    $userProfileCategory = $postFromData['profile_category'] ?? null;
    $checkPostBoosted = $iN->iN_CheckPostBoostedBefore($userPostOwnerID, $userPostID);
    $planIcon = '';
    $checkCount = ''; 
    $viewCount = '';
    if($checkPostBoosted){
      $getBoostDetails = $iN->iN_GetBoostedPostDetails($userPostID);
      $boostPlanID = $getBoostDetails['boost_type'] ?? null;
      $boostStatus = $getBoostDetails['status'] ?? null;
      $boostID = $getBoostDetails['boost_id'] ?? null;
      $viewCount = $getBoostDetails['view_count'] ?? null;
      $boostPostOwnerID = $getBoostDetails['iuid_fk'] ?? null;
      $planDetails = $iN->iN_GetBoostPostDetails($boostPlanID);
      $planIcon = '<div class="boostIcon flex_ justify-content-align-items-center">'.$planDetails['plan_icon'].$LANG['boosted_post'].'</div>';
      $userIP = $iN->iN_GetIPAddress();
      if($logedIn != 0){
        if($userID != $boostPostOwnerID){
             $checkCount = $iN->iN_CountSeenBoostedPostbyID($userPostOwnerID,$boostID);
             if($checkCount != $viewCount){
                $iN->iN_BoostPostSeenCounter($userID, $boostID, $userIP);
             }
             if($checkCount == $viewCount){
                $iN->iN_UpdateBoostedPostStatus($userPostOwnerID, $boostID);
             }
        }  
      }
    }

    if($fullnameorusername == 'no'){
       $userPostOwnerUserFullName = $userPostOwnerUsername;
    }
    $userPostOwnerUserGender = $postFromData['user_gender'] ?? null;
    $userTextForPostTip = $postFromData['thanks_for_tip'] ?? $LANG['thanks_for_tip'];
    $getUserPaymentMethodStatus = $postFromData['payout_method'] ?? null;
    $userPostHashTags = $postFromData['hashtags'] ?? null;
    $userPostCommentAvailableStatus = $postFromData['comment_status'] ?? null;
    $userPostOwnerUserLastLogin = $postFromData['last_login_time'] ?? null;
    $lastSeen = date("c", $userPostOwnerUserLastLogin);
	 $OnlineStatus = date("c", time());
    $oStatus = time() - 35;
    if ($userPostOwnerUserLastLogin > $oStatus) {
      $timeStatus = '<div class="userIsOnline flex_ tabing">'.$LANG['online'].'</div>';
    } else {
      $timeStatus = '<div class="userIsOffline flex_ tabing">'.$LANG['offline'].'</div>';
    }
    $userPostPinStatus = $postFromData['post_pined'] ?? null;
    $slugUrl = $base_url.'post/'.$postFromData['url_slug'].'_'.$userPostID;
    $userPostSharedID = $postFromData['shared_post_id'] ?? null;
    $userPostOwnerUserAvatar = $iN->iN_UserAvatar($userPostOwnerID, $base_url);
    $userPostUserVerifiedStatus = $postFromData['user_verified_status'] ?? null;
    if($userPostOwnerUserGender == 'male'){
       $publisherGender = '<div class="i_plus_g">'.$iN->iN_SelectedMenuIcon('12').'</div>';
    }else if($userPostOwnerUserGender == 'female'){
       $publisherGender = '<div class="i_plus_gf">'.$iN->iN_SelectedMenuIcon('13').'</div>';
    }else if($userPostOwnerUserGender == 'couple'){
       $publisherGender = '<div class="i_plus_g">'.$iN->iN_SelectedMenuIcon('58').'</div>';
    }
    $userVerifiedStatus = '';
    if($userPostUserVerifiedStatus == '1'){
       $userVerifiedStatus = '<div class="i_plus_s">'.$iN->iN_SelectedMenuIcon('11').'</div>';
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
    $postStyle = '';
    if(empty($userPostText)){
        $postStyle = 'nonePoint';
    } 
   $getUserComments = $iN->iN_GetPostComments($userPostID, 0);
   $c = 1;
   $TotallyPostComment = '';
   if ($c) {
      if ($getUserComments > 0) {
         $CountTheUniqComment = count($getUserComments);
         $SecondUniqComment = $CountTheUniqComment - 2;
         if ($CountTheUniqComment > 2) {
            $getUserComments = $iN->iN_GetPostComments($userPostID, 2);
            $TotallyPostComment = '<div class="lc_sum_container lc_sum_container_'.$userPostID.'"><div class="comnts transition more_comment" id="od_com_'.$userPostID.'" data-id="'.$userPostID.'">'.preg_replace( '/{.*?}/', $SecondUniqComment, $LANG['t_comments']).'</div></div>';
         }
      }
   }
   $pSaveStatusBtn = $iN->iN_SelectedMenuIcon('22');
   if($logedIn == 0){
      $getFriendStatusBetweenTwoUser = '1';
      $checkPostLikedBefore ='';
      $checkPostReportedBefore = '';
      $checkUserPurchasedThisPost = '0';
   }else{
      $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $userPostOwnerID);
      $checkPostLikedBefore = $iN->iN_CheckPostLikedBefore($userID, $userPostID);
      $checkPostReportedBefore = $iN->iN_CheckPostReportedBefore($userID, $userPostID);
      if($iN->iN_CheckPostSavedBefore($userID, $userPostID) == '1'){
         $pSaveStatusBtn = $iN->iN_SelectedMenuIcon('63');
      }
      if($page == 'purchasedpremiums' || $page == 'morepurchased'){
         $checkUserPurchasedThisPost = $iN->iN_CheckUserPurchasedThisPost($userID, $postFromData['post_id']);
      }else{
         $checkUserPurchasedThisPost = $iN->iN_CheckUserPurchasedThisPost($userID, $userPostID);
      }
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
      $onlySubs = '<div class="com_min_height"></div><div class="onlyPremium"><div class="onlySubsWrapper"><div class="premium_locked"><div class="premium_locked_icon">'.$iN->iN_SelectedMenuIcon('56').'</div></div><div class="onlySubs_note"><div class="buyThisPost prcsPost" id="'.$userPostID.'">'.preg_replace( '/{.*?}/', $userPostWantedCredit, $LANG['post_credit']).'</div><div class="buythistext prcsPost" id="'.$userPostID.'">'.$LANG['purchase_post'].'</div></div><div class="fr_subs uSubsModal transition" data-u="'.$userPostOwnerID.'">'.$iN->iN_SelectedMenuIcon('51').$LANG['free_for_subscribers'].'</div></div></div>';
    }
   $postReportStatus = $iN->iN_SelectedMenuIcon('32').$LANG['report_this_post'];
   if($checkPostReportedBefore == '1'){
      $postReportStatus = $iN->iN_SelectedMenuIcon('32').$LANG['unreport'];
   }
   if($checkPostLikedBefore){
      $likeIcon = $iN->iN_SelectedMenuIcon('18');
      $likeClass = 'in_unlike';
   }else{
      $likeIcon = $iN->iN_SelectedMenuIcon('17');
      $likeClass = 'in_like';
   }
   if($userPostCommentAvailableStatus == '1'){
      $commentStatusText = $LANG['disable_comment'];
   }else{
      $commentStatusText = $LANG['enable_comments'];
   }
   $pPinStatus = '';
   $pPinStatusBtn = $iN->iN_SelectedMenuIcon('29').$LANG['pin_on_my_profile'];
   if($userPostPinStatus == '1'){
     $pPinStatus = '<div class="i_pined_post" id="i_pined_post_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('62').'</div>';
     $pPinStatusBtn = $iN->iN_SelectedMenuIcon('29').$LANG['post_pined_on_your_profile'];
   }
   $waitingApprove = '';
   $likeSum = $iN->iN_TotalPostLiked($userPostID);
   if($likeSum > '0'){
      $likeSum = $likeSum;
   }else{
      $likeSum = '';
   }
      if($checkCount != $viewCount && $checkPostBoosted){
         
         if($userPostStatus == '2') {
            $ApproveNot = $iN->iN_GetAdminNot($userPostOwnerID, $userPostID);
            $aprove_status = isset($ApproveNot['approve_status']) ? $ApproveNot['approve_status'] : NULL;
            $a_not = $iN->iN_SelectedMenuIcon('10').$LANG['waiting_for_approve'];
            $theApproveNot = isset($ApproveNot['approve_not']) ? $ApproveNot['approve_not'] : NULL;
            if($aprove_status == '2'){
               $a_not = $iN->iN_SelectedMenuIcon('113').$LANG['request_rejected'].' '.$theApproveNot;
            }else if($aprove_status == '3'){
               $a_not = $iN->iN_SelectedMenuIcon('114').$LANG['declined'].' '.$theApproveNot;
            }
            $waitingApprove = '<div class="waiting_approve flex_">'.$a_not.'</div>';
            if($logedIn == 0){
               echo '<div class="i_post_body nonePoint body_'.$userPostID.'" id="'.$userPostID.'" data-last="'.$userPostID.'"></div>';
            }else{
               if($userID == $userPostOwnerID){
                  if(empty($userPostFile)){
                     include("textPost.php");
                  }else{
                     include("ImagePost.php");
                  }
               }else{
                  echo '<div class="i_post_body nonePoint body_'.$userPostID.'" id="'.$userPostID.'" data-last="'.$userPostID.'"></div>';
               }
            }
         }else{
            if(empty($userPostFile)){
               include("textPost.php");
            }else{
               include("ImagePost.php");
            }
         }
      }
   }
}
?>