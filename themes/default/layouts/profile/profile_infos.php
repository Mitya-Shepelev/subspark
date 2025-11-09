<div class="i_profile_container">
   <div class="i_profile_cover_blur" data-background="<?php echo iN_HelpSecure($p_profileCover); ?>" role="img" aria-label="Profile cover image"></div>
   <div class="i_profile_i_container">
       <div class="i_profile_infos_wrapper">
          <!--PROFILE COVER AND AVATAR-->
          <div class="i_profile_cover">
              <div class="i_im_cover">
                 <img src="<?php echo iN_HelpSecure($p_profileCover); ?>">
              </div>
              <div class="i_profile_avatar_container">
                  <div class="i_profile_avatar_wrp">
                    <?php if($p_frame){ ?>
                        <div class="i_profile_gift_frame flex_ tabing"><img src="<?php echo $base_url.$p_frame;?>"></div>
                    <?php } ?>
                    <div class="i_profile_avatar" data-avatar="<?php echo iN_HelpSecure($p_profileAvatar); ?>" role="img" aria-label="Profile avatar image">
                    <?php echo html_entity_decode($p_is_creator); ?>
                        <?php if($p_profileID == $userID){ ?>
                        <div class="frame_badge"><a class="flex_ tabing" href="<?php echo iN_HelpSecure($base_url).'settings?tab=myframes'?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('180'));?></a></div>
                    <?php } ?>
                    </div>
                  </div>
              </div>
          </div>
          <!--/PROFILE COVER AND AVATAR-->
          <!--USER PROFILE INFO-->
          <div class="i_u_profile_info">
               <div class="i_u_name">
                   <?php echo iN_HelpSecure($p_userfullname); ?><?php echo html_entity_decode($pVerifyStatus); ?> <?php echo html_entity_decode($pGender); ?>
               </div>
               <?php echo html_entity_decode($pTime); ?>
               <div class="i_p_cards">
                  <?php echo html_entity_decode($pCategory); ?>
               </div>
               <?php if ($p_friend_status != 'me') {?>
               <div class="i_p_cards">
                  <?php echo html_entity_decode($sendMessage); ?>
                  <div class="i_btn_item transition copyUrl tabing ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['copy_profile_url']);?>" data-clipboard-text="<?php echo iN_HelpSecure($profileUrl); ?>">
                     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('30')); ?>
                  </div>
                  <div class="i_btn_item <?php echo iN_HelpSecure($blockBtn); ?> transition tabing ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['block_this_user']);?>" data-u="<?php echo iN_HelpSecure($p_profileID); ?>">
                     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('64')); ?>
                  </div>
                  <?php if ($p_friend_status != 'subscriber') {?>
                    <div class="i_fw<?php echo iN_HelpSecure($p_profileID); ?> transition <?php echo iN_HelpSecure($flwrBtn); ?>" id="i_btn_like_item" data-u="<?php echo iN_HelpSecure($p_profileID); ?>">
                      <?php echo html_entity_decode($flwBtnIconText); ?>
                    </div>
                  <?php }?>
               </div>
               <?php if($pCertificationStatus == '2' && $pValidationStatus == '2' && $feesStatus == '2'){ ?>
               <div class="i_p_items_box">
                    <?php if ($p_friend_status != 'subscriber') {?>
                        <div class="i_btn_become_fun <?php echo iN_HelpSecure($subscBTN); ?> transition" data-u="<?php echo iN_HelpSecure($p_profileID); ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')) . $LANG['become_a_subscriber']; ?>
                        </div>
                    <?php } else { if($p_subscription_type == 'point'){?>
                        <div class="i_btn_unsubscribe transition unSubUP" data-u="<?php echo iN_HelpSecure($p_profileID); ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')) . $LANG['unsubscribe']; ?>
                        </div>
                    <?php }else{?>
                        <div class="i_btn_unsubscribe transition unSubU" data-u="<?php echo iN_HelpSecure($p_profileID); ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')) . $LANG['unsubscribe']; ?>
                        </div>
                    <?php }}?>
                    <div class="i_btn_send_to_point transition sendPoint tabing flex_" data-u="<?php echo iN_HelpSecure($p_profileID); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('145')) . $LANG['offer_a_tip']; ?>
                    </div>
                    <div class="i_btn_send_to_frame transition sendFrame tabing flex_" data-u="<?php echo iN_HelpSecure($p_profileID); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('180')) . $LANG['gift_a_frame']; ?>
                    </div>
               </div>
               <?php }?>
               <?php }?>
                   <?php
                    $sociallinks = $iN->iN_ShowUserSocialSites($p_profileID);
                   if($sociallinks){
                        echo '<div class="i_profile_menu"><div class="i_profile_menu_middle flex_ tabing">';
                        foreach($sociallinks as $sDa){
                            $sLink = $sDa['s_link'] ?? NULL;
                            $sIcon = $sDa['social_icon'] ?? NULL;
                            echo '<div class="s_m_link flex_ tabing"><a class="flex_ tabing" href="'.iN_HelpSecure($sLink).'">'.$sIcon.'</a></div>';
                        }
                        echo '</div></div>';
                   }?>
                <?php
                $check = $iN->iN_CheckUnsubscribeStatus($userID, $p_profileID);
                if($unSubscribeStyle == 'yes' && $check && $userID != $p_profileID){ $finishTime = date('Y-m-d', strtotime($check)); ?>
                     <div class="i_p_item_box">
                       <div class="sub_finish_time"><?php echo preg_replace( '/{.*?}/', $finishTime, $LANG['subs_finish_at']); ?></div>
                     </div>
                <?php } ?>
                <?php if ($p_profileBio) {?>
               <div class="i_p_item_box">
                   <div class="i_p_bio"><?php echo html_entity_decode($p_profileBio); ?></div>
               </div>
               <?php }?>
               <div class="i_p_item_box flex_ tabing">
                   <div class="i_p_ffs flex_ tabing <?php echo iN_HelpSecure($pCat) == 'following' ? "active_page_menu" : ""; ?>">
                       <a class="flex_ tabing" href="<?php echo iN_HelpSecure($base_url.$p_username.'?pcat=following');?>">
                           <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('139'));?>
                           <?php echo iN_HelpSecure($LANG['im_following']);?> (<?php echo (int)$totalFollowingUsers; ?>)
                       </a>
                   </div>
                   <div class="i_p_ffs flex_ tabing <?php echo iN_HelpSecure($pCat) == 'followers' ? "active_page_menu" : ""; ?>">
                       <a class="flex_ tabing" href="<?php echo iN_HelpSecure($base_url.$p_username.'?pcat=followers');?>">
                           <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('140'));?>
                           <?php echo iN_HelpSecure($LANG['my_followers']);?> (<?php echo (int)$totalFollowerUsers; ?>)
                       </a>
                   </div>
                   <?php if($pCertificationStatus == '2' && $pValidationStatus == '2' && $feesStatus == '2'){ ?>
                   <div class="i_p_ffs i_p_ffs_plus flex_ tabing <?php echo iN_HelpSecure($pCat) == 'subscribers' ? "active_page_menu" : ""; ?>">
                       <a class="flex_ tabing" href="<?php echo iN_HelpSecure($base_url.$p_username.'?pcat=subscribers');?>">
                           <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('43'));?>
                           <?php echo iN_HelpSecure($LANG['subscribers']);?> (<?php echo (int)$totalSubscribers; ?>)
                       </a>
                   </div>
                   <?php }?>
               </div>
               <div class="i_profile_menu">
                    <div class="i_profile_menu_middle flex_ tabing">
                        <!---->
                        <div class="i_profile_menu_item <?php if(!isset($pCat)){echo 'active_page_menu';}?>">
                            <a href="<?php echo iN_HelpSecure($profileUrl);?>">
                            <div class="i_p_sum"><?php echo iN_HelpSecure($totalPost); ?></div>
                            <div class="i_profile_menu_item_con flex_ tabing">
                                <div class="i_profile_menu_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('67')); ?></div>
                                <div class="i_profile_menu_item_name flex_ tabing"><?php echo iN_HelpSecure($LANG['profile_posts']);?></div>
                            </div>
                            </a>
                        </div>
                        <!---->
                        <!---->
                        <div class="i_profile_menu_item <?php echo iN_HelpSecure($pCat) == 'photos' ? "active_page_menu" : ""; ?>">
                            <a href="<?php echo iN_HelpSecure($base_url.$p_username.'?pcat=photos');?>">
                            <div class="i_p_sum"><?php echo iN_HelpSecure($totalImagePost); ?></div>
                            <div class="i_profile_menu_item_con flex_ tabing">
                                <div class="i_profile_menu_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('68')); ?></div>
                                <div class="i_profile_menu_item_name flex_ tabing"><?php echo iN_HelpSecure($LANG['profile_post_images']);?></div>
                            </div>
                            </a>
                        </div>
                        <!---->
                        <!---->
                        <div class="i_profile_menu_item <?php echo iN_HelpSecure($pCat) == 'videos' ? "active_page_menu" : ""; ?>">
                            <a href="<?php echo iN_HelpSecure($base_url.$p_username.'?pcat=videos');?>">
                            <div class="i_p_sum"><?php echo iN_HelpSecure($totalVideoPosts); ?></div>
                            <div class="i_profile_menu_item_con flex_ tabing">
                                <div class="i_profile_menu_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52')); ?></div>
                                <div class="i_profile_menu_item_name flex_ tabing"><?php echo iN_HelpSecure($LANG['profile_videos']);?></div>
                            </div>
                            </a>
                        </div>
                        <!---->
                        <!---->
                        <div class="i_profile_menu_item <?php echo iN_HelpSecure($pCat) == 'reels' ? "active_page_menu" : ""; ?>">
                            <a href="<?php echo iN_HelpSecure($base_url.$p_username.'?pcat=reels');?>">
                            <div class="i_p_sum"><?php echo iN_HelpSecure($totalReelsPosts); ?></div>
                            <div class="i_profile_menu_item_con flex_ tabing">
                                <div class="i_profile_menu_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('187')); ?></div>
                                <div class="i_profile_menu_item_name flex_ tabing"><?php echo iN_HelpSecure($LANG['reels']);?></div>
                            </div>
                            </a>
                        </div>
                        <!---->
                        <!---->
                        <div class="i_profile_menu_item <?php echo iN_HelpSecure($pCat) == 'audios' ? "active_page_menu" : ""; ?>">
                            <a href="<?php echo iN_HelpSecure($base_url.$p_username.'?pcat=audios');?>">
                            <div class="i_p_sum"><?php echo iN_HelpSecure($totalAudioPosts);?></div>
                            <div class="i_profile_menu_item_con flex_ tabing">
                                <div class="i_profile_menu_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('152')); ?></div>
                                <div class="i_profile_menu_item_name flex_ tabing"><?php echo iN_HelpSecure($LANG['profile_audios']);?></div>
                            </div>
                            </a>
                        </div>
                        <!---->
                        <?php if($iN->iN_ShopStatus(1) == 'yes'){?>
                        <!---->
                        <div class="i_profile_menu_item <?php echo iN_HelpSecure($pCat) == 'products' ? "active_page_menu" : ""; ?>">
                            <a href="<?php echo iN_HelpSecure($base_url.$p_username.'?pcat=products');?>">
                            <div class="i_p_sum"><?php echo iN_HelpSecure($totalProducts);?></div>
                            <div class="i_profile_menu_item_con flex_ tabing">
                                <div class="i_profile_menu_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('158')); ?></div>
                                <div class="i_profile_menu_item_name flex_ tabing"><?php echo iN_HelpSecure($LANG['profile_products']);?></div>
                            </div>
                            </a>
                        </div>
                        <!---->
                        <?php }?>
                    </div>
                </div>
          </div>
          <!--/USER PROFILE INFO-->
       </div>
   </div>
</div>
