<div class="i_modal_bg_in">
    <!-- SHARE MODAL -->
    <div class="i_modal_in_in">
        <div class="i_modal_content">

            <!-- Modal Header -->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['select_privacy']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /Modal Header -->

            <!-- Sharing Options -->
            <div class="i_sharing_post_wrapper">
                <div class="i_wcs_in">

                    <!-- Who Can See: Everyone -->
                    <div class="who_can_see_pop_item transition wcs<?php echo iN_HelpSecure($postID); ?>
                        <?php echo iN_HelpSecure($whoCSee) === '1' ? 'selectedWhoCanSee' : ''; ?>"
                        id="<?php echo iN_HelpSecure($postID); ?>" data-id="1">

                        <div class="whoCanSeeIcon">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('50')); ?>
                        </div>
                        <div class="whoCanSeeTit">
                            <?php echo iN_HelpSecure($LANG['weveryone']); ?>
                        </div>
                        <div class="i_whoCC">
                            <div class="whoCC">
                                <div class="whoCCbox wcsc_<?php echo iN_HelpSecure($postID); ?>
                                    <?php echo iN_HelpSecure($whoCSee) === '1' ? 'whoCCboxActive' : 'whoCCboxPassive'; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Who Can See: Followers -->
                    <div class="who_can_see_pop_item transition wcs<?php echo iN_HelpSecure($postID); ?>
                        <?php echo iN_HelpSecure($whoCSee) === '2' ? 'selectedWhoCanSee' : ''; ?>"
                        id="<?php echo iN_HelpSecure($postID); ?>" data-id="2">

                        <div class="whoCanSeeIcon">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('15')); ?>
                        </div>
                        <div class="whoCanSeeTit">
                            <?php echo iN_HelpSecure($LANG['wfollowers']); ?>
                        </div>
                        <div class="i_whoCC">
                            <div class="whoCC">
                                <div class="whoCCbox wcsc_<?php echo iN_HelpSecure($postID); ?>
                                    <?php echo iN_HelpSecure($whoCSee) === '2' ? 'whoCCboxActive' : 'whoCCboxPassive'; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($feesStatus === '2') : ?>
                        <!-- Who Can See: Subscribers -->
                        <div class="who_can_see_pop_item transition wcs<?php echo iN_HelpSecure($postID); ?>
                            <?php echo iN_HelpSecure($whoCSee) === '3' ? 'selectedWhoCanSee' : ''; ?>"
                            id="<?php echo iN_HelpSecure($postID); ?>" data-id="3">

                            <div class="whoCanSeeIcon">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('51')); ?>
                            </div>
                            <div class="whoCanSeeTit">
                                <?php echo iN_HelpSecure($LANG['wsubscribers']); ?>
                            </div>
                            <div class="i_whoCC">
                                <div class="whoCC">
                                    <div class="whoCCbox wcsc_<?php echo iN_HelpSecure($postID); ?>
                                        <?php echo iN_HelpSecure($whoCSee) === '3' ? 'whoCCboxActive' : 'whoCCboxPassive'; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
            <!-- /Sharing Options -->

        </div>
    </div>
    <!-- /SHARE MODAL -->
</div>