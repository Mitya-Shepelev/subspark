(function ($) {
    "use strict";

    $(document).ready(function () {
        const newStickerForm = $("#newStickerForm");
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

        newStickerForm.on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                type: "POST",
                url: siteurl + "request/request.php",
                data: newStickerForm.serialize(),
                beforeSend: function () {
                    $(".warning_wrapper").hide();
                    $("#general_conf").append(loaderHTML);
                    newStickerForm.find(':input[type=submit]').prop("disabled", true);
                },
                success: function (data) {
                    setTimeout(() => {
                        newStickerForm.find(':input[type=submit]').prop("disabled", false);
                    }, 3000);

                    $(".loaderWrapper").remove();

                    switch (data) {
                        case "200":
                            location.reload();
                            break;
                        case "1":
                            $(".ppk_wraning").show();
                            break;
                        case "2":
                            $(".papk_wraning").show();
                            break;
                        case "3":
                            $(".warning_one").show();
                            break;
                        default:
                            $("body").append(`
                                <div class="nnauthority">
                                    <div class="no_permis flex_ c3 border_one tabing">${data}</div>
                                </div>`);
                            setTimeout(() => {
                                $(".nnauthority").remove();
                            }, 8000);
                    }
                }
            });
        });
    });
})(jQuery);