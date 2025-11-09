<?php
$planData              = $iN->GetLivePlanDetails($planID);
$planID                = $planData['gift_id'] ?? null;
$planNameKey           = $planData['gift_name'] ?? null;
$planPoint             = $planData['gift_point'] ?? null;
$planStatus            = $planData['gift_status'] ?? null;
$planAmount            = $planData['gift_money_equal'] ?? null;
$giftImage             = $planData['gift_image'] ?? null;
$giftAnimationImage    = $planData['gift_money_animation_image'] ?? null;
?>

<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify white_board_style">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['edit_live_package']); ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding" id="general_conf">
            <div class=""></div>
            <div class="i_p_e_body zero_margin_bottom">
                <div class="i_general_row_box_item flex_ tabing_non_justify" id="sec_logo">
                    <div class="irow_box_right zero_padding_left">
                        <div class="add_app_not_point">
                            <?php echo iN_HelpSecure($LANG['update_gift_avatar']); ?>
                        </div>
                        <div class="certification_file_box flex_ tabing_non_justify padding_zero">
                            <form id="giftImageUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/request/request.php">
                                <label for="gift_image">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')) . iN_HelpSecure($LANG['u_ads_image']); ?>
                                    <input type="file" class="editAds_file" id="gift_image" name="uploading[]" data-id="GiftFile" data-type="adsType">
                                </label>
                            </form>
                            <div class="success_tick tabing flex_ adsType">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="i_general_row_box_item flex_ tabing_non_justify" id="sec_logo">
                    <div class="irow_box_right zero_padding_left optional_width">
                        <div class="certification_file_box padding_zero">
                            <form id="giftAnimationImageUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/request/request.php">
                                <label for="gift_animation_image editLiveGifPackageMaxWidth">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')) . iN_HelpSecure($LANG['u_coin_animation']); ?>
                                    <input type="file" class="editAds_file" id="gift_animation_image" name="uploading[]" data-id="GiftAnimationFile" data-type="adsType">
                                </label>
                            </form>
                            <div class="success_tick tabing flex_ adsType">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                            </div>
                        </div>
                        <div class="rec_not rec_not_top">
                            <?php echo iN_HelpSecure($LANG['u_coin_animation_not']); ?>
                        </div>
                    </div>
                </div>
            </div>

            <form enctype="multipart/form-data" method="post" id="editLivePointPackage">
                <div class="i_p_e_body editAds_padding">
                    <div class="warning_wrapper pk_wraning">
                        <?php echo iN_HelpSecure($LANG['all_fields_must_be_filled']); ?>
                    </div>

                    <div class="add_app_not_point">
                        <?php echo isset($LANG['gift_name']) ? $LANG['gift_name'] : 'NaN'; ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <input type="text" name="planKey" class="point_input" value="<?php echo iN_HelpSecure($planNameKey); ?>">
                    </div>

                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['gift_point']); ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                        <input type="text" name="planPoint" class="point_input pnt white_board_padding_left" onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)" value="<?php echo iN_HelpSecure($planPoint); ?>">
                    </div>

                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['pay_cost']); ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <div class="rec_not">
                            <?php echo $currencys[$defaultCurrency]; ?><span class="totsm"><?php echo iN_HelpSecure($planAmount); ?></span>
                        </div>
                    </div>

                    <div class="i_become_creator_box_footer">
                        <input type="hidden" name="f" value="editLivePlan">
                        <input type="hidden" name="giftFile" id="giftFile" value="<?php echo iN_HelpSecure($giftImage); ?>">
                        <input type="hidden" name="GiftAnimationFile" id="GiftAnimationFilea" value="<?php echo iN_HelpSecure($giftAnimationImage); ?>">
                        <input type="hidden" name="planid" value="<?php echo iN_HelpSecure($planID); ?>">
                        <input type="hidden" name="pointAmount" class="pamnt" value="<?php echo iN_HelpSecure($planAmount); ?>">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
                            <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    window.onePointEqual = <?php echo (float) $onePointEqual; ?>;
</script> 
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/editLivePlan.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>