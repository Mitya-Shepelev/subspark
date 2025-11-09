(function ($) {
  "use strict";
 
  $(document).ready(function () {
    $(".a-item-img_live_gift").on("load", function () {
      const loadingDiv = $(this).parent().find(".loading-div");
      if (loadingDiv.length) {
        loadingDiv.hide();
      }
    }).each(function () { 
      if (this.complete) {
        $(this).trigger("load");
      }
    });
  });

})(jQuery);