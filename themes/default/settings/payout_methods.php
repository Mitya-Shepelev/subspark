<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('77'));?><?php echo iN_HelpSecure($LANG['payout_methods']);?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['payout_methods_not']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
    <div class="payouts_form_container">
   <div class="i_payout_methods_form_container">
       <form id="bankForm">
       <?php if($payPalPaymentStatus == '1'){?>
        <!--SET SUBSCRIPTION FEE BOX-->
        <div class="i_set_subscription_fee_box">
            <div class="i_sub_not">
            <?php echo iN_HelpSecure($LANG['paypal']);?>
            </div>
            <div class="i_sub_not_check">
            <?php echo iN_HelpSecure($LANG['if_default_not']);?>
            <div class="i_sub_not_check_box pyot">
                <div class="el-radio el-radio-yellow">
                    <input type="radio" name="default" id="paypal" value="paypal" <?php echo iN_HelpSecure($payoutMethod) == 'paypal' ? "checked='checked'" : ""; ?>>
                    <label class="el-radio-style" for="paypal"></label>
			    </div>
            </div>
            </div>
            <div class="i_t_warning" id="setWarning"><?php echo iN_HelpSecure($LANG['paypal_payout_warning']);?></div>
            <div class="i_t_warning" id="notMatch"><?php echo iN_HelpSecure($LANG['paypal_email_address_not_match']);?></div>
            <div class="i_t_warning" id="notValidE"><?php echo iN_HelpSecure($LANG['invalid_email_address']);?></div>
            <div class="i_set_subscription_fee margin-bottom-ten">
            <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('80'));?></div>
            <div class="i_payout_"><input type="text" class="transition aval" id="paypale" placeholder="<?php echo iN_HelpSecure($LANG["paypal_email"]);?>" value="<?php echo filter_var($iN->iN_Secure($paypalEmail), FILTER_SANITIZE_EMAIL);?>"></div>
            </div>
            <div class="i_set_subscription_fee margin-bottom-ten">
            <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('80'));?></div>
            <div class="i_payout_"><input type="text" class="transition aval" id="paypalere" placeholder="<?php echo iN_HelpSecure($LANG["confirm_paypal_email"]);?>" value="<?php echo filter_var($iN->iN_Secure($paypalEmail), FILTER_SANITIZE_EMAIL);?>"></div>
            </div>
        </div>
        <!--/SET SUBSCRIPTION FEE BOX-->
        <?php }?>
        <!--SET SUBSCRIPTION FEE BOX-->
        <div class="i_set_subscription_fee_box">
            <div class="i_sub_not">
            <?php echo iN_HelpSecure($LANG['bank_transfer']);?>
            </div>
            <div class="i_sub_not_check">
            <?php echo iN_HelpSecure($LANG['if_default_not_bank']);?>
            <div class="i_sub_not_check_box pyot">
                <div class="el-radio el-radio-yellow">
                    <input type="radio" name="default" id="bank" value="bank" <?php echo iN_HelpSecure($payoutMethod) == 'bank' ? "checked='checked'" : ""; ?>>
                    <label class="el-radio-style" for="bank"></label>
			    </div>
            </div>
            </div>
            <div class="i_t_warning" id="setBankWarning"><?php echo iN_HelpSecure($LANG['bank_payout_warning']);?></div>
            <div class="i_set_subscription_fee margin-bottom-ten">
            <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('81'));?></div>
            <div class="i_payout_">
                <textarea name="bank" id="bank_transfer" class="bank_textarea" placeholder="<?php echo iN_HelpSecure($LANG['bank_transfer_placeholder']);?>"><?php echo iN_HelpSecure($iN->iN_Secure($bankAccount));?></textarea>
            </div>
            </div>
        </div>
        <!--/SET SUBSCRIPTION FEE BOX-->
       </form>
   </div>
</div>
    </div>
    <div class="i_settings_wrapper_item successNot">
        <?php echo iN_HelpSecure($LANG['payment_settings_updated_success'])?>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <div class="i_nex_btn pyot_sNext transition"><?php echo iN_HelpSecure($LANG['save_edit']);?></div>
     </div>
  </div>
</div>