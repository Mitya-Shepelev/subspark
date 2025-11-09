(function($) {
    "use strict";

    $(document).ready(function() {
        const form = $('#newBoostPackageForm');
        const loaderHTML = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader"><div class="i_loading product_page_loading"><div class="dot-pulse"></div></div></div></div></div>';

        form.on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: siteurl + 'request/request.php',
                data: form.serialize(),
                beforeSend: function() {
                    $('.ppk_wraning, .papk_wraning, .pk_wraning, .general_warning').hide();
                    $('.i_p_e_body').append(loaderHTML);
                    form.find(':input[type=submit]').prop('disabled', true);
                },
                success: function(data) {
                    setTimeout(() => {
                        form.find(':input[type=submit]').prop('disabled', false);
                    }, 3000);

                    switch (data) {
                        case '1':
                            $('.ppk_wraning').show();
                            break;
                        case '3':
                            $('.papk_wraning').show();
                            break;
                        case '4':
                            $('.pk_wraning').show();
                            break;
                        case '5':
                            $('.general_warning').show();
                            break;
                        case '200':
                            location.reload();
                            break;
                        default:
                            $('body').append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                            setTimeout(() => {
                                $('.nnauthority').remove();
                            }, 5000);
                    }

                    $('.loaderWrapper').remove();
                }
            });
        });
    });

})(jQuery);