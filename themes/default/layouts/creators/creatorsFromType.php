<div class="creators_container">
  <div class="creator_pate_title">
      <?php
        if(isset($PROFILE_CATEGORIES[$iN->iN_Secure($pageCreator)])){
            echo iN_HelpSecure($PROFILE_CATEGORIES[$iN->iN_Secure($pageCreator)]);
        }else if(isset($PROFILE_SUBCATEGORIES[$iN->iN_Secure($pageCreator)])){
            $catOwn = $iN->iN_GetCategoryFromSubCategory($pageCreator);
            echo iN_HelpSecure($PROFILE_CATEGORIES[$iN->iN_Secure($catOwn)]).' -> '.iN_HelpSecure($PROFILE_SUBCATEGORIES[$iN->iN_Secure($pageCreator)]);
        }
      ?>
  </div>

    <div class="creators_list_container" id="moreType" data-type="creators" data-r="<?php echo html_entity_decode($iN->iN_Secure($pageCreator));?>">
        <?php
            $lastPostID = isset($_POST['last']) ? $_POST['last'] : '';
            $creatorTypeUrl = $iN->iN_GetCreatorFromUrl($iN->iN_Secure($pageCreator),$lastPostID, $scrollLimit);
            if($creatorTypeUrl){
                foreach($creatorTypeUrl as $td){
                    $popularuserID = $td['iuid'];
                    $uD = $iN->iN_GetUserDetails($popularuserID);
                    $popularUserAvatar = $iN->iN_UserAvatar($popularuserID, $base_url);
                    $creatorCover = $iN->iN_UserCover($popularuserID, $base_url);
                    $popularUserName = $td['i_username'];
                    $popularUserFullName = $td['i_user_fullname'];
                    $userProfileFrame = isset($td['user_frame']) ? $td['user_frame'] : NULL;
                    if($fullnameorusername == 'no'){
                        $popularUserFullName = $popularUserName;
                    }
                    $uPCategory = isset($uD['profile_category']) ? $uD['profile_category'] : NULL;
                    $totalPost = $iN->iN_TotalPosts($popularuserID);
                    $totalImagePost = $iN->iN_TotalImagePosts($popularuserID);
                    $totalVideoPosts = $iN->iN_TotalVideoPosts($popularuserID);
                    if(isset($PROFILE_CATEGORIES[$uPCategory])){
                        $pCt = isset($PROFILE_CATEGORIES[$uPCategory]) ? $PROFILE_CATEGORIES[$uPCategory] : NULL;
                    }else if(isset($PROFILE_SUBCATEGORIES[$uPCategory])){
                        $pCt = isset($PROFILE_SUBCATEGORIES[$uPCategory]) ? $PROFILE_SUBCATEGORIES[$uPCategory] : NULL;
                    }
        ?>
        <!---->
        <div class="creator_list_box_wrp mor body_<?php echo iN_HelpSecure($popularuserID);?>" data-last="<?php echo iN_HelpSecure($popularuserID);?>">
            <div class="creator_l_box transition flex_">
                <div class="creator_l_cover" style="background-image:url(<?php echo iN_HelpSecure($creatorCover);?>);"></div>
                <!---->
                <div class="creator_l_avatar_name">
                <div class="creator_avatar_container">
                        <?php if($userProfileFrame){ ?>
                            <div class="frame_out_container_creator"><div class="frame_container_creator"><img src="<?php echo $base_url.$userProfileFrame;?>"></div></div>
                        <?php }?>
                        <a href="<?php echo iN_HelpSecure($base_url).$popularUserName;?>"><div class="creator_avatar"><img src="<?php echo iN_HelpSecure($popularUserAvatar);?>"></div></a>
                    </div>
                    <div class="creator_nm transition truncated"><a href="<?php echo iN_HelpSecure($base_url).$popularUserName;?>"><?php echo iN_HelpSecure($iN->iN_Secure($popularUserFullName));?></a></div>
                    <div class="i_p_cards">
                       <div class="i_creator_category"><a href="<?php echo $base_url.'creators?creator='.$uPCategory;?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('65')).$pCt;?></a></div>
                    </div>
                    <!---->
                    <div class="i_p_items_box_">
                        <div class="i_btn_item_box transition">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('67'));?> <?php echo iN_HelpSecure($totalPost);?>
                        </div>
                        <div class="i_btn_item_box transition">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('68'));?> <?php echo iN_HelpSecure($totalImagePost);?>
                        </div>
                        <div class="i_btn_item_box transition">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52'));?> <?php echo iN_HelpSecure($totalVideoPosts);?>
                        </div>
                    </div>
                    <!---->
                    <!---->
                    <div class="creator_last_two_post flex_ tabing">
                        <?php
                           $getLatestFivePost = $iN->iN_ExploreUserLatestFivePost($popularuserID);
                           if($getLatestFivePost){
                               foreach($getLatestFivePost as $suggestedData){
                                $userPostID = $suggestedData['post_id'];
                                $userPostFile = $suggestedData['post_file'];
                                $slugData = isset($suggestedData['url_slug']) ? $suggestedData['url_slug'] : NULL;
                                $slugUrl = $base_url.'post/'.$slugData.'_'.$userPostID;
                                $userPostWhoCanSee = $suggestedData['who_can_see'];
                                $trimValue = rtrim($userPostFile,',');
                                $explodeFiles = explode(',', $trimValue);
                                $explodeFiles = array_unique($explodeFiles);
                                $countExplodedFiles = count($explodeFiles);
                                $nums = preg_split('/\s*,\s*/', $trimValue);
                                $lastFileID = end($nums);
                                if($logedIn == '1'){
                                    $getFriendStatusBetweenTwoUser = $iN->iN_GetRelationsipBetweenTwoUsers($userID, $popularuserID);
                                } 
                                $fileData = $iN->iN_GetUploadedFileDetails($lastFileID);
                                if($fileData){
                                    $fileUploadID = $fileData['upload_id'];
                                    $fileExtension = $fileData['uploaded_file_ext'];
                                    $filePath = $fileData['uploaded_file_path'];
                                    $filePathTumbnail = $fileData['upload_tumbnail_file_path'];
                                    if($filePathTumbnail){
                                        $imageTumbnail = $filePathTumbnail;
                                     }else{
                                        $imageTumbnail = $filePath;
                                     }
                                    if($userPostWhoCanSee != '1' && $logedIn == '1'){
                                        if($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                                            $filePath = $fileData['uploaded_x_file_path'];
                                        }
                                    }else{
                                        $filePath = $fileData['uploaded_x_file_path'];
                                    }
                                    $filePathWithoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $filePath);
                                    if(function_exists('storage_public_url')){
                                        $filePathUrl = storage_public_url($filePathTumbnail ? $imageTumbnail : $filePath);
                                    }else{
                                        $filePathUrl = $base_url . ($filePathTumbnail ? $imageTumbnail : $filePath);
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
                                        if(function_exists('storage_public_url')){
                                            $filePathUrl = storage_public_url($filePath);
                                        }else{
                                            $filePathUrl = $base_url.$filePath;
                                        }
                                        $fileisVideo = 'data-src="'.$filePathUrl.'"';
                                    }
                                    $onlySubs = '';
                                    if($userPostWhoCanSee == '1'){
                                        $onlySubs = '';
                                    }else if($userPostWhoCanSee == '2'){
                                        $wCanSee = '<div class="i_plus_subs" id="ipublic_'.$userPostID.'">'.$iN->iN_SelectedMenuIcon('15').'</div>';
                                        $onlySubs = '<div class="onlySubsSuggestion"><div class="onlySubsSuggestionWrapper"><div class="onlySubsSuggestion_icon">'.$iN->iN_SelectedMenuIcon('56').'</div></div></div>';
                                    }else if($userPostWhoCanSee == '3'){
                                        $onlySubs = '<div class="onlySubsSuggestion"><div class="onlySubsSuggestionWrapper"><div class="onlySubsSuggestion_icon">'.$iN->iN_SelectedMenuIcon('56').'</div></div></div>';
                                    }else if($userPostWhoCanSee == '4'){
                                        $onlySubs = '<div class="onlySubsSuggestion"><div class="onlySubsSuggestionWrapper"><div class="onlySubsSuggestion_icon">'.$iN->iN_SelectedMenuIcon('40').'</div></div></div>';
                                    }
                                } 
                        ?>
                            <div class="creator_last_post_item">
                                <div class="creator_last_post_item-box"  style="background-image: url('<?php echo iN_HelpSecure($filePathUrl);?>');">
                                <a href="<?php echo iN_HelpSecure($slugUrl);?>">
                                    <?php
                                      if($logedIn == '1'){
                                        if($getFriendStatusBetweenTwoUser != 'me' && $getFriendStatusBetweenTwoUser != 'subscriber'){
                                            echo html_entity_decode($onlySubs);
                                        }
                                      }else{
                                        echo html_entity_decode($onlySubs);
                                      }
                                    ?>
                                    <img class="creator_last_post_item-img" src="<?php echo iN_HelpSecure($filePathUrl);?>">
                                </a>
                                </div>
                            </div>
                        <?php  
                               }
                            }else{
                                echo '<div class="no_content tabing flex_">'.$LANG['no_posts_yet'].'</div>';
                            } ?>
                    </div>
                    <!---->
                </div>
                <!---->
            </div>
        </div>
        <!---->
    <?php  } }else{
        echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.$iN->iN_SelectedMenuIcon('54').'</div><div class="n_c_t">'.$LANG['not_creator_in_this_caregory'].'</div></div>';
    } ?>
    </div>
</div>
