<div class="i_become_creator_terms_box">
<div class="certification_form_container">
  <div class="certification_form_title"><?php echo iN_HelpSecure($LANG['payout_methods']);?></div>
  <div class="certification_form_not"><?php echo iN_HelpSecure($LANG['payout_methods_not']);?></div>
  <div class="i_subscription_form_container">
    <form id="bankForm">
      <!-- PayPal -->
      <div class="i_set_subscription_fee_box">
        <div class="i_sub_not"><?php echo iN_HelpSecure($LANG['paypal']);?></div>
        <div class="i_sub_not_check">
          <?php echo iN_HelpSecure($LANG['if_default_not']);?>
          <div class="i_sub_not_check_box pyot">
            <div class="el-radio el-radio-yellow">
              <input type="radio" name="default" id="paypal" value="paypal" checked="checked">
              <label class="el-radio-style" for="paypal"></label>
            </div>
          </div>
        </div>
        <div class="i_t_warning" id="setWarning"><?php echo iN_HelpSecure($LANG['paypal_payout_warning']);?></div>
        <div class="i_t_warning" id="notMatch"><?php echo iN_HelpSecure($LANG['paypal_email_address_not_match']);?></div>
        <div class="i_t_warning" id="notValidE"><?php echo iN_HelpSecure($LANG['invalid_email_address']);?></div>
        <div class="i_set_subscription_fee margin-bottom-ten">
          <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('80'));?></div>
          <div class="i_payout_">
            <input 
              type="text" 
              class="transition aval" 
              id="paypale" 
              placeholder="<?php echo filter_var($LANG["paypal_email"], FILTER_SANITIZE_EMAIL);?>"
              data-email-msg="<?php echo iN_HelpSecure($LANG['paypal_payout_warning']);?>"
              data-nomatch-msg="<?php echo iN_HelpSecure($LANG['paypal_email_address_not_match']);?>"
              data-invalid-msg="<?php echo iN_HelpSecure($LANG['invalid_email_address']);?>"
            >
          </div>
        </div>
        <div class="i_set_subscription_fee margin-bottom-ten">
          <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('80'));?></div>
          <div class="i_payout_">
            <input 
              type="text" 
              class="transition aval" 
              id="paypalere" 
              placeholder="<?php echo filter_var($LANG["confirm_paypal_email"], FILTER_SANITIZE_EMAIL);?>"
            >
          </div>
        </div>
      </div>

      <!-- Banka Transfer -->
      <div class="i_set_subscription_fee_box">
        <div class="i_sub_not"><?php echo iN_HelpSecure($LANG['bank_transfer']);?></div>
        <div class="i_sub_not_check">
          <?php echo iN_HelpSecure($LANG['if_default_not_bank']);?>
          <div class="i_sub_not_check_box pyot">
            <div class="el-radio el-radio-yellow">
              <input type="radio" name="default" id="bank" value="bank">
              <label class="el-radio-style" for="bank"></label>
            </div>
          </div>
        </div>
        <div class="i_t_warning" id="setBankWarning"><?php echo iN_HelpSecure($LANG['bank_payout_warning']);?></div>
        <div class="i_set_subscription_fee margin-bottom-ten">
          <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('81'));?></div>
          <div class="i_payout_ i_payout_style">
            <textarea 
              name="bank" 
              id="bank_transfer" 
              class="bank_textarea" 
              placeholder="<?php echo iN_HelpSecure($LANG['bank_transfer_placeholder']);?>"
              data-bank-msg="<?php echo iN_HelpSecure($LANG['bank_payout_warning']);?>"
            ></textarea>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
</div>

<div class="i_become_creator_box_footer">
  <div class="i_nex_btn pyot_Next transition"><?php echo iN_HelpSecure($LANG['next']);?></div>
</div>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/payoutHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>