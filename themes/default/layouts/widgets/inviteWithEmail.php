<div class="sp_wrp">
    <div class="suggested_products inviteemail">
        <div class="total_online_user">
            <div class="total_online_users_wrapper flex_ tabing_non_justify">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15'));?><span><?php echo $iN->getTotalCurrentOnlineUsers();?></span><?php echo $LANG['online_users'];?>
            </div>
        </div>
        <div class="i_right_box_header">
        <?php echo $LANG['invite_your_friends_title'];?>
        </div>
        <div class="i_sponsorad flex_ tabing">
           <div class="i_e_warnings">
               <div class="already_in_use"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('60'));?><?php echo iN_HelpSecure($LANG['this_email_already_in_use']);?></div>
           </div>
           <div class="flex_ tabing inviteEmailMargin"><input type="email" name="i-email" id="inv_email" class="inviteemail_input" placeholder="<?php echo iN_HelpSecure($LANG['email']);?>"/></div>
           <div class="send_invitation_btn transition inv_btn">
               <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26'));?><div class="pbtn"><?php echo iN_HelpSecure($LANG['send_invite']);?></div>
            </div>
            <div class="flex_ tabing invite_not"><?php echo iN_HelpSecure($LANG['send_invite_not']);?></div>
        </div>
    </div>
</div>
