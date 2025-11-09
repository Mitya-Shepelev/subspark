<?php
$userSharedPostID = $sharedPostData['post_id'] ?? null;
$userSharedPostOwnerID = $sharedPostData['post_owner_id'] ?? null;
$userSharedPostText = $sharedPostData['post_text'] ?? null;
$userSharedPostFile = $sharedPostData['post_file'] ?? null;
$userSharedPostCreatedTime = $sharedPostData['post_created_time'] ?? null;
$UserSharedcrTime = date('Y-m-d H:i:s',$userSharedPostCreatedTime);
$userSharedPostWantStatus = $sharedPostData['post_want_status'] ?? null;
$userPostWantedCredit = $sharedPostData['post_wanted_credit'] ?? null;
$userSharedPostStatus = $sharedPostData['post_status'] ?? null;
$userSharedPostOwnerUsername = $sharedPostData['i_username'] ?? null;
$userSharedPostOwnerUserFullName = $sharedPostData['i_user_fullname'] ?? null;
$userProfileFrame = $sharedPostData['user_frame'] ?? null;
if($fullnameorusername == 'no'){
    $userSharedPostOwnerUserFullName = $userSharedPostOwnerUsername;
}
$userSharedPostOwnerUserGender = $sharedPostData['user_gender'];
$userPostHashTags = $sharedPostData['hashtags'] ?? null;
$userSharedPostCommentAvailableStatus = $sharedPostData['comment_status'] ?? null;
$userSharedPostOwnerUserLastLogin = $sharedPostData['last_login_time'] ?? null;
$userSharedProfileCategory = $postFromData['profile_category'] ?? null;
$lastSeen = date("c", $userPostOwnerUserLastLogin);
$OnlineStatus = date("c", time());
$oStatus = time() - 35;
if ($userPostOwnerUserLastLogin > $oStatus) {
 $timeStatus = '<div class="userIsOnline flex_ tabing">'.$LANG['online'].'</div>';
} else {
 $timeStatus = '<div class="userIsOffline flex_ tabing">'.$LANG['offline'].'</div>';
}
$userSharedPostSharedID = $sharedPostData['shared_post_id'] ?? null;
$userSharedPostWhoCanSee = $iN->iN_CheckWhoCanSeePost($userPostSharedID);
$getPostOwnerUserID = $iN->iN_GetPostOwnerIDFromPostID($userPostID);
$userSharedPostOwnerUserAvatar = $iN->iN_UserAvatar($userSharedPostOwnerID, $base_url);
$userSharedPostUserVerifiedStatus = $sharedPostData['user_verified_status'] ?? null;
$getFriendStatusBetweenTwoUser = '';
if($logedIn == '1'){
    $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $userSharedPostOwnerID);
    $getFriendStatusBetweenTwoUserShared = $iN->iN_GetRelationsipBetweenTwoUsers($userPostOwnerID, $userSharedPostOwnerID);
}
if($userSharedPostOwnerUserGender == 'male'){
   $publisherGender = '<div class="i_plus_g">'.$iN->iN_SelectedMenuIcon('12').'</div>';
}else if($userSharedPostOwnerUserGender == 'female'){
   $publisherGender = '<div class="i_plus_gf">'.$iN->iN_SelectedMenuIcon('13').'</div>';
}else if($userSharedPostOwnerUserGender == 'couple'){
   $publisherGender = '<div class="i_plus_g">'.$iN->iN_SelectedMenuIcon('58').'</div>';
}
$userSharedVerifiedStatus = '';
if($userSharedPostUserVerifiedStatus == '1'){
   $userSharedVerifiedStatus = '<div class="i_plus_s">'.$iN->iN_SelectedMenuIcon('11').'</div>';
}
$profileCategory = $pCt = $profileCategoryLink = '';
if($userProfileCategory && $userSharedPostUserVerifiedStatus == '1'){
    $profileCategory = $userProfileCategory;
if(isset($PROFILE_CATEGORIES[$userProfileCategory])){
    $pCt = isset($PROFILE_CATEGORIES[$userProfileCategory]) ? $PROFILE_CATEGORIES[$userProfileCategory] : NULL;
}else if(isset($PROFILE_SUBCATEGORIES[$userProfileCategory])){
    $pCt = isset($PROFILE_SUBCATEGORIES[$userProfileCategory]) ? $PROFILE_SUBCATEGORIES[$userProfileCategory] : NULL;
}
    $profileCategoryLink = '<a class="i_p_categoryp flex_ tabing_non_justify" href="'.$base_url.'creators?creator='.$userProfileCategory.'">'.$iN->iN_SelectedMenuIcon('65').$pCt.'</a>- ';
}
$onlySubs = '';
if($userSharedPostWhoCanSee == '1'){
    $onlySubs = '';
    $subPostTop = '';
    $wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostSharedID.'">'.$iN->iN_SelectedMenuIcon('50').'</div>';
 }else if($userSharedPostWhoCanSee == '2'){
    $subPostTop = '';
    $wCanSee = '<div class="i_plus_subs" id="ipublic_'.$userPostSharedID.'">'.$iN->iN_SelectedMenuIcon('15').'</div>';
    $onlySubs = '<div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">'.$iN->iN_SelectedMenuIcon('15').'</div><div class="onlySubs_note">'.preg_replace( '/{.*?}/', $userPostOwnerUserFullName, $LANG['only_followers']).'</div></div></div>';
 }else if($userSharedPostWhoCanSee == '3'){
    $subPostTop = 'extensionPost';
    $wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostSharedID.'">'.$iN->iN_SelectedMenuIcon('51').'</div>';
    $onlySubs = '<div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">'.$iN->iN_SelectedMenuIcon('56').'</div><div class="onlySubs_note">'.preg_replace( '/{.*?}/', $userPostOwnerUserFullName, $LANG['only_subscribers']).'</div></div></div>';
 }else if($userSharedPostWhoCanSee == '4'){
   $subPostTop = 'extensionPost';
   $wCanSee = '<div class="i_plus_public" id="ipublic_'.$userPostSharedID.'">'.$iN->iN_SelectedMenuIcon('9').'</div>';
   $onlySubs = '<div class="onlyPremium"><div class="onlySubsWrapper"><div class="premium_locked"><div class="premium_locked_icon">'.$iN->iN_SelectedMenuIcon('56').'</div></div><div class="onlySubs_note"><div class="buyThisPost prcsPost" id="'.$userPostSharedID.'">'.preg_replace( '/{.*?}/', $userPostWantedCredit, $LANG['post_credit']).'</div><div class="buythistext prcsPost" id="'.$userPostSharedID.'">'.$LANG['purchase_post'].'</div></div><div class="fr_subs uSubsModal transition" data-u="'.$userSharedPostOwnerID.'">'.$iN->iN_SelectedMenuIcon('51').$LANG['free_for_subscribers'].'</div></div></div>';
 }
?>
<!--Sharing POST DETAILS-->
<div class="i_shared_post_wrapper">
    <div class="i_sharing_post_wrapper_in">
        <!--POST HEADER-->
    <div class="i_post_body_header">
        <div class="i_post_user_avatar">
            <img src="<?php echo iN_HelpSecure($userSharedPostOwnerUserAvatar);?>"/>
        </div>
        <div class="i_post_i">
            <div class="i_post_username"><a href="<?php echo iN_HelpSecure($base_url).$userSharedPostOwnerUsername;?>"><?php echo iN_HelpSecure($userSharedPostOwnerUserFullName);?><?php echo html_entity_decode($publisherGender);?><?php echo html_entity_decode($userSharedVerifiedStatus);?><?php echo html_entity_decode($wCanSee);?><?php echo html_entity_decode($timeStatus);?></a></div>
            <div class="i_post_shared_time"><?php echo html_entity_decode($profileCategoryLink);?><a href="<?php echo iN_HelpSecure($base_url) . $userPostOwnerUsername; ?>">@<?php echo iN_HelpSecure($userPostOwnerUsername); ?></a> - <?php echo TimeAgo::ago($UserSharedcrTime , date('Y-m-d H:i:s'));?></div>
        </div>
    </div>
    <!--/POST HEADER-->
    <?php
    if($userSharedPostText){
    ?>
    <!--POST CONTAINER-->
    <div class="i_post_container">
        <!--POST TEXT-->
        <div class="i_post_text">
        <?php
        $pStatus = '1'; 
        if($userSharedPostWhoCanSee != '1'){
            if($getFriendStatusBetweenTwoUserShared != 'me' && $getFriendStatusBetweenTwoUserShared != 'subscriber' && $userPostStatus != '2' && $userSharedPostWhoCanSee == '3'){
                $pStatus = '0';
            }else if($userSharedPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUserShared != 'me'){
                if($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                    $pStatus = '0';
                }
            } else if($userSharedPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUserShared != 'me' && $getFriendStatusBetweenTwoUserShared != 'flwr'){
                $pStatus = '0';
            }
        }
        if($pStatus == '1'){
            if(!empty($userSharedPostText)){
                if(isset($userPostHashTags) && !empty($userPostHashTags)){
                    echo $urlHighlight->highlightUrls($iN->sanitize_output($userSharedPostText,$base_url));
                }else{
                    echo $urlHighlight->highlightUrls($userSharedPostText);
                }
            }
            $regexUrl = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
            $totalUrl = preg_match_all($regexUrl, $userSharedPostText, $matches);

            $urls = $matches[0];
            // go over all links
            foreach($urls as $url)
            {
                $em = new Url_Expand($url);
                // Get the link size
                $site = $em->get_site();

                if ($site != "") {
                    // If code is iframe then show the link in iframe
                    $code = $em->get_iframe();
                    if ($code == "") {
                        // If code is embed then show the link in embed
                        $code = $em->get_embed();
                        if ($code == "") {
                            // If code is thumb then show the link medium
                            $codesrc = $em->get_thumb("medium");
                        }
                    }
                    echo $code;
                }
            }
        }else{
            if(empty($userSharedPostFile)){
                echo '<div class="onlySubs">'.html_entity_decode($onlySubs).'</div>';
            }
        } 
        ?>
        </div>
        <!--/POST TEXT-->
    </div>
    <!--/POST CONTAINER-->
    <?php }
    ?>
    <?php if($userSharedPostFile){?>
    <!--POST IMAGES-->
    <div class="i_post_u_images">
        <?php
        if($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userSharedPostWhoCanSee == '3') {
            echo html_entity_decode($onlySubs);
        }else if($userSharedPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me'){
            if($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                echo html_entity_decode($onlySubs);
            }
        }else if($userSharedPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr'){
            echo html_entity_decode($onlySubs);
        }
        $trimValue = rtrim($userSharedPostFile, ',');
        $explodeFiles = array_unique(explode(',', $trimValue));
        $countExplodedFiles = count($explodeFiles);
            if ($countExplodedFiles == 1) {
                $container = 'i_image_one';
            } else if ($countExplodedFiles == 2) {
                $container = 'i_image_two';
            } else if ($countExplodedFiles == 3) {
                $container = 'i_image_three';
            } else if ($countExplodedFiles == 4) {
                $container = 'i_image_four';
            } else if($countExplodedFiles >= 5) {
                $container = 'i_image_five';
            }
            $videoRendered = false;
        foreach($explodeFiles as $explodeVideoFile){
                $VideofileData = $iN->iN_GetUploadedFileDetails($explodeVideoFile);
                if ($VideofileData) {
                    $VideofileUploadID = $VideofileData['upload_id'] ?? null;
                    $VideofileExtension = $VideofileData['uploaded_file_ext'] ?? null;
                    $VideofilePath = $VideofileData['uploaded_file_path'] ?? null;
                    $videoFileTumbnailHere = $VideofileData['upload_tumbnail_file_path'] ?? null;
                    if ($userPostWhoCanSee != '1') {
                        if ($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostStatus != '2' && $userPostWhoCanSee == '3') {
                            $VideofilePath = $VideofileData['uploaded_x_file_path'] ?? null;
                        } else if ($userPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me') {
                            if ($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber') {
                                $VideofilePath = $VideofileData['uploaded_x_file_path'] ?? null;
                            }
                        } else if ($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr') {
                            $VideofilePath = $VideofileData['uploaded_x_file_path'] ?? null;
                        }
                    }
                    $VideofilePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $VideofilePath);
                    if ($VideofileExtension == 'mp4' && !$videoRendered) {
                        $VideoPathExtension = '.jpg';
                        if ($s3Status == 1) {
                            $VideofilePathUrl = function_exists('storage_public_url') ? storage_public_url($VideofilePath) : ($base_url . $VideofilePath);
                            $VideofileTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($VideofilePathWithoutExt . $VideoPathExtension) : ($base_url . $VideofilePathWithoutExt . $VideoPathExtension);
                        }else if($WasStatus == 1){
                            $VideofilePathUrl = function_exists('storage_public_url') ? storage_public_url($VideofilePath) : ($base_url . $VideofilePath);
                            $VideofileTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($VideofilePathWithoutExt . $VideoPathExtension) : ($base_url . $VideofilePathWithoutExt . $VideoPathExtension);
                        } else if ($digitalOceanStatus == '1') {
                            $VideofilePathUrl = function_exists('storage_public_url') ? storage_public_url($VideofilePath) : ($base_url . $VideofilePath);
                            $VideofileTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($VideofilePathWithoutExt . $VideoPathExtension) : ($base_url . $VideofilePathWithoutExt . $VideoPathExtension);
                        } else {
                            $VideofilePathUrl = $base_url . $VideofilePath;
                            $VideofileTumbnailUrl = $base_url . $VideofileExtension;
                        }
                        echo '
                <div class="nonePoint" id="video' . $userPostID . '">
                    <video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none" onended="videoEnded()">
                        <source src="' . $VideofilePathUrl . '" type="video/mp4">
                        Your browser does not support HTML5 video.
                    </video>
                </div>';
                        $videoRendered = true;
                    }
                }
        } 
        echo '<div class="'.$container.'" id="lightgallery'.$userPostID.'">';
            foreach($explodeFiles  as $dataFile){
                $fileData = $iN->iN_GetUploadedFileDetails($dataFile);
                if($fileData){
                $fileUploadID = $fileData['upload_id'] ?? null;
                $fileExtension = $fileData['uploaded_file_ext'] ?? null;
                $filePath = $fileData['uploaded_file_path'] ?? null;
                $filePathTumbnail = $fileData['upload_tumbnail_file_path'] ?? null;
        		if ($filePathTumbnail) {
        			$imageTumbnail = $filePathTumbnail;
        		} else {
        			$imageTumbnail = $filePath;
        		}
                if($userSharedPostWhoCanSee != '1'){
                    if($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostStatus != '2' && $userSharedPostWhoCanSee == '3'){
                          $filePath = $fileData['uploaded_x_file_path'] ?? null;
                    }else if($userSharedPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me'){
                        if($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                          $filePath = $fileData['uploaded_x_file_path'] ?? null;
                        }
                    } else if($userSharedPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr'){
                        $filePath = $fileData['uploaded_x_file_path'] ?? null;
                    }
                }
                $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                if ($s3Status == 1) {
                    if ($filePathTumbnail) {
                        $filePathUrl = function_exists('storage_public_url') ? storage_public_url($imageTumbnail) : ($base_url . $imageTumbnail);
                    } else {
                        $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                    }
                }else if($WasStatus == 1){
                    if ($filePathTumbnail) {
                        $filePathUrl = function_exists('storage_public_url') ? storage_public_url($imageTumbnail) : ($base_url . $imageTumbnail);
                    } else {
                        $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                    }
                } else if ($digitalOceanStatus == '1') {
                    if ($filePathTumbnail) {
                        $filePathUrl = function_exists('storage_public_url') ? storage_public_url($imageTumbnail) : ($base_url . $imageTumbnail);
                    } else {
                        $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                    }
                } else {
                    if ($filePathTumbnail) {
                        $filePathUrl = $base_url . $filePath;
                    } else {
                        $filePathUrl = $base_url . $filePath;
                    }
                }
                $videoPlaybutton ='';
                if($fileExtension == 'mp4') {
                    $videoPlaybutton = '<div class="playbutton">' . $iN->iN_SelectedMenuIcon('55') . '</div>';
                    $PathExtension = '.jpg';
                    if ($s3Status == 1) {
                        if ($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr') {
                            $filePath = $fileData['upload_tumbnail_file_path'] ?? null;
                            $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                        } else if ($getFriendStatusBetweenTwoUser == 'me') {
                            $filePath = $fileData['upload_tumbnail_file_path'] ?? null;
                            $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                        } else {
                            $filePath = $fileData['upload_tumbnail_file_path'] ?? null;
                            $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                        }
                        if ($ffmpegStatus == '1') {
                            $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                            $filePathTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                        } else {
                            if ($s3Status == 1) {
                                $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                                $filePathTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                            } else {
                                $filePathUrl = $base_url . $filePathTumbnail;
                                $filePathTumbnailUrl = $base_url . $fileData['upload_tumbnail_file_path'];
                            }
                        }
                    }else if($WasStatus == 1){
                        if ($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr') {
                            $filePath = $fileData['upload_tumbnail_file_path'] ?? null;
                            $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                        } else if ($getFriendStatusBetweenTwoUser == 'me') {
                            $filePath = $fileData['upload_tumbnail_file_path'] ?? null;
                            $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                        } else {
                            $filePath = $fileData['upload_tumbnail_file_path'] ?? null;
                            $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                        }
                        if ($ffmpegStatus == '1') {
                            $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                            $filePathTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                        } else {
                            if ($WasStatus == 1) {
                                $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                                $filePathTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                            } else {
                                $filePathUrl = $base_url . $filePathTumbnail;
                                $filePathTumbnailUrl = $base_url . $fileData['upload_tumbnail_file_path'];
                            }
                        }
                    } else if ($digitalOceanStatus == '1') {
                        if ($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr' && $getFriendStatusBetweenTwoUser != 'subscriber') {
                            $filePath = $fileData['uploaded_x_file_path'] ?? null;
                        } else if ($getFriendStatusBetweenTwoUser == 'me') {
                            $filePath = $fileData['upload_tumbnail_file_path'] ?? null;
                        } else {
                            $filePath = $fileData['upload_tumbnail_file_path'] ?? null;
                        }
                        if ($ffmpegStatus == '1') {
                            $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                            $filePathTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                        } else {
                            if ($digitalOceanStatus == '1') {
                                $filePathUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                                $filePathTumbnailUrl = function_exists('storage_public_url') ? storage_public_url($filePath) : ($base_url . $filePath);
                            } else {
                                $filePathUrl = $base_url . $filePathTumbnail;
                                $filePathTumbnailUrl = $base_url . $filePath;
                            }
                        }
                    } else {
                        if($userPostWhoCanSee == '3' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                           $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                           $filePathUrl = $base_url . $filePathWithoutExt . $PathExtension;
                           $filePathTumbnailUrl = $base_url . $filePathWithoutExt . $PathExtension;
                        }else{
                            $filePathUrl = $base_url . $fileData['upload_tumbnail_file_path'];
                            $filePathTumbnailUrl = $base_url . $fileData['upload_tumbnail_file_path'];
                        }
                    } 
                    $fileisVideo = ($fileExtension == 'mp4') ? 'data-poster="' . $filePathUrl . '" data-html="#video' . $userPostID . '"' : 'data-src="'.$filePathUrl.'"';
                } else{
                    $fileisVideo = 'data-src="'.$filePathUrl.'"';
                }
            ?>
                <div class="i_post_image_swip_wrapper" data-bg="<?php echo iN_HelpSecure($filePathUrl); ?>" <?php echo $fileisVideo;?>>
                    <?php echo html_entity_decode($videoPlaybutton);?>
                    <img class="i_p_image" src="<?php echo $filePathUrl;?>">
                </div>
            <?php }
            }
            echo '</div>';
            ?> 
    </div>
    <!--POST IMAGES-->
    <?php }?>
    </div>
</div>
<!--/Sharing POST DETAILS-->
