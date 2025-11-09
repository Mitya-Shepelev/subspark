<?php
$lastPostID = isset($_POST['last']) ? trim($_POST['last']) : null;
$validTypes = ['paid', 'free', 'both'];

if (isset($liveListType) && in_array($liveListType, $validTypes, true)) {
    $liveListData = ($liveListType === 'both')
        ? $iN->iN_LiveStreaminsListAllType($lastPostID, $showingNumberOfPost)
        : $iN->iN_LiveStreaminsList($liveListType, $lastPostID, $showingNumberOfPost);

    if (!empty($liveListData)) {
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

            $liveFinishTime = $liData['finish_time'] ?? null;
            $remaining = (int)$liveFinishTime - time();
            $remainingTime = date('i', $remaining);

            $checkLiveStreamPurchased = '';
            if ($logedIn === '1') {
                $checkLiveStreamPurchased = $iN->iN_CheckUserPurchasedThisLiveStream($userID, $liveID);
            } else {
                $userID = '1';
            }
?>
<div class="live_list_box_wrapper mor" data-last="<?php echo iN_HelpSecure($liveID); ?>">
    <div class="live_list_box_wrapper_in">
        <div class="live_creator_cover flex_" style="background-image:url('<?php echo iN_HelpSecure($liveUserCover); ?>');">
            <div class="live_s">LIVE</div>
            <?php if (!$liveCredit) { ?>
                <div class="count_time flex_ alignItem">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('115')); ?>
                    <?php echo iN_HelpSecure($remainingTime) . $LANG['minutes_left']; ?>
                </div>
            <?php } ?>
            <img class="live_creator_cover_img" src="<?php echo iN_HelpSecure($liveUserCover); ?>" alt="Cover">
        </div>
        <div class="live_creator_avatar">
            <div class="live_creator_avatar_middle">
                <div class="i_live_profile_avatar" style="background-image:url('<?php echo iN_HelpSecure($liveUserAvatar); ?>');"></div>
            </div>
        </div>
        <div class="live_stream_creator_name">
            <a href="<?php echo iN_HelpSecure($base_url . $liveCreatorUserName); ?>">
                <?php echo iN_HelpSecure($liveCreatorUserFullName); ?>
            </a>
        </div>
        <div class="live_stream_creator_name">
            <?php echo iN_HelpSecure($liveName); ?>
        </div>
        <?php if ($liveCredit && $userID !== $liveCreatorID && !$checkLiveStreamPurchased) { ?>
            <div class="pr_liv">
                <div class="purchaseLiveButton flex_ tabing" id="<?php echo iN_HelpSecure($liveID); ?>">
                    <?php echo iN_HelpSecure($LANG['join']); ?>
                    <strong class="tabing flex_ i_inline_flex">
                        <?php echo number_format($liveCredit); ?>
                        <span class="prcsic">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                        </span>
                    </strong>
                </div>
            </div>
        <?php } elseif ($logedIn !== '1') { ?>
            <div class="pr_liv loginForm">
                <div class="purchaseLiveButton flex_ tabing" id="<?php echo iN_HelpSecure($liveID); ?>">
                    <?php echo iN_HelpSecure($LANG['join']); ?>
                </div>
            </div>
        <?php } else { ?>
            <a href="<?php echo iN_HelpSecure($base_url . 'live/' . $liveCreatorUserName); ?>">
                <div class="pr_liv">
                    <div class="purchaseLiveButton flex_ tabing" id="<?php echo iN_HelpSecure($liveID); ?>">
                        <?php echo iN_HelpSecure($LANG['join']); ?>
                    </div>
                </div>
            </a>
        <?php } ?>
    </div>
</div>
<?php
        }
    } else {
        echo '
        <div class="noPost">
            <div class="noPostIcon">' . $iN->iN_SelectedMenuIcon('54') . '</div>
            <div class="noPostNote">' . iN_HelpSecure($LANG['no_live_streams']) . '</div>
        </div>';
    }
}
?>