<div class="th_middle">
    <div class="pageMiddle">
        <div class="notificationsContainer">
            <div class="notificationsHeader">
                <?php echo iN_HelpSecure($LANG['notifications']); ?>
            </div>

            <div class="i_header_others_box" id="moreType" data-type="notifications">
                <?php if ($Notifications) {
                    foreach ($Notifications as $notData) {
                        $notificationID = $notData['not_id'] ?? null;
                        $notificationStatus = $notData['not_status'] ?? null;
                        $notPostID = $notData['not_post_id'] ?? null;
                        $notificationType = $notData['not_type'] ?? null;
                        $notificationTypeType = $notData['not_not_type'] ?? null;
                        $notificationTime = $notData['not_time'] ?? null;
                        $notCreator = $notData['not_iuid'] ?? null;

                        $notCreatorDetails = $iN->iN_GetUserDetails($notCreator);
                        $notCreatorUserName = $notCreatorDetails['i_username'] ?? null;
                        $notCreatorUserFullName = $notCreatorDetails['i_user_fullname'] ?? null;
                        $notCreatorUserFullName = ($fullnameorusername === 'no') ? $notCreatorUserName : $notCreatorUserFullName;

                        $notificationCreatorAvatar = $iN->iN_UserAvatar($notCreator, $base_url);

                        switch ($notificationTypeType) {
                            case 'commented':
                                $notText = $LANG['commented_on_your_post'];
                                $notIcon = $iN->iN_SelectedMenuIcon('20');
                                $notUrl = $base_url . 'post/' . $notPostID;
                                break;
                            case 'verification_approved':
                                $notText = $LANG['your_confirmation_accepted_email_title'];
                                $notIcon = $iN->iN_SelectedMenuIcon('11');
                                $notUrl = $base_url . 'creator/becomeCreator';
                                break;
                            case 'verification_declined':
                                $notText = $LANG['your_confirmation_declined_email_title'];
                                $notIcon = $iN->iN_SelectedMenuIcon('5');
                                $notUrl = $base_url . 'creator/becomeCreator';
                                break;
                            case 'postLike':
                                $notText = $LANG['liked_your_post'];
                                $notIcon = $iN->iN_SelectedMenuIcon('18');
                                $notUrl = $base_url . 'post/' . $notPostID;
                                break;
                            case 'commentLike':
                                $notText = $LANG['liked_your_comment'];
                                $notIcon = $iN->iN_SelectedMenuIcon('18');
                                $notUrl = $base_url . 'post/' . $notPostID;
                                break;
                            case 'follow':
                                $notText = $LANG['is_now_following_your_profile'];
                                $notIcon = $iN->iN_SelectedMenuIcon('66');
                                $notUrl = $base_url . $notCreatorUserName;
                                break;
                            case 'subscribe':
                                $notText = $LANG['is_subscribed_your_profile'];
                                $notIcon = $iN->iN_SelectedMenuIcon('51');
                                $notUrl = $base_url . $notCreatorUserName;
                                break;
                            case 'accepted_post':
                                $notText = $LANG['accepted_post'];
                                $notIcon = $iN->iN_SelectedMenuIcon('69');
                                $notUrl = $base_url . 'post/' . $notPostID;
                                break;
                            case 'rejected_post':
                            case 'declined_post':
                                $notText = $LANG['rejected_post'];
                                $notIcon = $iN->iN_SelectedMenuIcon('5');
                                $notUrl = $base_url . 'post/' . $notPostID;
                                break;
                            case 'umentioned':
                                $notText = $LANG['mentioned_u'];
                                $notIcon = $iN->iN_SelectedMenuIcon('37');
                                $notUrl = $base_url . 'post/' . $notPostID;
                                break;
                            case 'purchasedYourPost':
                                $notText = $LANG['congratulations_you_sold'];
                                $notIcon = $iN->iN_SelectedMenuIcon('175');
                                $notUrl = $base_url . 'post/' . $notPostID;
                                break;
                            default:
                                continue 2; // Skip this notification
                        }
                        ?>

                        <!-- Single Notification Item -->
                        <div class="i_notification_wrpper mor hidNot_<?php echo iN_HelpSecure($notificationID); ?> body_<?php echo iN_HelpSecure($notificationID); ?>" id="<?php echo iN_HelpSecure($notificationID); ?>" data-last="<?php echo iN_HelpSecure($notificationID); ?>">
                            <a href="<?php echo iN_HelpSecure($notUrl, FILTER_VALIDATE_URL); ?>">
                                <div class="i_notification_wrapper transition">
                                    <div class="i_message_owner_avatar">
                                        <div class="i_message_not_icon flex_ tabing">
                                            <?php echo html_entity_decode($notIcon); ?>
                                        </div>
                                        <div class="i_message_avatar">
                                            <img src="<?php echo iN_HelpSecure($notificationCreatorAvatar); ?>" alt="<?php echo iN_HelpSecure($notCreatorUserFullName); ?>" />
                                        </div>
                                    </div>
                                    <div class="i_message_info_container">
                                        <div class="i_message_owner_name">
                                            <?php echo iN_HelpSecure($notCreatorUserFullName); ?>
                                        </div>
                                        <div class="i_message_i">
                                            <?php echo iN_HelpSecure($notText); ?>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <!-- Notification Settings -->
                            <div class="i_message_setting msg_Set_<?php echo iN_HelpSecure($notificationID); ?> msg_Set" id="<?php echo iN_HelpSecure($notificationID); ?>">
                                <div class="i_message_set_icon">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('16')); ?>
                                </div>
                                <div class="i_message_set_container msg_Set msg_Set_<?php echo iN_HelpSecure($notificationID); ?>">
                                    <!-- Menu: Remove Notification -->
                                    <div class="i_post_menu_item_out transition hidNot" id="<?php echo iN_HelpSecure($notificationID); ?>">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
                                        <?php echo iN_HelpSecure($LANG['remove_this_notification']); ?>
                                    </div>
                                    <!-- Menu: Mark as Read -->
                                    <div class="i_post_menu_item_out transition">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('47')); ?>
                                        <?php echo iN_HelpSecure($LANG['mark_as_read']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Single Notification Item -->

                <?php
                    } // end foreach
                } // end if
                ?>
            </div>
        </div>
    </div>
</div>
