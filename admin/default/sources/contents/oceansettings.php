<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['digital_ocean_settings']); ?>
        </div>
        <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="storageSettings">
                <div class="i_general_row_box_item flex_ column tabing__justify">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="sstat">
                            <input type="checkbox" name="s3Status" class="sstat" id="sstat" <?php echo iN_HelpSecure($digitalOceanStatus) == '1' ? 'value="1" checked="checked"' : 'value="0"'; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="i_chck_text"><?php echo iN_HelpSecure($LANG['docean_status']); ?></div>
                        <input type="hidden" name="s3Status" id="stats3" value="<?php echo iN_HelpSecure($digitalOceanStatus); ?>">
                    </div>
                    <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['docean_status_not']); ?></div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['docean_ducket']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="docean_ducket" class="i_input flex_" value="<?php echo iN_HelpSecure($oceanspace_name); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['oceanregion']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="oceanregion" class="i_input flex_" value="<?php echo iN_HelpSecure($oceanregion); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['docean_key']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="docean_key" class="i_input flex_" value="<?php echo iN_HelpSecure($oceankey); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['oceansecret_key']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="oceansecret_key" class="i_input flex_" value="<?php echo iN_HelpSecure($oceansecret); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['storage_public_base']); ?></div>
                    <div class="irow_box_right column flex_">
                        <input type="text" name="oceanPublicBase" class="i_input flex_" placeholder="https://cdn.example.com/" value="<?php echo iN_HelpSecure($digitalOceanPublicBase ?? ''); ?>">
                        <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['storage_public_base_not']); ?></div>
                    </div>
                </div>

                <div class="i_settings_wrapper_item successNot">
                    <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <input type="hidden" name="f" value="DigitalOceanSettings">
                    <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                        <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
