<div class="live_stream_list flex_">
    <!-- PAID LIVE SECTION -->
    <div class="live_item_cont paidLive">
        <?php if ($logedIn === '1') : ?>
            <div class="new_s_one new_s_first cNLive" data-type="paidLive" role="button" tabindex="0" aria-label="<?php echo iN_HelpSecure($LANG['start_new_paid_live_stream']); ?>">
                <div class="flex_ alignItem">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                    <?php echo iN_HelpSecure($LANG['start_new_paid_live_stream']); ?>
                </div>
            </div>
        <?php endif; ?>

        <a href="<?php echo iN_HelpSecure($base_url . 'live_streams?live=paid'); ?>" title="<?php echo iN_HelpSecure($LANG['paid_live_streamings']); ?>">
            <div class="live_item transition">
                <div class="live_title flex_">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('133')); ?>
                    <?php echo iN_HelpSecure($LANG['paid_live_streamings']); ?>
                </div>
            </div>
        </a>
    </div>

    <!-- FREE LIVE SECTION -->
    <div class="live_item_cont freeLive">
        <?php if ($logedIn === '1') : ?>
            <div class="new_s_one new_s_second cNLive" data-type="freeLive" role="button" tabindex="0" aria-label="<?php echo iN_HelpSecure($LANG['start_new_free_live_stream']); ?>">
                <div class="flex_ alignItem">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                    <?php echo iN_HelpSecure($LANG['start_new_free_live_stream']); ?>
                </div>
            </div>
        <?php endif; ?>

        <a href="<?php echo iN_HelpSecure($base_url . 'live_streams?live=free'); ?>" title="<?php echo iN_HelpSecure($LANG['free_live_streams']); ?>">
            <div class="live_item transition">
                <div class="live_title flex_">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('134')); ?>
                    <?php echo iN_HelpSecure($LANG['free_live_streams']); ?>
                </div>
            </div>
        </a>
    </div>
</div>