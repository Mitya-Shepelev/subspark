<!---->
<div class="msg_<?php echo iN_HelpSecure($mClass).' '.$styleFor .' '. $imStyle;?>">
        <div class="msg_o_avatar"><img src="<?php echo iN_HelpSecure($msgOwnerAvatar);?>"></div>
        <?php if($cMessage){?>
            <?php if($gifMoney){ ?>
                <div class="gfIcon flex_ justify-content-align-items-center"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('175'));?></div>
            <?php } ?>
            <div class="msg_txt"><?php echo $urlHighlight->highlightUrls($iN->iN_RemoveYoutubelink($cMessage));?></div>
        <?php } ?>
        <?php
            if($cFile){
                $trimValue = rtrim($cFile,',');
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
                } else if($countExplodedFiles >= 5) {
                    $container = 'i_image_five';
                }
                foreach($explodeFiles as $explodeVideoFile){
                $VideofileData = $iN->iN_GetUploadedMessageFileDetails($explodeVideoFile);
                if($VideofileData){
                    $VideofileUploadID = $VideofileData['upload_id'];
                    $VideofileExtension = $VideofileData['uploaded_file_ext'];
                    $VideofilePath = $VideofileData['uploaded_file_path'];
                    $VideofilePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $VideofilePath);
                    if($VideofileExtension == 'mp4'){
                        $VideoPathExtension = '.jpg';
                        if (function_exists('storage_public_url')) {
                            $VideofilePathUrl = storage_public_url($VideofilePath);
                            $VideofileTumbnailUrl = storage_public_url($VideofilePathWithoutExt.$VideoPathExtension);
                        } else {
                            $VideofilePathUrl = $base_url . $VideofilePath;
                            $VideofileTumbnailUrl = $base_url . $VideofilePathWithoutExt.$VideoPathExtension;
                        }
                        echo '
                        <div class="nonePoint" id="video'.$VideofileUploadID.'">
                            <video class="lg-video-object lg-html5 video-js vjs-default-skin" controls preload="none" onended="videoEnded()">
                                <source src="'.$VideofilePathUrl.'" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                        ';
                    }
                }
                }
                echo '<div class="'.$container.'" id="lightgallery'.$cMessageID.'">';
                foreach($explodeFiles  as $dataFile){
                $fileData = $iN->iN_GetUploadedMessageFileDetails($dataFile);
                if($fileData){
                $fileUploadID = $fileData['upload_id'];
                $fileExtension = $fileData['uploaded_file_ext'];
                $filePath = $fileData['uploaded_file_path'];
                $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                if(function_exists('storage_public_url')){
                    $filePathUrl = storage_public_url($filePath);
                }else{
                    $filePathUrl = $base_url . $filePath;
                }
                $videoPlaybutton ='';
                if($fileExtension == 'mp4'){
                    $videoPlaybutton = '<div class="playbutton">'.$iN->iN_SelectedMenuIcon('55').'</div>';
                    $PathExtension = '.jpg';
                    if (function_exists('storage_public_url')) {
                        $filePathUrl = storage_public_url($filePathWithoutExt . $PathExtension);
                        $filePathUrlV = storage_public_url($filePath);
                    } else {
                        $filePathUrl = $base_url . $filePathWithoutExt . $PathExtension;
                        $filePathUrlV = $base_url . $filePath;
                    }
                    $fileisVideo = 'data-poster="' . $filePathUrlV . '" data-html="#video' . $fileUploadID . '"';
                }else{
                    $fileisVideo = 'data-src="'.$filePathUrl.'"';
                }
        ?>
        <div class="i_post_image_swip_wrapper" style="background-image:url('<?php echo iN_HelpSecure($filePathUrl);?>');" <?php echo html_entity_decode($fileisVideo);?>>
            <?php echo html_entity_decode($videoPlaybutton);?>
            <img class="i_p_image" src="<?php echo iN_HelpSecure($filePathUrl);?>">
        </div>
        <?php }} echo '</div>'; } ?>
        <div class="gallery_trigger" data-gallery-id="lightgallery<?php echo iN_HelpSecure($cMessageID); ?>"></div>
        <?php if($mClass == 'me'){?>
        <div class="me_btns_cont transition">
            <div class="me_btns_cont_icon smscd flex_ tabing" id="<?php echo iN_HelpSecure($cMessageID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('16'));?></div>
            <div class="me_msg_plus msg_set_plus_<?php echo iN_HelpSecure($cMessageID);?>">
                <!--MENU ITEM-->
                <div class="i_post_menu_item_out delmes truncated transition" id="<?php echo iN_HelpSecure($cMessageID); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28'));?> <?php echo iN_HelpSecure($LANG['delete_message']);?>
                </div>
                <!--/MENU ITEM-->
            </div>
        </div>
        <?php }?>
    </div>
    <div class="<?php echo iN_HelpSecure($timeStyle);?>"><?php echo html_entity_decode($seenStatus).$netMessageHour;?></div>
</div>
<!---->
