<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['pages']); ?>
        </div>
        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="new_svg_icon_wrapper margin_bottom_custom_css_js">
                <div class="newpa inline_block">
                    <a href="<?php echo iN_HelpSecure($base_url) . 'admin/pages?new=1'; ?>">
                        <div class="flex_ tabing_non_justify newSvgCode border_one">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('91')); ?>
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('124')); ?>
                            <?php echo iN_HelpSecure($LANG['create_a_new_page']); ?>
                        </div>
                    </a>
                </div>
            </div>
            <?php $pages = $iN->iN_GetPages(); if ($pages) { ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['page_title']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['seo_url']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['actions']); ?></th>
                        </tr>
                        <?php
                        foreach ($pages as $pageData) {
                            $pageID = $pageData['page_id'] ?? null;
                            $pageTitle = $pageData['page_title'] ?? null;
                            $pageSEOUrl = $pageData['page_name'] ?? null;
                            $seePage = $base_url . $pageSEOUrl;
                            $editPage = $base_url . 'admin/pages?pid=' . $pageID;
                        ?>
                            <tr class="transition trhover">
                                <td><?php echo iN_HelpSecure($pageID); ?></td>
                                <td><?php echo iN_HelpSecure($pageTitle); ?></td>
                                <td><?php echo iN_HelpSecure($pageSEOUrl); ?></td>
                                <td class="flex_ tabing_non_justify">
                                    <div class="flex_ tabing_non_justify">
                                        <?php if ($pageSEOUrl != 'contact') { ?>
                                            <div class="delu delpage border_one transition tabing flex_" id="<?php echo iN_HelpSecure($pageID); ?>">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                                            </div>
                                            <div class="seePost c2 border_one transition tabing flex_" id="<?php echo iN_HelpSecure($pageID); ?>">
                                                <a class="tabing flex_" href="<?php echo iN_HelpSecure($editPage, FILTER_VALIDATE_URL); ?>">
                                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('27')) . $LANG['edit_page']; ?>
                                                </a>
                                            </div>
                                        <?php } ?>
                                        <div class="seePost c3 border_one transition tabing flex_" id="<?php echo iN_HelpSecure($pageID); ?>">
                                            <a class="tabing flex_" href="<?php echo iN_HelpSecure($seePage, FILTER_VALIDATE_URL); ?>" target="blank_">
                                                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('83')) . $LANG['view_page']; ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>