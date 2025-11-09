(function($) {
    "use strict";

    var preLoadingAnimation = '<div class="i_loading"><div class="dot-pulse"></div></div>';
    var plreLoadingAnimationPlus = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';
    var scrollLoad = true;

    $(document).on('touchmove', showMoreProduct); // For mobile
    $(window).on('scroll', showMoreProduct);

    function showMoreProduct() {
        const moreType = document.getElementById("moreTypeContainer")?.getAttribute("data-moretype") || '';
        var profileUserID = '';
        var ID = $('#moreType').children('.mor').last().attr('data-last');
        if ($('.i_loading , .nomore , .nmr , .no_creator_f_wrap').length === 0 && !$(".i_loading , .nomore , .nmr , .no_creator_f_wrap")[0] && moreType != undefined) {
            var data = 'f=mrProduct' + '&last=' + ID + '&ty=' + moreType;
            $.ajax({
                type: "POST",
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {
                    $(".body_" + ID).after(preLoadingAnimation);
                    scrollLoad = false;
                },
                success: function(response) {
                    if (response && !$(".nomore")[0]) {
                        $("#moreType").append(response);
                        scrollLoad = true;
                    }
                    setTimeout(() => {
                        $(".i_loading").remove();
                    }, 1000);
                }
            });
        }
    }

    $(document).on("click", ".settings_mobile_menu_container", function() {
        if (!$(".settingsMenuDisplay")[0]) {
            $(".i_shopping_menu_wrapper").addClass("settingsMenuDisplay");
        } else {
            $(".i_shopping_menu_wrapper").removeClass("settingsMenuDisplay");
        }
    });

})(jQuery);