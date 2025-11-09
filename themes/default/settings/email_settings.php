<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <?php echo iN_HelpSecure($LANG['changing_email_address']);?>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['changing_email_adress_not']);?></div>
    </div>
     <form enctype="multipart/form-data" method="post" id="myEmailForm">
     <div class="i_settings_wrapper_items">
        <!---->
        <div class="i_settings_wrapper_item">
             <div class="i_settings_item_title"><?php echo iN_HelpSecure($LANG['your_new_email_address']);?></div>
             <div class="i_settings_item_title_for">
                <input type="text" name="newEmail" class="flnm" id="newEmail" value="<?php echo filter_var($userEmail, FILTER_SANITIZE_EMAIL);?>">
                <div class="box_not warning_inuse"><?php echo iN_HelpSecure($LANG['email_already_in_use_warning']);?></div>
                <div class="box_not warning_invalid"><?php echo iN_HelpSecure($LANG['invalid_email_address']);?></div>
                <div class="box_not warning_same_email"><?php echo iN_HelpSecure($LANG['warning_same_email']);?></div>
             </div>
         </div>
        <!---->
        <!---->
        <div class="i_settings_wrapper_item">
             <div class="i_settings_item_title"><input type="hidden" name="f" value="editMyEmail"><?php echo iN_HelpSecure($LANG['your_current_password']);?></div>
             <div class="i_settings_item_title_for">
                <input type="password" name="currentPass" id="cPass" class="flnm">
                <div class="box_not warning_wrong_password"><?php echo iN_HelpSecure($LANG['wrong_password']);?></div>
                <div class="box_not warning_required"><?php echo iN_HelpSecure($LANG['this_field_is_required']);?></div>
             </div>
         </div>
        <!---->
        <div class="i_settings_wrapper_item successNot">
          <?php echo iN_HelpSecure($LANG['email_changed_success']);?>
        </div>
     </div>
     <div class="i_become_creator_box_footer">
        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myemail"><?php echo iN_HelpSecure($LANG['save_edit']);?></button>
      </div>
    </form>
  </div>
</div>