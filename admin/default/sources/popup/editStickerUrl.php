<div class="i_modal_bg_in">
    <div class="i_modal_in_in">
        <div class="i_modal_content" id="stickerEditContainer">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['edit_sticker']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <div class="editing_svg_icon flex_ tabing">
                <img src="<?php echo iN_HelpSecure($getSData['sticker_url'], FILTER_VALIDATE_URL); ?>">
            </div>
            <form enctype="multipart/form-data" method="post" id="stickerEdit">
                <div class="i_editsvg_code flex_ tabing">
                    <textarea class="svg_more_textarea" name="stickerURL" placeholder="<?php echo iN_HelpSecure($LANG['paste_your_sticker_url_here']); ?>"><?php echo iN_HelpSecure($getSData['sticker_url'], FILTER_VALIDATE_URL); ?></textarea>
                </div>
                <div class="warning_wrapper warning_svg box_not_padding_left">
                    <?php echo iN_HelpSecure($LANG['must_be_enter_image_url_for_sticker']); ?>
                </div>
                <div class="warning_wrapper warning_format box_not_padding_left">
                    <?php echo iN_HelpSecure($LANG['sticker_url_must_be']); ?>
                </div>
                <div class="warning_wrapper warning_empty box_not_padding_left">
                    <?php echo iN_HelpSecure($LANG['alert_for_sticker_url']); ?>
                </div>
                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="sid" value="<?php echo iN_HelpSecure($getSData['sticker_id']); ?>">
                    <input type="hidden" name="f" value="stickerEdit">
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
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/editStickerHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>
</div>