<?php
$lastPostID = $_POST['last'] ?? '';
$notFoundNot = '';
$loginFormClass = '';

$postsFromData = $postsFromDataProduct = $profileFollowers = $profileFollowing = $profileSubscriber = $userTextForPostTip = '';

// If user is not logged in
if ($logedIn === '0') {
    $loginFormClass = 'loginForm';

    if ($page === 'moreposts') {
        $postsFromData = $iN->iN_AllFriendsPostsOut($lastPostID, $showingNumberOfPost);
    } elseif ($page === 'profile') {
        if (isset($pCat) && $pCat === 'reels') {
            $postsFromData = $iN->iN_AllUserProfilePostsByChooseReels($p_profileID, $lastPostID, $showingNumberOfPost);
        } else {
            $postsFromData = $iN->iN_AllUserProfilePosts($p_profileID, $lastPostID, $showingNumberOfPost);
        }
    } elseif ($page === 'hashtag') {
        $postsFromData = $iN->iN_GetHashTagsSearch($pageFor, $lastPostID, $showingNumberOfPost);
        $notFoundNot = $LANG['no_post_will_be_shown'];
    }
} else {
    if (in_array($page, ['moreposts', 'friends'], true)) {
        $postsFromData = $iN->iN_AllFriendsPosts($userID, $lastPostID, $showingNumberOfPost);
    } elseif ($page === 'profile') {
        switch ($pCat) {
            case 'audios':
                $postsFromData = $iN->iN_AllUserProfilePostsByChooseAudios($p_profileID, $lastPostID, $showingNumberOfPost);
                break;
            case 'videos':
                $postsFromData = $iN->iN_AllUserProfilePostsByChooseVideos($p_profileID, $lastPostID, $showingNumberOfPost);
                break;
            case 'reels':
                $postsFromData = $iN->iN_AllUserProfilePostsByChooseReels($p_profileID, $lastPostID, $showingNumberOfPost);
                break;
            case 'photos':
                $postsFromData = $iN->iN_AllUserProfilePostsByChoosePhotos($p_profileID, $lastPostID, $showingNumberOfPost);
                break;
            case 'products':
                $postsFromDataProduct = $iN->iN_AllUserProfileProductPosts($p_profileID, $lastPostID, $showingNumberOfPost);
                break;
            case 'followers':
                $profileFollowers = $iN->iN_FollowerUsersListProfilePage($p_profileID, $lastPostID, $showingNumberOfPost);
                break;
            case 'following':
                $profileFollowing = $iN->iN_FollowingUsersListProfilePage($p_profileID, $lastPostID, $showingNumberOfPost);
                break;
            case 'subscribers':
                $profileSubscriber = $iN->iN_SubscribersUsersListProfilePage($p_profileID, $lastPostID, $showingNumberOfPost);
                break;
            default:
                $postsFromData = $iN->iN_AllUserProfilePosts($p_profileID, $lastPostID, $showingNumberOfPost);
        }
        $notFoundNot = $LANG['no_post_wilbe_shown_in_this_profile'];
    } elseif (in_array($page, ['allPosts', 'moreexplore'], true)) {
        $postsFromData = $iN->iN_AllUserForExplore($userID, $lastPostID, $showingNumberOfPost);
    } elseif (in_array($page, ['premiums', 'morepremium'], true)) {
        $postsFromData = $iN->iN_AllUserForPremium($userID, $lastPostID, $showingNumberOfPost);
    } elseif ($page === 'savedpost') {
        $postsFromData = $iN->iN_SavedPosts($userID, $lastPostID, $showingNumberOfPost);
    } elseif ($page === 'hashtag') {
        $postsFromData = $iN->iN_GetHashTagsSearch($pageFor, $lastPostID, $showingNumberOfPost);
    } elseif (in_array($page, ['purchasedpremiums', 'morepurchased'], true)) {
        $postsFromData = $iN->iN_AllUserForPurchasedPremium($userID, $lastPostID, $showingNumberOfPost);
    } elseif (in_array($page, ['boostedposts', 'moreboostedposts'], true)) {
        $postsFromData = $iN->iN_AllUserForBoostedPosts($userID, $lastPostID, $showingNumberOfPost);
    } elseif ($page === 'trendposts') {
        $postsFromData = $iN->iN_GetTotalHotPosts($userID, $showingNumberOfPost, $showingTrendPostLimitDay);
    }

    $notFoundNot = $notFoundNot ?: $LANG['no_post_will_be_shown'];
}
if($postsFromData){
   foreach($postsFromData as $postFromData){
    $userPostID = $postFromData['post_id'] ?? null;
    if($page == 'purchasedpremiums' || $page == 'morepurchased'){
      $userPostID = $postFromData['payment_id'] ?? null;
    }else if($page == 'moreboostedposts'){
      $userPostID = $postFromData['post_id'] ?? null;
    }else if($page == 'trendposts'){
      $userPostID = $postFromData['post_id'] ?? null;
    }
    $userPostOwnerID = $postFromData['post_owner_id'] ?? null;
    $userPostText = $postFromData['post_text'] ?? null;
    $userPostFile = $postFromData['post_file'] ?? null;
    $userPostCreatedTime = $postFromData['post_created_time'] ?? null;
    $crTime = date('Y-m-d H:i:s',$userPostCreatedTime);
    $userPostWhoCanSee = $postFromData['who_can_see'] ?? null;
    $userPostWantStatus = $postFromData['post_want_status'] ?? null;
    $userPostWantedCredit = $postFromData['post_wanted_credit'] ?? null;
    $userPostStatus = $postFromData['post_status'] ?? null;
    $userPostOwnerUsername = $postFromData['i_username'] ?? null;
    $userPostOwnerUserFullName = $postFromData['i_user_fullname'] ?? null;
    $userProfileFrame = $postFromData['user_frame'] ?? null;
    $checkPostBoosted = $iN->iN_CheckPostBoostedBefore($userPostOwnerID, $userPostID);
    $planIcon = '';
    $boostID = '';
    $boostPostOwnerID = '';
    $viewCount = '';
    $boostStatus = '';
    if($checkPostBoosted){
      $getBoostDetails = $iN->iN_GetBoostedPostDetails($userPostID);
      $boostPlanID = $getBoostDetails['boost_type'] ?? null;
      $boostStatus = $getBoostDetails['status'] ?? null;
      $boostID = $getBoostDetails['boost_id'] ?? null;
      $viewCount = $getBoostDetails['view_count'] ?? null;
      $boostPostOwnerID = $getBoostDetails['iuid_fk'] ?? null;
      $planDetails = $iN->iN_GetBoostPostDetails($boostPlanID);
      $planIcon = '<div class="boostIcon flex_ justify-content-align-items-center">'.$planDetails['plan_icon'].$LANG['boosted_post'].'</div>';
    }
    if($fullnameorusername == 'no'){
       $userPostOwnerUserFullName = $userPostOwnerUsername;
    }
    $userPostOwnerUserGender = $postFromData['user_gender'] ?? null;
    $userProfileFrame = $postFromData['user_frame'] ?? null;
    $userTextForPostTip = $postFromData['thanks_for_tip'] ?? null;
    $getUserPaymentMethodStatus = $postFromData['payout_method'] ?? null;
    $userPostHashTags = $postFromData['hashtags'] ?? null;
    $userPostCommentAvailableStatus = $postFromData['comment_status'] ?? null;
    $userPostOwnerUserLastLogin = $postFromData['last_login_time'] ?? null;
    $userProfileCategory = $postFromData['profile_category'] ?? null;

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
    /*Comment*/
   // OPTIMIZATION: Use total_comments from main query if available, avoid extra DB query
   $totalCommentsCount = isset($postFromData['total_comments'])
       ? (int)$postFromData['total_comments']
       : null;

   $c = 1;
   $TotallyPostComment = '';
   $getUserComments = []; // Initialize to avoid undefined variable warnings

   if ($c) {
      // If we don't have the count from the query, fetch comments to get it
      if ($totalCommentsCount === null) {
         $getUserComments = $iN->iN_GetPostComments($userPostID, 0);
         $totalCommentsCount = is_array($getUserComments) ? count($getUserComments) : 0;
      }

      if ($totalCommentsCount > 0) {
         $CountTheUniqComment = $totalCommentsCount;
         $SecondUniqComment = $CountTheUniqComment - 2;
         if ($CountTheUniqComment > 2) {
            // Only fetch actual comments when we need to display them
            $getUserComments = $iN->iN_GetPostComments($userPostID, 2);
            $TotallyPostComment = '<div class="lc_sum_container lc_sum_container_'.$userPostID.'"><div class="comnts transition more_comment" id="od_com_'.$userPostID.'" data-id="'.$userPostID.'">'.preg_replace( '/{.*?}/', $SecondUniqComment, $LANG['t_comments']).'</div></div>';
         } else if ($totalCommentsCount > 0) {
            // If we have 1-2 comments, fetch them
            $getUserComments = $iN->iN_GetPostComments($userPostID, $totalCommentsCount);
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
      // OPTIMIZATION: Use liked_by_user from main query if available, avoid extra DB query
      $checkPostLikedBefore = isset($postFromData['liked_by_user'])
          ? $postFromData['liked_by_user']
          : $iN->iN_CheckPostLikedBefore($userID, $userPostID);
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
      $commentStatusText = $LANG['disable_comment'] ?? null;
   }else{
      $commentStatusText = $LANG['enable_comments'] ?? null;
   }
   $pPinStatus = '';
   $pPinStatusBtn = $iN->iN_SelectedMenuIcon('29').$LANG['pin_on_my_profile'];
   if($userPostPinStatus == '1'){
     $pPinStatus = '<div class="i_pined_post" id="i_pined_post_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('62').'</div>';
     $pPinStatusBtn = $iN->iN_SelectedMenuIcon('29').$LANG['post_pined_on_your_profile'];
   }
   $waitingApprove = '';
   // OPTIMIZATION: Use total_likes from main query if available, avoid extra DB query
   $likeSum = isset($postFromData['total_likes'])
       ? $postFromData['total_likes']
       : $iN->iN_TotalPostLiked($userPostID);
   if($likeSum > '0'){
      $likeSum = $likeSum;
   }else{
      $likeSum = '';
   }
   /*Comment*/
   if($userPostStatus == '2') {
      $ApproveNot = $iN->iN_GetAdminNot($userPostOwnerID, $userPostID);
      $aprove_status = $ApproveNot['approve_status'] ?? null;
      $a_not = $iN->iN_SelectedMenuIcon('10').$LANG['waiting_for_approve'];
      $theApproveNot = $ApproveNot['approve_not'] ?? null;
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
}else if(is_array($postsFromDataProduct) || is_object($postsFromDataProduct)){
   foreach($postsFromDataProduct as $oprod){
      $ProductID = $oprod['pr_id'] ?? null;
      $ProductName = $oprod['pr_name'] ?? null;
      $ProductPrice = $oprod['pr_price'] ?? null;
      $ProductFiles = $oprod['pr_files'] ?? null;
      $ProductOwnerID = $oprod['iuid_fk'] ?? null;
      $productOwnerUserName = $oprod['i_username'] ?? null;
      $productOwnerUserFullName = $oprod['i_user_fullname'] ?? null;
      $userProfileFrame = $oprod['user_frame'] ?? null;
      if($fullnameorusername == 'no'){
         $productOwnerUserFullName = $productOwnerUserName;
      }
      $pprofileAvatar = $iN->iN_UserAvatar($ProductOwnerID, $base_url);
      $ProductSlug = $oprod['pr_name_slug'] ?? null;
      $ProductType = $oprod['product_type'] ?? null;
      $p__style = $ProductType;
      if($ProductType == 'scratch'){
          $ProductType = 'simple_product';
          $p__style = 'scratch';
      }
      $ProductSlotsNumber = $oprod['pr_slots_number'] ?? null;
      $SlugUrl = $base_url.'product/'.$ProductSlug.'_'.$ProductID;
      $trimValue = rtrim($ProductFiles,',');
      $nums = preg_split('/\s*,\s*/', $trimValue);
      $lastFileID = end($nums);
      $pfData = $iN->iN_GetUploadedFileDetails($lastFileID);
      if($pfData){
          $fileUploadID = $pfData['upload_id'] ?? null;
          $fileExtension = $pfData['uploaded_file_ext'] ?? null;
          $filePath = $pfData['uploaded_file_path'] ?? null;
          // Use unified storage helper to resolve public URL (supports MinIO/S3/Wasabi/Spaces/local)
          if (function_exists('storage_public_url')) {
              $productDataImage = storage_public_url($filePath);
          } else {
              $productDataImage = $base_url . $filePath;
          }
      }
      include("products.php");
   }
}else if(is_array($profileFollowers) || is_object($profileFollowers)){
   foreach ($profileFollowers as $flU) {
        $followingUserID = $flU['fr_one'] ?? null;
        $followingUserData = $iN->iN_GetUserDetails($followingUserID);
        $flUUserName = $followingUserData['i_username'] ?? null;
        $flUUserFullName = $followingUserData['i_user_fullname'] ?? null;
        $userProfileFrame = $followingUserData['user_frame'] ?? null;
        $flUUserAvatar = $iN->iN_UserAvatar($followingUserID, $base_url);
       $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $followingUserID);
      if ($getFriendStatusBetweenTwoUser == 'flwr') {
         $flwrBtn = 'i_btn_like_item_flw f_p_follow';
         $flwBtnIconText = $iN->iN_SelectedMenuIcon('66') . $LANG['unfollow'];
      } else {
         $flwrBtn = 'i_btn_like_item free_follow';
         $flwBtnIconText = $iN->iN_SelectedMenuIcon('66') . $LANG['follow'];
      }
   include("followers.php");
   }
}else if(is_array($profileFollowing) || is_object($profileFollowing)){
   /****/
   foreach ($profileFollowing as $flU) {
        $followingUserID = $flU['fr_two'] ?? null;
        $followingID = $flU['fr_id'] ?? null;
        $followingUserData = $iN->iN_GetUserDetails($followingUserID);
        $flUUserName = $followingUserData['i_username'] ?? null;
        $flUUserFullName = $followingUserData['i_user_fullname'] ?? null;
        $flUUserAvatar = $iN->iN_UserAvatar($followingUserID, $base_url);
       $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $followingUserID);
      if ($getFriendStatusBetweenTwoUser == 'flwr') {
         $flwrBtn = 'i_btn_like_item_flw f_p_follow';
         $flwBtnIconText = $iN->iN_SelectedMenuIcon('66') . $LANG['unfollow'];
      } else {
         $flwrBtn = 'i_btn_like_item free_follow';
         $flwBtnIconText = $iN->iN_SelectedMenuIcon('66') . $LANG['follow'];
      }
   include("following.php");
   }
}else if(is_array($profileSubscriber) || is_object($profileSubscriber)){
   foreach ($profileSubscriber as $flU) {
        $followingUserID = $flU['fr_one'] ?? null;
        $followingID = $flU['fr_id'] ?? null;
        $followingUserData = $iN->iN_GetUserDetails($followingUserID);
        $flUUserName = $followingUserData['i_username'] ?? null;
        $flUUserFullName = $followingUserData['i_user_fullname'] ?? null;
        $flUUserAvatar = $iN->iN_UserAvatar($followingUserID, $base_url);
       $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $followingUserID);
      if ($getFriendStatusBetweenTwoUser == 'flwr') {
         $flwrBtn = 'i_btn_like_item_flw f_p_follow';
         $flwBtnIconText =  $LANG['unfollow'];
      } else if ($getFriendStatusBetweenTwoUser == 'subscriber') {
         $flwrBtn = 'i_btn_unsubscribe';
         $flwBtnIconText =   $LANG['unsubscribe'];
      } else {
         $flwrBtn = 'i_btn_like_item free_follow';
         $flwBtnIconText =   $LANG['follow'];
      }
   include("subscribers.php");
   }
} else {
   echo '
    <div class="noPost optional_width">
        <div class="noPostIcon">'.$iN->iN_SelectedMenuIcon('182').'</div>
        <div class="noPostNote">'.$notFoundNot.'</div>
    </div>
   ';
}
?>
