<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['social_logins']); ?>
        </div>
        <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="storageSettings">
                <?php
                $socialLoginList = $iN->iN_SocialLoginsList();
                if ($socialLoginList) {
                    foreach ($socialLoginList as $slData) {
                        $slID = $slData['s_id'] ?? null;
                        $sKey = $slData['s_key'] ?? null;
                        $sKeyOne = $slData['s_key_one'] ?? null;
                        $sKeyTwo = $slData['s_key_two'] ?? null;
                        $sLoginIcon = $slData['s_icon'] ?? null;
                        $sStatus = $slData['s_status'] ?? null;
                ?>
                        <div class="i_general_row_box_item flex_ tabing_non_justify">
                            <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG[$sKey]); ?> CLIEND ID</div>
                            <div class="irow_box_right">
                                <input type="text" name="<?php echo iN_HelpSecure($sKey); ?>_cliend_id" class="i_input flex_" value="<?php echo iN_HelpSecure($sKeyOne); ?>">
                            </div>
                        </div>

                        <div class="i_general_row_box_item flex_ tabing_non_justify">
                            <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG[$sKey]); ?> ICON ID</div>
                            <div class="irow_box_right">
                                <input type="text" name="<?php echo iN_HelpSecure($sKey); ?>_icon" class="i_input flex_" value="<?php echo iN_HelpSecure($sLoginIcon); ?>">
                            </div>
                        </div>

                        <div class="i_general_row_box_item flex_ tabing_non_justify">
                            <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG[$sKey]); ?> SECRET KEY</div>
                            <div class="irow_box_right">
                                <input type="text" name="<?php echo iN_HelpSecure($sKey); ?>_cliend_secret" class="i_input flex_" value="<?php echo iN_HelpSecure($sKeyTwo); ?>">
                            </div>
                        </div>

                        <div class="i_general_row_box_item flex_ tabing__justify">
                            <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG[$sKey]); ?> Status</div>
                            <div class="irow_box_right">
                                <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                                    <label class="el-switch el-switch-yellow" for="<?php echo iN_HelpSecure($sKey); ?>_status">
                                        <input type="checkbox" class="slog" data-type="<?php echo iN_HelpSecure($sKey); ?>" id="<?php echo iN_HelpSecure($sKey); ?>_status" <?php echo iN_HelpSecure($sStatus) == '1' ? 'value="1" checked="checked"' : 'value="0"'; ?>>
                                        <span class="el-switch-style"></span>
                                    </label>
                                    <input type="hidden" name="<?php echo iN_HelpSecure($sKey); ?>_status" id="<?php echo iN_HelpSecure($sKey); ?>_statuss" <?php echo iN_HelpSecure($sStatus) == '1' ? 'value="1" checked="checked"' : 'value="0"'; ?>>
                                    <div class="success_tick tabing flex_ sec_one <?php echo iN_HelpSecure($sKey); ?>_status"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?></div>
                                </div>
                                <div class="rec_not box_not_padding_left"><?php echo preg_replace('/{.*?}/', $sKey, $LANG['social_login_status_not']); ?></div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
                <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']); ?></div>
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <input type="hidden" name="f" value="sLoginSet">
                    <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings"><?php echo iN_HelpSecure($LANG['save_edit']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>