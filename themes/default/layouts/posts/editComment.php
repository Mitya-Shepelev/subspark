<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="editCommentModalTitle">
    <!-- Modal container -->
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="editCommentModalTitle">
                <?php echo iN_HelpSecure($LANG['edit_comment']); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <!-- /Modal Header -->

            <!-- Editable Textarea -->
            <div class="i_more_text_wrapper">
                <label for="ed_<?php echo iN_HelpSecure($commentID); ?>" class="visually-hidden">
                    <?php echo iN_HelpSecure($LANG['write_your_comment']); ?>
                </label>
                <textarea 
                    class="more_textarea" 
                    id="ed_<?php echo iN_HelpSecure($commentID); ?>" 
                    dir="auto" 
                    rows="5" 
                    placeholder="<?php echo iN_HelpSecure($LANG['write_your_comment']); ?>"
                    aria-label="<?php echo iN_HelpSecure($LANG['write_your_comment']); ?>"
                ><?php 
                    if (!empty($commentText)) {
                        echo iN_HelpSecure($iN->br2nl($commentText)); 
                    } 
                ?></textarea>
            </div>
            <!-- /Editable Textarea -->

            <!-- Modal Footer -->
            <div class="i_modal_g_footer">
                <div 
                    class="shareBtn secdt transition" 
                    id="<?php echo iN_HelpSecure($commentID); ?>" 
                    data-id="<?php echo iN_HelpSecure($postID); ?>" 
                    role="button" 
                    aria-label="<?php echo iN_HelpSecure($LANG['save_edit']); ?>"
                >
                    <?php echo iN_HelpSecure($LANG['save_edit']); ?>
                </div>
            </div>
            <!-- /Modal Footer -->
        </div>
    </div>
    <!-- /Modal container -->
</div>