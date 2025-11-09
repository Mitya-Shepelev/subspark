<div class="i_modal_bg_in">
<?php
$lngData = $iN->iN_GetLangDetails($langID);
$languageID = $lngData['lang_id'] ?? null;
$languageName = $lngData['lang_name'] ?? null;
$languageStatus = $lngData['lang_status'] ?? null;
?>
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['edit_language']).'('.$LANGNAME[$languageName].')';?>
                <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?></div>
            </div>
            <form enctype="multipart/form-data" method="post" id="editLanguage">
                <div class="i_editsvg_code flex_ tabing">
                    <div class="i_p_e_body editAds_padding">
                        <div class="general_warning_lang">
                            <div class="border_one c3 tabing_non_justify editAds_padding">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60')); ?>
                                <?php echo iN_HelpSecure($LANG['not_mach_abbreviation']); ?>
                            </div>
                        </div>
                        <div class="general_warning">
                            <div class="border_one c3 flex_ tabing_non_justify editAds_padding">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60')); ?>
                                <?php echo iN_HelpSecure($LANG['lang_global_warning']); ?>
                            </div>
                        </div>
                        <div class="add_app_not_point">
                            <?php echo isset($LANG['edit_lang_not']) ? $LANG['edit_lang_not'] : 'NaN'; ?>
                        </div>
                        <div class="add_app_not_point">
                            <?php echo iN_HelpSecure($LANG['lang_abbreviation']); ?>
                        </div>
                        <div class="i_plnn_container flex_">
                            <input type="text" name="langabbreviationName" class="point_input" placeholder="<?php echo iN_HelpSecure($LANG['lang_abbreviation_ex']); ?>" value="<?php echo iN_HelpSecure($languageName); ?>">
                        </div>
                        <div class="warning_wrapper ppk_wraning">
                            <?php echo iN_HelpSecure($LANG['lang_abbreviation_warning']); ?>
                        </div>
                    </div>
                </div>
                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="f" value="editLanguage">
                    <input type="hidden" name="id" value="<?php echo iN_HelpSecure($languageID); ?>">
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
    <script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/editLanguageHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>
</div>