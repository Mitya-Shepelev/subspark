<div class="i_modal_bg_in">
   <div class="i_modal_in_in">
       <div class="i_modal_content">
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['creating_new_gift_coin']); ?>
                <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?></div>
            </div>

            <!-- Static Coin Image -->
            <div class="i_editsvg_code flex_ tabing_non_justify">
                <div class="i_general_row_box_item flex_ tabing_non_justify" id="sec_logo">
                    <div class="irow_box_right irow_box_rightst">
                        <div class="certification_file_box certification_file_boxst">
                            <form id="giftImageUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/request/request.php">
                                <label for="gift_image" class="editLiveGifPackageMaxWidth">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')); ?>
                                    <?php echo iN_HelpSecure($LANG['u_coin_image']); ?>
                                    <input type="file" id="gift_image" name="uploading[]" data-id="GiftFile" data-type="adsType" class="editAds_file nonePoint">
                                </label>
                            </form>
                            <div class="success_tick tabing flex_ adsType"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?></div>
                        </div>
                        <div class="rec_not rec_not_top"><?php echo iN_HelpSecure($LANG['i_coin_image_not']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Animated Gift Coin -->
            <div class="i_editsvg_code flex_ tabing_non_justify">
                <div class="i_general_row_box_item flex_ tabing_non_justify" id="sec_logo">
                    <div class="irow_box_right irow_box_rightst">
                        <div class="certification_file_box certification_file_boxst_pl">
                            <form id="giftAnimationImageUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/request/request.php">
                                <label for="gift_animation_image" class="editLiveGifPackageMaxWidth">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')); ?>
                                    <?php echo iN_HelpSecure($LANG['u_coin_animation']); ?>
                                    <input type="file" id="gift_animation_image" name="uploading[]" data-id="GiftAnimationFile" data-type="adsType" class="editAds_file nonePoint">
                                </label>
                            </form>
                            <div class="success_tick tabing flex_ adsType"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?></div>
                        </div>
                        <div class="rec_not rec_not_top"><?php echo iN_HelpSecure($LANG['u_coin_animation_not']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Gift Coin Form -->
            <form enctype="multipart/form-data" method="post" id="newGiftCardForm">
                <div class="i_editsvg_code flex_ tabing">
                    <div class="i_p_e_body editAds_padding">
                        <div class="general_warning">
                            <div class="border_one c3 flex_ tabing_non_justify editAds_padding">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60')); ?>
                                <?php echo iN_HelpSecure($LANG['must_contain_all_plan_informations']); ?>
                            </div>
                        </div>

                        <div class="add_app_not_point"><?php echo isset($LANG['new_gift_not']) ? $LANG['new_gift_not'] : 'NaN'; ?></div>

                        <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['gift_name']); ?></div>
                        <div class="i_plnn_container flex_">
                            <input type="text" name="gift_name" class="point_input" placeholder="<?php echo iN_HelpSecure($LANG['ex_gift_name']); ?>">
                        </div>
                        <div class="warning_wrapper pk_wraning"><?php echo iN_HelpSecure($LANG['please_write_gift_name']); ?></div>

                        <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['gift_point']); ?></div>
                        <div class="i_plnn_container flex_">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                            <input type="text" name="giftPoint" class="point_input pnt white_board_padding_left" inputmode="decimal" pattern="[0-9.]*" placeholder="<?php echo iN_HelpSecure($LANG['plan_point_amount_ex']); ?>">
                        </div>
                        <div class="warning_wrapper ppk_wraning"><?php echo preg_replace('/{.*?}/', $minimumPointLimit, $LANG['plan_point_warning']); ?></div>

                        <div class="add_app_not_point"><?php echo iN_HelpSecure($LANG['pay_cost']); ?></div>
                        <div class="i_plnn_container flex_">
                            <div class="rec_not"><?php echo $currencys[$defaultCurrency]; ?><span class="totsm">0</span></div>
                        </div>
                        <div class="warning_wrapper papk_wraning"><?php echo preg_replace('/{.*?}/', $maximumPointAmountLimit, $LANG['plan_point_amount_warning']); ?></div>
                    </div>
                </div>

                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="f" value="newGiftCardForm">
                    <input type="hidden" name="giftFile" id="giftFile" value="">
                    <input type="hidden" name="GiftAnimationFile" id="GiftAnimationFilea" value="">
                    <div class="popupSaveButton transition">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition"><?php echo iN_HelpSecure($LANG['save_edit']); ?></button>
                    </div>
                    <div class="alertBtnLeft no-del transition"><?php echo iN_HelpSecure($LANG['no']); ?></div>
                </div>
            </form>
       </div>
   </div>
<script src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/giftCoinHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
</div>