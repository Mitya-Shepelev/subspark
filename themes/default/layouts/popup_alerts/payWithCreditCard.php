<?php if($subscriptionType == '1'){?>
<div class="i_modal_bg_in i_subs_modal pay_zindex">
    <!--SHARE-->
   <div class="i_modal_in_in i_payment_pop_box">
       <div class="i_modal_content">
           <div class="payClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
           <!--Subscribing Avatar-->
           <div class="i_subscribing" style="background-image:url(<?php echo iN_HelpSecure($f_profileAvatar);?>);"></div>
           <div class="i_subscribing_note" id="pln" data-p="<?php echo iN_HelpSecure($planID);?>">
              <?php echo preg_replace( '/{.*?}/', $f_userfullname, $LANG['subscription_payment']); ?>
           </div>
           <form id="paymentFrm" novalidate>
           <div class="i_credit_card_form">
                <div id="paymentResponse"></div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['card_holder']);?></label>
                    <div class="form-control">
                        <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('70'));?></div>
                       <input type="text" id="name" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['card_holder']);?>" autocomplete="cc-name" inputmode="text" maxlength="64" required>
                    </div>
                </div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['email']);?></label>
                    <div class="form-control">
                       <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('71'));?></div>
                       <input type="email" id="email" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['email']);?>" autocomplete="email" inputmode="email" maxlength="120" required>
                    </div>
                </div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['card_number']);?></label>
                    <div class="form-control">
                       <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('72'));?></div>
                       <div id="card_number" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['card_number']);?>" aria-label="Card number"></div>
                    </div>
                </div>
                <div class="pay_form_group_plus">
                    <div class="i_form_group_plus">
                        <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['expiration_date']);?></label>
                        <div class="form-control">
                            <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73'));?></div>
                            <div id="card_expiry" class="inora_user_input" placeholder="DD/YY" aria-label="Card expiry"></div>
                        </div>
                    </div>
                    <div class="i_form_group_plus">
                        <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['ccv_code']);?></label>
                        <div class="form-control">
                            <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('74'));?></div>
                            <div id="card_cvc" class="inora_user_input" placeholder="123" aria-label="Card CVC"></div>
                        </div>
                    </div>
                </div>
                <div class="pay_form_group">
                    <button type="button" class="pay_subscription transition" aria-live="polite" data-label-pay="<?php echo iN_HelpSecure($LANG['pay']).' '.iN_HelpSecure($currencys[$stripeCurrency]).$f_PlanAmount; ?>" data-label-processing="<?php echo iN_HelpSecure($LANG['processing'] ?? 'Processing...');?>"><?php echo iN_HelpSecure($LANG['pay']);?> <?php echo iN_HelpSecure($currencys[$stripeCurrency]).$f_PlanAmount;?></button>
                </div>
                <div class="pay_form_group">
                   <div class="i_pay_note">
                       <?php echo iN_HelpSecure($LANG['subscription_renew']);?>
                   </div>
                </div>
           </div>
           </form>
       </div>
   </div>
<script>
    window.payWithCardData = {
        stripePublicKey: "<?php echo iN_HelpSecure($stripePublicKey); ?>",
        siteurl: "<?php echo iN_HelpSecure($base_url); ?>",
        planID: "<?php echo iN_HelpSecure($planID); ?>",
        userID: "<?php echo iN_HelpSecure($userID); ?>",
        lightDark: "<?php echo iN_HelpSecure($lightDark); ?>"
    };
</script>
<script src="https://js.stripe.com/v3"></script> 
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/payWithCreditCard.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
</div>
<?php }else if($subscriptionType == '3'){?>
<script>
    window.manualCardData = {
        siteurl: "<?php echo iN_HelpSecure($base_url); ?>",
        planID: "<?php echo iN_HelpSecure($planID); ?>",
        userID: "<?php echo iN_HelpSecure($userID); ?>"
    };
</script> 
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/manualCreditCard.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
<!--CREDIT CARD FORM-->
<div class="i_moda_bg_in_form i_subs_modal i_modal_display_in pay_zindex">
   <div class="i_modal_in_in i_payment_pop_box">
       <div class="i_modal_content">
           <div class="payClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
           <div class="i_subscribing" style="background-image:url(<?php echo iN_HelpSecure($f_profileAvatar);?>);"></div>
           <div class="i_subscribing_note" id="pln" data-p="<?php echo iN_HelpSecure($planID);?>">
              <?php echo preg_replace( '/{.*?}/', $f_userfullname, $LANG['subscription_payment']); ?>
           </div>
           <form id="paymentFrm" novalidate>
           <div class="i_credit_card_form">
                <div id="paymentResponse"></div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['card_holder']);?></label>
                    <div class="form-control">
                        <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('70'));?></div>
                       <input type="text" id="cname" name="cardname" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['card_holder']);?>" autocomplete="cc-name" inputmode="text" maxlength="64" required>
                    </div>
                </div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['email']);?></label>
                    <div class="form-control">
                       <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('71'));?></div>
                       <input type="email" id="email" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['email']);?>" autocomplete="email" inputmode="email" maxlength="120" required>
                    </div>
                </div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['card_number']);?></label>
                    <div class="form-control">
                       <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('72'));?></div>
                       <input type="text" id="cardNumber" name="cardnumber" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['card_number']);?>" inputmode="numeric" autocomplete="cc-number" maxlength="19" required>
                    </div>
                </div>
                <div class="pay_form_group_plus">
                    <div class="i_form_group_plus_extra">
                        <label class="form_label"><?php echo iN_HelpSecure($LANG['expiration_date']);?></label>
                        <div class="form-control">
                            <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73'));?></div>
                            <input type="text" id="expmonth" name="expmonth" class="inora_user_input" placeholder="MM" inputmode="numeric" pattern="^(0?[1-9]|1[0-2])$" maxlength="2" required>
                        </div>
                    </div>
                    <div class="i_form_group_plus_extra">
                        <label class="form_label"><?php echo iN_HelpSecure($LANG['expiration_year']);?></label>
                        <div class="form-control">
                            <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73'));?></div>
                            <input type="text" id="expyear" name="expyear" class="inora_user_input" placeholder="YY" inputmode="numeric" pattern="^[0-9]{2}$" maxlength="2" required>
                        </div>
                    </div>
                    <div class="i_form_group_plus_extra">
                        <label class="form_label"><?php echo iN_HelpSecure($LANG['ccv_code']);?></label>
                        <div class="form-control">
                            <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('74'));?></div>
                            <input type="text" id="cvv" name="cvv" class="inora_user_input" placeholder="123" inputmode="numeric" pattern="^[0-9]{3,4}$" maxlength="4" required>
                        </div>
                    </div>
                </div>
                <div class="pay_form_group">
                    <button type="button" class="pay_subscription transition" aria-live="polite" data-label-pay="<?php echo iN_HelpSecure($LANG['pay']).' '.iN_HelpSecure($currencys[$stripeCurrency]).$f_PlanAmount; ?>" data-label-processing="<?php echo iN_HelpSecure($LANG['processing'] ?? 'Processing...');?>"><?php echo iN_HelpSecure($LANG['pay']);?> <?php echo iN_HelpSecure($currencys[$stripeCurrency]).$f_PlanAmount;?></button>
                </div>
                <div class="pay_form_group">
                   <div class="i_pay_note"><?php echo iN_HelpSecure($LANG['subscription_renew']);?></div>
                </div>
           </div>
           </form>
           <script src="https://js.stripe.com/v3"></script>
       </div>
   </div>
</div>
<?php } ?>
