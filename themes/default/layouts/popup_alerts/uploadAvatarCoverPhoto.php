<!-- Avatar & Cover Düzenleme Ana Modal -->
<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="personalizeProfileModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!--Modal Header-->
            <div class="i_modal_ac_header" id="personalizeProfileModalTitle">
                <?php echo iN_HelpSecure($LANG['personalizeyourprofile']); ?>
                <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['helps_to_gain_visibility']); ?></div>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!--/Modal Header-->

            <!-- Avatar & Cover Alanı -->
            <div class="i_block_user_avatar_cover_wrapper">
                <div class="i_blck_in">
                    <div class="coverImageArea" style="background-image:url('<?php echo iN_HelpSecure($userCover); ?>');">
                        <div class="newCoverUpload">
                            <label for="cover">
                                <input type="file" accept="image/*" class="nonePoint" id="cover" name="cover_file" />
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('84')); ?>
                            </label>
                        </div>
                    </div>
                    <div class="avatarImageArea flex_">
                        <div class="avatarImageWrapper">
                            <div class="avatarImage" style="background-image:url('<?php echo iN_HelpSecure($userAvatar); ?>');"></div>
                            <div class="newAvatarUpload">
                                <label for="avatar">
                                    <input type="file" accept="image/*" class="nonePoint" id="avatar" name="avatar_file" />
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('84')); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Avatar & Cover Alanı -->

            <!-- Modal Footer -->
            <div class="i_block_box_footer_container">
                <div class="alertBtnRightWithIcon svAC transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['finished']); ?>">
                    <?php echo iN_HelpSecure($LANG['finished']); ?>
                </div>
                <div class="alertBtnLeft no-del transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
            <!--/ Modal Footer -->

            <!-- Script -->
            <script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/crop/croppie.js?v=091<?php echo iN_HelpSecure($version); ?>"></script>
            <script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/avatarCoverCropHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
        </div>
    </div>
</div>

<!-- Kapak Fotoğrafı Kırpma Modali -->
<div class="i_modal_cover_resize_bg_in" role="dialog" aria-modal="true" aria-labelledby="coverCropTitle">
    <div class="i_modal_in_in noTransition">
        <div class="i_modal_content">
            <div class="i_modal_ac_header" id="coverCropTitle">
                <?php echo iN_HelpSecure($LANG['cover_image_modification']); ?>
                <div class="coverCropClose transition" role="button" aria-label="Close"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?></div>
            </div>
            <div class="i_block_user_avatar_cover_wrapper">
                <div class="i_blck_in">
                    <div class="cropier_container">
                        <div class="crop_middle"><span id="cover_image"></span></div>
                    </div>
                </div>
            </div>
            <div class="i_block_box_footer_container">
                <div class="alertBtnRightWithIcon finishCrop transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['save_edit']); ?>">
                    <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                </div>
                <div class="alertBtnLeft cnclcrp transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Profil Fotoğrafı Kırpma Modali -->
<div class="i_modal_avatar_resize_bg_in" role="dialog" aria-modal="true" aria-labelledby="avatarCropTitle">
    <div class="i_modal_in_in noTransition">
        <div class="i_modal_content">
            <div class="i_modal_ac_header" id="avatarCropTitle">
                <?php echo iN_HelpSecure($LANG['profile_image_modification']); ?>
                <div class="coverCropClose transition" role="button" aria-label="Close"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?></div>
            </div>
            <div class="i_block_user_avatar_cover_wrapper">
                <div class="i_blck_in">
                    <div class="cropier_container">
                        <div class="crop_middle"><span id="avatar_image"></span></div>
                    </div>
                </div>
            </div>
            <div class="i_block_box_footer_container">
                <div class="alertBtnRightWithIcon finishACrop transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['save_edit']); ?>">
                    <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                </div>
                <div class="alertBtnLeft cnclcrp transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
        </div>
    </div>
</div>