<div class="i_modal_bg_in">
    <div class="i_modal_in_in">
        <div class="i_modal_content general_conf">
            <div class="i_modal_g_header margin_bottom_custom_css_js">
                <?php echo iN_HelpSecure($LANG['create_a_new_question_answer']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>
            <form enctype="multipart/form-data" method="post" id="newQAForm">
                <div class="i_plnn_container flex_ i_plnn_containerst">
                    <input type="text" name="newq" class="point_input" placeholder="<?php echo iN_HelpSecure($LANG['question']); ?>">
                </div>
                <div class="i_editsvg_code flex_ tabing">
                    <textarea class="svg_more_textarea" name="newqa" placeholder="<?php echo iN_HelpSecure($LANG['answer']); ?>"></textarea>
                </div>
                <div class="warning_wrapper papk_wraning box_not_padding_left_plus_ten">
                    <?php echo iN_HelpSecure($LANG['question_and_answer_must_be_filled']); ?>
                </div>
                <div class="i_modal_g_footer flex_">
                    <input type="hidden" name="f" value="newQA">
                    <div class="popupSaveButton transition">
                        <button type="submit" name="submit" class="i_nex_btn_btn transition" id="updateGeneralSettings">
                            <?php echo iN_HelpSecure($LANG['save_new_question_answer']); ?>
                        </button>
                    </div>
                    <div class="alertBtnLeft no-del transition">
                        <?php echo iN_HelpSecure($LANG['no']); ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
<script src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/newQuestionAnswerHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
</div> 