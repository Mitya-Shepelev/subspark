(function($) {
    "use strict";

    const preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
    const loadingWrapper = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';

    $(document).on('submit', '#generateFakeUser', function(e) {
        e.preventDefault();
        const $form = $('#generateFakeUser');

        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: $form.serialize(),
            beforeSend: function() {
                $('.successNot, .warning_').hide();
                $('#general_conf').append(loadingWrapper);
                $form.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(response) {
                // Re-enable the submit button after delay
                setTimeout(function() {
                    $form.find(':input[type=submit]').prop('disabled', false);
                }, 3000);

                // Handle response
                switch (response) {
                    case '200':
                        $('.successNot').show();
                        break;
                    case '404':
                        $('.warning_').show();
                        break;
                    default:
                        // Show generic error message box
                        const safeText = $('<div>').text(response).html();
                        $('body').append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + safeText + '</div></div>');
                        setTimeout(function() {
                            $('.nnauthority').remove();
                        }, 5000);
                }

                $('.loaderWrapper').remove();
            }
        });
    });
})(jQuery);