<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['general_settings']); ?>
        </div>
        <div class="i_general_row_box column flex_" id="general_conf">
            <div class="warning_">
                <?php echo iN_HelpSecure($LANG['noway_desc']); ?>
            </div>

            <div class="i_general_row_box_item flex_ tabing_non_justify">
                <div class="irow_box_left tabing flex_">
                    <?php echo iN_HelpSecure($LANG['main_language']); ?>
                </div>
                <div class="irow_box_right">
                    <div class="i_box_limit flex_ column">
                        <div class="i_limit" data-type="chm_limit">
                            <span class="lclt"><?php echo iN_HelpSecure($LANGNAME[$defaultLanguage]); ?></span>
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
                        </div>
                        <div class="i_limit_list_cp_container">
                            <div class="i_countries_list border_one column flex_">
                                <?php foreach ($languages as $lang) { ?>
                                    <div class="i_s_limit transition border_one gsearch setDefault <?php echo iN_HelpSecure($defaultLanguage) == '' . $lang['lang_name'] . '' ? 'choosed' : ''; ?>" id="<?php echo iN_HelpSecure($lang['lang_id']); ?>" data-c="<?php echo iN_HelpSecure($LANGNAME[$lang['lang_name']]); ?>" data-type="language">
                                        <?php echo iN_HelpSecure($LANGNAME[$lang['lang_name']]); ?>
                                    </div>
                                <?php } ?>
                            </div>
                            <input type="hidden" name="main_lang" id="upcmLimit" value="<?php echo iN_HelpSecure($availableLength); ?>">
                        </div>
                        <div class="rec_not box_not_padding_top">
                            <?php echo iN_HelpSecure($LANG['main_lang_not']); ?>
                        </div>
                        <div class="success_tick tabing flex_ sec_one up_lng success_tick_style">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            $toggles = [
                ['id' => 'detect_lang_status', 'name' => 'maintenancemode', 'status' => $autoDetectLanguageStatus, 'label' => $LANG['auto_detect_language'], 'desc' => $LANG['auto_detect_language_not']],
                ['id' => 'maintenance_status', 'name' => 'maintenancemode', 'status' => $maintenanceMode, 'label' => $LANG['maintenance_mode'], 'desc' => $LANG['maintenance_mode_not']],
                ['id' => 'email_verification_status', 'name' => 'email_verification_status', 'status' => $emailSendStatus, 'label' => $LANG['email_verification_status'], 'desc' => $LANG['email_verification_not']],
                ['id' => 'send__email', 'name' => 'send__email', 'status' => $sendEmailForAll, 'label' => $LANG['send__email'], 'desc' => $LANG['send__email_not']],
                ['id' => 'register_new', 'name' => 'register_new', 'status' => $userCanRegister, 'label' => $LANG['register'], 'desc' => $LANG['allow_disallow_register']],
                ['id' => 'ipLimit', 'name' => 'ipLimit', 'status' => $ipLimitStatus, 'label' => $LANG['ip_limit'], 'desc' => $LANG['allow_disallow_register']]
            ];

            foreach ($toggles as $toggle) {
                $checked = iN_HelpSecure($toggle['status']) == '1' ? 'value="0" checked="checked"' : 'value="1"';
                ?>
                <div class="i_general_row_box_item flex_ column tabing__justify">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="<?php echo $toggle['id']; ?>">
                            <input type="checkbox" name="<?php echo $toggle['name']; ?>" class="chmd" id="<?php echo $toggle['id']; ?>" <?php echo $checked; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="i_chck_text">
                            <?php echo iN_HelpSecure($toggle['label']); ?>
                        </div>
                        <div class="success_tick tabing flex_ sec_one <?php echo $toggle['id']; ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                        </div>
                    </div>
                    <div class="rec_not box_not_padding_left">
                        <?php echo iN_HelpSecure($toggle['desc']); ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>