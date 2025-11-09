<div class="i_contents_container">
  <div class="i_general_white_board border_one column flex_ tabing__justify">
    <div class="i_general_title_box">
      <?php echo iN_HelpSecure($LANG['bankpayment']); ?>
    </div>

    <div class="i_general_row_box column flex_" id="general_conf">
      <div class="i_general_row_box_item flex_ tabing__justify">
        <div class="irow_box_left tabing flex_">
          <?php echo iN_HelpSecure($LANG['bankPaymentStatus']); ?>
        </div>
        <div class="irow_box_right">
          <div class="i_checkbox_wrapper flex_ tabing_non_justify">
            <label class="el-switch el-switch-yellow" for="bankPaymentStatus">
              <input type="checkbox" name="maintenancemode" class="chmdPayment" id="bankPaymentStatus" <?php echo iN_HelpSecure($bankPaymentStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
              <span class="el-switch-style"></span>
            </label>
            <div class="success_tick tabing flex_ sec_one bankPaymentStatus">
              <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
            </div>
          </div>
          <div class="rec_not box_not_padding_left">
            <?php echo iN_HelpSecure($LANG['bankPaymentStatusNot']); ?>
          </div>
        </div>
      </div>

      <form enctype="multipart/form-data" method="post" id="updateBankPaymentGataway">
        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['bankPaymentPercentageFee']); ?>
          </div>
          <div class="irow_box_right">
            <input type="text" name="bankpaymentpercentagefee" class="i_input flex_" value="<?php echo iN_HelpSecure($bankPaymentPercentageFee); ?>">
          </div>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['bankPaymentFixedCharge']); ?>
          </div>
          <div class="irow_box_right">
            <input type="text" name="bankpaymentfixedcharge" class="i_input flex_" value="<?php echo iN_HelpSecure($bankPaymentFixedCharge); ?>">
          </div>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['bankPaymentDetails']); ?>
          </div>
          <div class="irow_box_right">
            <textarea name="bank_description" class="i_textarea flex_ border_one"><?php echo iN_HelpSecure($bankPaymentDetails, 0, false); ?></textarea>
          </div>
        </div>

        <div class="i_settings_wrapper_item successNot">
          <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
        </div>

        <div class="admin_approve_post_footer">
          <div class="i_become_creator_box_footer">
            <input type="hidden" name="f" value="bankPaymentStatusa">
            <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
              <?php echo iN_HelpSecure($LANG['save_edit']); ?>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>