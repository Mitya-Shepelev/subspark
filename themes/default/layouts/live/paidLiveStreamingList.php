<!-- Start a new paid live stream button -->
<div class="new_s_one new_s_first cNLive" data-type="paidLive">
    <div class="flex_ alignItem">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
        <?php echo iN_HelpSecure($LANG['start_new_paid_live_stream']); ?>
    </div>
</div>

<!-- Paid live streamings header -->
<div class="live_item transition">
    <div class="live_title_page flex_">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('133')); ?>
        <?php echo iN_HelpSecure($LANG['paid_live_streamings']); ?>
    </div>
</div>

<!-- Paid live stream list -->
<div class="free_live_Streamings_list_container" id="moreType" data-type="<?php echo iN_HelpSecure($liveListType); ?>">
    <?php include 'live_list.php'; ?>
</div>