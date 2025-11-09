(function ($) {
  "use strict";

  $(document).ready(function () {
    $(".theaImage").each(function () {
      const bg = $(this).data("bg");
      if (bg) {
        $(this).css("background-image", "url(" + bg + ")");
      }
    });
  });
})(jQuery);