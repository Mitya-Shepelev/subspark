<?php
$creatorTypeUrl = $iN->iN_GetCreatorFromUrl($iN->iN_Secure($pageCreator), $lastPostID, $scrollLimit);

if ($creatorTypeUrl) {
    foreach ($creatorTypeUrl as $td) {
        $popularuserID = $td['iuid'];
        $uD = $iN->iN_GetUserDetails($popularuserID);
        $popularUserAvatar = $iN->iN_UserAvatar($popularuserID, $base_url);
        $creatorCover = $iN->iN_UserCover($popularuserID, $base_url);
        $popularUserName = $uD['i_username'];
        $popularUserFullName = ($fullnameorusername === 'no') ? $popularUserName : $uD['i_user_fullname'];
        $uPCategory = $uD['profile_category'];
        $totalPost = $iN->iN_TotalPosts($popularuserID);
        $totalImagePost = $iN->iN_TotalImagePosts($popularuserID);
        $totalVideoPosts = $iN->iN_TotalVideoPosts($popularuserID);
        ?>

        <div class="creator_list_box_wrp mor" data-last="<?php echo iN_HelpSecure($popularuserID); ?>">
            <div class="creator_l_box flex_">
                <div class="creator_l_cover" style="background-image:url(<?php echo iN_HelpSecure($creatorCover); ?>);"></div>

                <div class="creator_l_avatar_name">
                    <div class="creator_avatar_container">
                        <a href="<?php echo iN_HelpSecure($base_url . $popularUserName); ?>">
                            <div class="creator_avatar">
                                <img src="<?php echo iN_HelpSecure($popularUserAvatar); ?>" alt="creator">
                            </div>
                        </a>
                    </div>

                    <div class="creator_nm transition">
                        <a href="<?php echo iN_HelpSecure($base_url . $popularUserName); ?>">
                            <?php echo iN_HelpSecure($popularUserFullName); ?>
                        </a>
                    </div>

                    <div class="i_p_cards">
                        <div class="i_creator_category">
                            <a href="<?php echo iN_HelpSecure($base_url . 'creators?creator=' . $uPCategory); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('65')) . iN_HelpSecure($PROFILE_CATEGORIES[$uPCategory]); ?>
                            </a>
                        </div>
                    </div>

                    <div class="i_p_items_box_">
                        <div class="i_btn_item_box transition">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('67')) . ' ' . iN_HelpSecure($totalPost); ?>
                        </div>
                        <div class="i_btn_item_box transition">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('68')) . ' ' . iN_HelpSecure($totalImagePost); ?>
                        </div>
                        <div class="i_btn_item_box transition">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52')) . ' ' . iN_HelpSecure($totalVideoPosts); ?>
                        </div>
                    </div>

                    <div class="creator_last_two_post flex_ tabing">
                        <?php
                        $getLatestFivePost = $iN->iN_ExploreUserLatestFivePost($popularuserID);
                        if ($getLatestFivePost) {
                            foreach ($getLatestFivePost as $suggestedData) {
                                $userPostID = $suggestedData['post_id'];
                                $userPostFile = $suggestedData['post_file'];
                                $slugUrl = $base_url . 'post/' . $suggestedData['url_slug'] . '_' . $userPostID;
                                $userPostWhoCanSee = $suggestedData['who_can_see'];
                                $explodeFiles = array_unique(array_filter(explode(',', rtrim($userPostFile, ','))));
                                $lastFileID = end($explodeFiles);
                                $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $popularuserID);
                                $fileData = $iN->iN_GetUploadedFileDetails($lastFileID);

                                if ($fileData) {
                                    $fileExtension = $fileData['uploaded_file_ext'];
                                    $filePath = $fileData['uploaded_file_path'];

                                    if ($userPostWhoCanSee !== '1' && !in_array($getFriendStatusBetweenTwoUser, ['me', 'subscriber'])) {
                                        $filePath = $fileData['uploaded_x_file_path'];
                                    }

                                    $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                                    if (function_exists('storage_public_url')) {
                                        $filePathUrl = storage_public_url($filePath);
                                    } else {
                                        $filePathUrl = $base_url . $filePath;
                                    }

                                    if ($fileExtension === 'mp4') {
                                        $thumbPath = $filePathWithoutExt . '.png';
                                        if (function_exists('storage_public_url')) {
                                            $filePathUrl = storage_public_url($thumbPath);
                                        } else {
                                            $filePathUrl = $base_url . $thumbPath;
                                        }
                                        $videoPlaybutton = '<div class="playbutton">' . $iN->iN_SelectedMenuIcon('55') . '</div>';
                                    }

                                    // Visibility Badge
                                    $onlySubs = '';
                                    if ($userPostWhoCanSee !== '1') {
                                        $iconID = ($userPostWhoCanSee === '2' || $userPostWhoCanSee === '3') ? '56' : '40';
                                        $onlySubs = '<div class="onlySubsSuggestion"><div class="onlySubsSuggestionWrapper"><div class="onlySubsSuggestion_icon">' . $iN->iN_SelectedMenuIcon($iconID) . '</div></div></div>';
                                    }
                                    ?>
                                    <div class="creator_last_post_item">
                                        <div class="creator_last_post_item-box" style="background-image: url('<?php echo iN_HelpSecure($filePathUrl); ?>');">
                                            <a href="<?php echo iN_HelpSecure($slugUrl); ?>">
                                                <?php
                                                if (!in_array($getFriendStatusBetweenTwoUser, ['me', 'subscriber'])) {
                                                    echo html_entity_decode($onlySubs);
                                                }
                                                ?>
                                                <img class="creator_last_post_item-img" src="<?php echo iN_HelpSecure($filePathUrl); ?>" alt="post">
                                            </a>
                                        </div>
                                    </div>
                                <?php }
                            }
                        } else {
                            echo '<div class="no_content tabing flex_">' . iN_HelpSecure($LANG['no_posts_yet']) . '</div>';
                        } ?>
                    </div>
                </div>
            </div>
        </div>

    <?php }
} else {
    echo '<div class="no_creator_f_wrap flex_ tabing mor"><div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div><div class="n_c_t">' . iN_HelpSecure($LANG['no_more_creator_will_be_shown']) . '</div></div>';
}
?>
