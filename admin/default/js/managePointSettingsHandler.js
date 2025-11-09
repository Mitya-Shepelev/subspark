(function($) {
    "use strict";

    const loadingHTML = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader"><div class="i_loading product_page_loading"><div class="dot-pulse"></div></div></div></div></div>';

    $(document).on("change", ".chmdPoint", function() {
        const type = $(this).attr("id");
        const isChecked = $(this).val() === 'yes';
        const newValue = isChecked ? 'no' : 'yes';
        $("#" + type).val(newValue);
        $("." + type).val(newValue);
    });

    $(document).on('submit', '#epdSettings', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: form.serialize(),
            beforeSend: function() {
                $(".warning_two , .successNot , .warning_one").hide();
                $("#general_conf").append(loadingHTML);
                form.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    form.find(':input[type=submit]').prop('disabled', false);
                }, 3000);

                switch (data) {
                    case '200':
                        $(".successNot").show();
                        break;
                    case '1':
                        $(".warning_two").show();
                        break;
                    case '2':
                        $(".warning_one").show();
                        break;
                    case '404':
                        $(".warning_").show();
                        break;
                    default:
                        $("body").append(`<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">${data}</div></div>`);
                        setTimeout(() => {
                            $(".nnauthority").remove();
                        }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
})(jQuery);