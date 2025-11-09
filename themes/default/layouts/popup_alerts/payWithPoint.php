<div class="i_modal_bg_in i_subs_modal pay_zindex" role="dialog" aria-modal="true" aria-labelledby="pointSubModalTitle">
    <div class="i_modal_in_in i_payment_pop_box">
        <div class="i_modal_content bySub">
            <!-- Close Button -->
            <div class="payClose transition" role="button" aria-label="Close">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
            </div>

            <!-- Subscribing Avatar -->
            <div class="i_subscribing" style="background-image:url('<?php echo iN_HelpSecure($f_profileAvatar); ?>');" aria-hidden="true"></div>

            <!-- Subscribing Note -->
            <div class="i_subscribing_note" id="pointSubModalTitle" data-p="<?php echo iN_HelpSecure($planID); ?>">
                <?php echo preg_replace('/{.*?}/', $f_userfullname, iN_HelpSecure($LANG['subscription_payment'])); ?>
            </div>

            <!-- Payment Info -->
            <div class="i_credit_card_form">
                <div id="paymentResponse"></div>
                <div class="pay_form_group point_subs_not">
                    <?php
                    if ($planType == 'weekly') {
                        echo iN_HelpSecure($LANG['subscription_point_weekly_payment_not']);
                    } elseif ($planType == 'monthly') {
                        echo iN_HelpSecure($LANG['subscription_point_monthly_payment_not']);
                    } elseif ($planType == 'yearly') {
                        echo iN_HelpSecure($LANG['subscription_point_yearly_payment_not']);
                    }
                    ?>
                </div>
            </div>

            <!-- Payment Actions -->
            <?php if ($userCurrentPoints >= $f_PlanAmount) { ?>
                <div class="pay_form_group">
                    <div class="pay_subscription_point transition subMyPoint"
                         id="<?php echo iN_HelpSecure($planID); ?>"
                         data-u="<?php echo iN_HelpSecure($iuID); ?>"
                         role="button"
                         aria-label="<?php echo iN_HelpSecure($LANG['pay']); ?>">
                        <?php echo iN_HelpSecure($LANG['pay']) . ' ' . $f_PlanAmount . html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                    </div>
                </div>
            <?php } else { ?>
                <div class="pay_form_group">
                    <div class="pay_subscription_point_renew transition">
                        <a href="<?php echo $base_url . 'purchase/purchase_point'; ?>" role="button">
                            <?php echo iN_HelpSecure($LANG['you_dont_have_a_enough_point_to_subscribe']); ?>
                        </a>
                    </div>
                </div>
            <?php } ?>

            <!-- Error States -->
            <div class="pay_form_group cntsub nonePoint">
                <?php echo html_entity_decode($LANG['can_not_subscribe']); ?>
            </div>
            <div class="pay_from_froup insfsub nonePoint">
                <?php echo html_entity_decode($LANG['insufficient_balance']); ?>
            </div>
        </div>
    </div> 
</div>