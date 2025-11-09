<?php
$aData = $iN->iN_ShowAnnouncement();

if ($aData) {
    $announcementID = $aData['a_id'];
    $announcementNot = $aData['a_text'];
    $announcementType = $aData['a_who_see'];
    $checkAnnouncementAcceptedBefore = $iN->iN_CheckUserAcceptedAnnouncementBefore($userID, $announcementID);

    $showForEveryone = $checkAnnouncementAcceptedBefore && $announcementType === 'everyone';
    $showForCreators = $checkAnnouncementAcceptedBefore && $announcementType === 'creators' && $conditionStatus === '2';

    if ($showForEveryone || $showForCreators) {
?>
<div class="announcement_container">
    <div class="announcement_title flex_ tabing_non_justify">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('171')); ?>
        <?php echo iN_HelpSecure($LANG['announcement_title']); ?>
    </div>
    <div class="announcement_not" data-announcement-id="<?php echo iN_HelpSecure($announcementID); ?>">
        <?php echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($announcementNot), $base_url)); ?>
    </div>
    <div class="git">
        <div class="got_it flex_ tabing gotit"><?php echo iN_HelpSecure($LANG['got_it']); ?></div>
    </div>
</div> 
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/announcementHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
<?php
    }
}
?>