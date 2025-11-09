<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="storieViewersModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in i_sf_box">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="storieViewersModalTitle">
                <?php echo iN_HelpSecure($LANG['who_have_seen_your_storie']); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <!-- Viewer List -->
            <div class="viewer_list_container">
                <?php if ($swData) {
                    foreach ($swData as $swD) {
                        $storieViewerUserID = $swD['i_seen_uid_fk'] ?? NULL;
                        $storieViewerUserName = $iN->iN_GetUserName($storieViewerUserID);
                        $storieViewerUserFULLName = $iN->iN_UserFullName($storieViewerUserID);
                        $storieViewerAvatar = $iN->iN_UserAvatar($storieViewerUserID, $base_url);
                        $profileUrl = iN_HelpSecure($base_url . $storieViewerUserName);
                ?>
                    <div class="i_message_wrapper transition wpr">
                        <a href="<?php echo $profileUrl; ?>" class="flex_ tabing_non_justify" aria-label="<?php echo iN_HelpSecure($storieViewerUserFULLName); ?>">
                            <div class="i_message_owner_avatar">
                                <div class="i_message_avatar">
                                    <img src="<?php echo iN_HelpSecure($storieViewerAvatar); ?>"
                                         alt="<?php echo iN_HelpSecure($storieViewerUserFULLName); ?>"
                                         loading="lazy">
                                </div>
                            </div>
                            <div class="i_message_info_container">
                                <div class="i_message_owner_name">
                                    <?php echo iN_HelpSecure($storieViewerUserFULLName); ?>
                                </div>
                                <div class="i_message_i">
                                    @<?php echo iN_HelpSecure($storieViewerUserName); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php } } else { ?>
                    <div class="no_one_has_viewed flex_ tabing">
                        <?php echo iN_HelpSecure($LANG['no_one_has_viewed']); ?>
                    </div>
                <?php } ?>
            </div>
            <!--/ Viewer List -->
        </div>
    </div>
    <!--/SHARE-->
</div>