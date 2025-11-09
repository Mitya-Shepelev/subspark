(function ($) {
  "use strict";

  const stripePublicKey = window.payWithCardData?.stripePublicKey || "";
  const siteurl = window.payWithCardData?.siteurl || "";
  const planID = window.payWithCardData?.planID || "";
  const userID = window.payWithCardData?.userID || "";
  const theme = window.payWithCardData?.lightDark || "light";

  const stripe = Stripe(stripePublicKey);
  const elements = stripe.elements();

  const style = theme === "dark" ? {
    base: {
      color: "#ffffff"
    }
  } : {};

  const cardElement = elements.create("cardNumber", { style });
  const expElement = elements.create("cardExpiry", { style });
  const cvcElement = elements.create("cardCvc", { style });

  cardElement.mount("#card_number");
  expElement.mount("#card_expiry");
  cvcElement.mount("#card_cvc");

  const resultContainer = document.getElementById("paymentResponse");

  let cardComplete = false, expComplete = false, cvcComplete = false;

  function setError(msg) {
    if (!resultContainer) return;
    if (!msg) {
      resultContainer.innerHTML = "";
      resultContainer.style.display = "none";
    } else {
      resultContainer.innerHTML = '<p>' + msg + '</p>';
      resultContainer.style.display = "block";
    }
  }

  function validateEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value || "");
  }

  function updatePayButtonState() {
    const btn = document.querySelector('.pay_subscription');
    const name = document.getElementById('name')?.value?.trim();
    const email = document.getElementById('email')?.value?.trim();
    const valid = Boolean(name && name.length >= 2 && validateEmail(email) && cardComplete && expComplete && cvcComplete);
    if (btn) {
      btn.disabled = !valid;
      btn.classList.toggle('disabled', !valid);
    }
  }

  cardElement.on("change", function (event) {
    cardComplete = !!event.complete;
    setError(event.error ? event.error.message : "");
    updatePayButtonState();
  });
  expElement.on("change", function (event) {
    expComplete = !!event.complete;
    setError(event.error ? event.error.message : "");
    updatePayButtonState();
  });
  cvcElement.on("change", function (event) {
    cvcComplete = !!event.complete;
    setError(event.error ? event.error.message : "");
    updatePayButtonState();
  });

  const form = document.getElementById("paymentFrm");

  async function createToken() {
    const { token, error } = await stripe.createToken(cardElement, {
      name: document.getElementById('name')?.value?.trim(),
    });
    if (error) {
      setError(error.message);
      return null;
    }
    return token;
  }

  function stripeTokenHandler(token) {
    $("#stripeTokenID").remove();
    const hiddenInput = document.createElement("input");
    hiddenInput.setAttribute("type", "hidden");
    hiddenInput.setAttribute("name", "stripeToken");
    hiddenInput.setAttribute("id", "stripeTokenID");
    hiddenInput.setAttribute("value", token.id);
    form.appendChild(hiddenInput);
  }

  $("body").on("click", ".pay_subscription", async function () {
    const btn = this;
    if (btn.disabled) return;
    setError("");

    // Basic input validation
    const name = $("#name").val()?.trim();
    const email = $("#email").val()?.trim();
    if (!name || name.length < 2) {
      return setError("Please enter the card holder name.");
    }
    if (!validateEmail(email)) {
      return setError("Please enter a valid email address.");
    }
    if (!(cardComplete && expComplete && cvcComplete)) {
      return setError("Please complete your card details.");
    }

    // UI: lock button and show loader
    const originalLabel = btn.textContent;
    const processingLabel = btn.dataset.labelProcessing || "Processing...";
    btn.disabled = true;
    btn.textContent = processingLabel;
    const preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
    const loaderHTML = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';
    $(".i_modal_in_in").append(loaderHTML);

    try {
      const token = await createToken();
      if (!token) {
        throw new Error("Token creation failed");
      }

      // Attach hidden input and fire request
      stripeTokenHandler(token);
      const tokenId = $("#stripeTokenID").val();
      const data = `f=subscribeMe&u=${userID}&pl=${planID}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}&t=${encodeURIComponent(tokenId)}`;

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
            btn.disabled = false;
            btn.textContent = originalLabel;
          }
        },
        error: function () {
          setError("Network error. Please try again.");
          $(".loaderWrapper").remove();
          btn.disabled = false;
          btn.textContent = originalLabel;
        }
      });
    } catch (err) {
      setError(err?.message || "Unexpected error.");
      $(".loaderWrapper").remove();
      btn.disabled = false;
      btn.textContent = originalLabel;
    }
  });

  $("body").on("click", ".payClose", function () {
    $(".i_payment_pop_box").addClass("i_modal_in_in_out");
    setTimeout(() => {
      $(".i_subs_modal").remove();
      $("iframe").remove();
      $("strong").remove();
    }, 200);
  });

  // Initialize button state
  updatePayButtonState();
  $(document).on('input', '#name,#email', updatePayButtonState);

})(jQuery);
