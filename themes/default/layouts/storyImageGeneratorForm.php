<div class="th_middle">
  <div class="pageMiddle">
    <div class="live_item transition">
      <div class="live_title_page create_stories flex_">
        <?php echo $iN->iN_SelectedMenuIcon('154'); ?>
        <?php echo iN_HelpSecure($LANG['createyourstatus']); ?>
      </div>
    </div>

    <div class="create_sotry_form_container flex_ tabing">
      <div class="upload_story_image">
        <form id="storiesform" method="post" enctype="multipart/form-data" action="<?php echo $base_url; ?>requests/request.php">
          <label class="label_storyUpload" data-id="stories" for="storie_img">
            <input type="file" name="storieimg[]" id="storie_img" data-id="stories" multiple>
            <div class="story-view-item story_margin_right_zero" style="background-image:url(<?php echo iN_HelpSecure($userAvatar); ?>);">
              <div class="newSto">
                <div class="plusSIc"></div>
                <?php echo iN_HelpSecure($LANG['upload_storie_files']); ?>
              </div>
            </div>
          </label>
        </form>
      </div>
      <div class="i_uploading_not_story flex_ tabing nonePoint">
        <?php echo iN_HelpSecure($LANG['uploading_please_wait']); ?>
      </div>
    </div>

    <div class="edit_created_stories"></div>

    <div class="live_item transition">
      <div class="live_title_page non-shared-title-style create_stories flex_">
        <?php echo $iN->iN_SelectedMenuIcon('115') . iN_HelpSecure($LANG['non_shared_stories']); ?>
      </div>
    </div>

    <div class="non-shared-yet">
      <?php
        $nonSharedStoriesData = $iN->iN_GetNonSharedStories($userID);
        if ($nonSharedStoriesData) {
          foreach ($nonSharedStoriesData as $stData) {
            $storieID = $stData['s_id'];
            $storiOwnerID = $stData['uid_fk'];
            $storieUploadedFilePath = $stData['uploaded_file_path'];
            $storieUploadedfileExtension = $stData['uploaded_file_ext'];
            $storieUploadedFileTumbnail = $stData['upload_tumbnail_file_path'];
            $storieText = $stData['text'];
            $createdTime = $stData['created'];
            $crTime = date('Y-m-d H:i:s', $createdTime);

            if (in_array($storieUploadedfileExtension, ['mp4'])) {
              // Build proper public URLs for video src and poster (thumbnail)
              if (function_exists('storage_public_url')) {
                $videoUrl  = storage_public_url($storieUploadedFilePath);
                $posterUrl = !empty($storieUploadedFileTumbnail)
                  ? storage_public_url($storieUploadedFileTumbnail)
                  : $base_url . 'uploads/web.png';
              } else {
                $videoUrl  = $base_url . $storieUploadedFilePath;
                $posterUrl = !empty($storieUploadedFileTumbnail) ? $base_url . $storieUploadedFileTumbnail : $base_url . 'uploads/web.png';
              }
      ?>
      <div class="uploaded_storie_container body_<?php echo iN_HelpSecure($storieID); ?>">
        <div class="shared_storie_time flex_">
          <?php echo $iN->iN_SelectedMenuIcon('115') . ' ' . TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
        </div>
        <div class="dmyStory dmyStory_extra" id="<?php echo iN_HelpSecure($storieID); ?>">
          <div class="i_h_in flex_ ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['delete']); ?>">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
          </div>
        </div>
        <div class="uploaded_storie_image uploaded_storie_before border_one tabing flex_">
          <video class="lg-video-object" id="v<?php echo iN_HelpSecure($storieID); ?>" controls preload="none" poster="<?php echo $posterUrl; ?>">
            <source src="<?php echo $videoUrl; ?>" preload="metadata" type="video/mp4">
          </video>
        </div>
        <div class="add_a_text">
          <textarea class="add_my_text st_txt_<?php echo iN_HelpSecure($storieID); ?>" placeholder="Do you want to write something about this storie?"></textarea>
        </div>
        <div class="share_story_btn_cnt flex_ tabing transition share_this_story" id="<?php echo iN_HelpSecure($storieID); ?>">
          <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?>
          <div class="pbtn"><?php echo iN_HelpSecure($LANG['publish']); ?></div>
        </div>
      </div>
      <?php
            } else {
              if (function_exists('storage_public_url')) {
                $filePathUrl = storage_public_url($storieUploadedFileTumbnail ?: $storieUploadedFilePath);
              } else {
                $filePathUrl = $base_url . ($storieUploadedFileTumbnail ?: $storieUploadedFilePath);
              }
      ?>
      <div class="uploaded_storie_container body_<?php echo iN_HelpSecure($storieID); ?>">
        <div class="shared_storie_time flex_">
          <?php echo $iN->iN_SelectedMenuIcon('115') . ' ' . TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
        </div>
        <div class="dmyStory dmyStory_extra" id="<?php echo iN_HelpSecure($storieID); ?>">
          <div class="i_h_in flex_ ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['delete']); ?>">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
          </div>
        </div>
        <div class="uploaded_storie_image uploaded_storie_before border_one tabing flex_">
          <img src="<?php echo $filePathUrl; ?>" id="img<?php echo iN_HelpSecure($storieID); ?>">
        </div>
        <div class="add_a_text">
          <textarea class="add_my_text st_txt_<?php echo iN_HelpSecure($storieID); ?>" placeholder="Do you want to write something about this storie?"></textarea>
        </div>
        <div class="share_story_btn_cnt flex_ tabing transition share_this_story" id="<?php echo iN_HelpSecure($storieID); ?>">
          <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?>
          <div class="pbtn"><?php echo iN_HelpSecure($LANG['publish']); ?></div>
        </div>
      </div>
      <?php
            }
          }
        }
      ?>
    </div>
  </div>
</div>

<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/storyImageGeneratorHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
