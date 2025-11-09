(function ($) {
    "use strict";

    /**
     * Toggle additional field visibility based on select input
     */
    $(document).on("change", ".subfeea", function (e) {
        const type = $(this).data("id");
        if (type !== 'askaquestion' && type !== 'limitslots') { return; }

        const checked = $(this).prop('checked');
        const $target = $('.' + type);

        $('#' + type).val(checked ? 'ok' : 'not');
        if (checked) {
            $target.removeClass('nonePoint').css('display', '');
        } else {
            $target.addClass('nonePoint').hide();
        }

        e.stopImmediatePropagation();
    });

    /**
     * Handle media upload for scratch product (image, video, music)
     */
    $(document).on("change", "#i_pr_file", function (e) {
        e.preventDefault();

        const prevValues = $("#uploadPrVal").val();
        const uploadType = $(this).data("id");

        $("#uploadprform").ajaxForm({
            type: "POST",
            data: { f: uploadType },
            delegation: true,
            cache: false,
            beforeSubmit: function () {
                $(".i_warning_unsupported").hide();
                $(".i_uploaded_iv").show();
                $(".publish").prop("disabled", true).css("pointer-events", "none");
            },
            uploadProgress: function (e, position, total, percentComplete) {
                $(".i_upload_progress").width(percentComplete + "%");
            },
            success: function (response) {
                if (response !== '303') {
                    $(".input_uploaded").append(response);

                    const uploadedIDs = $(".i_uploaded_item").map(function () {
                        return this.id;
                    }).get().join(",");

                    if (uploadedIDs !== "undefined,") {
                        $("#uploadPrVal").val(uploadedIDs + "," + prevValues);
                    }
                } else {
                    $(".i_uploaded_iv, .i_uploading_not").hide();
                    $(".i_warning_unsupported").show();
                }

                $(".i_upload_progress").width("0%");
                $(".i_uploading_not").hide();
                setTimeout(() => {
                    $(".publish").prop("disabled", false).css("pointer-events", "auto");
                }, 3000);
            },
            error: function () { 
            }
        }).submit();
    });

    /**
     * Delete uploaded file before product is published
     */
    $(document).on("click", ".i_delete_item_button", function () {
        const fileID = $(this).attr("id");

        $.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: { f: "delete_file", file: fileID },
            beforeSend: function () {
                // Optional: add loading animation
            },
            success: function (response) {
                if (response !== "404") {
                    $(".iu_f_" + fileID).remove();

                    $("#uploadPrVal").val(function (_, currentVal) {
                        return currentVal.split(',').filter(val => val !== fileID).join(',');
                    });

                    if (!$(".i_uploaded_item").length) {
                        $(".i_uploaded_iv").hide();
                    }
                } else {
                    PopUPAlerts("not_file", "ialert");
                }
            }
        });
    });

    /**
     * Upload verification thumbnail
     */
    $(document).on("change", ".cTumb", function (e) {
        e.preventDefault();

        const id = $(this).data("id");

        $("#tupprloadform").ajaxForm({
            type: "POST",
            data: { f: "vTumbnail", id: id },
            delegation: true,
            cache: false,
            beforeSubmit: function () {
                $(".iu_f_" + id).append('<div class="i_upload_progress"></div>');
            },
            uploadProgress: function (e, position, total, percentComplete) {
                $(".i_upload_progress").width(percentComplete + "%");
            },
            success: function (response) {
                $("#viTumb" + id).css("background-image", "url(" + response + ")");
                $("#viTumbi" + id).attr("src", response);
                $(".i_upload_progress").remove();
            },
            error: function () { 
            }
        }).submit();
    });

    /**
     * Submit scratch product form
     */
    $(document).on("click", ".pr_save_btna", function () {
        const postData = {
            f: "createScratch",
            prnm: $("#pr_name").val(),
            prprc: $("#pr_price").val(),
            prdsc: $("#pr_description").val(),
            prdscinf: $("#pr_conf").val(),
            vals: $("#uploadPrVal").val(),
            lmSlot: $("#limitslots").prop('checked') ? 'ok' : 'not',
            askQ: $("#askaquestion").prop('checked') ? 'ok' : 'not',
            qAsk: $("#question_ask").val(),
            lSlot: $("#limit_slot").val()
        };

        $.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: postData,
            cache: false,
            beforeSend: function () {
                $(".i_warning, .successNot").hide();
            },
            success: function (response) {
                if (String(response).trim() === "200") {
                    $(".successNot").show();
                    $("#pr_name, #pr_description, #pr_conf").val('');
                    $("#pr_price").val('10');
                    $("#uploadPrVal").val('');
                    $(".input_uploaded").html('');
                } else {
                    $(".i_warning").show();
                }
            }
        });
    });

})(jQuery);
