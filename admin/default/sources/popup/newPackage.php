<div class="i_modal_bg_in">
    <!--SHARE-->
   <div class="i_modal_in_in">
       <div class="i_modal_content">
            <!--Modal Header-->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['create_a_new_package']);?>
                <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
            </div>
            <form enctype="multipart/form-data" method="post" id="newPackageForm">
            <!--/Modal Header-->
            <div class="i_editsvg_code flex_ tabing"> 
                <div class="i_p_e_body editAds_padding">
                    <div class="general_warning"><div class="border_one c3 flex_ tabing_non_justify editAds_padding"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60'));?><?php echo iN_HelpSecure($LANG['must_contain_all_plan_informations']);?></div></div>
                    <div class="add_app_not_point"><?php echo isset($LANG['new_plan_key']) ? $LANG['new_plan_key'] : 'NaN';?></div>
                    <div class="i_plnn_container flex_">
                        <input type="text" name="planKey" class="point_input" placeholder="<?php echo iN_HelpSecure($LANG['plan_key_ex']);?>">
                    </div>
                    <div class="warning_wrapper pk_wraning"><?php echo iN_HelpSecure($LANG['plan_key_warning']);?></div>
                    <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['plan_point']);?></div>
                    <div class="i_plnn_container flex_">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?>
                        <input type="text" name="planPoint" class="point_input white_board_padding_left" onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)" placeholder="<?php echo iN_HelpSecure($LANG['plan_point_amount_ex']);?>">
                    </div>
                    <div class="warning_wrapper ppk_wraning"><?php echo preg_replace( '/{.*?}/', $minimumPointLimit, $LANG['plan_point_warning']);?></div>

                    <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['plan_fee']);?></div>
                    <div class="i_plnn_container flex_">
                        <div class="i_amount_currency"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?></div>
                        <input type="text" name="pointAmount" class="point_input white_board_padding_left" onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)" placeholder="<?php echo iN_HelpSecure($LANG['plan_amount_ex']);?>">
                    </div>
                    <div class="warning_wrapper papk_wraning"><?php echo preg_replace( '/{.*?}/', $maximumPointAmountLimit, $LANG['plan_point_amount_warning']);?></div>
                </div> 
            </div>
            <!--Modal Header-->
            <div class="i_modal_g_footer flex_">
                <input type="hidden" name="f" value="newPackageForm">
                <div class="popupSaveButton transition"><button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings"><?php echo iN_HelpSecure($LANG['save_edit']);?></button></div>
                <div class="alertBtnLeft no-del transition"><?php echo iN_HelpSecure($LANG['no']);?></div>
            </div>
            <!--/Modal Header-->
            </form>
       </div>
   </div>
   <!--/SHARE-->
   <script src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/newPackageHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
</div>