<div class="i_created_live_streamings_container">
    <div class="i_list_live_streams flex_">
        <div class="i_l_see_others flex_">
            <a href="<?php echo $base_url . 'live_streams?live=both'; ?>" class="flex_">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('143')); ?>
                <span class="flex_">
                    <?php echo iN_HelpSecure($LANG['see_the_otherss']); ?>
                </span>
            </a>
        </div>

        <?php if ($logedIn == '1' && $paidLiveStreamingStatus == '1' && $feesStatus == '2') { ?>
            <div class="c_live_streaming flex_ cNLive" data-type="paidLive">
                <div class="i_live_p_icon flex_">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                </div>
                <div class="c_live_plus flex_">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                </div>
            </div>
        <?php } ?>

        <?php if ($logedIn == '1' && $freeLiveStreamingStatus == '1') { ?>
            <div class="c_live_streaming flex_ cNLive" data-type="freeLive">
                <div class="i_live_p_icon flex_">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('115')); ?>
                </div>
                <div class="c_live_plus flex_">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                </div>
            </div>
        <?php } ?>

        <!-- Live Stream List -->
        <?php
        $LiveStreamingList = $iN->iN_LiveStreamingListWidget($showingNumberOfPost);
        if ($LiveStreamingList) {
            foreach ($LiveStreamingList as $liData) {
                $liveID = $liData['live_id'] ?? NULL;
                $liveName = $liData['live_name'] ?? NULL;
                $live_Status = $liData['live_type'] ?? NULL;
                $liveCredit = $liData['live_credit'] ?? NULL;
                $liveCreatorID = $liData['live_uid_fk'] ?? NULL;
                $liveUserAvatar = $iN->iN_UserAvatar($liveCreatorID, $base_url);
                $liveCreatorUserName = $liData['i_username'] ?? NULL;
                $liveCreatorUserFullName = $liData['i_user_fullname'] ?? NULL;
                $liveFinishTime = $liData['finish_time'] ?? NULL;
                $remaining = $liveFinishTime - time();
                $remaminingTime = date('i', $remaining);

                $checkLiveStreamPurchased = '';
                if ($logedIn == '1') {
                    $checkLiveStreamPurchased = $iN->iN_CheckUserPurchasedThisLiveStream($userID, $liveID);
                }

                if ($logedIn != '1') {
                    $userID = '1';
                }

                $lStat = ($live_Status === 'free') 
                    ? '<div class="i_live_paid flex_">' . $iN->iN_SelectedMenuIcon('115') . '</div>' 
                    : '<div class="i_live_paid flex_">' . $iN->iN_SelectedMenuIcon('40') . '</div>';
                ?>

                <div class="i_list_live_i_c flex_">
                    <?php echo html_entity_decode($lStat); ?>
                    <a href="<?php echo iN_HelpSecure($base_url) . 'live/' . iN_HelpSecure($liveCreatorUserName); ?>">
                        <div class="i_list_live_owner">
                            <img src="<?php echo iN_HelpSecure($liveUserAvatar); ?>">
                        </div>
                    </a>
                </div>

                <?php
            }
        }
        ?>
        <!-- /Live Stream List -->
    </div>
</div>