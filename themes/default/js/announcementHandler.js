(function($) {
    "use strict";

    $(document).on("click", ".gotit", function() {
        var ID = $(".announcement_not").data("announcement-id");

        if (!ID) {
            return;
        }

        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: {
                f: 'gotAnnouncement',
                aid: ID
            },
            cache: false,
            success: function(response) {
                if (String(response).trim() === '200') {
                    $(".announcement_container").remove();
                }
            }
        });
    });

})(jQuery);
