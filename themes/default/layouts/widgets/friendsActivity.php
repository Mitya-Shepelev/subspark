<?php
if($logedIn == '1'){
$activityData = $iN->iN_FriendsActivity($userID, $showingActivityLimit, $showingTimeActivityLimit);
if ($activityData) {
    echo '
    <div class="sp_wrp">
    <div class="suggested_products">
    <div class="i_right_box_header">
     ' . iN_HelpSecure($LANG['friends_activity']) . '
    </div>
    <div class="i_topinoras_wrapper activityWrapper">
    ';
    foreach ($activityData as $activity) {
        $activityID = isset($activity['activity_id']) ? $activity['activity_id'] : NULL;
        $activityUserID = isset($activity['iuid']) ? $activity['iuid'] : NULL;
        $activityType = isset($activity['activity_type']) ? $activity['activity_type'] : NULL;
        $activityPostID = isset($activity['post_id']) ? $activity['post_id'] : NULL;
        $activityFollowUserID = isset($activity['fr_id']) ? $activity['fr_id']: NULL; 
        $popularUserName = isset($activity['i_username']) ? $activity['i_username'] : NULL;
        $popularUserFullName = isset($activity['i_user_fullname']) ? $activity['i_user_fullname'] : NULL;

        $uD = $iN->iN_GetUserDetails($activityUserID);
        $popularUserAvatar = $iN->iN_UserAvatar($activityUserID, $base_url);
        $activityTypeText = 'This activity is no longer available';
        if($fullnameorusername == 'no'){
            $popularUserFullName = $popularUserName;
        }
        if($activityType == 'newPost'){
            if($popularUserName && $popularUserFullName){
                $activityTypeText = html_entity_decode($iN->iN_TextReaplacement($LANG['new_post_activity'],[$popularUserName,$popularUserFullName,$activityPostID]));
            }
        }else if($activityType == 'postLike'){
            $likedPostOwnerID = $iN->iN_GetPostOwnerIDFromPostID($activityPostID);
            if($likedPostOwnerID){
                $uData = $iN->iN_GetUserDetails($likedPostOwnerID);
                if(!empty($uData)){
                    $lpopularUserName = isset($uData['i_username']) ? $uData['i_username'] : NULL;
                    $lpopularUserFullName = isset($uData['i_user_fullname']) ? $uData['i_user_fullname'] : NULL;
                    if($fullnameorusername == 'no'){
                        $lpopularUserFullName = $lpopularUserName;
                    }
                    if($popularUserName && $popularUserFullName && $lpopularUserName && $lpopularUserFullName){
                        $activityTypeText = html_entity_decode($iN->iN_TextReaplacement($LANG['new_post_like_activity'],[$popularUserName,$popularUserFullName,$lpopularUserName,$lpopularUserFullName,$activityPostID]));
                    }
                }
            }else{
                $activityTypeText = 'This activity is no longer available';
            }
        }else if($activityType == 'userFollow'){
            $uData = $iN->iN_GetUserDetails($activityFollowUserID);
            if(!empty($uData)){
                $lpopularUserName = isset($uData['i_username']) ? $uData['i_username'] : NULL;
                $lpopularUserFullName = isset($uData['i_user_fullname']) ? $uData['i_user_fullname'] : NULL;
                if($fullnameorusername == 'no'){
                    $lpopularUserFullName = $lpopularUserName;
                }
                if($popularUserName && $popularUserFullName && $lpopularUserName && $lpopularUserFullName){
                    $activityTypeText = html_entity_decode($iN->iN_TextReaplacement($LANG['new_follow_activity'],[$popularUserName,$popularUserFullName,$lpopularUserName,$lpopularUserFullName,$activityPostID]));
                }
            }
        }

?>
<div class="i_message_wrpper">
    <a href="<?php echo $base_url.$popularUserName;?>" target="blank_" title="<?php echo $popularUserFullName;?>">
        <div class="i_message_wrapper transition">
            <div class="i_message_owner_avatar">
                <div class="i_message_avatar">
                    <img src="<?php echo $popularUserAvatar;?>" alt="<?php echo $popularUserFullName;?>">
                </div>
            </div>
            <!---->
            <div class="i_activity_info_container tabing_non_justify">
                <?php echo $activityTypeText;?>
            </div>
            <!---->
        </div>
    </a>
</div>
<?php
    }
    echo '
    </div>
    </div>
    </div>';
}
}
?>
