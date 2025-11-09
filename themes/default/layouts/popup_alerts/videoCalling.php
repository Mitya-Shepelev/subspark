<div class="i_modal_bg_in videoCall" role="dialog" aria-modal="true" aria-labelledby="videoCallWaitingTitle" aria-describedby="videoCallWaitingDesc">
    <!--SHARE-->
    <div class="i_modal_in_in modal_tip">
        <div class="i_modal_content">
            <!-- Call Details -->
            <div class="call_details">
                <!-- Caller Avatar -->
                <div class="caller_user_avatar">
                    <div class="caller_avatar" style="background-image:url(<?php echo $callerUserAvatar;?>);" role="img" aria-label="<?php echo iN_HelpSecure($callerUserFullName); ?>"></div>
                </div>

                <!-- Modal Title -->
                <div class="caller_title" id="videoCallWaitingTitle">
                    <?php echo iN_HelpSecure($LANG['v_calling']); ?> <?php echo iN_HelpSecure($callerUserFullName); ?>
                </div>

                <!-- Description / Waiting Notice -->
                <div class="caller_det" id="videoCallWaitingDesc">
                    <?php echo iN_HelpSecure($LANG['please_wait_for_your_friend_answer_video_calling']); ?>
                </div>

                <!-- Declined Message -->
                <div class="call_declined" aria-live="polite">
                    <?php echo iN_HelpSecure($LANG['declined_call']); ?>
                </div>

                <!-- Cancel Button -->
                <div class="call_buttons flex_ tabing">
                    <div class="call_btn_item flex_ tabing">
                        <div class="call_btn_item_btn_decline flex_ tabing leave"
                             role="button"
                             tabindex="0"
                             aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                             <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('31')); ?>
                             <?php echo iN_HelpSecure($LANG['cancel']); ?>
                        </div>
                    </div>
                </div>
                <!-- /Cancel Button -->
            </div>
        </div>
    </div>
    <!--/SHARE-->
</div>