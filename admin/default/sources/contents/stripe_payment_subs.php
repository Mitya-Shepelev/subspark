<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['stripe_payment_subs']); ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="i_general_row_box_item flex_ tabing__justify">
                <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['subscription_feature_status']); ?></div>
                <div class="irow_box_right">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="stripe_sub_status">
                            <input type="checkbox" name="stripe_sub_status" class="chmdSubPayment" id="stripe_sub_status" <?php echo iN_HelpSecure($stripeStatus) == '1' ? 'value="2" checked="checked"' : 'value="1"'; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="success_tick tabing flex_ sec_one stripe_sub_status">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                        </div>
                    </div>
                    <div class="rec_not box_not_padding_left">
                        <?php echo iN_HelpSecure($LANG['no_one_will_make_new_subs']); ?>
                    </div>
                </div>
            </div>

            <form enctype="multipart/form-data" method="post" id="updateSubsPaymentGataway">
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['stripe_webhook_secret']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="stripeWebhookSecret" class="i_input flex_" value="<?php echo iN_HelpSecure(isset($stripeWebhookSecret) ? $stripeWebhookSecret : ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['stripe_test_secret_key']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="subSecretKey" class="i_input flex_" value="<?php echo iN_HelpSecure($stripeKey); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['stripe_test_public_key']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="subPublicKey" class="i_input flex_" value="<?php echo iN_HelpSecure($stripePublicKey); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['stripe_crncy']); ?></div>
                    <div class="irow_box_right">
                        <div class="i_box_limit flex_ column">
                            <div class="i_limit" data-type="fl_limit">
                                <span class="lmt"><?php echo iN_HelpSecure($stripeCurrency) . '(' . $currencys[$stripeCurrency] . ')'; ?></span>
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
                            </div>
                            <div class="i_limit_list_container">
                                <div class="i_countries_list border_one column flex_">
                                    <?php foreach ($currencys as $crncy => $value) { ?>
                                        <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($stripeCurrency) == $crncy ? 'choosed' : ''; ?>" id="<?php echo iN_HelpSecure($crncy); ?>" data-c="<?php echo iN_HelpSecure($crncy) . '(' . $value . ')'; ?>" data-type="mb_limit">
                                            <?php echo iN_HelpSecure($crncy) . '(' . $value . ')'; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <input type="hidden" name="stripe_currency" id="upLimit" value="<?php echo iN_HelpSecure($stripeCurrency); ?>">
                            </div>
                            <div class="rec_not box_not_padding_top">
                                <?php echo iN_HelpSecure($LANG['make_sure_for_stripe']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="i_settings_wrapper_item successNot">
                    <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
                </div>

                <div class="admin_approve_post_footer">
                    <div class="i_become_creator_box_footer">
                        <input type="hidden" name="f" value="updateSubStripe">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
                            <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
