<?php
if ($feesStatus != '2') {
    header('Location: ' . route_url('404'));
}
?>
<div class="settings_main_wrapper">
    <div class="i_settings_wrapper_in i_inline_table">
        <div class="i_settings_wrapper_title">
            <div class="i_settings_wrapper_title_txt flex_">
                <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('159')); ?>
                <?php echo iN_HelpSecure($LANG['createaProduct']); ?>
            </div>
            <div class="i_moda_header_nt">
                <?php echo iN_HelpSecure($LANG['create_your_own_product']); ?>
            </div>
        </div>
        <div class="i_settings_wrapper_items">
            <div class="i_tab_container i_tab_padding">
                <?php
                $createProof = array(
                    'scratch',
                    'bookazoom',
                    'digitaldownload',
                    'liveeventticket',
                    'artcommission',
                    'joininstagramclosefriends'
                );

                if (isset($_GET['create']) && in_array($_GET['create'], $createProof)) {
                    $proof = isset($_GET['create']) ? $iN->iN_Secure($_GET['create']) : '';
                    include_once("createProduct/" . $proof . ".php");
                } else {
                    ?>
                    <div class="crate_a_product_wrapper flex_">
                        <?php if ($iN->iN_ShopData($userID, 2) == 'yes') { ?>
                            <div class="crate_a_product_item">
                                <a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=createaProduct&create=scratch">
                                    <div class="start_from_scratch flex_ tabing">
                                        <?php echo iN_HelpSecure($LANG['scratch']); ?>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ($iN->iN_ShopData($userID, 3) == 'yes') { ?>
                            <div class="crate_a_product_item">
                                <a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=createaProduct&create=bookazoom">
                                    <div class="cretate_item_box cibBoxColorOne flex_ tabing_non_justify">
                                        <div class="cibIcon flex_ tabing">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('160')); ?>
                                        </div>
                                        <div class="cibTitle">
                                            <?php echo iN_HelpSecure($LANG['bookazoom']); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ($iN->iN_ShopData($userID, 4) == 'yes') { ?>
                            <div class="crate_a_product_item">
                                <a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=createaProduct&create=digitaldownload">
                                    <div class="cretate_item_box cibBoxColorTwo flex_ tabing_non_justify">
                                        <div class="cibIcon flex_ tabing">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('161')); ?>
                                        </div>
                                        <div class="cibTitle">
                                            <?php echo iN_HelpSecure($LANG['digitaldownload']); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ($iN->iN_ShopData($userID, 5) == 'yes') { ?>
                            <div class="crate_a_product_item">
                                <a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=createaProduct&create=liveeventticket">
                                    <div class="cretate_item_box cibBoxColorThree flex_ tabing_non_justify">
                                        <div class="cibIcon flex_ tabing">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('162')); ?>
                                        </div>
                                        <div class="cibTitle">
                                            <?php echo iN_HelpSecure($LANG['liveeventticket']); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ($iN->iN_ShopData($userID, 6) == 'yes') { ?>
                            <div class="crate_a_product_item">
                                <a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=createaProduct&create=artcommission">
                                    <div class="cretate_item_box cibBoxColorFour flex_ tabing_non_justify">
                                        <div class="cibIcon flex_ tabing">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('163')); ?>
                                        </div>
                                        <div class="cibTitle">
                                            <?php echo iN_HelpSecure($LANG['artcommission']); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>

                        <?php if ($iN->iN_ShopData($userID, 7) == 'yes') { ?>
                            <div class="crate_a_product_item">
                                <a href="<?php echo iN_HelpSecure($base_url); ?>settings?tab=createaProduct&create=joininstagramclosefriends">
                                    <div class="cretate_item_box cibBoxColorFive flex_ tabing_non_justify">
                                        <div class="cibIcon flex_ tabing">
                                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('164')); ?>
                                        </div>
                                        <div class="cibTitle">
                                            <?php echo iN_HelpSecure($LANG['joininstagramclosefriends']); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
