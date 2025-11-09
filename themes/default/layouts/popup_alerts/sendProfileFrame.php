<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="sendFrameModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in modal_frames">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="sendFrameModalTitle">
                <?php echo iN_HelpSecure(preg_replace('/{.*?}/', $f_userfullname, $LANG['send_frame_to'])); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="i_more_frames_wrapper flex_ tabing__justify">
                <?php
                $frameData = $iN->iN_FrameListFromProfile();
                if ($frameData) {
                    foreach ($frameData as $fData) {
                        $frameID = $fData['f_id'] ?? NULL;
                        $frameImage = $fData['f_file'] ?? NULL;
                        $framePrice = (float)($fData['f_price'] ?? 0);
                        $frameLocation = $base_url . $frameImage;
                        $frameMoneyEqual = $onePointEqual * $framePrice;
                        $isLoggedIn = iN_HelpSecure($logedIn);
                        $frameIDSecured = iN_HelpSecure($frameID);
                ?>
                        <div class="credit_plan_box <?php echo $isLoggedIn == '0' ? 'loginForm' : 'buyFrameGift'; ?>" id="<?php echo $frameIDSecured; ?>">
                            <div class="plan_box_frame tabing flex_" id="p_i_<?php echo $frameIDSecured; ?>">
                                <div class="a_image_area_live_gift flex_ tabing border_one theaImage"
                                     style="background-image:url('<?php echo $frameLocation; ?>')"
                                     aria-hidden="true">
                                    <img class="a-item-img_live_gift"
                                         src="<?php echo $frameLocation; ?>"
                                         alt="Frame Image" />
                                </div>

                                <div class="plan_value">
                                    <div class="plan_price tabing">
                                        <div class="ib">
                                            <?php echo number_format($framePrice); ?>
                                            <span class="plan_point_icon">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="plan_point tabing flex_">
                                        <?php echo iN_HelpSecure($LANG['points']); ?>
                                    </div>

                                    <!-- Purchase Button -->
                                    <div class="purchaseButton flex_ tabing" role="button" aria-label="<?php echo iN_HelpSecure($LANG['purchase']); ?>">
                                        <?php echo iN_HelpSecure($LANG['purchase']); ?>
                                        <strong class="tabing flex_ i_inline_flex">
                                            <?php echo number_format($framePrice); ?>
                                            <span class="prcsic">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                            </span>
                                        </strong>
                                        <div class="foramount">
                                            <?php echo iN_HelpSecure($LANG['for']) . ' ' . $currencys[$defaultCurrency] . number_format($frameMoneyEqual); ?>
                                        </div>
                                    </div>
                                    <!-- /Purchase Button -->
                                </div>
                            </div>
                        </div>
                <?php }} ?>
            </div>

            <!-- Modal Footer -->
            <div class="i_block_box_footer_container">
                <?php echo iN_HelpSecure(preg_replace('/{.*?}/', $f_userfullname, $LANG['after_purchase_frame_not'])); ?>
            </div>
        </div>
    </div>

    <!-- JavaScript (image load optimization) -->
    <script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/frameGiftLoader.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
    <!--/SHARE-->
</div>