<div class="i_contents_container">
  <div class="i_general_white_board border_one column flex_ tabing__justify white_board_style">
    <div class="i_general_title_box">
      <?php echo iN_HelpSecure($LANG['approve_or_decline']); ?>
    </div>
    <div class="i_general_row_box column flex_ white_board_padding" id="general_conf">
      <form enctype="multipart/form-data" method="post" id="approvePostForm">
        <?php
        $postFromData = $iN->iN_GetAllPostDetails($approvePostID);
        if ($postFromData) {
          $userPostID = $postFromData['post_id'] ?? null;
          $userPostOwnerID = $postFromData['post_owner_id'] ?? null;
          $userPostText = $postFromData['post_text'] ?? null;
          $userPostFile = $postFromData['post_file'] ?? null;
          $userPostCreatedTime = $postFromData['post_created_time'] ?? null;
          $crTime = date('Y-m-d H:i:s', $userPostCreatedTime);
          $userPostWhoCanSee = $postFromData['who_can_see'] ?? null;
          $userPostWantStatus = $postFromData['post_want_status'] ?? null;
          $userPostWantedCredit = $postFromData['post_wanted_credit'] ?? null;
          $userPostStatus = $postFromData['post_status'] ?? null;
          $userPostOwnerUsername = $postFromData['i_username'] ?? null;
          $userPostOwnerUserFullName = $postFromData['i_user_fullname'] ?? null;
          $userPostOwnerUserGender = $postFromData['user_gender'] ?? null;
          $userPostCommentAvailableStatus = $postFromData['comment_status'] ?? null;
          $userPostOwnerUserLastLogin = $postFromData['last_login_time'] ?? null;
          $userPostPinStatus = $postFromData['post_pined'] ?? null;
          $slugUrl = $base_url . 'post/' . $postFromData['url_slug'] . '_' . $userPostID;
          $userPostSharedID = $postFromData['shared_post_id'] ?? null;
          $userPostOwnerUserAvatar = $iN->iN_UserAvatar($userPostOwnerID, $base_url);
          $userPostUserVerifiedStatus = $postFromData['user_verified_status'] ?? null;

          if ($userPostOwnerUserGender == 'male') {
            $publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
          } else if ($userPostOwnerUserGender == 'female') {
            $publisherGender = '<div class="i_plus_gf">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
          } else if ($userPostOwnerUserGender == 'couple') {
            $publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
          }

          $userVerifiedStatus = '';
          if ($userPostUserVerifiedStatus == '1') {
            $userVerifiedStatus = '<div class="i_plus_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
          }

          $postStyle = '';
          if ($userPostWhoCanSee == '1') {
            $onlySubs = '';
            $subPostTop = '';
            $wCanSee = '<div class="i_plus_public" id="ipublic_' . $userPostID . '">' . $iN->iN_SelectedMenuIcon('50') . '</div>';
          } else if ($userPostWhoCanSee == '2') {
            $subPostTop = '';
            $wCanSee = '<div class="i_plus_subs" id="ipublic_' . $userPostID . '">' . $iN->iN_SelectedMenuIcon('15') . '</div>';
            $onlySubs = '<div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">' . $iN->iN_SelectedMenuIcon('15') . '</div><div class="onlySubs_note">' . preg_replace('/{.*?}/', $userPostOwnerUserFullName, $LANG['only_followers']) . '</div></div></div>';
          } else if ($userPostWhoCanSee == '3') {
            $subPostTop = 'extensionPost';
            $wCanSee = '<div class="i_plus_public" id="ipublic_' . $userPostID . '">' . $iN->iN_SelectedMenuIcon('51') . '</div>';
            $onlySubs = '<div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">' . $iN->iN_SelectedMenuIcon('56') . '</div><div class="onlySubs_note">' . preg_replace('/{.*?}/', $userPostOwnerUserFullName, $LANG['only_subscribers']) . '</div></div></div>';
          } else if ($userPostWhoCanSee == '4') {
            $subPostTop = 'extensionPost';
            $wCanSee = '<div class="i_plus_public" id="ipublic_' . $userPostID . '">' . $iN->iN_SelectedMenuIcon('9') . '</div>';
            $onlySubs = '<div class="onlyPremium"><div class="onlySubsWrapper"><div class="premium_locked"><div class="premium_locked_icon">' . $iN->iN_SelectedMenuIcon('56') . '</div></div><div class="onlySubs_note"><div class="buyThisPost prcsPost" id="' . $userPostID . '">' . preg_replace('/{.*?}/', $userPostWantedCredit, $LANG['post_credit']) . '</div><div class="buythistext prcsPost" id="' . $userPostID . '">' . $LANG['purchase_post'] . '</div></div><div class="fr_subs uSubsModal transition" data-u="' . $userPostOwnerID . '">' . $iN->iN_SelectedMenuIcon('51') . $LANG['free_for_subscribers'] . '</div></div></div>';
          }
        ?>
        <div class="i_post_body body_<?php echo iN_HelpSecure($userPostID); ?> <?php echo html_entity_decode($subPostTop); ?>" id="<?php echo iN_HelpSecure($userPostID); ?>" data-last="<?php echo iN_HelpSecure($userPostID); ?>">
          <div class="i_post_body_header">
            <div class="i_post_user_avatar">
              <img src="<?php echo iN_HelpSecure($userPostOwnerUserAvatar); ?>" />
            </div>
            <div class="i_post_i">
              <div class="i_post_username">
                <a class="truncated" href="<?php echo iN_HelpSecure($base_url . $userPostOwnerUsername); ?>">
                  <?php echo iN_HelpSecure($userPostOwnerUserFullName); ?>
                  <?php echo html_entity_decode($publisherGender); ?>
                  <?php echo html_entity_decode($userVerifiedStatus); ?>
                  <?php echo html_entity_decode($wCanSee); ?>
                </a>
              </div>
              <div class="i_post_shared_time"><?php echo TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?></div>
            </div>
          </div>

          <div class="i_post_container flex_ <?php echo html_entity_decode($postStyle); ?>" id="i_post_container_<?php echo iN_HelpSecure($userPostID); ?>">
            <textarea class="more_textarea" name="newpostDesc" id="ed_<?php echo iN_HelpSecure($userPostID); ?>" placeholder="<?php echo iN_HelpSecure($LANG['write_something_about_the_post']); ?>"><?php if (!empty($userPostText)) {
              echo iN_HelpSecure($iN->br2nl($userPostText));
            } ?></textarea>
          </div>

          <div class="i_post_container flex_ <?php echo html_entity_decode($postStyle); ?>">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
            <input type="text" name="newPostPoint" class="point_input white_board_padding_left" id="pointed_<?php echo iN_HelpSecure($userPostID); ?>" placeholder="<?php echo iN_HelpSecure($LANG['write_something_about_the_post']); ?>" onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)" value="<?php if (!empty($userPostWantedCredit)) {
              echo iN_HelpSecure($iN->br2nl($userPostWantedCredit));
            } ?>">
          </div>
                    <!--POST IMAGES-->
          <div class="i_post_u_images">
            <?php
            $trimValue = rtrim($userPostFile, ',');
            $explodeFiles = explode(',', $trimValue);
            $explodeFiles = array_unique($explodeFiles);
            $countExplodedFiles = $iN->iN_CheckCountFile($userPostFile);
            if ($countExplodedFiles == 1) {
              $container = 'i_image_one';
            } else if ($countExplodedFiles == 2) {
              $container = 'i_image_two';
            } else if ($countExplodedFiles == 3) {
              $container = 'i_image_three';
            } else if ($countExplodedFiles == 4) {
              $container = 'i_image_four';
            } else if ($countExplodedFiles >= 5) {
              $container = 'i_image_five';
            }

            foreach ($explodeFiles as $explodeVideoFile) {
              $VideofileData = $iN->iN_GetUploadedFileDetails($explodeVideoFile);
              if ($VideofileData) {
                $VideofileUploadID = $VideofileData['upload_id'] ?? null;
                $VideofileExtension = $VideofileData['uploaded_file_ext'] ?? null;
                $VideofilePath = $VideofileData['uploaded_file_path'] ?? null;
                $VideofilePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $VideofilePath);
                if ($VideofileExtension == 'mp4') {
                  $VideoPathExtension = '.jpg';
                  if (function_exists('storage_public_url')) {
                    $VideofilePathUrl = storage_public_url($VideofilePath);
                    $VideofileTumbnailUrl = storage_public_url($VideofilePathWithoutExt . $VideoPathExtension);
                  } else {
                    $VideofilePathUrl = $base_url . $VideofilePath;
                    $VideofileTumbnailUrl = $base_url . $VideofilePathWithoutExt . $VideoPathExtension;
                  }

                  echo '
                    <div class="nonePoint" id="video' . $VideofileUploadID . '">
                      <video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none" onended="videoEnded()">
                        <source src="' . $VideofilePathUrl . '" type="video/mp4">
                        Your browser does not support HTML5 video.
                      </video>
                    </div>
                  ';
                }
              }
            }

            echo '<div class="' . $container . '" id="lightgallery' . $userPostID . '">';
            foreach ($explodeFiles as $dataFile) {
              $fileData = $iN->iN_GetUploadedFileDetails($dataFile);
              if ($fileData) {
                $fileUploadID = $fileData['upload_id'] ?? null;
                $fileExtension = $fileData['uploaded_file_ext'] ?? null;
                $filePath = $fileData['uploaded_file_path'] ?? null;
                $filePathTumbnail = $fileData['upload_tumbnail_file_path'] ?? null;

                if (function_exists('storage_public_url')) {
                  $filePathUrl = storage_public_url($filePath);
                } else {
                  $filePathUrl = $base_url . $filePath;
                }

                $videoPlaybutton = '';
                if ($fileExtension == 'mp4') {
                  $videoPlaybutton = '<div class="playbutton">' . $iN->iN_SelectedMenuIcon('55') . '</div>';
                  $PathExtension = '.jpg';

                  if (function_exists('storage_public_url')) {
                    $filePathUrl = storage_public_url($filePathTumbnail);
                  } else {
                    $filePathUrl = $base_url . $filePathTumbnail;
                  }

                  $fileisVideo = 'data-poster="' . $filePathUrl . '" data-html="#video' . $fileUploadID . '"';
                } else {
                  $fileisVideo = 'data-src="' . $filePathUrl . '"';
                }

                if ($fileExtension != 'mp3') {
            ?>
                  <div class="i_post_image_swip_wrapper image-skeleton" data-bg="<?php echo iN_HelpSecure($filePathUrl); ?>" <?php echo html_entity_decode($fileisVideo); ?>>
                    <?php echo html_entity_decode($videoPlaybutton); ?>
                    <img class="i_p_image" src="<?php echo iN_HelpSecure($filePathUrl); ?>" alt="Post image" loading="lazy">
                  </div>
            <?php
                }
              }
            }
            echo '</div>';
            ?>
          </div>
          <!--/POST IMAGES-->

          <script>
            window.lightGalleryConfig = window.lightGalleryConfig || {};
            window.lightGalleryConfig[<?php echo (int)$userPostID; ?>] = true;

            window.audioPlayers = window.audioPlayers || [];
            <?php
            foreach ($explodeFiles as $dataFile) {
              $fileData = $iN->iN_GetUploadedMp3FileDetails($dataFile);
              if ($fileData && $fileData['uploaded_file_ext'] === 'mp3') {
                echo 'window.audioPlayers.push("play_po_' . iN_HelpSecure($fileData['upload_id']) . '");';
              }
            }
            ?>
          </script>

          <?php
          echo '<div class="myaudio">';
          foreach ($explodeFiles as $dataFile) {
            $fileAudioData = $iN->iN_GetUploadedMp3FileDetails($dataFile);
            if ($fileAudioData) {
              $fileUploadID = $fileAudioData['upload_id'] ?? null;
              $fileExtension = $fileAudioData['uploaded_file_ext'] ?? null;
              $filePath = $fileAudioData['uploaded_file_path'] ?? null;

              if ($fileExtension == 'mp3') {
                if (function_exists('storage_public_url')) {
                  $filePathUrl = storage_public_url($filePath);
                  $filePathTumbnailUrl = $filePathUrl;
                } else {
                  $filePathUrl = $base_url . $filePath;
                  $filePathTumbnailUrl = $filePathUrl;
                }

                $audShowType = '<audio crossorigin="" preload="none"><source src="' . iN_HelpSecure($filePathUrl) . '" type="audio/mp3" /></audio>';
                $fileisVideo = 'data-src="' . $filePathTumbnailUrl . '"';
          ?>
                <div class="i_post_image_swip_wrappera image-skeleton" <?php echo html_entity_decode($fileisVideo); ?>>
                  <div id="play_po_<?php echo iN_HelpSecure($fileUploadID); ?>" class="green-audio-player">
                    <?php echo html_entity_decode($audShowType); ?>
                  </div>
                </div>
          <?php
              }
            }
          }
          echo '</div>';
          ?>
                    <div class="admin_approve_post_footer">
            <div class="add_app_not"><?php echo iN_HelpSecure($LANG['add_not_to_the_post_owner']); ?></div>
            <div class="i_not_container flex_" id="i_not_container_<?php echo iN_HelpSecure($userPostID); ?>">
              <textarea class="more_textarea" name="approve_not" id="ad_not_ed_<?php echo iN_HelpSecure($userPostID); ?>" placeholder="<?php echo iN_HelpSecure($LANG['write_your_not']); ?>"></textarea>
            </div>
            <div class="i_not_container flex_ column" id="i_not_container_<?php echo iN_HelpSecure($userPostID); ?>">
              <div class="approve_ch_item flex_ column border_one transition choosed" id="appr_1" data-val="1">
                <div class="flex_ tabing_non_justify">
                  <div class="approve_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('112')); ?></div>
                  <div class="approve_title flex_ tabing__justify"><?php echo iN_HelpSecure($LANG['approve']); ?></div>
                </div>
                <div class="rec_not padding_left_ten"><?php echo iN_HelpSecure($LANG['be_carefuly_check_post']); ?></div>
              </div>
              <div class="approve_ch_item flex_ column border_one transition" id="appr_2" data-val="2">
                <div class="flex_ tabing_non_justify">
                  <div class="reject_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('113')); ?></div>
                  <div class="approve_title flex_ tabing__justify"><?php echo iN_HelpSecure($LANG['reject']); ?></div>
                </div>
                <div class="rec_not padding_left_ten"><?php echo iN_HelpSecure($LANG['rejected_not']); ?></div>
              </div>
              <div class="approve_ch_item flex_ column border_one transition" id="appr_3" data-val="3">
                <div class="flex_ tabing_non_justify">
                  <div class="declined_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('114')); ?></div>
                  <div class="approve_title flex_ tabing__justify"><?php echo iN_HelpSecure($LANG['decline']); ?></div>
                </div>
                <div class="rec_not padding_left_ten"><?php echo iN_HelpSecure($LANG['decline_not']); ?></div>
              </div>
              <input type="hidden" name="postApproveStatus" id="approve_type" value="1">
            </div>
            <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']); ?></div>
            <div class="i_become_creator_box_footer">
              <input type="hidden" name="f" value="postApprove">
              <input type="hidden" name="postOwnerID" value="<?php echo iN_HelpSecure($userPostOwnerID); ?>">
              <input type="hidden" name="postID" value="<?php echo iN_HelpSecure($userPostID); ?>">
              <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile"><?php echo iN_HelpSecure($LANG['save_edit']); ?></button>
            </div>
          </div>
        </div>
      <?php } ?>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/approvePostHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
