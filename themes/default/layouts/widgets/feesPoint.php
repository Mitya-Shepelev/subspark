<div class="i_become_creator_terms_box">
<div class="certification_form_container">
   <div class="certification_form_title"><?php echo iN_HelpSecure($LANG['setup_subscribers_fee']);?></div>
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
                    <input type="checkbox" name="weekly" <?php echo $iN->iN_Secure(isset($WeeklySubDetail['plan_status'])) == '1' ? 'checked="checked"' : NULL;?>>
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="i_t_warning" id="wweekly"><?php echo iN_HelpSecure($LANG['must_specify_weekly_subscription_fee_point']);?></div>
        <div class="i_t_warning" id="waweekly"><?php echo iN_HelpSecure($LANG['minimum_weekly_subscription_fee_point']);?></div>
        <div class="i_set_subscription_fee">
           <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div>
           <div class="i_subs_price">
                <input 
                  type="text" 
                  class="transition aval" 
                  id="spweek"
                  placeholder="<?php echo iN_HelpSecure($LANG['weekly_subs_ex_fee']);?>"
                  value="<?php echo isset($WeeklySubDetail['amount']) ? $WeeklySubDetail['amount'] : NULL;?>"
                  data-min="<?php echo iN_HelpSecure($minPointFeeWeekly); ?>"
                  data-rate="<?php echo iN_HelpSecure($onePointEqual); ?>"
                  data-fee="<?php echo iN_HelpSecure($adminFee / 100); ?>"
                >
           </div>
           <div class="i_subs_interval"><?php echo iN_HelpSecure($LANG['weekly']);?></div>
        </div>
        <div class="i_t_warning_earning weekly_earning"><?php echo iN_HelpSecure($LANG['potential_gain']);?> <?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span id="weekly_earning"></span></div>
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
                    <input type="checkbox" name="monthly" <?php echo $iN->iN_Secure(isset($MonthlySubDetail['plan_status'])) == '1' ? 'checked="checked"' : NULL;?>>
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="i_t_warning" id="wmonthly"><?php echo iN_HelpSecure($LANG['must_specify_monthly_subscription_fee_point']);?></div>
        <div class="i_t_warning" id="mamonthly"><?php echo iN_HelpSecure($LANG['minimum_monthly_subscription_fee_point']);?></div>
        <div class="i_set_subscription_fee">
           <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div>
            <div class="i_subs_price">
                <input 
                  type="text" 
                  class="transition aval" 
                  id="spmonth"
                  placeholder="<?php echo iN_HelpSecure($LANG['monthly_subs_ex_fee']);?>"
                  value="<?php echo isset($MonthlySubDetail['amount']) ? $MonthlySubDetail['amount'] : NULL;?>"
                  data-min="<?php echo iN_HelpSecure($minPointFeeMonthly); ?>"
                  data-rate="<?php echo iN_HelpSecure($onePointEqual); ?>"
                  data-fee="<?php echo iN_HelpSecure($adminFee / 100); ?>"
                >
            </div>
           <div class="i_subs_interval"><?php echo iN_HelpSecure($LANG['monthly']);?></div>
        </div>
        <div class="i_t_warning_earning mamonthly_earning"><?php echo iN_HelpSecure($LANG['potential_gain']);?> <?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span id="mamonthly_earning"></span></div>
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
                    <input type="checkbox" name="yearly" <?php echo $iN->iN_Secure(isset($YearlySubDetail['plan_status'])) == '1' ? 'checked="checked"' : NULL;?>>
                    <span class="el-switch-style"></span>
                </label>
           </div>
        </div>
        <div class="i_t_warning" id="wyearly"><?php echo iN_HelpSecure($LANG['must_specify_yearly_subscription_fee_point']);?></div>
        <div class="i_t_warning" id="yayearly"><?php echo iN_HelpSecure($LANG['minimum_yearly_subscription_fee_point']);?></div>
        <div class="i_set_subscription_fee">
           <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div>
           <div class="i_subs_price">
                <input 
                  type="text" 
                  class="transition aval" 
                  id="spyear"
                  placeholder="<?php echo iN_HelpSecure($LANG['yearly_subs_ex_fee']);?>"
                  value="<?php echo isset($YearlySubDetail['amount']) ? $YearlySubDetail['amount'] : NULL;?>"
                  data-min="<?php echo iN_HelpSecure($minPointFeeYearly); ?>"
                  data-rate="<?php echo iN_HelpSecure($onePointEqual); ?>"
                  data-fee="<?php echo iN_HelpSecure($adminFee / 100); ?>"
                >
            </div>
           <div class="i_subs_interval"><?php echo iN_HelpSecure($LANG['yearly']);?></div>
        </div>
        <div class="i_t_warning_earning yayearly_earning"><?php echo iN_HelpSecure($LANG['potential_gain']);?> <?php echo iN_HelpSecure($currencys[$defaultCurrency]);?><span id="yayearly_earning"></span></div>
    </div>
    <!--/SET SUBSCRIPTION FEE BOX-->
    <?php }?>
   </div>
</div>
</div>
<div class="i_become_creator_box_footer">
   <div class="i_nex_btn c_Next transition"><?php echo iN_HelpSecure($LANG['next']);?></div>
</div>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/feesPointHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>