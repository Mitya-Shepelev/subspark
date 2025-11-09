<div class="i_comment_form">
<!--COMMENT FORM AVATAR-->
<div class="i_post_user_comment_avatar">
    <img src="<?php echo iN_HelpSecure($userAvatar);?>"/>
</div>
<div class="i_comment_form_textarea" data-id="<?php echo iN_HelpSecure($userPostID);?>">
    <div class="i_comment_t_body"><textarea name="post_comment" class="comment commenta nwComment" data-id="<?php echo iN_HelpSecure($userPostID);?>" id="comment<?php echo iN_HelpSecure($userPostID);?>" placeholder="<?php echo iN_HelpSecure($LANG['write_your_comment']);?>"></textarea><input type="hidden" id="stic_<?php echo iN_HelpSecure($userPostID);?>"><input type="hidden" id="cgif_<?php echo iN_HelpSecure($userPostID);?>"></div>
    <!--FAST COMMENT BUTTONS-->
    <div class="i_comment_footer i_comment_footer<?php echo iN_HelpSecure($userPostID);?>">
        <div class="i_comment_fast_answers getStickers<?php echo iN_HelpSecure($userPostID);?> ">
            <div class="i_fa_body getGifs" id="<?php echo iN_HelpSecure($userPostID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('23'));?></div>
            <div class="i_fa_body getStickers" id="<?php echo iN_HelpSecure($userPostID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('24'));?></div>
            <div class="i_fa_body getEmojisC<?php echo iN_HelpSecure($userPostID);?> getEmojisC" data-type="emojiBoxC" data-id="<?php echo iN_HelpSecure($userPostID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('25'));?></div>
            <div class="i_fa_body sndcom" id="<?php echo iN_HelpSecure($userPostID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26'));?></div>
        </div>
    </div>
    <!--/FAST COMMENT BUTTONS-->
</div>
<!--/COMMENT FORM AVATAR-->
</div>
<div class="emptyStickerArea emptyStickerArea<?php echo iN_HelpSecure($userPostID);?>"></div>
<div class="emptyGifArea nonePoint emptyGifArea<?php echo iN_HelpSecure($userPostID);?>">
<div class="in_gif_wrapper"><img class="srcGif<?php echo iN_HelpSecure($userPostID);?>" src=""></div>
<div class="removeGif" id="<?php echo iN_HelpSecure($userPostID);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
</div>