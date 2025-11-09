(function($){
  "use strict";
  $(document).ready(function(){
    $(".dynamic-bar").each(function () {
      const w = $(this).data("width");
      if (w) {
        $(this).css("width", w);
      }
    });
  });
})(jQuery);