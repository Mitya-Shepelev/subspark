<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        
        <div class="i_general_title_box">
          <?php echo iN_HelpSecure($LANG['limits']);?>
        </div>
         
        <div class="i_general_row_box column flex_" id="general_conf">
        <form enctype="multipart/form-data" method="post" id="limits">
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['autofollow_admin']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="autoFollowAdmin">
                        <input type="checkbox" name="autoFollowAdmin" class="chmdMod" id="autoFollowAdmin" <?php echo iN_HelpSecure($autoFollowAdmin) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="autoFollowAdmin" class="autoFollowAdmin" value="<?php echo iN_HelpSecure($autoFollowAdmin);?>">
                    <div class="success_tick tabing flex_ sec_one autoFollowAdmin"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['autofollow_admin_not']);?></div>
                 
               </div>
            </div>
            
            
            <div class="arrow"></div>

            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['reels_feature_status']);?></div>
               <div class="irow_box_right">
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['disable']);?></div>
                        <label class="el-switch el-switch-yellow" for="reelsFeatureStatus">
                          <input type="checkbox" id="reelsFeatureStatus" <?php echo ($reelsFeatureStatus ?? '1') === '1' ? 'checked="checked"' : ''; ?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="reels_feature_status" id="reelsFeatureStatusValue" value="<?php echo ($reelsFeatureStatus ?? '0'); ?>">
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['enable']);?></div>
                    <div class="success_tick tabing flex_ sec_one reelsFeatureStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
</div>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var reelsToggle = document.getElementById('reelsFeatureStatus');
    var reelsHidden = document.getElementById('reelsFeatureStatusValue');
    if (reelsToggle && reelsHidden) {
        var syncReelsValue = function () {
            reelsHidden.value = reelsToggle.checked ? '1' : '0';
        };
        syncReelsValue();
        reelsToggle.addEventListener('change', syncReelsValue);
    }
});
</script>

            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['max_reel_duration']);?></div>
               <div class="irow_box_right">
                 <input type="number" min="1" step="1" name="max_video_duration" class="i_input flex_" value="<?php echo iN_HelpSecure($maxVideoDuration ?? '15'); ?>">
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['applied_to_reels_processing_limit']);?></div>
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['search_results']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['search_in_all_users']);?></div>
                      <label class="el-switch el-switch-yellow" for="searchResultUpdate">
                        <input type="checkbox" name="searchResultUpdate" class="chmdMod" id="searchResultUpdate" <?php echo iN_HelpSecure($whicUsers) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['only_content_creators']);?></div>
                      <input type="hidden" name="searchResultUpdate" class="searchResultUpdate" value="<?php echo iN_HelpSecure($whicUsers);?>">
                    <div class="success_tick tabing flex_ sec_one searchResultUpdate"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="arrow"></div>
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['video_call_feature_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="videoCallFeatureStatus">
                        <input type="checkbox" name="videoCallFeatureStatus" class="chmdMod" id="videoCallFeatureStatus" <?php echo iN_HelpSecure($videoCallFeatureStatus) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="videoCallFeatureStatus" class="videoCallFeatureStatus" value="<?php echo iN_HelpSecure($videoCallFeatureStatus);?>">
                    <div class="success_tick tabing flex_ sec_one videoCallFeatureStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['who_can_create_videocall']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['everyone_can_create_a_videocall']);?></div>
                        <label class="el-switch el-switch-yellow" for="whoCanCreateVideoCall">
                          <input type="checkbox" name="whoCanCreateVideoCall" class="chmdMod" id="whoCanCreateVideoCall" <?php echo iN_HelpSecure($whoCanCreateVideoCall) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="whoCanCreateVideoCall" class="whoCanCreateVideoCall" value="<?php echo iN_HelpSecure($whoCanCreateVideoCall);?>">
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['just_creators_can_create_videocall']);?></div>
                    <div class="success_tick tabing flex_ sec_one whoCanCreateVideoCall"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['vidoe_call_free_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="isVideoCallFree">
                        <input type="checkbox" name="isVideoCallFree" class="chmdMod" id="isVideoCallFree" <?php echo iN_HelpSecure($isVideoCallFree) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="isVideoCallFree" class="isVideoCallFree" value="<?php echo iN_HelpSecure($isVideoCallFree);?>">
                    <div class="success_tick tabing flex_ sec_one isVideoCallFree"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="arrow"></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['story_feature_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="storyFeatureStatus">
                        <input type="checkbox" name="storyFeatureStatus" class="chmdMod" id="storyFeatureStatus" <?php echo iN_HelpSecure($iN->iN_StoryData($userID, '1')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="storyFeatureStatus" class="storyFeatureStatus" value="<?php echo iN_HelpSecure($iN->iN_StoryData($userID, '1'));?>">
                    <div class="success_tick tabing flex_ sec_one storyFeatureStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['who_can_create_story']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['just_creators_can_create_story']);?></div>
                        <label class="el-switch el-switch-yellow" for="whoCanCretaStory">
                          <input type="checkbox" name="whoCanCretaStory" class="chmdMod" id="whoCanCretaStory" <?php echo iN_HelpSecure($iN->iN_StoryData($userID, '4')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="whoCanCretaStory" class="whoCanCretaStory" value="<?php echo iN_HelpSecure($iN->iN_StoryData($userID, '4'));?>">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['everyone_can_create_a_story']);?></div>
                    <div class="success_tick tabing flex_ sec_one whoCanCretaStory"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['story_image_feature_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="storyImageFeatureStatus">
                        <input type="checkbox" name="storyImageFeatureStatus" class="chmdMod" id="storyImageFeatureStatus" <?php echo iN_HelpSecure($iN->iN_StoryData($userID, '2')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="storyImageFeatureStatus" class="storyImageFeatureStatus" value="<?php echo iN_HelpSecure($iN->iN_StoryData($userID, '2'));?>">
                    <div class="success_tick tabing flex_ sec_one storyImageFeatureStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['story_text_feature_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="storyTextFeatureStatus">
                        <input type="checkbox" name="storyTextFeatureStatus" class="chmdMod" id="storyTextFeatureStatus" <?php echo iN_HelpSecure($iN->iN_StoryData($userID, '3')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="storyTextFeatureStatus" class="storyTextFeatureStatus" value="<?php echo iN_HelpSecure($iN->iN_StoryData($userID, '3'));?>">
                    <div class="success_tick tabing flex_ sec_one storyTextFeatureStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            <div class="arrow"></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['shop_feature_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="shopFeatureStatus">
                        <input type="checkbox" name="shopFeatureStatus" class="chmdMod" id="shopFeatureStatus" <?php echo iN_HelpSecure($iN->iN_ShopData($userID, '1')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="shopFeatureStatus" class="shopFeatureStatus" value="<?php echo iN_HelpSecure($iN->iN_ShopData($userID, '1'));?>">
                    <div class="success_tick tabing flex_ sec_one shopFeatureStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['who_can_create_product']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['everyone_can_create_a_product']);?></div>
                        <label class="el-switch el-switch-yellow" for="whoCanCretaProduct">
                          <input type="checkbox" name="whoCanCretaProduct" class="chmdMod" id="whoCanCretaProduct" <?php echo iN_HelpSecure($iN->iN_ShopData($userID, '8')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="whoCanCretaProduct" class="whoCanCretaProduct" value="<?php echo iN_HelpSecure($iN->iN_ShopData($userID, '8'));?>">
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['just_creators_can_create_product']);?></div>
                    <div class="success_tick tabing flex_ sec_one whoCanCretaProduct"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
              
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['create_from_scratch_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="shopScratchStatus">
                        <input type="checkbox" name="shopScratchStatus" class="chmdMod" id="shopScratchStatus" <?php echo iN_HelpSecure($iN->iN_ShopData($userID, '2')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="shopScratchStatus" class="shopScratchStatus" value="<?php echo iN_HelpSecure($iN->iN_ShopData($userID, '2'));?>">
                    <div class="success_tick tabing flex_ sec_one shopScratchStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['create_a_book_a_zoom_product']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="shopBookaZoomStatus">
                        <input type="checkbox" name="shopBookaZoomStatus" class="chmdMod" id="shopBookaZoomStatus" <?php echo iN_HelpSecure($iN->iN_ShopData($userID, '3')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="shopBookaZoomStatus" class="shopBookaZoomStatus" value="<?php echo iN_HelpSecure($iN->iN_ShopData($userID, '3'));?>">
                    <div class="success_tick tabing flex_ sec_one shopBookaZoomStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['create_a_digital_download_product']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="shopDigitalDownloadStatus">
                        <input type="checkbox" name="shopDigitalDownloadStatus" class="chmdMod" id="shopDigitalDownloadStatus" <?php echo iN_HelpSecure($iN->iN_ShopData($userID, '4')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="shopDigitalDownloadStatus" class="shopDigitalDownloadStatus" value="<?php echo iN_HelpSecure($iN->iN_ShopData($userID, '4'));?>">
                    <div class="success_tick tabing flex_ sec_one shopDigitalDownloadStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['create_a_live_event_ticket_product']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="shopLiveEventTicketStatus">
                        <input type="checkbox" name="shopLiveEventTicketStatus" class="chmdMod" id="shopLiveEventTicketStatus" <?php echo iN_HelpSecure($iN->iN_ShopData($userID, '5')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="shopLiveEventTicketStatus" class="shopLiveEventTicketStatus" value="<?php echo iN_HelpSecure($iN->iN_ShopData($userID, '5'));?>">
                    <div class="success_tick tabing flex_ sec_one shopLiveEventTicketStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['create_a_art_commission_product']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="shopArtCommissionStatus">
                        <input type="checkbox" name="shopArtCommissionStatus" class="chmdMod" id="shopArtCommissionStatus" <?php echo iN_HelpSecure($iN->iN_ShopData($userID, '6')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="shopArtCommissionStatus" class="shopArtCommissionStatus" value="<?php echo iN_HelpSecure($iN->iN_ShopData($userID, '6'));?>">
                    <div class="success_tick tabing flex_ sec_one shopArtCommissionStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['create_a_join_instagram_close_friends_product']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                      <label class="el-switch el-switch-yellow" for="shopInstagramGloseFriendsStatus">
                        <input type="checkbox" name="shopInstagramGloseFriendsStatus" class="chmdMod" id="shopInstagramGloseFriendsStatus" <?php echo iN_HelpSecure($iN->iN_ShopData($userID, '7')) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                      <span class="el-switch-style"></span>
                      </label>
                      <input type="hidden" name="shopInstagramGloseFriendsStatus" class="shopInstagramGloseFriendsStatus" value="<?php echo iN_HelpSecure($iN->iN_ShopData($userID, '7'));?>">
                    <div class="success_tick tabing flex_ sec_one shopInstagramGloseFriendsStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
            
          <div class="arrow"></div>
          
          <div class="i_general_row_box_item flex_ tabing_non_justify">
              <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['accept_creator_status']);?></div>
              <div class="irow_box_right">
                
                <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                  <div class="el-radio el-radio-yellow">
                    <span class="margin-r"><?php echo iN_HelpSecure($LANG['to_become_content_creator']);?></span>
                    <input type="radio" name="radio1" class="chmdCrAc" id="request" <?php echo iN_HelpSecure($beaCreatorStatus) == 'request' ? 'value="request" checked="checked"' : 'value="request"';?>>
                    <label class="el-radio-style" for="request"></label>
                  </div>
                    <div class="success_tick tabing flex_ sec_one request"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                
                
                <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                  <div class="el-radio el-radio-yellow">
                    <span class="margin-r"><?php echo iN_HelpSecure($LANG['only_admin_can_set_creator']);?></span>
                    <input type="radio" name="radio1" class="chmdCrAc" id="admin_accept" <?php echo iN_HelpSecure($beaCreatorStatus) == 'admin_accept' ? 'value="admin_accept" checked="checked"' : 'value="admin_accept"';?>>
                    <label class="el-radio-style" for="admin_accept"></label>
                  </div>
                    <div class="success_tick tabing flex_ sec_one admin_accept"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                
                
                <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                  <div class="el-radio el-radio-yellow">
                    <span class="margin-r"><?php echo iN_HelpSecure($LANG['automatically_make_creator']);?></span>
                    <input type="radio" name="radio1" class="chmdCrAc" id="auto_approve" <?php echo iN_HelpSecure($beaCreatorStatus) == 'auto_approve' ? 'value="auto_approve" checked="checked"' : 'value="auto_approve"';?>>
                    <label class="el-radio-style" for="auto_approve"></label>
                  </div>
                    <div class="success_tick tabing flex_ sec_one auto_approve"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                
              </div>
          </div>
          
          <div class="arrow"></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['post_create_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['just_creators_can_creta_a_post']);?></div>
                        <label class="el-switch el-switch-yellow" for="postCreateStatus">
                          <input type="checkbox" name="postCreateStatus" class="chmdPost" id="postCreateStatus" <?php echo iN_HelpSecure($normalUserCanPost) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="postCreateStatus" class="postCreateStatus" value="<?php echo iN_HelpSecure($normalUserCanPost);?>">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['normal_user_can_create_a_post']);?></div>
                    <div class="success_tick tabing flex_ sec_one postCreateStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['block_countries_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['can_block_countries']);?></div>
                        <label class="el-switch el-switch-yellow" for="blockCountriesStatus">
                          <input type="checkbox" name="blockCountriesStatus" class="chmdBlockCountries" id="blockCountriesStatus" <?php echo iN_HelpSecure($userCanBlockCountryStatus) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="blockCountriesStatus" class="blockCountriesStatus" value="<?php echo iN_HelpSecure($userCanBlockCountryStatus);?>">
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['can_not_block_countries']);?></div>
                    <div class="success_tick tabing flex_ sec_one blockCountriesStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['auto_approve_post']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['approve_posts_automatically']);?></div>
                        <label class="el-switch el-switch-yellow" for="autoApprovePost">
                          <input type="checkbox" name="autoApprovePost" class="chmdAutoApprovePost" id="autoApprovePost" <?php echo iN_HelpSecure($autoApprovePostStatus) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="autoApprovePost" class="autoApprovePost" value="<?php echo iN_HelpSecure($autoApprovePostStatus);?>">
                    <div class="success_tick tabing flex_ sec_one autoApprovePost"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['upload_size_limit']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="fl_limit"><span class="lmt"><?php echo iN_HelpSecure($MBLIMITS[$availableUploadFileSize]);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($MBLIMITS as $country => $value){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($availableUploadFileSize) == '' . $country . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($country); ?>' data-c="<?php echo iN_HelpSecure($value);?>" data-type="mb_limit"><?php echo iN_HelpSecure($value); ?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="file_limit" id="upLimit" value="<?php echo iN_HelpSecure($availableUploadFileSize);?>">
                        </div>
                        <div class="rec_not box_not_padding_top">
                            <?php echo iN_HelpSecure($LANG['attantion_server_default_maximum_file_upload_sizes']);?>
                            <p>
                            <?php
                                $serverLimits = array(
                                    'post_max_size' => ini_get('post_max_size'),
                                    'upload_max_filesize' => ini_get('upload_max_filesize'),
                                    'max_execution_time' => ini_get('max_execution_time'),
                                    'max_input_vars' => ini_get('max_input_vars'),
                                    'max_input_time' => ini_get('max_input_time'),
                                    'memory_limit' => ini_get('memory_limit')
                                );
                                foreach ($serverLimits as $key => $value) {
                                    echo $key . ': ' . $value . '<br>';
                                }
                            ?>
                            </p>
                        </div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['post_length']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="ch_limit"><span class="lct"><?php echo iN_HelpSecure($availableLength).' '.$LANG['character'];?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_ch_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($LIMITLENGTH as $chLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($availableLength) == '' . $chLimit . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($chLimit); ?>' data-c="<?php echo preg_replace( '/{.*?}/', $chLimit, $LANG['limit_character']);?>" data-type="characterLimit"><?php echo iN_HelpSecure($chLimit).' '.$LANG['character'];?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="length_limit" id="upcLimit" value="<?php echo iN_HelpSecure($availableLength);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['max_character']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['show_number_of_post']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="cp_limit"><span class="lppt"><?php echo iN_HelpSecure($scrollLimit);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_cp_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($POSTLIMIT as $cpLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($scrollLimit) == '' . iN_HelpSecure($cpLimit) . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($cpLimit); ?>' data-c="<?php echo iN_HelpSecure($cpLimit);?>" data-type="postLimit"><?php echo iN_HelpSecure($cpLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="post_show_limit" id="uppLimit" value="<?php echo iN_HelpSecure($scrollLimit);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['also_displayed_whe_page_scrolled']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['show_number_of_message']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="cpm_limit"><span class="lpptm"><?php echo iN_HelpSecure($scrollToLimitMessage);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_mp_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($POSTLIMIT as $cpLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($scrollToLimitMessage) == '' . iN_HelpSecure($cpLimit) . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($cpLimit); ?>' data-c="<?php echo iN_HelpSecure($cpLimit);?>" data-type="postMLimit"><?php echo iN_HelpSecure($cpLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="message_show_limit" id="uppmLimit" value="<?php echo iN_HelpSecure($scrollToLimitMessage);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['also_displayed_when_message_page_scrolled']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['show_number_of_paginaton']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="p_limit"><span class="ppt"><?php echo iN_HelpSecure($paginationLimit);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_p_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($PAGINATIONLIMIT as $pLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($paginationLimit) == '' . $pLimit . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($pLimit); ?>' data-c="<?php echo iN_HelpSecure($pLimit);?>" data-type="pagLimit"><?php echo iN_HelpSecure($pLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="pagination_limit" id="ppLimit" value="<?php echo iN_HelpSecure($paginationLimit);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['also_displayed_whe_page_scrolled']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['show_number_of_ads']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="cpa_limit"><span class="lppat"><?php echo iN_HelpSecure($showNumberOfAds);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_cpads_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($LIMITSONETOTEN as $cpLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($showNumberOfAds) == '' . iN_HelpSecure($cpLimit) . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($cpLimit); ?>' data-c="<?php echo iN_HelpSecure($cpLimit);?>" data-type="adsLimit"><?php echo iN_HelpSecure($cpLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="ads_show_limit" id="uppadLimit" value="<?php echo iN_HelpSecure($showNumberOfAds);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['show_number_of_ads_not']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['show_number_of_suggested_user']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="cpu_limit"><span class="lppsug"><?php echo iN_HelpSecure($showingNumberOfSuggestedUser);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_cpsugg_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($LIMITSONETOTEN as $cpLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($showingNumberOfSuggestedUser) == '' . iN_HelpSecure($cpLimit) . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($cpLimit); ?>' data-c="<?php echo iN_HelpSecure($cpLimit);?>" data-type="sugUserLimit"><?php echo iN_HelpSecure($cpLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="suggu_show_limit" id="uppsugLimit" value="<?php echo iN_HelpSecure($showingNumberOfSuggestedUser);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['show_number_of_suggested_user_not']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['show_number_of_suggested_product']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="cprod_limit"><span class="lppprod"><?php echo iN_HelpSecure($showingNumberOfProduct);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_cpprod_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($LIMITSONETOTEN as $cpLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($showingNumberOfProduct) == '' . iN_HelpSecure($cpLimit) . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($cpLimit); ?>' data-c="<?php echo iN_HelpSecure($cpLimit);?>" data-type="sugProdLimit"><?php echo iN_HelpSecure($cpLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="prod_show_limit" id="uppprodLimit" value="<?php echo iN_HelpSecure($showingNumberOfProduct);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['show_number_of_suggested_product_not']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['trending_calculation_time']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="cptrend_limit"><span class="lpptrend"><?php echo iN_HelpSecure($showingTrendPostLimitDay);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_cptrend_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($TRENDLIMITDAY as $cpLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($showingTrendPostLimitDay) == '' . iN_HelpSecure($cpLimit) . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($cpLimit); ?>' data-c="<?php echo iN_HelpSecure($cpLimit);?>" data-type="trendLimit"><?php echo iN_HelpSecure($cpLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="trend_show_limit" id="uppTrendLimit" value="<?php echo iN_HelpSecure($showingTrendPostLimitDay);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['trending_not']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['number_of_avtivity_to_be_shown']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="cpactivity_limit"><span class="lppfractivity"><?php echo iN_HelpSecure($showingActivityLimit);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_cpfriendactivities_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($TRENDLIMITDAY as $cpLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($showingActivityLimit) == '' . iN_HelpSecure($cpLimit) . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($cpLimit); ?>' data-c="<?php echo iN_HelpSecure($cpLimit);?>" data-type="activityLimit"><?php echo iN_HelpSecure($cpLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="activity_show_limit" id="uppFriendAvtivityLimit" value="<?php echo iN_HelpSecure($showingActivityLimit);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['friends_avtivity_not']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['how_many_days_activities_shown']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="cpactivityshown_limit"><span class="lppfractivityshown"><?php echo iN_HelpSecure($showingTimeActivityLimit);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_cpfriendactivitiesshown_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($TRENDLIMITDAY as $cpLimit){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($showingTimeActivityLimit) == '' . iN_HelpSecure($cpLimit) . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($cpLimit); ?>' data-c="<?php echo iN_HelpSecure($cpLimit);?>" data-type="activityshownLimit"><?php echo iN_HelpSecure($cpLimit);?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="activity_show_time_limit" id="uppFriendAvtivitySlownLimit" value="<?php echo iN_HelpSecure($showingTimeActivityLimit);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['friends_avtivity_time_not']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['allowed_file_extension']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="available_file_extensions" class="i_input flex_" value="<?php echo iN_HelpSecure($availableFileExtensions);?>">
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['separated_with']);?></div>
               </div>
            </div>
            
            <div class="warning_wrapper warning_two"><?php echo iN_HelpSecure($LANG['not_live_file_extensions_blank']);?></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['file_extensions_for_approval']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="available_verification_file_extensions" class="i_input flex_" value="<?php echo iN_HelpSecure($availableVerificationFileExtensions);?>">
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['separated_with']);?></div>
               </div>
            </div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['not_allowed_usernames']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="unavailable_usernames" class="i_input flex_" value="<?php echo iN_HelpSecure($disallowedUserNames);?>">
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['separated_with']);?></div>
               </div>
            </div>
            
            <div class="arrow"></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['ffmpeg_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['use_ffmpeg']);?></div>
                        <label class="el-switch el-switch-yellow" for="ffmpegMode">
                          <input type="checkbox" name="ffmpegMode" class="chmdPayment" id="ffmpegMode" <?php echo iN_HelpSecure($ffmpegStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['not_use_ffmpeg']);?></div>
                    <div class="success_tick tabing flex_ sec_one ffmpegMode"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['make_sure_ffmpeg_activated']);?><a href="<?php echo $base_url;?>ffmpegTest.php"><?php echo iN_HelpSecure($LANG['check_ffmpeg_path']);?></a></div>
            </div>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
           <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['ffprobe']);?></div>
           <div class="irow_box_right">
             <input type="text" name="ffprobe_path" class="i_input flex_" value="<?php echo iN_HelpSecure($ffprobePath ?? ''); ?>" placeholder="/usr/bin/ffprobe or C:\\ffmpeg\\bin\\ffprobe.exe">
             <div class="rec_not box_not_padding_top">
                <?php echo iN_HelpSecure($LANG['ffprobe_path'] ?? 'Set the full path to ffprobe if not in PATH.'); ?>
                <a href="<?php echo $base_url;?>ffmpegTest.php"><?php echo iN_HelpSecure($LANG['test_ffprobe']);?></a>
             </div>
           </div>
        </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['ffmpeg_path']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="ffmpeg_path" class="i_input flex_" value="<?php echo iN_HelpSecure($ffmpegPath);?>">
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['make_sure_ffmpeg_activated']);?></div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['paste_porfile_link_on_video']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['paste_approve']);?></div>
                        <label class="el-switch el-switch-yellow" for="drawTextMode">
                          <input type="checkbox" name="drawTextMode" class="chmdPayment" id="drawTextMode" <?php echo iN_HelpSecure($drawTextStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['paste_donot_approve']);?></div>
                    <div class="success_tick tabing flex_ sec_one drawTextMode"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['paste_note']);?></div>
               </div>
            </div>
            
            <div class="arrow"></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['re_captcha_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['recaptcha_inactive']);?></div>
                        <label class="el-switch el-switch-yellow" for="reCreateStatus">
                          <input type="checkbox" name="reCreateStatus" class="reCaptchaPost" id="reCreateStatus" <?php echo iN_HelpSecure($captchaStatus) == 'yes' ? 'value="yes" checked="checked"' : 'value="no"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['recaptcha_active']);?></div>
                    <div class="success_tick tabing flex_ sec_one reCreateStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div> 
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['recaptcha_site_key']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="rsitekey" class="i_input flex_" value="<?php echo iN_HelpSecure($captcha_site_key);?>">
               </div>
            </div> 
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['recaptcha_secret_key']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="rseckey" class="i_input flex_" value="<?php echo iN_HelpSecure($captcha_secret_key);?>">
               </div>
            </div>
            
            <div class="arrow"></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['one_signal_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['onesignal_inactive']);?></div>
                        <label class="el-switch el-switch-yellow" for="oneSignalStatus">
                          <input type="checkbox" name="oneSignalStatus" class="oneSignalStatuss" id="oneSignalStatus" <?php echo iN_HelpSecure($oneSignalStatus) == 'open' ? 'value="open" checked="checked"' : 'value="close"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                    <div class="i_chck_text admin_note_t"><?php echo iN_HelpSecure($LANG['onesignal_active']);?></div>
                    <div class="success_tick tabing flex_ sec_one oneSignalStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div> 
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['onesignal_api_key']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="onesignalapikey" class="i_input flex_" value="<?php echo iN_HelpSecure($oneSignalApi);?>">
               </div>
            </div>
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left flex_"><?php echo iN_HelpSecure($LANG['onesignal_restapikey']);?></div>
               <div class="irow_box_right">
                 <input type="text" name="onesignalrestapikey" class="i_input flex_" value="<?php echo iN_HelpSecure($oneSignalRestApi);?>">
               </div>
            </div> 
            <div class="warning_wrapper warning_one"><?php echo iN_HelpSecure($LANG['not_live_approved_file_extension']);?></div>
            <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']);?></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
                <input type="hidden" name="f" value="updateLimits">
                <button type="submit" name="submit" class="i_nex_btn_btn transition"><?php echo iN_HelpSecure($LANG['save_edit']);?></button>
            </div>
            
        </form>
        </div>
        
    </div>
</div>
