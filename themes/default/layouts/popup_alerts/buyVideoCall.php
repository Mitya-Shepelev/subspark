<div class="i_modal_bg_in videoCalli" role="dialog" aria-modal="true" aria-labelledby="videoCallModalTitle">
    <div class="i_modal_in_in modal_tip">
        <div class="i_modal_content">
            <div class="call_details">
                <div class="caller_user_avatar">
                    <div class="caller_avatar" style="background-image:url(<?php echo iN_HelpSecure($callerUserAvatar); ?>);" aria-hidden="true"></div>
                </div>

                <?php if ($subStatus === 'subscriber') { ?>
                    <div class="caller_title" id="videoCallModalTitle">
                        <?php echo iN_HelpSecure(preg_replace('/{.*?}/', $callerUserFullName, $LANG['press_call_button'])); ?>
                    </div>
                <?php } else { ?>
                    <?php if ($whoCanVideoCall == '1' && $userCurrentPoints < $videoCallPrice) { ?>
                        <div class="current_point_box_video">
                            <div class="current_balance_box flex_ tabing_non_justify">
                                <?php echo iN_HelpSecure($LANG['not_enough_make_video_call']); ?>
                            </div>
                            <div class="current_balance_box flex_ tabing_non_justify">
                                <?php echo iN_HelpSecure($LANG['point_balance']); ?>
                                <span class="crnblnc"><?php echo number_format($userCurrentPoints); ?></span>
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                <a href="<?php echo iN_HelpSecure($base_url . 'purchase/purchase_point'); ?>" target="_blank" class="transitions">
                                    <?php echo iN_HelpSecure($LANG["get_points"]); ?>
                                </a>
                            </div>
                        </div>
                    <?php } elseif ($whoCanVideoCall == '1') { ?>
                        <div class="caller_title">
                            <?php echo iN_HelpSecure(preg_replace('/{.*?}/', $callerUserFullName, $LANG['invitation_will_be_send_after_payment'])); ?>
                        </div>
                        <div class="caller_det">
                            <?php echo iN_HelpSecure(preg_replace('/{.*?}/', $videoCallPrice, $LANG['give_point'])); ?>
                        </div>
                        <div class="current_point_box_video">
                            <div class="current_balance_box flex_ tabing_non_justify">
                                <?php echo iN_HelpSecure($LANG['point_balance']); ?>
                                <span class="crnblnc"><?php echo number_format($userCurrentPoints); ?></span>
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                <a href="<?php echo iN_HelpSecure($base_url . 'purchase/purchase_point'); ?>" target="_blank" class="transitions">
                                    <?php echo iN_HelpSecure($LANG["get_points"]); ?>
                                </a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="caller_title">
                            <?php echo iN_HelpSecure(preg_replace('/{.*?}/', $callerUserFullName, $LANG['press_call_button'])); ?>
                        </div>
                    <?php } ?>
                <?php } ?>

                <div class="call_buttons flex_ tabing">
                    <?php if ($subStatus === 'subscriber') { ?>
                        <div class="call_btn_item flex_ tabing">
                            <div class="call_btn_item_btn_accept flex_ tabing joinVideoCall" role="button" aria-label="Join Call">
                                <?php echo iN_HelpSecure($LANG['call']); ?>
                            </div>
                        </div>
                    <?php } elseif ($whoCanVideoCall == '1' && $userCurrentPoints >= $videoCallPrice) { ?>
                        <div class="call_btn_item flex_ tabing">
                            <div class="call_btn_item_btn_accept flex_ tabing joinVideoCall" role="button" aria-label="Pay and Call">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('151')); ?>
                                <?php echo iN_HelpSecure($LANG['pay']) . ' ' . $videoCallPrice . ' <span>' . iN_HelpSecure($LANG['point']) . '</span>'; ?>
                            </div>
                        </div>
                    <?php } elseif ($whoCanVideoCall == '0') { ?>
                        <div class="call_btn_item flex_ tabing">
                            <div class="call_btn_item_btn_accept flex_ tabing joinVideoCall" role="button" aria-label="Join Call">
                                <?php echo iN_HelpSecure($LANG['call']); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="call_btn_item flex_ tabing">
                        <div class="call_btn_item_btn_decline flex_ tabing call_decline" role="button" aria-label="Cancel Call">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('31')); ?>
                            <?php echo iN_HelpSecure($LANG['cancel']); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
