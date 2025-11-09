(function ($) {
    "use strict";

    $(document).ready(function () {
        const form = $('#stickerEdit');
        const container = $('#stickerEditContainer');

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
                    $('.warning_svg, .warning_format, .warning_empty').hide();
                    container.append(loaderHTML);
                    form.find(':input[type=submit]').prop('disabled', true);
                },
                success: function (data) {
                    setTimeout(() => {
                        form.find(':input[type=submit]').prop('disabled', false);
                    }, 3000);

                    if (data === '200') {
                        location.reload();
                    } else if (data === '1') {
                        $('.warning_svg').show();
                    } else if (data === '2') {
                        $('.warning_format').show();
                    } else if (data === '3') {
                        $('.warning_empty').show();
                    } else {
                        const errorMsg = `
                            <div class="nnauthority">
                                <div class="no_permis flex_ c3 border_one tabing">${data}</div>
                            </div>`;
                        $('body').append(errorMsg);
                        setTimeout(() => {
                            $('.nnauthority').remove();
                        }, 8000);
                    }

                    $('.loaderWrapper').remove();
                }
            });
        });
    });
})(jQuery);