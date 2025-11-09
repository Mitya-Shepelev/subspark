<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="createPaidLiveModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="createPaidLiveModalTitle">
                <?php echo iN_HelpSecure($LANG['create_a_paid_live_streaming']); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /Modal Header -->

            <!-- Modal Body -->
            <div class="i_more_text_wrapper">
                <!-- Stream Title -->
                <div class="give_a_name">
                    <?php echo iN_HelpSecure($LANG['give_this_live_stream_a_name']); ?>
                </div>
                <div class="i_live_c_item i_post_text_arrow">
                    <input type="text" name="liveName" id="liveName" class="flnm" aria-label="<?php echo iN_HelpSecure($LANG['give_this_live_stream_a_name']); ?>">
                </div>

                <!-- Stream Fee -->
                <div class="give_a_name">
                    <?php echo iN_HelpSecure($LANG['entrace_fee']); ?>
                </div>
                <div class="i_set_subscription_fee">
                    <div class="i_subs_currency">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                    </div>
                    <div class="i_subs_price">
                        <input type="text"
                               class="transition aval"
                               id="lsFee"
                               placeholder="3"
                               inputmode="decimal"
                               aria-label="<?php echo iN_HelpSecure($LANG['entrace_fee']); ?>"
                               pattern="[0-9]*"
                               value="<?php echo iN_HelpSecure($minimumLiveStreamingFee); ?>"
                               onkeypress="return event.charCode === 46 || (event.charCode >= 48 && event.charCode <= 57)">
                    </div>
                </div>

                <div class="box_not box_not_padding_left">
                    <?php echo iN_HelpSecure($LANG['point_wanted_for_streaming']); ?>
                </div>

                <!-- Warnings -->
                <div class="box_not warning_required point_warning">
                    <?php echo iN_HelpSecure($LANG['min_stream_fee']); ?>
                </div>
                <div class="box_not warning_required title_warning">
                    <?php echo iN_HelpSecure($LANG['enter_live_stream_title']); ?>
                </div>
                <div class="box_not warning_required require">
                    <?php echo iN_HelpSecure($LANG['please_fill_all_for_live_stream']); ?>
                </div>
            </div>
            <!-- /Modal Body -->

            <!-- Modal Footer -->
            <div class="i_block_box_footer_container">
                <div class="alertBtnRightWithIcon createLiveStreamPaid transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['create']); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                    <?php echo iN_HelpSecure($LANG['create']); ?>
                </div>
                <div class="alertBtnLeft no-del transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
            <!-- /Modal Footer -->
        </div>
    </div>
    <!--/SHARE-->
</div>