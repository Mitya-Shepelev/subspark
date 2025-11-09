<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['custom_colors']); ?>
        </div>

        <div class="i_general_row_box column flex_ box_not_padding_top_package" id="general_conf">
            <?php
            $colorItems = [
                ['header_top_color', $headerTopColor, $LANG['header_top_color'], $LANG['header_top_color_not']],
                ['header_svg_color', $headerSVGColor, $LANG['header_svg_color'], $LANG['header_svg_color_not']],
                ['left_menu_svg_color', $leftMenuSVGColor, $LANG['left_menu_svg_color'], $LANG['left_menu_svg_color_not']],
                ['left_menu_text_color', $MenuTextColor, $LANG['text_color'], $LANG['text_color_not']],
                ['post_icon_colors', $postSectionSVGColor, $LANG['create_post_svg_color'], $LANG['create_post_svg_color_not']],
                ['post_section_svg_colors', $postIconSVGColor, $LANG['post_svg_color'], $LANG['post_svg_color_not']],
                ['publish_btn_color', $publishBTNColor, $LANG['btn_color'], $LANG['btn_color_not']],
                ['create_live_streamings_btn_color', $createLiveStreamingsBtnColor, $LANG['live_streaming_creation_button'], $LANG['live_streaming_creation_button_not']],
                ['left_menu_hover_color', $textHoverColor, $LANG['text_hover_color'], $LANG['text_hover_color_not']]
            ];

            foreach ($colorItems as $color) {
                [$id, $value, $label, $note] = $color;
                ?>
                <div class="i_general_row_box_item flex_ tabing_non_justify">
                    <div class="irow_box_left flex_"><?php echo iN_HelpSecure($label); ?></div>
                    <div class="irow_box_right gencolor">
                        <div class="general_color_box <?php echo $id; ?> call_colors"
                             data-id="<?php echo $id; ?>"
                             style="<?php if ($value) { ?>background-color:#<?php echo iN_HelpSecure($value); ?><?php } ?>">
                            <?php if ($id === 'header_top_color') { ?>
                                <div class="tool-hovers">
                                    <div class="bubble-tool-in">Click Choose Color</div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="flex_ tabing_non_justify">
                            <input type="text" name="<?php echo $id; ?>" class="i_input flex_" value="<?php echo iN_HelpSecure($value); ?>">
                            <div class="hbttn flex_">
                                <div class="bradius flex_ tabing c1 saveChange" data-id="<?php echo $id; ?>">
                                    <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                                </div>
                                <div class="bradius flex_ tabing c2 setDefaultColor" data-id="<?php echo $id; ?>">
                                    <?php echo iN_HelpSecure($LANG['set_default_color']); ?>
                                </div>
                            </div>
                        </div>
                        <div class="rec_not box_not_padding_top"><?php echo iN_HelpSecure($note); ?></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div> 
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/customColorsHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>