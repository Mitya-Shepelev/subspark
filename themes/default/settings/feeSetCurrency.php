<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('77'));?><?php echo iN_HelpSecure($LANG['setup_subscribers_fee']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
    <div class="payouts_form_container">
   <div class="i_payout_methods_form_container">
   <div class="certification_form_not"><?php echo html_entity_decode($LANG['setup_subscribers_fee_note']);?></div>
   <div class="i_subscription_form_container">
    <?php if($subWeekStatus == 'yes'){?>
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box">
        <div class="i_sub_not">
           <?php echo iN_HelpSecure($LANG['weekly_subs_fee']);?> <span class="weekly_success"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></span>
        </div>
        <div class="i_sub_not_check">
           <?php echo iN_HelpSecure($LANG['weekly_subs_fee_not']);?>
           <div class="i_sub_not_check_box">
                <label class="el-switch el-switch-yellow">
                    <input type="checkbox" name="weekly" class="subfeea" id="aweekly" data-id="aweekly" <?php echo (isset($WeeklySubDetail['plan_status']) ? $iN->iN_Secure($WeeklySubDetail['plan_status']) : NULL) == '1' ? 'value="1" checked="checked"' : 'value="0"';?>>
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="i_t_warning" id="wweekly"><?php echo iN_HelpSecure($LANG['must_specify_weekly_subscription_fee']);?></div>
        <div class="i_t_warning" id="waweekly"><?php echo iN_HelpSecure($LANG['minimum_weekly_subscription_fee']);?></div>
        <div class="i_set_subscription_fee">
           <div class="i_subs_currency"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?></div>
           <div class="i_subs_price"><input type="text" class="transition aval" id="spweek" placeholder="<?php echo iN_HelpSecure($LANG['weekly_subs_ex_fee']);?>" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' value="<?php echo isset($WeeklySubDetail['amount']) ? $WeeklySubDetail['amount'] : NULL;?>"></div>
           <div class="i_subs_interval"><?php echo iN_HelpSecure($LANG['weekly']);?></div>
        </div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
    <?php }?>
    <?php if($subMontlyStatus == 'yes'){?>
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box">
        <div class="i_sub_not">
        <?php echo iN_HelpSecure($LANG['monthly_subs_fee']);?><span class="monthly_success"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></span>
        </div>
        <div class="i_sub_not_check">
        <?php echo iN_HelpSecure($LANG['monthly_subs_fee_not']);?>
           <div class="i_sub_not_check_box">
                <label class="el-switch el-switch-yellow">
                    <input type="checkbox" name="monthly" class="subfeea" id="amonthly" data-id="amonthly" <?php echo (isset($MonthlySubDetail['plan_status']) ? $iN->iN_Secure($MonthlySubDetail['plan_status']) : NULL) == '1' ? 'value="1" checked="checked"' : 'value="0"';?>>
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="i_t_warning" id="wmonthly"><?php echo iN_HelpSecure($LANG['must_specify_monthly_subscription_fee']);?></div>
        <div class="i_t_warning" id="mamonthly"><?php echo iN_HelpSecure($LANG['minimum_monthly_subscription_fee']);?></div>
        <div class="i_set_subscription_fee">
           <div class="i_subs_currency"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?></div>
           <div class="i_subs_price"><input type="text" class="transition aval" id="spmonth" placeholder="<?php echo iN_HelpSecure($LANG['monthly_subs_ex_fee']);?>" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' value="<?php echo isset($MonthlySubDetail['amount']) ? $MonthlySubDetail['amount'] : NULL;?>"></div>
           <div class="i_subs_interval"><?php echo iN_HelpSecure($LANG['monthly']);?></div>
        </div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
    <?php }?>
    <?php if($subYearlyStatus == 'yes'){?>
    <!--SET SUBSCRIPTION FEE BOX-->
    <div class="i_set_subscription_fee_box">
        <div class="i_sub_not">
        <?php echo iN_HelpSecure($LANG['yearly_subs_fee']);?><span class="yearly_success"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></span>
        </div>
        <div class="i_sub_not_check">
           <?php echo iN_HelpSecure($LANG['yearly_subs_fee_not']);?>
           <div class="i_sub_not_check_box">
                <label class="el-switch el-switch-yellow">
                    <input type="checkbox" name="yearly" class="subfeea" id="ayearly" data-id="ayearly" <?php echo (isset($YearlySubDetail['plan_status']) ? $iN->iN_Secure($YearlySubDetail['plan_status']) : NULL) == '1' ? 'value="1" checked="checked"' : 'value="0"';?>>
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="i_t_warning" id="wyearly"><?php echo iN_HelpSecure($LANG['must_specify_yearly_subscription_fee']);?></div>
        <div class="i_t_warning" id="yayearly"><?php echo iN_HelpSecure($LANG['minimum_yearly_subscription_fee']);?></div>
        <div class="i_set_subscription_fee">
           <div class="i_subs_currency"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?></div>
           <div class="i_subs_price"><input type="text" class="transition aval" id="spyear" placeholder="<?php echo iN_HelpSecure($LANG['yearly_subs_ex_fee']);?>" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' value="<?php echo isset($YearlySubDetail['amount']) ? $YearlySubDetail['amount'] : NULL;?>"></div>
           <div class="i_subs_interval"><?php echo iN_HelpSecure($LANG['yearly']);?></div>
        </div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
    <?php }?>
   </div>
   </div>
</div>
    </div>
    <div class="i_settings_wrapper_item successNot">
        <?php echo iN_HelpSecure($LANG['payment_settings_updated_success'])?>
    </div>
     <div class="i_become_creator_box_footer tabing">
        <div class="i_nex_btn c_uNext transition"><?php echo iN_HelpSecure($LANG['save_edit']);?></div>
     </div>
  </div>
</div>
<script>
  window.subscriptionConfig = {
    subWeekStatus: "<?php echo $subWeekStatus; ?>",
    subMonthlyStatus: "<?php echo $subMontlyStatus; ?>",
    subYearlyStatus: "<?php echo $subYearlyStatus; ?>",
    minWeekAmount: "<?php echo $subscribeWeeklyMinimumAmount; ?>",
    minMonthAmount: "<?php echo $subscribeMonthlyMinimumAmount; ?>",
    minYearAmount: "<?php echo $subscribeYearlyMinimumAmount; ?>",
    currencySymbol: "<?php echo iN_HelpSecure($currencys[$defaultCurrency]); ?>"
  };
</script>