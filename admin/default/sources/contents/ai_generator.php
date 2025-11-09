<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['manage_generate_ai_content']); ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="i_general_row_box_item flex_ column tabing__justify">
                <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                    <label class="el-switch el-switch-yellow" for="ai_generator_status">
                        <input type="checkbox" name="maintenancemode" class="chmd" id="ai_generator_status" <?php echo iN_HelpSecure($openAiStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
                        <span class="el-switch-style"></span>
                    </label>
                    <div class="i_chck_text">
                        <?php echo iN_HelpSecure($LANG['ai_content_generation_status']); ?>
                    </div>
                    <div class="success_tick tabing flex_ sec_one ai_generator_status">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                    </div>
                </div>
            </div>

            <form enctype="multipart/form-data" method="post" id="updateAiCredit">
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_">
                        <?php echo iN_HelpSecure($LANG['openai_key']); ?>
                    </div>
                    <div class="irow_box_right">
                        <input type="text" name="apiKey" class="i_input flex_" value="<?php echo iN_HelpSecure($opanAiKey); ?>">
                        <div class="rec_not box_not_padding_top">
                            <a href="https://platform.openai.com/api-keys" target="blank_">Get OpenAI API Key</a>
                        </div>
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_">
                        <?php echo iN_HelpSecure($LANG['credit_to_be_deducted_one_single_use']); ?>
                    </div>
                    <div class="irow_box_right">
                        <input type="number" name="perAmount" class="i_input flex_" value="<?php echo iN_HelpSecure($perAiUse); ?>">
                        <div class="rec_not box_not_padding_top">
                            <?php echo iN_HelpSecure($LANG['one_time_usage']); ?>
                        </div>
                    </div>
                </div>

                <div class="i_settings_wrapper_item successNot">
                    <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
                </div>

                <div class="admin_approve_post_footer">
                    <div class="i_become_creator_box_footer">
                        <input type="hidden" name="f" value="updateAiCredit">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
                            <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>