<div class="i_modal_bg_in">
    <!--SHARE-->
   <div class="i_modal_in_in">
       <div class="i_modal_content" id="general_conf">
            <!--Modal Header-->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['add_new_sticker']);?>
                <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
            </div>
            <form enctype="multipart/form-data" method="post" id="newStickerForm">
            <!--/Modal Header-->
            <div class="i_editsvg_code flex_ tabing">
               <textarea class="svg_more_textarea" name="stickerURL" placeholder="<?php echo iN_HelpSecure($LANG['paste_your_sticker_url_here']);?>"></textarea>
            </div>
            <div class="warning_wrapper ppk_wraning box_not_padding_left"><?php echo iN_HelpSecure($LANG['must_be_enter_image_url_for_sticker']);?></div>
            <div class="warning_wrapper papk_wraning box_not_padding_left"><?php echo iN_HelpSecure($LANG['sticker_url_must_be']);?></div>
            <div class="warning_wrapper warning_one box_not_padding_left"><?php echo iN_HelpSecure($LANG['alert_for_sticker_url']);?></div>
            <!--Modal Header-->
            <div class="i_modal_g_footer flex_">
                <input type="hidden" name="f" value="stickerNew">
                <div class="popupSaveButton transition"><button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings"><?php echo iN_HelpSecure($LANG['save_sticker']);?></button></div>
                <div class="alertBtnLeft no-del transition"><?php echo iN_HelpSecure($LANG['no']);?></div>
            </div>
            <!--/Modal Header-->
            </form>
       </div>
   </div>
   <!--/SHARE-->
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/addStickerHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>
</div>