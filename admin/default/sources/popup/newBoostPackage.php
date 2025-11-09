<div class="i_modal_bg_in">
   <div class="i_modal_in_in">
       <div class="i_modal_content">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['create_a_new_package']);?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?>
                </div>
            </div>
            <form enctype="multipart/form-data" method="post" id="newBoostPackageForm">
                <div class="i_editsvg_code flex_ tabing">
                    <div class="i_p_e_body editAds_padding">
                        <div class="general_warning">
                            <div class="border_one c3 flex_ tabing_non_justify editAds_padding">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60'));?>
                                <?php echo iN_HelpSecure($LANG['must_contain_all_plan_informations']);?>
                            </div>
                        </div>

                        <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['new_plan_key']);?></div>
                        <div class="i_plnn_container flex_">
                            <input type="text" name="planKey" class="point_input" placeholder="<?php echo iN_HelpSecure($LANG['plan_key_ex']);?>">
                        </div>
                        <div class="warning_wrapper pk_wraning"><?php echo iN_HelpSecure($LANG['plan_key_warning']);?></div>

                        <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['view_time']);?></div>
                        <div class="i_plnn_container i_plnn_container_t flex_">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('122'));?>
                            <input type="text" name="planViewTime" class="point_input newboleft" pattern="[0-9.]*" inputmode="decimal" placeholder="<?php echo iN_HelpSecure($LANG['plan_point_amount_ex']);?>">
                        </div>
                        <div class="warning_wrapper ppk_wraning"><?php echo iN_HelpSecure($LANG['please_fill_in_all_fields']);?></div>

                        <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['boost_amount']);?></div>
                        <div class="i_plnn_container flex_">
                            <div class="i_amount_currency"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?></div>
                            <input type="text" name="planAmount" class="point_input newboleft" pattern="[0-9.]*" inputmode="decimal" placeholder="<?php echo iN_HelpSecure($LANG['plan_amount_ex']);?>">
                        </div>
                        <div class="warning_wrapper papk_wraning"><?php echo iN_HelpSecure($LANG['please_fill_in_all_fields']);?></div>

                        <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['plan_icon']);?></div>
                        <div class="i_plnn_container flex_">
                            <textarea class="svg_more_textarea" name="newsvgcode" placeholder="<?php echo iN_HelpSecure($LANG['paste_your_svg_code_here']);?>"></textarea>
                        </div>
                    </div>
                </div>
                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="f" value="newBoostPackageForm">
                    <div class="popupSaveButton transition">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                            <?php echo iN_HelpSecure($LANG['save_edit']);?>
                        </button>
                    </div>
                    <div class="alertBtnLeft no-del transition">
                        <?php echo iN_HelpSecure($LANG['no']);?>
                    </div>
                </div>
            </form>
       </div>
   </div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/mainBoostPackageHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>

</div>