<div class="contact_us_form_container tabing_non_justify relativePosition" id="general_conf">
<div class="i_login_box_header">
    <div class="i_login_box_wellcome_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('96'));?></div>
    <div class="i_welcome_back">
        <div class="i_lBack"><?php echo iN_HelpSecure($LANG['any_questions']);?></div>
        <div class="i_lnot"><?php echo iN_HelpSecure($LANG['do_not_hesitate']);?></div>
    </div>
</div>
<!--Direct Login-->
<div class="i_direct_login idrec text_align_left">
  <div class="sended contact_sended"><?php echo iN_HelpSecure($LANG['your_message_sended_success']);?></div>
  <div class="contact_disabled"><?php echo iN_HelpSecure($LANG['please_try_again_later_contact']);?></div>
  <div class="con_warning"><?php echo iN_HelpSecure($LANG['alread_sended_contact_email']);?></div>
  <div class="con_warning_rec"><?php echo iN_HelpSecure($LANG['prove_you_are_not_robot']);?></div>
  <div id="con_for">
    <form enctype="multipart/form-data" method="post" id='newContact' autocomplete="off">
        <div class="form_group">
            <label for="email_fullname" class="form_label"><?php echo iN_HelpSecure($LANG['full_name']);?></label>
            <div class="form-control">
                <input type="text" name="email_fullname" id="email_fullname" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['full_name']);?>">
            </div>
        </div>
        <div class="form_group">
            <label for="contact_email" class="form_label"><?php echo iN_HelpSecure($LANG['email-address']);?></label>
            <div class="form-control">
                <input type="email" name="contact_email" id="contact_email" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['your_email_address']);?>">
            </div>
        </div>
        <div class="form_group">
            <label for="content" class="form_label"><?php echo iN_HelpSecure($LANG['msg']);?></label>
            <div class="form-control">
                <textarea name="content" id="content" class="description_ description_style" placeholder="<?php echo iN_HelpSecure($LANG['your_message']);?>"></textarea>
            </div>
        </div>
        <?php if($captchaStatus == 'yes'){?>
        <div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key;?>"></div>
        <?php }?>
        <div class="form_group">
        <input type="hidden" name="f" value="newContact">
            <div class="i_login_button"><button type="submit"><?php echo iN_HelpSecure($LANG['send']);?></button></div>
        </div>
    </form>
  </div>
</div>
<!--/Direct Login-->
</div>
<?php if($captchaStatus == 'yes'){?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php }?>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/contactusformHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
