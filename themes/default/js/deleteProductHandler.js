(function($) {
    "use strict";

    const preLoadingAnimation = '<div class="i_loading i_loading_margin"><div class="dot-pulse"></div></div>';
    const plreLoadingAnimationPlus = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';

    $(document).on("click", ".delmyprod", function() {
        const type = 'delete_product';
        const ID = $(this).attr("id");
        const data = 'f=' + type + '&id=' + ID;

        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() { },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
})(jQuery);