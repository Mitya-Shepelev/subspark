<div class="i_contents_container general_top">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['profile_categories_and_subcategories_set']); ?>
        </div>

        <div class="i_general_row_box column flex_" id="business_conf">
            <div class="new_svg_icon_wrapper">
                <div class="inline_block">
                    <div class="flex_ tabing_non_justify newSvgCode newCreate border_one" data-type="newProfileCategory">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')) . iN_HelpSecure($LANG['create_new_category']); ?>
                    </div>
                </div>
            </div>

            <?php
            $categories = $iN->iN_GetCategoriesForAdmin();
            if ($categories) {
                foreach ($categories as $caData) {
                    $categoryID = $caData['c_id'] ?? null;
                    $categoryKey = $caData['c_key'] ?? null;
                    $categoryStatus = $caData['c_status'] ?? null;
                    $checkAndGetSubCat = $iN->iN_CheckAndGetSubCatForAdmin($categoryID);
            ?>
            <div class="i_general_row_box_item flex_ tabing_non_justify typing_textarea_story" id="gen_<?php echo iN_HelpSecure($categoryID); ?>">
                <div class="irow_box_left">
                    <?php echo isset($PROFILE_CATEGORIES[$categoryKey]) ? $PROFILE_CATEGORIES[$categoryKey] : preg_replace('/{.*?}/', $categoryKey, $LANG['add_this_not_for_key']); ?>
                    <span class="catKeySyle">(<?php echo iN_HelpSecure($categoryKey); ?>)</span>
                </div>
                <div class="irow_box_right">
                    <div class="i_checkbox_wrapper flex_ tabing_non_justify dis_block cse_i_<?php echo iN_HelpSecure($categoryID); ?>">
                        <label class="el-switch el-switch-yellow" for="<?php echo iN_HelpSecure($categoryKey); ?>">
                            <input type="checkbox" name="<?php echo iN_HelpSecure($categoryKey); ?>" class="cmdCatMod ca_<?php echo iN_HelpSecure($categoryKey); ?>" data-id="<?php echo iN_HelpSecure($categoryID); ?>" id="<?php echo iN_HelpSecure($categoryKey); ?>" <?php echo iN_HelpSecure($categoryStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
                            <span class="el-switch-style"></span>
                        </label>
                        <div class="success_tick tabing flex_ sec_one mysuc_<?php echo iN_HelpSecure($categoryID); ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                        </div>
                    </div>
                    <div class="flex_ tabing cat_se cse_<?php echo iN_HelpSecure($categoryID); ?>">
                        <input type="text" class="i_input_cce flex_" id="cat_va_<?php echo iN_HelpSecure($categoryID); ?>" value="<?php echo iN_HelpSecure($categoryKey); ?>">
                    </div>
                </div>
                <div class="seePost c2 border_one transition tabing flex_ s_h_<?php echo iN_HelpSecure($categoryID); ?> svcEdt nonePoint" id="<?php echo iN_HelpSecure($categoryID); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')) . $LANG['save_edit']; ?>
                </div>
                <div class="seePost c8 border_one transition tabing flex_ edtt_cat_<?php echo iN_HelpSecure($categoryID); ?> edittCat" id="<?php echo iN_HelpSecure($categoryID); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_this']; ?>
                </div>
                <div class="seePost c7 border_one transition tabing flex_ del_this_cat" id="<?php echo iN_HelpSecure($categoryID); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['delete']; ?>
                </div>
            </div>

            <div class="i_general_row_item flex_ tabing_non_justify">
                <div class="irow_box_left">
                    Sub Categories for <?php echo isset($PROFILE_CATEGORIES[$categoryKey]) ? $PROFILE_CATEGORIES[$categoryKey] : preg_replace('/{.*?}/', $categoryKey, $LANG['add_this_not_for_key']); ?>
                </div>
                <div class="irow_box_right subCat border_one">
                    <?php
                    if ($checkAndGetSubCat) {
                        foreach ($checkAndGetSubCat as $scaData) {
                            $subCategoryKey = $scaData['sc_key'] ?? null;
                            $scID = $scaData['sc_id'] ?? null;
                            $scStatus = $scaData['sc_status'] ?? null;
                    ?>
                    <div class="cat_sub_item flex_" id="general_conf<?php echo iN_HelpSecure($scID); ?>">
                        <div class="flex_ tabing sc_<?php echo iN_HelpSecure($scID); ?>">
                            <?php echo isset($PROFILE_SUBCATEGORIES[$subCategoryKey]) ? $PROFILE_SUBCATEGORIES[$subCategoryKey] : preg_replace('/{.*?}/', $subCategoryKey, $LANG['add_lang_key_not']); ?>
                        </div>
                        <div class="flex_ tabing sub_se se_<?php echo iN_HelpSecure($scID); ?>">
                            <input type="text" class="i_input_sce flex_" id="sub_va_<?php echo iN_HelpSecure($scID); ?>" value="<?php echo iN_HelpSecure($subCategoryKey); ?>">
                        </div>
                        <div class='sub_cat_e flex_ tabing_non_justify'>
                            <div class="sub_cat_edit c1 flex_ tabing border_one transition sbEd sc_ed_<?php echo iN_HelpSecure($scID); ?>" id="<?php echo iN_HelpSecure($scID); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_this']; ?>
                            </div>
                            <div class="sub_cat_edit c8 flex_ tabing border_one transition sc_e_<?php echo iN_HelpSecure($scID); ?> sceEd nonePoint" id="<?php echo iN_HelpSecure($scID); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')) . $LANG['save_edit']; ?>
                            </div>
                            <div class="sub_cat_edit c7 flex_ tabing border_one transition sbc_delete" id="<?php echo iN_HelpSecure($scID); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                            </div>
                        </div>
                        <div class="sub_stat flex_ tabing_non_justify">
                            <div class="i_chck_text_sub p_right_eight"><?php echo iN_HelpSecure($LANG['plan_status']); ?></div>
                            <label class="el-switch el-switch-yellow" for="fig_sec_<?php echo iN_HelpSecure($scID); ?>">
                                <input type="checkbox" name="fig_sec_<?php echo iN_HelpSecure($scID); ?>" class="chmdModTwo fig_sec_<?php echo iN_HelpSecure($scID); ?>" id="fig_sec_<?php echo iN_HelpSecure($scID); ?>" data-id="<?php echo iN_HelpSecure($scID); ?>" <?php echo iN_HelpSecure($scStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
                                <span class="el-switch-style"></span>
                            </label>
                            <div class="success_tick tabing flex_ sec_one sucss_<?php echo iN_HelpSecure($scID); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                            </div>
                        </div>
                    </div>
                    <?php }
                    } ?>
                    <div class="cat_sub_item flex_ tabing_non_justify border_one n_s_c_<?php echo iN_HelpSecure($categoryKey); ?> nonePoint" id="<?php echo iN_HelpSecure($categoryKey); ?>">
                        <div class="flex_ sub_se_new margin_bottom_custom_css_js">
                            <input type="text" name="newsubcat" class="i_input_sce flex" id="n_<?php echo iN_HelpSecure($categoryKey); ?>" placeholder="Add new Subcategory key">
                        </div>
                        <div class="sub_cat_a flex_ tabing_non_justify">
                            <div class="sub_cat_edit c8 flex_ tabing border_one transition scneEd" id="<?php echo iN_HelpSecure($categoryID); ?>" data-c="<?php echo iN_HelpSecure($categoryKey); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')) . $LANG['save_edit']; ?>
                            </div>
                        </div>
                    </div>
                    <div class="createNewSubCat flex_ tabing_non_justify border_one a_n_<?php echo iN_HelpSecure($categoryKey); ?> newSubC" data-id="<?php echo iN_HelpSecure($categoryKey); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('39')) . $LANG['create_new_subcat']; ?>
                    </div>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/profileCategoriesAndSubcategoriesHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>
</div>