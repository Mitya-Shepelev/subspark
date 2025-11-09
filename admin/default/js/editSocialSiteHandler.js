(function ($) {
    "use strict";

    $(document).ready(function () {
        const form = $('#newSocialSiteForm');
        const loaderHTML = `
            <div class="loaderWrapper">
                <div class="loaderContainer">
                    <div class="loader">
                        <div class="i_loading product_page_loading">
                            <div class="dot-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>`;

        form.on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: siteurl + "request/request.php",
                data: form.serialize(),
                beforeSend: function () {
                    $('.papk_wraning, .warning_one').hide();
                    $('.general_conf').append(loaderHTML);
                    form.find(':input[type=submit]').prop('disabled', true);
                },
                success: function (data) {
                    setTimeout(() => {
                        form.find(':input[type=submit]').prop('disabled', false);
                    }, 3000);

                    if (data === '200') {
                        location.reload();
                    } else if (data === '1') {
                        $('.papk_wraning').show();
                    } else if (data === '2') {
                        $('.warning_one').show();
                    } else {
                        const errorMsg = `
                            <div class="nnauthority">
                                <div class="no_permis flex_ c3 border_one tabing">${data}</div>
                            </div>`;
                        $('body').append(errorMsg);
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