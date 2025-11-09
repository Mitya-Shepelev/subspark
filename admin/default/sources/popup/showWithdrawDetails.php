<div class="i_modal_bg_in">
    <!--SHARE-->
    <div class="i_modal_in_in"> 
        <div class="i_modal_content">  
            <!--Modal Header-->
            <div class="i_modal_g_header">
                <?php echo iN_HelpSecure($LANG['withdrawal_details']); ?>
                <div class="shareClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?></div>
            </div>
            <!--/Modal Header-->
            <div class="i_delete_post_description column positionRelative">  
                <div class="purchase_post_details">
                    <div class="wallet-debit-confirm-container flex_">
                        <div class="owner_avatar" style="background-image: url(<?php echo $iN->iN_UserAvatar($wDet['iuid_fk'], $base_url); ?>);"></div>
                        <div class="wuser">
                            <a href="<?php echo $base_url . $wDetUserData['i_username']; ?>" target="_blank">
                                <?php echo iN_HelpSecure($wDetUserData['i_user_fullname']); ?>
                            </a>
                        </div>  
                    </div>
                </div>
                <div class="withdraw_other_details border_one flex_ column tabing">
                    <div class="withdrawl_detail_box flex_">
                        <div class="withdrawl_detail_box_item"><?php echo iN_HelpSecure($LANG['amount_requested']); ?></div>
                        <div class="withdrawl_detail_box_item_ f_bold"><?php echo $wDet['amount'] . $currencys[$defaultCurrency]; ?></div>
                    </div>
                    <div class="withdrawl_detail_box flex_">
                        <div class="withdrawl_detail_box_item"><?php echo iN_HelpSecure($LANG['payment_method']); ?></div>
                        <div class="withdrawl_detail_box_item_"><?php echo $wDet['method']; ?></div>
                    </div>
                    <div class="withdrawl_detail_box flex_">
                        <div class="withdrawl_detail_box_item"><?php echo iN_HelpSecure($LANG['payment_address']); ?></div>
                        <div class="withdrawl_detail_box_item_">
                            <?php 
                            if ($wDet['method'] == 'paypal') {
                                echo $wDetUserData['paypal_email']; 
                            } elseif ($wDet['method'] == 'bank') {
                                echo $wDetUserData['bank_account']; 
                            }
                            ?>
                        </div>
                    </div>
                    <div class="withdrawl_detail_box flex_">
                        <div class="withdrawl_detail_box_item"><?php echo iN_HelpSecure($LANG['requested_date']); ?></div>
                        <div class="withdrawl_detail_box_item_"><?php echo date('d/m/Y', $wDet['payout_time']); ?></div>
                    </div>
                    <div class="withdrawl_detail_box flex_">
                        <div class="withdrawl_detail_box_item"><?php echo iN_HelpSecure($LANG['status']); ?></div>
                        <div class="withdrawl_detail_box_item_">
                            <?php  
                            if ($wDet['status'] == 'pending') {
                                echo '<span class="tc1">' . $LANG['pending'] . '</span>';
                            } elseif ($wDet['status'] == 'payed') {
                                echo '<span class="tc2">' . $LANG['paid'] . '</span>';
                            } elseif ($wDet['status'] == 'declined') {
                                echo '<span class="tc3">' . $LANG['declined'] . '</span>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="withdrawl_detail_box flex_">
                        <div class="withdrawl_detail_box_item"><?php echo iN_HelpSecure($LANG['payment_date']); ?></div>
                        <div class="withdrawl_detail_box_item_">
                            <?php 
                            if (!empty($wDet['paid_time'])) {
                                echo date('d/m/Y', $wDet['paid_time']);
                            } else {
                                echo '-';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--Modal Footer-->
            <div class="i_modal_g_footer flex_"> 
                <div class="alertBtnLeft no-del transition"><?php echo iN_HelpSecure($LANG['close']); ?></div>
            </div>
            <!--/Modal Footer-->
        </div>   
    </div>
    <!--/SHARE--> 
</div>