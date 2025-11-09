<div class="i_modal_bg_in">
    <!--SHARE-->
   <div class="i_modal_in_in">
       <div class="i_modal_content">
            <!--Modal Header-->
            <div class="i_modal_g_header">
             <?php echo iN_HelpSecure($LANG['choose_your_story_type']); ?>
             <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
            </div>
            <!--/Modal Header-->
            <!--Sharing POST DETAILS-->
            <div class="i_block_user_nots_wrapper">
                <div class="i_blck_in">
                    <div class="choose_me tabing">
                    <?php if($whoCanShareStory == 'no'){ ?>
                        <?php if($feesStatus == '2'){?>
                        <?php if($iN->iN_StoryData($userID, '2') == 'yes'){?>
                        <!--ChM-->
                        <div class="chsm-item flex_ tabing">
                            <a class="flex_ tabing" href="<?php echo $base_url.'createStory?t=image';?>">
                                <div class="chsm chsm_bg_one flex_ tabing"> <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('53'));?> <?php echo iN_HelpSecure($LANG['create_photo_story']);?>
                                </div>
                            </a>
                        </div>
                        <!--/ChM-->
                        <?php }?>
                        <?php if($iN->iN_StoryData($userID, '3') == 'yes'){?>
                        <!--ChM-->
                        <div class="chsm-item flex_ tabing">
                            <a class="flex_ tabing" href="<?php echo $base_url.'createStory?t=text';?>">
                                <div class="chsm chsm_bg_two flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('168'));?><?php echo iN_HelpSecure($LANG['create_writing_story']);?>

                                </div>
                            </a>
                        </div>
                        <!--/ChM-->
                        <?php }?>
                        <?php }else{?>
                            <div class="i_become_creator_wrapper">
                                <div class="i_become_creator_title"><?php echo iN_HelpSecure($LANG['become_creator']);?></div>
                                <div class="i_become_title_mini"><?php echo iN_HelpSecure($LANG['registeration_free_fast']);?></div>
                                <div class="i_become_ceator_link">
                                    <?php if($logedIn == '1'){ ?>
                                        <a href="<?php echo iN_HelpSecure($base_url).'creator/becomeCreator';?>"><?php echo iN_HelpSecure($LANG['become_creator']);?></a>
                                    <?php }else{ ?>
                                        <a class="loginForm"><?php echo iN_HelpSecure($LANG['become_creator']);?></a>
                                    <?php }?>
                                </div>
                                <div class="i_become_creator_icon">
                                <div class="i_bicome"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('9'));?></div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php }else if($whoCanShareStory == 'yes'){?>
                        <?php if($iN->iN_StoryData($userID, '2') == 'yes'){?>
                        <!--ChM-->
                        <div class="chsm-item flex_ tabing">
                            <a class="flex_ tabing" href="<?php echo $base_url.'createStory?t=image';?>">
                                <div class="chsm chsm_bg_one tabing">
                                    <div class="chsm_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('53'));?></div>
                                    <div class="chsm_title flex_ tabing"><?php echo iN_HelpSecure($LANG['create_photo_story']);?></div>
                                </div>
                            </a>
                        </div>
                        <!--/ChM-->
                        <?php }?>
                        <?php if($iN->iN_StoryData($userID, '3') == 'yes'){?>
                        <!--ChM-->
                        <div class="chsm-item flex_ tabing">
                            <a class="flex_ tabing" href="<?php echo $base_url.'createStory?t=text';?>">
                                <div class="chsm chsm_bg_two tabing">
                                    <div class="chsm_icon flex_ tabing"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('168'));?></div>
                                    <div class="chsm_title flex_ tabing"><?php echo iN_HelpSecure($LANG['create_writing_story']);?></div>
                                </div>
                            </a>
                        </div>
                        <!--/ChM-->
                        <?php }?>
                        <?php }?>
                    </div>
                </div>
            </div>
            <!--/Sharing POST DETAILS-->
       </div>
   </div>
   <!--/SHARE-->
</div>