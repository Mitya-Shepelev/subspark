<div class="i_modal_bg_in">
    <!--SHARE-->
   <div class="i_modal_in_in i_sf_box">
       <div class="i_modal_content">
           <!--User Cover & Profile-->
           <div class="i_f_cover_avatar" style="background-image:url('<?php echo iN_HelpSecure($f_profileCover);?>');">
               <div class="popClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
               <div class="i_f_avatar_container">
                  <div class="i_f_avatar" style="background-image:url('<?php echo iN_HelpSecure($f_profileAvatar);?>');"></div>
               </div>
           </div>
           <!--/User Cover & Profile-->
           <!--User Other Infos-->
            <div class="i_f_other" id="pr_u_id">
              <div class="i_u_name">
                   <a href="<?php echo iN_HelpSecure($fprofileUrl);?>"><?php echo iN_HelpSecure($f_userfullname);?><?php echo html_entity_decode($fVerifyStatus);?> <?php echo html_entity_decode($fGender);?></a>
              </div>
              <div class="i_u_name_mention">
                   <a href="<?php echo iN_HelpSecure($fprofileUrl);?>">@<?php echo iN_HelpSecure($f_username);?></a>
              </div>
              <div class="support_not"> <?php echo preg_replace( '/{.*?}/', $f_userfullname, $LANG['subscribeNot']); ?></div>
              <div class="i_s_popup_title_dark">
                 <?php echo iN_HelpSecure($LANG['avantages_of_subscription']);?>
              </div>
              <div class="i_advantages_wrapper">
                   <div class="avantage_box">
                   <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?> <?php echo iN_HelpSecure($LANG['unblock_all_fan_contents']);?>
                   </div>
                   <div class="avantage_box">
                      <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?> <?php echo iN_HelpSecure($LANG['full_acces_my_conent']);?>
                   </div>
                   <div class="avantage_box">
                      <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?> <?php echo iN_HelpSecure($LANG['direct_message_me']);?>
                   </div>
                   <div class="avantage_box">
                      <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?> <?php echo iN_HelpSecure($LANG['cancel_subs_any_time']);?>
                   </div>
              </div>
              <div class="i_s_popup_title_dark_offers">
                 <?php echo iN_HelpSecure($LANG['offers']);?>
              </div>
              <?php
                 $getUserOffers = $iN->iN_UserSusbscriptionOffers($f_userID);
                 if($getUserOffers){
                    foreach($getUserOffers as $uOfferData){
                       $planID = $uOfferData['plan_id'];
                       $planAmount = $uOfferData['amount'];
                       $planType = $uOfferData['plan_type'];
                        echo '
                        <div class="i_prices_subscribe">
                         <div class="subscribe_price_btn bcmSubsPoint" id="'.$planID.'" data-u="'.$f_userID.'">
                            '.$iN->iN_SelectedMenuIcon('51').preg_replace( '/{.*?}/', $planAmount, $LANG['subscribe_for_point']).' / '.$LANG[$planType].'</span>
                         </div>
                        </div>'
                       ;
                    }
                 }
              ?>
           </div>
           <!--/User Other Infos-->
       </div>
   </div>
   <!--/SHARE-->
</div>