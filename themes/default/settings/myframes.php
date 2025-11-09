<div class="settings_main_wrapper">
  <div class="i_settings_wrapper_in i_inline_table">
     <div class="i_settings_wrapper_title">
       <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('180'));?><?php echo iN_HelpSecure($LANG['my_frames']);?></div>
       <div class="i_moda_header_nt"><?php echo iN_HelpSecure($LANG['use_gift_frame']);?></div>
    </div>
    <div class="i_settings_wrapper_items">
       <div class="i_tab_container i_tab_padding">
           <div class="i_more_frames_wrapper flex_ tabing__justify">
            <?php
               $frameData = $iN->iN_FrameListWithCheckFromUserDashboard($userID);

if ($frameData) {
    foreach ($frameData as $fData) {
        $fDataID = $fData['f_id'] ? $fData['f_id'] : NULL;
        $fDataFile = $fData['f_file'] ? $fData['f_file'] : NULL;
        $fDataPrice = $fData['f_price'] ? $fData['f_price'] : NULL;
        $frameFileUrl = $base_url . $fDataFile;
        $onePointEqual = (float)$onePointEqual;
        $fDataPrice = (float)$fDataPrice;
        $frameMoneyEqual = $onePointEqual * $fDataPrice;
        $purchased = $fData['purchased'];
        $notPurchasedStyle = '';
        if(!$purchased){
            $notPurchasedStyle = 'not_purchased_frame_style';
        }
        ?>
        <!---->
        <div class="credit_plan_box credit_plan_box_<?php echo iN_HelpSecure($fDataID);?> <?php echo iN_HelpSecure($notPurchasedStyle);?>" id="<?php echo iN_HelpSecure($fDataID);?>">
            <div class="plan_box_frame tabing flex_">
                <div class="a_image_area_live_gift flex_ tabing border_one theaImage" style="background-image:url(<?php echo $frameFileUrl;?>)"><img class="a-item-img_live_gift" src="<?php echo $frameFileUrl;?>"></div>
                <?php if ($purchased) { ?>
                    <div class="plan_value">
                        <!---->
                        <div class="purchaseButton updateMyFrame flex_ tabing" data-id="<?php echo iN_HelpSecure($fDataID);?>">
                            Use this frame
                            <div class="foramount">You have this frame</div>
                        </div>
                        <!---->
                    </div>
                <?php } else { ?>
                    <div class="plan_value">
                        <div class="plan_price tabing">
                            <div class="plan_price_in">
                                <?php echo number_format($fDataPrice); ?>
                                <span class="plan_point_icon">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                </span>
                            </div>
                        </div>
                        <div class="plan_point tabing flex_"><?php echo iN_HelpSecure($LANG['points']); ?></div>
                        <!---->
                        <div class="purchaseButton flex_ tabing">
                            <?php echo iN_HelpSecure($LANG['purchase']); ?>
                            <strong class="tabing flex_ purchaseButton_wrap">
                                <?php echo number_format($fDataPrice); ?>
                                <span class="prcsic">
                                    <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')); ?>
                                </span>
                            </strong>
                            <div class="foramount"><?php echo iN_HelpSecure($LANG['for']) . ' ' . $currencys[$defaultCurrency] . number_format($frameMoneyEqual); ?></div>
                        </div>
                        <!---->
                    </div>
                <?php } ?>
            </div>
        </div>
        <!---->
<?php
    }
}
?>
            </div>
       </div>
    </div>
  </div>
</div>