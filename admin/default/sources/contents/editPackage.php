<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify white_board_style">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['edit_package']); ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding" id="general_conf">
            <div class=""></div>

            <form enctype="multipart/form-data" method="post" id="editPointPackage">
                <?php
                $planData    = $iN->GetPlanDetails($planID);
                $planID      = $planData['plan_id'] ?? null;
                $planNameKey = $planData['plan_name_key'] ?? null;
                $planPoint   = $planData['plan_amount'] ?? null;
                $planStatus  = $planData['plan_status'] ?? null;
                $planAmount  = $planData['amount'] ?? null;
                ?>

                <div class="i_p_e_body editAds_padding">
                    <div class="warning_wrapper pk_wraning">
                        <?php echo iN_HelpSecure($LANG['all_fields_must_be_filled']); ?>
                    </div>

                    <div class="add_app_not_point">
                        <?php echo isset($LANG['plan_key']) ? $LANG['plan_key'] : 'NaN'; ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <input type="text" name="planKey" class="point_input" value="<?php echo iN_HelpSecure($planNameKey); ?>">
                    </div>

                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['plan_point']); ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                        <input type="text" name="planPoint" class="point_input white_board_padding_left" onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)" value="<?php echo iN_HelpSecure($planPoint); ?>">
                    </div>

                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['plan_fee']); ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <div class="i_amount_currency">
                            <?php echo iN_HelpSecure($currencys[$defaultCurrency]); ?>
                        </div>
                        <input type="text" name="pointAmount" class="point_input white_board_padding_left" onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)" value="<?php echo iN_HelpSecure($planAmount); ?>">
                    </div>

                    <div class="i_become_creator_box_footer">
                        <input type="hidden" name="f" value="editPlan">
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