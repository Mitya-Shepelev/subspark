<div class="th_middle">
  <div class="pageMiddle myFriednsStories">
    <div class="live_item transition">
      <div class="stories_page_title flex_ tabing_non_justify">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('154'));?>
        <?php echo iN_HelpSecure($LANG['all_friends_stories']);?>
      </div>
    </div>

    <div class="my-stories-wrapper flex_ mystoriesstyle" id="story-view" data-padding-top="30">
      <?php
      $stories = $iN->iN_FriendStoryPostListAll($userID);
      if($stories){
        foreach($stories as $mySData){
          $SotryUploaded = isset($mySData['pics']) ? $mySData['pics'] : NULL;
          $up = explode(",", $SotryUploaded);
          $storySharedOwnerID = $mySData['uid_fk'];
          $storieOwnerFullName = $iN->iN_UserFullName($storySharedOwnerID);
          $StorySharedUserAvatar = $iN->iN_UserAvatar($storySharedOwnerID, $base_url);
          $lastStorieImage = $iN->iN_GetLastSharedStatus($storySharedOwnerID);

          if($lastStorieImage){
            if (function_exists('storage_public_url')) {
              $lastStoryUrl = storage_public_url($lastStorieImage);
            } else {
              $lastStoryUrl = $base_url . $lastStorieImage;
            }
          }

          $StoryCreatedTime = $mySData['created'];
      ?>
      <div class="story-view-item" data-background-image="<?php echo $lastStoryUrl;?>" data-profile-image="<?php echo $StorySharedUserAvatar;?>" data-profile-name="<?php echo $storieOwnerFullName;?>">
        <span class="name truncate"><?php echo $storieOwnerFullName;?></span>
        <div class="story-view-pr-avatar" data-avatar="<?php echo $StorySharedUserAvatar;?>"></div>
        <ul class="media">
          <?php
          foreach ($up as $item) {
            $stD = $iN->iN_GetUploadedStoriesDataS($item);
            $final_Image = $stD['uploaded_file_path'];
            $storieText = $stD['text'];
            $storieID = $stD['s_id'];
            $storieTextStyle = $stD['text_style'] ?? 'not';
            $exts = pathinfo($final_Image, PATHINFO_EXTENSION);

            if (function_exists('storage_public_url')) {
              $final_Image = storage_public_url($final_Image);
            } else {
              $final_Image = $base_url . $final_Image;
            }

            if (in_array($exts, ['mp4', 'MP4'])) {
              echo '<li class="move_' . $storieID . '" data-id="' . $storieID . '" data-sid="' . $storieID . '" data-duration="" data-time="' . $StoryCreatedTime . '">
                      <video src="' . $final_Image . '" id="video_' . $storieID . '" alt="' . $storieText . '" data-id="' . $storieID . '" type="video/mp4"></video>
                    </li>';
            } else {
              echo '<li data-duration="7" data-id="' . $storieID . '" data-sid="' . $storieID . '" data-time="' . $StoryCreatedTime . '">
                      <img src="' . $final_Image . '" data-id="' . $storieID . '" data-ts="' . $storieTextStyle . '" alt="' . $storieText . '">
                    </li>';
            }
          }
          ?>
        </ul>
      </div>
      <?php }
      } else {
        echo '<div class="noPost" data-width="100%">'
          . '<div class="noPostIcon">' . $iN->iN_SelectedMenuIcon('54') . '</div>'
          . '<div class="noPostNote">' . $LANG['no_story_to_show'] . '</div>'
          . '</div>';
      }
      ?>
    </div>
  </div>
</div>
