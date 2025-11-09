(function ($) {
    "use strict";

    $(document).ready(function () {
        const addNewLanguage = $("#addNewLanguage");
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

        addNewLanguage.on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: siteurl + "request/request.php",
                data: addNewLanguage.serialize(),
                beforeSend: function () {
                    $(".ppk_wraning, .general_warning, .general_warning_lang_two, .general_warning_lang").hide();
                    $(".i_p_e_body").append(loaderHTML);
                    addNewLanguage.find(':input[type=submit]').prop("disabled", true);
                },
                success: function (data) {
                    setTimeout(() => {
                        addNewLanguage.find(':input[type=submit]').prop("disabled", false);
                    }, 3000);

                    $(".loaderWrapper").remove();

                    switch (data) {
                        case "1":
                            $(".ppk_wraning").show();
                            break;
                        case "2":
                            $(".general_warning_lang_two").show();
                            break;
                        case "3":
                            $(".general_warning_lang").show();
                            break;
                        case "200":
                            location.reload();
                            break;
                        default:
                            $("body").append(`
                                <div class="nnauthority">
                                    <div class="no_permis flex_ c3 border_one tabing">${data}</div>
                                </div>`);
                            setTimeout(() => {
                                $(".nnauthority").remove();
                            }, 5000);
                    }
                }
            });
        });
    });
})(jQuery);