<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify white_board_style">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['edit_package']); ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding" id="general_conf">
            <div class=""></div>
            <form enctype="multipart/form-data" method="post" id="editBoostPackage">
                <?php
                $planData      = $iN->iN_GetBoostPostDetails($planID);
                $planID        = $planData['plan_id'] ?? null;
                $planNameKey   = $planData['plan_name_key'] ?? null;
                $planAmount    = $planData['plan_amount'] ?? null;
                $planStatus    = $planData['plan_status'] ?? null;
                $planIcon      = $planData['plan_icon'] ?? null;
                $planViewTime  = $planData['view_time'] ?? null;
                ?>

                <div class="i_p_e_body editAds_padding">
                    <!-- Warning -->
                    <div class="warning_wrapper pk_wraning">
                        <?php echo iN_HelpSecure($LANG['all_fields_must_be_filled']); ?>
                    </div>

                    <!-- Plan Key -->
                    <div class="add_app_not_point">
                        <?php echo isset($LANG['plan_key']) ? $LANG['plan_key'] : 'NaN'; ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <input type="text" name="planKey" class="point_input" value="<?php echo iN_HelpSecure($planNameKey); ?>">
                    </div>

                    <!-- View Time -->
                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['view_time']); ?>
                    </div>
                    <div class="i_plnn_container i_plnn_container_t flex_">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('122')); ?>
                        <input type="text" name="planViewTime" class="point_input white_board_padding_left"
                            onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)"
                            value="<?php echo iN_HelpSecure($planViewTime); ?>">
                    </div>

                    <!-- Plan Amount -->
                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['boost_amount']); ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <div class="i_amount_currency">
                            <?php echo iN_HelpSecure($currencys[$defaultCurrency]); ?>
                        </div>
                        <input type="text" name="planAmount" class="point_input white_board_padding_left"
                            onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)"
                            value="<?php echo iN_HelpSecure($planAmount); ?>">
                    </div>

                    <!-- Plan Icon -->
                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['plan_icon']); ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <textarea class="svg_more_textarea" name="newsvgcode"
                            placeholder="<?php echo iN_HelpSecure($LANG['paste_your_svg_code_here']); ?>"><?php echo html_entity_decode($planIcon); ?></textarea>
                    </div>

                    <!-- Save Button -->
                    <div class="i_become_creator_box_footer">
                        <input type="hidden" name="f" value="editBoostPlan">
                        <input type="hidden" name="planid" value="<?php echo iN_HelpSecure($planID); ?>">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
                            <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>