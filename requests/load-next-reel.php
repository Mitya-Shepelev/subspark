<?php
include "../includes/inc.php";
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit($LANG['method_not_allowed']);
}

$lastId = isset($_POST['last_id']) ? (int) $_POST['last_id'] : 0;
if ($lastId <= 0) {
    exit($LANG['invalid_id']);
}
$uid = isset($userID) ? (int)$userID : 0;
$sql = "
    SELECT P.post_id, P.post_file, P.post_text, P.post_owner_id, P.who_can_see, P.post_wanted_credit, P.comment_status,
           U.i_username, U.user_avatar, U.i_user_fullname
    FROM i_posts P
    INNER JOIN i_users U ON P.post_owner_id = U.iuid
    WHERE P.post_type = 'reels'
      AND (P.post_status = '1' OR P.post_owner_id = ?)
      AND P.post_id < ?
    ORDER BY P.post_id DESC
    LIMIT 1";

$first = DB::one($sql, [$uid, $lastId]);
if (!$first) { exit; }
if ($logedIn == 0) {
    $loginFormClass = 'loginForm';
} else {
    $loginFormClass = '';
}
if ($first) {
    $reel = $first;
        $postFileId = isset($reel['post_file']) ? htmlspecialchars(trim($reel['post_file'])) : '';
        $videoSrc = '';

        if (strpos($postFileId, ',') !== false) {
            $postFileId = explode(',', $postFileId)[0];
        }
        $uploadedFilePath = DB::col("SELECT uploaded_file_path FROM i_user_uploads WHERE upload_id = ? LIMIT 1", [(int)$postFileId]);
        $missingVideo = false;
        if ($uploadedFilePath !== false && $uploadedFilePath !== null) {
            $videoSrc = htmlspecialchars($uploadedFilePath);
            if (!$videoSrc || trim($videoSrc) === '') {
                $missingVideo = true;
            }
        } else {
            $missingVideo = true;
        }
        $userPostOwnerID = $reel['post_id'] ?? null;
    $userPostID = $reel['post_id'] ?? null;
    $userPostCommentAvailableStatus = $reel['comment_status'] ?? null;
    $pSaveStatusBtn = $iN->iN_SelectedMenuIcon('22');
    if($logedIn == 0){
      $getFriendStatusBetweenTwoUser = '1';
      $checkPostLikedBefore ='';
      $checkPostReportedBefore = '';
      $checkUserPurchasedThisPost = '0';
   }else{
      $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $userPostOwnerID);
      $checkPostLikedBefore = $iN->iN_CheckPostLikedBefore($userID, $userPostID);
      $checkPostReportedBefore = $iN->iN_CheckPostReportedBefore($userID, $userPostID);
      if($iN->iN_CheckPostSavedBefore($userID, $userPostID) == '1'){
         $pSaveStatusBtn = $iN->iN_SelectedMenuIcon('63');
      }
       $checkUserPurchasedThisPost = $iN->iN_CheckUserPurchasedThisPost($userID, $userPostID);
   }
   if($checkPostReportedBefore == '1'){
      $postReportStatus = $iN->iN_SelectedMenuIcon('32').$LANG['unreport'];
   }
   if($checkPostLikedBefore){
      $likeIcon = $iN->iN_SelectedMenuIcon('18');
      $likeClass = 'in_unlike';
   }else{
      $likeIcon = $iN->iN_SelectedMenuIcon('17');
      $likeClass = 'in_like';
   }
   if($userPostCommentAvailableStatus == '1'){
      $commentStatusText = $LANG['disable_comment'] ?? null;
   }else{
      $commentStatusText = $LANG['enable_comments'] ?? null;
   }
   $likeSum = $iN->iN_TotalPostLiked($userPostID);
   if($likeSum > '0'){
      $likeSum = $likeSum;
   }else{
      $likeSum = '';
   }
   $getUserComments = $iN->iN_GetPostComments($userPostID, 0);
   $commentCountDisplay = '';
   $TotallyPostComment = '';
   if (!empty($getUserComments)) {
      $CountTheUniqComment = count($getUserComments);
      $commentCountDisplay = (int) $CountTheUniqComment;
      if ($CountTheUniqComment > 2) {
         $SecondUniqComment = $CountTheUniqComment - 2;
         $getUserComments = $iN->iN_GetPostComments($userPostID, 2);
         $TotallyPostComment = '<div class="lc_sum_container lc_sum_container_' . $userPostID . '"><div class="comnts transition more_comment" id="od_com_' . $userPostID . '" data-id="' . $userPostID . '">' . preg_replace( '/{.*?}/', $SecondUniqComment, $LANG['t_comments']) . '</div></div>';
      }
   }
   if (empty($commentCountDisplay)) {
      $commentCountDisplay = '';
   }
   $slugUrl = $base_url.'reels/'. $userPostID;
   $privacy = $reel['who_can_see'] ?? '1';
    $reelPostID = $reel['post_id'] ?? null;
    $reelOwnerId = $reel['post_owner_id'] ?? 0;
    $userPostOwnerUsername = $reel['i_username'] ?? null;
    $userPostOwnerUserFullName = $reel['i_user_fullname'] ?? $userPostOwnerUsername;
    $userPostWantedCredit = $reel['post_wanted_credit'] ?? null;
    $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $reelOwnerId);

    $canView = false;
    $onlySubs = '';
    $flwrBtn = 'i_btn_like_item free_follow';
    $flwBtnIconText = $iN->iN_SelectedMenuIcon('66').$LANG['follow'];
    if ($privacy == '1') {
        $canView = true;
    }elseif ($privacy == '2') {
        if ($getFriendStatusBetweenTwoUser === 'subscriber' || $getFriendStatusBetweenTwoUser === 'me') {
            $canView = true;
        } else {
            $onlySubs = '<div class="com_min_height"></div><div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">'
            . $iN->iN_SelectedMenuIcon('56') . '</div><div class="onlySubs_note">'
            . preg_replace('/{.*?}/', $userPostOwnerUserFullName ?? '', $LANG['only_subscribers']) . '</div><div class="fr_subs uSubsModal transition" data-u="' . $reelOwnerId . '">'
            . $iN->iN_SelectedMenuIcon('51') . $LANG['free_for_subscribers'] . '</div></div></div>';
        }
    }elseif ($privacy == '3') {
        if ($getFriendStatusBetweenTwoUser === 'flwr' || $getFriendStatusBetweenTwoUser === 'subscriber' || $getFriendStatusBetweenTwoUser === 'me') {
            $canView = true;
        } else {
            $onlySubs = '<div class="com_min_height"></div><div class="onlySubs"><div class="onlySubsWrapper"><div class="onlySubs_icon">'
    . $iN->iN_SelectedMenuIcon('15') . '</div><div class="onlySubs_note">'
    . preg_replace('/{.*?}/', $userPostOwnerUserFullName ?? '', $LANG['only_followers']) . '</div>' . '
    <div class="i_fw' . iN_HelpSecure($reelOwnerId) . ' transition ' . iN_HelpSecure($flwrBtn) . '" id="i_btn_like_item" data-u="' . iN_HelpSecure($reelOwnerId) . '">'
    . html_entity_decode($flwBtnIconText) . '</div></div></div>';
        }
    }elseif ($privacy == '4') {
        if ($iN->iN_CheckUserPurchasedThisPost($userID, $reelPostID) || $getFriendStatusBetweenTwoUser === 'me') {
            $canView = true;
        } else {
            $onlySubs = '<div class="com_min_height"></div><div class="onlyPremium"><div class="onlySubsWrapper"><div class="premium_locked"><div class="premium_locked_icon">'
            . $iN->iN_SelectedMenuIcon('56') . '</div></div><div class="onlySubs_note"><div class="buyThisPost prcsPost" id="' . $userPostID . '">'
            . preg_replace('/{.*?}/', $userPostWantedCredit ?? '', $LANG['post_credit']) . '</div><div class="buythistext prcsPost" id="' . $userPostID . '">'
            . $LANG['purchase_post'] . '</div></div><div class="fr_subs uSubsModal transition" data-u="' . $reelOwnerId . '">'
            . $iN->iN_SelectedMenuIcon('51') . $LANG['free_for_subscribers'] . '</div></div></div>';
        }
    }
        ?>
    <div class="reel body_<?php echo iN_HelpSecure($userPostID); ?>" data-reel-id="<?php echo htmlspecialchars($reel['post_id']); ?>">
        <?php if($canView){ ?>
        <div class="reel-inner">
            <?php if (!empty($missingVideo)) { ?>
                <div class="reel-missing" style="color:#fff; text-align:center; padding:24px;">
                    <?php echo iN_HelpSecure($LANG['this_video_no_longer_available']); ?>
                </div>
            <?php } else { ?>
                <div class="video-loader">
                  <div class="spinner"></div>
                </div>
            <div class="custom-controls">
                <div class="volume-control" data-muted="true">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('185')); ?>
                </div>
            </div>

            <video src="<?php echo $base_url.$videoSrc; ?>" autoplay muted playsinline preload="auto" crossorigin="anonymous"></video>

            <div class="reel-ui">
                <div class="left-ui">
                    <div class="user">
                        <strong><?php echo htmlspecialchars($reel['i_username']); ?></strong>
                    </div>

                    <div class="description-wrapper truncated" id="descWrapper">
                      <div class="description" id="descBox">
                        <?php echo htmlspecialchars($reel['post_text']); ?>
                      </div>
                      <div class="read-more" id="readMore"><?php echo iN_HelpSecure($LANG['show_more']); ?></div>
                    </div>
                </div>
                <div class="right-ui">
                    <div class="action i_post_item_btn <?php echo iN_HelpSecure($likeClass); ?> <?php echo iN_HelpSecure($loginFormClass); ?>"  id="p_l_<?php echo iN_HelpSecure($userPostID); ?>" data-id="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($likeIcon); ?></div>
                    <span class="lp_sum flex_ tabing" id="lp_sum_<?php echo iN_HelpSecure($userPostID); ?>"><?php echo iN_HelpSecure($likeSum); ?></span>
                    <div class="action i_post_item_btn transition in_comment"  id="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('20')); ?></div>
                    <span class="lp_sum flex_ tabing"><?php echo iN_HelpSecure($commentCountDisplay); ?></span>
                    <div class="action i_post_item_btn transition svp in_save_<?php echo iN_HelpSecure($userPostID); ?> in_save"  id="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($pSaveStatusBtn); ?>  </div>
                    <div class="action i_post_item_btn transition in_social_share" id="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('21')); ?></div>
                    <div class="action i_post_item_btn transition openPostMenu_reel transition" id="<?php echo iN_HelpSecure($userPostID); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('16')); ?>
                        <!--POST MENU-->
                        <div class="i_post_menu_container mnoBox mnoBox<?php echo iN_HelpSecure($userPostID); ?>">
                            <div class="i_post_menu_item_wrapper">
                                <?php if ($logedIn !== 0 && ($userPostOwnerID === $userID || $userType === '2')) { ?>
                                    <div class="i_post_menu_item_out wcs transition" id="<?php echo iN_HelpSecure($userPostID); ?>">
                                        <span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15')); ?></span>
                                        <?php echo iN_HelpSecure($LANG['whocanseethis']); ?>
                                    </div>
                                    <div class="i_post_menu_item_out edtp transition" id="<?php echo iN_HelpSecure($userPostID); ?>">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')); ?>
                                        <?php echo iN_HelpSecure($LANG['edit_post']); ?>
                                    </div>
                                    <div class="i_post_menu_item_out pcl transition" id="dc_<?php echo iN_HelpSecure($userPostID); ?>" data-id="<?php echo iN_HelpSecure($userPostID); ?>">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('31')); ?>
                                        <?php echo html_entity_decode($commentStatusText); ?>
                                    </div>
                                    <div class="i_post_menu_item_out delp transition" id="<?php echo iN_HelpSecure($userPostID); ?>">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
                                        <?php echo iN_HelpSecure($LANG['delete_post']); ?>
                                    </div>
                                <?php } ?>

                                <div class="i_post_menu_item_out transition copyUrl" data-clipboard-text="<?php echo $slugUrl; ?>">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('30')); ?>
                                    <?php echo iN_HelpSecure($LANG['copy_post_url']); ?>
                                </div>

                                <a class="i_opennewtab" href="<?php echo $slugUrl; ?>" target="_blank">
                                    <div class="i_post_menu_item_out transition">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('183')); ?>
                                        <?php echo iN_HelpSecure($LANG['open_in_new_tab']); ?>
                                    </div>
                                </a>

                                <?php if ($logedIn !== 0 && $userPostOwnerID !== $userID) { ?>
                                    <div class="i_post_menu_item_out transition rpp rpp<?php echo iN_HelpSecure($userPostID); ?>" id="<?php echo iN_HelpSecure($userPostID); ?>">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('32')); ?>
                                        <?php echo iN_HelpSecure($LANG['report_this_post']); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <!--/POST MENU-->
                    </div>
                </div>
                <div class="video-progress">
                <div class="progress-bar">
                  <div class="progress-fill">
                    <div class="progress-dot"></div>
                  </div>
                </div>
                <div class="time-display">0:00 / 0:00</div>
            </div>
            </div>

            <div class="video-overlay"></div>

            <!--Comments-->
            <div class="reels_comments_wrapper this_reels_<?php echo iN_HelpSecure($userPostID);?>">
                <div class="reels_comments_cont">
                    <div class="reels_comments_title"><?php echo iN_HelpSecure($LANG['comments']); ?> <div class="close_reels_comment"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?></div></div>
                    <div class="reels_comments_container" id="i_user_comments_<?php echo iN_HelpSecure($userPostID);?>"></div>
                    <?php echo html_entity_decode($TotallyPostComment); ?>
                    <!---->
                    <div class="reels_make_comment_wrapper">
                        <div class="i_comment_form">
                            <!-- Avatar -->
                            <div class="i_post_user_comment_avatar">
                                <img src="<?php echo iN_HelpSecure($userAvatar); ?>"/>
                            </div>

                            <!-- Textarea + Buttons -->
                            <div class="i_comment_form_textarea" data-id="<?php echo iN_HelpSecure($userPostID); ?>">
                                <div class="i_comment_t_body">
                                    <textarea
                                        name="post_comment"
                                        class="comment nwComment comment_reel_item_<?php echo iN_HelpSecure($userPostID); ?>"
                                        data-id="<?php echo iN_HelpSecure($userPostID); ?>"
                                        placeholder="<?php echo iN_HelpSecure($LANG['write_your_comment']); ?>"></textarea>
                                    <input type="hidden" id="stic_<?php echo iN_HelpSecure($userPostID); ?>">
                                    <input type="hidden" id="cgif_<?php echo iN_HelpSecure($userPostID); ?>">
                                </div>

                                <!-- Fast Buttons -->
                                <div class="i_comment_footer i_comment_footer<?php echo iN_HelpSecure($userPostID); ?>">
                                    <div class="i_comment_fast_answers getStickers<?php echo iN_HelpSecure($userPostID); ?>">
                                        <div class="i_fa_body getGifsr" id="<?php echo iN_HelpSecure($userPostID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('23')); ?>
                                        </div>
                                        <div class="i_fa_body getStickersr" id="<?php echo iN_HelpSecure($userPostID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('24')); ?>
                                        </div>
                                        <div class="i_fa_body getEmojisC<?php echo iN_HelpSecure($userPostID); ?> getEmojisCr"
                                             data-type="emojiBoxC"
                                             data-id="<?php echo iN_HelpSecure($userPostID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('25')); ?>
                                        </div>
                                        <div class="i_fa_body sndcom" id="<?php echo iN_HelpSecure($userPostID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sticker ve GIF Alanları -->
                        <div class="emptyStickerArea emptyStickerArea<?php echo iN_HelpSecure($userPostID); ?>"></div>
                        <div class="emptyGifArea nonePoint emptyGifArea<?php echo iN_HelpSecure($userPostID); ?>">
                            <div class="in_gif_wrapper">
                                <img class="srcGif<?php echo iN_HelpSecure($userPostID); ?>" src="">
                            </div>
                            <div class="removeGif" id="<?php echo iN_HelpSecure($userPostID); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                            </div>
                        </div>
                    </div>
                    <!---->
                </div>
            </div>
            <!--/Comments-->
            <?php } ?>

        </div>
        <?php }else{ ?>
        <div class="reel-inner">
            <?php echo $onlySubs;?>
        </div>
        <?php }?>
    </div>
        <?php
    
}
?>
