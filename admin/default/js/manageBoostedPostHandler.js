(function($) {
  "use strict";

  $(document).on("click", ".search_vl", function() {
    const value = $("#srcMe").val();
    if (value) {
      const url = window.location.origin + '/admin/manage_products?page-id=1&sr=' + encodeURIComponent(value);
      window.location.href = url;
    }
  });

})(jQuery);