<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['manage_point_settings']);?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="i_general_row_box_item flex_ tabing_non_justify">
                <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['users_can_earn_point_status']);?></div>
                <div class="irow_box_right">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="pointSystemStatus">
                            <input type="checkbox" name="pointSystemStatus" class="chmdPost" id="pointSystemStatus" <?php echo iN_HelpSecure($earnPointSystemStatus) == 'yes' ? 'value="no" checked="checked"' : 'value="yes"';?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <input type="hidden" name="pointSystemStatus" class="pointSystemStatus" value="<?php echo iN_HelpSecure($earnPointSystemStatus);?>">
                        <div class="success_tick tabing flex_ sec_one pointSystemStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69'));?></div>
                    </div>
                </div>
            </div>
            <form enctype="multipart/form-data" method="post" id="epdSettings">
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_">How many points can 1 person earn in a day?</div>
                    <div class="irow_box_right">
                        <input type="text" name="max_point_amount" class="i_input flex_" value="<?php echo $iN->iN_Secure($maximumPointInADay);?>">
                    </div>
                </div>

                <?php
                $EarnPointData = $iN->iN_GetUserEarnPointData($userID);
                if ($EarnPointData) {
                    foreach ($EarnPointData as $EPD) {
                        $epd_ID = $EPD['i_af_id'] ?? null;
                        $epd_Type = $EPD['i_af_type'] ?? null;
                        $epd_amount = $EPD['i_af_amount'] ?? null;
                        $epd_status = $EPD['i_af_status'] ?? null;
                        $namType = 'point_' . $epd_Type;
                        $namAmount = 'point_' . $epd_Type . '_amount';
                        $valueChecked = 'value="no"';
                        if ($epd_status === 'yes') {
                            $valueChecked = 'checked="checked" value="yes"';
                        }
                ?>
                <div class="i_general_row_box_item" id="<?php echo iN_HelpSecure($epd_ID); ?>">
                    <div class="i_general_row_box_item flex_ tabing_non_justify">
                        <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG[$namType]); ?></div>
                        <div class="irow_box_right">
                            <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                                <label class="el-switch el-switch-yellow" for="<?php echo iN_HelpSecure($epd_Type); ?>SystemStatus">
                                    <input type="checkbox" name="<?php echo iN_HelpSecure($epd_Type); ?>SystemStatus" class="chmdPoint" id="<?php echo iN_HelpSecure($epd_Type); ?>SystemStatus" <?php echo html_entity_decode($valueChecked); ?>>
                                    <span class="el-switch-style"></span>
                                </label>
                                <div class="success_tick tabing flex_ sec_one <?php echo iN_HelpSecure($epd_Type); ?>SystemStatus"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="i_general_row_box_item flex_ tabing_non_justify">
                        <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG[$namAmount]); ?></div>
                        <div class="irow_box_right">
                            <input type="text" name="<?php echo iN_HelpSecure($epd_Type); ?>_amount" class="i_input flex_" value="<?php echo iN_HelpSecure($epd_amount); ?>">
                        </div>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <input type="hidden" name="f" value="epdSettings">
                    <button type="submit" name="submit" class="i_nex_btn_btn transition"><?php echo iN_HelpSecure($LANG['save_edit']); ?></button>
                </div>
            </form>
            <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']); ?></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/managePointSettingsHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>