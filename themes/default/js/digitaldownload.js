(function ($) {
    "use strict";

    /**
     * Upload image, video, audio files
     */
    $(document).on("change", "#i_pr_file", function (e) {
        e.preventDefault();

        const currentVal = $("#uploadPrVal").val();
        const uploadID = $(this).data("id");

        $("#uploadprform").ajaxForm({
            type: "POST",
            data: { f: uploadID },
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
                if (response !== "303") {
                    $(".input_uploaded").append(response);

                    const uploadedIDs = $(".i_uploaded_item").map(function () {
                        return this.id;
                    }).get().join(",");

                    if (uploadedIDs !== "undefined,") {
                        $("#uploadPrVal").val(uploadedIDs + "," + currentVal);
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
                // console.error("File upload failed.");
                // Note: Avoid console logs in production. Instead, you can optionally show an error box.
                // Example UI fallback (optional):
                // $(".i_warning_upload_failed").show();
            }
        }).submit();
    });

    /**
     * Upload downloadable product file
     */
    $(document).on("change", "#i_prd_file", function (e) {
        e.preventDefault();

        const currentVal = $("#uploadPrVal").val();
        const uploadID = $(this).data("id");

        $("#uploadprdform").ajaxForm({
            type: "POST",
            dataType: "json",
            data: { f: uploadID },
            delegation: true,
            cache: false,
            beforeSubmit: function () {
                $(".i_warning_unsupported").hide();
                $(".uploadedFileContainer").hide();
                $(".i_uploaded_iv").show();
                $(".upld").append('<div class="i_upload_progress" style="top: initial;"></div>');
                $(".publish").prop("disabled", true).css("pointer-events", "none");
            },
            uploadProgress: function (e, position, total, percentComplete) {
                $(".i_upload_progress").width(percentComplete + "%");
            },
            success: function (response) {
                if (response.status === "ok") {
                    $(".uploadedFileContainer").show();
                    $(".theFileIcon").html(response.fileIcon);
                    $(".theFileName").text(response.fileName);
                    $(".fileLink").attr("href", response.fileUrl);
                    $("#uploadPrfVal").val(response.filePath);
                }

                setTimeout(() => {
                    $(".publish").prop("disabled", false).css("pointer-events", "auto");
                }, 3000);
            },
            error: function () {
                // console.error("Downloadable file upload failed.");
                // Note: Avoid console logs in production. Optionally, show a user-friendly UI message here.
                // $(".i_warning_upload_failed").show(); // Optional fallback UI
            }
        }).submit();
    });

    /**
     * Delete uploaded file (before publishing)
     */
    $(document).on("click", ".i_delete_item_button", function () {
        const fileID = $(this).attr("id");

        $.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: { f: "delete_file", file: fileID },
            beforeSend: function () {
                // Optional: loading indicator
            },
            success: function (response) {
                if (response !== "404") {
                    $(".iu_f_" + fileID).remove();

                    const input = $("#uploadPrVal");
                    input.val(function (_, value) {
                        return value.split(",").filter(val => val !== fileID).join(",");
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
                // console.error("Thumbnail upload failed."); 
                // $(".i_warning_thumb_upload").show(); // Optional: show UI feedback instead
            }
        }).submit();
    });

    /**
     * Save digital product form
     */
    $(document).on("click", ".pr_save_btna", function () {
        const postData = {
            f: "createDigitalDownload",
            prnm: $("#pr_name").val(),
            prprc: $("#pr_price").val(),
            prdsc: $("#pr_description").val(),
            prdscinf: $("#pr_conf").val(),
            vals: $("#uploadPrVal").val(),
            dFile: $("#uploadPrfVal").val()
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
