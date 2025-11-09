<?php
// Hide reels page completely if feature is disabled
if (isset($reelsFeatureStatus) && (string)$reelsFeatureStatus !== '1') {
    header('Location: ' . (isset($base_url) ? $base_url : '/'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php
    include "layouts/header/meta.php";
    include "layouts/header/css.php";
    include "layouts/header/javascripts.php";
    ?>
    <link rel="stylesheet" href="<?php echo $themePath; ?>/scss/reels_style.css?v=<?php echo iN_HelpSecure($version); ?>">

</head>
<body>
<div id="fallbackShareModal" class="share-modal" style="display: none;">
  <div class="modal-content">
    <p><?php echo iN_HelpSecure($LANG['copy_link_below']); ?></p>
    <input type="text" id="fallbackShareInput" readonly>
    <div class="modal-buttons">
      <button type="button" onclick="copyFallbackLink()"><?php echo iN_HelpSecure($LANG['copy']); ?></button>
      <button type="button" onclick="closeFallback()"><?php echo iN_HelpSecure($LANG['close']); ?></button>
    </div>
  </div>
</div>
    <div class="exit_reels_and_site_logo">
        <div class="exit_reels_wraper">
            <a href="<?php echo iN_HelpSecure($base_url); ?>"><div class="exit_reels"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?></div></a>
            <div class="i_logo tabing flex_">
                <a href="<?php echo iN_HelpSecure($base_url); ?>">
                    <img src="<?php echo iN_HelpSecure($siteLogoUrl); ?>">
                </a>
            </div>
            <div class="reels_title"><?php echo iN_HelpSecure($LANG['reels']); ?></div>
        </div>
    </div>
<?php

$reelID = null;
$requestUriParts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
if (isset($requestUriParts[0]) && $requestUriParts[0] === 'reels') {
    if (isset($requestUriParts[1]) && is_numeric($requestUriParts[1])) {
        $reelUploadID = intval($requestUriParts[1]);

        $foundPostID = DB::col("SELECT post_id FROM i_posts WHERE FIND_IN_SET(?, post_file) > 0 AND post_type = 'reels' LIMIT 1", [$reelUploadID]);
        if ($foundPostID !== false && $foundPostID !== null) {
            $reelID = intval($foundPostID);
        }
    }
}


if ($logedIn == 0) {
    $loginFormClass = 'loginForm';
} else {
    $loginFormClass = '';
}

$initialReels = $iN->iN_GetInitialReels($reelID, 2, isset($userID)? (int)$userID : 0);
?>
<div class="reels-container" id="reelsContainer">
<?php if (!$initialReels || (is_array($initialReels) && count($initialReels) === 0)) { ?>
    <div class="no_content tabing flex_"><?php echo iN_HelpSecure($LANG['no_posts_yet']); ?></div>
<?php } else { foreach ($initialReels as $index => $reel) {
    // Güvenlik filtreleri
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
    $userPostOwnerID = $reel['post_owner_id'] ?? null;
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
    $reelPostText = $reel['post_text'] ?? NULL;
    $userPostOwnerUsername = $reel['i_username'] ?? null;
    $userPostOwnerUserFullName = $reel['i_user_fullname'] ?? null;
    if ($fullnameorusername == 'no') {
        $userPostOwnerUserFullName = $userPostOwnerUsername;
    }
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
            . preg_replace('/{.*?}/', $userPostOwnerUserFullName ?? '', $LANG['only_followers']) . '</div>' . '<div class="i_fw' . iN_HelpSecure($reelOwnerId) . ' transition ' . iN_HelpSecure($flwrBtn) . '" id="i_btn_like_item" data-u="' . iN_HelpSecure($reelOwnerId) . '">'
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
<!--Reels Started-->
    <div class="reel<?php if (isset($reelID) && $reelID == $reel['post_id']) { echo ' active'; } elseif (!isset($reelID) && $index === 0) { echo ' active'; } ?> body_<?php echo iN_HelpSecure($userPostID); ?>" data-index="<?php echo $index; ?>" data-reel-id="<?php echo htmlspecialchars($reel['post_id']); ?>">
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
                        <strong><?php echo iN_HelpSecure($userPostOwnerUserFullName); ?></strong>
                    </div>
                    <?php
                    if (!empty($reelPostText)) {
                        $cleanedText = $iN->sanitize_output(
                            $iN->iN_RemoveYoutubelink($reelPostText),
                            $base_url
                        );

                        $highlightedText = $urlHighlight->highlightUrls($cleanedText);
                        $shouldShowReadMore = mb_strlen(strip_tags($cleanedText)) > 90;
                        ?>
                        <div class="description-wrapper truncated" id="descWrapper">
                            <div class="description" id="descBox">
                                <?php echo $highlightedText; ?>
                            </div>
                            <?php if ($shouldShowReadMore) { ?>
                                <div class="read-more" id="readMore"><?php echo iN_HelpSecure($LANG['show_more']); ?></div>
                            <?php } ?>
                        </div>
                    <?php } ?>
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
<!--Reels Finished-->
<?php } } ?>
</div>
 <div class="scroll-buttons">
  <button id="scrollUpBtn" class="scroll-btn">
      <svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M7.5 1C7.66148 1 7.81301 1.07798 7.90687 1.20938L12.9069 8.20938C13.0157 8.36179 13.0303 8.56226 12.9446 8.72879C12.8589 8.89533 12.6873 9 12.5 9H10V11.5C10 11.7761 9.77614 12 9.5 12H5.5C5.22386 12 5 11.7761 5 11.5V9H2.5C2.31271 9 2.14112 8.89533 2.05542 8.72879C1.96972 8.56226 1.98427 8.36179 2.09314 8.20938L7.09314 1.20938C7.18699 1.07798 7.33853 1 7.5 1ZM3.4716 8H5.5C5.77614 8 6 8.22386 6 8.5V11H9V8.5C9 8.22386 9.22386 8 9.5 8H11.5284L7.5 2.36023L3.4716 8Z" fill="#ffffff"></path> </g></svg>
  </button>
  <button id="scrollDownBtn" class="scroll-btn">
      <svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path fill-rule="evenodd" clip-rule="evenodd" d="M5 3.5C5 3.22386 5.22386 3 5.5 3H9.5C9.77614 3 10 3.22386 10 3.5V6H12.5C12.6873 6 12.8589 6.10467 12.9446 6.27121C13.0303 6.43774 13.0157 6.63821 12.9069 6.79062L7.90687 13.7906C7.81301 13.922 7.66148 14 7.5 14C7.33853 14 7.18699 13.922 7.09314 13.7906L2.09314 6.79062C1.98427 6.63821 1.96972 6.43774 2.05542 6.27121C2.14112 6.10467 2.31271 6 2.5 6H5V3.5ZM6 4V6.5C6 6.77614 5.77614 7 5.5 7H3.4716L7.5 12.6398L11.5284 7H9.5C9.22386 7 9 6.77614 9 6.5V4H6Z" fill="#ffffff"></path> </g></svg>
  </button>
</div>
<div id="seekFeedback" class="seek-feedback"></div>
<div id="noMoreContentMessage" class="noMoreContentMessage">
    <div class="noMoreReels"><?php echo iN_HelpSecure($LANG['no_more_content']); ?></div>
</div>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/reelsHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/reelsScrollhandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>

</body>
</html>
