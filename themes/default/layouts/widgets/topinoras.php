<?php
$tops = $iN->iN_PopularUsersFromLastWeek();
if ($tops) {
    echo '
    <div class="sp_wrp">
    <div class="suggested_products">
    <div class="i_right_box_header">
     ' . iN_HelpSecure($LANG['best_creators_last_week']) . '
    </div>
    <div class="i_topinoras_wrapper">
    ';
    $i = '1';
    foreach ($tops as $td) {
        $popularuserID = $td['post_owner_id'];
        $uD = $iN->iN_GetUserDetails($popularuserID);
        $popularUserAvatar = $iN->iN_UserAvatar($popularuserID, $base_url);
        $popularUserName = $td['i_username'];
        $popularUserFullName = $td['i_user_fullname'];
        if ($fullnameorusername == 'no') {
            $popularUserFullName = $popularUserName;
        }
        $subscBTN = '';
        if (!$logedIn) {
            $userID = '';
            $subscBTN = 'loginForm';
        }
        $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $popularuserID);
        $flwrBtn = '';
        if ($getFriendStatusBetweenTwoUser == 'flwr') {
            $flwrBtn = '';
        } else if ($getFriendStatusBetweenTwoUser == 'subscriber') {
            $flwrBtn = '';
        } else {
            if ($popularuserID != $userID) {
                $flwrBtn = '<div class="i_sub_flw"><div class="i_follow_me i_follow transition i_btn_like_item ' . $subscBTN . ' free_follow i_fw' . $popularuserID . '" id="i_btn_like_item" data-u="' . $popularuserID . '">' . $iN->iN_SelectedMenuIcon('66') . $LANG['follow'] . '</div></div>';
            }
        }

?>
        <!-- top_users_template.php -->
        <div class="i_top_inora transition">
            <div class="i_top_inora_number"><span><?php echo iN_HelpSecure($i); ?></span></div>
            <div class="i_top_inora_avatar_wrapper">
                <div class="i_top_u">
                    <div class="i_hot_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9')); ?></div>
                    <div class="i_top_inora_avatar">
                        <img src="<?php echo iN_HelpSecure($popularUserAvatar); ?>" alt="<?php echo iN_HelpSecure($popularUserFullName); ?>">
                    </div>
                </div>
            </div>
            <div class="i_top_inora_user_name_hot_name">
                <div class="i_top_inora_user_name"><a class="truncated style_display_block" href="<?php echo iN_HelpSecure($base_url) . $popularUserName; ?>"><?php echo iN_HelpSecure($popularUserFullName); ?></a> <div class="i_plus_s"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('11')); ?></div></div>
                <div class="i_top_inora_hot_name">
                    <?php
                    if ($i == '1') {
                        echo iN_HelpSecure($LANG['super_active']);
                    } else if ($i == '2') {
                        echo iN_HelpSecure($LANG['very_active']);
                    } else if ($i == '3') {
                        echo iN_HelpSecure($LANG['active_']);
                    } else if ($i == '4') {
                        echo iN_HelpSecure($LANG['active_']);
                    } else {
                        echo iN_HelpSecure($LANG['active_']);
                    } ?>
                </div>
            </div>
            <?php echo html_entity_decode($flwrBtn); ?>
        </div>

<?php        $i++;
    }
    echo '
    </div>
    </div>
    </div>';
}
?>
