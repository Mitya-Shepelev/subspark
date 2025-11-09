<div class="i_general_box_message_notifications_container generalBox">
    <div class="btest">
        <div class="i_user_details">
            <!--MESSAGE HEADER-->
            <div class="i_box_messages_header">
                <?php echo iN_HelpSecure($LANG['messages']); ?>
                <div class="i_message_full_screen transition">
                    <a href="<?php echo iN_HelpSecure($base_url); ?>chat">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('48')); ?>
                    </a>
                </div>
            </div>
            <!--/MESSAGE HEADER-->

            <div class="i_header_others_box">
                <?php
                $cList = $iN->iN_ChatUserList($userID, $scrollLimit);
                if ($cList) {
                    foreach ($cList as $cData) {
                        $chatID = $cData['chat_id'] ?? null;
                        $chatUserIDOne = $cData['user_one'] ?? null;
                        $chatUserIDTwo = $cData['user_two'] ?? null;

                        $cUID = ($chatUserIDOne == $userID) ? $chatUserIDTwo : $chatUserIDOne;
                        $chatUserAvatar = $iN->iN_UserAvatar($cUID, $base_url);
                        $chatUserDetails = $iN->iN_GetUserDetails($cUID);
                        $chatUserName = $chatUserDetails['i_username'] ?? null;
                        $chatUserFullName = $chatUserDetails['i_user_fullname'] ?? null;
                        $chatUserGender = $chatUserDetails['user_gender'] ?? null;

                        if ($chatUserGender === 'male') {
                            $publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
                        } elseif ($chatUserGender === 'female') {
                            $publisherGender = '<div class="i_plus_gf">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
                        } elseif ($chatUserGender === 'couple') {
                            $publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
                        }

                        $latestChatMessage = $iN->iN_GetLatestMessage($chatID);
                        $message = $latestChatMessage['message'] ?? null;
                        $messageSeenStatus = $latestChatMessage['seen_status'] ?? null;
                        $messageFile = $latestChatMessage['file'] ?? null;
                        $messageSticker = $latestChatMessage['sticker_url'] ?? null;
                        $messageGif = $latestChatMessage['gifurl'] ?? null;
                        $gifMoney = $latestChatMessage['gifMoney'] ?? null;
                        $privateStatus = $latestChatMessage['private_status'] ?? null;
                        $privatePrice = $latestChatMessage['private_price'] ?? null;

                        if ($messageFile) {
                            $message = $iN->iN_SelectedMenuIcon('53') . $LANG['isImage'];
                        }

                        if (!empty($messageSticker)) {
                            $message = $iN->iN_SelectedMenuIcon('24') . $LANG['isSticker'];
                        }

                        if (!empty($messageGif)) {
                            $message = $iN->iN_SelectedMenuIcon('23') . $LANG['isGif'];
                        }

                        $messageSeenStatusBgColor = '';
                        if ($messageSeenStatus == '0') {
                            $messageSeenStatusBgColor = 'notSeenYet';
                        }
                        ?>
                        <!--MESSAGE-->
                        <div class="i_message_wrpper">
                            <a href="<?php echo iN_HelpSecure($base_url) . 'chat?chat_width=' . $chatID; ?>">
                                <div class="i_message_wrapper <?php echo iN_HelpSecure($messageSeenStatusBgColor); ?> transition">
                                    <div class="i_message_owner_avatar">
                                        <div class="i_message_avatar">
                                            <img src="<?php echo iN_HelpSecure($chatUserAvatar); ?>" alt="<?php echo iN_HelpSecure($chatUserFullName); ?>">
                                        </div>
                                    </div>
                                    <div class="i_message_info_container">
                                        <div class="i_message_owner_name">
                                            <?php echo iN_HelpSecure($chatUserFullName); ?>
                                            <?php echo html_entity_decode($publisherGender); ?>
                                        </div>
                                        <div class="i_message_i">
                                            <?php if ($privateStatus == 'closed' && trim($privatePrice) != '') { ?>
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('14')) . iN_HelpSecure($LANG['locked_message']); ?>
                                            <?php } else {
                                                if ($message) {
                                                    echo $urlHighlight->highlightUrls($message);
                                                }
                                            } ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!--/MESSAGE-->
                        <?php
                    }
                }
                ?>
            </div>

            <?php if (empty($Notifications) && !isset($Notifications)) { ?>
                <div class="no_not_here tabing flex_">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('104')); ?>
                </div>
            <?php } ?>
        </div>

        <div class="footer_container messages">
            <a href="<?php echo iN_HelpSecure($base_url); ?>chat">
                <?php echo iN_HelpSecure($LANG['see_all_in_messenger']); ?>
            </a>
        </div>
    </div>
</div>