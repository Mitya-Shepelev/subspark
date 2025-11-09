<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="purchasePostModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in i_sf_box">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="purchase_premium_header flex_ tabing border_top_radius" id="purchasePostModalTitle">
                <?php echo iN_HelpSecure($LANG['purchase_post']); ?>
            </div>

            <!-- Modal Body -->
            <div class="purchase_post_details">
                <?php
                $totalVideos = $totalAudio = $totalPhotos = 0;
                if (!empty($userPostFile)) {
                    $trimValue = rtrim($userPostFile, ',');
                    $explodeFiles = array_unique(explode(',', $trimValue));
                    $countExplodedFiles = count($explodeFiles);
                    $fileExts = [];

                    foreach ($explodeFiles as $explodeVideoFile) {
                        $VideofileData = $iN->iN_GetUploadedFileDetails($explodeVideoFile);
                        $ext = isset($VideofileData['uploaded_file_ext']) ? $VideofileData['uploaded_file_ext'] : 'unknown';
                        $fileExts[] = $ext;
                    }

                    $extCounts = array_count_values($fileExts);
                    $totalVideos = isset($extCounts['mp4']) ? $extCounts['mp4'] : 0;
                    $totalAudio = isset($extCounts['mp3']) ? $extCounts['mp3'] : 0;
                    $totalPhotos = $countExplodedFiles - ($totalVideos + $totalAudio);
                }
                ?>
                <div class="wallet-debit-confirm-container flex_">
                    <div class="owner_avatar" style="background-image:url('<?php echo iN_HelpSecure($userPostOwnerUserAvatar); ?>');" aria-hidden="true"></div>
                    
                    <?php if (!empty($userPostFile)) { ?>
                        <div class="album-details">
                            <?php
                            echo iN_HelpSecure($LANG['purchasing']) . ' ';
                            $purchaseParts = [];
                            if ($totalPhotos > 0) {
                                $purchaseParts[] = preg_replace('/{.*?}/', $totalPhotos, $LANG['pr_photo']);
                            }
                            if ($totalVideos > 0) {
                                $purchaseParts[] = preg_replace('/{.*?}/', $totalVideos, $LANG['pr_video']);
                            }
                            if ($totalAudio > 0) {
                                $purchaseParts[] = preg_replace('/{.*?}/', $totalAudio, $LANG['pr_audio']);
                            }
                            echo implode(', ', $purchaseParts);
                            ?>
                        </div>
                    <?php } else { ?>
                        <div class="album-details">
                            <?php echo iN_HelpSecure($LANG['purchasing']) . ' ' . iN_HelpSecure($LANG['purchasing_warning_for_empty_video_and_image']); ?>
                        </div>
                    <?php } ?>

                    <div class="album-wanted-point">
                        <div><?php echo html_entity_decode($userPostWantedCredit); ?></div>
                        <span><?php echo iN_HelpSecure($LANG['points']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="i_modal_g_footer">
                <div class="alertBtnRight prchase_go_wallet transition"
                     id="<?php echo iN_HelpSecure($userPostID); ?>"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['confirm']); ?>">
                    <?php echo iN_HelpSecure($LANG['confirm']); ?>
                </div>
                <div class="alertBtnLeft no-del transition"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
        </div>
    </div>
    <!--/SHARE-->
</div>