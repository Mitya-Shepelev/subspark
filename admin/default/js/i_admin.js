(function($) {
    "use strict";

    // Normalize all text AJAX responses by trimming whitespace/newlines
    if ($ && $.ajaxSetup) {
        $.ajaxSetup({
            converters: {
                "text text": function (text) {
                    return (typeof text === 'string') ? text.trim() : text;
                }
            }
        });
    }
    var preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
    var plreLoadingAnimationPlus = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';
    var uploadLoading = '<div class="i_upload_progress"></div>';
    $(document).on("click", ".subCaller", function() {
        var type = $(this).attr("data-id");
        if (!$("#" + type).hasClass("sub_in")) {
            $("#" + type).addClass("sub_in");
        } else {
            $(".sub_menu_wrapper").removeClass("sub_in");
        }
    });
    $(document).on("click",".saveChange", function(){
        var dataID = $(this).attr("data-id");
        var val = $("[name='" + dataID + "']").val();
        var data = 'f=changeColor&data='+dataID+'&clr='+val;
        if (val.trim() !== "") {
            $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("." + dataID).append(plreLoadingAnimationPlus); 
            },
            success: function(response) {
                if(response){ 
                    $(".i_modal_display_in_picker").remove();
                } 
                $(".loaderWrapper").remove();
            }
        });
        }
    });
    $(document).on("click",".setDefaultColor", function(){
        var dataID = $(this).attr("data-id"); 
        var data = 'f=setDefaultColor&data='+dataID;
        if (dataID.trim() !== "") {
            $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                 $("." + dataID).append(plreLoadingAnimationPlus); 
            },
            success: function(response) {
                $("[name='" + dataID + "']").val(''); 
                $("." + dataID).css('background-color', '');
                $(".loaderWrapper").remove(); 
            }
        });
        }
    });
    $(document).on("keyup input", "#gsearchsimple", function() {
        if (this.value.length > 0) {
            $(".i_choose_country").addClass("boxactive");
            $("#simple div").hide().filter(function() {
                return $(this).text().toLowerCase().lastIndexOf($("#gsearchsimple").val().toLowerCase(), 0) == 0;
            }).show();
        } else {
            $(".i_choose_country").removeClass("boxactive");
        }
    });
    $(document).on("mouseup touchend", function(e) {
        var boxContainer = $('.i_choose_country , .i_limit_list_mp_container , .i_limit_list_container , .i_limit_list_ch_container , .i_limit_list_cp_container , .i_limit_list_p_container , .i_limit_list_s3_container, .i_point_sub_list_container , .i_limit_list_cpads_container , .i_limit_list_cpsugg_container , .i_limit_list_cpprod_container , .i_limit_list_cptrend_container , .i_limit_list_cpfriendactivities_container , .i_limit_list_cpfriendactivitiesshown_container');
        if (!boxContainer.is(e.target) && boxContainer.has(e.target).length === 0) {
            $(boxContainer).removeClass('boxactive');
        }
    });
    $(document).on("click", ".i_s_country", function() {
        var countryCode = $(this).attr("id");
        var countryName = $(this).attr("data-c");
        $("#newCountry").val(countryCode);
        $("#gsearchsimple").val(countryName);
        $(".i_choose_country").removeClass("boxactive");
    });
    $(document).on('submit', "#genderOptionsForm", function(e) {
        e.preventDefault();
        var $form = $(this);
        $.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: $form.serialize(),
            beforeSend: function() {
                $(".successNot, .warning_invalid_genders").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                $form.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    $form.find(':input[type=submit]').prop('disabled', false);
                }, 500);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == 'invalid') {
                    $(".warning_invalid_genders").show();
                } else {
                    $("body").append('<div class=\"nnauthority\"><div class=\"no_permis flex_ c3 border_one tabing\">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            },
            error: function() {
                $form.find(':input[type=submit]').prop('disabled', false);
                $(".loaderWrapper").remove();
            }
        });
    });

    $(document).on('submit', "#sLoginSet", function(e) {
        e.preventDefault();
        var sLoginSet = $("#sLoginSet");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: sLoginSet.serialize(),
            beforeSend: function() {
                $(".warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                sLoginSet.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    sLoginSet.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    location.reload();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#updatePaymentGataway", function(e) {
        e.preventDefault();
        var updateGateway = $("#updatePaymentGataway");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: updateGateway.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                updateGateway.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    updateGateway.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#paymentSettings", function(e) {
        e.preventDefault();
        var paymentSettings = $("#paymentSettings");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: paymentSettings.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_one , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                paymentSettings.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    paymentSettings.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '1') {
                    $(".warning_one").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#updateVerificationStatus", function(e) {
        e.preventDefault();
        var updateVerificationStatus = $("#updateVerificationStatus");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: updateVerificationStatus.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_one").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                updateVerificationStatus.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    updateVerificationStatus.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $('.successNot').show();
                } else if (data == '1') {
                    $('.warning_one').show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#editUserDetails", function(e) {
        e.preventDefault();
        var editUserDetails = $("#editUserDetails");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: editUserDetails.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                editUserDetails.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    editUserDetails.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#editPointPackage", function(e) {
        e.preventDefault();
        var editPointPackage = $("#editPointPackage");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: editPointPackage.serialize(),
            beforeSend: function() {
                $(".pk_wraning").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                editPointPackage.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    editPointPackage.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    window.location.href = siteurlRedirect + 'manage_point_packages';
                } else if (data == '404') {
                    $(".pk_wraning").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#customCodes", function(e) {
        e.preventDefault();
        var customCodes = $("#customCodes");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: customCodes.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                customCodes.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    customCodes.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#editPostForm", function(e) {
        e.preventDefault();
        var editPostForm = $("#editPostForm");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: editPostForm.serialize(),
            beforeSend: function() {
                $(".warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                editPostForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    editPostForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    window.location.href = siteurlRedirect + 'allPosts';
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });

    $(document).on('submit', "#approvePostForm", function(e) {
        e.preventDefault();
        var approvePostForm = $("#approvePostForm");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: approvePostForm.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                approvePostForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    approvePostForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', '#storageSettings', function(e) {
        e.preventDefault();
        var storageSettings = $('#storageSettings');
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: storageSettings.serialize(),
            beforeSend: function() {
                $(".warning_ , .successNot").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                storageSettings.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    storageSettings.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', '#myEmailForm', function(e) {
        e.preventDefault();
        var myEmailForm = $('#myEmailForm');
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: myEmailForm.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_one , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                myEmailForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    myEmailForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '1') {
                    $(".warning_one").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', '#myProfileForm', function(e) {
        e.preventDefault();
        var generalSettingsForm = $('#myProfileForm');
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: generalSettingsForm.serialize(),
            beforeSend: function() {
                $(".warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                generalSettingsForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    generalSettingsForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', '#siteBusinessInformation', function(e) {
        e.preventDefault();
        var businessInformation = $('#siteBusinessInformation');
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: businessInformation.serialize(),
            beforeSend: function() {
                $(".warning_one , .successNot").hide();
                $("#business_conf").append(plreLoadingAnimationPlus);
                businessInformation.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    businessInformation.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '1') {
                    $(".warning_one").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', '#limits', function(e) {
        e.preventDefault();
        var limits = $('#limits');
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: limits.serialize(),
            beforeSend: function() {
                $(".warning_two , .successNot , .warning_one").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                limits.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    limits.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '1') {
                    $(".warning_two").show();
                } else if (data == '2') {
                    $(".warning_one").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_logo", function(e) {
        e.preventDefault();
        
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#lUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                if (type === 'sec_one') {
                    $("#logo").val('');
                    $("#sec_logo").append(uploadLoading);
                } else {
                    $("#favicon").val('');
                    $("#sec_fav").append(uploadLoading);
                }
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_one') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            }
        });
     
        $("#lUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_fav", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#lfUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                if (type === 'sec_one') {
                    $("#logo").val('');
                    $("#sec_logo").append(uploadLoading);
                } else {
                    $("#favicon").val('');
                    $("#sec_fav").append(uploadLoading);
                }
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_one') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            }
        });
     
        $("#lfUploadForm").trigger("submit");
    });
    $(document).on("click", ".i_limit", function() {
        var type = $(this).attr("data-type");
        if (type == 'fl_limit') {
            if (!$(".i_limit_list_container").hasClass("boxactive")) {
                $(".i_limit_list_container").addClass("boxactive");
            } else {
                $(".i_limit_list_container").removeClass("boxactive");
            }
        } else if (type == 'ch_limit') {
            if (!$(".i_limit_list_ch_container").hasClass("boxactive")) {
                $(".i_limit_list_ch_container").addClass("boxactive");
            } else {
                $(".i_limit_list_ch_container").removeClass("boxactive");
            }
        } else if (type == 'cp_limit') {
            if (!$(".i_limit_list_cp_container").hasClass("boxactive")) {
                $(".i_limit_list_cp_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cp_container").removeClass("boxactive");
            }
        }else if (type == 'cpm_limit') {
            if (!$(".i_limit_list_mp_container").hasClass("boxactive")) {
                $(".i_limit_list_mp_container").addClass("boxactive");
            } else {
                $(".i_limit_list_mp_container").removeClass("boxactive");
            }
        } else if (type == 'p_limit') {
            if (!$(".i_limit_list_p_container").hasClass("boxactive")) {
                $(".i_limit_list_p_container").addClass("boxactive");
            } else {
                $(".i_limit_list_p_container").removeClass("boxactive");
            }
        } else if (type == 'chm_limit') {
            if (!$(".i_limit_list_cp_container").hasClass("boxactive")) {
                $(".i_limit_list_cp_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cp_container").removeClass("boxactive");
            }
        } else if (type == 'smtpormail') {
            if (!$(".i_limit_list_cp_container").hasClass("boxactive")) {
                $(".i_limit_list_cp_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cp_container").removeClass("boxactive");
            }
        } else if (type == 'smtp_encription') {
            if (!$(".i_limit_list_ch_container").hasClass("boxactive")) {
                $(".i_limit_list_ch_container").addClass("boxactive");
            } else {
                $(".i_limit_list_ch_container").removeClass("boxactive");
            }
        } else if (type == 's3update') {
            if (!$(".i_limit_list_s3_container").hasClass("boxactive")) {
                $(".i_limit_list_s3_container").addClass("boxactive");
            } else {
                $(".i_limit_list_s3_container").removeClass("boxactive");
            }
        } else if (type == 'verification') {
            if (!$(".i_limit_list_ch_container").hasClass("boxactive")) {
                $(".i_limit_list_ch_container").addClass("boxactive");
            } else {
                $(".i_limit_list_ch_container").removeClass("boxactive");
            }
        } else if (type == 'usertype') {
            if (!$(".i_limit_list_cp_container").hasClass("boxactive")) {
                $(".i_limit_list_cp_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cp_container").removeClass("boxactive");
            }
        } else if (type == 'pl_limit') {
            if (!$(".i_point_sub_list_container").hasClass("boxactive")) {
                $(".i_point_sub_list_container").addClass("boxactive");
            } else {
                $(".i_point_sub_list_container").removeClass("boxactive");
            }
        } else if(type == 'cpa_limit'){
            if (!$(".i_limit_list_cpads_container").hasClass("boxactive")) {
                $(".i_limit_list_cpads_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cpads_container").removeClass("boxactive");
            }
        }else if(type == 'cpu_limit'){
            if (!$(".i_limit_list_cpsugg_container").hasClass("boxactive")) {
                $(".i_limit_list_cpsugg_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cpsugg_container").removeClass("boxactive");
            }
        }else if(type == 'cprod_limit'){
            if (!$(".i_limit_list_cpprod_container").hasClass("boxactive")) {
                $(".i_limit_list_cpprod_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cpprod_container").removeClass("boxactive");
            }
        }else if(type == 'cptrend_limit'){
            if (!$(".i_limit_list_cptrend_container").hasClass("boxactive")) {
                $(".i_limit_list_cptrend_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cptrend_container").removeClass("boxactive");
            }
        }else if(type == 'cpactivity_limit'){
            if (!$(".i_limit_list_cpfriendactivities_container").hasClass("boxactive")) {
                $(".i_limit_list_cpfriendactivities_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cpfriendactivities_container").removeClass("boxactive");
            }
        }else if(type == 'cpactivityshown_limit'){
            if (!$(".i_limit_list_cpfriendactivitiesshown_container").hasClass("boxactive")) {
                $(".i_limit_list_cpfriendactivitiesshown_container").addClass("boxactive");
            } else {
                $(".i_limit_list_cpfriendactivitiesshown_container").removeClass("boxactive");
            }
        }
    });
    $(document).on("click", ".i_s_limit", function() {
        var newLimit = $(this).attr("id");
        var newLimitText = $(this).attr("data-c");
        var type = $(this).attr("data-type");
        if (type == 'mb_limit') {
            $("#upLimit").val(newLimit);
            $(".lmt").html(newLimitText);
            $(".i_limit_list_container").removeClass("boxactive");
        } else if (type == 'ps_limit') {
            $("#pSLimit").val(newLimit);
            $(".pslmt").html(newLimitText);
            $(".i_point_sub_list_container").removeClass("boxactive");
        } else if (type == 'characterLimit') {
            $("#upcLimit").val(newLimit);
            $(".lct").html(newLimitText);
            $(".i_limit_list_ch_container").removeClass("boxactive");
        } else if (type == 'postLimit') {
            $("#uppLimit").val(newLimit);
            $(".lppt").html(newLimitText);
            $(".i_limit_list_cp_container").removeClass("boxactive");
        } else if (type == 'adsLimit') {
            $("#uppadLimit").val(newLimit);
            $(".lppat").html(newLimitText);
            $(".i_limit_list_cpads_container").removeClass("boxactive");
        } else if (type == 'sugUserLimit') {
            $("#uppsugLimit").val(newLimit);
            $(".lppsug").html(newLimitText);
            $(".i_limit_list_cpsugg_container").removeClass("boxactive");
        } else if (type == 'sugProdLimit') {
            $("#uppprodLimit").val(newLimit);
            $(".lppprod").html(newLimitText);
            $(".i_limit_list_cpprod_container").removeClass("boxactive");
        }else if (type == 'trendLimit') {
            $("#uppTrendLimit").val(newLimit);
            $(".lpptrend").html(newLimitText);
            $(".i_limit_list_cptrend_container").removeClass("boxactive");
        }else if (type == 'activityLimit') {
            $("#uppFriendAvtivityLimit").val(newLimit);
            $(".lppfractivity").html(newLimitText);
            $(".i_limit_list_cpfriendactivities_container").removeClass("boxactive");
        }else if (type == 'activityshownLimit') {
            $("#uppFriendAvtivitySlownLimit").val(newLimit);
            $(".lppfractivityshown").html(newLimitText);
            $(".i_limit_list_cpfriendactivitiesshown_container").removeClass("boxactive");
        } else if (type == 'pagLimit') {
            $("#ppLimit").val(newLimit);
            $(".ppt").html(newLimitText);
            $(".i_limit_list_p_container").removeClass("boxactive");
        } else if (type == 'language') {
            $("#upcmLimit").val(newLimit);
            $(".lclt").html(newLimitText);
            $(".i_limit_list_cp_container").removeClass("boxactive");
        } else if (type == 'smtpOrMail') {
            $("#smtp_or_mail").val(newLimit);
            $(".sm_or_ma").html(newLimitText);
            $(".i_limit_list_cp_container").removeClass("boxactive");
        } else if (type == 'smtpEncryption') {
            $("#smtp_encription").val(newLimit);
            $(".ssl_or_tls").html(newLimitText);
            $(".i_limit_list_ch_container").removeClass("boxactive");
        } else if (type == 's3set') {
            $("#s3region").val(newLimit);
            $(".s3choosed").html(newLimitText);
            $(".i_limit_list_s3_container").removeClass("boxactive");
        }else if(type == 'wasSet'){
            $("#wasregion").val(newLimit);
            $(".wasChoosed").html(newLimitText);
            $(".i_limit_list_s3_container").removeClass("boxactive");
        } else if (type == 'verfUser') {
            $("#verification").val(newLimit);
            $(".lct").html(newLimitText);
            $(".i_limit_list_ch_container").removeClass("boxactive");
        } else if (type == 'usrtyp') {
            $("#usertype").val(newLimit);
            $(".lut").html(newLimitText);
            $(".i_limit_list_cp_container").removeClass("boxactive");
        } else if (type == 'chooseAnnouncementType') {
            $("#upcLimit").val(newLimit);
            $(".lct").html(newLimitText);
            $(".i_limit_list_ch_container").removeClass("boxactive");
        }else if (type == 'postMLimit') {
            $("#uppmLimit").val(newLimit);
            $(".lpptm").html(newLimitText);
            $(".i_limit_list_mp_container").removeClass("boxactive");
        }
    });
    /*Change Default Language*/
    $(document).on("click", ".setDefault", function() {
        var type = 'updateDefaultLang';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&lang=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    $(".up_lng").show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $(".up_lng").hide();
                }, 5000);
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmd", function() {
        var type = $(this).attr("id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type).val('0');
                    } else {
                        $("#" + type).val('1');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    $(document).on("change", ".sstat", function() {
        var value = $(this).val();
        if (value == '1') {
            $("#sstat").val('0');
            $("#stats3").val('0');
        } else {
            $("#stats3").val('1');
            $("#sstat").val('1');
        }
    });
    $(document).on("change", ".sfstat", function() {
        var value = $(this).val();
        if (value == '1') {
            $("#sfstat").val('0');
            $("#sftats3").val('0');
        } else {
            $("#sftats3").val('1');
            $("#sfstat").val('1');
        }
    });
    $(document).on("change", ".spstat", function() {
        var value = $(this).val();
        if (value == '1') {
            $("#spstat").val('0');
            $("#sptats3").val('0');
        } else {
            $("#sptats3").val('1');
            $("#spstat").val('1');
        }
    });
    /*Select Approve / Decline / Rejected */
    $(document).on("click", ".approve_ch_item", function() {
        var ID = $(this).attr("data-val");
        $(".approve_ch_item").removeClass("choosed");
        $("#appr_" + ID).addClass("choosed");
        $("#approve_type").val(ID);
    });
    /*Delete Premium Post From aWaiting Post Page*/
    $(document).on("click", ".delete_post", function() {
        var type = 'deletePost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".i_modal_g_footer").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == 200) {
                    location.reload();
                } else if (response == 404) {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delp", function() {
        var type = 'ddelPost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });

    $(document).on("click", ".shareClose , .no-del", function() {
        $(".i_modal_in_in , .i_modal_display_in_picker").addClass("i_modal_in_in_out");
        setTimeout(() => {
            $(".i_modal_bg_in , .i_modal_bg_colorpicker_in").remove();
        }, 200);
    });
    /*Call SVG Edit PopUP*/
    $(document).on("click", ".editSvgIcon", function() {
        var type = 'editSVGPopUp';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&svg=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $("#svg_id_" + ID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change SVG Status*/
    $(document).on("change", ".iaStat", function() {
        var type = 'iconSVGStatus';
        var value = $(this).val();
        var ID = $(this).attr("data-id");
        var data = 'f=' + type + '&mod=' + value + '&svg=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type).val('0');
                    } else {
                        $("#" + type).val('1');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    /*Call SVG Edit PopUP*/
    $(document).on("click", ".newCreate", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&svg=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $("#svg_id_" + ID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on("change", ".pstat", function() {
        var value = $(this).val();
        if (value == '1') {
            $("#plnststs").val('0');
        } else {
            $("#plnststs").val('1');
        }
    });
    /*Change Module Statuses*/
    $(document).on("change", ".pstat", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type + ID).val('0');
                    } else {
                        $("#" + type + ID).val('1');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delete_plan", function() {
        var type = 'ddelPlan';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Plan */
    $(document).on("click", ".del__plan", function() {
        var type = 'deleteThisPlan';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".uplan", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type + ID).val('0');
                    } else {
                        $("#" + type + ID).val('1');
                    }
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".upStick", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type + ID).val('0');
                    } else {
                        $("#" + type + ID).val('1');
                    }
                    $(".upStick" + ID).show();
                    setTimeout(() => {
                        $(".upStick" + ID).hide();
                    }, 5000);
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".edit_lang", function() {
        var type = 'editLanguage';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Call Delete Language PopUpBox*/
    $(document).on("click", ".del_lang", function() {
        var type = 'delLang';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Premium Post From aWaiting Post Page*/
    $(document).on("click", ".delete_lng", function() {
        var type = 'deleteThisLanguage';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User PopUp*/
    $(document).on("click", ".del_us", function() {
        var type = 'deleteUser';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete User*/
    $(document).on("click", ".delete_usr", function() {
        var type = 'deleteUser';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".del_verf", function() {
        var type = 'deleteUserVerification';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete User*/
    $(document).on("click", ".delete_verf", function() {
        var type = 'deleteUserVerification';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".delpage", function() {
        var type = 'ddelPage';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".delqa", function() {
        var type = 'ddelQA';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Premium Post From aWaiting Post Page*/
    $(document).on("click", ".delete_page", function() {
        var type = 'deletePage';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Delete Premium Post From aWaiting Post Page*/
    $(document).on("click", ".delete_qa", function() {
        var type = 'deleteQA';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".edStick", function() {
        var type = 'editStickerUrl';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&sid=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Call Delete User PopUp*/
    $(document).on("click", ".del_stick", function() {
        var type = 'deleteSticker';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".addNewSticker", function() {
        var type = 'addNewStickerUrl';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&sid=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdPayment", function() {
        var type = $(this).attr("id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type).val('0');
                    } else {
                        $("#" + type).val('1');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".slog", function() {
        var type = $(this).attr("data-type");
        var value = $(this).val();

        if (value == '1') {
            $("#" + type + '_status').val('0');
            $("#" + type + '_statuss').val('0');
        } else if (value == '0') {
            $("#" + type + '_statuss').val('1');
            $("#" + type + '_status').val('1');
        }

    });
    /*Mark as Paid*/
    $(document).on("click", ".mark_as_paid", function() {
        var type = 'paid';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    window.location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".decline_this", function() {
        var type = 'declineSure';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&did=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Yes Decline Request*/
    $(document).on("click", ".yesDecline", function() {
        var type = 'yesDecline';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });

    /*Call Delete User PopUp*/
    $(document).on("click", ".del_upout", function() {
        var type = 'deletePayout';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Call Delete Product PopUP*/
    $(document).on("click", ".del_ProdPopUP", function() {
        var type = 'deleteProduct';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    $(document).on("click", ".delete_pryt", function() {
        var type = 'deleteProductt';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Delete User*/
    $(document).on("click", ".delete_pyt", function() {
        var type = 'deletePayoutt';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on("click", ".clps", function() {
        if (!$(".i_admin_left").hasClass("open_left_menu")) {
            $(".i_admin_left").addClass("open_left_menu");
        } else {
            $(".i_admin_left").removeClass("open_left_menu");
        }
    });
    /*Upload Verification Files*/
    $(document).on("change", "#ad_image", function(e) {
        e.preventDefault();
    
        var values = $("#adsFile").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#adsUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#adsFile").val('');
                $("#sec_logo").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    $("#adsFile").val(response);
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#adsUploadForm").trigger("submit");
    });

    $(document).on('submit', "#adsDForm", function(e) {
        e.preventDefault();
        var adsDForm = $("#adsDForm");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: adsDForm.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_one , .warning_two , .ppk_wraning , .warning_tree").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                adsDForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    adsDForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '2') {
                    $(".warning_one").show();
                } else if (data == '3') {
                    $(".warning_two").show();
                } else if (data == '1') {
                    $(".ppk_wraning").show();
                } else if (data == '4') {
                    $(".warning_tree").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Ads Status*/
    $(document).on("change", ".adsStat", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $(".plbox" + ID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type + ID).val('0');
                    } else {
                        $("#" + type + ID).val('1');
                    }
                    $("." + type).show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });

    $(document).on('submit', "#adsUForm", function(e) {
        e.preventDefault();
        var adsUForm = $("#adsUForm");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: adsUForm.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_one , .warning_two , .ppk_wraning , .warning_tree").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                adsUForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    adsUForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '2') {
                    $(".warning_one").show();
                } else if (data == '3') {
                    $(".warning_two").show();
                } else if (data == '1') {
                    $(".ppk_wraning").show();
                } else if (data == '4') {
                    $(".warning_tree").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delete_ads", function() {
        var type = 'ddelAds';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Plan */
    $(document).on("click", ".del__ads", function() {
        var type = 'deleteThisAds';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Delete Sticker*/
    $(document).on("click", ".delete_sticker", function() {
        var type = 'deleteSticker';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
                if (response == '200') {
                    location.reload();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#updateSubsPaymentGataway", function(e) {
        e.preventDefault();
        var updateSubGateway = $("#updateSubsPaymentGataway");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: updateSubGateway.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                updateSubGateway.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    updateSubGateway.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdSubPayment", function() {
        var type = $(this).attr("id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type).val('2');
                    } else {
                        $("#" + type).val('1');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    $(document).on('submit', "#updateGiphy", function(e) {
        e.preventDefault();
        var updateGiphy = $("#updateGiphy");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: updateGiphy.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                updateGiphy.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    updateGiphy.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#updateAiCredit", function(e) {
        e.preventDefault();
        var updateAiCredit = $("#updateAiCredit");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: updateAiCredit.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                updateAiCredit.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    updateAiCredit.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', '#liveSettings', function(e) {
        e.preventDefault();
        var liveStreamForm = $('#liveSettings');
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: liveStreamForm.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_one , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                liveStreamForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    liveStreamForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", "input:radio[name='mTheme']", function() {
        var type = 'updateMainPage';
        var ID = $(this).val();
        var data = 'f=' + type + '&tm=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $(".plbox" + ID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {

                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_first", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#lUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_one").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_one') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            }
        });
     
        $("#lUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_second", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#lsUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_two").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_two') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#lsUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_thirth", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#ltUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_three").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_three') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#ltUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_four", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#lfUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_four").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_four') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            }
        });
     
        $("#lfUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_five", function(e) {
        e.preventDefault();
        
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#lfiUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_five").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_five') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            }
        });
    
        // Modern trigger usage instead of .submit()
        $("#lfiUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_six", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#lsiUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_six").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_six') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            }
        });
     
        $("#lsiUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_seventh", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#lsevUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_seventh").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_seventh') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            }
        });
     
        $("#lsevUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_bg", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#bgUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_bg").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_bg') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#bgUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_landing_frnt", function(e) {
        e.preventDefault();
    
        var values = $("#logo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#frntUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#sec_frnt").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_frnt') {
                        $("#logo").val(response);
                    } else {
                        $("#favicon").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#frntUploadForm").trigger("submit");
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".editQA", function() {
        var type = 'editQuestionAnswer';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&sid=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    $(document).on('submit', "#updateSubsPaymentGatawayCCBILL", function(e) {
        e.preventDefault();
        var updateSubGateway = $("#updateSubsPaymentGatawayCCBILL");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: updateSubGateway.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                updateSubGateway.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    updateSubGateway.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User PopUp*/
    $(document).on("click", ".show_withdraw_details", function() {
        var type = 'showWithdrawDetails';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdPost", function() {
        var type = $(this).attr("id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) { 
                if (response == '200') {
                    if (value == 'yes') {
                        $("#" + type).val('no');
                        $("." + type).val('no');
                    } else {
                        $("." + type).val('yes');
                        $("#" + type).val('yes');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delq", function() {
        var type = 'ddelQuest';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Question*/
    $(document).on("click", ".delete_quest", function() {
        var type = 'deleteQuest';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User PopUp*/
    $(document).on("click", ".show_question_details", function() {
        var type = 'showQuestionDetails';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });

    /*Change Module Statuses*/
    $(document).on("change", ".chmdquestion", function() {
        var type = 'questionAnswerStatus';
        var value = $(this).val();
        var qID = $(this).attr("data-id");
        var data = 'f=' + type + '&mod=' + value + '&qid=' + qID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $(".q" + qID).val('0');
                    } else {
                        $(".q" + qID).val('1');
                    }
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $(".q" + qID).hide();
                }, 5000);
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delr", function() {
        var type = 'ddelReportP';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Question*/
    $(document).on("click", ".delete_report", function() {
        var type = 'deleteReport';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".rchmdReport", function() {
        var type = 'rCheckStatus';
        var value = $(this).val();
        var qID = $(this).attr("data-id");
        var data = 'f=' + type + '&mod=' + value + '&rid=' + qID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $(".q" + qID).val('0');
                    } else {
                        $(".q" + qID).val('1');
                    }
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $(".q" + qID).hide();
                }, 5000);
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".rcchmdReport", function() {
        var type = 'rcCheckStatus';
        var value = $(this).val();
        var qID = $(this).attr("data-id");
        var data = 'f=' + type + '&mod=' + value + '&rid=' + qID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $(".q" + qID).val('0');
                    } else {
                        $(".q" + qID).val('1');
                    }
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $(".q" + qID).hide();
                }, 5000);
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delrc", function() {
        var type = 'ddelReportC';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Question*/
    $(document).on("click", ".delete_report_c", function() {
        var type = 'deleteCReport';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdBlockCountries", function() {
        var type = $(this).attr("id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == 'yes') {
                        $("#" + type).val('no');
                        $("." + type).val('no');
                    } else {
                        $("." + type).val('yes');
                        $("#" + type).val('yes');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".reCaptchaPost", function() {
        var value = $(this).val();
        if (value == 'yes') {
            $(this).val('no');
        } else {
            $(this).val('yes');
        }
    });
    /*Change Module Statuses*/
    $(document).on("change", ".oneSignalStatuss", function() {
        var value = $(this).val();
        if (value == 'open') {
            $(this).val('close');
        } else {
            $(this).val('open');
        }
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_watermark", function(e) {
        e.preventDefault();
    
        var values = $("#watlogo").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#waUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                if (type === 'sec_tree') {
                    $("#watlogo").val('');
                    $("#sec_logo").append(uploadLoading);
                }
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    if (type === 'sec_tree') {
                        $("#watlogo").val(response);
                    }
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#waUploadForm").trigger("submit");
    });
    /*Upload Verification Files*/
    $(document).on("change", "#gift_image", function(e) {
        e.preventDefault();
    
        var values = $("#giftFile").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#giftImageUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#giftFile").val('');
                $("#sec_logo").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    $("#giftFile").val(response);
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#giftImageUploadForm").trigger("submit");
    });

    $(document).on('submit', "#editLivePointPackage", function(e) {
        e.preventDefault();
        var editLivePointPackage = $("#editLivePointPackage");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: editLivePointPackage.serialize(),
            beforeSend: function() {
                $(".pk_wraning").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                editLivePointPackage.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    editLivePointPackage.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    window.location.href = siteurlRedirect + 'manage_point_packages_live';
                } else if (data == '404') {
                    $(".pk_wraning").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delete_live_plan", function() {
        var type = 'ddelLivePlan';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delete_frame_plan", function() {
        var type = 'ddelFramePlan';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Plan */
    $(document).on("click", ".del__live_plan", function() {
        var type = 'deleteThisLivePlan';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Delete Plan */
    $(document).on("click", ".del__frame_plan", function() {
        var type = 'deleteThisFramePlan';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Upload Verification Files*/
    $(document).on("change", "#gift_animation_image", function(e) {
        e.preventDefault();
    
        var values = $("#GiftAnimationFile").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#giftAnimationImageUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#GiftAnimationFilea").val('');
                $("#sec_logo").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    $("#GiftAnimationFilea").val(response);
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#giftAnimationImageUploadForm").trigger("submit");
    });
    $(document).on('submit', '#affilateSet', function(e) {
        e.preventDefault();
        var businessInformation = $('#affilateSet');
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: businessInformation.serialize(),
            beforeSend: function() {
                $(".warning_one , .successNot").hide();
                $("#business_conf").append(plreLoadingAnimationPlus);
                businessInformation.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    businessInformation.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '1') {
                    $(".warning_one").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdAutoApprovePost", function() {
        var type = $(this).attr("id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == 'yes') {
                        $("#" + type).val('no');
                        $("." + type).val('no');
                    } else {
                        $("." + type).val('yes');
                        $("#" + type).val('yes');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdCrAc", function() {
        var type = 'becomecreatortypestatus';
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
                $(".success_tick").hide();
            },
            success: function(response) {
                if (response == '200') {
                    $("." + value).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    /*Delete Post From Database*/
    $(document).on("click", ".yes-del-story", function() {
        var type = 'deleteStorie';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                /*Do Something*/
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 100);
                if (response == '200') {
                    $(".body_" + ID).fadeOut();
                } else {
                    $(".warning_").show();
                }
            }
        });
    });
    /*Call Delete User PopUp*/
    $(document).on("click", ".del_stor_bg", function() {
        var type = 'deleteStoryBg';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Sticker*/
    $(document).on("click", ".delete_storybg", function() {
        var type = 'deleteStoryBg';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
                if (response == '200') {
                    location.reload();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdMod", function() {
        var type = $(this).attr("id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == 'yes') {
                        $("#" + type).val('no');
                        $("." + type).val('no');
                    } else {
                        $("." + type).val('yes');
                        $("#" + type).val('yes');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".addNewAnnouncement", function() {
        var type = 'addNewAnnouncement';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&sid=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdAnnouncementStatus", function() {
        var type = $(this).attr("id");
        var value = $(this).val();
        if (value == 'yes') {
            $("#" + type).val('no');
            $("." + type).val('no');
        } else {
            $("." + type).val('yes');
            $("#" + type).val('yes');
        }
        $("." + type).show();
    });
    /*Change Module Statuses*/
    $(document).on("change", ".upAnnon", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == 'yes') {
                        $("#" + type + ID).val('no');
                    } else {
                        $("#" + type + ID).val('yes');
                    }
                    $(".upAnnon" + ID).show();
                    setTimeout(() => {
                        $(".upAnnon" + ID).hide();
                    }, 5000);
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User PopUp*/
    $(document).on("click", ".del_annon", function() {
        var type = 'deleteAnnouncement';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Sticker*/
    $(document).on("click", ".delete_announce", function() {
        var type = 'deleteAnnouncement';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
                if (response == '200') {
                    location.reload();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete User Verification Request PopUp*/
    $(document).on("click", ".edAnnon", function() {
        var type = 'editAnnouncement';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&sid=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".upSocial", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == 'yes') {
                        $("#" + type + ID).val('no');
                    } else {
                        $("#" + type + ID).val('yes');
                    }
                    $(".upSocial" + ID).show();
                    setTimeout(() => {
                        $(".upSocial" + ID).hide();
                    }, 5000);
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Delete Question*/
    $(document).on("click", ".delete_ssite", function() {
        var type = 'deleteSocialSit';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Delete Question*/
    $(document).on("click", ".delete_sswite", function() {
        var type = 'deleteSocialSitW';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
   $(document).on("change", ".chmdModTwo", function() {
    var type = 'subCatMod';
    var value = $(this).val();
    var ID = $(this).attr("data-id");
    var data = 'f=' + type + '&mod=' + value + '&sID='+ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf"+ID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $(".fig_sec_" + ID).val('0');
                    } else {
                        $(".fig_sec_" + ID).val('1');
                    }
                    $(".sucss_"+ID).show();
                    setTimeout(() => {
                        $(".sucss_"+ID).hide();
                    }, 5000);
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();

            }
        });
    });
    $(document).on("click",".sceEd", function(){
        var ID = $(this).attr("id");
        var val = $("#sub_va_"+ID).val();
        var type = 'upSubKey';
        var data = 'f='+type+'&skey='+val+'&sid='+ID;
        $.ajax({
         type: 'POST',
         url: siteurl + 'request/request.php',
         data: data,
             beforeSend: function() {
                $("#general_conf"+ID).append(plreLoadingAnimationPlus);
             },
             success: function(response) {
                if (response == '200') {
                    $(".sucss_"+ID).show();
                    setTimeout(() => {
                        $(".sucss_"+ID).hide();
                    }, 5000);
                } else if (response == '404') {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
             }
         });
    });
    $(document).on("click", ".scneEd", function(){
        var ID = $(this).attr("data-c");
        var newKey = $("#n_"+ ID).val();
        var addToID = $(this).attr("id");
        var data = 'f=addNewSubCat'+'&nkey='+newKey+'&addTo='+addToID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
                beforeSend: function() {
                    $("#general_conf"+ID).append(plreLoadingAnimationPlus);
                },
                success: function(response) {
                    $(".a_n_"+ID).before(response);
                    $(".n_s_c_"+ID).hide(); 
                }
            });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".sbc_delete", function() {
        var type = 'delSubCat';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delete_subc", function() {
        var type = 'delSubCat';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $("#general_conf"+ID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response != '404') {
                   $("#general_conf"+ID).remove();
                }
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                    $(".loaderWrapper").remove();
                }, 200);
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".cmdCatMod", function() {
        var type = 'catModStatus';
        var value = $(this).val();
        var qID = $(this).attr("data-id");
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&mod=' + value + '&Cid=' + qID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#gen_"+qID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $(".ca_" + ID).val('0');
                    } else {
                        $(".ca_" + ID).val('1');
                    }
                    $(".mysuc_"+qID).show();
                    setTimeout(() => {
                        $(".mysuc_"+qID).hide();
                    }, 5000);
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();

            }
        });
    });
    $(document).on("click",".svcEdt", function(){
        var ID = $(this).attr("id");
        var val = $("#cat_va_"+ID).val();
        var type = 'upCatKey';
        var data = 'f='+type+'&ckey='+val+'&cid='+ID;
        $.ajax({
         type: 'POST',
         url: siteurl + 'request/request.php',
         data: data,
             beforeSend: function() {
                $("#gen_"+ID).append(plreLoadingAnimationPlus);
             },
             success: function(response) {
                if (response == '200') {
                    $(".mysuc_"+ID).show();
                    location.reload();
                    setTimeout(() => {
                        $(".mysuc_"+ID).hide();
                    }, 5000);
                } else if (response == '404') {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
             }
         });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".del_this_cat", function() {
        var type = 'delCatt';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delete_tcat", function() {
        var type = 'delCatt';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $("#gen_"+ID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response != '404') {
                   $("#gen_"+ID).remove();
                   location.reload();
                }
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                    $(".loaderWrapper").remove();
                }, 200);
            }
        });
    });
    $(document).on("click",".addNewPCat", function(){
        var nKey = $(".newCla").val();
        var data = 'f=cNewCatP'+'&ky='+nKey;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".papk_wraning , .warning_one").hide();
                $(".general_top").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                }else if(response == '1'){
                    $(".papk_wraning").show();
                }else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".uPBoost", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type + ID).val('0');
                    } else {
                        $("#" + type + ID).val('1');
                    }
                    $(".uPBoost" + ID).show();
                    setTimeout(() => {
                        $(".uPBoost" + ID).hide();
                    }, 5000);
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete Product PopUP*/
    $(document).on("click", ".del_BoostPopUP", function() {
        var type = 'deleteBoostedPost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    $(document).on("click", ".delete_boost", function() {
        var type = 'deleteBoostedPost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
                if (response == '200') {
                    location.reload();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delp", function() {
        var type = 'ddelPost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    $(document).on("change", ".pstatBoost", function() {
        var value = $(this).val();
        if (value == '1') {
            $("#plnststs").val('0');
        } else {
            $("#plnststs").val('1');
        }
    });
    /*Change Module Statuses*/
    $(document).on("change", ".pstatBoost", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#" + type + ID).val('0');
                    } else {
                        $("#" + type + ID).val('1');
                    }
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
     /*Call Delete Post PopUpBox*/
     $(document).on("click", ".delete_boost_plan", function() {
        var type = 'ddelBoostPlan';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Delete Plan */
    $(document).on("click", ".del__plan_boost", function() {
        var type = 'deleteThisBoostPlan';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#editBoostPackage", function(e) {
        e.preventDefault();
        var editBoostPackage = $("#editBoostPackage");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: editBoostPackage.serialize(),
            beforeSend: function() {
                $(".pk_wraning").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                editBoostPackage.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    editBoostPackage.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    window.location.href = siteurlRedirect + 'boost_package_settings';
                } else if (data == '404') {
                    $(".pk_wraning").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Check Payment*/
    $(document).on("click",".check_payment",function(){
        var type = 'getPaymentDetails';
        var paymentID = $(this).attr('id');
        var data = 'f=' + type + '&pyID=' + paymentID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false, 
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Approve Bank Payment*/
    $(document).on("click", ".approve_bank_payment", function() {
        var type = 'approveBankPayment';
        var payerUID = $(this).attr("id");
        var planID = $(this).attr("data-id");
        var imID = $(this).attr("data-imd");
        var paymentID = $(this).attr('data-pid');
        var data = 'f=' + type + '&payerid=' + payerUID + '&planID='+planID + '&imID=' +imID+'&paymentID='+paymentID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $(".progme").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Decline Bank Payment*/
    $(document).on("click", ".decline_bank_payment", function() {
        var type = 'declineBankPayment';
        var payerUID = $(this).attr("id");
        var planID = $(this).attr("data-id");
        var imID = $(this).attr("data-imd");
        var paymentID = $(this).attr('data-pid');
        var data = 'f=' + type + '&payerid=' + payerUID + '&planID='+planID + '&imID=' +imID+'&paymentID='+paymentID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $(".progme").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on('submit', "#updateBankPaymentGataway", function(e) {
        e.preventDefault();
        var updateBankPGateway = $("#updateBankPaymentGataway");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: updateBankPGateway.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                updateBankPGateway.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    updateBankPGateway.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Upload Verification Files*/
    $(document).on("change", "#frame_image", function(e) {
        e.preventDefault();
    
        var values = $("#frameFile").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
    
        $("#frameImageUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $("#frameFile").val('');
                $("#sec_logo").append(uploadLoading);
                $("." + type).hide();
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response && response !== "undefined,") {
                    $("#frameFile").val(response);
                    $("." + type).show();
                }
                $(".i_upload_progress").remove();
            } 
        });
     
        $("#frameImageUploadForm").trigger("submit");
    });
    $(document).on('submit', "#editFramePointPackage", function(e) {
        e.preventDefault();
        var editLivePointPackage = $("#editFramePointPackage");
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: editLivePointPackage.serialize(),
            beforeSend: function() {
                $(".pk_wraning").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                editLivePointPackage.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    editLivePointPackage.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    window.location.href = siteurlRedirect + 'manage_frame_packages';
                } else if (data == '404') {
                    $(".pk_wraning").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    }); 
    /*Call Colors*/
    $(document).on("click", ".call_colors", function() {
        var type = 'callColors';
        var ID = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'request/popup.php',
            data: data,
            cache: false,
            beforeSend: function() {
               $(".i_modal_display_in_picker").off("click"); 
               $(".i_modal_display_in_picker").remove(); 
               $("." + ID).append(plreLoadingAnimationPlus); 
            },
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_colorpicker_in").addClass('i_modal_display_in_picker');
                        $(".i_modal_display_in_picker").on("click");
                    }, 200);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    
    $(document).on("click", ".ttcolor", function() {
        var colorCode = $(this).css("background-color");
        colorCode = rgbToHex(colorCode); // RGB rengi HEX'e çevir
        var colorFor = $(this).attr("data-id");
    
        // Rengi header_top_color divine uygula
        $("."+colorFor).css("background-color", colorCode);
    
        // Rengi input elementinin değerine ata
        $('input[name="'+colorFor+'"]').val(colorCode);
    });
    
    // RGB rengi HEX'e çeviren fonksiyon
    function rgbToHex(rgb) {
        var hex = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        return "#" + ("0" + parseInt(hex[1], 10).toString(16)).slice(-2) +
                     ("0" + parseInt(hex[2], 10).toString(16)).slice(-2) +
                     ("0" + parseInt(hex[3], 10).toString(16)).slice(-2);
    }

/*Change Module Statuses*/
    $(document).on("change", ".chmdPostSub", function() {
        var type = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) { 
                if (response == '200') { 
                    $("." + type).show();
                } else if (response == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
                setTimeout(() => {
                    $("." + type).hide();
                }, 5000);
            }
        });
    });
})(jQuery);

$(document).ready(function() {
    var preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
    var plreLoadingAnimationPlus = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';
    $("body").on('submit', "#createNewPage", function(e) {
        e.preventDefault();
        var createNewPage = $("#createNewPage");
         
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: createNewPage.serialize(),
            beforeSend: function() {
                $(".warning_one , .warning_two , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                createNewPage.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    createNewPage.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    window.location.href = siteurlRedirect + 'pages';
                } else if (data == '1') {
                    $(".warning_one").show();
                } else if (data == '2') {
                    $(".warning_two").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $("body").on('submit', "#editPage", function(e) {
        e.preventDefault();
        var editPage = $("#editPage");
         
        jQuery.ajax({
            type: "POST",
            url: siteurl + "request/request.php",
            data: editPage.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_one , .warning_two , .warning_").hide();
                $("#general_conf").append(plreLoadingAnimationPlus);
                editPage.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    editPage.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '200') {
                    $(".successNot").show();
                } else if (data == '1') {
                    $(".warning_one").show();
                } else if (data == '2') {
                    $(".warning_two").show();
                } else if (data == '404') {
                    $(".warning_").show();
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + data + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".upwSocial", function() {
        var type = $(this).attr("data-type");
        var ID = $(this).attr("data-id");
        var value = $(this).val();
        var data = 'f=' + type + '&mod=' + value + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'request/request.php',
            data: data,
            beforeSend: function() {
                $("#general_conf").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == 'yes') {
                        $("#" + type + ID).val('no');
                    } else {
                        $("#" + type + ID).val('yes');
                    }
                    $(".upwSocial" + ID).show();
                    setTimeout(() => {
                        $(".upwSocial" + ID).hide();
                    }, 5000);
                } else {
                    $("body").append('<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' + response + '</div></div>');
                    setTimeout(() => {
                        $(".nnauthority").remove();
                    }, 5000);
                }
                $(".loaderWrapper").remove();
            }
        });
    });
});
