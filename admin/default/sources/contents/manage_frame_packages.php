<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['frame_package_settings']); ?>
        </div>

        <div class="i_general_row_box column flex_ box_not_padding_top_package" id="general_conf">
            <div class="new_svg_icon_wrapper">
                <div class="inline_block">
                    <div class="flex_ tabing_non_justify newSvgCode newCreate border_one" data-type="newFrameCard">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                        <?php echo iN_HelpSecure($LANG['create_new_frame']); ?>
                    </div>
                </div>
            </div>

            <div class="buyCreditWrapper flex_ tabing">
                <?php
                $planFrameList = $iN->iN_FrameListFromAdmin();
                if ($planFrameList) {
                    foreach ($planFrameList as $planData) {
                        $gifID = $planData['f_id'] ?? null;
                        $gifImage = $planData['f_file'] ?? null;
                        $gifPoint = floatval($planData['f_price'] ?? 0);
                        $gifStatus = $planData['f_frame_status'] ?? null;

                        $theGiftImage = $base_url . $gifImage;
                        $editGiftPlan = $base_url . 'admin/manage_frame_packages?id=' . urlencode($gifID);

                        $onePointEqual = floatval($onePointEqual);
                        $gifMoneyEqual = $onePointEqual * $gifPoint;
                ?>
                        <div class="credit_plan_box" id="<?php echo iN_HelpSecure($gifID); ?>">
                            <div class="plan_box tabing flex_" id="p_i_<?php echo iN_HelpSecure($gifID); ?>">
                                <div class="a_image_area_live_gift flex_ tabing border_one theaImage" data-bg="<?php echo htmlspecialchars($theGiftImage); ?>">
                                    <img class="a-item-img_live_gift" src="<?php echo htmlspecialchars($theGiftImage); ?>" alt="Frame Image">
                                </div>

                                <div class="plan_value">
                                    <div class="plan_price tabing">
                                        <div class="display_initial positionRelative">
                                            <?php echo number_format($gifPoint); ?>
                                            <span class="plan_point_icon">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="plan_point tabing flex_">
                                        <?php echo iN_HelpSecure($LANG['points']); ?>
                                    </div>

                                    <div class="purchaseButton flex_ tabing">
                                        <?php echo iN_HelpSecure($LANG['purchase']); ?>
                                        <strong class="tabing flex_ inline_flexing">
                                            <?php echo number_format($gifPoint); ?>
                                            <span class="prcsic">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                            </span>
                                        </strong>
                                        <div class="foramount">
                                            <?php echo iN_HelpSecure($LANG['for']) . ' ' . $currencys[$defaultCurrency] . number_format($gifMoneyEqual, 2); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="tabing flex_ edit_active_delete">
                                    <div class="ecd_item">
                                        <div class="ecd_item_in flex_ tabing">
                                            <div class="i_checkbox_wrapper flex_ tabing_non_justify box_padding_d">
                                                <label class="el-switch el-switch-yellow" for="frameplanStatus<?php echo iN_HelpSecure($gifID); ?>">
                                                    <input 
                                                        type="checkbox" 
                                                        class="pstat" 
                                                        id="frameplanStatus<?php echo iN_HelpSecure($gifID); ?>" 
                                                        data-id="<?php echo iN_HelpSecure($gifID); ?>" 
                                                        data-type="frameplanStatus" 
                                                        <?php echo $gifStatus == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
                                                    <span class="el-switch-style"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="ecd_item flex_ tabing">
                                        <a href="<?php echo htmlspecialchars($editGiftPlan); ?>">
                                            <div class="ecd_item_in flex_ tabing edit_plan border_one c2">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')); ?>
                                                <?php echo iN_HelpSecure($LANG['edit_plan']); ?>
                                            </div>
                                        </a>
                                    </div>

                                    <div class="ecd_item flex_ tabing">
                                        <div class="ecd_item_in flex_ tabing delete_frame_plan border_one c3" id="<?php echo iN_HelpSecure($gifID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
                                            <?php echo iN_HelpSecure($LANG['delete_plan']); ?>
                                        </div>
                                    </div>
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
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/framePackage.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>