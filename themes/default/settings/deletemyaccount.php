<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in inTable">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28'));?><?php echo iN_HelpSecure($LANG['deletemyaccount']);?></div>
       <div class="i_moda_header_nt"><?php echo html_entity_decode($LANG['delete_account_not']);?></div>
    </div>
    <form enctype="multipart/form-data" method="post" id="deleteMyAccount">
    <div class="i_settings_wrapper_items">
         <div class="payouts_form_container">
            <!---->
            <div class="i_settings_wrapper_item">
                <div class="i_settings_item_title"><?php echo iN_HelpSecure($LANG['cur_password']);?></div>
                <div class="i_settings_item_title_for">
                    <input type="password" name="crn_password" class="flnm" id="crn_password">
                </div>
            </div>
            <input type="hidden" name="deleteMe" value="<?php echo iN_HelpSecure($userID);?>">
            <!---->
         </div>
    </div>
    <div class="i_settings_wrapper_item warning_not_mach">
       <?php echo iN_HelpSecure($LANG['new_pass_not_match']);?>
    </div>
    <div class="i_settings_wrapper_item warning_write_current_password">
       <?php echo iN_HelpSecure($LANG['please_write_crnt_pass']);?>
    </div>
    <div class="i_settings_wrapper_item warning_not_correct">
       <?php echo iN_HelpSecure($LANG['warning_current_password_not_correct']);?>
    </div>
    <div class="i_settings_wrapper_item successNot">
          <?php echo iN_HelpSecure($LANG['profile_updated_success']);?>
    </div>
    <div class="i_settings_wrapper_item no_new_password_wrote">
          <?php echo iN_HelpSecure($LANG['no_new_password_wrote']);?>
    </div>
    <div class="i_settings_wrapper_item minimum_character_not">
          <?php echo iN_HelpSecure($LANG['minimum_character_not']);?>
    </div>
    <div class="i_settings_wrapper_item not_contain_whitespace">
          <?php echo iN_HelpSecure($LANG['not_contain_whitespace']);?>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="delete_myaccount"><?php echo iN_HelpSecure($LANG['start_deletion']);?></button>
     </div>
    </form>
  </div>
</div>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/deleteMyAccountHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
