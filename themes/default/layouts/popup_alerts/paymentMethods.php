<div class="i_modal_bg_in">
  <!-- Payment Modal -->
  <div class="i_modal_in_in i_sf_box">
    <div class="i_modal_content">
      <div class="purchase_premium_header flex_ tabing border_top_radius mp" data-p="<?php echo iN_HelpSecure($planID); ?>">
        <?php echo iN_HelpSecure($LANG['choose_payment_method']); ?>
      </div>

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

        if ($bankPaymentStatus == '1') {
          echo '<div class="payment_method_box transition"><div class="payment_method_item flex_ bankaccount bankOpen"></div></div>';
        }
        ?>
      </div>

      <div class="i_modal_g_footer">
        <div class="alertBtnLeft no-del transition">
          <?php echo iN_HelpSecure($LANG['cancel']); ?>
        </div>
      </div>

      <!-- Bank Payment Section -->
      <div class="payment_success_bank flex_ tabing">
        <?php echo iN_HelpSecure($LANG['bank_payment_request_sended']); ?>
      </div>

      <div class="bank_container displayNone">
        <div class="purchase_premium_header flex_ tabing border_top_radius mp">
          <?php echo iN_HelpSecure($LANG['make_payment_directly_bank']); ?>
        </div>
        <div class="purchase_post_details tabing">
          <div class="purchase_not_">Transaction fee %2.0</div>
          <div class="purchase_sw_details">
            <?php echo $iN->sanitize_output($bankPaymentDetails, $base_url); ?>
          </div>

          <form id="pBUploadForm" class="options-form" method="post" enctype="multipart/form-data" action="<?php echo iN_HelpSecure($base_url); ?>requests/request.php">
            <div class="certification_file_form" id="sec_one">
              <div class="certification_file_box">
                <label for="id_card">
                  <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('79')) . iN_HelpSecure($LANG['upload_payment_recoded']); ?>
                  <input type="file" id="id_card" name="uploading[]" data-id="uploadPaymentSuccessImage" data-type="sec_one" class="editAds_file">
                </label>
              </div>
              <div class="certificate_file_box_not">
                <?php echo iN_HelpSecure($LANG['upload_screenshot_make_sure_visible']); ?>
              </div>
              <div class="certificate_uploaded_file f_sec_one"></div>
            </div>
          </form>

          <div class="certification_file_form">
            <input type="hidden" id="uploadVal_sec_one">
          </div>

          <div class="purchase_not_">
            <?php echo iN_HelpSecure($LANG['wait_for_approve_']); ?>
          </div>
        </div>

        <div class="i_become_creator_box_footer">
          <div class="i_canc_btn bankOpen transition">
            <?php echo iN_HelpSecure($LANG['cancel']); ?>
          </div>
          <div class="i_nex_btn bnk_Next transition" id="<?php echo iN_HelpSecure($planID); ?>">
            <?php echo iN_HelpSecure($LANG['send']); ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Global JS config -->
  <script>
    window.siteurl = "<?php echo iN_HelpSecure($base_url); ?>";
    window.planID = "<?php echo iN_HelpSecure($planID); ?>";
    window.userData = <?php echo json_encode($DataUserDetails); ?>;
    window.configData = <?php echo json_encode($PublicConfigs); ?>;
    window.paymentPagePath = <?php echo json_encode($paymentPagePath); ?>;
    window.stripeTestKey = "<?php echo $stripePaymentTestPublicKey; ?>";
    window.stripeLiveKey = "<?php echo $stripePaymentLivePublicKey; ?>";
    window.authorizeNetCallbackUrl = <?php echo json_encode($authorizeNetCallbackUrl); ?>;
    window.razorpayCallbackUrl = <?php echo json_encode($razorpayCallbackUrl); ?>;
  </script>

  <script src="<?php echo iN_HelpSecure($base_url); ?>themes/<?php echo iN_HelpSecure($currentTheme); ?>/js/paymentMethodHandler.js?v=<?php echo iN_HelpSecure($version); ?>"></script>

  <?php
  echo iN_HelpSecure($stripePaymentStatus) == 1 ? '<script src="https://js.stripe.com/v3"></script>' : "";
  echo iN_HelpSecure($razorPayPaymentStatus) == 1 ? '<script src="https://checkout.razorpay.com/v1/checkout.js"></script>' : "";
  echo iN_HelpSecure($payStackPaymentStatus) == 1 ? '<script src="https://js.paystack.co/v1/inline.js"></script>' : "";
  ?>
</div>

<!-- CREDIT CARD FORM -->
<div class="i_moda_bg_in_form i_subs_modal fixed_zindex">
  <div class="i_modal_in_in i_payment_pop_box">
    <div class="i_modal_content">
      <div class="payClose transition">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('5')); ?>
      </div>

      <div class="point_purchase_not flex_">
        <?php echo html_entity_decode($iN->iN_SelectedMenuIcon('40')) . ' ' . $iN->iN_TextReaplacement($LANG['point_buy_not'], [$planPoint, $planAmount]); ?>
      </div>

      <form id="paymentFrm">
        <div class="i_credit_card_form">
          <div id="paymentResponse"></div>

          <div class="pay_form_group">
            <label for="cname" class="form_label"><?php echo iN_HelpSecure($LANG['card_holder']); ?></label>
            <div class="form-control">
              <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('70')); ?></div>
              <input type="text" id="cname" name="cardname" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['card_holder']); ?>">
            </div>
          </div>

          <div class="pay_form_group">
            <label for="email" class="form_label"><?php echo iN_HelpSecure($LANG['email']); ?></label>
            <div class="form-control">
              <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('71')); ?></div>
              <input type="text" id="email" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['email']); ?>">
            </div>
          </div>

          <div class="pay_form_group">
            <label for="cardNumber" class="form_label"><?php echo iN_HelpSecure($LANG['card_number']); ?></label>
            <div class="form-control">
              <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('72')); ?></div>
              <input type="text" id="cardNumber" name="cardnumber" class="inora_user_input" placeholder="<?php echo iN_HelpSecure($LANG['card_number']); ?>">
            </div>
          </div>

          <div class="pay_form_group_plus">
            <div class="i_form_group_plus_extra">
              <label class="form_label"><?php echo iN_HelpSecure($LANG['expiration_date']); ?></label>
              <div class="form-control">
                <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73')); ?></div>
                <input type="text" id="expmonth" name="expmonth" class="inora_user_input" placeholder="DD">
              </div>
            </div>

            <div class="i_form_group_plus_extra">
              <label class="form_label"><?php echo iN_HelpSecure($LANG['expiration_year']); ?></label>
              <div class="form-control">
                <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('73')); ?></div>
                <input type="text" id="expyear" name="expyear" class="inora_user_input" placeholder="YY">
              </div>
            </div>

            <div class="i_form_group_plus_extra">
              <label class="form_label"><?php echo iN_HelpSecure($LANG['ccv_code']); ?></label>
              <div class="form-control">
                <div class="form_control_icon"><?php echo html_entity_decode($iN->iN_SelectedMenuIcon('74')); ?></div>
                <input type="text" id="cvv" name="cvv" class="inora_user_input" placeholder="123">
              </div>
            </div>
          </div>

          <div class="pay_form_group">
            <div class="pay_subscription transition point_purchase payMethod" data-type="iyzico">
              <?php echo iN_HelpSecure($LANG['pay']); ?> <?php echo iN_HelpSecure($currencys[$stripeCurrency]) . $planAmount; ?>
            </div>
          </div>

          <div class="pay_form_group">
            <div class="i_pay_note">
              <?php echo iN_HelpSecure($LANG['you_can_use_instantly']); ?>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>