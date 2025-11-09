(function ($) {
    "use strict";

    $(document).ready(function () {
        const edQAForm = $('#edQAForm');
        const loadingHTML = `
            <div class="loaderWrapper">
                <div class="loaderContainer">
                    <div class="loader">
                        <div class="i_loading product_page_loading">
                            <div class="dot-pulse"></div>
                        </div>
                    </div>
                </div>
            </div>`;

        edQAForm.on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: siteurl + "request/request.php",
                data: edQAForm.serialize(),
                beforeSend: function () {
                    $('.warning_two').hide();
                    $('.general_conf').append(loadingHTML);
                    edQAForm.find(':input[type=submit]').prop('disabled', true);
                },
                success: function (data) {
                    setTimeout(() => {
                        edQAForm.find(':input[type=submit]').prop('disabled', false);
                    }, 3000);

                    if (data === '200') {
                        location.reload();
                    } else if (data === '1') {
                        $('.warning_two').show();
                    } else if (data === '2') {
                        $('.warning_two').show();
                    } else {
                        const errorMessage = `
                            <div class="nnauthority">
                                <div class="no_permis flex_ c3 border_one tabing">${data}</div>
                            </div>`;
                        $('body').append(errorMessage);
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