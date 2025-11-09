<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['minio_settings']); ?>
        </div>
        <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="storageSettings">
                <div class="i_general_row_box_item flex_ column tabing__justify">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="sstat">
                            <input type="checkbox" name="minioStatus" class="sstat" id="sstat" <?php echo iN_HelpSecure($minioStatus) == '1' ? 'value="1" checked="checked"' : 'value="0"'; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="i_chck_text"><?php echo iN_HelpSecure($LANG['minio_status']); ?></div>
                        <input type="hidden" name="minioStatus" id="stats3" value="<?php echo iN_HelpSecure($minioStatus); ?>">
                    </div>
                    <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['minio_status_not'] ?? 'MinIO etkinleştirildiğinde S3/Spaces/Wasabi yerine kullanılır.'); ?></div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['minio_endpoint']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="minioEndpoint" class="i_input flex_" placeholder="https://minio.example.com:9000" value="<?php echo iN_HelpSecure($minioEndpoint ?? ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['server_type']); ?></div>
                    <div class="irow_box_right">
                        <div class="i_box_limit flex_ column">
                            <div class="i_limit" data-type="s3update">
                                <span class="s3choosed"><?php echo iN_HelpSecure($minioRegion ?? 'us-east-1'); ?></span>
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
                            </div>
                            <div class="i_limit_list_s3_container">
                                <div class="i_countries_list border_one column flex_">
                                    <?php foreach (['us-east-1','us-west-1','eu-west-1','eu-central-1','ap-south-1','ap-southeast-1','ap-southeast-2'] as $region) { ?>
                                        <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($minioRegion ?? 'us-east-1') == $region ? 'choosed' : ''; ?>" id='<?php echo iN_HelpSecure($region); ?>' data-c="<?php echo iN_HelpSecure($region); ?>" data-type="s3set"><?php echo iN_HelpSecure($region); ?></div>
                                    <?php } ?>
                                </div>
                                <input type="hidden" name="minioRegion" id="s3region" value="<?php echo iN_HelpSecure($minioRegion ?? 'us-east-1'); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3Bucket']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="minioBucket" class="i_input flex_" value="<?php echo iN_HelpSecure($minioBucket ?? ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3Key']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="minioKey" class="i_input flex_" value="<?php echo iN_HelpSecure($minioKey ?? ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['s3sKey']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="minioSecret" class="i_input flex_" value="<?php echo iN_HelpSecure($minioSecret ?? ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['minio_public_base']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="minioPublicBase" class="i_input flex_" placeholder="(Opsiyonel) https://minio.example.com:9000/bucket/" value="<?php echo iN_HelpSecure($minioPublicBase ?? ''); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['minio_path_style']); ?></div>
                    <div class="irow_box_right">
                        <label class="el-switch el-switch-yellow" for="mpathstyle">
                            <input type="checkbox" name="minioPathStyle" id="mpathstyle" <?php echo (isset($minioPathStyle) ? (in_array((string)$minioPathStyle, ['1','true','yes']) ? 'checked="checked" value="1"' : 'value="0"') : 'checked="checked" value="1"'); ?>>
                            <span class="el-switch-style"></span>
                        </label>
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['minio_ssl_verify']); ?></div>
                    <div class="irow_box_right">
                        <label class="el-switch el-switch-yellow" for="msslverify">
                            <input type="checkbox" name="minioSslVerify" id="msslverify" <?php echo (isset($minioSslVerify) ? (in_array((string)$minioSslVerify, ['1','true','yes']) ? 'checked="checked" value="1"' : 'value="0"') : 'checked="checked" value="1"'); ?>>
                            <span class="el-switch-style"></span>
                        </label>
                    </div>
                </div>

                <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']); ?></div>
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <input type="hidden" name="f" value="MinioSettings">
                    <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings"><?php echo iN_HelpSecure($LANG['save_edit']); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
