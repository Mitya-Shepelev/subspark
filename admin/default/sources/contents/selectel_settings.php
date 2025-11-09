<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['selectel_settings'] ?? 'Selectel S3 Storage'); ?>
        </div>
        <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="storageSettings">
                <div class="i_general_row_box_item flex_ column tabing__justify">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="sstat">
                            <input type="checkbox" name="selectelStatus" class="sstat" id="sstat" <?php echo iN_HelpSecure($selectelStatus) == '1' ? 'value="1" checked="checked"' : 'value="0"'; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="i_chck_text"><?php echo iN_HelpSecure($LANG['selectel_status'] ?? 'Selectel S3 Status'); ?></div>
                        <input type="hidden" name="selectelStatus" id="stats3" value="<?php echo iN_HelpSecure($selectelStatus); ?>">
                    </div>
                    <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['selectel_status_not'] ?? 'When enabled, Selectel S3 will be used instead of other storage providers.'); ?></div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['selectel_endpoint'] ?? 'Endpoint'); ?></div>
                    <div class="irow_box_right column flex_">
                        <input type="text" name="selectelEndpoint" class="i_input flex_" placeholder="https://s3.selcdn.ru" value="<?php echo iN_HelpSecure($selectelEndpoint ?? 'https://s3.selcdn.ru'); ?>" readonly style="background-color: #f5f5f5;">
                        <div class="rec_not box_not_padding_left"><strong>⚠️ Не изменяйте!</strong> Правильный endpoint: https://s3.selcdn.ru</div>
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['server_type'] ?? 'Region'); ?></div>
                    <div class="irow_box_right column flex_">
                        <input type="text" name="selectelRegion" class="i_input flex_" value="<?php echo iN_HelpSecure($selectelRegion ?? 'ru-1'); ?>" readonly>
                        <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['selectel_region_not'] ?? 'Selectel uses ru-1 region'); ?></div>
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3Bucket'] ?? 'Container Name'); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="selectelBucket" class="i_input flex_" value="<?php echo iN_HelpSecure($selectelBucket ?? ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3Key'] ?? 'Access Key'); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="selectelKey" class="i_input flex_" value="<?php echo iN_HelpSecure($selectelKey ?? ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3sKey'] ?? 'Secret Key'); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="selectelSecret" class="i_input flex_" value="<?php echo iN_HelpSecure($selectelSecret ?? ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['storage_public_base'] ?? 'Public Base URL'); ?></div>
                    <div class="irow_box_right column flex_">
                        <input type="text" name="selectelPublicBase" class="i_input flex_" placeholder="https://123456.selcdn.ru/container-name/" value="<?php echo iN_HelpSecure($selectelPublicBase ?? ''); ?>">
                        <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['selectel_public_base_not'] ?? 'Optional: CDN URL for your container (e.g., https://123456.selcdn.ru/container-name/)'); ?></div>
                    </div>
                </div>

                <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']); ?></div>
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <input type="hidden" name="f" value="SelectelSettings">
                    <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings"><?php echo iN_HelpSecure($LANG['save_edit']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
