(function ($) {
  "use strict";

  $(document).ready(function () {
    const $form = $("#newAnnouncementForm");
    const $submitBtn = $form.find('button[type="submit"]');
    const $warning = $(".warning_wrapper");
    const $modalContainer = $("#general_conf");

    const loaderHTML = `
      <div class="loaderWrapper">
        <div class="loaderContainer">
          <div class="loader">
            <div class="i_loading product_page_loading">
              <div class="dot-pulse"></div>
            </div>
          </div>
        </div>
      </div>`;

    $form.on("submit", function (e) {
      e.preventDefault();

      $.ajax({
        type: "POST",
        url: siteurl + "request/request.php",
        data: $form.serialize(),
        beforeSend: function () {
          $warning.hide();
          $modalContainer.append(loaderHTML);
          $submitBtn.prop("disabled", true);
        },
        success: function (response) {
          setTimeout(() => {
            $submitBtn.prop("disabled", false);
          }, 3000);

          $(".loaderWrapper").remove();

          switch (response) {
            case "200":
              location.reload();
              break;
            case "2":
              $(".papk_wraning").show();
              break;
            case "1":
              $(".ppk_wraning").show();
              break;
            case "3":
              $(".warning_one").show();
              break;
            default:
              const msg = `
                <div class="nnauthority">
                  <div class="no_permis flex_ c3 border_one tabing">${response}</div>
                </div>`;
              $("body").append(msg);
              setTimeout(() => {
                $(".nnauthority").remove();
              }, 8000);
              break;
          }
        }
      });
    });
  });
})(jQuery);