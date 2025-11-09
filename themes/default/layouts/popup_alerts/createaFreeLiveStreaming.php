<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="createFreeLiveTitle">
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <?php
            $currentDateNumber = '1';
            $finishDateNumber = '2';
            if ($l_Time) {
                $currentDateNumber = date('d', $currentTime);
                $finishDateNumber = date('d', $l_Time);
            }

            if ($l_Time && $currentDateNumber == $finishDateNumber) {
                if ($currentTime > $l_Time) {
            ?>
                    <!-- Modal Header -->
                    <div class="i_modal_g_header" id="createFreeLiveTitle">
                        <?php echo iN_HelpSecure($LANG['create_a_free_live_streaming']); ?>
                        <div class="shareClose transition" role="button" aria-label="Close">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                        </div>
                    </div>
                    <!-- /Modal Header -->

                    <!-- Modal Body -->
                    <div class="i_more_text_wrapper">
                        <?php if ($currentDateNumber == $finishDateNumber) { ?>
                            <?php echo iN_HelpSecure($LANG['filled_daily_live_broadcast']); ?>
                        <?php } else { ?>
                            <div class="give_a_name"><?php echo iN_HelpSecure($LANG['give_this_live_stream_a_name']); ?></div>
                            <div class="i_live_c_item">
                                <input type="text" name="liveName" id="liveName" class="flnm" aria-label="<?php echo iN_HelpSecure($LANG['give_this_live_stream_a_name']); ?>">
                            </div>
                            <div class="free_live_not flex_ alignItem">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('32')); ?>
                                <?php echo iN_HelpSecure($LANG['free_live_not']); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- /Modal Body -->

                    <!-- Modal Footer -->
                    <div class="i_block_box_footer_container">
                        <div class="alertBtnLeft no-del transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                            <?php echo iN_HelpSecure($LANG['cancel']); ?>
                        </div>
                    </div>
                    <!-- /Modal Footer -->
                <?php
                } else {
                ?>
                    <!-- Modal Header -->
                    <div class="i_modal_g_header" id="createFreeLiveTitle">
                        <?php echo iN_HelpSecure($LANG['create_a_free_live_streaming']); ?>
                        <div class="shareClose transition" role="button" aria-label="Close">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                        </div>
                    </div>
                    <!-- /Modal Header -->

                    <!-- Modal Body -->
                    <div class="i_more_text_wrapper">
                        <?php echo iN_HelpSecure($LANG['already_created_live_breadcast']); ?>
                    </div>

                    <!-- Modal Footer -->
                    <div class="i_block_box_footer_container">
                        <a href="<?php echo iN_HelpSecure($base_url . 'live/' . $userName); ?>">
                            <div class="alertBtnRightWithIcon continue transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['continue']); ?>">
                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('98')); ?>
                                <?php echo iN_HelpSecure($LANG['continue']); ?>
                            </div>
                        </a>
                        <div class="alertBtnLeft no-del transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                            <?php echo iN_HelpSecure($LANG['cancel']); ?>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <!-- Modal Header -->
                <div class="i_modal_g_header" id="createFreeLiveTitle">
                    <?php echo iN_HelpSecure($LANG['create_a_free_live_streaming']); ?>
                    <div class="shareClose transition" role="button" aria-label="Close">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                    </div>
                </div>
                <!-- /Modal Header -->

                <!-- Modal Body -->
                <div class="i_more_text_wrapper">
                    <div class="give_a_name"><?php echo iN_HelpSecure($LANG['give_this_live_stream_a_name']); ?></div>
                    <div class="i_live_c_item">
                        <input type="text" name="liveName" id="liveName" class="flnm" aria-label="<?php echo iN_HelpSecure($LANG['give_this_live_stream_a_name']); ?>">
                    </div>
                    <div class="free_live_not flex_ alignItem">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('32')); ?>
                        <?php echo iN_HelpSecure($LANG['free_live_not']); ?>
                    </div>
                    <?php echo html_entity_decode($liveStreamNotForNonCreators); ?>
                    <div class="box_not warning_required require"><?php echo iN_HelpSecure($LANG['enter_live_stream_title']); ?></div>
                    <div class="box_not warning_required name_short"><?php echo iN_HelpSecure($LANG['stream_name_wrning']); ?></div>
                </div>
                <!-- /Modal Body -->

                <!-- Modal Footer -->
                <div class="i_block_box_footer_container">
                    <div class="alertBtnRightWithIcon createLiveStream transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['create']); ?>">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                        <?php echo iN_HelpSecure($LANG['create']); ?>
                    </div>
                    <div class="alertBtnLeft no-del transition" role="button" aria-label="<?php echo iN_HelpSecure($LANG['cancel']); ?>">
                        <?php echo iN_HelpSecure($LANG['cancel']); ?>
                    </div>
                </div>
                <!-- /Modal Footer -->
            <?php } ?>
        </div>
    </div>
</div>