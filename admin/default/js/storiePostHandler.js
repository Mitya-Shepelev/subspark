(function($) {
    "use strict";

    $(document).on("click", ".delps", function() {
        var type = 'delete_storie_alert';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;

        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {
                // Optional: loading animation
            },
            success: function(response) {
                $("body").append(response);
                setTimeout(function() {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });

    $(document).on("click", ".lightgallery", function(e) {
        e.preventDefault();
        var src = $(this).data("src");

        $(this).lightGallery({
            dynamic: true,
            dynamicEl: [{
                src: src,
                thumb: src
            }],
            videojs: true,
            mode: 'lg-fade',
            cssEasing : 'cubic-bezier(0.25, 0, 0.25, 1)',
            download: false,
            share: false
        }).data('lightGallery').openGallery();
    });

})(jQuery);