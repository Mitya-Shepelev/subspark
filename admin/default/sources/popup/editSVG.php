<div class="i_modal_bg_in">
    <div class="i_modal_in_in">
        <div class="i_modal_content general_conf" id="svgEditContainer">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['edit_svg_code']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <div class="editing_svg_icon flex_ tabing">
                <?php echo html_entity_decode($getIconData); ?>
            </div>
            <form enctype="multipart/form-data" method="post" id="svgEditForm">
                <div class="i_editsvg_code flex_ tabing">
                    <textarea class="svg_more_textarea" name="svgcode" placeholder="<?php echo iN_HelpSecure($LANG['paste_your_svg_code_here']); ?>"><?php echo html_entity_decode($getIconData); ?></textarea>
                </div>
                <div class="warning_wrapper warning_svg_required box_not_padding_left">
                    <?php echo iN_HelpSecure($LANG['please_use_svg_code']); ?>
                </div>
                <div class="warning_wrapper warning_svg_empty box_not_padding_left">
                    <?php echo iN_HelpSecure($LANG['ups_you_forgot_to_add_svg_code']); ?>
                </div>
                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="iconid" value="<?php echo iN_HelpSecure($cID); ?>">
                    <input type="hidden" name="f" value="editedSVG">
                    <div class="popupSaveButton transition">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                            <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                        </button>
                    </div>
                    <div class="alertBtnLeft no-del transition">
                        <?php echo iN_HelpSecure($LANG['no']); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/editSvgHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>

</div>