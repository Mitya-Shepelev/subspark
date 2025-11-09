<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box flex_ tabing_non_justify">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('120')); ?>
            <?php echo iN_HelpSecure($LANG['manageicons']); ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="new_svg_icon_wrapper">
                <div class="inline_block">
                    <div class="flex_ tabing_non_justify newSvgCode newCreate border_one" data-type="newSVGCode">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                        <?php echo iN_HelpSecure($LANG['create_a_new_svg_icon']); ?>
                    </div>
                </div>
            </div>

            <div class="i_svg_codes_list tabing">
                <?php
                if ($allSVGIcons) {
                    foreach ($allSVGIcons as $svgData) {
                        $svgCodeID = $svgData['icon_id'] ?? null;
                        $svgCodeC = $svgData['icon_code'] ?? null;
                        $svgCodeStatus = $svgData['icon_status'] ?? null;
                ?>
                        <div class="svg_icon_wrapper" id="svg_id_<?php echo iN_HelpSecure($svgCodeID); ?>">
                            <div class="svg_item flex_ column tabing_non_justify border_one">
                                <div class="icon_id tabing_ flex">
                                    <div class="icon_idm tabing flex_ border_one">
                                        <?php echo iN_HelpSecure($svgCodeID); ?>
                                    </div>
                                </div>

                                <div class="svg_code flex_ tabing editSvgIcon" id="<?php echo iN_HelpSecure($svgCodeID); ?>">
                                    <div class="edit_ic_wrapper border_one">
                                        <div class="edit_ic tabing flex_">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')); ?>
                                        </div>
                                    </div>
                                    <?php echo html_entity_decode($svgCodeC); ?>
                                </div>

                                <div class="active_inactive_btn">
                                    <label class="el-switch el-switch-yellow" for="svg<?php echo iN_HelpSecure($svgCodeID); ?>">
                                        <input type="checkbox" class="iaStat" id="svg<?php echo iN_HelpSecure($svgCodeID); ?>" data-id="<?php echo iN_HelpSecure($svgCodeID); ?>" <?php echo iN_HelpSecure($svgCodeStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
                                        <span class="el-switch-style"></span>
                                    </label>
                                </div>

                                <div class="success_tick tabing flex_ sec_one iaStat<?php echo iN_HelpSecure($svgCodeID); ?>">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>