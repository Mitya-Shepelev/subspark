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
    $cFile = isset($conData['file']) ? $conData['file'] : NULL;
    $cStickerUrl = isset($conData['sticker_url']) ? $conData['sticker_url'] : NULL;
    $cGifUrl = isset($conData['gifurl']) ? $conData['gifurl'] : NULL;
    $gifMoney = isset($conData['gifMoney']) ? $conData['gifMoney'] : NULL;
    $privateStatus = isset($conData['private_status']) ? $conData['private_status'] : NULL;
	$privatePrice = isset($conData['private_price']) ? $conData['private_price'] : NULL;
    $mSeenStatus = $conData['seen_status'];
    $msgDots = '';
    $imStyle = '';
    $seenStatus = '';
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
    } else {
        $mClass = 'friend';
        $msgOwnerID = $cUserOne;
        $lastM = 'mm_' . $msgOwnerID;
        if (!empty($cFile)) {
            $imStyle = 'mmi_if';
        }
        $timeStyle = 'msg_time_fri';
    }
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
                            <div class="msg_txt"><?php echo $urlHighlight->highlightUrls($cMessage); ?></div>
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
                        <div class="i_post_image_swip_wrapper" style="background-image:url('<?php echo $filePathUrl; ?>');" <?php echo $fileisVideo; ?>>
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
        <div class="<?php echo iN_HelpSecure($timeStyle); ?>"><?php echo html_entity_decode($seenStatus) . $netMessageHour; ?></div>
    </div>
    <!---->
<?php }?>
<?php }}?>
