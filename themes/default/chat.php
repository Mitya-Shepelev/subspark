<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title><?php echo iN_HelpSecure($siteTitle); ?></title>
    <?php
include "layouts/header/meta.php";
include "layouts/header/css.php";
include "layouts/header/javascripts.php";
?>
</head>
<body class="chat_p_body">
<?php include "layouts/header/header.php";?>
    <div class="wrapper chat_absolute_bottom">
      <div class="i_chat_wrapper flex_">
        <!--CHAT LEFT CONTAINER-->
        <div class="chat_left_container flex_">
           <!--CHAT LEFT HEADER-->
           <div class="chat_left_header flex_">
              <div class="chat_left_header_title"><?php echo iN_HelpSecure($LANG['messages']); ?></div>
              <div class="chat_search_box">
                 <input type="text" id="c_search" class="c_search" placeholder="<?php echo iN_HelpSecure($LANG['search']); ?>">
              </div>
           </div>
           <!--/CHAT LEFT HEADER-->
           <!--CHAT SEARCH RESULTS-->
           <div class="chat_users_wrapper_results nonePoint"></div>
           <!--/CHAT SERACH RESULTS-->
           <!--CHAT PEOPLES-->
           <div class="chat_users_wrapper">
            <?php
$urlChatID = '';
if (isset($_GET['chat_width'])) {
	$cID = isset($_GET['chat_width']) ? (int)$_GET['chat_width'] : 0;
	$checkcIDExist = $iN->iN_CheckChatIDExist($cID);
	if ($checkcIDExist) {
		$urlChatID = $cID;
	}
    $chatOwnersStatus = $iN->iN_CheckChatUserOwnersID($userID, $cID);
    if(!$chatOwnersStatus){
        header('Location: ' . route_url('404'));
        exit();
    }
}
$cList = $iN->iN_ChatUserList($userID, '50');
if ($cList) {
	foreach ($cList as $cData) {
		$chatID = $cData['chat_id'];
		$chatUserIDOne = $cData['user_one'];
		$chatUserIDTwo = $cData['user_two'];
		if ($chatUserIDOne == $userID) {
			$cUID = $chatUserIDTwo;
		} else {
			$cUID = $chatUserIDOne;
		}
		$chatUserAvatar = $iN->iN_UserAvatar($cUID, $base_url);
		$chatUserDetails = $iN->iN_GetUserDetails($cUID);
		$chatUserName = $chatUserDetails['i_username'];
		$chatUserFullName = $chatUserDetails['i_user_fullname'];
		$chatUserGender = $chatUserDetails['user_gender'];
		if ($chatUserGender == 'male') {
			$publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
		} else if ($chatUserGender == 'female') {
			$publisherGender = '<div class="i_plus_gf">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
		} else if ($chatUserGender == 'couple') {
			$publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
		}
		$latestChatMessage = $iN->iN_GetLatestMessage($chatID);
		$message = isset($latestChatMessage['message']) ? $latestChatMessage['message'] : NULL;
		$messageSeenStatus = isset($latestChatMessage['seen_status']) ? $latestChatMessage['seen_status'] : NULL;
		$messageFile = isset($latestChatMessage['file']) ? $latestChatMessage['file'] : NULL;
		$messageSticker = isset($latestChatMessage['sticker_url']) ? $latestChatMessage['sticker_url'] : NULL;
		$messageGif = isset($latestChatMessage['gifurl']) ? $latestChatMessage['gifurl'] : NULL;
		$messagePrivatePrice = isset($latestChatMessage['private_price']) ? $latestChatMessage['private_price'] : NULL;
		if ($messageFile) {
			$message = $iN->iN_SelectedMenuIcon('53') . $LANG['isImage'];
		}
		if (!empty($messageSticker)) {
			$message = $iN->iN_SelectedMenuIcon('24') . $LANG['isSticker'];
		}
		if (!empty($messageGif)) {
			$message = $iN->iN_SelectedMenuIcon('23') . $LANG['isGif'];
		}
		if(!empty($messagePrivatePrice)){
		    $message = $iN->iN_SelectedMenuIcon('14') . $LANG['locked_message'];
		}
        $messageSeenStatusBgColor = '';
		if($messageSeenStatus == '0'){
            $messageSeenStatusBgColor = 'notSeenYet';
		}
		?>
            <!--MESSAGE-->
            <div class="i_message_wrpper">
                <a href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL) . 'chat?chat_width=' . $chatID; ?>">
                <div class="i_message_wrapper <?php echo iN_HelpSecure($messageSeenStatusBgColor);?> transition <?php if ($urlChatID == $chatID) {echo 'talking';}?>">
                    <div class="i_message_owner_avatar"><div class="i_message_avatar"><img src="<?php echo iN_HelpSecure($chatUserAvatar); ?>" alt="<?php echo iN_HelpSecure($chatUserFullName); ?>"></div></div>
                    <div class="i_message_info_container">
                        <div class="i_message_owner_name truncated"><?php echo iN_HelpSecure($chatUserFullName); ?><?php echo html_entity_decode($publisherGender); ?></div>
                        <?php if(!empty($message)){?>
                           <div class="i_message_i"><?php echo $urlHighlight->highlightUrls($iN->iN_RemoveYoutubelink($message));?></div>
                        <?php }?>
                    </div>
                </div>
                </a>
                <div class="i_message_setting msg_Set_<?php echo iN_HelpSecure($chatID); ?> msg_Set" id="<?php echo iN_HelpSecure($chatID); ?>">
                    <div class="i_message_set_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('16')); ?></div>
                    <!--MESSAGE SETTING-->
                    <div class="i_message_set_container msg_Set msg_Set_<?php echo iN_HelpSecure($chatID); ?>">
                    <!--MENU ITEM-->
                    <div class="i_post_menu_item_out transition d_conversation" id="<?php echo iN_HelpSecure($chatID); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?> <?php echo iN_HelpSecure($LANG['delete_message']); ?>
                    </div>
                    <!--/MENU ITEM-->
                    </div>
                    <!--/MESSAGE SETTING-->
                </div>
            </div>
            <!--/MESSAGE-->
            <?php }}?>
           </div>
           <!--/CHAT PEOPLES-->
        </div>
        <!--/CHAT LEFT CONTAINER-->
        <!---->
        <div class="chat_middle_container flex_">
           <?php
$blockedType = '';
if (isset($_GET['chat_width'])) {
	$cID = isset($_GET['chat_width']) ? (int)$_GET['chat_width'] : 0;
	$checkcIDExist = $iN->iN_CheckChatIDExist($cID);
	if ($checkcIDExist) {
		$getChatUserIDs = $iN->iN_GetChatUserIDs($cID);
		$cuIDOne = $getChatUserIDs['user_one'];
		$cuIDTwo = $getChatUserIDs['user_two'];
		if ($cuIDOne == $userID) {
			$conversationUserID = $cuIDTwo;
		} else {
			$conversationUserID = $cuIDOne;
		}

	}
	if ($checkcIDExist) {
		echo '<div class="conversations_container flex_">';
		$lastMessageID = isset($_POST['lastMessageID']) ? $_POST['lastMessageID'] : NULL;
		$conversationData = $iN->iN_GetChatMessages($userID, $cID, $lastMessageID, $scrollLimit);
		$cuD = $iN->iN_GetUserDetails($conversationUserID);
		$cuserAvatar = $iN->iN_UserAvatar($conversationUserID, $base_url);
		$conversationUserVerifyStatus = $cuD['user_verified_status'];
		$conversationUserName = $cuD['i_username'];
		$conversationUserFullName = $cuD['i_user_fullname'];
		$conversationUserGender = $cuD['user_gender'];
        $conversationUserOnlineOffline = $cuD['last_login_time'];
        $pCertificationStatus = $cuD['certification_status'];
        $pValidationStatus = $cuD['validation_status'];
        $whoSendMessage = $cuD['who_can_send_message'];
        $whoCanMessage = $cuD['who_can_message'];
        $myMessageStatus = $cuD['message_status'];
        $feesStatus = $cuD['fees_status'];
        $lastLoginDateTime = date("c", $conversationUserOnlineOffline);
        $p_crTime = date('Y-m-d H:i:s',$conversationUserOnlineOffline);
        $lastSeenTreeMinutesAgo = time() - 60; // Tree minutes ago
        $p_friend_status = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $conversationUserID);
		$checkUserinBlockedList = $iN->iN_CheckUserBlocked($userID, $conversationUserID);
		$checkVisitedProfileBlockedVisitor = $iN->iN_CheckUserBlockedVisitor($conversationUserID, $userID);
		if ($checkUserinBlockedList == '1') {
			$blockedType = $iN->iN_GetUserBlockedType($userID, $conversationUserID);
			$blockNote = preg_replace('/{.*?}/', $conversationUserFullName, $LANG['unblock']);
		} else if ($checkVisitedProfileBlockedVisitor == '1') {
			$blockedType = $iN->iN_GetUserBlockedType($conversationUserID, $userID);
			$blockNote = preg_replace('/{.*?}/', $conversationUserFullName, $LANG['unblock_me']);
		}
		if ($conversationUserGender == 'male') {
			$publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('12') . '</div>';
		} else if ($conversationUserGender == 'female') {
			$publisherGender = '<div class="i_plus_gf">' . $iN->iN_SelectedMenuIcon('13') . '</div>';
		} else if ($conversationUserGender == 'couple') {
			$publisherGender = '<div class="i_plus_g">' . $iN->iN_SelectedMenuIcon('58') . '</div>';
		}
		$userVerifiedStatus = '';
		if ($conversationUserVerifyStatus == '1') {
			$userVerifiedStatus = '<div class="i_plus_s">' . $iN->iN_SelectedMenuIcon('11') . '</div>';
		}
        $checkUserIsCreator = $iN->iN_CheckUserIsCreator($conversationUserID);
        $checkIamCreator = $iN->iN_CheckUserIsCreator($userID);
        // Decide call button behavior
        // Default is free call (joinVideoCall). If site is not free and the callee is a creator, require paid flow (crVidCall).
        if ($isVideoCallFree === 'yes') {
            $crVideo = 'joinVideoCall';
        } else {
            $crVideo = ($checkUserIsCreator == '1') ? 'crVidCall' : 'joinVideoCall';
        }

		?>
            <!--Conversation HEADER-->
            <div class="conversation_box_header flex_">
                <div class="cList flex_ tabing" id="<?php echo iN_HelpSecure($cID); ?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('102')); ?></div>
                <div class="conversation_avatar">
                   <img src="<?php echo iN_HelpSecure($cuserAvatar); ?>">
                </div>
                <div class="conversation_user_d flex_ tabing_non_justify">
                    <div class="conversation_user tabing_non_justify">
                        <div class="c_u_f_nm"><a class="truncated" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL) . $conversationUserName; ?>"><?php echo iN_HelpSecure($conversationUserFullName); ?></a></div>
                        <div class="c_u_time flex_"></div>
                    </div>
                        <!--AAAA-->
                        <?php  if($videoCallFeatureStatus == 'yes' && $whoCanCreateVideoCall == 'yes' && $conversationUserOnlineOffline > $lastSeenTreeMinutesAgo && $pCertificationStatus == '2' && $pValidationStatus == '2' && $feesStatus == '2'){?>
                            <div class="c_dotdot tabing flex_">
                                <div class="c_set callUser flex_ transition <?php echo $crVideo; ?>" ><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52')); ?></div>
                            </div>
                        <?php }else if($videoCallFeatureStatus == 'yes' && $whoCanCreateVideoCall == 'no'){?>
                            <div class="c_dotdot tabing flex_">
                                <div class="c_set callUser flex_ transition <?php echo $crVideo; ?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52')); ?></div>
                            </div>
                        <?php }else { ?>
                            <?php if($conversationUserOnlineOffline < $lastSeenTreeMinutesAgo){?>
                                <div class="c_dotdot tabing flex_">
                                    <div class="c_set callUser flex_ transition <?php echo iN_HelpSecure($isVideoCallFree) == 'yes' ? 'joinOffline' : 'joinOffline';?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52')); ?></div>
                                </div>
                            <?php }else if($conversationUserOnlineOffline > $lastSeenTreeMinutesAgo && $whoCanCreateVideoCall != 'yes'){ ?>
                                <div class="c_dotdot tabing flex_">
                                    <div class="c_set callUser flex_ transition <?php echo $crVideo;?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52')); ?></div>
                                </div>
                            <?php }?>
                        <?php }?>
                        <!--AAA-->
                    <div class="c_dotdot tabing flex_">
                        <div class="c_set mcSt flex_ transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('16')); ?></div>
                        <div class="cSetc">
                            <!--MENU ITEM-->
                            <div class="i_post_menu_item_out transition d_conversation" id="<?php echo iN_HelpSecure($conversationUserID); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?> <?php echo iN_HelpSecure($LANG['delete_message']); ?>
                            </div>
                            <!--/MENU ITEM-->
                            <!--MENU ITEM-->
                            <div class="i_post_menu_item_out transition ublknot truncated" data-u="<?php echo iN_HelpSecure($conversationUserID); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('64')); ?> <?php echo preg_replace('/{.*?}/', $conversationUserFullName, $LANG['restrict']); ?>
                            </div>
                            <!--/MENU ITEM-->
                        </div>
                    </div>
                </div>
            </div>
            <!--/Conversation HEADER-->
            <div class="messages_container flex_">
                <div class="msg_wrapper">
                  <div class="all_messages">
                    <div class="all_messages_container">
                        <?php
if ($conversationData) {
			foreach ($conversationData as $conData) {
				$cMessageID = $conData['con_id'];
				$cUserOne = $conData['user_one'];
				$cUserTwo = $conData['user_two'];
				$cMessage = isset($conData['message']) ? $conData['message'] : NULL;
				$cMessageTime = $conData['time'];
                if($userTimeZone){
                    date_default_timezone_set($userTimeZone);
                }
				$message_time = date("c", $cMessageTime);
                $gifMoney = isset($conData['gifMoney']) ? $conData['gifMoney'] : NULL;
                $privateStatus = isset($conData['private_status']) ? $conData['private_status'] : NULL;
				$privatePrice = isset($conData['private_price']) ? $conData['private_price'] : NULL;
				$cFile = isset($conData['file']) ? $conData['file'] : NULL;
				$cStickerUrl = isset($conData['sticker_url']) ? $conData['sticker_url'] : NULL;
				$cGifUrl = isset($conData['gifurl']) ? $conData['gifurl'] : NULL;
				$mSeenStatus = $conData['seen_status'];
				$msgDots = '';
				$imStyle = '';
				$seenStatus = '';
                $SGifMoneyText = '';
				if ($cUserOne == $userID) {
					$mClass = 'me';
					$msgOwnerID = $cUserOne;
					$lastM = '';
					if (!empty($cFile)) {
						$imStyle = 'mmi_i';
					}
					$timeStyle = 'msg_time_me';
					$seenStatus = '<span class="seenStatus flex_ notSeen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
					if ($mSeenStatus == '1') {
						$seenStatus = '<span class="seenStatus flex_ seen">' . $iN->iN_SelectedMenuIcon('94') . '</span>';
					}
                    if($gifMoney){
                        $SGifMoneyText = preg_replace( '/{.*?}/', $cMessage, $LANG['youSendGifMoney']);
                    }
				} else {
					$mClass = 'friend';
					$msgOwnerID = $cUserOne;
					$lastM = 'mm_' . $msgOwnerID;
					if (!empty($cFile)) {
						$imStyle = 'mmi_if';
					}
					$timeStyle = 'msg_time_fri';
                    if($gifMoney){
                        $msgOwnerFullName = $iN->iN_UserFullName($msgOwnerID);
                        $SGifMoneyText = $iN->iN_TextReaplacement($LANG['sendedGifMoney'],[$msgOwnerFullName , $cMessage]);
                    }
				}
                $msgOwnerFullName = $iN->iN_UserFullName($msgOwnerID);
				$msgOwnerAvatar = $iN->iN_UserAvatar($msgOwnerID, $base_url);
				$styleFor = '';
				if ($cStickerUrl) {
					$styleFor = 'msg_with_sticker';
					$cMessage = '<img class="mStick" src="' . $cStickerUrl . '">';
				}
				if ($cGifUrl) {
					$styleFor = 'msg_with_gif';
					$cMessage = '<img class="mGifM" src="' . $cGifUrl . '">';
				}
				$convertMessageTime = strtotime($message_time);
				$netMessageHour = date('H:i', $convertMessageTime);
				?>
                <?php if(!empty($privatePrice) && $privateStatus == 'closed' && $mClass != 'me'){?>
                    <div class="msg <?php echo iN_HelpSecure($lastM); ?>" id="msg_<?php echo iN_HelpSecure($cMessageID); ?>" data-id="<?php echo iN_HelpSecure($cMessageID); ?>">
                        <div class="msg_<?php echo iN_HelpSecure($mClass) . ' ' . $styleFor ; ?> secretMessageBgColor ch_msg_<?php echo iN_HelpSecure($cMessageID); ?>">
                            <div class="msg_o_avatar"><img src="<?php echo iN_HelpSecure($msgOwnerAvatar); ?>"></div>
                            <div class="msg_txt_sec flex_ justify-content-align-items-center">
                                <!--COUNT-->
                                <?php
                                if($cFile){
                                    $trimValue = rtrim($cFile,',');
                                    $explodeFiles = explode(',', $trimValue);
                                    $explodeFiles = array_unique($explodeFiles);
                                    $countExplodedFiles = count($explodeFiles);
                                    $array = array('mp4');
                                    if($countExplodedFiles){
                                        foreach($explodeFiles as $explodeVideoFile){
                                            $VideofileData = $iN->iN_GetUploadedMessageFileDetails($explodeVideoFile);
                                            if($VideofileData){
                                                $VideofileExtension = $VideofileData['uploaded_file_ext'];
                                            }
                                            $count[] = isset($VideofileExtension) ? $VideofileExtension : '1';
                                        }
                                        $totalVideos = isset(array_count_values($count)['mp4']) ? array_count_values($count)['mp4'] : '0';
                                        $totalPhotos = $countExplodedFiles - $totalVideos;
                                    }
                                ?>
                                    <?php if(empty($cFile) || $cFile == '' || !isset($cFile)){?>
                                    <div class="album-details"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('14'));?><?php echo iN_HelpSecure($LANG['purchasing_warning_for_empty_video_and_image_message']);?></div>
                                    <?php }else{?>
                                    <div class="album-details"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('14'));?><?php echo iN_HelpSecure($LANG['purchasing']) .' '; echo preg_replace( '/{.*?}/', $totalPhotos, $LANG['pr_photo']).' '; if(!empty($totalVideos)){echo ', '.preg_replace( '/{.*?}/', $totalVideos, $LANG['pr_video']);}?></div>
                                    <?php }?>
                                <?php }else{ ?>
                                    <div class="album-details"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('14'));?><?php echo iN_HelpSecure($LANG['purchasing']) .' '; echo iN_HelpSecure($LANG['purchasing_warning_for_empty_video_and_image_message']);?></div>
                                <?php } ?>
                                <div class="unLockMe unlockFor" id="<?php echo iN_HelpSecure($cMessageID); ?>"><?php echo preg_replace( '/{.*?}/', $privatePrice, $LANG['unlock_for']);?></div>
                                <!--COUNT-->
                            </div>
                        </div>
                        <div class="<?php echo iN_HelpSecure($timeStyle); ?>"><?php echo html_entity_decode($seenStatus) . $netMessageHour; ?></div>
                        <div class="unlockWarning unlc_<?php echo iN_HelpSecure($cMessageID); ?>"><?php echo iN_HelpSecure($LANG['dont_have_enough_credit_for_onlock']);?></div>
                    </div>
                <?php }else{?>
                    <!---->
                    <div class="msg <?php echo iN_HelpSecure($lastM); ?>" id="msg_<?php echo iN_HelpSecure($cMessageID); ?>" data-id="<?php echo iN_HelpSecure($cMessageID); ?>">
                               <div class="msg_<?php echo iN_HelpSecure($mClass) . ' ' . $styleFor . ' ' . $imStyle; ?>">
                                   <div class="msg_o_avatar"><img src="<?php echo iN_HelpSecure($msgOwnerAvatar); ?>"></div>
                                   <?php if ($cMessage) {?>
                                    <?php if($gifMoney){ ?>
                                        <div class="gfIcon flex_ justify-content-align-items-center"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('175'));?></div>
                                    <?php } ?>
                                    <div class="msg_txt"><?php if($gifMoney){ ?><?php echo iN_HelpSecure($SGifMoneyText);?><?php }else{?><?php echo $urlHighlight->highlightUrls($cMessage); ?><?php }?></div>
                                   <?php }?>
                                   <?php
                                    if ($cFile) {
                                        $trimValue = rtrim($cFile, ',');
                                        $explodeFiles = explode(',', $trimValue);
                                        $explodeFiles = array_unique($explodeFiles);
                                        $countExplodedFiles = count($explodeFiles);
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
                                        foreach ($explodeFiles as $explodeVideoFile) {
                                            $VideofileData = $iN->iN_GetUploadedMessageFileDetails($explodeVideoFile);
                                            if ($VideofileData) {
                                                $VideofileUploadID = $VideofileData['upload_id'];
                                                $VideofileExtension = $VideofileData['uploaded_file_ext'];
                                                $VideofilePath = $VideofileData['uploaded_file_path'];
                                                $VideofilePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $VideofilePath);
                                                if ($VideofileExtension == 'mp4') {
                                                    $VideoPathExtension = '.jpg';
                                                    if (function_exists('storage_public_url')) {
                                                        $VideofilePathUrl = storage_public_url($VideofilePath);
                                                        $VideofileTumbnailUrl = storage_public_url($VideofilePathWithoutExt . $VideoPathExtension);
                                                    } else {
                                                        $VideofilePathUrl = $base_url . $VideofilePath;
                                                        $VideofileTumbnailUrl = $base_url . $VideofilePathWithoutExt . $VideoPathExtension;
                                                    }
                                                    echo '
                                                                        <div class="nonePoint" id="video' . $VideofileUploadID . '">
                                                                            <video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none" onended="videoEnded()">
                                                                                <source src="' . $VideofilePathUrl . '" type="video/mp4">
                                                                                Your browser does not support HTML5 video.
                                                                            </video>
                                                                        </div>
                                                                        ';
                                                }
                                            }
                                        }
                                        echo '<div class="' . $container . '" id="lightgallery' . $cMessageID . '">';
                                        foreach ($explodeFiles as $dataFile) {
                                            $fileData = $iN->iN_GetUploadedMessageFileDetails($dataFile);
                                            if ($fileData) {
                                                $fileUploadID = $fileData['upload_id'];
                                                $fileExtension = $fileData['uploaded_file_ext'];
                                                $filePath = $fileData['uploaded_file_path'];
                                                $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                                                if (function_exists('storage_public_url')) {
                                                    $filePathUrl = storage_public_url($filePath);
                                                } else {
                                                    $filePathUrl = $base_url . $filePath;
                                                }
                                                $videoPlaybutton = '';
                                                if ($fileExtension == 'mp4') {
                                                    $videoPlaybutton = '<div class="playbutton">' . $iN->iN_SelectedMenuIcon('55') . '</div>';
                                                    $PathExtension = '.jpg';
                                                    if (function_exists('storage_public_url')) {
                                                        $filePathUrl = storage_public_url($filePathWithoutExt . $PathExtension);
                                                        $filePathUrlV = storage_public_url($filePath);
                                                    } else {
                                                        $filePathUrl = $base_url . $filePathWithoutExt . $PathExtension;
                                                        $filePathUrlV = $base_url . $filePath;
                                                    }
                                                    $fileisVideo = 'data-poster="' . $filePathUrlV . '" data-html="#video' . $fileUploadID . '"';
                                                } else {
                                                    $fileisVideo = 'data-src="' . $filePathUrl . '"';
                                                }
                                                ?>
                                                        <div class="i_post_image_swip_wrapper dynamic-bg" data-img="<?php echo $filePathUrl; ?>" <?php echo $fileisVideo; ?>>
                                                            <?php echo html_entity_decode($videoPlaybutton); ?>
                                                            <img class="i_p_image" src="<?php echo iN_HelpSecure($filePathUrl); ?>">
                                                        </div>
                                                    <?php }}
                                        echo '</div>';}?>
                                   <div class="gallery_trigger" data-gallery-id="lightgallery<?php echo iN_HelpSecure($cMessageID); ?>"></div>
                                   <?php if ($mClass == 'me') {?>
                                   <div class="me_btns_cont transition">
                                       <div class="me_btns_cont_icon smscd flex_ tabing" id="<?php echo iN_HelpSecure($cMessageID); ?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('16')); ?></div>
                                       <div class="me_msg_plus msg_set_plus_<?php echo iN_HelpSecure($cMessageID); ?>">
                                            <!--MENU ITEM-->
                                            <div class="i_post_menu_item_out delmes truncated transition" id="<?php echo iN_HelpSecure($cMessageID); ?>">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?> <?php echo iN_HelpSecure($LANG['delete_message']); ?>
                                            </div>
                                            <!--/MENU ITEM-->
                                       </div>
                                   </div>
                                   <?php }?>
                                </div>
                                <div class="<?php echo iN_HelpSecure($timeStyle); ?>">
                                    <?php if(!empty($privatePrice) && ( $privateStatus == 'opened' || $privateStatus == 'closed')){echo $currencys[$defaultCurrency].($privatePrice*$onePointEqual).html_entity_decode($iN->iN_SelectedMenuIcon('14')). '- ';}?>
                                    <?php echo html_entity_decode($seenStatus) . $netMessageHour; ?>
                                </div>
                    </div>
                    <!---->
                <?php }?>
                <?php }}?>
                    </div>
                  </div>
                </div>
                <!---->
                <?php if (!$blockedType) {?>
                <?php if($whoSendMessage == '1' && $p_friend_status == 'subscriber'){?>
                   <div class="message_send_form_wrapper"  id="<?php echo iN_HelpSecure($cID); ?>">
                    <div class="nanos transition"></div>
                    <div class="nanosSecret transition">
                        <!---->
                        <div class="i_write_secret_post_price boxD">
                            <div class="i_set_subscription_fee subU" id="<?php echo iN_HelpSecure($conversationUserID); ?>">
                                <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div>
                                <div class="i_subs_price"><input type="text" class="transition aval border-right-radius" id="sicVal" placeholder="<?php echo iN_HelpSecure($LANG['amount_in_points']);?>" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' ></div>
                            </div>
                            <div class="i_tip_not"><?php echo iN_HelpSecure($LANG['min_secret_post_amount']);?></div>
                        </div>
                        <!---->
                    </div>
                    <div class="tabing_non_justify flex_">
                        <div class="message_form_items flex_">
                            <!---->
                            <div class="message_send_text flex_ tabing">
                                <div class="message_text_textarea flex_">
                                <textarea class="mSize"></textarea>
                                <!---->
                                <div class="message_smiley getMEmojis">
                                    <div class="message_form_smiley_plus transition">
                                        <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('25')); ?></div>
                                    </div>
                                </div>
                                <!---->
                                <input type="hidden" id="uploadVal">
                                </div>
                            </div>
                            <!---->
                            <div class="message_form_plus transition sendmes">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="nanosSend flex_ transition box_not_padding_top">
                            <div class="message_form_plus transition chtBtns"  id="<?php echo iN_HelpSecure($cID); ?>">
                                <div class="fl_btns"></div>
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('92')); ?></div>
                            </div>
                            <div class="message_form_plus transition getmGifs ownTooltip" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['chs_gifimage_send']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('23')); ?></div>
                            </div>
                            <div class="message_form_plus transition getmStickers ownTooltip" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['chs_sticker_send']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('24')); ?></div>
                            </div>
                            <?php if($checkIamCreator){?>
                            <div class="message_form_plus transition sendPointMessage ownTooltip" data-u="<?php echo iN_HelpSecure($conversationUserID); ?>" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['send_gift_points']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('175')); ?></div>
                            </div>
                            <div class="message_form_plus transition sendSecretMessage ownTooltip" data-u="<?php echo iN_HelpSecure($conversationUserID); ?>" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['send_locked_message']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('14')); ?></div>
                            </div>
                            <?php }?>
                            <?php if(!$checkIamCreator){?>
                            <div class="message_form_plus transition sendPointMessage ownTooltip" data-u="<?php echo iN_HelpSecure($conversationUserID); ?>" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['send_gift_points']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('175')); ?></div>
                            </div>
                            <?php }?>
                    </div>
                </div>
                <?php }else if($whoSendMessage == '0' && $myMessageStatus == '1'){ ?>
                    <div class="message_send_form_wrapper"  id="<?php echo iN_HelpSecure($cID); ?>">
                    <div class="nanos transition"></div>
                    <div class="nanosSecret transition">
                        <!---->
                        <div class="i_write_secret_post_price boxD">
                            <div class="i_set_subscription_fee subU" id="<?php echo iN_HelpSecure($conversationUserID); ?>">
                                <div class="i_subs_currency"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40'));?></div>
                                <div class="i_subs_price"><input type="text" class="transition aval border-right-radius" id="sicVal" placeholder="<?php echo iN_HelpSecure($LANG['amount_in_points']);?>" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' ></div>
                            </div>
                            <div class="i_tip_not"><?php echo iN_HelpSecure($LANG['min_secret_post_amount']);?></div>
                        </div>
                        <!---->
                    </div>
                    <div class="tabing_non_justify flex_">
                        <div class="message_form_items flex_">
                            <!---->
                            <div class="message_send_text flex_ tabing">
                                <div class="message_text_textarea flex_">
                                <textarea class="mSize"></textarea>
                                <!---->
                                <div class="message_smiley getMEmojis">
                                    <div class="message_form_smiley_plus transition">
                                        <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('25')); ?></div>
                                    </div>
                                </div>
                                <!---->
                                <input type="hidden" id="uploadVal">
                                </div>
                            </div>
                            <!---->
                            <div class="message_form_plus transition sendmes">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="nanosSend flex_ transition box_not_padding_top">
                            <div class="message_form_plus transition chtBtns" id="<?php echo iN_HelpSecure($cID); ?>">
                                <div class="fl_btns"></div>
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('92')); ?></div>
                            </div>
                            <div class="message_form_plus transition getmGifs ownTooltip" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['chs_gifimage_send']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('23')); ?></div>
                            </div>
                            <div class="message_form_plus transition getmStickers ownTooltip" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['chs_sticker_send']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('24')); ?></div>
                            </div>
                            <?php if($checkIamCreator){?>
                            <div class="message_form_plus transition sendPointMessage ownTooltip" data-u="<?php echo iN_HelpSecure($conversationUserID); ?>" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['send_gift_points']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('175')); ?></div>
                            </div>
                            <div class="message_form_plus transition sendSecretMessage ownTooltip" data-u="<?php echo iN_HelpSecure($conversationUserID); ?>" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['send_locked_message']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('14')); ?></div>
                            </div>
                            <?php }?>
                            <?php if(!$checkIamCreator){?>
                            <div class="message_form_plus transition sendPointMessage ownTooltip" data-u="<?php echo iN_HelpSecure($conversationUserID); ?>" id="<?php echo iN_HelpSecure($cID); ?>" data-label="<?php echo iN_HelpSecure($LANG['send_gift_points']);?>"  data-type="topPoints">
                                <div class="message_pls flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('175')); ?></div>
                            </div>
                            <?php }?>
                    </div>
                </div>
                <?php }else{?>
                    <div class="message_send_form_wrapper blocked_not">
                        <div class="tabing_non_justify flex_">
                            <div class="message_form_items flex_">
                                <?php echo preg_replace('/{.*?}/', $conversationUserFullName, $LANG['taking_just_followers']);?>
                            </div>
                        </div>
                    </div>
                <?php }?>
                <!---->
                <?php } else {?>
                    <div class="message_send_form_wrapper blocked_not">
                        <div class="tabing_non_justify flex_">
                            <div class="message_form_items flex_">
                                <?php echo html_entity_decode($blockNote); ?>
                            </div>
                        </div>
                    </div>
                <!---->
                <?php }?>
            </div>
           <?php }
	if (!$checkcIDExist) {
		echo '<div class="chat_empty flex_ tabing"><div class="chat_empty_logo"></div></div>';
	}
	echo '</div>';} else {?>
           <div class="chat_empty flex_ tabing"><div class="chat_empty_logo"></div></div>
           <?php }?>
        </div>
        <!--chat_middle_container finished-->
        <!--Video Call Camera Container-->
        <div class="live_pp_camera_container nonePoint">
            <div class="friendsCam ">
                    <div id="remote-playerlist"></div>
            </div>
            <div class="myCam flex_ tabing">
                <p id="local-player-name" class="player-name nonePoint"></p>
                <div id="local-player" class="player"></div>
            </div>
            <div class="videoCallButtons">
                   <div class="call_footer_buttons flex_ tabing">
                         <div class="footer_call_btn_item flex_ tabing">
                            <div class="call_btn_icon flex_ tabing" id="mute-audio">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('173')); ?>
                            </div>
                         </div>
                         <div class="footer_call_btn_item flex_ tabing">
                            <div class="call_btn_end_icon flex_ tabing leave">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('172')); ?>
                            </div>
                         </div>
                         <div class="footer_call_btn_item flex_ tabing">
                            <div class="call_btn_icon flex_ tabing" id="mute-video">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('174')); ?>
                            </div>
                         </div>
                   </div>
            </div>
        </div>
        <!--Video Call Camera Container FINISHED-->
        <script src="https://download.agora.io/sdk/release/AgoraRTC_N.js"></script>
        <?php
        $videoCallData = $iN->iN_GetVideoCallDetailsBetweenTwoUsersIfExist($urlChatID, $userID);
        $channelName = isset($videoCallData['voice_call_name']) ? $videoCallData['voice_call_name'] : "stream_" . $userID . "_" . rand(1111111, 9999999);
        $videoCallExists = $videoCallData ? 'true' : 'false';
        ?>
        <script id="chat-config" type="application/json">
        <?php
            echo json_encode([
              'siteurl' => $base_url,
              'agoraAppID' => $agoraAppID,
              'userID' => $userID,
              'cID' => $cID,
              'conversationUserID' => $conversationUserID,
              'videoCall' => [
                  'exists' => $videoCallExists === 'true',
                  'channelName' => $channelName
              ]
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG);
        ?>
        </script>

        <script type="text/javascript" src="<?php echo iN_HelpSecure($base_url);?>themes/<?php echo iN_HelpSecure($currentTheme);?>/js/videoCallAgora.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
     </div>
    </div>
</body>
</html>
