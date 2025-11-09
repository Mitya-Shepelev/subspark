(function ($) {
  "use strict";

  $(document).ready(function () {
    // Remove unnecessary UI elements
    $(".header, .sound-controls, .mobile_footer_fixed_menu_container").remove();

    // Initialize verifyLang if it's defined via <script id="verify-lang-data">
    const langDataEl = document.getElementById("verify-lang-data");
    if (langDataEl) {
      try {
        window.verifyLang = JSON.parse(langDataEl.textContent);
      } catch (e) { 
        window.verifyLang = {};
      }
    }

    // Click handler for resend confirmation
    $("body").on("click", ".sendmeagainconfirm", function () {
      $.ajax({
        type: "POST",
        url: siteurl + "requests/request.php",
        data: { f: "sndAgCon" },
        cache: false,
        beforeSend: function () {
          $(".sendmeagainconfirm").css("pointer-events", "none");
        },
        success: function (response) {
          if (response === "8") {
            showAlert(window.verifyLang.check_email_address, "success");
          } else {
            showAlert(window.verifyLang.confirmation_email_error, "error");
          }

          $(".sendmeagainconfirm").css("pointer-events", "auto");
        }
      });
    });

    // Custom alert display function
    function showAlert(message, type) {
      const alertBox = $("<div>").addClass("verify-alert " + type).text(message);
      $(".i_not_found_page").prepend(alertBox);
      setTimeout(() => alertBox.fadeOut(400, () => alertBox.remove()), 4000);
    }
  });
})(jQuery);