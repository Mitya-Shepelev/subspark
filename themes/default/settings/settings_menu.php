<div class="settings_left_menu">
   <div class="settings_mobile_ope_menu">
      <div class="settings_mobile_menu_container transition flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('100')).$LANG['menu'];?></div>
   </div>
  <div class="i_settings_menu_wrapper">
     <div class="i_settings_title"><?php echo iN_HelpSecure($LANG['settings']);?></div>
     <div class="i_s_menus">
        <div class="i_s_menus_title"><?php echo iN_HelpSecure($LANG['menu_arrow_account_title']);?></div>
        <div class="i_s_menu_wrapper">
        <?php if($feesStatus == '2'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=dashboard">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'dashboard' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('35'));?> <?php echo iN_HelpSecure($LANG['dashboard']);?>
               </div>
            </a>
         <?php }?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_profile">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'my_profile' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('83'));?> <?php echo iN_HelpSecure($LANG['my_profile']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_followers">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'my_followers' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('140'));?> <?php echo iN_HelpSecure($LANG['my_followers']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=im_following">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'im_following' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('139'));?> <?php echo iN_HelpSecure($LANG['im_following']);?>
               </div>
            </a>
            <?php if($whoCanShareStory == 'no'){ ?>
               <?php if($feesStatus == '2'){?>
                  <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=stories">
                     <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'stories' ? "active_p" : ""; ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('154'));?> <?php echo iN_HelpSecure($LANG['my_stories']);?>
                     </div>
                  </a>
               <?php } ?>
            <?php }else if($whoCanShareStory == 'yes'){?>
               <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=stories">
                  <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'stories' ? "active_p" : ""; ?>">
                     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('154'));?> <?php echo iN_HelpSecure($LANG['my_stories']);?>
                  </div>
               </a>
            <?php } ?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myframes">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'myframes' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('180'));?> <?php echo iN_HelpSecure($LANG['my_frames']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=purchased_points">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'purchased_points' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?> <?php echo iN_HelpSecure($LANG['purchased_points']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=qrCode">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'qrCode' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('146'));?> <?php echo iN_HelpSecure($LANG['qrCodeGenerator']);?>
               </div>
            </a>
            <?php if($affilateSystemStatus == 'yes'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=affiliate">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'affiliate' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('148'));?> <?php echo iN_HelpSecure($LANG['my_affilate']);?>
               </div>
            </a>
            <?php }?>
            <?php if($earnPointSystemStatus == 'yes'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=earned_points">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'earned_points' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('151'));?> <?php echo iN_HelpSecure($LANG['earned_points']);?>
               </div>
            </a>
            <?php }?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscriptions">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'subscriptions' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('43'));?> <?php echo iN_HelpSecure($LANG['subscriptions']);?>
               </div>
            </a>
         </div>
         <div class="i_s_menus_title"><?php echo iN_HelpSecure($LANG['privacy']);?></div>
         <div class="i_s_menu_wrapper">
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=password">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'password' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('6'));?> <?php echo iN_HelpSecure($LANG['password']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=preferences">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'preferences' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('105'));?> <?php echo iN_HelpSecure($LANG['preferences']);?>
               </div>
            </a>
            <?php if($userCanBlockCountryStatus == 'yes'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=blocked">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'blocked' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('64'));?> <?php echo iN_HelpSecure($LANG['blocked']);?>
               </div>
            </a>
            <?php }?>
         </div>
         <?php if($iN->iN_ShopData($userID, 1) == 'yes'){?>
            <?php if($feesStatus == '2' && $iN->iN_ShopData($userID, '8') == 'yes'){?>
            <div class="i_s_menus_title"><?php echo iN_HelpSecure($LANG['shop']);?></div>
            <div class="i_s_menu_wrapper">
               <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=createaProduct">
                  <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'createaProduct' ? "active_p" : ""; ?>">
                     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('159'));?> <?php echo iN_HelpSecure($LANG['createaProduct']);?>
                  </div>
               </a>
               <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts">
                  <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'myProducts' ? "active_p" : ""; ?>">
                     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('158'));?> <?php echo iN_HelpSecure($LANG['myProducts']);?>
                  </div>
               </a>
               <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=mySales">
                  <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'mySales' ? "active_p" : ""; ?>">
                     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('155'));?> <?php echo iN_HelpSecure($LANG['mySales']);?>
                  </div>
               </a>
               <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myPurchasedProducts">
                  <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'myPurchasedProducts' ? "active_p" : ""; ?>">
                     <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('156'));?> <?php echo iN_HelpSecure($LANG['myPurchasedProducts']);?>
                  </div>
               </a>
            </div>
            <?php }else if($iN->iN_ShopData($userID, '8') == 'no'){?>
               <div class="i_s_menus_title"><?php echo iN_HelpSecure($LANG['shop']);?></div>
               <div class="i_s_menu_wrapper">
                  <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=createaProduct">
                     <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'createaProduct' ? "active_p" : ""; ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('159'));?> <?php echo iN_HelpSecure($LANG['createaProduct']);?>
                     </div>
                  </a>
                  <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myProducts">
                     <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'myProducts' ? "active_p" : ""; ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('158'));?> <?php echo iN_HelpSecure($LANG['myProducts']);?>
                     </div>
                  </a>
                  <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=mySales">
                     <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'mySales' ? "active_p" : ""; ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('155'));?> <?php echo iN_HelpSecure($LANG['mySales']);?>
                     </div>
                  </a>
                  <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=myPurchasedProducts">
                     <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'myPurchasedProducts' ? "active_p" : ""; ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('156'));?> <?php echo iN_HelpSecure($LANG['myPurchasedProducts']);?>
                     </div>
                  </a>
               </div>
            <?php }?>
         <?php }?>
         <div class="i_s_menus_title"><?php echo iN_HelpSecure($LANG['payments']);?></div>
         <div class="i_s_menu_wrapper">
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'my_payments' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?> <?php echo iN_HelpSecure($LANG['my_payments']);?>
               </div>
            </a>
           <?php if($feesStatus == '2'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_methods">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'payout_methods' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('77'));?> <?php echo iN_HelpSecure($LANG['payout_methods']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'payments' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?> <?php echo iN_HelpSecure($LANG['payments']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payout_history">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'payout_history' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('85'));?> <?php echo iN_HelpSecure($LANG['payout_history']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=withdrawal">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'withdrawal' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('86'));?> <?php echo iN_HelpSecure($LANG['withdrawal']);?>
               </div>
            </a>
            <?php } ?>
        </div>
        <div class="i_s_menus_title"><?php echo iN_HelpSecure($LANG['premium_zone']);?></div>
        <div class="i_s_menu_wrapper">
        <?php if($feesStatus == '2'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscription_payments">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'subscription_payments' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?> <?php echo iN_HelpSecure($LANG['subscription_payments']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=fees">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'fees' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('76'));?> <?php echo iN_HelpSecure($LANG['fees']);?>
               </div>
            </a>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscribers">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'subscribers' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51'));?> <?php echo iN_HelpSecure($LANG['subscribers']);?>
               </div>
            </a>
            <?php if($videoCallFeatureStatus == 'yes'){?>
               <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=videoCallSet">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'videoCallSet' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52'));?> <?php echo iN_HelpSecure($LANG['videoCallSet']);?>
               </div>
            </a>
            <?php }?>
            <?php if($userCanBlockCountryStatus == 'yes'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=block_country">
               <div class="i_s_menu_box transition <?php echo iN_HelpSecure($pageGet) == 'block_country' ? "active_p" : ""; ?>">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('138'));?> <?php echo iN_HelpSecure($LANG['block_country']);?>
               </div>
            </a>
            <?php } ?>
         <?php }else{ ?>
            <?php if($beaCreatorStatus == 'request'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>creator/becomeCreator">
               <div class="i_s_menu_box transition become_a_creator active_p">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9'));?> <?php echo iN_HelpSecure($LANG['become_creator']);?>
               </div>
            </a>
            <?php }?>
         <?php } ?>
        </div>
     </div>
  </div>
</div>

