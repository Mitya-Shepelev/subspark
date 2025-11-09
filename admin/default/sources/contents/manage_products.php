<?php

$searchUser = '';
$totalProduct = $iN->iN_TotalProducts($userID);
$totalPages = ceil($totalProduct / $paginationLimit);

if (isset($_GET["page-id"])) {
    $pagep = isset($_GET["page-id"]) ? (int)$_GET["page-id"] : 1;
    if (!preg_match('/^[0-9]+$/', $pagep)) {
        $pagep = '1';
    }
} else {
    $pagep = '1';
}

if (isset($_GET['sr'])) {
    $searchUser = isset($_GET['sr']) ? $iN->iN_Secure($_GET['sr']) : '';
}
?>
<div class="i_contents_container">
    <div class="i_general_white_board border_one column flex_ tabing__justify">
        <div class="i_general_title_box">
            <?php echo iN_HelpSecure($LANG['u_products']) . '(' . $totalProduct . ')'; ?>
        </div>

        <div class="i_general_row_box column flex_ white_board_padding_" id="general_conf">
            <div class="i_contents_section flex_ tabing manage_margin_bottom">

                <?php
                $categories = [
                    ['id' => '159', 'key' => 'scratch', 'class' => 'cibBoxColorOne_One'],
                    ['id' => '160', 'key' => 'bookazoom', 'class' => 'cibBoxColorOne'],
                    ['id' => '161', 'key' => 'digitaldownload', 'class' => 'cibBoxColorTwo'],
                    ['id' => '162', 'key' => 'liveeventticket', 'class' => 'cibBoxColorThree'],
                    ['id' => '163', 'key' => 'artcommission', 'class' => 'cibBoxColorFour'],
                    ['id' => '164', 'key' => 'joininstagramclosefriends', 'class' => 'cibBoxColorFive']
                ];

                foreach ($categories as $cat) {
                    echo '
                    <div class="row_wrapper">
                        <div class="row_item flex_ column border_one cibBoxColorOne_One_black ' . $cat['class'] . '">
                            <div class="chart_row_box_title flex_ tabing_non_justify">
                                ' . html_entity_decode($iN->iN_SelectedMenuIcon($cat['id'])) . iN_HelpSecure($LANG[$cat['key']]) . '
                            </div>
                            <div class="chart_row_box_sum">
                                <span class="count-num">' . iN_HelpSecure($iN->iN_GetTotalProductByCategory($userID, $cat['key'])) . '</span>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>

            <div class="warning_"><?php echo iN_HelpSecure($LANG['noway_desc']); ?></div>

            <?php
            $allUsers = $iN->iN_AllTypeOfProductList($userID, $paginationLimit, $pagep, $searchUser);
            if ($allUsers) {
            ?>
                <div class="i_overflow_x_auto">
                    <table class="border_one">
                        <tr>
                            <th><?php echo iN_HelpSecure($LANG['id']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['product_owner']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['product_name']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['share_time']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['product_price']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['product_type']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['pp_sales']); ?></th>
                            <th><?php echo iN_HelpSecure($LANG['actions']); ?></th>
                        </tr>
                        <?php
foreach ($allUsers as $oprod) {
    $sProductID = $oprod['pr_id'] ?? null;
    $sProductName = $oprod['pr_name'] ?? null;
    $sProductPrice = $oprod['pr_price'] ?? null;
    $sProductFiles = $oprod['pr_files'] ?? null;
    $sProductOwnerID = $oprod['iuid_fk'] ?? null;
    $sProductSlug = $oprod['pr_name_slug'] ?? null;
    $sProductType = $oprod['product_type'] ?? null;
    $sProductCreatedTime = $oprod['pr_created_time'] ?? null;
    $crTime = date('Y-m-d H:i:s', $sProductCreatedTime);
    $p__style = $sProductType;

    if ($sProductType === 'scratch') {
        $sProductType = 'simple_product';
        $p__style = 'scratch';
    }

    $sSlugUrl = $base_url . 'product/' . $sProductSlug . '_' . $sProductID;
    $strimValue = rtrim($sProductFiles, ',');
    $snums = preg_split('/\s*,\s*/', $strimValue);
    $slastFileID = end($snums);
    $spfData = $iN->iN_GetUploadedFileDetails($slastFileID);

    if ($spfData) {
        $sfilePath = $spfData['uploaded_file_path'] ?? null;
        if (function_exists('storage_public_url')) {
            $sproductDataImage = storage_public_url($sfilePath);
        } else {
            $sproductDataImage = $base_url . $sfilePath;
        }
    }

    $userAvatar = $iN->iN_UserAvatar($sProductOwnerID, $base_url);
    $userUserName = $oprod['i_username'] ?? null;
    $userUserFullName = $oprod['i_user_fullname'] ?? null;
    ?>
    <tr class="transition trhover">
        <td><?php echo iN_HelpSecure($sProductID); ?></td>
        <td>
            <div class="t_od flex_ c6">
                <div class="t_owner_avatar border_two tabing flex_">
                    <img src="<?php echo iN_HelpSecure($userAvatar, FILTER_VALIDATE_URL); ?>">
                </div>
                <div class="t_owner_user tabing flex_">
                    <a class="truncated" href="<?php echo iN_HelpSecure($base_url, FILTER_VALIDATE_URL) . $userUserName; ?>">
                        <?php echo iN_HelpSecure($userUserFullName); ?>
                    </a>
                </div>
            </div>
        </td>
        <td class="see_post_details">
            <div class="flex_ tabing_non_justify see_post_details_a">
                <a href="<?php echo iN_HelpSecure($sSlugUrl, FILTER_VALIDATE_URL); ?>">
                    <?php echo $urlHighlight->highlightUrls($iN->sanitize_output($iN->iN_RemoveYoutubelink($sProductName), $base_url)); ?>
                </a>
            </div>
        </td>
        <td class="see_post_details">
            <div class="flex_ tabing_non_justify">
                <div class="tim flex_ tabing">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73')) . ' ' . TimeAgo::ago($crTime, date('Y-m-d H:i:s')); ?>
                </div>
            </div>
        </td>
        <td class="see_post_details">
            <div class="flex_ tabing_non_justify">
                <div class="tim flex_ tabing">
                    <?php echo iN_HelpSecure($sProductPrice) . $currencys[$defaultCurrency]; ?>
                </div>
            </div>
        </td>
        <td class="see_post_details">
            <div class="flex_ tabing_non_justify">
                <div class="i_sub_not_check_box type_news positionRelative <?php echo iN_HelpSecure($p__style); ?>">
                    <?php echo iN_HelpSecure($LANG[$sProductType]); ?>
                </div>
            </div>
        </td>
        <td class="see_post_details">
            <div class="flex_ tabing_non_justify">
                <div class="tim flex_ tabing">
                    <?php echo iN_HelpSecure($iN->iN_TotalProductSell($sProductID)); ?>
                </div>
            </div>
        </td>
        <td class="flex_ tabing_non_justify">
            <div class="flex_ tabing_non_justify">
                <div class="delu del_ProdPopUP border_one transition tabing flex_ delete" id="<?php echo iN_HelpSecure($sProductID); ?>">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')) . $LANG['delete']; ?>
                </div>
            </div>
        </td>
    </tr>
    <?php
}
?>
                    </table>
                </div>
            <?php
            } else {
                echo '<div class="no_creator_f_wrap flex_ tabing"><div class="no_c_icon">' . $iN->iN_SelectedMenuIcon('54') . '</div><div class="n_c_t">' . $LANG['no_user_found'] . '</div></div>';
            }
            ?>

        </div>

        <div class="i_become_creator_box_footer tabing">
            <?php if ($totalPages >= 1): ?>
                <ul class="pagination">
                    <?php if ($pagep > 1): ?>
                        <li class="prev">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_products?page-id=<?php echo iN_HelpSecure($pagep - 1); ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo iN_HelpSecure($LANG['preview_page']); ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep > 3): ?>
                        <li class="start"><a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_products?page-id=1&sr=<?php echo iN_HelpSecure($searchUser); ?>">1</a></li>
                        <li class="dots">...</li>
                    <?php endif; ?>

                    <?php for ($i = max(1, $pagep - 2); $i <= min($totalPages, $pagep + 2); $i++): ?>
                        <li class="<?php echo $i == $pagep ? 'currentpage active' : 'page'; ?>">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_products?page-id=<?php echo $i; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($pagep < $totalPages - 2): ?>
                        <li class="dots">...</li>
                        <li class="end">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_products?page-id=<?php echo $totalPages; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo $totalPages; ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <?php if ($pagep < $totalPages): ?>
                        <li class="next">
                            <a class="transition" href="<?php echo iN_HelpSecure($base_url); ?>admin/manage_products?page-id=<?php echo $pagep + 1; ?>&sr=<?php echo iN_HelpSecure($searchUser); ?>">
                                <?php echo iN_HelpSecure($LANG['next_page']); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<script>
    window.appBaseUrl = "<?php echo iN_HelpSecure($base_url); ?>";
</script> 
<script type="text/javascript" src="<?php echo iN_HelpSecure($base_url); ?>admin/<?php echo iN_HelpSecure($adminTheme); ?>/js/manageProductsHandler.js?v=<?php echo iN_HelpSecure($version); ?>" defer></script>
