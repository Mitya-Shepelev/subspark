<?php if (!empty($searchValueFromData)) { ?>
    <div class="r_u_s"><?php echo iN_HelpSecure($LANG['users_found']); ?></div>
    <?php foreach ($searchValueFromData as $sRData) {
        $resultUserID = $sRData['iuid'];
        $resultUserName = $sRData['i_username'];
        $resultUserFullName = $fullnameorusername === 'no' ? $resultUserName : $sRData['i_user_fullname'];
        $profileUrl = $base_url . $resultUserName;
        $resultUserAvatar = $iN->iN_UserAvatar($resultUserID, $base_url);
    ?>
        <div class="i_message_wrpper">
            <a href="<?php echo iN_HelpSecure($profileUrl); ?>">
                <div class="i_message_wrapper transition">
                    <div class="i_message_owner_avatar">
                        <div class="i_message_avatar">
                            <img src="<?php echo iN_HelpSecure($resultUserAvatar); ?>" alt="<?php echo iN_HelpSecure($resultUserFullName); ?>">
                        </div>
                    </div>
                    <div class="i_message_info_container">
                        <div class="i_message_owner_name"><?php echo iN_HelpSecure($resultUserFullName); ?></div>
                    </div>
                </div>
            </a>
        </div>
    <?php } ?>
<?php } ?>

<?php if (!empty($mentionSearchValueFromData)) { ?>
    <div class="r_u_s"><?php echo iN_HelpSecure($LANG['hashtags_found']); ?></div>
    <?php
    $perPageLimit = 5;
    $i = 0;
 
    $mentionFlatList = implode(',', $mentionSearchValueFromData[0]);
    $mentionArray = explode(',', $mentionFlatList);
    $mentionArray = array_filter($mentionArray); 
    $hashtagCounts = array_count_values($mentionArray);
    arsort($hashtagCounts); 

    foreach ($hashtagCounts as $tag => $count) {
        if ($i >= $perPageLimit) break;
        if (!empty($tag)) {
            $tagUrl = $base_url . 'hashtag/' . urlencode($tag);
    ?>
            <div class="i_message_wrpper">
                <a href="<?php echo iN_HelpSecure($tagUrl); ?>">
                    <div class="i_message_wrapper transition">
                        <div class="i_message_info_container">
                            <div class="i_message_owner_name">#<?php echo iN_HelpSecure($tag); ?></div>
                        </div>
                    </div>
                </a>
            </div>
    <?php
        }
        $i++;
    }
}
?>