<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="aiModalTitle">
    <!-- MODAL CONTENT -->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- MODAL HEADER -->
            <div class="i_modal_g_header" id="aiModalTitle">
                <?php echo iN_HelpSecure($LANG['generate_ai_content']); ?>
                <div class="shareClose transition" role="button" aria-label="Close Modal">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /MODAL HEADER -->

            <!-- MODAL BODY -->
            <div class="i_more_text_wrapper">
                <div class="i_warning_ai nonePoint" role="alert">
                    <?php echo iN_HelpSecure($LANG['please_check_api_key']); ?>
                </div>
                <div class="i_warning_ai_credit nonePoint" role="alert">
                    <?php echo iN_HelpSecure($LANG['no_enough_credit']); ?>
                </div>

                <div class="i_editai_textarea_box">
                    <textarea 
                        class="ai_more_textarea aiContT"
                        placeholder="<?php echo iN_HelpSecure($LANG['write_topic_for_ai_content']); ?>"
                        aria-label="<?php echo iN_HelpSecure($LANG['write_topic_for_ai_content']); ?>">
                    </textarea>
                </div>

                <div class="free_live_not flex_ alignItem">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('32')); ?>
                    <?php echo iN_HelpSecure($LANG['generate_not']); ?>
                </div>
            </div>
            <!-- /MODAL BODY -->

            <!-- MODAL FOOTER -->
            <div class="i_block_box_footer_container">
                <div class="alertBtnRightWithIcon createAiContent transition" role="button">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                    <?php echo iN_HelpSecure($LANG['create']); ?>
                </div>
                <div class="alertBtnLeft no-del transition" role="button">
                    <?php echo iN_HelpSecure($LANG['cancel']); ?>
                </div>
            </div>
            <!-- /MODAL FOOTER -->
        </div>
    </div>
    <!-- /MODAL CONTENT -->
</div>