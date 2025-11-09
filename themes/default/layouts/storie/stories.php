<div class="stories_wrapper flex_">
    <div class="story-view-item-fake chsStoryw" data-background-image="<?php echo iN_HelpSecure($userAvatar)?>">
        <div class="newSto">
            <div class="plusSIc">
                <div class="plstr"><?php echo $iN->iN_SelectedMenuIcon('153')?></div>
            </div>
            <?php echo iN_HelpSecure($LANG['upload_storie_files'])?>
        </div>
    </div>

    <?php
    $LiveStreamingList = $iN->iN_LiveStreamingListWidget($showingNumberOfPost);
    if ($LiveStreamingList) {
        foreach ($LiveStreamingList as $liData) {
            $liveID = $liData['live_id'] ?? null;
            $live_Status = $liData['live_type'] ?? null;
            $liveCreatorID = $liData['live_uid_fk'] ?? null;
            $liveUserAvatar = $iN->iN_UserAvatar($liveCreatorID, $base_url);
            $liveCreatorUserName = $liData['i_username'] ?? null;

            $lStat = '<div class="i_live_paid flex_">' . $iN->iN_SelectedMenuIcon($live_Status === 'free' ? '115' : '40') . '</div>';
    ?>
    <div class="story-view-item-fake" data-background-image="<?php echo iN_HelpSecure($liveUserAvatar); ?>">
        <a class="story-link" href="<?php echo iN_HelpSecure($base_url) . 'live/' . iN_HelpSecure($liveCreatorUserName); ?>">
            <div class="newSto">
                <div class="plusSIc">
                    <div class="plstr"><?php echo html_entity_decode($lStat); ?></div>
                </div>
                <div class="live_now"><?php echo iN_HelpSecure($LANG['live_now'])?></div>
            </div>
        </a>
    </div>
    <?php }} ?>

    <div class="my-stories-wrapper flex_ mystoriesstyle" id="story-view">
        <?php
        $stories = $iN->iN_FriendStoryPost($userID);
        if($stories){
            foreach($stories as $mySData){
                $SotryUploaded = isset($mySData['pics']) ? $mySData['pics'] : NULL;
                $up = explode(",", $SotryUploaded);
                $storySharedOwnerID = $mySData['uid_fk'] ?? null;
                $storieOwnerFullName = $iN->iN_UserFullName($storySharedOwnerID);
                $StorySharedUserAvatar = $iN->iN_UserAvatar($storySharedOwnerID, $base_url);
                $lastStorieImage = $iN->iN_GetLastSharedStatus($storySharedOwnerID);

                if($lastStorieImage){
                    if (function_exists('storage_public_url')) {
                        $lastStoryUrl = storage_public_url($lastStorieImage);
                    } else {
                        $lastStoryUrl = $base_url . $lastStorieImage;
                    }
                }

                $StoryCreatedTime = $mySData['created'] ?? null;
        ?>
        <div class="story-view-item" data-background-image="<?php echo iN_HelpSecure($lastStoryUrl);?>" data-profile-image="<?php echo iN_HelpSecure($StorySharedUserAvatar);?>" data-profile-name="<?php echo iN_HelpSecure($storieOwnerFullName);?>">
            <span class="name truncate"><?php echo iN_HelpSecure($storieOwnerFullName);?></span>
            <div class="story-view-pr-avatar" data-avatar="<?php echo iN_HelpSecure($StorySharedUserAvatar);?>"></div>
            <ul class="media">
            <?php
                foreach ($up as $item) {
                    $stD = $iN->iN_GetUploadedStoriesDataS($item);
                    $final_Image = $stD['uploaded_file_path'] ?? null;
                    $storieText = $stD['text'] ?? null;
                    $storieID = $stD['s_id'] ?? null;
                    $storieTextStyle = $stD['text_style'] ?? 'not';
                    $exts = pathinfo($final_Image, PATHINFO_EXTENSION);

                    if (function_exists('storage_public_url')) {
                        $final_Image = storage_public_url($final_Image);
                    } else {
                        $final_Image = $base_url . $final_Image;
                    }

                    if (in_array($exts, ['mp4', 'MP4'])) {
                        echo '<li class="move_' . $storieID . '" data-id="' . $storieID . '" data-sid="' . $storieID . '" data-duration="" data-time="' . $StoryCreatedTime . '">
                                <video src="' . $final_Image . '" id="video_' . $storieID . '" alt="' . $storieText . '" data-id="' . $storieID . '" type="video/mp4"></video>
                              </li>';
                    } else {
                        echo '<li data-duration="7" data-id="' . $storieID . '" data-sid="' . $storieID . '" data-time="' . $StoryCreatedTime . '">
                                <img src="' . $final_Image . '" data-id="' . $storieID . '" data-ts="' . $storieTextStyle . '" alt="' . $storieText . '">
                              </li>';
                    }
                }
            ?>
            </ul>
        </div>
        <?php }
        }
        ?>
    </div>
</div>
