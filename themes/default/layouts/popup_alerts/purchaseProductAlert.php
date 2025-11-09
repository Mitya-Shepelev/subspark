<div class="i_modal_bg_in" role="dialog" aria-modal="true" aria-labelledby="buyProductModalTitle">
    <!--SHARE-->
    <div class="i_modal_in_in i_sf_box">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header" id="buyProductModalTitle">
                <?php echo iN_HelpSecure($LANG['buy_this_product']); ?>
                <div class="shareClose transition" role="button" aria-label="Close">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="purchase_pp_container">
                <div class="yourWallet flex_ tabing">
                    <div class="your_wallet_icon_cont flex_ tabing">
                        <div class="your_wallet_icon flex_ tabing">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                        </div>
                    </div>
                </div>

                <div class="p_p_wallet_cont">
                    <div class="h_product_title h_product_title_pop">
                        <?php echo iN_HelpSecure($LANG['your_point_balance']); ?>
                    </div>
                    <div class="crnt_points crnt_points_pop">
                        <?php echo number_format($userCurrentPoints); ?>
                        <span>(
                            <?php
                                echo $onePointEqual * $userCurrentPoints;
                                echo iN_HelpSecure($currencys[$defaultCurrency]);
                            ?>)
                        </span>
                    </div>

                    <?php
                    $prProductPrice = $prData['pr_price'];
                    $currentAmount = $onePointEqual * $userCurrentPoints;
                    if ($currentAmount >= $prProductPrice) {
                        echo '<div class="wallet-info-ok">' . iN_HelpSecure($LANG['your_balance_is_enough_for_purchase']) . '</div>';
                    } else {
                        echo '<div class="wallet-info-warning">' . iN_HelpSecure($LANG['not_enough_balance_purchase']) . '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!--/SHARE-->
</div>