(function ($) {
  "use strict";

  // Loader animation for visual feedback during async operations
  const preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
  const loaderWrapper = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';

  $(document).ready(function () {

    /**
     * Handle click event for iyzico and authorize.net methods.
     * Opens the credit card modal window.
     */
    $("body").on("click", ".paywith", function () {
      const type = $(this).data("type");
      if (type === "iyzico" || type === "authorize-net") {
        $(".point_purchase").attr("data-type", type);
      }
      setTimeout(() => {
        $(".i_moda_bg_in_form").addClass("i_modal_display_in");
      }, 200);
    });

    /**
     * Close credit card modal window
     */
    $("body").on("click", ".payClose", function () {
      $(".i_moda_bg_in_form").removeClass("i_modal_display_in");
    });

    /**
     * Payment method selector handler
     * Handles logic for various payment gateways like PayPal, Stripe, Razorpay, etc.
     */
    $("body").on("click", ".payMethod", function (e) {
      e.preventDefault();
    
      const payWidth = $(this).data("type");
      const planID = window.planID;
      const type = "process";
      const configData = window.configData;
      const configItem = configData.payments.gateway_configuration[payWidth] || {};
      const userDetails = window.userData;
    
      // Display loading animation and disable method buttons
      $(`#${payWidth}`).append(loaderWrapper);
      $(".payment_method_box").css("pointer-events", "none");
    
      switch (payWidth) {
        case "paypal":
        case "iyzico":
        case "authorize-net":
        case "bitpay":
        case "mercadopago": {
          const requestData = `f=${type}&paymentOption=${payWidth}&creditPlan=${planID}&` + $("#paymentFrm").serialize() + "&" + $.param(userDetails);
    
          $.ajax({
            type: "POST",
            url: window.siteurl + "requests/request.php",
            dataType: "JSON",
            data: requestData,
            success: function (response) {
              $(".payment_method_box").css("pointer-events", "auto");
              $(".loaderWrapper").remove();
    
              if (response.validationMessage) {
                $.each(response.validationMessage, function (_, message) { });
              }
    
              switch (payWidth) {
                case "paypal":
                  $(".lw-show-till-loading").show();
                  window.location.href = response.paypalUrl;
                  break;
                case "bitpay":
                  if (response.status === "success") {
                    window.location.href = response.invoiceUrl;
                  } 
                  break;
                case "iyzico":
                  if (response.status === "success") {
                    $("body").html(response.htmlContent);
                  } 
                  break;
                case "authorize-net":
                  if (response.status === "success") {
                    $("body").html(
                      `<form action='${window.authorizeNetCallbackUrl}' method='post'>
                        <input type='hidden' name='response' value='${JSON.stringify(response)}'>
                        <input type='hidden' name='paymentOption' value='authorize-net'>
                      </form>`
                    );
                    $("body form").submit();
                  } 
                  break;
                case "mercadopago":
                  if (response.status === "success") {
                    window.location.href = response.redirect_url;
                  } else {
                    $(".lw-show-till-loading").hide(); 
                  }
                  break;
              }
            }
          });
          break;
        }
    
        case "stripe": {
          $(".payment_method_box").css("pointer-events", "auto");
          $(".loaderWrapper").remove();
    
          const stripeKey = configItem.testMode === 'true' ? window.stripeTestKey : window.stripeLiveKey;
          const stripe = Stripe(stripeKey);
          userDetails.paymentOption = payWidth;
          userDetails.f = type;
          userDetails.creditPlan = planID;
    
          $.ajax({
            type: "POST",
            url: window.siteurl + "requests/request.php",
            dataType: "JSON",
            data: userDetails,
            success: function (response) {
              stripe.redirectToCheckout({ sessionId: response.id }).then(function (result) { });
            }
          });
          break;
        }
    
        case "paystack": {
          $(".payment_method_box").css("pointer-events", "auto");
          $(".loaderWrapper").remove();
    
          const amount = userDetails.amounts[configItem.currency];
          const paystackPublicKey = configItem.testMode ? configItem.paystackTestingPublicKey : configItem.paystackLivePublicKey;
          const paystackAmount = amount * 100;
    
          userDetails.paymentOption = payWidth;
          userDetails.f = type;
          userDetails.creditPlan = planID;
    
          const handler = PaystackPop.setup({
            key: paystackPublicKey,
            email: userDetails.payer_email,
            amount: paystackAmount,
            currency: configItem.currency,
            callback: function (response) {
              $(".lw-show-till-loading").show();
    
              const paystackData = {
                paystackReferenceId: response.reference,
                paystackAmount: paystackAmount
              };
    
              const requestData = $('#lwPaymentForm').serialize() + '&' + $.param(userDetails) + '&' + $.param(paystackData);
    
              $.ajax({
                type: "POST",
                url: window.siteurl + "requests/request.php",
                dataType: "JSON",
                data: requestData,
                success: function (response) {
                  if (response.status === true) {
                    const callbackUrl = configItem.callbackUrl + '?orderId=' + userDetails.order_id + '&paymentOption=' + payWidth;
                    $("body").html(
                      `<form action='${callbackUrl}' method='post'>
                        <input type='hidden' name='response' value='${JSON.stringify(response)}'>
                        <input type='hidden' name='paymentOption' value='paystack'>
                      </form>`
                    );
                    $("body form").submit();
                  }
                }
              });
            },
            onClose: function () {
              window.location.href = window.paymentPagePath;
            }
          });
    
          handler.openIframe();
          break;
        }
    
        case "razorpay": {
          $(".payment_method_box").css("pointer-events", "auto");
          $(".loaderWrapper").remove();
    
          const amount = userDetails.amounts[configItem.currency];
          const razorpayAmount = amount * 100;
          const razorpayKeyId = configItem.testMode ? configItem.razorpayTestingkeyId : configItem.razorpayLivekeyId;
    
          userDetails.paymentOption = payWidth;
          userDetails.f = type;
          userDetails.creditPlan = planID;
    
          const options = {
            key: razorpayKeyId,
            amount: razorpayAmount,
            currency: configItem.currency,
            name: configItem.merchantname,
            handler: function (response) {
              const razorpayData = {
                razorpayPaymentId: response.razorpay_payment_id,
                razorpayAmount: window.btoa(razorpayAmount)
              };
    
              const requestData = $('#lwPaymentForm').serialize() + '&' + $.param(userDetails) + '&' + $.param(razorpayData);
    
              $.ajax({
                type: "POST",
                url: window.siteurl + "requests/request.php",
                dataType: "JSON",
                data: requestData,
                success: function (response) {
                  if (response.status === "captured") {
                    const callbackUrl = configItem.callbackUrl + '?orderId=' + userDetails.order_id + '&paymentOption=' + payWidth;
                    $("body").html(
                      `<form action='${callbackUrl}' method='post'>
                        <input type='hidden' name='response' value='${JSON.stringify(response)}'>
                        <input type='hidden' name='paymentOption' value='razorpay'>
                      </form>`
                    );
                    $("body form").submit();
                  }
                }
              });
            },
            prefill: {
              name: userDetails.payer_name,
              email: userDetails.payer_email
            },
            theme: {
              color: configItem.themeColor
            },
            modal: {
              ondismiss: function () {
                window.location.href = window.paymentPagePath;
              }
            }
          };
    
          const rzp1 = new Razorpay(options);
          rzp1.open();
          break;
        }
    
        default:
          break;
      }
    });

    /**
     * CoinPayment Gateway (crypto-based) - redirection via JSON response
     */
    $("body").on("click", ".paywithCrip", function () {
      const planID = window.planID;
      const payWidth = $(this).data("type");
      const data = `f=cop&p=${planID}`;

      $.ajax({
        type: "POST",
        url: window.siteurl + "requests/request.php",
        dataType: "JSON",
        data: data,
        beforeSend: function () {
          $(`#${payWidth}`).append(loaderWrapper);
          $(".payment_method_box").css("pointer-events", "none");
        },
        success: function (response) {
          const redirect = response.redirect;
          const status = response.status;
          if (redirect && status === '200') {
            window.location.href = redirect;
          } 
        }
      });
    });

  });
})(jQuery);