<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="blockUserModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="blockUserModalTitle">
                <?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['are_you_sure_want_to_block']); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /Modal Header -->

            <!-- Block Options -->
            <div class="i_block_user_nots_wrapper">
                <div class="i_blck_in">

                    <!-- Restrict Option -->
                    <div class="i_redtrict_u" data-s="1" role="button" tabindex="0" aria-pressed="true" aria-label="<?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['restrict']); ?>">
                        <div class="i_block_choose">
                            <div class="block_a_status blockboxActive" id="bl_s_1"></div>
                        </div>
                        <?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['restrict']); ?>
                    </div>

                    <div class="i_block_i_item">
                        <div class="i_block_not_title">
                            <?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['no_longer_be_able_to']); ?>
                        </div>
                        <ul class="i_block_not_list">
                            <li><?php echo iN_HelpSecure($LANG['tag_you']); ?></li>
                            <li><?php echo iN_HelpSecure($LANG['message_you']); ?></li>
                            <li><?php echo iN_HelpSecure($LANG['comment_on_your_post']); ?></li>
                        </ul>

                        <div class="i_block_not_title">
                            <?php echo iN_HelpSecure($LANG['also_you_no_longer_be_able_to']); ?>
                        </div>
                        <ul class="i_block_not_list">
                            <li><?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['tag_you_you']); ?></li>
                            <li><?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['message_you_you']); ?></li>
                            <li><?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['comment_on_his_post']); ?></li>
                        </ul>
                    </div>

                    <!-- Full Block Option -->
                    <div class="i_redtrict_u" data-s="2" role="button" tabindex="0" aria-pressed="false" aria-label="<?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['block_completely']); ?>">
                        <div class="i_block_choose">
                            <div class="block_a_status blockboxPassive" id="bl_s_2"></div>
                        </div>
                        <?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['block_completely']); ?>
                    </div>

                    <div class="i_block_i_item">
                        <div class="i_block_not_title">
                            <?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['no_longer_be_able_to']); ?>
                        </div>
                        <ul class="i_block_not_list">
                            <li><?php echo iN_HelpSecure($LANG['see_your_post_on_your_timeline']); ?></li>
                            <li><?php echo iN_HelpSecure($LANG['can_not_follow_you']); ?></li>
                            <li><?php echo iN_HelpSecure($LANG['can_not_subscribe_you']); ?></li>
                            <li><?php echo iN_HelpSecure($LANG['tag_you']); ?></li>
                            <li><?php echo iN_HelpSecure($LANG['message_you']); ?></li>
                            <li><?php echo iN_HelpSecure($LANG['comment_on_your_post']); ?></li>
                        </ul>

                        <div class="i_block_not_title_plus">
                            <?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['block_unfollow_not']); ?>
                        </div>
                        <div class="i_block_not_title_plus">
                            <?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['block_unsubscribe_not']); ?>
                        </div>
                        <div class="i_block_not_title_plus">
                            <?php echo preg_replace('/{.*?}/', $f_userfullname, $LANG['note_blocker']); ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Block Options -->

            <!-- Modal Footer -->
            <div class="i_block_box_footer_container">
                <div class="alertBtnRightWithIcon ublk transition"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['accept']); ?>"
                     id="<?php echo iN_HelpSecure($iuID); ?>"
                     data-u="<?php echo iN_HelpSecure($iuID); ?>"
                     data-bt="1">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('64')) . ' ' . iN_HelpSecure($LANG['accept']); ?>
                </div>
                <div class="alertBtnLeft no-del transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
            <!-- /Modal Footer -->
        </div>
    </div>
</div>