<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="followFreeModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in i_sf_box">
        <div class="i_modal_content">
            <!-- User Cover & Profile -->
            <div class="i_f_cover_avatar" style="background-image:url('<?php echo iN_HelpSecure($f_profileCover); ?>');">
                <div class="popClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
                <div class="i_f_avatar_container">
                    <div class="i_f_avatar" style="background-image:url('<?php echo iN_HelpSecure($f_profileAvatar); ?>');" aria-hidden="true"></div>
                </div>
            </div>
            <!-- /User Cover & Profile -->

            <!-- User Info -->
            <div class="i_f_other" id="pr_u_id">
                <div class="i_u_name" id="followFreeModalTitle">
                    <a href="<?php echo iN_HelpSecure($fprofileUrl); ?>">
                        <?php echo iN_HelpSecure($f_userfullname); ?>
                        <?php echo html_entity_decode($fVerifyStatus); ?>
                        <?php echo html_entity_decode($fGender); ?>
                    </a>
                </div>
                <div class="i_u_name_mention">
                    <a href="<?php echo iN_HelpSecure($fprofileUrl); ?>">
                        @<?php echo iN_HelpSecure($f_username); ?>
                    </a>
                </div>

                <div class="i_s_popup_title">
                    <?php echo iN_HelpSecure($LANG['subscription_benefits']); ?>
                </div>

                <!-- Subscription Benefits -->
                <div class="i_advantages_wrapper">
                    <div class="advantage_box">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                        <?php echo iN_HelpSecure($LANG['full_acces_to_conent']); ?>
                    </div>
                    <div class="advantage_box">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                        <?php echo iN_HelpSecure($LANG['direct_message_this_user']); ?>
                    </div>
                    <div class="advantage_box">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                        <?php echo iN_HelpSecure($LANG['cancel_follow_any_time']); ?>
                    </div>
                </div>

                <!-- Subscribe Button -->
                <div class="i_free_subscribe">
                    <div class="i_free_flw f_p_follow transition"
                         data-u="<?php echo iN_HelpSecure($f_userID); ?>"
                         role="button"
                         aria-label="<?php echo iN_HelpSecure($LANG['follow_for_free']); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('66')); ?>
                        <?php echo iN_HelpSecure($LANG['follow_for_free']); ?>
                    </div>
                </div>
                <!-- /Subscribe Button -->
            </div>
            <!-- /User Info -->
        </div>
    </div>
    <!--/SHARE-->
</div>