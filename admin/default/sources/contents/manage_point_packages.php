<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['point_packages_settings']); ?>
        </div>

        <div class="i_general_row_box column flex_ box_not_padding_top_package" id="general_conf">
            <div class="new_svg_icon_wrapper">
                <div class="inline_block">
                    <div class="flex_ tabing_non_justify newSvgCode newCreate border_one" data-type="newPackage">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                        <?php echo iN_HelpSecure($LANG['create_a_new_package']); ?>
                    </div>
                </div>
            </div>

            <div class="buyCreditWrapper flex_ tabing">
                <?php
                if ($planTableList) {
                    foreach ($planTableList as $planData) {
                        $planID            = $planData['plan_id'] ?? null;
                        $planName          = $planData['plan_name_key'] ?? null;
                        $planCreditAmount  = $planData['plan_amount'] ?? null;
                        $planAmount        = $planData['amount'] ?? null;
                        $editPlanLink      = $base_url . 'admin/manage_point_packages?id=' . $planID;
                        $planStatus        = $planData['plan_status'] ?? null;
                        ?>
                        <div class="credit_plan_box" id="<?php echo iN_HelpSecure($planID); ?>">
                            <div class="plan_box tabing flex_" id="p_i_<?php echo iN_HelpSecure($planID); ?>">
                                <div class="plan_name flex_">
                                    <?php echo isset($LANG[$planName]) ? $LANG[$planName] : $planName; ?>
                                </div>
                                <div class="plan_value">
                                    <div class="plan_price tabing">
                                        <div class="positionRelative display_initial">
                                            <?php echo number_format($planCreditAmount); ?>
                                            <span class="plan_point_icon">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="plan_point tabing flex_">
                                        <?php echo iN_HelpSecure($LANG['points']); ?>
                                    </div>
                                    <div class="purchaseButton flex_ tabing">
                                        <?php echo iN_HelpSecure($LANG['purchase']); ?>
                                        <strong class="tabing flex_ inline_flexing">
                                            <?php echo number_format($planCreditAmount); ?>
                                            <span class="prcsic">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                            </span>
                                        </strong>
                                        <div class="foramount">
                                            <?php echo iN_HelpSecure($LANG['for']) . ' ' . $currencys[$defaultCurrency] . number_format($planAmount); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tabing flex_ edit_active_delete">
                                    <div class="ecd_item">
                                        <div class="ecd_item_in flex_ tabing">
                                            <div class="i_checkbox_wrapper flex_ tabing_non_justify box_padding_d">
                                                <label class="el-switch el-switch-yellow" for="planStatus<?php echo iN_HelpSecure($planID); ?>">
                                                    <input type="checkbox"
                                                        class="pstat"
                                                        id="planStatus<?php echo iN_HelpSecure($planID); ?>"
                                                        data-id="<?php echo iN_HelpSecure($planID); ?>"
                                                        data-type="planStatus"
                                                        <?php echo iN_HelpSecure($planStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
                                                    <span class="el-switch-style"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="ecd_item flex_ tabing">
                                        <a href="<?php echo iN_HelpSecure($editPlanLink, FILTER_VALIDATE_URL); ?>">
                                            <div class="ecd_item_in flex_ tabing edit_plan border_one c2">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')); ?>
                                                <?php echo iN_HelpSecure($LANG['edit_plan']); ?>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="ecd_item flex_ tabing">
                                        <div class="ecd_item_in flex_ tabing delete_plan border_one c3" id="<?php echo iN_HelpSecure($planID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
                                            <?php echo iN_HelpSecure($LANG['delete_plan']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>