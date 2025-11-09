(function ($) {
    "use strict";

    $(document).ready(function () {
        const $form = $('#newSocialSiteForm');
        const loader = `
            <div class="loaderWrapper">
                <div class="loaderContainer">
                    <div class="loader">
                        <div class="i_loading product_page_loading">
                            <div class="dot-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>`;

        $form.on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: siteurl + 'request/request.php',
                data: $form.serialize(),
                beforeSend: function () {
                    $('.papk_wraning, .warning_one').hide();
                    $('.general_conf').append(loader);
                    $form.find(':input[type=submit]').prop('disabled', true);
                },
                success: function (response) {
                    setTimeout(() => {
                        $form.find(':input[type=submit]').prop('disabled', false);
                    }, 3000);

                    if (response === '200') {
                        $('.point_input').val('');
                        $('.svg_more_textarea').val('');
                        location.reload();
                    } else if (response === '1') {
                        $('.papk_wraning').show();
                    } else if (response === '2') {
                        $('.warning_one').show();
                    } else {
                        $('body').append(`<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">${response}</div></div>`);
                        setTimeout(() => { $('.nnauthority').remove(); }, 5000);
                    }

                    $('.loaderWrapper').remove();
                }
            });
        });
    });
})(jQuery);