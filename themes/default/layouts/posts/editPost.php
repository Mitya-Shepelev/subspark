<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="editPostModalTitle">
    <!-- Modal Container -->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="editPostModalTitle">
                <?php echo iN_HelpSecure($LANG['edit_post']); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /Modal Header -->

            <!-- Post Editing Textarea -->
            <div class="i_more_text_wrapper">
                <label for="ed_<?php echo iN_HelpSecure($postID); ?>" class="visually-hidden">
                    <?php echo iN_HelpSecure($LANG['write_something_about_the_post']); ?>
                </label>
                <textarea 
                    class="more_textarea" 
                    id="ed_<?php echo iN_HelpSecure($postID); ?>" 
                    dir="auto" 
                    rows="5" 
                    placeholder="<?php echo iN_HelpSecure($LANG['write_something_about_the_post']); ?>"
                    aria-label="<?php echo iN_HelpSecure($LANG['write_something_about_the_post']); ?>"
                ><?php 
                    if (!empty($posText)) {
                        echo iN_HelpSecure($iN->br2nl($posText)); 
                    } 
                ?></textarea>
            </div>
            <!-- /Post Editing Textarea -->

            <!-- Modal Footer -->
            <div class="i_modal_g_footer">
                <div 
                    class="shareBtn sedt transition" 
                    id="<?php echo iN_HelpSecure($postID); ?>" 
                    role="button" 
                    aria-label="<?php echo iN_HelpSecure($LANG['save_edit']); ?>"
                >
                    <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                </div>
            </div>
            <!-- /Modal Footer -->
        </div>
    </div>
    <!-- /Modal Container -->
</div>