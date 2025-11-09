<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('154')); ?><?php echo iN_HelpSecure($LANG['my_stories']) . ''; ?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['my_stories_desc']); ?></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container i_tab_padding">
            <!---->
                <div class="exBody ">
                <?php
                $lastPostID = isset($_POST['last']) ? $_POST['last'] : '';
                $storiePostData = $iN->iN_AllUserStoriePosts($userID, $lastPostID, $showingNumberOfPost);
                if($storiePostData){
                   foreach($storiePostData as $stData){
                       $storieID = $stData['s_id'] ?? NULL;
                       $storieImage = $stData['uploaded_file_path'] ?? NULL;
                       $storieTumbnail = $stData['upload_tumbnail_file_path'] ?? NULL;
                       $storieFileExt = $stData['uploaded_file_ext'] ?? NULL;
                       $createdTime = $stData['created'] ?? NULL;
                       $storieStatus = $stData['status'] ?? NULL;
                       $videoBtn = $iN->iN_SelectedMenuIcon('27');
                       if($storieFileExt != 'mp4'){
                          $storieTumbnail = $storieImage;
                          $videoBtn = '';
                       }
                        if (function_exists('storage_public_url')) {
                            $storieImageURL = storage_public_url($storieTumbnail);
                        } else {
                            $storieImageURL = $base_url . $storieTumbnail;
                        }
                        $storySeenCount = $iN->iN_GetStorySeenCount($userID, $storieID);
                ?>
                    <!---->
                    <div class="_pbwg8 body_<?php echo iN_HelpSecure($storieID);?>">
                        <div class="_jjzlb">
                            <!---->
                            <div class="st_det transitions">
                                <div class="set_items">
                                    <div class="set_the_top_right_btns">
                                        <!--<div class="set_btn flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')); ?></div>-->
                                        <div class="set_btn flex_ tabing del_stor ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['delete']);?>" id="<?php echo iN_HelpSecure($storieID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?> </div>
                                    </div>
                                    <div class="set_ite_footer flex_ tabing">
                                        <div class="set_eye flex_ tabing stViewers" data-viewer="<?php echo iN_HelpSecure($storieID);?>">
                                             <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('10')).$storySeenCount; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!---->
                            <img src="<?php echo $storieImageURL;?>" class="exPex">
                        </div>
                    </div>
                <!---->
                <?php   }
                }else{
                    echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">'.html_entity_decode($iN->iN_SelectedMenuIcon('54')).'</div><div class="n_c_t">'.$LANG['nothing_to_show_about_stories'].'</div></div>';
                }
                ?>
                </div>
            <!---->
       </div>
  </div>
</div> 
