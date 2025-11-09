<div class="i_modal_bg_in">
    <div class="i_modal_in_in">
        <div class="i_modal_content">
            <!-- Modal Header -->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['payment_details']); ?>
                <div class="shareClose transition">
                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="i_delete_post_description column positionRelative">
                <div class="purchase_post_details">
                    <div class="wallet-debit-confirm-container flex_">
                        <div class="owner_avatar" style="background-image: url('<?php echo $iN->iN_UserAvatar($pData['payer_iuid_fk'], $base_url); ?>');"></div>
                        <div class="wuser">
                            <a href="<?php echo $base_url . $wDetUserData['i_username']; ?>" target="_blank">
                                <?php echo $wDetUserData['i_user_fullname']; ?>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="withdraw_other_details border_one flex_ column tabing">
                    <div class="withdrawl_detail_box flex_">
                        <div class="withdrawl_detail_box_item"><?php echo iN_HelpSecure($LANG['amount_paid']); ?></div>
                        <div class="withdrawl_detail_box_item_ f_bold"><?php echo $pData['amount'] . $currencys[$defaultCurrency]; ?></div>
                    </div>

                    <div class="withdrawl_detail_box flex_ withdrawl_detail_box_bank_image">
                        <img src="<?php echo $iN->iN_GetImageByID($base_url, $userID, $ImageFileID); ?>" alt="Bank Receipt">
                    </div>

                    <div class="withdrawl_detail_box flex_">
                        <div class="withdrawl_detail_box_item"><?php echo $LANG['requested_date']; ?></div>
                        <div class="withdrawl_detail_box_item_"><?php echo date('d/m/Y', $pData['payment_time']); ?></div>
                    </div>
                </div>

                <div class="withdraw_other_details border_one flex_ column tabing progme i_bank_detail_margin_top">
                    <div class="flex_ tabing">
                        <div class="appr c2 border_one transition tabing flex_ approve_bank_payment"
                             data-user="<?php echo iN_HelpSecure($pData['payer_iuid_fk']); ?>"
                             data-plan="<?php echo iN_HelpSecure($planID); ?>"
                             data-img="<?php echo iN_HelpSecure($ImageFileID); ?>"
                             data-payment="<?php echo iN_HelpSecure($paymentID); ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('69')); ?>
                            <?php echo iN_HelpSecure($LANG['approve']); ?>
                        </div>

                        <div class="seePost c7 border_one transition tabing flex_ decline_bank_payment product_margin_left"
                             data-user="<?php echo iN_HelpSecure($pData['payer_iuid_fk']); ?>"
                             data-plan="<?php echo iN_HelpSecure($planID); ?>"
                             data-img="<?php echo iN_HelpSecure($ImageFileID); ?>"
                             data-payment="<?php echo iN_HelpSecure($paymentID); ?>">
                            <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('28')); ?>
                            <?php echo iN_HelpSecure($LANG['delete']); ?>
                        </div>
                    </div>

                    <div class="after_approve flex_ tabing i_bank_detail_margin_top">
                        <?php echo iN_HelpSecure($LANG['approve_bank_payment_not']); ?>
                    </div>

                    <div class="after_delete flex_ tabing i_bank_detail_margin_top">
                        <?php echo iN_HelpSecure($LANG['decline_bank_payment_not']); ?>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="i_modal_g_footer flex_">
                <div class="alertBtnLeft no-del transition">
                    <?php echo iN_HelpSecure($LANG['close']); ?>
                </div>
            </div>
        </div>
    </div>
</div>