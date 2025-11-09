(function ($) {
  "use strict";

  $(document).ready(function () {
    // Set background images from data-bg attributes
    $('[data-bg]').each(function () {
      var bg = $(this).data('bg');
      if (bg) {
        $(this).css('background-image', 'url(' + bg + ')');
      }
    });
  });

})(jQuery);