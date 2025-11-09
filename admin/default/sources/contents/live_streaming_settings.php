<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['live_streaming_settings']); ?>
        </div>
        <div class="i_general_row_box column flex_" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="liveSettings">
                <div class="i_general_row_box_item flex_ column tabing__justify">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="sstat">
                            <input type="checkbox" name="s3Status" class="sstat" id="sstat" <?php echo iN_HelpSecure($agoraStatus) == '1' ? 'value="1" checked="checked"' : 'value="0"'; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="i_chck_text"><?php echo iN_HelpSecure($LANG['live_s_status']); ?></div>
                        <input type="hidden" name="s3Status" id="stats3" value="<?php echo iN_HelpSecure($agoraStatus); ?>">
                    </div>
                    <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['live_s_not']); ?></div>
                </div>

                <div class="i_general_row_box_item flex_ column tabing__justify">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="sfstat">
                            <input type="checkbox" name="sPlStatus" class="sfstat" id="sfstat" <?php echo iN_HelpSecure($freeLiveStreamingStatus) == '1' ? 'value="1" checked="checked"' : 'value="0"'; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="i_chck_text"><?php echo iN_HelpSecure($LANG['free_live_streaming_status']); ?></div>
                        <input type="hidden" name="sPlStatus" id="sftats3" value="<?php echo iN_HelpSecure($freeLiveStreamingStatus); ?>">
                    </div>
                    <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['free_live_streaming_status_not']); ?></div>
                </div>

                <div class="i_general_row_box_item flex_ column tabing__justify">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                        <label class="el-switch el-switch-yellow" for="spstat">
                            <input type="checkbox" name="sflStatus" class="spstat" id="spstat" <?php echo iN_HelpSecure($paidLiveStreamingStatus) == '1' ? 'value="1" checked="checked"' : 'value="0"'; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="i_chck_text"><?php echo iN_HelpSecure($LANG['paid_live_streaming_status']); ?></div>
                        <input type="hidden" name="sflStatus" id="sptats3" value="<?php echo iN_HelpSecure($paidLiveStreamingStatus); ?>">
                    </div>
                    <div class="rec_not box_not_padding_left"><?php echo iN_HelpSecure($LANG['paid_live_streaming_status_not']); ?></div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['free_live_stream_time']); ?></div>
                    <div class="irow_box_right">
                        <div class="i_box_limit flex_ column">
                            <div class="i_limit" data-type="cp_limit">
                                <span class="lppt"><?php echo iN_HelpSecure($freeLiveTime); ?></span>
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
                            </div>
                            <div class="i_limit_list_cp_container">
                                <div class="i_countries_list border_one column flex_">
                                    <?php foreach ($LIVETIMELIMIT as $cpLimit) { ?>
                                        <div class="i_s_limit transition border_one gsearch <?php echo iN_HelpSecure($freeLiveTime) == iN_HelpSecure($cpLimit) ? 'choosed' : ''; ?>" id="<?php echo iN_HelpSecure($cpLimit); ?>" data-c="<?php echo iN_HelpSecure($cpLimit); ?>" data-type="postLimit">
                                            <?php echo iN_HelpSecure($cpLimit); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <input type="hidden" name="post_show_limit" id="uppLimit" value="<?php echo iN_HelpSecure($freeLiveTime); ?>">
                            </div>
                            <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($LANG['not_for_time']); ?></div>
                        </div>
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['live_stream_price_point']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="liveMinPrice" class="i_input flex_" value="<?php echo iN_HelpSecure($minimumLiveStreamingFee); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['agora_app_id']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="appID" class="i_input flex_" value="<?php echo iN_HelpSecure($agoraAppID); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['agora_certificate']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="appCertificate" class="i_input flex_" value="<?php echo iN_HelpSecure($agoraCertificate); ?>">
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['agora_customer_id']); ?></div>
                    <div class="irow_box_right">
                        <input type="text" name="appCustomerID" class="i_input flex_" value="<?php echo iN_HelpSecure($agoraCustomerID); ?>">
                    </div>
                </div>

                <div class="i_settings_wrapper_item successNot">
                    <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <input type="hidden" name="f" value="updateLiveSettings">
                    <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                        <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>