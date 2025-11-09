<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="startChatModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="startChatModalTitle">
                <?php echo iN_HelpSecure(preg_replace('/{.*?}/', $f_userfullname, $LANG['shart_a_chat_with'])); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /Modal Header -->

            <!-- Modal Body -->
            <div class="i_more_text_wrapper">
                <textarea class="more_textarea"
                          id="sendNewM"
                          dir="auto"
                          rows="5"
                          aria-label="<?php echo iN_HelpSecure($LANG['what_you_want_to_write']); ?>"
                          placeholder="<?php echo iN_HelpSecure($LANG['what_you_want_to_write']); ?>">
                </textarea>
            </div>
            <!-- /Modal Body -->

            <!-- Modal Footer -->
            <div class="i_block_box_footer_container">
                <div class="alertBtnRightWithIcon sndNewMessage transition"
                     id="<?php echo iN_HelpSecure($iuID); ?>"
                     data-u="<?php echo iN_HelpSecure($iuID); ?>"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['send_message']); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('26')); ?>
                    <?php echo iN_HelpSecure($LANG['send_message']); ?>
                </div>
                <div class="alertBtnLeft no-del transition"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
            <!-- /Modal Footer -->
        </div>
    </div>
    <!--/SHARE-->
</div>