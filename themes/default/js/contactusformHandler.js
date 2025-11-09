(function ($) {
  "use strict";
  $(document).ready(function () {
    const preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
    const fullLoader = `<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">${preLoadingAnimation}</div></div></div>`;
    const newContact = $("#newContact");

    newContact.on("submit", function (e) {
      e.preventDefault();

      $.ajax({
        type: "POST",
        url: siteurl + "requests/contact.php",
        data: newContact.serialize(),
        beforeSend: function () {
          $("#general_conf").append(fullLoader);
          newContact.find(":input[type=submit]").prop("disabled", true);
        },
        success: function (data) {
          setTimeout(function () {
            newContact.find(":input[type=submit]").prop("disabled", false);
          }, 3000);

          $(".loaderWrapper").remove();

          switch (data) {
            case "200":
              $("#con_for").remove();
              $(".sended").show();
              newContact.trigger("reset");
              break;
            case "1":
              $("#con_for").remove();
              $(".con_warning").show();
              break;
            case "2":
              $("#contact_email").focus();
              break;
            case "404":
              $(".contact_disabled").show();
              break;
            case "405":
              $(".con_warning_rec").show();
              break;
            default:
              $(".contact_disabled").show();
              break;
          }
        },
        error: function (xhr, status, error) {
          $(".loaderWrapper").remove();
          newContact.find(":input[type=submit]").prop("disabled", false); 
        }
      });
    });
  });
})(jQuery);