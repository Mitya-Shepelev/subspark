<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="sendTipModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in modal_tip">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="sendTipModalTitle">
                <?php echo iN_HelpSecure(preg_replace('/{.*?}/', $f_userfullname, $LANG['send_tip_to'])); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="i_more_text_wrapper">
                <div class="i_set_subscription_fee_box">
                    <div class="i_set_subscription_fee" id="<?php echo iN_HelpSecure($tipingUserID); ?>">
                        <div class="i_subs_currency">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                        </div>
                        <div class="i_subs_price">
                            <input type="text"
                                   class="transition aval border-right-radius"
                                   id="tipVal"
                                   inputmode="decimal"
                                   placeholder="<?php echo iN_HelpSecure($LANG['amount_in_points']); ?>"
                                   aria-label="<?php echo iN_HelpSecure($LANG['amount_in_points']); ?>" />
                        </div>
                    </div>
                    <div class="i_tip_not">
                        <?php echo iN_HelpSecure($LANG['min_tip_amount']); ?>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="i_block_box_footer_container">
                <div class="send_tip_btn_profile"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['send_your_tip']); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                    <?php echo iN_HelpSecure($LANG['send_your_tip']); ?>
                </div>
            </div>
        </div>
    </div>
    <!--/SHARE-->
</div>