<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="deleteMessageModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="deleteMessageModalTitle">
                <?php echo iN_HelpSecure($LANG['delete_message']); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /Modal Header -->

            <!-- Modal Body -->
            <div class="i_delete_post_description">
                <?php echo iN_HelpSecure($LANG['are_you_sure_to_delete_message']); ?>
            </div>

            <!-- Modal Footer -->
            <div class="i_modal_g_footer">
                <div class="alertBtnRight dlMesv transition"
                     id="<?php echo iN_HelpSecure($messageID); ?>"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['ok']); ?>">
                    <?php echo iN_HelpSecure($LANG['ok']); ?>
                </div>
                <div class="alertBtnLeft no-del transition"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['no']); ?>">
                    <?php echo iN_HelpSecure($LANG['no']); ?>
                </div>
            </div>
            <!-- /Modal Footer -->
        </div>
    </div>
    <!--/SHARE-->
</div>