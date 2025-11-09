<div class="i_modal_bg_in">
    <!--SHARE-->
   <div class="i_modal_in_in">
       <div class="i_modal_content general_conf">
            <!--Modal Header-->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['create_new_category']);?>
                <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
            </div>
            <!--/Modal Header-->
            <div class="i_addnew_profilecat flex_ tabing_non_justify">
               <input class="i_input_cce flex_ newCla optional_width" name="newsvgcode" placeholder="<?php echo iN_HelpSecure($LANG['new_cat_key_h']);?>">
            </div>
            <div class="warning_wrapper papk_wraning box_not_padding_left_plus_ten"><?php echo iN_HelpSecure($LANG['ups_you_forgot_to_add_new_cat_key']);?></div>
            <!--Modal Header-->
            <div class="i_modal_g_footer flex_">
                <input type="hidden" name="f" value="newPCategory">
                <div class="popupSaveButton transition"><div class="i_nex_btn_btn transition addNewPCat"><?php echo iN_HelpSecure($LANG['save_edit']);?></div></div>
                <div class="alertBtnLeft no-del transition"><?php echo iN_HelpSecure($LANG['cancel']);?></div>
            </div>
            <!--/Modal Header-->
       </div>
   </div>
   <!--/SHARE-->
</div>