(function($) {
    "use strict";

    const preLoadingAnimation = '<div class="i_loading i_loading_margin"><div class="dot-pulse"></div></div>';
    const plreLoadingAnimationPlus = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';

    $(document).on("submit", "#deleteMyAccount", function(e) {
        e.preventDefault();

        var currentPassword = $("#crn_password").val();
        var deleteUserID = $('input[name="deleteMe"]').val();
        var data = 'f=deleteMyAccount&crn_password=' + encodeURIComponent(currentPassword) + '&deleteMe=' + deleteUserID;

        $(".warning_not_correct, .warning_write_current_password").hide();

        if (currentPassword == '') {
            $(".warning_write_current_password").show();
            return;
        }

        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {
                $("#deleteMyAccount").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    window.location.href = siteurl;
                } else if (response == '403') {
                    $(".warning_not_correct").show();
                } else if (response == '401') {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">You are not authorized to do this action.</div></div>');
                    setTimeout(() => { $(".nnauthority").remove(); }, 5000);
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => { $(".nnauthority").remove(); }, 5000);
                }

                $(".loaderWrapper").remove();
            }
        });
    });
})(jQuery);