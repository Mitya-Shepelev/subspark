<?php
$alertMap = [
    'not_Shared' => [
        'type' => 'warning_alert',
        'icon' => '60',
        'title' => $LANG['can_not_share_title'],
        'desc' => $LANG['can_not_share_desc']
    ],
    'not_file' => [
        'type' => 'notice_alert',
        'icon' => '60',
        'title' => $LANG['file_not_found'],
        'desc' => $LANG['file_not_found_not']
    ],
    'sWrong' => [
        'type' => 'warning_alert',
        'icon' => '60',
        'title' => $LANG['noway'],
        'desc' => $LANG['noway_desc']
    ],
    'eCouldNotEmpty' => [
        'type' => 'warning_alert',
        'icon' => '60',
        'title' => $LANG['noway'],
        'desc' => $LANG['can_not_save_blank_post']
    ],
    'delete_success' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['post_deleted'],
        'desc' => $LANG['delete_success']
    ],
    'delete_not_success' => [
        'type' => 'warning_alert',
        'icon' => '60',
        'title' => $LANG['noway'],
        'desc' => $LANG['post_could_not_be_delete']
    ],
    'urlCopied' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['copied'],
        'desc' => $LANG['link_copied']
    ],
    'commentClosed' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['comments_opened'],
        'desc' => $LANG['comments_enabled_success']
    ],
    'commentOpened' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['comments_disabled'],
        'desc' => $LANG['comments_disabled_success']
    ],
    'pinClosed' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['pinClosed'],
        'desc' => $LANG['pinClosed_notification_desc']
    ],
    'pined' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['post_pined_on_your_profile'],
        'desc' => $LANG['post_pined_on_your_profile_notification_desc']
    ],
    'sAdded' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['added_in_saved_list'],
        'desc' => $LANG['post_in_your_saved_list']
    ],
    'sRemoved' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['removed_in_saved_list'],
        'desc' => $LANG['post_removed_in_saved_list']
    ],
    'delete_comment_success' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['comment_deleted'],
        'desc' => $LANG['delete_comment_success']
    ],
    'delete_comment_not_success' => [
        'type' => 'warning_alert',
        'icon' => '60',
        'title' => $LANG['noway'],
        'desc' => $LANG['post_could_not_be_delete']
    ],
    'youFollowing' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['follow'],
        'desc' => $LANG['you_are_following']
    ],
    'youUnfollowing' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['unfollow'],
        'desc' => $LANG['you_are_unfollowing']
    ],
    'shared_storie_success' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['storie_shard'],
        'desc' => $LANG['storie_shared_success']
    ],
    'cnbowni' => [
        'type' => 'warning_alert',
        'icon' => '60',
        'title' => $LANG['come_on'],
        'desc' => $LANG['you_can_not_buy_your_own_product']
    ],
    'tipSuccess' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['tip_sended_success'],
        'desc' => $LANG['thanks_for_kind_gift']
    ],
    'camOffline' => [
        'type' => 'warning_alert',
        'icon' => '32',
        'title' => $LANG['oops'],
        'desc' => $LANG['user_offline_for_video_call']
    ],
    'cNotSend' => [
        'type' => 'warning_alert',
        'icon' => '32',
        'title' => $LANG['oopsSomethingMissing'],
        'desc' => $LANG['cannotsendemptyprmessage']
    ],
    'frameSuccess' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['frame_changed_title'],
        'desc' => $LANG['frame_changed_success']
    ],
    'emailSendsuccess' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['emailSendtitle'],
        'desc' => $LANG['emailSendnot']
    ],
    'buySuccess' => [
        'type' => 'warning_alert',
        'icon' => '61',
        'title' => $LANG['frame_purchased'],
        'desc' => $LANG['you_purchased_frame_success']
    ],
    'upload_connection_lost' => [
        'type' => 'warning_alert',
        'icon' => '60',
        'title' => $LANG['upload_connection_lost_title'],
        'desc' => $LANG['upload_connection_lost_desc']
    ]
];

if (isset($alertMap[$alertType])) {
    $alert = $alertMap[$alertType];
    ?>
    <div class="i_bottom_left_alert_container <?php echo iN_HelpSecure($alert['type']); ?> animated fadeInUp">
        <div class="i_alert_wrapper">
            <div class="i_alert_close transition">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
            </div>
            <div class="<?php echo $alert['icon'] === '61' ? 'i_alert_icon_tick' : 'i_alert_icon'; ?>">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon($alert['icon'])); ?>
            </div>
            <div class="i_alert_notes">
                <div class="i_alert_title">
                    <?php echo iN_HelpSecure($alert['title']); ?>
                </div>
                <div class="i_alert_desc">
                    <?php echo iN_HelpSecure($alert['desc']); ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
