(function ($) {
  "use strict";

  $(document).ready(function () {
    $(".st_img_wrapper").each(function () {
      const $this = $(this);
      const bg = $this.data("bg");

      if (bg) {
        const img = new Image();
        img.onload = function () {
          $this.css("background-image", `url("${bg}")`);
          $this.find(".loader").fadeOut(200, function () {
            $(this).remove(); 
          });
        };
        img.onerror = function () { 
          $this.find(".loader").remove(); 
        };
        img.src = bg;
      }
    });
  });

  $(document).on("keyup", ".strt_typing", function () {
    const theText = $(this).val();
    $(".text_typed").text(theText);
  });

  $(document).on("click", ".st_img_wrapper", function () {
    const bgUrl = $(this).data("img");
    $(".st_img_wrapper").removeClass("choosed_bg");
    $(this).addClass("choosed_bg");
    $("#theBg").attr("src", bgUrl);
  });

})(jQuery);