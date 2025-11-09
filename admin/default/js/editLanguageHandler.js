(function ($) {
    "use strict";

    $(document).ready(function () {
        const editLanguage = $('#editLanguage');
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

        editLanguage.on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: siteurl + "request/request.php",
                data: editLanguage.serialize(),
                beforeSend: function () {
                    $('.ppk_warning, .general_warning, .general_warning_lang').hide();
                    $('.i_p_e_body').append(loadingHTML);
                    editLanguage.find(':input[type=submit]').prop('disabled', true);
                },
                success: function (data) {
                    setTimeout(() => {
                        editLanguage.find(':input[type=submit]').prop('disabled', false);
                    }, 3000);

                    if (data === '1') {
                        $('.ppk_wraning').show();
                    } else if (data === '2') {
                        $('.general_warning').show();
                    } else if (data === '3') {
                        $('.general_warning_lang').show();
                    } else if (data === '200') {
                        location.reload();
                    }

                    $('.loaderWrapper').remove();
                }
            });
        });
    });
})(jQuery);