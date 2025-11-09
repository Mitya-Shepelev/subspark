<div class="i_modal_bg_in">
    <!-- SHARE -->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['decline_payment_request']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /Modal Header -->

            <div class="i_delete_post_description">
                <?php echo iN_HelpSecure($LANG['sure_to_decline_this_payment']); ?>
            </div>

            <!-- Modal Footer -->
            <div class="i_modal_g_footer flex_">
                <div class="alertBtnRight yesDecline transition" id="<?php echo iN_HelpSecure($declinedID); ?>">
                    <?php echo iN_HelpSecure($LANG['ok_decline']); ?>
                </div>
                <div class="alertBtnLeft no-del transition">
                    <?php echo iN_HelpSecure($LANG['no']); ?>
                </div>
            </div>
            <!-- /Modal Footer -->
        </div>
    </div>
    <!-- /SHARE -->
</div>