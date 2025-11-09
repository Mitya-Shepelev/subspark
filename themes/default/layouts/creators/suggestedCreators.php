<?php
// Suggested/Popular creators rendered with the same layout as featuredCreators.php
// Aim: keep page populated in a visually consistent way

// 1) Try suggested creators (depends on login)
if ($logedIn != 0) {
    $list = $iN->iN_SuggestionCreatorsList($userID, $showingNumberOfSuggestedUser);
} else {
    $list = $iN->iN_SuggestionCreatorsListOut($showingNumberOfSuggestedUser);
}

// 2) If empty, fallback to popular last week (different source)
if (!$list) {
    $list = $iN->iN_PopularUsersFromLastWeekInExplorePage();
}

if ($list) { ?>
<div class="creators_container">
  <div class="creator_pate_title"><?php echo iN_HelpSecure($LANG['suggested_creators'] ?? ($LANG['best_creators_of_last_week'] ?? 'Creators'));?></div>

  <div class="creators_list_container">
    <?php foreach ($list as $row) {
        // Normalize dataset to match featuredCreators.php expectations
        $uid = $row['iuid'] ?? ($row['post_owner_id'] ?? 0);
        if (!$uid) { continue; }
        $uD = $iN->iN_GetUserDetails($uid);
        $avatar = $iN->iN_UserAvatar($uid, $base_url);
        $cover = $iN->iN_UserCover($uid, $base_url);
        $userName = $row['i_username'] ?? ($uD['i_username'] ?? '');
        $fullName = $row['i_user_fullname'] ?? ($uD['i_user_fullname'] ?? $userName);
        if ($fullnameorusername == 'no') { $fullName = $userName; }
        $userProfileFrame = $uD['user_frame'] ?? null;
        $uPCategory = $uD['profile_category'] ?? null;
        $totalPost = $iN->iN_TotalPosts($uid);
        $totalImagePost = $iN->iN_TotalImagePosts($uid);
        $totalVideoPosts = $iN->iN_TotalVideoPosts($uid);
        if (isset($PROFILE_CATEGORIES[$uPCategory])) {
            $pCt = $PROFILE_CATEGORIES[$uPCategory];
        } elseif (isset($PROFILE_SUBCATEGORIES[$uPCategory])) {
            $pCt = $PROFILE_SUBCATEGORIES[$uPCategory];
        } else { $pCt = null; }
        $uCateg = $uPCategory ? '<div class="i_p_cards"> <div class="i_creator_category"><a href="'.iN_HelpSecure($base_url).'creators?creator='.$uPCategory.'">'.html_entity_decode($iN->iN_SelectedMenuIcon('65')).$pCt.'</a></div></div>' : '';
    ?>
    <div class="creator_list_box_wrp">
      <div class="creator_l_box flex_">
        <div class="creator_l_cover" style="background-image:url(<?php echo iN_HelpSecure($cover);?>);"></div>
        <div class="creator_l_avatar_name">
          <div class="creator_avatar_container">
            <?php if($userProfileFrame){ ?>
              <div class="frame_out_container_creator"><div class="frame_container_creator"><img src="<?php echo $base_url.$userProfileFrame;?>"></div></div>
            <?php }?>
            <div class="creator_avatar"><img src="<?php echo iN_HelpSecure($avatar);?>"></div>
          </div>
          <div class="creator_nm truncated"><?php echo iN_HelpSecure($iN->iN_Secure($fullName));?></div>
          <?php echo $uCateg; ?>
          <div class="i_p_items_box_">
            <div class="i_btn_item_box transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('67'));?> <?php echo iN_HelpSecure($totalPost);?></div>
            <div class="i_btn_item_box transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('68'));?> <?php echo iN_HelpSecure($totalImagePost);?></div>
            <div class="i_btn_item_box transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52'));?> <?php echo iN_HelpSecure($totalVideoPosts);?></div>
          </div>
          <div class="creator_last_two_post flex_ tabing">
            <?php
              $latestPosts = $iN->iN_ExploreUserLatestFivePost($uid);
              if ($latestPosts) {
                foreach ($latestPosts as $p) {
                  $userPostID = $p['post_id'];
                  $userPostFile = $p['post_file'];
                  $slugUrl = $base_url.'post/'.$p['url_slug'].'_'.$userPostID;
                  $whoCanSee = $p['who_can_see'];
                  $trimValue = rtrim($userPostFile,',');
                  $explodeFiles = array_unique(explode(',', $trimValue));
                  // assume first file
                  $fileUploadID = $explodeFiles[0] ?? null;
                  $filePathUrl = $base_url.'img/no_image.png';
                  if ($fileUploadID) {
                    $gUFID = $iN->iN_GetUploadedFileDetails($fileUploadID);
                    $filePath = $gUFID['uploaded_file_path'] ?? '';
                    $fileTmb = $gUFID['upload_tumbnail_file_path'] ?? '';
                    $ext = $gUFID['uploaded_file_ext'] ?? '';
                    $isVideo = in_array($ext, ['mp4','mkv','webm','mov']);
                    $path = $isVideo ? ($fileTmb ?: $filePath) : $filePath;
                    if (function_exists('storage_public_url')) {
                        $filePathUrl = storage_public_url($path);
                    } else {
                        $filePathUrl = $base_url . $path;
                    }
                  }
                  $onlySubs = '';
                  if ($whoCanSee == '2' || $whoCanSee == '3' || $whoCanSee == '4') {
                    $icon = ($whoCanSee == '4') ? '40' : '56';
                    $onlySubs = '<div class="onlySubsSuggestion"><div class="onlySubsSuggestionWrapper"><div class="onlySubsSuggestion_icon">'.$iN->iN_SelectedMenuIcon($icon).'</div></div></div>';
                  }
            ?>
              <div class="creator_last_post_item">
                <div class="creator_last_post_item-box" style="background-image: url('<?php echo iN_HelpSecure($filePathUrl);?>');">
                  <a href="<?php echo iN_HelpSecure($slugUrl);?>">
                    <?php if($logedIn == '1'){
                        $rel = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $uid);
                        if($rel != 'me' && $rel != 'subscriber') echo html_entity_decode($onlySubs);
                      } else { echo html_entity_decode($onlySubs);} ?>
                    <img class="creator_last_post_item-img" src="<?php echo iN_HelpSecure($filePathUrl);?>">
                  </a>
                </div>
              </div>
            <?php } // foreach posts
              } else {
                echo '<div class="no_content tabing flex_">'.$LANG['no_posts_yet'].'</div>';
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php } // foreach creators ?>
  </div>
</div>
<?php } // if list ?>
