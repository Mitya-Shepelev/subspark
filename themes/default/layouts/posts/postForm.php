<div class="i_postFormContainer">
    <?php if ($agoraStatus === '1' && $page !== 'profile') : ?>
        <div class="i_postLiveStreaming flex_ tabing">
            <?php if ($logedIn === '1' && $paidLiveStreamingStatus === '1' && $feesStatus === '2') : ?>
                <div class="i_live_ cNLive flex_ tabing_non_justify transition" data-type="paidLive">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                    <?php echo iN_HelpSecure($LANG['paid_live_streamings']); ?>
                </div>
            <?php endif; ?>

            <?php if ($logedIn === '1' && $freeLiveStreamingStatus === '1') : ?>
                <div class="i_live_ cNLive flex_ tabing_non_justify transition" data-type="freeLive">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('115')); ?>
                    <?php echo iN_HelpSecure($LANG['free_live_streams']); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="i_warning"><?php echo iN_HelpSecure($LANG['please_enter_a_message_or_add_a_photo_or_video']); ?></div>
    <div class="i_warning_point"><?php echo iN_HelpSecure($LANG['must_write_point_for_post']); ?></div>
    <div class="i_warning_point_two"><?php echo iN_HelpSecure($LANG['must_be_start_with_number']); ?></div>
    <div class="i_warning_prmfl"><?php echo iN_HelpSecure($LANG['must_upload_files_for_premium']); ?></div>
    <div class="i_warning_unsupported"><?php echo iN_HelpSecure($LANG['unsupported_video_format']); ?></div>
    <div class="i_upload_warning"></div>

    <div class="i_post_form transition aft">
        <div class="i_post_creator_avatar">
            <a href="<?php echo iN_HelpSecure($base_url) . $userName; ?>">
                <img src="<?php echo iN_HelpSecure($userAvatar); ?>" alt="<?php echo iN_HelpSecure($userFullName); ?>">
            </a>
        </div>
        <div class="i_post_form_textarea">
            <textarea
                name="postText"
                id="newPostT"
                maxlength="<?php echo iN_HelpSecure($availableLength); ?>"
                class="comment commenta newPostT"
                placeholder="<?php echo iN_HelpSecure($LANG['write_message_add_photo_or_video']); ?>"></textarea>
        </div>
    </div>

    <?php if ($userWhoCanSeePost === '4') : ?>
        <div class="point_input_wrapper">
            <input
                type="text"
                name="point"
                id="point"
                class="pointIN"
                onkeypress="return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)"
                placeholder="<?php echo iN_HelpSecure($LANG['write_points']); ?>">
            <div class="box_not box_not_padding_left">
                <?php echo iN_HelpSecure($LANG['point_wanted']); ?>
            </div>
        </div>
    <?php endif; ?>

    <form id="tuploadform" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url) . 'requests/request.php'; ?>">
        <div class="i_uploaded_iv nonePoint">
            <div class="i_upload_progress"></div>
            <div class="i_uploading_not">
                <?php echo iN_HelpSecure($LANG['uploading_please_wait']); ?>
            </div>
            <div class="i_uploaded_file_box"></div>
        </div>
    </form>

    <div class="mentions_list nonePoint"></div>

    <div class="i_form_buttons">
        <div class="form_btn transition ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['image_video']); ?>">
            <form id="uploadform" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url) . 'requests/request.php'; ?>">
                <label for="i_image_video">
                    <div class="i_image_video_btn">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('49')); ?>
                    </div>
                    <input type="file" id="i_image_video" class="imageorvideo" name="uploading[]" data-id="upload" multiple>
                </label>
            </form>
        </div>
        <!--Upload Reels-->
        <?php if (isset($reelsFeatureStatus) && (string)$reelsFeatureStatus === '1') : ?>
            <div class="form_btn transition ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['create_reels']); ?>">
                <form id="uploadReelsform" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url) . 'requests/request.php'; ?>">
                    <label for="i_reels_video">
                        <div class="i_image_video_btn">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('187')); ?>
                        </div>
                        <input type="file" id="i_reels_video" class="imageorvideo" name="uploading[]" data-id="uploadReel">
                    </label>
                </form>
            </div>
        <?php endif; ?>
        <!--/Upload Reels-->
        <?php if ($openAiStatus === '1') : ?>
            <div class="i_ai_generate transition ownTooltip getAiBox" data-type="aiBox" data-label="<?php echo iN_HelpSecure($LANG['generate_ai_content']); ?>">
                <div class="i_pb_aiBox">
                    <div class="i_ai_emojis_Box">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('184')); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ($iN->iN_ShopData($userID, 1) === 'yes') : ?>
            <?php if ($feesStatus === '2' && $iN->iN_ShopData($userID, '8') === 'yes') : ?>
                <div class="form_btn transition ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['createaProduct']); ?>">
                    <div class="i_image_video_btn">
                        <a href="<?php echo $base_url . 'settings?tab=createaProduct'; ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('155')); ?>
                        </a>
                    </div>
                </div>
            <?php elseif ($iN->iN_ShopData($userID, '8') === 'no') : ?>
                <div class="form_btn transition ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['createaProduct']); ?>">
                    <div class="i_image_video_btn">
                        <a href="<?php echo $base_url . 'settings?tab=createaProduct'; ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('155')); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="i_pb_emojis transition">
            <div class="i_pb_emojisBox getEmojis" data-type="emojiBox">
                <div class="i_pb_emojis_Box">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('25')); ?>
                </div>
            </div>
        </div>

        <div class="form_who_see transition">
            <div class="whoSeeBox whs">
                <div class="wBox">
                    <?php echo html_entity_decode($activeWhoCanSee); ?>
                </div>
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('36')); ?>
            </div>

            <div class="i_choose_ws_wrapper">
                <div class="whctt"><?php echo iN_HelpSecure($LANG['whocanseethis']); ?></div>

                <div class="i_whoseech_menu_item_out wsUpdate transition <?php echo $userWhoCanSeePost === 1 ? 'wselected' : ''; ?>" data-id="1" id="wsUpdate1">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('50')); ?> <?php echo iN_HelpSecure($LANG['weveryone']); ?>
                </div>

                <div class="i_whoseech_menu_item_out wsUpdate transition <?php echo $userWhoCanSeePost === 2 ? 'wselected' : ''; ?>" data-id="2" id="wsUpdate2">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15')); ?> <?php echo iN_HelpSecure($LANG['wfollowers']); ?>
                </div>

                <?php if ($feesStatus === '2') : ?>
                    <div class="i_whoseech_menu_item_out wsUpdate transition <?php echo $userWhoCanSeePost === 3 ? 'wselected' : ''; ?>" data-id="3" id="wsUpdate3">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')); ?> <?php echo iN_HelpSecure($LANG['wsubscribers']); ?>
                    </div>

                    <div class="i_whoseech_menu_item_out wsUpdate transition <?php echo $userWhoCanSeePost === 4 ? 'wselected' : ''; ?>" data-id="4" id="wsUpdate4">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9')); ?> <?php echo iN_HelpSecure($LANG['premium']); ?>
                    </div>
                <?php endif; ?>
            </div>
            <input type="hidden" id="uploadVal">
        </div>

        <div class="publish_btn transition publish">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?>
            <div class="pbtn"><?php echo iN_HelpSecure($LANG['publish']); ?></div>
        </div>
    </div>
</div>
<?php include 'dayMessage.php'; ?>
