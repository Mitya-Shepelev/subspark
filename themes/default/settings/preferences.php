<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('105'));?><?php echo iN_HelpSecure($LANG['preferences']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
    <div class="payouts_form_container">
    <div class="i_payout_methods_form_container">
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box email_not">
        <div class="i_sub_not i_preference">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('106'));?> <?php echo iN_HelpSecure($LANG['email_notification_settings']);?>
        </div>
        <div class="i_sub_not_check i_pref">
        <?php echo iN_HelpSecure($LANG['allow_email_notifications']);?>
           <div class="i_sub_not_check_box">
                <label class="el-switch el-switch-yellow" for="email_not">
                    <input type="checkbox" name="email_not" id="email_not" class="setChange" <?php echo iN_HelpSecure($notificationEmailStatus) == '1' ? 'checked="checked"' : '';?> value="<?php echo iN_HelpSecure($notificationEmailStatus) == '1' ? '0' : '1';?>">
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="box_not"><?php echo iN_HelpSecure($LANG['allow_email_notification_not']);?></div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box pref_top message_not">
        <div class="i_sub_not i_preference">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('38'));?> <?php echo iN_HelpSecure($LANG['message_notification_settings']);?>
        </div>
        <div class="i_sub_not_check i_pref">
        <?php echo iN_HelpSecure($LANG['allow_message']);?>
           <div class="i_sub_not_check_box">
                <label class="el-switch el-switch-yellow" for="message_not">
                    <input type="checkbox" name="message_not" id="message_not" class="setChange" <?php echo iN_HelpSecure($messageSendStatus) == '1' ? 'checked="checked"' : '';?> value="<?php echo iN_HelpSecure($messageSendStatus) == '1' ? '0' : '1';?>">
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="box_not"><?php echo iN_HelpSecure($LANG['allow_message_not']);?></div>
        <!---->
        <div class="i_sub_not_check i_pref">
        <?php echo iN_HelpSecure($LANG['only_subscriber_can_send_message']);?>
           <div class="i_sub_not_check_box">
                <label class="el-switch el-switch-yellow" for="who_send_message_not">
                    <input type="checkbox" name="who_send_message_not" id="who_send_message_not" class="setChange" <?php echo iN_HelpSecure($whoCanSendYouMessage) == '1' ? 'checked="checked"' : '';?> value="<?php echo iN_HelpSecure($whoCanSendYouMessage) == '1' ? '0' : '1';?>">
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="box_not"><?php echo iN_HelpSecure($LANG['only_subscriber_can_send_message_info']);?></div>
        <!---->
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box pref_top show_hide_profile">
        <div class="i_sub_not i_preference">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('10'));?> <?php echo iN_HelpSecure($LANG['profile_display_settings']);?>
        </div>
        <div class="i_sub_not_check i_pref">
        <?php echo iN_HelpSecure($LANG['profile_display_settings_not']);?>
           <div class="i_sub_not_check_box">
                <label class="el-switch el-switch-yellow" for="show_hide_profile">
                    <input type="checkbox" name="show_hide_profile" id="show_hide_profile" class="setChange" <?php echo iN_HelpSecure($showHidePostOnlineOffline) == '1' ? 'checked="checked"' : '';?> value="<?php echo iN_HelpSecure($showHidePostOnlineOffline) == '1' ? '0' : '1';?>">
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="box_not"><?php echo iN_HelpSecure($LANG['profile_display_settings_not_two']);?></div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
   </div>
</div>
    </div>
    <div class="i_settings_wrapper_item successNot">
        <?php echo iN_HelpSecure($LANG['payment_settings_updated_success'])?>
    </div>
  </div>
</div>