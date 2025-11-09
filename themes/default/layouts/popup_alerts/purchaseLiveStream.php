<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="joinLiveStreamModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in i_sf_box">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="purchase_premium_header flex_ tabing border_top_radius" id="joinLiveStreamModalTitle">
                <?php echo iN_HelpSecure($LANG['join_the_live_broadcast']); ?>
            </div>

            <!-- Modal Body -->
            <div class="purchase_post_details">
                <div class="wallet-debit-confirm-container flex_">
                    <div class="owner_avatar"
                         style="background-image:url('<?php echo iN_HelpSecure($liveCreatorAvatar); ?>');"
                         aria-hidden="true">
                    </div>
                    <div class="album-details">
                        <?php echo iN_HelpSecure($LANG['paying_point_for_live_streaming']); ?>
                    </div>
                    <div class="album-wanted-point">
                        <div><?php echo html_entity_decode($liveCredit); ?></div>
                        <span><?php echo iN_HelpSecure($LANG['points']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="i_modal_g_footer">
                <div class="alertBtnRight joinLiveStream transition"
                     id="<?php echo iN_HelpSecure($liveID); ?>"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['confirm']); ?>">
                    <?php echo iN_HelpSecure($LANG['confirm']); ?>
                </div>
                <div class="alertBtnLeft no-del transition"
                     role="button"
                     aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
        </div>
    </div>
    <!--/SHARE-->
</div>