<div class="i_modal_bg_in">
    <div class="i_modal_in_in">
        <div class="i_modal_content general_conf">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['create_a_new_svg_icon']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <form enctype="multipart/form-data" method="post" id="newSVGForm">
                <div class="i_editsvg_code flex_ tabing">
                    <textarea class="svg_more_textarea" name="newsvgcode" placeholder="<?php echo iN_HelpSecure($LANG['paste_your_svg_code_here']); ?>"></textarea>
                </div>

                <div class="warning_wrapper papk_wraning box_not_padding_left_plus_ten">
                    <?php echo iN_HelpSecure($LANG['ups_you_forgot_to_add_svg_code']); ?>
                </div>
                <div class="warning_wrapper warning_one box_not_padding_left_plus_ten">
                    <?php echo iN_HelpSecure($LANG['please_use_svg_code']); ?>
                </div>

                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="f" value="newSVG">
                    <div class="popupSaveButton transition">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                            <?php echo iN_HelpSecure($LANG['save_new_svg']); ?>
                        </button>
                    </div>
                    <div class="alertBtnLeft no-del transition">
                        <?php echo iN_HelpSecure($LANG['no']); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/newSVGHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
</div> 