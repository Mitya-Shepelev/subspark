<?php
$planData    = $iN->GetFramePlanDetails($planID);
$planID      = $planData['f_id'] ?? null;
$planStatus  = $planData['f_frame_status'] ?? null;
$planAmount  = $planData['f_price'] ?? null;
$giftImage   = $planData['f_file'] ?? null;
?>

<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify white_board_style">
        <!-- Title -->
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['edit_frame_details']); ?>
        </div>

        <div class="i_general_row_box column flex_" id="general_conf">
            <!-- Upload Frame -->
            <div class="i_p_e_body zero_margin_bottom">
                <div class="i_general_row_box_item flex_ tabing_non_justify" id="sec_logo">
                    <div class="irow_box_right zero_padding_left">
                        <div class="add_app_not_point">
                            <?php echo iN_HelpSecure($LANG['upload_new_frame']); ?>
                        </div>

                        <div class="certification_file_box flex_ tabing_non_justify padding_zero">
                            <form id="frameImageUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/request/request.php">
                                <label for="frame_image">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')) . iN_HelpSecure($LANG['u_ads_image']); ?>
                                    <input type="file" class="editAds_file" id="frame_image" name="uploading[]" data-id="frameFile" data-type="adsType">
                                </label>
                            </form>
                            <div class="success_tick tabing flex_ adsType">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form enctype="multipart/form-data" method="post" id="editFramePointPackage">
                <div class="i_p_e_body editAds_padding">
                    <div class="warning_wrapper pk_wraning">
                        <?php echo iN_HelpSecure($LANG['all_fields_must_be_filled']); ?>
                    </div>

                    <!-- Price Point -->
                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['frame_price']); ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                        <input type="text" name="planPoint" class="point_input pnt white_board_padding_left"
                            onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)"
                            value="<?php echo iN_HelpSecure($planAmount); ?>">
                    </div>

                    <!-- Translated Cost -->
                    <div class="add_app_not_point">
                        <?php echo iN_HelpSecure($LANG['pay_cost']); ?>
                    </div>
                    <div class="i_plnn_container flex_">
                        <div class="rec_not">
                            <?php echo iN_HelpSecure($currencys[$defaultCurrency]); ?>
                            <span class="totsm"><?php echo iN_HelpSecure($planAmount); ?></span>
                        </div>
                    </div>

                    <!-- Hidden Fields + Save -->
                    <div class="i_become_creator_box_footer">
                        <input type="hidden" name="f" value="editFramePlan">
                        <input type="hidden" name="frameFile" id="frameFile" value="<?php echo iN_HelpSecure($giftImage); ?>">
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

<!-- JSON Data Transfer -->
<script id="frameEditData" type="application/json">
<?php echo json_encode(['onePointEqual' => $onePointEqual]); ?>
</script> 
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>admin/<?php echo iN_HelpSecure($adminTheme);?>/js/frameEditHandler.js?v=<?php echo iN_HelpSecure($version);?>" defer></script>