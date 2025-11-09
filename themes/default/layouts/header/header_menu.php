<div class="i_general_box_container generalBox">
<div class="btest">
  <div class="i_user_details">
      <div class="i_u_details transition"> 
        <a href="<?php echo iN_HelpSecure($base_url).$userName;?>">
        <div class="i_user_profile_avatar">
            <div class="iu_avatar"><img src="<?php echo iN_HelpSecure($userAvatar);?>" alt="<?php echo iN_HelpSecure($userFullName);?>"></div>
        </div>
        <div class="i_user_nm">
            <div class="i_unm"><?php echo iN_HelpSecure($userFullName);?></div>
            <div class="i_see_prof"><?php echo iN_HelpSecure($LANG['look-at-your-profile'])?></div>
        </div>
        </a>
      </div>
      <div class="arrow"></div>
      <div class="i_header_others_box">
           <?php if($userType == '2' || $userType == '3'){?>
            <a href="<?php echo iN_HelpSecure($base_url);?>admin/index">
               <div class="i_header_others_item transition">
                  <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('107'));?></div> <?php echo iN_HelpSecure($LANG['admin_panel']);?>
               </div>
            </a>
            <div class="arrow"></div>
           <?php }?>
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div> <?php echo iN_HelpSecure($LANG['y_point_balance']); echo addCommasNoDot($userCurrentPoints);?>
            </div>
           <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('77'));?></div> <?php echo iN_HelpSecure($LANG['y_earnings_balance']);?><?php echo iN_HelpSecure($currencys[$defaultCurrency].addCommasAndDots($userWallet));?>
            </div>
           </a>
           <div class="arrow"></div>
           <?php if($feesStatus == '2'){?>
           <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=dashboard">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('41'));?></div> <?php echo iN_HelpSecure($LANG['dashboard']);?>
            </div>
           </a>
           <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=payments">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?></div> <?php echo iN_HelpSecure($LANG['payments']);?>
            </div>
           </a>
           <?php } ?>
           <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=my_payments">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('42'));?></div> <?php echo iN_HelpSecure($LANG['my_payments']);?>
            </div>
           </a>
           <?php if($feesStatus == '2'){?>
           <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscribers">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51'));?></div> <?php echo iN_HelpSecure($LANG['subscribers']);?>
            </div>
           </a>
           <a href="<?php echo iN_HelpSecure($base_url);?>settings?tab=subscriptions">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('43'));?></div> <?php echo iN_HelpSecure($LANG['subscriptions']);?>
            </div>
           </a>
           <?php } ?>
           <a href="<?php echo iN_HelpSecure($base_url);?>saved">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('22'));?></div> <?php echo iN_HelpSecure($LANG['saved']);?>
            </div>
           </a>
           <div class="arrow"></div>
            <div class="i_header_others_item transition chooseLanguage" id="chooseLanguage">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('1'));?></div> <?php echo iN_HelpSecure($LANG['languages']);?>
            </div>
           <a href="<?php echo iN_HelpSecure($base_url);?>settings">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('44'));?></div> <?php echo iN_HelpSecure($LANG['settings']);?>
            </div>
           </a>
            <div class="i_header_others_item updateTheme transition" data-id="<?php if($lightDark == 'dark'){echo 'light';}else{echo 'dark';}?>">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('46'));?></div> <?php echo iN_HelpSecure($LANG['day-night']);?>
            </div>
           <a href="<?php echo iN_HelpSecure($base_url);?>logout.php">
            <div class="i_header_others_item transition">
               <div class="i_header_item_icon_box"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('45'));?></div> <?php echo iN_HelpSecure($LANG['logout']);?>
            </div>
           </a>
      </div>
      <?php if($conditionStatus == '0' && $beaCreatorStatus == 'request'){?>
         <div class="arrow"></div>
         <a href="<?php echo iN_HelpSecure($base_url);?>creator/becomeCreator">
            <div class="i_s_menu_box transition become_a_creator active_p">
               <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9'));?> <?php echo iN_HelpSecure($LANG['become_creator']);?>
            </div>
         </a>
      <?php }?>

  </div>
    <div class="footer_container">
    <?php
        // Securely resolve and include the footer file
        $footerFilePath = realpath(__DIR__ . '/../themes/' . basename($currentTheme) . '/layouts/footer.php');
        if ($footerFilePath && file_exists($footerFilePath)) {
            include $footerFilePath;
        }
        
        // Securely resolve and include the footer social links file
        $footerSocialLinksPath = realpath(__DIR__ . '/../themes/' . basename($currentTheme) . '/layouts/footerSocialLinks.php');
        if ($footerSocialLinksPath && file_exists($footerSocialLinksPath)) {
            echo '<div class="footer_social_links_container flex_">';
            include $footerSocialLinksPath;
            echo '</div>';
        }
    ?>
    </div>
</div>
</div>