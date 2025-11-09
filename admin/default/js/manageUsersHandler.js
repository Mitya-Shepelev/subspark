(function($) {
    "use strict";

    $(document).on("click", ".search_vl", function() {
        var value = $("#srcMe").val().trim();
        if (value !== '') {
            var baseUrl = window.appBaseUrl || '';
            var url = baseUrl + "admin/manage_users?page-id=1&sr=" + encodeURIComponent(value);
            window.location.href = url;
        }
    });

})(jQuery);