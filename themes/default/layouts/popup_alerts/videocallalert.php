<div class="i_modal_bg_in videoCall" role="dialog" aria-modal="true" aria-labelledby="videoCallModalTitle" aria-describedby="videoCallModalDesc">
    <!--SHARE-->
    <div class="i_modal_in_in modal_tip">
        <div class="i_modal_content">
            <!-- Video Call Details -->
            <div class="call_details">
                <div class="caller_user_avatar">
                    <div class="caller_avatar" style="background-image:url(<?php echo $callerUserAvatar;?>);" role="img" aria-label="<?php echo iN_HelpSecure($callerUserFullName); ?>"></div>
                </div>

                <!-- Modal Title -->
                <div class="caller_title" id="videoCallModalTitle">
                    <?php echo iN_HelpSecure($LANG['new_video_calling']); ?>
                </div>

                <!-- Modal Description -->
                <div class="caller_det" id="videoCallModalDesc">
                    <?php echo preg_replace('/{.*?}/', $callerUserFullName, $LANG['video_caller']); ?>
                </div>

                <!-- Action Buttons -->
                <div class="call_buttons flex_ tabing">
                    <!-- Accept Call -->
                    <div class="call_btn_item flex_ tabing">
                        <div class="call_btn_item_btn_accept flex_ tabing call_accept"
                             id="<?php echo iN_HelpSecure($chatUrl);?>"
                             role="button"
                             tabindex="0"
                             aria-label="<?php echo iN_HelpSecure($LANG['accept']); ?>">
                             <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('172')); ?>
                             <?php echo iN_HelpSecure($LANG['accept']); ?>
                        </div>
                    </div>

                    <!-- Decline Call -->
                    <div class="call_btn_item flex_ tabing">
                        <div class="call_btn_item_btn_decline flex_ tabing call_decline"
                             id="<?php echo iN_HelpSecure($chatUrl);?>"
                             role="button"
                             tabindex="0"
                             aria-label="<?php echo iN_HelpSecure($LANG['decline']); ?>">
                             <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('31')); ?>
                             <?php echo iN_HelpSecure($LANG['decline']); ?>
                        </div>
                    </div>
                </div>
                <!-- /Action Buttons -->
            </div>
            <!-- /Video Call Details -->
        </div>
    </div>
    <!--/SHARE-->
</div>