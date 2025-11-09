<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52'));?><?php echo iN_HelpSecure($LANG['videoCallSet']);?></div>
       <div class="i_moda_header_nt"><strong><?php echo iN_HelpSecure($LANG['all_processing_fee_note']);?></strong></div>
    </div>
    <div class="i_settings_wrapper_items">
    <div class="payouts_form_container">
    <div class="i_payout_methods_form_container">
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box">
        <div class="i_sub_not">
        <?php echo iN_HelpSecure($LANG['video_call_fee']);?><span class="monthly_success"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></span>
        </div>
        <div class="i_sub_not_check">
        <?php echo iN_HelpSecure($LANG['video_call_fee_not']);?>

        </div>
        <div class="i_t_warning" id="wmonthly"><?php echo iN_HelpSecure($LANG['video_call_cost_warning']);?></div>
        <div class="i_set_subscription_fee">
           <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div>
           <div class="i_subs_price"><input type="text" class="transition avalv" id="spmonth" placeholder="<?php echo iN_HelpSecure($LANG['video_call_cost']);?>" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' value="<?php echo isset($myVideoCallPrice) ? $myVideoCallPrice : NULL;?>"></div>
           <div class="i_subs_interval"><?php echo  $currencys[$defaultCurrency].'<span class="pricecal">'.$myVideoCallPrice*$onePointEqual.'</span>';?></div>
        </div>
        <div class="i_t_warning_earning mamonthly_earning"><?php echo iN_HelpSecure($LANG['potential_gain']);?> <?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span id="mamonthly_earning"></span></div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
   </div>
</div>
    </div>
    <div class="i_settings_wrapper_item successNot">
        <?php echo iN_HelpSecure($LANG['payment_settings_updated_success'])?>
    </div>
    <div class="i_become_creator_box_footer tabing">
        <div class="i_nex_btn c_UpdateCostV transition"><?php echo iN_HelpSecure($LANG['save_edit']);?></div>
     </div>
  </div>
</div>
<script type="text/javascript">
  var pointEqualValue = <?php echo iN_HelpSecure($onePointEqual);?>;
</script>