<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('86'));?><?php echo iN_HelpSecure($LANG['withdrawal']);?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['send_withdrawal_not']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
         <div class="payouts_form_container">
            <div class="minimum_amount flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60'));?><?php echo iN_HelpSecure($LANG['minimmum_withdrawal_amount']);?></div>
            <div class="method_not"><?php echo html_entity_decode($LANG['not_default_payment_method']);?></div>
            <!--SET SUBSCRIPTION FEE BOX-->
            <div class="i_set_subscription_fee_box">
               <div class="your_balance"><?php echo iN_HelpSecure($LANG['your_available_balance']);?><?php echo $currencys[$defaultCurrency] . (addCommasAndDots($userWallet));?></div>
            </div>
            <!--/SET SUBSCRIPTION FEE BOX-->
            <!---->
            <div class="i_settings_wrapper_items">
               <div class="i_settings_item_title_withdraw"><?php echo iN_HelpSecure($LANG['amount']);?></div>
               <div class="i_settings_item_title_for_withdraw"><input type="text" name="amount" class="flnm" id="wamount" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)'></div>
            </div>
            <div class="i_t_warning padding_left_ten" id="mwithdrawal"><?php echo iN_HelpSecure($LANG['minimum_withdrawal_amount']);?></div>
            <div class="i_t_warning padding_left_ten" id="nbudget"><?php echo iN_HelpSecure($LANG['budget_not_enough']);?></div>
            <div class="i_t_warning padding_left_ten" id="nnoway"><?php echo iN_HelpSecure($LANG['noway_desc']);?></div>
            <div class="i_t_warning padding_left_ten" id="nwaitpending"><?php echo iN_HelpSecure($LANG['wait_for_pending']);?></div>
            <!---->
            <div class="i_settings_wrapper_item successNot">
               <?php echo html_entity_decode($LANG['withdrawal_success'])?>
            </div>
         </div>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <div class="i_nex_btn_btn mwithdraw transition"><?php echo iN_HelpSecure($LANG['request_withdrawal']);?></div>
     </div>
  </div>
</div>