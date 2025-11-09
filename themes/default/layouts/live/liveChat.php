<?php
$liveMessageList = $iN->iN_LiveChatMessages($theLiveID, $userID);

if (!empty($liveMessageList)) {
    foreach ($liveMessageList as $lmData) {
        $messageID = $lmData['cm_id'] ?? null;
        $messageLiveUserID = $lmData['cm_iuid_fk'] ?? null;
        $messageLiveTime = $lmData['cm_time'] ?? null;
        $liveMessage = $lmData['cm_message'] ?? '';
        $giftSended = $lmData['cm_gift_type'] ?? null;

        $msgData = $iN->iN_GetUserDetails($messageLiveUserID);
        $msgUserName = $msgData['i_username'] ?? '';
        $msgUserFullName = $msgData['i_user_fullname'] ?? '';

        if ($fullnameorusername === 'no') {
            $msgUserFullName = $msgUserName;
        }

        $getLiveGiftDataFromID = $iN->GetLivePlanDetails($giftSended);
        $giftImage = $getLiveGiftDataFromID['gift_image'] ?? '';
        $giftHTML = '';
        $giftColorStyle = '';

        if (!empty($giftSended) && !empty($giftImage)) {
            $giftHTML = '
                <span class="gift_attan">' . iN_HelpSecure($LANG['send_you_a_gift']) . '</span>
                <img src="' . iN_HelpSecure($base_url . $giftImage) . '" alt="Gift" loading="lazy" width="24" />
            ';
            $giftColorStyle = 'giftColorStyle';
        }

        $userProfileURL = iN_HelpSecure($base_url . $msgUserName);
        $safeFullName = iN_HelpSecure($msgUserFullName);
        $safeMessage = iN_HelpSecure($liveMessage);
        $safeMessageID = iN_HelpSecure($messageID);
?>
        <div class="gElp9 flex_ tabing_non_justify eo2As cUq_<?php echo $safeMessageID; ?>" id="<?php echo $safeMessageID; ?>">
            <a href="<?php echo $userProfileURL; ?>" class="<?php echo iN_HelpSecure($giftColorStyle);?>" target="_blank" title="<?php echo $safeFullName; ?>">
                <?php echo $safeFullName; ?>
            </a>
            <?php echo iN_HelpSecure($safeMessage); ?>
            <?php echo html_entity_decode($giftHTML); ?>
        </div>
<?php
    }
}
?>