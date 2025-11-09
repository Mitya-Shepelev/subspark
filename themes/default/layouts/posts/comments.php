<!--COMMENT-->
<div class="i_u_comment_body dlCm<?php echo iN_HelpSecure($commentID);?>" id="<?php echo iN_HelpSecure($commentID);?>" role="article" aria-labelledby="i_u_c_<?php echo iN_HelpSecure($commentID);?>">
    <div class="i_post_user_commented_avatar_out">
        <?php if(isset($commentUserFrame)){ ?>
            <div class="frame_out_container_comment" role="presentation">
                <div class="frame_container_comment">
                    <img src="<?php echo $base_url.$commentUserFrame;?>" alt=""/>
                </div>
            </div>
        <?php }?>
        <div class="i_post_user_commented_avatar">
            <img src="<?php echo iN_HelpSecure($commentedUserAvatar);?>" alt="<?php echo iN_HelpSecure($commentedUserFullName);?>" />
        </div>
    </div>
    <div class="i_user_comment_header">
        <div class="i_user_commented_user_infos">
            <a href="<?php echo iN_HelpSecure($base_url).$commentedUserName;?>">
                <?php echo iN_HelpSecure($commentedUserFullName);?>
                <?php echo html_entity_decode($cpublisherGender);?>
                <?php echo html_entity_decode($cuserVerifiedStatus);?>
                <?php echo html_entity_decode($cUType);?>
            </a>
        </div>
        <?php if(!empty($Usercomment)){?>
        <div class="i_user_comment_text" id="i_u_c_<?php echo iN_HelpSecure($commentID);?>" aria-label="Comment content">
        <?php
            echo $urlHighlight->highlightUrls($iN->sanitize_output($Usercomment, $base_url));
            $regexUrl = '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i';
            $totalUrl = preg_match_all($regexUrl, $Usercomment, $matches);
            $urls = $matches[0];
            foreach($urls as $url){
                $em = new Url_Expand($url);
                $site = $em->get_site();
                if ($site != "") {
                    $code = $em->get_iframe() ?: $em->get_embed();
                    if ($code == "") {
                        $codesrc = $em->get_thumb("medium");
                    }
                    echo $code;
                }
            }
        ?>
        </div>
        <?php } ?>
        <?php echo html_entity_decode($stickerComment); echo html_entity_decode($gifComment); ?>
        <div class="i_comment_like_time" aria-label="Comment actions">
            <div class="i_comment_reply rplyComment" id="<?php echo iN_HelpSecure($userPostID);?>" data-who="<?php echo iN_HelpSecure($commentedUserName);?>" role="button" tabindex="0">
                Reply
            </div>
            <div class="i_comment_like_btn">
                <div class="i_comment_item_btn transition c_in_l_<?php echo iN_HelpSecure($commentID);?> <?php echo html_entity_decode($commentLikeBtnClass);?>"
                     id="com_<?php echo iN_HelpSecure($commentID);?>"
                     data-id="<?php echo iN_HelpSecure($commentID);?>"
                     data-p="<?php echo iN_HelpSecure($userPostID);?>"
                     role="button"
                     aria-pressed="<?php echo $commentLikeBtnClass ? 'true' : 'false'; ?>"
                     tabindex="0">
                     <?php echo html_entity_decode($commentLikeIcon);?>
                </div>
                <div class="i_comment_like_sum" id="t_c_<?php echo iN_HelpSecure($commentID);?>">
                    <?php echo iN_HelpSecure($iN->iN_TotalCommentLiked($commentID));?>
                </div>
            </div>
            <div class="i_comment_time" aria-label="Comment time">
                <?php echo TimeAgo::ago($corTime , date('Y-m-d H:i:s'));?>
            </div>

            <!-- Comment Menu -->
            <div class="i_comment_call_popup openComMenu" id="<?php echo iN_HelpSecure($commentID);?>" role="button" tabindex="0" aria-label="Open comment menu">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('16'));?>
                <div class="i_comment_menu_container comMBox comMBox<?php echo iN_HelpSecure($commentID);?>">
                    <div class="i_comment_menu_wrapper">
                        <div class="i_post_menu_item_out delCm transition"
                             id="<?php echo iN_HelpSecure($commentID);?>"
                             data-id="<?php echo iN_HelpSecure($userPostID);?>"
                             role="button"
                             tabindex="0">
                             <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28'));?> <?php echo iN_HelpSecure($LANG['delete_comment']);?>
                        </div>
                        <?php if($logedIn != 0 && $commentedUserID == $userID){ ?>
                        <div class="i_post_menu_item_out cced transition"
                             id="<?php echo iN_HelpSecure($commentID);?>"
                             data-id="<?php echo iN_HelpSecure($userPostID);?>"
                             role="button"
                             tabindex="0">
                             <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27'));?> <?php echo iN_HelpSecure($LANG['edit_comment']);?>
                        </div>
                        <?php }?>
                        <?php if($logedIn != 0 && $commentedUserID != $userID){
                            $checkCommentReportedBeforeByUserID = $iN->iN_CheckCommentReportedBefore($userID, $commentID);
                            $reportText = $checkCommentReportedBeforeByUserID == '1' ? $LANG['unreport'] : $LANG['report_comment'];
                        ?>
                        <div class="i_post_menu_item_out ccp transition ccp<?php echo iN_HelpSecure($commentID);?>"
                             id="<?php echo iN_HelpSecure($commentID);?>"
                             data-id="<?php echo iN_HelpSecure($userPostID);?>"
                             role="button"
                             tabindex="0">
                             <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('32'));?> <?php echo iN_HelpSecure($reportText);?>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!--/Comment Menu-->
        </div>
    </div>
</div>
<!--/COMMENT-->