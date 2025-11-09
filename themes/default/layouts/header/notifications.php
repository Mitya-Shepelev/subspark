<div class="i_general_box_notifications_container generalBox">
  <div class="btest">
    <div class="i_user_details">
      <!-- NOTIFICATION HEADER -->
      <div class="i_box_messages_header">
        <?php echo iN_HelpSecure($LANG['notifications']); ?>
        <div class="i_message_full_screen transition">
          <a href="<?php echo iN_HelpSecure($base_url); ?>notifications">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('48')); ?>
          </a>
        </div>
      </div>
      <!-- /NOTIFICATION HEADER -->

      <div class="i_header_others_box">
        <?php if (!empty($Notifications)) {
          foreach ($Notifications as $notData) {
            $notificationID = $notData['not_id'];
            $notificationStatus = $notData['not_status'];
            $notPostID = $notData['not_post_id'];
            $notificationType = $notData['not_type'];
            $notificationTypeType = $notData['not_not_type'];
            $notificationTime = $notData['not_time'];
            $notCreator = $notData['not_iuid'];
            $notCreatorDetails = $iN->iN_GetUserDetails($notCreator);
            $notCreatorUserName = $notCreatorDetails['i_username'];
            $notCreatorUserFullName = $fullnameorusername === 'no' ? $notCreatorUserName : ($notCreatorDetails['i_user_fullname'] ?? $notCreatorUserName);
            $notificationCreatorAvatar = $iN->iN_UserAvatar($notCreator, $base_url);

            // Default values
            $notText = '';
            $notIcon = '';
            $notUrl = '#';

            switch ($notificationTypeType) {
              case 'commented':
                $notText = $LANG['commented_on_your_post'];
                $notIcon = $iN->iN_SelectedMenuIcon('20');
                $notUrl = $base_url . 'post/' . $notPostID;
                break;
              case 'verification_approved':
                $notText = $LANG['your_confirmation_accepted_email_title'];
                $notIcon = $iN->iN_SelectedMenuIcon('11');
                $notUrl = $base_url . 'creator/becomeCreator';
                break;
              case 'verification_declined':
                $notText = $LANG['your_confirmation_declined_email_title'];
                $notIcon = $iN->iN_SelectedMenuIcon('5');
                $notUrl = $base_url . 'creator/becomeCreator';
                break;
              case 'postLike':
              case 'commentLike':
                $notText = $notificationTypeType === 'postLike' ? $LANG['liked_your_post'] : $LANG['liked_your_comment'];
                $notIcon = $iN->iN_SelectedMenuIcon('18');
                $notUrl = $base_url . 'post/' . $notPostID;
                break;
              case 'follow':
                $notText = $LANG['is_now_following_your_profile'];
                $notIcon = $iN->iN_SelectedMenuIcon('66');
                $notUrl = $base_url . $notCreatorUserName;
                break;
              case 'subscribe':
                $notText = $LANG['is_subscribed_your_profile'];
                $notIcon = $iN->iN_SelectedMenuIcon('51');
                $notUrl = $base_url . $notCreatorUserName;
                break;
              case 'accepted_post':
                $notText = $LANG['accepted_post'];
                $notIcon = $iN->iN_SelectedMenuIcon('69');
                $notUrl = $base_url . 'post/' . $notPostID;
                break;
              case 'rejected_post':
              case 'declined_post':
                $notText = $notificationTypeType === 'rejected_post' ? $LANG['rejected_post'] : $LANG['declined_post'];
                $notIcon = $iN->iN_SelectedMenuIcon('5');
                $notUrl = $base_url . 'post/' . $notPostID;
                break;
              case 'umentioned':
                $notText = $LANG['mentioned_u'];
                $notIcon = $iN->iN_SelectedMenuIcon('37');
                $notUrl = $base_url . 'post/' . $notPostID;
                break;
              case 'purchasedYourPost':
                $notText = $LANG['congratulations_you_sold'];
                $notIcon = $iN->iN_SelectedMenuIcon('175');
                $notUrl = $base_url . 'post/' . $notPostID;
                break;
            }
        ?>
          <!-- NOTIFICATION ITEM -->
          <div class="i_message_wrpper hidNot_<?php echo iN_HelpSecure($notificationID); ?>">
            <a href="<?php echo iN_HelpSecure($notUrl); ?>">
              <div class="i_message_wrapper transition">
                <div class="i_message_owner_avatar">
                  <div class="i_message_not_icon flex_ tabing"><?php echo html_entity_decode($notIcon); ?></div>
                  <div class="i_message_avatar">
                    <img src="<?php echo iN_HelpSecure($notificationCreatorAvatar); ?>" alt="<?php echo iN_HelpSecure($notCreatorUserFullName); ?>">
                  </div>
                </div>
                <div class="i_message_info_container">
                  <div class="i_message_owner_name"><?php echo iN_HelpSecure($notCreatorUserFullName); ?></div>
                  <div class="i_message_i"><?php echo iN_HelpSecure($notText); ?></div>
                </div>
              </div>
            </a>
          </div>
          <!-- /NOTIFICATION ITEM -->
        <?php } // endforeach 
        } else { ?>
        <div class="no_not_here tabing flex_">
          <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('103')); ?>
        </div>
        <?php } ?>
      </div>
    </div>
    <!-- SEE ALL NOTIFICATIONS LINK -->
      <div class="footer_container messages">
        <a href="<?php echo iN_HelpSecure($base_url); ?>notifications">
          <?php echo iN_HelpSecure($LANG['see_all_notifications']); ?>
        </a>
      </div>
  </div>
</div>
