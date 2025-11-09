<div class="th_middle">
   <div class="pageMiddle">
      <div class="live_item transition">
         <div class="live_title_page create_stories flex_">
            <?php echo $iN->iN_SelectedMenuIcon('154'); ?>
            <?php echo iN_HelpSecure($LANG['createyourtextstatus']); ?>
         </div>
      </div>
      <div class="create_sotry_form_container">
         <div class="create_text_story_bg_wrapper flex_">
            <div class="bgs"><?php echo iN_HelpSecure($LANG['bgs']); ?></div>
            <?php
            $bgImages = $iN->iN_GetStoryBgImages();
            if ($bgImages) {
                foreach ($bgImages as $bgData) {
                    $bgID = $bgData['st_bg_id'] ?? null;
                    $bgImage = $bgData['st_bg_img_url'] ?? null;
                    $choosedStatus = $bgData['choosed_status'] ?? null;

                    if (function_exists('storage_public_url')) {
                        $filePathUrl = storage_public_url($bgImage);
                    } else {
                        $filePathUrl = $base_url . $bgImage;
                    }
            ?>
            <div class="st_bg_cont">
                <div class="st_img_wrapper relativePosition <?php echo iN_HelpSecure($choosedStatus) == 'ok' ? 'choosed_bg' : ''; ?>"
                     data-bg="<?php echo iN_HelpSecure($filePathUrl); ?>"
                     data-img="<?php echo iN_HelpSecure($filePathUrl); ?>"
                     data-iid="<?php echo iN_HelpSecure($bgID); ?>">
                    <div class="loader"></div>
                </div>
            </div>
            <?php
                }
            }
            ?>
            <div class="typing_textarea typing_textarea_story">
               <textarea class="strt_typing" id="strt_text" placeholder="<?php echo iN_HelpSecure($LANG['start_typing']); ?>"></textarea>
            </div>
            <div class="choosed_image">
               <div class="choosed_image_or">
                  <div class="text_typed flex_ tabing"><?php echo iN_HelpSecure($LANG['start_typing']); ?></div>
                  <img id="theBg" src="<?php echo iN_HelpSecure($base_url . $iN->iN_GetChoosedBgImage()); ?>">
               </div>
            </div>
            <div class="share_my_story">
               <div class="share_story_btn_cnt flex_ tabing transition share_text_story">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?>
                  <div class="pbtn"><?php echo iN_HelpSecure($LANG['publish']); ?></div>
               </div>
            </div>
         </div>
      </div>
      <div class="edit_created_stories"></div>
      <div class="non-shared-yet"></div>
   </div>
</div>  
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/textStoryHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
