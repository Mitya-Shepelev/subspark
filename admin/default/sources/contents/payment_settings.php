<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        
        <div class="i_general_title_box">
          <?php echo iN_HelpSecure($LANG['payment_settings']);?>
        </div>
         
        <div class="i_general_row_box column flex_ " id="general_conf">
        <form enctype="multipart/form-data" method="post" id="paymentSettings">
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['choose_subs_system']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="pl_limit"><span class="pslmt"><?php echo iN_HelpSecure($subscriptionType) == '1' ? 'Stripe' : 'Points'; ?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_point_sub_list_container">
                            <div class="i_countries_list border_one column flex_">

                            <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($subscriptionType) == '1' ? 'choosed' : ''; ?>" id='1' data-c="Stripe" data-type="ps_limit">Stripe</div>
                            <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($subscriptionType) == '2' ? 'choosed' : ''; ?>" id='2' data-c="Points" data-type="ps_limit">Points</div>

                            </div>
                            <input type="hidden" name="choose_sub_type" id="pSLimit" value="<?php echo iN_HelpSecure($subscriptionType);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['suggestion_choose']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['default_currency']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="fl_limit"><span class="lmt"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?></span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php foreach($currencys as $crncy => $value){?>
                              <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($defaultCurrency) == '' . $crncy . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($crncy); ?>' data-c="<?php echo iN_HelpSecure($value);?>" data-type="mb_limit"><?php echo iN_HelpSecure($crncy).'('.$value.')'; ?></div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="default_currency" id="upLimit" value="<?php echo iN_HelpSecure($defaultCurrency);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['important_for_subscription_currency_selection_not']);?></div>
                   </div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['fee_comission']);?></div>
               <div class="irow_box_right">
                   <div class="i_box_limit flex_ column">
                       <div class="i_limit" data-type="ch_limit"><span class="lct"><?php echo iN_HelpSecure($adminFee);?>%</span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36'));?></div>
                        <div class="i_limit_list_ch_container">
                            <div class="i_countries_list border_one column flex_">
                            <?php for ($i = 100; $i > 0; $i--) {?>
                                <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($adminFee) == '' . $i . '' ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($i); ?>' data-c="<?php echo iN_HelpSecure($i).'%';?>" data-type="characterLimit"><?php echo iN_HelpSecure($i);?>%</div>
                            <?php }?>
                            </div>
                            <input type="hidden" name="fee_comission" id="upcLimit" value="<?php echo iN_HelpSecure($adminFee);?>">
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['fee_mossion_not']);?></div>
                   </div>
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['min_sub_amount_weekly']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_sub_weekly" min="1" class="i_input flex_" value="<?php echo iN_HelpSecure($subscribeWeeklyMinimumAmount);?>">
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['min_sub_amount_monthly']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_sub_monthly" min="1" class="i_input flex_" value="<?php echo iN_HelpSecure($subscribeMonthlyMinimumAmount);?>">
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['min_sub_amount_yearly']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_sub_yearly" min="1" class="i_input flex_" value="<?php echo iN_HelpSecure($subscribeYearlyMinimumAmount);?>">
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['min_point_amount']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_point_amount" min="1" class="i_input flex_" value="<?php echo iN_HelpSecure($minimumPointLimit);?>">
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['min_tip_amount_title']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_tip_amount" min="1" class="i_input flex_" value="<?php echo iN_HelpSecure($minimumTipAmount);?>">
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['max_point_amount']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="max_point_amount" min="1" class="i_input flex_" value="<?php echo iN_HelpSecure($maximumPointLimit);?>">
               </div>
            </div>
            
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['how_much_one_point']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="point_to_dolar" min="0.001" step="0.001" class="i_input flex_" value="<?php echo iN_HelpSecure($onePointEqual);?>">
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['minimum_withdrawal_amount_set']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_withdrawl_amount" min="1" step="0.1" class="i_input flex_" value="<?php echo iN_HelpSecure($minimumWithdrawalAmount);?>">
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['minimum_point_fee_weekly']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_point_fee_weekly" min="1" step="1" class="i_input flex_" value="<?php echo iN_HelpSecure($minPointFeeWeekly);?>">
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['minimum_point_fee_weekly_not']);?></div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['minimum_point_fee_monthly']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_point_fee_monthly" min="1" step="1" class="i_input flex_" value="<?php echo iN_HelpSecure($minPointFeeMonthly);?>">
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['minimum_point_fee_monthly_not']);?></div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['minimum_point_fee_yearly']);?></div>
               <div class="irow_box_right">
                 <input type="number" name="min_point_fee_yearly" min="1" step="1" class="i_input flex_" value="<?php echo iN_HelpSecure($minPointFeeYearly);?>">
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['minimum_point_fee_yearly_not']);?></div>
               </div>
            </div>
            
            <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']);?></div>
            <div class="warning_wrapper warning_one"><?php echo iN_HelpSecure($LANG['no_empty_no_zero']);?></div>
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
                <input type="hidden" name="f" value="paymentSettings">
                <button type="submit" name="submit" class="i_nex_btn_btn transition"><?php echo iN_HelpSecure($LANG['save_edit']);?></button>
            </div>
            
        </form>
        </div>
        
    </div> 
    <div class="i_general_white_board border_one column flex_ tabing__justify manage_margin_top">
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['weekly_sub_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="weeklySubStatus">
                          <input type="checkbox" name="weeklySubStatus" class="chmdPost" id="weeklySubStatus" <?php echo iN_HelpSecure($subWeekStatus) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="weeklySubStatus" class="weeklySubStatus" value="<?php echo iN_HelpSecure($subWeekStatus);?>">
                    <div class="success_tick tabing flex_ sec_one weeklySubStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['monthly_sub_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="monthlySubStatus">
                          <input type="checkbox" name="monthlySubStatus" class="chmdPost" id="monthlySubStatus" <?php echo iN_HelpSecure($subMontlyStatus) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="monthlySubStatus" class="monthlySubStatus" value="<?php echo iN_HelpSecure($subMontlyStatus);?>">
                    <div class="success_tick tabing flex_ sec_one monthlySubStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div>
             
             <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['yearly_sub_status']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="yearlySubStatus">
                          <input type="checkbox" name="yearlySubStatus" class="chmdPost" id="yearlySubStatus" <?php echo iN_HelpSecure($subYearlyStatus) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                        <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="yearlySubStatus" class="yearlySubStatus" value="<?php echo iN_HelpSecure($subYearlyStatus);?>">
                    <div class="success_tick tabing flex_ sec_one yearlySubStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
               </div>
            </div> 
    </div> 
    <div class="i_general_white_board border_one column flex_ tabing__justify manage_margin_top">
            
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['wait_renewal_period']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-radio el-radio-sm fontsizeradio">
            				<span class="margin-r"></span>
            				<input type="radio" name="renewalsubs" data-id="renewalsubs" class="chmdPostSub" <?php echo iN_HelpSecure($unSubscribeStyle) == 'yes' ? 'value="yes" checked="checked"' : 'value="yes"';?>>
            				<span class="el-radio-style  pull-right"></span>
            			</label>
                <div class="success_tick tabing flex_ sec_one subsstatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                
                <div class="rec_not box_not_padding_top"><?php echo html_entity_decode($LANG['wait_renewal_period_not']);?></div>
               </div>
            </div>
             
            <div class="i_general_row_box_item flex_ tabing_non_justify">
               <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['subscription_cancel_immediately']);?></div>
               <div class="irow_box_right">
                 
                 <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-radio el-radio-sm fontsizeradio">
            				<span class="margin-r"></span>
            				<input type="radio" name="renewalsubs" data-id="renewalsubs" class="chmdPostSub" <?php echo iN_HelpSecure($unSubscribeStyle) == 'no' ? 'value="no" checked="checked"' : 'value="no"';?>>
            				<span class="el-radio-style  pull-right"></span>
            			</label>
                    <div class="success_tick tabing flex_ sec_one subsstatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                </div>
                 
                 <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['subscription_cancel_immeditaley_not']);?></div>
               </div>
            </div> 
    </div> 
</div>
