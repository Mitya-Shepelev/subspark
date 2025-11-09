<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="finishSubscriptionModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="finishSubscriptionModalTitle">
                <?php echo iN_HelpSecure($LANG['finish_subscription']); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <!-- Modal Description -->
            <div class="i_delete_post_description">
                <?php echo iN_HelpSecure($LANG['are_you_sure_want_to_finish_subscription']); ?>
            </div>

            <!-- Modal Footer -->
            <div class="i_modal_g_footer">
                <div class="alertBtnRight unSubNow transition"
                     id="<?php echo iN_HelpSecure($ui); ?>"
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
        </div>
    </div>
    <!--/SHARE-->
</div>