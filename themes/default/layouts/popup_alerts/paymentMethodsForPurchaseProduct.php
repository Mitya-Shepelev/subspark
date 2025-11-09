<div class="i_modal_bg_in">
    <!--SHARE-->
   <div class="i_modal_in_in i_sf_box">
       <div class="i_modal_content">
          <div class="purchase_premium_header flex_ tabing border_top_radius mp" data-p="<?php echo iN_HelpSecure($productID);?>"><?php echo iN_HelpSecure($LANG['choose_payment_method']);?></div>
          <div class="purchase_post_details tabing">
        <?php
        $methods = [
          'bitpay' => $bitPayPaymentStatus,
          'razorpay' => $razorPayPaymentStatus,
          'paypal' => $payPalPaymentStatus,
          'stripe' => $stripePaymentStatus,
          'paystack' => $payStackPaymentStatus,
          'iyzico' => $iyziCoPaymentStatus,
          'authorize-net' => $autHorizePaymentStatus,
          'coinpayment' => $coinPaymentStatus,
          'mercadopago' => $mercadoPagoPaymentStatus
        ];

        foreach ($methods as $id => $status) {
          if ($status == '1') {
            $class = in_array($id, ['iyzico', 'authorize-net']) ? 'paywith' : (in_array($id, ['coinpayment']) ? 'paywithCrip' : 'payMethod');
            echo '<div class="payment_method_box transition ' . $class . '" id="' . $id . '" data-type="' . $id . '"><div class="payment_method_item flex_ ' . $id . '"></div></div>';
          }
        } 
        ?>
      </div>
          <div class="i_modal_g_footer">
              <div class="alertBtnLeft no-del transition"><?php echo iN_HelpSecure($LANG['cancel']);?></div>
          </div>
       </div>
   </div>
   <!--/SHARE--> 
<script>
  // Define global variables for product payment handling
  window.siteurl = "<?php echo iN_HelpSecure($base_url); ?>";
  window.productID = "<?php echo iN_HelpSecure($productID); ?>";
  window.userData = <?php echo json_encode($DataUserDetails); ?>;
  window.configData = <?php echo json_encode($PublicConfigs); ?>;
  window.paymentPagePath = <?php echo json_encode($paymentPagePath); ?>;
  window.stripeTestKey = "<?php echo $stripePaymentTestPublicKey; ?>";
  window.stripeLiveKey = "<?php echo $stripePaymentLivePublicKey; ?>";
  window.authorizeNetCallbackUrl = <?php echo json_encode($authorizeNetCallbackUrl); ?>;
  window.razorpayCallbackUrl = <?php echo json_encode($razorpayCallbackUrl); ?>;
</script>
<script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/productPaymentHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>
<?php
// Conditionally load payment gateway SDKs if active
if (iN_HelpSecure($stripePaymentStatus) == 1) {
    echo '<script src="https://js.stripe.com/v3"></script>';
}
if (iN_HelpSecure($razorPayPaymentStatus) == 1) {
    echo '<script src="https://checkout.razorpay.com/v1/checkout.js"></script>';
}
if (iN_HelpSecure($payStackPaymentStatus) == 1) {
    echo '<script src="https://js.paystack.co/v1/inline.js"></script>';
}
?>
</div>
<!--CREDIT CARD FORM-->
<div class="i_moda_bg_in_form i_subs_modal fixed_zindex">
   <div class="i_modal_in_in i_payment_pop_box">
       <div class="i_modal_content">
           <div class="payClose transition"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5'));?></div>
           <!---->
           <div class="point_purchase_not flex_"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')).' '.$iN->iN_TextReaplacement($LANG['point_buy_not'],[$planPoint, $planAmount]);?></div>
           <!---->
           <!---->
           <form id="paymentFrm">
           <div class="i_credit_card_form">
                <div id="paymentResponse"></div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['card_holder']);?></label>
                    <div class="form-control">
                        <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('70'));?></div>
                       <input type="text" id="cname" name="cardname" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['card_holder']);?>">
                    </div>
                </div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['email']);?></label>
                    <div class="form-control">
                       <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('71'));?></div>
                       <input type="text" id="email" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['email']);?>">
                    </div>
                </div>
                <div class="pay_form_group">
                    <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['card_number']);?></label>
                    <div class="form-control">
                       <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('72'));?></div>
                       <input type="text" id="cardNumber" name="cardnumber" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['card_number']);?>">
                    </div>
                </div>
                <div class="pay_form_group_plus">
                    <div class="i_form_group_plus_extra">
                        <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['expiration_date']);?></label>
                        <div class="form-control">
                            <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73'));?></div>
                            <input type="text" id="expmonth" name="expmonth" class="inora_user_input" placeholder="DD">
                        </div>
                    </div>
                    <div class="i_form_group_plus_extra">
                        <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['expiration_year']);?></label>
                        <div class="form-control">
                            <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73'));?></div>
                            <input type="text" id="expyear" name="expyear" class="inora_user_input" placeholder="YY">
                        </div>
                    </div>
                    <div class="i_form_group_plus_extra">
                        <label for="i_nora_username" class="form_label"><?php echo iN_HelpSecure($LANG['ccv_code']);?></label>
                        <div class="form-control">
                            <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('74'));?></div>
                            <input type="text" id="cvv" name="cvv" class="inora_user_input" placeholder="123">
                        </div>
                    </div>
                </div>
                <div class="pay_form_group">
                    <div class="pay_subscription transition point_purchase payMethod" data-type="iyzico"><?php echo iN_HelpSecure($LANG['pay']);?> <?php echo iN_HelpSecure($currencys[$stripeCurrency]).$planAmount;?></div>
                </div>
                <div class="pay_form_group">
                   <div class="i_pay_note">
                       <?php echo iN_HelpSecure($LANG['you_can_use_instantly']);?>
                   </div>
                </div>
           </div>
           </form>
           <!---->
       </div>
   </div>
<!--/CREDIT CARD FORM-->