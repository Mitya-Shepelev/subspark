(function($){
  "use strict";
  $(document).ready(function(){
    $(".dynamic-bg").each(function () {
      const bg = $(this).data("img");
      if (bg) {
        $(this).css("background-image", "url(" + bg + ")");
      }
    });
  });
})(jQuery);