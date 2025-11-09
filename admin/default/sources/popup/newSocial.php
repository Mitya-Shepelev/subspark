<div class="i_modal_bg_in">
    <div class="i_modal_in_in">
        <div class="i_modal_content general_conf">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['create_new_social_site']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <form enctype="multipart/form-data" method="post" id="newSocialSiteForm">
                <div class="i_plnn_container flex_ i_plnn_container_st">
                    <input type="text" name="social_site" class="point_input" placeholder="<?php echo iN_HelpSecure($LANG['social_site_link_ex']); ?>">
                </div>
                <div class="i_plnn_container flex_ i_plnn_container_st">
                    <input type="text" name="socail_key" class="point_input" placeholder="<?php echo iN_HelpSecure($LANG['social_site_key']); ?>">
                </div>

                <div class="i_editsvg_code flex_ tabing">
                    <textarea class="svg_more_textarea" name="socialsvgcode" placeholder="<?php echo iN_HelpSecure($LANG['social_site_icon_code']); ?>"></textarea>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_right">
                        <div class="i_checkbox_wrapper flex_ tabing_non_justify">
                            <div class="i_chck_text box_not_padding_right"><?php echo iN_HelpSecure($LANG['status']); ?></div>
                            <label class="el-switch el-switch-yellow" for="socialsitestatus">
                                <input type="checkbox" name="socialsitestatus" class="chmdAnnouncementStatus" id="socialsitestatus" value="yes" checked="checked">
                                <span class="el-switch-style"></span>
                            </label>
                            <input type="hidden" name="socialsitestatus" class="socialsitestatus" value="yes">
                            <div class="success_tick tabing flex_ sec_one socialsitestatus">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="warning_wrapper papk_wraning box_not_padding_left_plus_ten">
                    <?php echo iN_HelpSecure($LANG['please_use_svg_code']); ?>
                </div>
                <div class="warning_wrapper warning_one box_not_padding_left_plus_ten">
                    <?php echo iN_HelpSecure($LANG['full_for_register']); ?>
                </div>

                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="f" value="newSocialSite">
                    <div class="popupSaveButton transition">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                            <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                        </button>
                    </div>
                    <div class="alertBtnLeft no-del transition">
                        <?php echo iN_HelpSecure($LANG['cancel']); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/newSocialSiteHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
</div> 