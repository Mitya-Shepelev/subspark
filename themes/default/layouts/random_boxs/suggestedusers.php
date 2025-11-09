<?php
if($logedIn != 0){
    $suggestedCreators = $iN->iN_SuggestionCreatorsList($userID, $showingNumberOfSuggestedUser);
}else{
    $suggestedCreators = $iN->iN_SuggestionCreatorsListOut($showingNumberOfSuggestedUser);
}
if($suggestedCreators){
?>
<div class="i_postFormContainer_swiper">
    <div class="i_right_box_header">
    <?php echo iN_HelpSecure($LANG['suggested_creators']);?>
    </div>
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
        <?php
            foreach($suggestedCreators as $sgCreatorData){
                $sgcreatorUserName = $sgCreatorData['i_username'] ?? null;
                $sgCreatorUserfullName = $sgCreatorData['i_user_fullname'] ?? null;
                $sgcreatorUserID = $sgCreatorData['iuid'] ?? null;
                $sgCreatorUserAvatar = $iN->iN_UserAvatar($sgcreatorUserID, $base_url);
                $sgCreatorUserCover = $iN->iN_UserCover($sgcreatorUserID, $base_url);
                $sgtotalPost = $iN->iN_TotalPosts($sgcreatorUserID);
                $sgtotalImagePost = $iN->iN_TotalImagePosts($sgcreatorUserID);
                $sgtotalVideoPosts = $iN->iN_TotalVideoPosts($sgcreatorUserID);
            ?>
            <!--ITEM-->
            <div class="swiper-slide">
            <div class="i_sub_u_cov" data-bg="<?php echo iN_HelpSecure($sgCreatorUserCover); ?>"></div>
                <div class="i_sub_u_det">
                    <div class="i_sub_u_det_container">
                            <!---->
                            <div class="i_sub_u_ava">
                                <div class="i_post_user_avatar">
                                    <img src="<?php echo $sgCreatorUserAvatar;?>" alt="<?php echo $sgCreatorUserfullName;?>">
                                </div>
                            </div>
                            <!---->
                            <div class="i_sub_u_d">
                                <div class="i_sub_u_name"><a href="<?php echo $base_url.$sgcreatorUserName;?>" target="_blank" title="<?php echo $sgCreatorUserfullName;?>"><?php echo $sgCreatorUserfullName;?></a></div>
                                <div class="i_sub_u_men"><a href="<?php echo $base_url.$sgcreatorUserName;?>" target="_blank" title="<?php echo $sgCreatorUserfullName;?>">@<?php echo $sgcreatorUserName;?></a></div>
                                <!---->
                                <div class="i_p_items_box_">
                                    <div class="i_btn_item_box transition">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('67')); ?> <?php echo iN_HelpSecure($sgtotalPost); ?>
                                    </div>
                                    <div class="i_btn_item_box transition">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('68')); ?> <?php echo iN_HelpSecure($sgtotalImagePost); ?>
                                    </div>
                                    <div class="i_btn_item_box transition">
                                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('52')); ?> <?php echo iN_HelpSecure($sgtotalVideoPosts); ?>
                                    </div>
                                </div>
                                <!---->
                            </div>
                    </div>
                </div>
            </div>
            <!--/ITEM-->
            <?php }?>
        </div>
        </div>
        <div class="horizontal_arrow"><?php echo $iN->iN_SelectedMenuIcon('141');?></div>
    </div>
<?php }?>