<div class='cat_sub_item flex_' id="general_conf<?php echo iN_HelpSecure($scID); ?>">
    <div class="flex_ tabing sc_<?php echo iN_HelpSecure($scID); ?>">
        <?php echo isset($PROFILE_SUBCATEGORIES[$subCategoryKey]) ? $PROFILE_SUBCATEGORIES[$subCategoryKey] : preg_replace('/{.*?}/', $subCategoryKey, $LANG['add_lang_key_not']); ?>
    </div>
    <div class="flex_ tabing sub_se se_<?php echo iN_HelpSecure($scID); ?>">
        <input type="text" class="i_input_sce flex_" id="sub_va_<?php echo iN_HelpSecure($scID); ?>" value="<?php echo iN_HelpSecure($subCategoryKey); ?>">
    </div>
    <div class='sub_cat_e flex_ tabing_non_justify'>
        <div class="sub_cat_edit c1 flex_ tabing border_one transition sbEd sc_ed_<?php echo iN_HelpSecure($scID); ?>" id="<?php echo iN_HelpSecure($scID); ?>">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_user_infos']; ?>
        </div>
        <div class="sub_cat_edit c8 flex_ tabing border_one transition sc_e_<?php echo iN_HelpSecure($scID); ?> sceEd nonePoint" id="<?php echo iN_HelpSecure($scID); ?>">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')) . $LANG['save_edit']; ?>
        </div>
        <div class="sub_cat_edit c7 flex_ tabing border_one transition sbc_delete" id="<?php echo iN_HelpSecure($scID); ?>">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
        </div>
    </div>
    <div class="sub_stat flex_ tabing_non_justify">
        <div class="i_chck_text_sub box_not_padding_right">
            <?php echo iN_HelpSecure($LANG['plan_status']); ?>
        </div>
        <label class="el-switch el-switch-yellow" for="fig_sec_<?php echo iN_HelpSecure($scID); ?>">
            <input type="checkbox" name="fig_sec_<?php echo iN_HelpSecure($scID); ?>" class="chmdModTwo fig_sec_<?php echo iN_HelpSecure($scID); ?>" id="fig_sec_<?php echo iN_HelpSecure($scID); ?>" data-id="<?php echo iN_HelpSecure($scID); ?>" <?php echo iN_HelpSecure($scStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
            <span class="el-switch-style"></span>
        </label>
        <div class="success_tick tabing flex_ sec_one sucss_<?php echo iN_HelpSecure($scID); ?>">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
        </div>
    </div>
</div>