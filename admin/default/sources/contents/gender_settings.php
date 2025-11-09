<?php
$genderConfigLines = [];
$genderOptionsList = isset($genderOptionsFull) && is_array($genderOptionsFull) ? $genderOptionsFull : $genderOptions;
foreach ($genderOptionsList as $option) {
    $key = $option['key'] ?? '';
    $label = $option['label'] ?? ucfirst($key);
    $icon = $option['icon'] ?? '';
    $status = isset($option['status']) ? (string)$option['status'] : '1';
    $status = ($status === '0') ? '0' : '1';
    $genderConfigLines[] = $key . '|' . $label . '|' . $icon . '|' . $status;
}
$genderConfigText = implode("\n", $genderConfigLines);
$genderPlaceholder = htmlspecialchars($LANG['gender_settings_placeholder'] ?? 'female|Female|13', ENT_QUOTES, 'UTF-8');
?>
<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['gender_settings']); ?>
        </div>
        <div class="i_general_row_box column flex_" id="general_conf">
            <form method="post" id="genderOptionsForm">
                <div class="i_general_row_box_item flex_ column tabing__justify">
                    <div class="irow_box_left tabing flex_"><?php echo iN_HelpSecure($LANG['gender_settings']); ?></div>
                    <div class="irow_box_right">
                        <textarea name="gender_options" class="i_textarea flex_" rows="8" placeholder="<?php echo $genderPlaceholder; ?>"><?php echo iN_SecureTextareaOutput($genderConfigText); ?></textarea>
                        <div class="rec_not box_not_padding_left"><?php echo html_entity_decode($LANG['gender_settings_desc']); ?></div>
                        <div class="rec_not box_not_padding_left"><?php echo html_entity_decode($LANG['gender_settings_hint']); ?></div>
                    </div>
                </div>
                <div class="warning_wrapper warning_invalid_genders"><?php echo iN_HelpSecure($LANG['gender_settings_invalid']); ?></div>
                <div class="i_settings_wrapper_item successNot"><?php echo iN_HelpSecure($LANG['updated_successfully']); ?></div>
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <input type="hidden" name="f" value="updateGenderOptions">
                    <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                        <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
