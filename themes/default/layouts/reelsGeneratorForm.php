<?php if (!isset($reelsFeatureStatus) || (string)$reelsFeatureStatus !== '1') { return; } ?>
<div class="th_middle">
  <div class="pageMiddle">
    <div class="live_item transition">
      <div class="live_title_page create_stories flex_">
        <?php echo $iN->iN_SelectedMenuIcon('187'); ?>
        <?php echo iN_HelpSecure($LANG['create_reels']); ?>
        <div class="cr_reels ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['create_reels']); ?>">
            <form id="uploadReelsform" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url) . 'requests/request.php'; ?>" data-lang-converting="<?php echo iN_HelpSecure($LANG['converting_video_please_wait']); ?>">
                <label for="i_reels_video">
                    <div class="i_image_video_btn">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('39')); ?>
                    </div>
                    <input type="file" id="i_reels_video" class="imageorvideo" name="uploading[]" data-id="uploadReel">
                </label>
            </form> 
        </div>
        <div class="i_uploading_not_story flex_ tabing nonePoint">
            <?php echo iN_HelpSecure($LANG['uploading_please_wait']); ?>
        </div>
        <!-- Progress bar container -->
        <div class="i_uploaded_iv" style="position: relative; margin-top: 10px; display: none;">
            <!-- Progress bar will be inserted here by JavaScript -->
        </div>
      </div>
    </div> 

    <div class="edit_created_stories">
        <?php 
            
            $reelData = $iN->iN_GetReelsVideoDetailsByID($userID, $fileID);
            if($reelData){
                $videoID = $reelData['upload_id']; 
                $videoUploadedType = $reelData['upload_type'];
                $reelUploadedFilePath = $reelData['uploaded_file_path'];
                $reelUploadedfileExtension = $reelData['uploaded_file_ext'];
                $reelUploadedFileTumbnail = $reelData['upload_tumbnail_file_path'];
                $createdTime = $reelData['upload_time'];
                $crTime = date('Y-m-d H:i:s', $createdTime);

                // Always use video file path for playback, not thumbnail
                if (function_exists('storage_public_url')) {
                    $filePathUrl = storage_public_url($reelUploadedFilePath);
                    $thumbnailUrl = storage_public_url($reelUploadedFileTumbnail);
                } else {
                    // For local storage, use direct path without base_url to avoid /index.php/ prefix
                    $filePathUrl = '/' . ltrim($reelUploadedFilePath, '/');
                    $thumbnailUrl = $reelUploadedFileTumbnail ? '/' . ltrim($reelUploadedFileTumbnail, '/') : '';
                }
        ?>
        <div class="uploaded_storie_container body_<?php echo iN_HelpSecure($videoID); ?>">
        <div class="shared_storie_time flex_">
          <?php echo $iN->iN_SelectedMenuIcon('115') . ' ' . TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
        </div>
        <div class="dmyStory dmyStory_extra" id="<?php echo iN_HelpSecure($videoID); ?>">
          <div class="i_h_in flex_ ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['delete']); ?>">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
          </div>
        </div>
        <div class="uploaded_storie_image uploaded_storie_before border_one tabing flex_ aft">
          <video class="lg-video-object" id="v<?php echo iN_HelpSecure($videoID); ?>" controls preload="none" poster="<?php echo $thumbnailUrl; ?>">
            <source src="<?php echo $filePathUrl; ?>" preload="metadata" type="video/mp4">
          </video>
        </div>
        <!---->
        
        <?php if ($userWhoCanSeePost === '4') : ?>
        <div class="point_input_wrapper">
            <input
                type="text"
                name="point"
                id="point"
                class="pointIN"
                onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)"
                placeholder="<?php echo iN_HelpSecure($LANG['write_points']); ?>">
            <div class="box_not box_not_padding_left">
                <?php echo iN_HelpSecure($LANG['point_wanted']); ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="mentions_list nonePoint"></div>

        <div class="i_form_buttons">
            <div class="i_pb_emojis transition">
                <div class="i_pb_emojisBox getEmojis" data-type="emojiBox">
                    <div class="i_pb_emojis_Box">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('25')); ?>
                    </div>
                </div>
            </div>
    
            <div class="form_who_see transition">
                <div class="whoSeeBox whs">
                    <div class="wBox">
                        <?php echo html_entity_decode($activeWhoCanSee); ?>
                    </div>
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
                </div>
    
                <div class="i_choose_ws_wrapper">
                    <div class="whctt"><?php echo iN_HelpSecure($LANG['whocanseethis']); ?></div>
    
                    <div class="i_whoseech_menu_item_out wsUpdate transition <?php echo $userWhoCanSeePost === 1 ? 'wselected' : ''; ?>" data-id="1" id="wsUpdate1">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('50')); ?> <?php echo iN_HelpSecure($LANG['weveryone']); ?>
                    </div>
    
                    <div class="i_whoseech_menu_item_out wsUpdate transition <?php echo $userWhoCanSeePost === 2 ? 'wselected' : ''; ?>" data-id="2" id="wsUpdate2">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15')); ?> <?php echo iN_HelpSecure($LANG['wfollowers']); ?>
                    </div>
    
                    <?php if ($feesStatus === '2') : ?>
                        <div class="i_whoseech_menu_item_out wsUpdate transition <?php echo $userWhoCanSeePost === 3 ? 'wselected' : ''; ?>" data-id="3" id="wsUpdate3">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')); ?> <?php echo iN_HelpSecure($LANG['wsubscribers']); ?>
                        </div>
    
                        <div class="i_whoseech_menu_item_out wsUpdate transition <?php echo $userWhoCanSeePost === 4 ? 'wselected' : ''; ?>" data-id="4" id="wsUpdate4">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9')); ?> <?php echo iN_HelpSecure($LANG['premium']); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <input type="hidden" id="uploadVal" value="<?php echo iN_HelpSecure($fileID);?>">
            </div>
        </div>
        <!---->
        <div class="i_post_form_textarea">
          <textarea name="postText"
                id="newPostT"
                maxlength="<?php echo iN_HelpSecure($availableLength); ?>"
                class="comment commenta newPostT"
                placeholder="<?php echo iN_HelpSecure($LANG['write_message_add_photo_or_video']); ?>" placeholder="Do you want to write something about this storie?"></textarea>
        </div>
        <div class="i_warning"><?php echo iN_HelpSecure($LANG['please_enter_a_message_or_add_a_photo_or_video']); ?></div>
        <div class="i_warning_point"><?php echo iN_HelpSecure($LANG['must_write_point_for_post']); ?></div>
        <div class="i_warning_point_two"><?php echo iN_HelpSecure($LANG['must_be_start_with_number']); ?></div>
        <div class="i_warning_prmfl"><?php echo iN_HelpSecure($LANG['must_upload_files_for_premium']); ?></div>
        <div class="i_warning_unsupported"><?php echo iN_HelpSecure($LANG['unsupported_video_format']); ?></div>
        <div class="i_upload_warning"></div>
        <div class="share_story_btn_cnt flex_ tabing transition publishReels" id="<?php echo iN_HelpSecure($videoID); ?>">
          <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?>
          <div class="pbtn"><?php echo iN_HelpSecure($LANG['publish']); ?></div>
        </div>
      </div>
        <?php } ?>
    </div>
  
  </div>
</div>

<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/saveReelsHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
