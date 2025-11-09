<div class="i_modal_bg_in">
    <div class="i_modal_in_in">
        <div class="i_modal_content" id="general_conf">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['edit_announcement']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <form enctype="multipart/form-data" method="post" id="announcementEdit">
                <div class="announcement_type_choose">
                    <div class="irow_box_right">
                        <div class="i_box_limit flex_ column">
                            <div class="i_limit" data-type="ch_limit">
                                <span class="lct">
                                    <?php echo isset($getaData['a_who_see']) && $getaData['a_who_see'] == 'everyone' 
                                        ? iN_HelpSecure($LANG['a_share_with_everyone']) 
                                        : iN_HelpSecure($LANG['a_share_with_creators']); ?>
                                </span>
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
                            </div>
                            <div class="i_limit_list_ch_container">
                                <div class="i_countries_list border_one column flex_">
                                    <div class="i_s_limit transition border_one gsearch creators" id="creators" data-c="Share with creators" data-type="chooseAnnouncementType">
                                        Share with creators
                                    </div>
                                    <div class="i_s_limit transition border_one gsearch everyone" id="everyone" data-c="Share with Everyone" data-type="chooseAnnouncementType">
                                        Share with Everyone
                                    </div>
                                </div>
                                <input type="hidden" name="announcementType" id="upcLimit" value="<?php echo iN_HelpSecure($getaData['a_who_see'] ?? ''); ?>">
                            </div>
                            <div class="rec_not popUppadding_left_top">
                                <?php echo iN_HelpSecure($LANG['choose_who_can_see_this_announcement']); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="i_editsvg_code flex_ tabing">
                    <textarea class="svg_more_textarea" name="announcementText" placeholder="<?php echo iN_HelpSecure($LANG['wrire_announcement_description']); ?>">
<?php echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($getaData['a_text']), $base_url)); ?>
                    </textarea>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_right">
                        <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                            <div class="i_chck_text box_not_padding_right">
                                <?php echo iN_HelpSecure($LANG['status']); ?>
                            </div>
                            <label class="el-switch el-switch-yellow" for="AnnouncementStatus">
                                <input type="checkbox" name="announcementStatusa" class="chmdAnnouncementStatus" id="AnnouncementStatus" <?php echo iN_HelpSecure($getaData['a_status']) == 'yes' ? 'value="yes" checked="checked"' : 'value="no"'; ?>>
                                <span class="el-switch-style"></span>
                            </label>
                            <input type="hidden" name="announcementStatus" class="AnnouncementStatus" value="<?php echo iN_HelpSecure($getaData['a_status']); ?>">
                            <div class="success_tick tabing flex_ sec_one AnnouncementStatus">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="warning_wrapper papk_wraning box_not_padding_left">
                    <?php echo iN_HelpSecure($LANG['please_write_announcement']); ?>
                </div>

                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="f" value="announcementEdit">
                    <input type="hidden" name="aid" value="<?php echo iN_HelpSecure($getaData['a_id']); ?>">
                    <div class="popupSaveButton transition">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                            <?php echo iN_HelpSecure($LANG['save_announcement']); ?>
                        </button>
                    </div>
                    <div class="alertBtnLeft no-del transition">
                        <?php echo iN_HelpSecure($LANG['cancel']); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/announcementEditHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
</div>