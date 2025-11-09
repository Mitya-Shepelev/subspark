<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in inTable">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('148'));?><?php echo iN_HelpSecure($LANG['my_affilate']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
    <div class="payouts_form_container">
    <div class="i_payout_methods_form_container">
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box email_not createQrBox">
        <div class="i_sub_not i_preference">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('148'));?> <?php echo iN_HelpSecure($LANG['my_affilate']);?>
        </div>
        <div class="i_sub_not_check i_pref">
             <div class="i_wrapper_cnt flex_ tabing">
                 <div class="ia_affiliate_wrapper flex_ tabing"></div>
                 <div class="iu_affilate_link">
                    <div class="flex_ tabing your_balance"><?php echo iN_HelpSecure($LANG['your_af_balance']);?></div>
                     <div class="flex_ tabing affilate_earnings"><span class="af_total"><?php echo isset($userData['affilate_earnings']) ? $userData['affilate_earnings'] : NULL;?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div>
                    <div class="flex_ tabing affilate_not"><?php echo iN_HelpSecure($LANG['affilate_not']);?></div>
                     <input type="text" id="link" class="i_affilate_input" value="<?php echo $base_url.'register?ref='.$userName;?>" autocomplete="off" onclick="this.select();" readonly="">
                     <div class="iu_affilate_link">
                   <div class="move_my_point"><?php echo iN_HelpSecure($LANG['move_earnings_to_point_balance']);?></div>
                 </div>
                    </div>
             </div>
        </div>
         <?php $shareUrl = $base_url.'register?ref='.base64_encode($userName);?>
            <div class="qrCodeShareButtons flex_ tabing">
              <div class="qrSocialIcon flex_ tabing share_to">
                <?php echo iN_HelpSecure($LANG['share_to']); ?>
              </div>
            
              <div class="qrSocialIcon flex_ tabing share-btn"
                   data-social="facebook"
                   data-url="<?php echo iN_HelpSecure($shareUrl); ?>"
                   data-id="<?php echo iN_HelpSecure($userID); ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('90')); ?>
              </div>
            
              <div class="qrSocialIcon flex_ tabing share-btn"
                   data-social="twitter"
                   data-url="<?php echo iN_HelpSecure($shareUrl); ?>"
                   data-id="<?php echo iN_HelpSecure($userID); ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('34')); ?>
              </div>
            
              <div class="qrSocialIcon flex_ tabing share-btn"
                   data-social="linkedin"
                   data-url="<?php echo iN_HelpSecure($shareUrl); ?>"
                   data-id="<?php echo iN_HelpSecure($userID); ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('89')); ?>
              </div>
            
              <div class="qrSocialIcon flex_ tabing share-btn"
                   data-social="whatsapp"
                   data-url="<?php echo iN_HelpSecure($shareUrl); ?>"
                   data-id="<?php echo iN_HelpSecure($userID); ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('147')); ?>
              </div>
            </div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
    <!-- Used to pass secure dynamic PHP variables to JS -->
    <script>
      window.affiliateConfig = {
        earnings: "<?php echo isset($userData['affilate_earnings']) ? $userData['affilate_earnings'] : 0; ?>", 
        userID: "<?php echo iN_HelpSecure($userID); ?>"
      };
    </script>
      </div>
    </div>
    </div>
  </div>
</div>