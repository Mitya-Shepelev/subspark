<div class="i_modal_bg_in">
    <!--SHARE-->
   <div class="i_modal_in_in">
       <div class="i_modal_content">
            <!--Modal Header-->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['re_share_title']);?>
                <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
            </div>
            <!--/Modal Header-->
            <!--Share More Text-->
            <div class="i_more_text_wrapper">
                <textarea class="more_textarea" id="" placeholder="<?php echo iN_HelpSecure($LANG['write_your_comment']);?>"></textarea>
            </div>
            <!--/Share More Text-->
            <!--Sharing POST DETAILS-->
            <div class="i_sharing_post_wrapper">
                <div class="i_sharing_post_wrapper_in">
                 <!--POST HEADER-->
                <div class="i_post_body_header">
                    <div class="i_post_user_avatar">
                        <img src="<?php echo iN_HelpSecure($userPostOwnerUserAvatar);?>"/>
                    </div>
                    <div class="i_post_i">
                        <div class="i_post_username"><a href="<?php echo iN_HelpSecure($base_url).$userPostOwnerUsername;?>"><?php echo iN_HelpSecure($userPostOwnerUserFullName);?> <?php echo html_entity_decode($publisherGender);?> <?php echo html_entity_decode($userVerifiedStatus);?> <?php echo html_entity_decode($wCanSee);?></a></div>
                        <div class="i_post_shared_time"><?php echo TimeAgo::ago($crTime , date('Y-m-d H:i:s'));?></div>
                    </div>
                </div>
                <!--/POST HEADER-->
                <?php
                if($userPostText){
                ?>
                <!--POST CONTAINER-->
                <div class="i_post_container">
                    <!--POST TEXT-->
                    <div class="i_post_text">
                    <?php
                    $pStatus = '1'; 
                    if($userPostWhoCanSee != '1'){
                        if($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostStatus != '2' && $userPostWhoCanSee == '3'){
                            $pStatus = '0';
                        }else if($userPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me'){
                            if($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                                $pStatus = '0';
                            }
                        } else if($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr'){
                            $pStatus = '0';
                        }
                    }
                    if($pStatus == '1'){
                        if(!empty($userPostText)){
                            if(isset($userPostHashTags) && !empty($userPostHashTags)){
                                echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($userPostText),$base_url));
                            }else{
                                echo $urlHighlight->highlightUrls($iN->iN_RemoveYoutubelink($userPostText));
                            }
                        }
                        $regexUrl = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
                        $totalUrl = preg_match_all($regexUrl, $userPostText, $matches);

                        $urls = $matches[0];
                        // go over all links
                        foreach($urls as $url)
                        {
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
                    }else{
                        if(!$userPostFile){
                            echo '<div class="onlySubs">'.html_entity_decode($onlySubs).'</div>';
                        }
                    } 
                    ?>
                    </div>
                    <!--/POST TEXT-->
                </div>
                <!--/POST CONTAINER-->
                <?php }
                ?>
                <!--POST IMAGES-->
                <div class="i_post_u_images">
                    <?php
                        if($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostWhoCanSee == '3') {
                            if($userPostFile){
                               echo html_entity_decode($onlySubs);
                            }
                        }else if($userPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me'){
                            if($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                                if($userPostFile){
                                    echo html_entity_decode($onlySubs);
                                 }
                            }
                        }else if($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr'){
                            if($userPostFile){
                                echo html_entity_decode($onlySubs);
                             }
                        }
                        $trimValue = rtrim($userPostFile,',');
                        $explodeFiles = explode(',', $trimValue);
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
                            $VideofileData = $iN->iN_GetUploadedFileDetails($explodeVideoFile);
                            if($VideofileData){
                                $VideofileUploadID = $VideofileData['upload_id'] ?? null;
                                $VideofileExtension = $VideofileData['uploaded_file_ext'] ?? null;
                                $VideofilePath = $VideofileData['uploaded_file_path'] ?? null;
                                $VideofilePathTumbnail = $VideofileData['upload_tumbnail_file_path'] ?? null;
                                if($userPostWhoCanSee != '1'){
                                    if($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostStatus != '2' && $userPostWhoCanSee == '3'){
                                        $VideofilePath = $VideofileData['uploaded_x_file_path'] ?? null;
                                    }else if($userPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me'){
                                        if($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                                            $VideofilePath = $VideofileData['uploaded_x_file_path'] ?? null;
                                        }
                                    }else if($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr'){
                                        $VideofilePath = $VideofileData['uploaded_x_file_path'] ?? null;
                                    }
                                }
                                $VideofilePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $VideofilePath);
                                if($VideofileExtension == 'mp4'){
                                    $VideoPathExtension = '.png';
                                    if(function_exists('storage_public_url')){
                                        $VideofilePathUrl = storage_public_url($VideofilePath);
                                        $VideofileTumbnailUrl = storage_public_url($VideofilePathTumbnail);
                                    }else{
                                        $VideofilePathUrl = $base_url . $VideofilePath;
                                        $VideofileTumbnailUrl = $base_url . $VideofilePathTumbnail;
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
                    echo '<div class="'.$container.'" id="lightgallery'.$userPostID.'">';
                        foreach($explodeFiles  as $dataFile){
                            $fileData = $iN->iN_GetUploadedFileDetails($dataFile);
                            if($fileData){
                            $fileUploadID = $fileData['upload_id'] ?? null;
                            $fileExtension = $fileData['uploaded_file_ext'] ?? null;
                            $filePath = $fileData['uploaded_file_path'] ?? null;
                            $filePathTumbnail = $fileData['upload_tumbnail_file_path'] ?? null;
                            if($userPostWhoCanSee != '1'){
                                if($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber' && $userPostStatus != '2' && $userPostWhoCanSee == '3'){
                                      $filePath = $fileData['uploaded_x_file_path'] ?? null;
                                }else if($userPostWhoCanSee == '4' && $getFriendStatusBetweenTwoUser != 'me'){
                                    if($checkUserPurchasedThisPost == '0' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                                      $filePath = $fileData['uploaded_x_file_path'] ?? null;
                                    }
                                } else if($userPostWhoCanSee == '2' && $getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'flwr'){
                                    $filePath = $fileData['uploaded_x_file_path'] ?? null;
                                }
                            }
                            $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                            if(function_exists('storage_public_url')){
                                $filePathUrl = storage_public_url($filePath);
                            }else{
                                $filePathUrl = $base_url . $filePath;
                            }
                            $videoPlaybutton ='';
                            if($fileExtension == 'mp4'){
                                $videoPlaybutton = '<div class="playbutton">'.$iN->iN_SelectedMenuIcon('55').'</div>';
                                $PathExtension = '.png';
                                if(function_exists('storage_public_url')){
                                    $filePathUrl = storage_public_url($filePathTumbnail);
                                }else{
                                    $filePathUrl = $base_url . $filePathTumbnail;
                                }
                                $fileisVideo = 'data-poster="'.$filePathUrl.'" data-html="#video'.$fileUploadID.'"';
                            }else{
                                $fileisVideo = 'data-src="'.$filePathUrl.'"';
                            }
                        ?>
                            <div class="i_post_image_swip_wrapper" data-bg="<?php echo iN_HelpSecure($filePathUrl); ?>" <?php echo iN_HelpSecure($fileisVideo);?>>
                                <?php echo html_entity_decode($videoPlaybutton);?>
                                <img class="i_p_image" src="<?php echo iN_HelpSecure($filePathUrl);?>">
                            </div>
                        <?php }
                        }
                        echo '</div>';
                        ?>
                </div>
                <!--POST IMAGES-->
            </div>
            </div>
            <!--/Sharing POST DETAILS-->
            <!--Modal Header-->
            <div class="i_modal_g_footer">
                <div class="shareBtn re-share transition" id="<?php echo iN_HelpSecure($userPostID);?>"><?php echo iN_HelpSecure($LANG['share']);?></div>
            </div>
            <!--/Modal Header-->
       </div>
   </div>
   <!--/SHARE-->
</div>
