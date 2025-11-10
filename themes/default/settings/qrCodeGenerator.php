<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('146'));?><?php echo iN_HelpSecure($LANG['qrCodeGenerator']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
    <div class="payouts_form_container">
    <div class="i_payout_methods_form_container">
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box email_not createQrBox">
        <div class="i_sub_not i_preference">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('146'));?> <?php echo iN_HelpSecure($LANG['create_qr_code']);?>
        </div>
        <div class="i_sub_not_check i_pref">
           <div class="qrCodeImage" id="message<?php echo $userID;?>">
              <?php if($userQrCode){?>
                <img src="<?php echo $base_url.$userQrCode;?>">
              <?php } ?>
           </div>
        </div>
        <?php if($userQrCode){ $shareUrl = $base_url.'sharer?page='.base64_encode($userName)."&qr=1";?>
            <div class="qrCodeShareButtons flex_ tabing_non_justify">
              <div class="qrSocialIcon flex_ tabing share-btn"
                   data-social="vk"
                   data-url="<?php echo iN_HelpSecure($shareUrl); ?>"
                   data-id="<?php echo iN_HelpSecure($userID); ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('189')); ?>
              </div>

              <div class="qrSocialIcon flex_ tabing share-btn"
                   data-social="telegram"
                   data-url="<?php echo iN_HelpSecure($shareUrl); ?>"
                   data-id="<?php echo iN_HelpSecure($userID); ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('190')); ?>
              </div>

              <div class="qrSocialIcon flex_ tabing share-btn"
                   data-social="odnoklassniki"
                   data-url="<?php echo iN_HelpSecure($shareUrl); ?>"
                   data-id="<?php echo iN_HelpSecure($userID); ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('191')); ?>
              </div>

              <div class="qrSocialIcon flex_ tabing share-btn"
                   data-social="whatsapp"
                   data-url="<?php echo iN_HelpSecure($shareUrl); ?>"
                   data-id="<?php echo iN_HelpSecure($userID); ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('147')); ?>
              </div>
            </div>
        <?php }?>
        <div class="qrCodeGenerator flex_ tabing border-right-radius"><?php echo iN_HelpSecure($LANG['create_qrcode']);?></div>
        <div class="box_not"><?php echo iN_HelpSecure($LANG['create_qr_code_not']);?></div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->

   </div>
</div>
    </div>
  </div>
</div>