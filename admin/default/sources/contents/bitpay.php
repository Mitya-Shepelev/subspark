<div class="i_contents_container">
  <div class="i_general_white_board border_one column flex_ tabing__justify">
    <div class="i_general_title_box">
      <?php echo iN_HelpSecure($LANG['bitpay_payment']); ?>
    </div>

    <div class="i_general_row_box column flex_ white_board_padding" id="general_conf">
      <div class="i_general_row_box_item flex_ column tabing__justify">
        <div class="i_checkbox_wrapper flex_ tabing_non_justify">
          <div class="i_chck_text admin_note_t">
            <?php echo iN_HelpSecure($LANG['test_mode']); ?>
          </div>
          <label class="el-switch el-switch-yellow" for="bitpay_mode">
            <input type="checkbox" name="bitpay_mode" class="chmdPayment" id="bitpay_mode" <?php echo iN_HelpSecure($bitPayPaymentMode) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
            <span class="el-switch-style"></span>
          </label>
          <div class="i_chck_text">
            <?php echo iN_HelpSecure($LANG['live_mode']); ?>
          </div>
          <div class="success_tick tabing flex_ sec_one bitpay_mode">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
          </div>
        </div>
      </div>

      <div class="i_general_row_box_item flex_ tabing__justify">
        <div class="irow_box_left tabing flex_">
          <?php echo iN_HelpSecure($LANG['bitpay_status']); ?>
        </div>
        <div class="irow_box_right">
          <div class="i_checkbox_wrapper flex_ tabing_non_justify">
            <label class="el-switch el-switch-yellow" for="bitpay_status">
              <input type="checkbox" name="bitpay_status" class="chmdPayment" id="bitpay_status" <?php echo iN_HelpSecure($bitPayPaymentStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
              <span class="el-switch-style"></span>
            </label>
            <div class="success_tick tabing flex_ sec_one bitpay_status">
              <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
            </div>
          </div>
          <div class="rec_not box_not_padding_left">
            <?php echo iN_HelpSecure($LANG['bitpay_status_not']); ?>
          </div>
        </div>
      </div>

      <form enctype="multipart/form-data" method="post" id="updatePaymentGataway">
        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['bitpay_notification_email']); ?>
          </div>
          <div class="irow_box_right">
            <input type="text" name="notification_email" class="i_input flex_" value="<?php echo iN_HelpSecure($bitPayPaymentNotificationEmail); ?>">
          </div>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['bitpay_password']); ?>
          </div>
          <div class="irow_box_right">
            <input type="password" name="bit_password" class="i_input flex_" value="<?php echo iN_HelpSecure($bitPayPaymentPassword); ?>">
          </div>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['bitpay_pairing_code']); ?>
          </div>
          <div class="irow_box_right">
            <input type="text" name="pairinccode" class="i_input flex_" value="<?php echo iN_HelpSecure($bitPayPaymentPairingCode); ?>">
          </div>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['bitpay_label']); ?>
          </div>
          <div class="irow_box_right">
            <input type="text" name="bitLabel" class="i_input flex_" value="<?php echo iN_HelpSecure($bitPayPaymentLabel); ?>">
          </div>
        </div>

        <div class="i_general_row_box_item flex_ tabing_non_justify">
          <div class="irow_box_left tabing flex_">
            <?php echo iN_HelpSecure($LANG['bitpay_currency']); ?>
          </div>
          <div class="irow_box_right">
            <div class="i_box_limit flex_ column">
              <div class="i_limit" data-type="fl_limit">
                <span class="lmt"><?php echo iN_HelpSecure($bitPayPaymentCurrency) . '(' . $currencys[$bitPayPaymentCurrency] . ')'; ?></span>
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
              </div>
              <div class="i_limit_list_container">
                <div class="i_countries_list border_one column flex_">
                  <?php foreach ($currencys as $crncy => $value) { ?>
                    <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($bitPayPaymentCurrency) == $crncy ? 'choosed' : ''; ?>" id="<?php echo iN_HelpSecure($crncy); ?>" data-c="<?php echo iN_HelpSecure($crncy) . '(' . $value . ')'; ?>" data-type="mb_limit">
                      <?php echo iN_HelpSecure($crncy) . '(' . $value . ')'; ?>
                    </div>
                  <?php } ?>
                </div>
                <input type="hidden" name="bitpay_currency" id="upLimit" value="<?php echo iN_HelpSecure($bitPayPaymentCurrency); ?>">
              </div>
              <div class="rec_not box_not_padding_top">
                <?php echo iN_HelpSecure($LANG['make_sure_for_bitpay']); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="i_settings_wrapper_item successNot">
          <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
        </div>

        <div class="admin_approve_post_footer">
          <div class="i_become_creator_box_footer">
            <input type="hidden" name="f" value="updateBitPay">
            <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
              <?php echo iN_HelpSecure($LANG['save_edit']); ?>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>