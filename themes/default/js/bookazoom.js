(function ($) {
    "use strict";

    // Toggle visibility and state for subscription fee fields
    $(document).on("change", ".subfeea", function (e) {
        const type = $(this).data("id");
        if (type !== 'askaquestion' && type !== 'limitslots') { return; }

        const checked = $(this).prop('checked');
        const $field = $('#' + type);
        const $block = $('.' + type);

        $field.val(checked ? 'ok' : 'not');
        if (checked) {
            $block.removeClass('nonePoint').css('display', '');
        } else {
            $block.addClass('nonePoint').hide();
        }

        e.stopImmediatePropagation();
    });

    // Handle file upload (Music, Video, Image)
    $(document).on("change", "#i_pr_file", function (e) {
        e.preventDefault();

        const id = $(this).data("id");
        const values = $("#uploadPrVal").val();
        const formData = { f: id };

        $("#uploadprform").ajaxForm({
            type: "POST",
            data: formData,
            delegation: true,
            cache: false,
            beforeSubmit: function () {
                $(".i_warning_unsupported").hide();
                $(".i_uploaded_iv").show();
                $(".publish").prop("disabled", true).css("pointer-events", "none");
            },
            uploadProgress: function (e, position, total, percentComplete) {
                $(".i_upload_progress").width(percentComplete + '%');
            },
            success: function (response) {
                if (response !== '303') {
                    $(".input_uploaded").append(response);
                    const fileIDs = $('.i_uploaded_item').map(function () {
                        return this.id;
                    }).get();

                    const combined = fileIDs.join(",") + "," + values;
                    if (combined !== "undefined,") {
                        $("#uploadPrVal").val(combined);
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
                console.error("Upload failed.");
            }
        }).submit();
    });

    // Delete uploaded item before publishing
    $(document).on("click", ".i_delete_item_button", function () {
        const fileID = $(this).attr("id");
        const input = $("#uploadPrVal");
        const postData = { f: "delete_file", file: fileID };

        $.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: postData,
            beforeSend: function () {
                // Optionally show loader
            },
            success: function (response) {
                if (response !== '404') {
                    $(".iu_f_" + fileID).remove();

                    input.val(function (_, value) {
                        return value.split(",").filter(v => v !== fileID).join(",");
                    });

                    if (!$(".i_uploaded_item").length) {
                        $(".i_uploaded_iv").hide();
                    }
                } else {
                    PopUPAlerts('not_file', 'ialert');
                }
            }
        });
    });

    // Upload verification thumbnail
    $(document).on("change", ".cTumb", function (e) {
        e.preventDefault();

        const id = $(this).data("id");
        const data = { f: "vTumbnail", id: id };

        $("#tupprloadform").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function () {
                $(".iu_f_" + id).append('<div class="i_upload_progress"></div>');
            },
            uploadProgress: function (e, position, total, percentComplete) {
                $(".i_upload_progress").width(percentComplete + '%');
            },
            success: function (response) {
                $("#viTumb" + id).css("background-image", "url(" + response + ")");
                $("#viTumbi" + id).attr("src", response);
                $(".i_upload_progress").remove();
            },
            error: function () {
                $(".i_upload_progress").remove();
                $(".i_warning_thumb_upload").fadeIn().delay(5000).fadeOut();
            }
        }).submit();
    });

    // Save product/booking form
    $(document).on("click", ".pr_save_btna", function () {
        const postData = {
            f: "createBookaZoom",
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
                if (String(response).trim() === '200') {
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
