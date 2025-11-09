<?php
$ProductData = $iN->iN_ProductDetails($userID ,$editProductID);
if($ProductData){
    $productID                = $ProductData['pr_id'] ?? null;
    $productName              = $ProductData['pr_name'] ?? null;
    $productPrice             = $ProductData['pr_price'] ?? null;
    $productFiles             = $ProductData['pr_files'] ?? null;
    $productDescription       = $ProductData['pr_desc'] ?? null;
    $productDescriptionInfo   = $ProductData['pr_desc_info'] ?? null;
    $productSlotsNumber       = $ProductData['pr_slots_number'] ?? null;
    $productQuestionAnswer    = $ProductData['pr_question_answer'] ?? null;
    $askClass = iN_HelpSecure($productQuestionAnswer) != '' ? 'ask-visible' : 'ask-hidden';
    $slotClass = iN_HelpSecure($productSlotsNumber) != '' ? 'ask-visible' : 'ask-hidden';
?>
<div class="settings_main_wrapper">
    <div class="i_settings_wrapper_in i_inline_table">
            <div class="i_settings_wrapper_title">
                <div class="i_settings_wrapper_title_txt flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('158'));?><?php echo iN_HelpSecure($LANG['edit_product']);?></div>
            </div>
        <div class="i_settings_wrapper_items">
            <div class="i_tab_container i_tab_padding">
              <!--Edit Product Colums-->
              <div class="create_product_form">
                    <!---->
                    <div class="create_product_form_column flex_">
                        <div class="input_title">
                            <div class="input_title_title"><?php echo iN_HelpSecure($LANG['pr_name']);?></div>
                            <input type="text" id="pr_name" class="prc" placeholder="<?php echo iN_HelpSecure($LANG['what_are_you_offering']);?>" value="<?php echo iN_HelpSecure($productName);?>">
                        </div>
                        <div class="input_price">
                            <div class="input_title_title"><?php echo iN_HelpSecure($LANG['pr_price']);?></div>
                            <div class="relativePosition">
                                <input type="text" id="pr_price" class="prc input_prc_padding" value="<?php echo iN_HelpSecure($productPrice);?>">
                                <span class="prc_currency flex_ tabing"><?php echo iN_HelpSecure($currencys[$defaultCurrency]);?></span>
                            </div>
                        </div>
                    </div>
                    <!---->
                    <!---->
                    <div class="create_product_form_column">
                        <div class="col-tit flex_ tabing_non_justify"><?php echo iN_HelpSecure($LANG['description']);?><span class="flex_ tabing_non_justify ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['pr_description_info']);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('96'));?></span></div>
                        <div class="col-textarea-box">
                            <textarea class="col-textarea" id="pr_description" placeholder="<?php echo iN_HelpSecure($LANG['pr_description_placeholder']);?>"><?php echo iN_HelpSecure($productDescription);?></textarea>
                        </div>
                    </div>
                    <!---->
                    <!---->
                    <div class="create_product_form_column">
                        <div class="col-tit flex_ tabing_non_justify"><?php echo iN_HelpSecure($LANG['pr_conf_message']);?><span class="flex_ tabing_non_justify ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['pr_conf_info']);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('96'));?></span></div>
                        <div class="col-textarea-box">
                            <textarea class="col-textarea" id="pr_conf"><?php echo iN_HelpSecure($productDescriptionInfo);?></textarea>
                        </div>
                    </div>
                    <!---->
                    <!---->
                    <div class="create_product_form_column padding_bottom_zero">
                        <div class="col-tit-advanced-settings flex_ tabing_non_justify"><?php echo iN_HelpSecure($LANG['advanced_settings_title']);?></div>
                    </div>
                    <!---->
                    <!---->
                    <div class="create_product_form_column padding_bottom_zero">
                        <!--SET SUBSCRIPTION FEE BOX-->
                        <div class="i_set_subscription_fee_box padding_zero">
                            <div class="i_sub_not_check qmark">
                            <?php echo iN_HelpSecure($LANG['ask_a_question_optional']);?><span class="flex_ tabing_non_justify ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['additional_information']);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('96'));?></span>
                            <div class="i_sub_not_check_box">
                                <label class="el-switch el-switch-yellow">
                                    <input type="checkbox" name="askaquestion" class="subfeea" id="askaquestion" data-id="askaquestion" <?php echo iN_HelpSecure($productQuestionAnswer) != '' ? 'value="ok" checked="checked"' : 'value="not"';?>>
                                    <span class="el-switch-style"></span>
                                </label>
                            </div>
                            </div>
                            <div class="i_set_subscription_fee askaquestion <?php echo $askClass; ?>">
                                <div class="i_subs_price"><input type="text" id="question_ask" class="transition prc" placeholder="<?php echo iN_HelpSecure($LANG['ask_a_question_placeholder']);?>" value="<?php echo iN_HelpSecure($productQuestionAnswer);?>"></div>
                            </div>
                        </div>
                        <!--/SET SUBSCRIPTION FEE BOX-->
                    </div>
                    <!---->
                    <!---->
                    <div class="create_product_form_column padding_bottom_zero">
                        <!--SET SUBSCRIPTION FEE BOX-->
                        <div class="i_set_subscription_fee_box padding_zero">
                            <div class="i_sub_not_check qmark">
                            <?php echo iN_HelpSecure($LANG['limit_slots']);?><span class="flex_ tabing_non_justify ownTooltip" data-label="<?php echo iN_HelpSecure($LANG['limit_slots_desc']);?>"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('96'));?></span>
                            <div class="i_sub_not_check_box">
                                <label class="el-switch el-switch-yellow">
                                    <input type="checkbox" name="limitslots" class="subfeea" id="limitslots" data-id="limitslots" <?php echo iN_HelpSecure($productSlotsNumber) != '' ? 'value="ok" checked="checked"' : 'value="not"';?>>
                                    <span class="el-switch-style"></span>
                                </label>
                            </div>
                            </div>
                            <div class="i_set_subscription_fee limitslots <?php echo $slotClass; ?>">
                                <div class="i_subs_price"><input type="text" id="limit_slot" class="transition prc" placeholder="10" onkeypress='return event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)' value="<?php echo iN_HelpSecure($productSlotsNumber);?>"></div>
                            </div>
                        </div>
                        <!--/SET SUBSCRIPTION FEE BOX-->
                    </div>
                    <!---->
                    <div class="i_warning"><?php echo iN_HelpSecure($LANG['please_fill_in_all_informations']);?></div>
                    <div class="i_settings_wrapper_item successNot">
                        <?php echo iN_HelpSecure($LANG['product_ready_for_the_published'])?>
                    </div>
                    <!---->
                    <div class="create_product_form_column">
                        <div class="pr_save_btna"><?php echo iN_HelpSecure($LANG['save_edit']);?></div>
                    </div>
                    <!---->
                </div>
              <!--/Edit Product Columns-->
            </div>
        </div>
        <div class="i_become_creator_box_footer tabing">

        </div>
    </div>
</div>
<script>
window.editProduct = {
  productID: "<?php echo iN_HelpSecure($productID); ?>", 
  successTextSelector: ".successNot",
  warningTextSelector: ".i_warning"
};
</script>
<?php }?>