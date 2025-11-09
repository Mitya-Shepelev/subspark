<div class="i_modal_bg_colorpicker_in i_modal_display_in_picker">
    <div class="i_modal_in_in_colors">
        <div class="i_modal_content">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['colors']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <div class="i_colors__description">
                <div class="colors_right">
                    <?php
                    if (!empty($dizColors) && is_array($dizColors)) {
                        foreach ($dizColors as $colorName => $colorCodes) {
                            $safeColorName = ucfirst(preg_replace('/[^a-zA-Z0-9_\-]/', '', $colorName));
                            echo '<div class="i_general_title_box_colorpicker" id="' . $safeColorName . '">' . $safeColorName . '</div>';
                            echo '<div class="flex_ tabing color_picker_wrapper">';

                            foreach ($colorCodes as $colorCode) {
                                $safeColorCode = preg_replace('/[^a-fA-F0-9]/', '', $colorCode);
                                echo '<div class="flex_ ttcolor" data-color="#' . $safeColorCode . '" data-id="'.$colorFor.'" style="background-color: #' . $safeColorCode . ';">#' . $safeColorCode . '</div>';
                            }

                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>