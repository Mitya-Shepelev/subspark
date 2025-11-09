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