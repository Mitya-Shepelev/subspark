<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box flex_ tabing_non_justify">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('132')); ?>
            <?php echo iN_HelpSecure($LANG['managa_advertisements']); ?>
        </div>

        <div class="i_general_row_box column flex_ box_not_padding_top_package" id="general_conf">
            <div class="i_general_row_box_item flex_ column tabing__justify">
                <div class="buyCreditWrapper flex_ tabing mTop">
                    <?php
                    $dataAds = $iN->iN_AdvertisementsListAdmin($userID);
                    if ($dataAds) {
                        foreach ($dataAds as $aData) {
                            $adsID = $aData['ads_id'] ?? null;
                            $ads_title = $aData['ads_title'] ?? null;
                            $ads_description = $aData['ads_desc'] ?? null;
                            $adsURL = $aData['ads_url'] ?? null;
                            $adsStatus = $aData['ads_status'] ?? null;
                            $adsImage = $aData['ads_image'] ?? null;
                            $advertisementImage = $adsImage;
                            $editAdsLink = $base_url . 'admin/managa_advertisements?editAds=' . $adsID;
                            ?>
                            <div class="credit_plan_box">
                                <div class="plan_box tabing flex_ column plbox<?php echo iN_HelpSecure($adsID); ?>">
                                    <div class="a_image_area flex_ tabing border_one theaImage" style="background-image:url(<?php echo iN_HelpSecure($advertisementImage); ?>)">
                                        <img class="a-item-img" src="<?php echo iN_HelpSecure($advertisementImage); ?>">
                                    </div>

                                    <div class="tabing flex_ edit_active_delete">
                                        <div class="ecd_item">
                                            <div class="ecd_item_in flex_ tabing">
                                                <div class="i_checkbox_wrapper flex_ tabing_non_justify box_padding_d">
                                                    <label class="el-switch el-switch-yellow" for="adsStatus<?php echo iN_HelpSecure($adsID); ?>">
                                                        <input type="checkbox" class="adsStat" id="adsStatus<?php echo iN_HelpSecure($adsID); ?>" data-id="<?php echo iN_HelpSecure($adsID); ?>" data-type="adsStatus" <?php echo iN_HelpSecure($adsStatus) == '1' ? 'value="0" checked="checked"' : 'value="1"'; ?>>
                                                        <span class="el-switch-style"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ecd_item flex_ tabing">
                                            <a href="<?php echo $editAdsLink; ?>">
                                                <div class="ecd_item_in flex_ tabing edit_plan border_one c2">
                                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')); ?>
                                                    <?php echo iN_HelpSecure($LANG['edit_plan']); ?>
                                                </div>
                                            </a>
                                        </div>

                                        <div class="ecd_item flex_ tabing">
                                            <div class="ecd_item_in flex_ tabing delete_ads border_one c3" id="<?php echo iN_HelpSecure($adsID); ?>">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
                                                <?php echo iN_HelpSecure($LANG['delete_plan']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="no_creator_f_wrap flex_ tabing">
                            <div class="no_c_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('54')); ?></div>
                            <div class="n_c_t"><?php echo iN_HelpSecure($LANG['dont_have_ads_yet']); ?></div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>