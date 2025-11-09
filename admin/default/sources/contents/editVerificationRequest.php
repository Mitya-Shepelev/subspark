<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify white_board_padding">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['approve_or_reject_verification_request']); ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding" id="general_conf">
            <form enctype="multipart/form-data" method="post" id="updateVerificationStatus">
                <?php
                $vData = $iN->iN_GetVerificationRequestFromID($verificationID);
                if ($vData) {
                    $vID = $vData['request_id'];
                    $vIDCard = $vData['id_card'];
                    $vIDPhotoOfCard = $vData['photo_of_card'];
                    $verificationRequestedUserID = $vData['iuid_fk'];
                    $uData = $iN->iN_GetUserDetails($verificationRequestedUserID);
                    $userUserName = $uData['i_username'];
                    $userUserFullName = $uData['i_user_fullname'];
                    $userAvatar = $iN->iN_UserAvatar($verificationRequestedUserID, $base_url);
                    $userRegisteredTime = $vData['request_time'];
                    $crTime = date('Y-m-d H:i:s', $userRegisteredTime);
                    $seeProfile = $base_url . $userUserName;
                    $userPostOwnerUserGender = $uData['user_gender'];

                    if ($userPostOwnerUserGender == 'male') {
                        $publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
                    } elseif ($userPostOwnerUserGender == 'female') {
                        $publisherGender = '<div class="i_plus_gf">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
                    } elseif ($userPostOwnerUserGender == 'couple') {
                        $publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
                    }
                ?>
                <div class="i_post_body body_<?php echo iN_HelpSecure($vID); ?>" id="<?php echo iN_HelpSecure($vID); ?>" data-last="<?php echo iN_HelpSecure($vID); ?>">
                    <div class="i_post_body_header">
                        <div class="i_post_user_avatar">
                            <img src="<?php echo iN_HelpSecure($userAvatar); ?>" />
                        </div>
                        <div class="i_post_i">
                            <div class="i_post_username">
                                <a class="truncated" href="<?php echo iN_HelpSecure($seeProfile); ?>">
                                    <?php echo iN_HelpSecure($userUserFullName); ?> <?php echo html_entity_decode($publisherGender); ?>
                                </a>
                            </div>
                            <div class="i_post_shared_time">
                                <?php echo TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="i_post_u_images">
                        <?php
                        echo '<div class="i_image_two lightGalleryInit" id="lightgallery' . iN_HelpSecure($verificationRequestedUserID) . '" data-uid="' . iN_HelpSecure($verificationRequestedUserID) . '">';

                        $fileData = $iN->iN_GetUploadedFileDetails($vIDCard);
                        if ($fileData) {
                            $filePath = $fileData['uploaded_file_path'];
                            if (function_exists('storage_public_url')) {
                                $filePathUrl = storage_public_url($filePath);
                            } else {
                                $filePathUrl = $base_url . $filePath;
                            }
                        }

                        $fileDataTwo = $iN->iN_GetUploadedFileDetails($vIDPhotoOfCard);
                        if ($fileDataTwo) {
                            $filePathTwo = $fileDataTwo['uploaded_file_path'];
                            if (function_exists('storage_public_url')) {
                                $filePathUrlTwo = storage_public_url($filePathTwo);
                            } else {
                                $filePathUrlTwo = $base_url . $filePathTwo;
                            }
                        }
                        ?>
                        <div class="i_post_image_swip_wrapper" data-bg="<?php echo iN_HelpSecure($filePathUrl); ?>" data-src="<?php echo iN_HelpSecure($filePathUrl); ?>">
                            <img class="i_p_image" src="<?php echo iN_HelpSecure($filePathUrl); ?>">
                        </div>
                        <div class="i_post_image_swip_wrapper" data-bg="<?php echo iN_HelpSecure($filePathUrlTwo); ?>" data-src="<?php echo iN_HelpSecure($filePathUrlTwo); ?>">
                            <img class="i_p_image" src="<?php echo iN_HelpSecure($filePathUrlTwo); ?>">
                        </div>
                        <?php echo '</div>'; ?>
                    </div>
                    <div class="admin_approve_post_footer">
                        <div class="add_app_not">
                            <?php echo iN_HelpSecure($LANG['add_not_to_the_request_owner']); ?>
                        </div>
                        <div class="i_not_container flex_" id="i_not_container_<?php echo iN_HelpSecure($verificationRequestedUserID); ?>">
                            <textarea class="more_textarea" name="approve_not" id="ad_not_ed_<?php echo iN_HelpSecure($vID); ?>" placeholder="<?php echo iN_HelpSecure($LANG['write_your_not']); ?>"></textarea>
                        </div>
                        <div class="i_not_container flex_ column" id="i_not_container_<?php echo iN_HelpSecure($verificationRequestedUserID); ?>">
                            <div class="approve_ch_item flex_ column border_one transition choosed" id="appr_1" data-val="1">
                                <div class="flex_ tabing_non_justify">
                                    <div class="approve_icon flex_ tabing">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('112')); ?>
                                    </div>
                                    <div class="approve_title flex_ tabing__justify">
                                        <?php echo iN_HelpSecure($LANG['approve']); ?>
                                    </div>
                                </div>
                                <div class="rec_not padding_left_ten">
                                    <?php echo iN_HelpSecure($LANG['be_carefuly_check_verifiction']); ?>
                                </div>
                            </div>
                            <div class="approve_ch_item flex_ column border_one transition" id="appr_2" data-val="2">
                                <div class="flex_ tabing_non_justify">
                                    <div class="reject_icon flex_ tabing">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('113')); ?>
                                    </div>
                                    <div class="approve_title flex_ tabing__justify">
                                        <?php echo iN_HelpSecure($LANG['reject']); ?>
                                    </div>
                                </div>
                                <div class="rec_not padding_left_ten">
                                    <?php echo iN_HelpSecure($LANG['rejected_verification_not']); ?>
                                </div>
                            </div>
                            <input type="hidden" name="vApproveStatus" id="approve_type" value="1">
                        </div>
                        <div class="warning_wrapper warning_one">
                            <?php echo iN_HelpSecure($LANG['warning_approve_profile_choose']); ?>
                        </div>
                        <div class="i_settings_wrapper_item successNot">
                            <?php echo iN_HelpSecure($LANG['updated_successfully']); ?>
                        </div>
                        <div class="i_become_creator_box_footer">
                            <input type="hidden" name="f" value="updateVerificationStatus">
                            <input type="hidden" name="vID" value="<?php echo iN_HelpSecure($vID); ?>">
                            <button type="submit" name="submit" class="i_nex_btn_btn transition" id="update_myprofile">
                                <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/verificationGalleryInit.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
