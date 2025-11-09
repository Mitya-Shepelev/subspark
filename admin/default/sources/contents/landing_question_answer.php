<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box flex_ tabing_non_justify">
            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('137')); ?>
            <?php echo iN_HelpSecure($LANG['landing_question_answer']); ?>
        </div>

        <div class="i_general_row_box column flex_" id="general_conf">
            <div class="new_svg_icon_wrapper product_page_loading">
                <div class="inline_block">
                    <div class="flex_ tabing_non_justify newSvgCode newCreate border_one" data-type="newQA">
                        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                        <?php echo iN_HelpSecure($LANG['create_a_new_question_answer']); ?>
                    </div>
                </div>
            </div>

            <?php  
            $pages = $iN->iN_ListQuestionAnswerFromLanding();
            if ($pages) { ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['question']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['actions']); ?></th>
                        </tr>
                        <?php
                        foreach ($pages as $pageData) {
                            $pageID = $pageData['qa_id'];
                            $pageTitle = $pageData['qa_title'];
                            $editPage = $base_url . 'admin/pages?pid=' . $pageID;
                            ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($pageID); ?></td>
                                <td><?php echo iN_HelpSecure($pageTitle); ?></td>
                                <td class="flex_ tabing_non_justify">
                                    <div class="flex_ tabing_non_justify">
                                        <div class="delu delqa border_one transition tabing flex_" id="<?php echo iN_HelpSecure($pageID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                                        </div>
                                        <div class="seePost c2 border_one transition tabing flex_ editQA" id="<?php echo iN_HelpSecure($pageID); ?>">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_qa']; ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        } ?>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>