<div class="i_post_body body_<?php echo iN_HelpSecure($userPostID); ?> <?php echo iN_HelpSecure($subPostTop); ?>" id="<?php echo iN_HelpSecure($userPostID); ?>" data-last="<?php echo iN_HelpSecure($userPostID); ?>">
<?php echo html_entity_decode($waitingApprove);
echo html_entity_decode($pPinStatus); ?>
    <!--POST HEADER-->
    <div class="i_post_body_header">
        <div class="i_post_user_avatar">
            <img src="<?php echo iN_HelpSecure($userPostOwnerUserAvatar); ?>"/>
            <!---->
            <div class="i_thanks_bubble_cont tip_<?php echo iN_HelpSecure($userPostID); ?>">
                <div class="i_bubble"><?php echo iN_HelpSecure($userTextForPostTip ?? ''); ?></div>
            </div>
            <!---->
        </div>
        <div class="i_post_i">
            <div class="i_post_username"><a class="truncated" href="<?php echo iN_HelpSecure($base_url) . $userPostOwnerUsername; ?>"><?php echo iN_HelpSecure($userPostOwnerUserFullName); ?><?php echo html_entity_decode($publisherGender); ?><?php echo html_entity_decode($userVerifiedStatus); ?><?php echo html_entity_decode($wCanSee); ?></a></div>
            <div class="i_post_shared_time"><?php echo TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?></div>
            <div class="i_post_menu">
                <div class="i_post_menu_dot openPostMenu transition" id="<?php echo iN_HelpSecure($userPostID); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('16')); ?>
                    <!--POST MENU-->
                    <div class="i_post_menu_container mnoBox mnoBox<?php echo iN_HelpSecure($userPostID); ?>">
                       <div class="i_post_menu_item_wrapper">
                           <?php if ($logedIn != 0 && ($userPostOwnerID == $userID || $userType == '2')) {?>
                           <!--MENU ITEM-->
                           <div class="i_post_menu_item_out wcs transition" id="<?php echo iN_HelpSecure($userPostID); ?>">
                              <span><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15')); ?></span> <?php echo iN_HelpSecure($LANG['whocanseethis']); ?>
                           </div>
                           <!--/MENU ITEM-->
                           <!--MENU ITEM-->
                           <div class="i_post_menu_item_out edtp transition" id="<?php echo iN_HelpSecure($userPostID); ?>">
                              <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')); ?> <?php echo iN_HelpSecure($LANG['edit_post']); ?>
                           </div>
                           <!--/MENU ITEM-->
                           <!--MENU ITEM-->
                           <div class="i_post_menu_item_out i_pnp transition pbtn_<?php echo iN_HelpSecure($userPostID); ?>" id="<?php echo iN_HelpSecure($userPostID); ?>">
                              <?php echo html_entity_decode($pPinStatusBtn); ?>
                           </div>
                           <!--/MENU ITEM-->
                           <!--MENU ITEM-->
                           <div class="i_post_menu_item_out pcl transition" id="dc_<?php echo iN_HelpSecure($userPostID); ?>" data-id="<?php echo iN_HelpSecure($userPostID); ?>">
                              <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('31')); ?> <?php echo html_entity_decode($commentStatusText); ?>
                           </div>
                           <!--/MENU ITEM-->
                           <!--MENU ITEM-->
                           <div class="i_post_menu_item_out delp transition" id="<?php echo iN_HelpSecure($userPostID); ?>">
                              <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?> <?php echo iN_HelpSecure($LANG['delete_post']); ?>
                           </div>
                           <!--/MENU ITEM-->
                           <?php }?>
                           <!--MENU ITEM-->
                           <div class="i_post_menu_item_out transition copyUrl" data-clipboard-text="<?php echo $slugUrl; ?>">
                              <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('30')); ?> <?php echo iN_HelpSecure($LANG['copy_post_url']); ?>
                           </div>
                           <!--/MENU ITEM-->
                           <?php if ($logedIn != 0 && ($userPostOwnerID != $userID)) {?>
                           <!--MENU ITEM-->
                           <div class="i_post_menu_item_out transition rpp rpp<?php echo iN_HelpSecure($userPostID); ?>" id="<?php echo iN_HelpSecure($userPostID); ?>">
                              <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('32')); ?> <?php echo iN_HelpSecure($LANG['report_this_post']); ?>
                           </div>
                           <!--/MENU ITEM-->
                           <?php }?>
                       </div>
                    </div>
                    <!--/POST MENU-->
                </div>
            </div>
        </div>
    </div>
    <!--/POST HEADER-->
    <?php if (!empty($userPostText)) {
	?>
    <!--POST CONTAINER-->
    <div class="i_post_container <?php echo iN_HelpSecure($postStyle); ?>" id="i_post_container_<?php echo iN_HelpSecure($userPostID); ?>">
        <!--POST TEXT-->
        <div class="i_post_text" id="i_post_text_<?php echo iN_HelpSecure($userPostID); ?>">
        <?php
    $pStatus = '1'; 
	if ($userPostWhoCanSee != '1') {
		if ($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostStatus != '2' && $userPostWhoCanSee == '3') {
			$pStatus = '0';
		} else if ($userPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me') {
			if ($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber') {
				$pStatus = '0';
			}
		} else if ($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr') {
			$pStatus = '0';
		}
	}
	if ($pStatus == '1') {
		if (!empty($userPostText)) {
			if (isset($userPostHashTags) && !empty($userPostHashTags)) {
				echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($userPostText), $base_url));
			} else {
				echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($userPostText), $base_url));
			}
		}
		$regexUrl = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
		$totalUrl = preg_match_all($regexUrl, $userPostText, $matches);

		$urls = $matches[0];
		// go over all links
		foreach ($urls as $url) {
			$em = new Url_Expand($url);
			// Get the link size
			$site = $em->get_site();

			if ($site != "") {
				// If code is iframe then show the link in iframe
				$code = $em->get_iframe();
				if ($code == "") {
					// If code is embed then show the link in embed
					$code = $em->get_embed();
					if ($code == "") {
						// If code is thumb then show the link medium
						$codesrc = $em->get_thumb("medium");
					}
				}
				echo $code;
			}
		}
	} 
	?>
        </div>
        <!--/POST TEXT-->
    </div>
    <!--/POST CONTAINER-->
    <?php }?>
    <!--POST IMAGES-->
    <div class="i_post_u_images <?php echo iN_HelpSecure($loginFormClass); ?>">
        <?php
            if ($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostWhoCanSee == '3') {
            	echo html_entity_decode($onlySubs);
            } else if ($userPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me') {
            	if ($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber') {
            		echo html_entity_decode($onlySubs);
            	}
            } else if ($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr' && $getFriendStatusBetweenTwoUser != 'subscriber') {
            	echo html_entity_decode($onlySubs);
            }
            $trimValue = rtrim($userPostFile, ',');
            $explodeFiles = explode(',', $trimValue);
            $explodeFiles = array_unique($explodeFiles);
            $countExplodedFiles = $iN->iN_CheckCountFile($userPostFile);
            $container = '';
            if ($countExplodedFiles == 1) {
            	$container = 'i_image_one';
            } else if ($countExplodedFiles == 2) {
            	$container = 'i_image_two';
            } else if ($countExplodedFiles == 3) {
            	$container = 'i_image_three';
            } else if ($countExplodedFiles == 4) {
            	$container = 'i_image_four';
            } else if ($countExplodedFiles >= 5) {
            	$container = 'i_image_five';
            }
        ?>
    </div>
    <!--POST IMAGES-->
	<?php
echo '<div class="myaudio">';
foreach ($explodeFiles as $dataFile) {
	$fileAudioData = $iN->iN_GetUploadedMp3FileDetails($dataFile);
	if($fileAudioData){

		$fileUploadID = $fileAudioData['upload_id'] ?? NULL;
		$fileExtension = $fileAudioData['uploaded_file_ext'] ?? NULL;
		$filePath = $fileAudioData['uploaded_file_path'] ?? NULL;
		$filePathTumbnail = $fileAudioData['upload_tumbnail_file_path'] ?? NULL;

		if ($userPostWhoCanSee != '1') {
			if ($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostStatus != '2' && $userPostWhoCanSee == '3') {
				$filePath = $fileAudioData['uploaded_x_file_path'];
			} else if ($userPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me') {
				if ($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber') {
					$filePath = $fileAudioData['uploaded_x_file_path'] ?? NULL;
				} else {
					$filePath = $fileAudioData['uploaded_file_path'] ?? NULL;
				}
			} else if ($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr' && $getFriendStatusBetweenTwoUser != 'subscriber') {
				$filePath = $fileAudioData['uploaded_x_file_path'] ?? NULL;
			} else {
				if ($getFriendStatusBetweenTwoUser == 'me') {
					$filePath = $fileAudioData['uploaded_file_path'] ?? NULL;
				} else {
					if ($getFriendStatusBetweenTwoUser == 'subscriber' && $userPostWhoCanSee == '3') {
						$filePath = $fileAudioData['upload_tumbnail_file_path'] ?? NULL;
					} else {
						if ($getFriendStatusBetweenTwoUser == 'flwr' || $getFriendStatusBetweenTwoUser == 'subscriber') {
							$filePath = $fileAudioData['upload_tumbnail_file_path'] ?? NULL;
						} else {
							$filePath = $fileAudioData['uploaded_x_file_path'] ?? NULL;
						}
					}
				}
			}
		} else {
			$filePath = $fileAudioData['uploaded_file_path'] ?? NULL;
		}
		if($fileExtension == 'mp3'){
			/*mp3 started*/
			if (function_exists('storage_public_url')) {
				$filePathUrl = storage_public_url($filePath);
				$filePathTumbnailUrl = storage_public_url($fileAudioData['uploaded_file_path'] ?? '');
			} else {
				$filePathUrl = $base_url . $filePath;
				$filePathTumbnailUrl = $base_url . ($fileAudioData['uploaded_file_path'] ?? '');
			}
			$audShowType = '<audio  crossorigin="" preload="none"><source src="'.iN_HelpSecure($filePathUrl).'" type="audio/mp3" /></audio>';
			if (($userPostWhoCanSee == '3' || $userPostWhoCanSee == '4' || $userPostWhoCanSee == '2') && $getFriendStatusBetweenTwoUser != 'me' && $checkUserPurchasedThisPost == '0') {
				if (function_exists('storage_public_url')) {
					$chosen = ($getFriendStatusBetweenTwoUser == 'subscriber') ? ($fileAudioData['uploaded_file_path'] ?? '') : ($fileAudioData['uploaded_x_file_path'] ?? '');
					$filePathTumbnailUrl = storage_public_url($chosen);
					$audShowType = ($getFriendStatusBetweenTwoUser == 'subscriber')
						? '<audio  crossorigin="" preload="none"><source src="'.iN_HelpSecure($filePathUrl).'" type="audio/mp3" /></audio>'
						: '<img class="i_p_image plus_opacity" src="'.$filePathTumbnailUrl.'">';
				} else {
					if ($getFriendStatusBetweenTwoUser == 'subscriber') {
						$filePathTumbnailUrl = $base_url . ($fileAudioData['uploaded_file_path'] ?? '');
						$audShowType = '<audio crossorigin="" preload="none"><source src="'.iN_HelpSecure($filePathUrl).'" type="audio/mp3" /></audio>';
					} else {
						$filePathTumbnailUrl = $base_url . ($fileAudioData['uploaded_x_file_path'] ?? '');
						$audShowType = '<img class="i_p_image plus_opacity" src="'.$filePathTumbnailUrl.'">';
					}
				}
				/**/
			} else {
				if (function_exists('storage_public_url')) {
					$filePathTumbnailUrl = storage_public_url($fileAudioData['uploaded_file_path'] ?? '');
				} else {
					$filePathTumbnailUrl = $base_url . ($fileAudioData['uploaded_file_path'] ?? '');
				}
			}
			$fileisVideo = 'data-src="' . $filePathTumbnailUrl . '"';
			/*mp3 finished*/
		}?>
                <?php if($fileExtension == 'mp3'){?>
					<div class="i_post_image_swip_wrappera" <?php echo html_entity_decode($fileisVideo); ?>>
						<div id="play_po_<?php echo iN_HelpSecure($fileUploadID);?>" class="green-audio-player">
							<?php echo html_entity_decode($audShowType);?>
						</div>
				    </div>
				<?php }?>
	<?php }
}
echo '</div>';
?>
    <!--POST LIKE/COMMENT/SHARE/SOCIAL SHARE/SAVE BUTTONS-->
    <div class="i_post_footer" id="pf_l_<?php echo iN_HelpSecure($userPostID); ?>">
        <div class="i_post_footer_item">
            <div class="i_post_item_btn transition <?php echo iN_HelpSecure($likeClass); ?> <?php echo iN_HelpSecure($loginFormClass); ?>" id="p_l_<?php echo iN_HelpSecure($userPostID); ?>" data-id="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($likeIcon); ?></div>
            <div class="lp_sum flex_ tabing" id="lp_sum_<?php echo iN_HelpSecure($userPostID); ?>"><?php echo iN_HelpSecure($likeSum); ?></div>
        </div>
        <?php if ($logedIn != 0 && $getUserPaymentMethodStatus && $userPostOwnerID != $userID) {?>
        <div class="i_post_footer_item">
           <div class="i_post_item_btn transition in_tips <?php echo iN_HelpSecure($loginFormClass); ?>" data-id="<?php echo iN_HelpSecure($userPostOwnerID); ?>" data-ppid="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('144')); ?></div>
        </div>
        <?php }?>
        <div class="i_post_footer_item">
            <div class="i_post_item_btn transition in_comment <?php echo iN_HelpSecure($loginFormClass); ?>" id="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('20')); ?></div>
        </div>
        <div class="i_post_footer_item">
           <div class="i_post_item_btn transition in_share <?php echo iN_HelpSecure($loginFormClass); ?>"  id="share_<?php echo iN_HelpSecure($userPostID); ?>" data-id="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('19')); ?></div>
        </div>
        <div class="i_post_footer_item">
           <div class="i_post_item_btn transition in_social_share openShareMenu" id="<?php echo iN_HelpSecure($userPostID); ?>">
               <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('21')); ?>
               <!--SHARE POST-->
               <div class="i_share_this_post mnsBox mnsBox<?php echo iN_HelpSecure($userPostID); ?>">
                   <div class="i_share_menu_wrapper">
                       <!--MENU ITEM-->
                        <div class="i_post_menu_item_out transition share-btn"
                             data-social="facebook"
                             data-url="<?php echo iN_HelpSecure($slugUrl); ?>"
                             data-id="<?php echo iN_HelpSecure($userPostID); ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('33')); ?>
                            <?php echo iN_HelpSecure($LANG['share_on_facebook']); ?>
                        </div>
                        <!--/MENU ITEM-->
                        
                        <!--MENU ITEM-->
                        <div class="i_post_menu_item_out transition share-btn"
                             data-social="twitter"
                             data-url="<?php echo iN_HelpSecure($slugUrl); ?>"
                             data-id="<?php echo iN_HelpSecure($userPostID); ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('34')); ?>
                            <?php echo iN_HelpSecure($LANG['share_on_twitter']); ?>
                        </div>
                        <!--/MENU ITEM-->
                   </div>
               </div>
               <!--/SHARE POST-->
           </div>
        </div>
        <div class="i_post_footer_item">
           <div class="i_post_item_btn transition svp in_save_<?php echo iN_HelpSecure($userPostID); ?> in_save" id="<?php echo iN_HelpSecure($userPostID); ?>"><?php echo html_entity_decode($pSaveStatusBtn); ?></div>
        </div>
    </div>
    <!--/POST LIKE/COMMENT/SHARE/SOCIAL SHARE/SAVE BUTTONS-->
    <?php echo html_entity_decode($TotallyPostComment); ?>
    <!--COMMENT FORM COMMENTS-->
    <div class="i_post_comments_wrapper">
        <div class="i_post_comments_box">
            <!--USER COMMENTS-->
            <div class="i_user_comments" name="i_user_comments_<?php echo iN_HelpSecure($userPostID); ?>" id="i_user_comments_<?php echo iN_HelpSecure($userPostID); ?>">
            <?php
if ($getUserComments && $logedIn == 1) {
	foreach ($getUserComments as $comment) {
		$commentID = $comment['com_id'] ?? NULL;
		$commentedUserID = $comment['comment_uid_fk'] ?? NULL;
		$Usercomment = $comment['comment'] ?? NULL;
		$commentTime = $comment['comment_time'] ?? NULL;
		$corTime = date('Y-m-d H:i:s', $commentTime);
		$commentFile = $comment['comment_file'] ?? NULL;
		$stickerUrl = $comment['sticker_url'] ?? NULL;
		$gifUrl = $comment['gif_url'] ?? NULL;
		$commentedUserIDFk = $comment['iuid'] ?? NULL;
		$commentedUserName = $comment['i_username'] ?? NULL;
		$commentedUserFullName = $comment['i_user_fullname'] ?? NULL;
		if($fullnameorusername == 'no'){
			$commentedUserFullName = $commentedUserName;
		}
		$commentedUserAvatar = $iN->iN_UserAvatar($commentedUserID, $base_url);
		$commentedUserGender = $comment['user_gender'] ?? NULL;
		if ($commentedUserGender == 'male') {
			$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
		} else if ($commentedUserGender == 'female') {
			$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
		} else if ($commentedUserGender == 'couple') {
			$cpublisherGender = '<div class="i_plus_comment_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
		}
		$commentedUserLastLogin = $comment['last_login_time'] ?? NULL;
		$commentedUserVerifyStatus = $comment['user_verified_status'] ?? NULL;
		$cuserVerifiedStatus = '';
		if ($commentedUserVerifyStatus == '1') {
			$cuserVerifiedStatus = '<div class="i_plus_comment_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
		}
		$commentLikeBtnClass = 'c_in_like';
		$commentLikeIcon = $iN->iN_SelectedMenuIcon('17');
		$commentReportStatus = $iN->iN_SelectedMenuIcon('32') . $LANG['report_comment'];
		if ($logedIn != 0) {
			$checkCommentLikedBefore = $iN->iN_CheckCommentLikedBefore($userID, $userPostID, $commentID);
			$checkCommentReportedBefore = $iN->iN_CheckCommentReportedBefore($userID, $commentID);
			if ($checkCommentLikedBefore == '1') {
				$commentLikeBtnClass = 'c_in_unlike';
				$commentLikeIcon = $iN->iN_SelectedMenuIcon('18');
			}
			if ($checkCommentReportedBefore == '1') {
				$commentReportStatus = $iN->iN_SelectedMenuIcon('32') . $LANG['unreport'];
			}
		}
		$stickerComment = '';
		$gifComment = '';
		if ($stickerUrl) {
			$stickerComment = '<div class="comment_file"><img src="' . $stickerUrl . '"></div>';
		}
		if ($gifUrl) {
			$gifComment = '<div class="comment_gif_file"><img src="' . $gifUrl . '"></div>';
		}
		include "comments.php";
	}
}
?>
            </div>
            <!--/USER COMMENTS-->
            <?php
            if ($logedIn != 0) {
                if ($userPostCommentAvailableStatus === '1') {
                    include 'comment.php';
                } elseif ($userPostCommentAvailableStatus === '0') {
                    if ($userType === '2' || $userPostOwnerID === $userID) {
                        include 'comment.php';
                    } else {
                        echo '
                            <div class="i_comment_form">
                                <div class="need_login">' . iN_HelpSecure($LANG['comments_limited_for_this_post']) . '</div>
                            </div>';
                    }
                }
            } elseif ($logedIn === 0) {
                ?>
                <div class="i_comment_form">
                    <div class="need_login"><?php echo iN_HelpSecure($LANG['must_login_for_comment']); ?></div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <!--/COMMENT FORM COMMENTS-->
</div>
