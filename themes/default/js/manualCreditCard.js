(function ($) {
  "use strict";

  const siteurl = window.manualCardData?.siteurl || "";
  const planID = window.manualCardData?.planID || "";
  const userID = window.manualCardData?.userID || "";

  $("body").on("click", ".payClose", function () {
    $(".i_moda_bg_in_form").removeClass("i_modal_display_in");
  });

  function setError(msg) {
    if (!msg) {
      $("#paymentResponse").hide().html("");
    } else {
      $("#paymentResponse").show().html('<p>' + msg + '</p>');
    }
  }

  function validateEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value || "");
  }

  $("body").on("click", ".pay_subscription", function () {
    const preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
    const loaderHTML = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';
    $(".i_modal_in_in").append(loaderHTML);

    setTimeout(() => {
      setError("");
      const name = $("#cname").val()?.trim();
      const email = $("#email").val()?.trim();
      const cardNumber = $("#cardNumber").val()?.replace(/\s+/g, '');
      const expMonth = $("#expmonth").val()?.trim();
      const expYear = $("#expyear").val()?.trim();
      const cardCCV = $("#cvv").val()?.trim();

      // Basic validations
      if (!name || name.length < 2) { setError("Please enter the card holder name."); $(".loaderWrapper").remove(); return; }
      if (!validateEmail(email)) { setError("Please enter a valid email address."); $(".loaderWrapper").remove(); return; }
      if (!/^\d{13,19}$/.test(cardNumber)) { setError("Please enter a valid card number."); $(".loaderWrapper").remove(); return; }
      if (!/^(0?[1-9]|1[0-2])$/.test(expMonth)) { setError("Invalid expiry month."); $(".loaderWrapper").remove(); return; }
      if (!/^\d{2}$/.test(expYear)) { setError("Invalid expiry year."); $(".loaderWrapper").remove(); return; }
      if (!/^\d{3,4}$/.test(cardCCV)) { setError("Invalid CVC."); $(".loaderWrapper").remove(); return; }

      const data = `f=subscribeMeAut&u=${userID}&pl=${planID}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&card=${cardNumber}&exm=${expMonth}&exy=${expYear}&cccv=${cardCCV}`;

      $.ajax({
        type: "POST",
        url: siteurl + "requests/request.php",
        data: data,
        cache: false,
        success: function (response) {
          if (String(response).trim() === "200") {
            location.reload();
          } else {
            setError(response || "Payment failed.");
            $(".loaderWrapper").remove();
          }
        },
        error: function () {
          setError("Network error. Please try again.");
          $(".loaderWrapper").remove();
        }
      });
    }, 1200);
  });

})(jQuery);
