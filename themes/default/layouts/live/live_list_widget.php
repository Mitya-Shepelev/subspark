<?php
$lastPostID = isset($_POST['last']) ? trim($_POST['last']) : '';
$lastPostID = iN_HelpSecure($lastPostID); 

$liveListType = 'both';
$pages = 'both';

if (!empty($liveListType)) {
    $liveListData = $iN->iN_LiveStreaminsListAllTypeSuggested($lastPostID, $userID, $showingNumberOfPost);

    if (!empty($liveListData)) {
        echo '<div class="i_right_box_header">Suggested Live Streamings</div>';

        foreach ($liveListData as $liData) {
            $liveID = $liData['live_id'] ?? null;
            $liveName = $liData['live_name'] ?? '';
            $liveCredit = $liData['live_credit'] ?? null;
            $liveCreatorID = $liData['live_uid_fk'] ?? null;
            $liveUserAvatar = $iN->iN_UserAvatar($liveCreatorID, $base_url);
            $liveUserCover = $iN->iN_UserCover($liveCreatorID, $base_url);
            $liveCreatorUserName = $liData['i_username'] ?? '';
            $liveCreatorUserFullName = $liData['i_user_fullname'] ?? '';

            if ($fullnameorusername === 'no') {
                $liveCreatorUserFullName = $liveCreatorUserName;
            }

            $liveFinishTime = $liData['finish_time'] ?? time();
            $remaining = (int)$liveFinishTime - time();
            $remainingTime = date('i', $remaining);

            $checkLiveStreamPurchased = '';
            if ($logedIn === '1') {
                $checkLiveStreamPurchased = $iN->iN_CheckUserPurchasedThisLiveStream($userID, $liveID);
            } else {
                $userID = '1';
            }
?>
            <div class="i_left_menu_box transition">
                <a href="<?php echo iN_HelpSecure($base_url . 'live/' . $liveCreatorUserName); ?>">
                    <div class="i_live_user_avatar">
                        <img src="<?php echo iN_HelpSecure($liveUserAvatar); ?>" alt="Avatar of <?php echo iN_HelpSecure($liveCreatorUserFullName); ?>" />
                    </div>
                    <div class="m_tit"><?php echo iN_HelpSecure($liveCreatorUserFullName); ?></div>
                </a>
            </div>
<?php
        } // end foreach
    } // end if liveListData
} // end if liveListType
?>