<?php
$checkUserPurchasedThisLiveStream = '1';
if($userID != $liveCreator){
    $checkUserPurchasedThisLiveStream = $iN->iN_CheckUserPurchasedThisLiveStream($userID, $liveID);
}
if($checkUserPurchasedThisLiveStream || $userType == '2'){?>
<div class="live_wrapper_tik" id="<?php echo iN_HelpSecure($liveID);?>">
    <div class="live_left"> 
        <div class="live_left_in_wrapper">
            <div class="live_left_in_holder">
                <a href="<?php echo iN_HelpSecure($base_url);?>">
                 <div class="i_left_menu_box transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('99'));?> <div class="m_tit"><?php echo iN_HelpSecure($LANG['home_page']);?></div>
                 </div>
                 </a>
                 <div class="i_left_menu_box transition g_feed" data-get="friends" data-type="moreposts">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('7'));?> <div class="m_tit"><?php echo iN_HelpSecure($LANG['newsfeed']);?></div>
                 </div> 
                 <div class="i_left_menu_box transition g_feed" data-get="allPosts" data-type="moreexplore">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('8'));?> <div class="m_tit"><?php echo iN_HelpSecure($LANG['explore']);?></div>
                 </div> 
                 <div class="i_left_menu_box transition g_feed" data-get="premiums" data-type="morepremium">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9'));?> <div class="m_tit"><?php echo iN_HelpSecure($LANG['premium']);?></div>
                 </div> 
                 <a href="<?php echo iN_HelpSecure($base_url);?>creators">
                  <div class="i_left_menu_box transition">
                     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('95'));?> <div class="m_tit"><?php echo iN_HelpSecure($LANG['our_creators']);?></div>
                  </div>
                 </a> 
                 <div class="live_suggested_lives_wrapper">
                     <?php include "live_list_widget.php";?>
                 </div> 
            </div>
        </div> 
    </div>
    <div class="live_right">
       <div class="live_right_in_wrapper"> 
           <div class="live_right_in_left"> 
               <div class="live_video_header">
                  <div class="live_creator_avatar_live flex_ tabing"><a class="flex_ alignItem" href="<?php echo $base_url.$liveCreatorUserName;?>" target="blank_"><img src="<?php echo iN_HelpSecure($liveCreatorAvatar);?>"></a></div>
                  <div class="live_creator_live_name_live_username">
                      <div class="live_creator_live_username"><a class="flex_ alignItem exen loi" href="<?php echo $base_url.$liveCreatorUserName;?>" target="blank_"><?php echo iN_HelpSecure($liveCreatorFullname);?></a></div>
                      <div class="live_creator_live_name flex_ tabing"><?php echo $siteTitle;?>  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15'));?><span class="sumonline">0</span></div>
                  </div>
                  <div class="live_header_in_right flex_ tabing">
                      <div class="live_owner_flw_btn">
                        <?php if ($p_friend_status != 'subscriber' && $p_friend_status != 'me' && $p_friend_status != 'flwr') {?>
                            <div class="i_fw<?php echo iN_HelpSecure($liveCreator); ?> transition <?php echo iN_HelpSecure($flwrBtn); ?>" id="i_btn_like_item" data-u="<?php echo iN_HelpSecure($liveCreator); ?>">
                            <?php echo html_entity_decode($flwBtnIconText); ?>
                            </div>
                        <?php }?>
                      </div>
                      <div class="live_mics_cameras flex_ tabing">
                          <?php if($userID == $liveCreator){ ?>
                            <div class="i_header_btn_item topPoints transition cameli">
                              <div class="i_h_in_live camera_chs">
                                 <div class="camList cam-list" id="camera-list"></div>
                                 <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('137'));?>
                              </div>
                            </div>
                            <div class="i_header_btn_item topPoints transition cameli">
                                <div class="i_h_in_live mick_chs">
                                    <div class="micList mic-list" id="camera-list"></div>
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('152'));?>
                                </div>
                            </div>
                            <div class="i_header_btn_item topPoints transition">
                                <div class="i_h_in_live camcloseCall">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('172'));?>
                                </div>
                            </div>
                          <?php }?>
                      </div>
                  </div>
               </div> 
               <div class="live__live_video_holder">
                    <div class="live_vide__holder">
                       <div class="filtvid flex_ player" id="<?php echo 'local-player';?>">
                       <?php if($userID != $liveCreator){ ?>
                       <div class="col cola nonePoint">
                            <p id="local-player-name" class="player-name nonePoint"></p>
                        </div>
                       <div class="w-100 nonePoint"></div>
                        <div class="col">
                            <div id="remote-playerlist"></div>
                        </div>
                        <?php }?>
                    </div>
                       <div class="live_holder_plus_in">
                         <div class="holder_l_in flex_ tabing">
                             <?php if($userID == $liveCreator){ ?>
                             <div class="button-group">
                                <button id="mute-audio" type="button" class="flex_ tabing"><?php echo iN_HelpSecure($LANG['mute_audio']);?></button>
                                <button id="mute-video" type="button" class="flex_ tabing"><?php echo iN_HelpSecure($LANG['mute_video']);?></button>
                             </div>
                             <?php }?>
                             <div class="live_pulse">LIVE</div> 
                             <div class="live_like_t">
                                <div class="like_live flex_ <?php echo iN_HelpSecure($likeClass);?>" id="p_l_l_<?php echo iN_HelpSecure($liveID);?>" data-id="<?php echo iN_HelpSecure($liveID);?>">
                                    <?php echo html_entity_decode($likeIcon);?>
                                </div>
                                <div class="lp_sum_l flex_ tabing" id="lp_sum_l_<?php echo iN_HelpSecure($liveID);?>"><?php echo iN_HelpSecure($likeSum);?></div>
                             </div> 
                             <?php if($userID != $liveCreator){ ?>
                             <div class="live_gift_call flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('145'));?></div>
                             <?php }?>
                         </div>
                       </div>
                    </div>
                    <div class="live_footer_holder">
                        <?php  if($p_friend_status != 'me'){?>
                        <?php include "liveCoinList.php";?>
                        <div class="live_coin_current_balance">
                            <div class="current_balance_box flex_ tabing_non_justify"><?php echo iN_HelpSecure($LANG['point_balance']);?> <span class="crnblnc"><?php echo number_format($userCurrentPoints);?></span> <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?><a href="<?php echo $base_url.'purchase/purchase_point';?>" target="blank_" class="transitions"><?php echo iN_HelpSecure($LANG["get_points"]);?></a></div>
                        </div>
                        <?php }?>
                        <div class="currentt_live_streamings_list_container tabing">
                            <?php include "sugLiveStreams.php";?>
                        </div>
                    </div>
               </div> 
           </div> 
           <div class="live_right_in_right relativePosition">
                <div class="live_right_in_right_in">
                     <?php include "liveChat.php";?>
                </div>
                <div class="live_send_message_box_wrapper">
                   <div class="nanos transition"></div>
                   <div class="tabing_non_justify flex_ optional_width">
                       <div class="message_form_items flex_ tabing">
                           <div class="message_send_text flex_ tabing">
                               <div class="message_text_textarea flex_">
                                   <textarea class="lmSize"></textarea> 
                                    <div class="message_smiley getMEmojisa">
                                        <div class="message_form_smiley_plus transition">
                                            <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('25')); ?></div>
                                        </div>
                                    </div> 
                               </div>
                           </div>
                           <div class="message_form_plus transition livesendmes">
                               <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?></div>
                           </div>
                       </div>
                   </div>
                </div>
           </div>
           <!---->
       </div>
    </div>
</div>
<?php if($userID != $liveCreator){$iN->iN_InsertMyOnlineStatus($userID, $liveID);}?>
<div id="mic-list"></div>
<!-- Safe global vars for JS -->
<script type="text/javascript">
    window.siteurl = "<?php echo iN_HelpSecure($base_url); ?>";
    window.liveAppID = "<?php echo iN_HelpSecure($agoraAppID); ?>";
    window.liveChannel = "<?php echo iN_HelpSecure($liveChannel); ?>";
    window.liveUserID = "<?php echo iN_HelpSecure($userID); ?>";
    window.liveCreator = "<?php echo iN_HelpSecure($liveCreator); ?>";
    window.theLiveID = "<?php echo iN_HelpSecure($theLiveID); ?>";
</script>
<!-- Agora & Handler -->
<script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
<script src="<?php echo $base_url; ?>themes/<?php echo $currentTheme; ?>/js/liveStreamingFullHandler.js?v=<?php echo $version; ?>"></script>
<?php } else { ?>
<div class="live_wrapper">
   <div class="i_liv_stream_video relativePosition backgroundBlack">
       <!---->
  <div class="i_modal_bg_in i_modal_display_in">
    <!--SHARE-->
   <div class="i_modal_in_in i_sf_box">
       <div class="i_modal_content">
          <div class="purchase_premium_header flex_ tabing border_top_radius"><?php echo iN_HelpSecure($LANG['join_the_live_broadcast']) ;?></div>
          <div class="purchase_post_details">

            <div class="wallet-debit-confirm-container flex_">
               <div class="owner_avatar" style="background-image:url(<?php echo iN_HelpSecure($liveCreatorAvatar, FILTER_VALIDATE_URL);?>);"></div>
               <div class="album-details"><?php echo iN_HelpSecure($LANG['paying_point_for_live_streaming']);?></div>
                <div class="album-wanted-point">
                  <div><?php echo html_entity_decode($liveCredit);?></div>
                  <span><?php echo iN_HelpSecure($LANG['points']);?></span>
                </div>
            </div>
          </div>
          <div class="i_modal_g_footer">
              <div class="alertBtnRight joinLiveStream transition" id="<?php echo iN_HelpSecure($liveID) ;?>"><?php echo iN_HelpSecure($LANG['confirm']) ;?></div>
              <div class="alertBtnLeft no-del transition"><?php echo iN_HelpSecure($LANG['cancel']) ;?></div>
          </div>
       </div>
   </div>
   <!--/SHARE-->
   </div>
   <!---->
    </div>
</div>
<?php }?>

