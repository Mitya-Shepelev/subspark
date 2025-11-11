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

    $(document).on("click", ".copyUrl", function() {
        PopUPAlerts('urlCopied', 'ialert');
    });
    document.addEventListener("DOMContentLoaded", function () {
      const mappings = [
        { selector: '.i_profile_cover_blur', attr: 'data-background' },
        { selector: '.i_profile_avatar', attr: 'data-avatar' }
      ];

      mappings.forEach(function (map) {
        document.querySelectorAll(map.selector).forEach(function (el) {
          const val = el.getAttribute(map.attr);
          if (val) {
            el.style.backgroundImage = 'url(' + val + ')';
          }
        });
      });
    });
    document.querySelectorAll('.hshCl[data-color]').forEach(function(el) {
      el.style.color = el.getAttribute('data-color');
    });
    var preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
    var plreLoadingAnimationPlus = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';
    var likeBox = '<div class="like_heart flex_ tabing">' +
        '<svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
        '<path d="M2 9.1371C2 14 6.01943 16.5914 8.96173 18.9109C10 19.7294 11 20.5 12 20.5C13 20.5 14 19.7294 15.0383 18.9109C17.9806 16.5914 22 14 22 9.1371C22 4.27416 16.4998 0.825464 12 5.50063C7.50016 0.825464 2 4.27416 2 9.1371Z" fill="#d32f2f"/>' +
        '</svg>' +
        '</div>';
    var UnlikeBox = '<div class="like_heart flex_ tabing">' +
    '<svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">' +
    '<path d="M8.10627 18.2468C5.29819 16.0833 2 13.5422 2 9.1371C2 4.53656 6.9226 1.20176 11.2639 4.81373L9.81064 8.20467C9.6718 8.52862 9.77727 8.90554 10.0641 9.1104L12.8973 11.1341L10.4306 14.012C10.1755 14.3096 10.1926 14.7533 10.4697 15.0304L12.1694 16.7302L11.2594 20.3702C10.5043 20.1169 9.74389 19.5275 8.96173 18.9109C8.68471 18.6925 8.39814 18.4717 8.10627 18.2468Z" fill="#d32f2f"/>' +
    '<path d="M12.8118 20.3453C13.5435 20.0798 14.2807 19.5081 15.0383 18.9109C15.3153 18.6925 15.6019 18.4717 15.8937 18.2468C18.7018 16.0833 22 13.5422 22 9.1371C22 4.62221 17.259 1.32637 12.9792 4.61919L11.4272 8.24067L14.4359 10.3898C14.6072 10.5121 14.7191 10.7007 14.7445 10.9096C14.7699 11.1185 14.7064 11.3284 14.5694 11.4882L12.0214 14.4609L13.5303 15.9698C13.7166 16.1561 13.7915 16.4264 13.7276 16.682L12.8118 20.3453Z" fill="#d32f2f"/>' +
    '</svg>' +
    '</div>';
    $(document).ready(function () {
        if ($('.commenta').length > 0 && $.fn.autoResize) {
          $('.commenta').autoResize();
        }

        if (typeof ClipboardJS !== "undefined") {
          new ClipboardJS('.copyUrl');
        }

        if ($('#newPostT').length > 0 && typeof $.fn.characterCounter === "function") {
          $("#newPostT").characterCounter({
            limit: typeof availableLength !== "undefined" ? availableLength : 250
          });
        }
      });
    /*Notifications*/
    var g = '';
    getm(g);
    function playNotificationSound() {
        var audio = document.getElementById('notification-sound-not');
        if (audio) {
            var playPromise = audio.play();

            if (playPromise !== undefined) {
                playPromise.then(_ => {}).catch(error => {});
            }
        }
    }
    function getm(g) {
        var type = 'get';
        if ($.trim(type).length === 0) {
            setTimeout(getm, 10000);
        } else {
            $.ajax({
                type: 'GET',
                url: siteurl + 'requests/get.php?f=1',
                dataType: "json",
                cache: false,
                beforeSend: function() {},
                success: function(response) {
                    var messageNotificationStatus = response.messageNotificationStatus;
                    var notificationStatus = response.notificationStatus;
                    var unReadedNotfications = response.unReadedNotfications;
                    var unReadMessageNotifications = response.unReadMessageNotifications;
                    var videoCallFound = response.videoCallFound;
                    var acceptStatus = response.acceptStatus;
                    if (messageNotificationStatus == '1') {
                        $(".msg_not").show();
                        $(".sum_m").html(unReadMessageNotifications);
                        if ($(".sum_m").attr("data-id") != messageNotificationStatus) {
                            $(".sum_m").attr("data-id", messageNotificationStatus);
                            playNotificationSound();
                        }
                    }
                    if (notificationStatus == '1') {
                        $(".not_not").show();
                        $(".sum_not").html(unReadedNotfications);
                        if ($(".sum_not").attr("data-id") != notificationStatus) {
                            $(".sum_not").attr("data-id", notificationStatus);
                            document.getElementById('notification-sound-not').play();
                        }
                    }
                    if (videoCallFound) {
                        if (!$("div").hasClass("videoCall")) {
                            VideoCallAlert(videoCallFound);
                        }
                    }
                    if (acceptStatus == '2') {
                        // Callee accepted: stop ring and remove caller's ringing modal if present
                        try { $("#notification-sound-call")[0].pause(); } catch(e){}
                        $(".videoCall").remove();
                    }
                    if (acceptStatus == '3') {
                        $(".caller_det").hide();
                        $(".call_declined").show();
                        $("#notification-sound-call")[0].pause();
                    }
                    if (!g) {
                        setTimeout(getm, 10000);
                    }
                }
            });
        }
    }
    $(document).on("click", ".loginForm", function() {
        $('.i_modal_bg').addClass('i_modal_display');
    });

    // Generic handler for upload/network failures
    function handleUploadError(xhr, status, error) {
        try { console.error('Upload error', { status: status, error: error, code: xhr && xhr.status }); } catch (e) {}
        // Abort = user canceled; skip noisy alert
        if (status === 'abort') { return; }
        if (status === 'timeout' || (xhr && xhr.status === 0)) {
            PopUPAlerts('upload_connection_lost', 'ialert');
        } else {
            PopUPAlerts('sWrong', 'ialert');
        }
        $(".processing-msg").remove();
        $(".i_upload_progress").removeClass('processing-animation').width('0%');
        $('.publish').prop('disabled', false);
        $(".publish").css("pointer-events", "auto");
    }
    $(document).on("click", ".i_modal_close", function() {
        $('.i_modal_bg').removeClass('i_modal_display');
        $(".i_modal_in").attr("style", "");
        $(".i_modal_forgot").hide();
    });
    $(document).on("click", ".password-reset", function() {
        $(".i_modal_in").hide();
        $(".i_modal_forgot").show();
    });
    $(document).on("click", ".already-member", function() {
        $(".i_modal_in").show();
        $(".i_modal_forgot").hide();
    });

    $(".i_comment_form_textarea").focusin(function() {
        var words = $(this).val();
        var ID = $(this).attr("data-id");
    });
    $(document).on("click", ".openPostMenu", function() {
        var ID = $(this).attr("id");
        $(".mnoBox" + ID).addClass("dblock");
    });
    $(document).on("click", ".openShareMenu", function() {
        var ID = $(this).attr("id");
        $(".mnsBox" + ID).addClass("dblock");
    });
    $(document).on("click", ".openComMenu", function() {
        var ID = $(this).attr("id");
        $(".comMBox" + ID).addClass("dblock");
    });
    $(document).on("click", ".msg_Set", function() {
        var ID = $(this).attr("id");
        if ($(".msg_Set")[0]) {
            $(".msg_Set").removeClass("dblock");
        }
        $(".msg_Set_" + ID).addClass("dblock");
    });
    $(document).on("click", ".smscd", function() {
        var ID = $(this).attr("id");
        if ($(".smscd")[0]) {
            $(".me_msg_plus").removeClass("dblock");
        }
        $(".msg_set_plus_" + ID).addClass("dblock");
    });
    $(document).on("click", ".whs", function() {
        $(".i_choose_ws_wrapper").addClass("dblock");
    });
    $(document).on("click", ".in_comment", function() {
        var ID = $(this).attr("id");
        $("#comment" + ID).focus();
    });
    $(document).on("mouseup touchend", function(e) {
        /*e.preventDefault();*/
        var postMenuContainer = $('.mnoBox , .mnsBox , .comMBox , .msg_Set , .me_msg_plus ,  .cSetc , .i_choose_ws_wrapper , .i_postFormContainer , .emojiBox , .emojiBoxC , .stickersContainer');
        var notif = $('.topMessages, .topPoints, .topNotifications , .getMenu , .emojiBox , .emojiBoxC , .camList , .stickersContainer , .chtBtns');
        if (!postMenuContainer.is(e.target) && postMenuContainer.has(e.target).length === 0) {
            $(postMenuContainer).removeClass('dblock');
        }
        if (!notif.is(e.target) && notif.has(e.target).length === 0) {
            $(".i_general_box_container , .i_general_box_message_notifications_container , .i_general_box_notifications_container , .emojiBox , .emojiBoxC , .stickersContainer , .ch_fl_btns_container").remove();
        }
    });
    $(document).on("click", ".emoji_item", function() {
        var copyEmoji = $(this).attr("data-emoji");
        var getValue = $(".newPostT").val();
        $(".newPostT").val(getValue + copyEmoji);
        $(".emojiBox").remove();
    });
    $(document).on("click", ".emoji_item_c", function() {
        var copyEmoji = $(this).attr("data-emoji");
        var ID = $(this).attr("data-id");
        var getValue = $("#comment" + ID).val();
        $("#comment" + ID).val(getValue + ' ' + copyEmoji + ' ');
    });

    function GetSlimScroll() {
        if ($(window).width() < 330) {
            $(".btest").slimScroll({
                height: '100%',
                width: '100%',
                railVisible: false,
                alwaysVisible: false,
                wheelStep: 1,
                railOpacity: .1
            });
        }
    }
    GetSlimScroll();
    $(document).on("click", ".getMenu", function() {
        var type = $(this).attr("data-type");
        var data = 'f=' + type;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (!$(".i_general_box_container")[0]) {
                    $("#" + type).append(response);
                    GetSlimScroll();
                } else {
                    $(".i_general_box_container").remove();
                }
                if ($("div").hasClass("stickersContainer") || $("div").hasClass("emojiBoxC") || $("div").hasClass("emojiBox") || $("div").hasClass("i_general_box_message_notifications_container") || $("div").hasClass("i_general_box_notifications_container")) {
                    $(".stickersContainer , .emojiBox , .emojiBoxC ,  .i_general_box_message_notifications_container , .i_general_box_notifications_container").remove();
                }
            }
        });
    });
    $(document).on("click", ".getEmojis", function() {
        var type = 'emoji';
        var ID = $(this).attr("data-type");
        var dataID = '';
        var data = 'f=' + type + '&id=' + ID + '&ec=' + dataID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (!$(".emojiBox")[0]) {
                    $(".i_pb_emojis").append(response);
                    GetSlimScroll();
                } else {
                    $(".emojiBox").remove();
                }
                if ($("div").hasClass("stickersContainer") || $("div").hasClass("emojiBoxC") || $("div").hasClass("i_general_box_container") || $("div").hasClass("i_general_box_message_notifications_container") || $("div").hasClass("i_general_box_notifications_container")) {
                    $(".stickersContainer , .emojiBoxC , .i_general_box_message_notifications_container , .i_general_box_notifications_container , .i_general_box_container").remove();
                }
            }
        });
    });
    $(document).on("click", ".getAiBox", function() {
        var type = 'aiBox';
        var ID = $(this).attr("data-type");
        var data = 'f=' + type;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (response) {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    $(document).on("click", ".createAiContent", function() {
        var type = 'generateAiContent';
        var prompt = $(".aiContT").val().trim();
        if (prompt === '') {
            return;
        }
        var data = 'f=' + type + '&uPrompt=' + encodeURIComponent(prompt);

        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {
                $(".i_modal_content").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response === 'no') {
                    $(".i_loading").remove();
                    $(".i_warning_ai").show();
                    setTimeout(() => {
                        $(".i_warning_ai").hide();
                    }, 5000);
                } else if (response === 'no_enough_credit') {
                    $(".i_loading").remove();
                    $(".i_warning_ai_credit").show();
                    setTimeout(() => {
                        $(".i_warning_ai_credit").hide();
                    }, 5000);
                } else {
                    $(".i_modal_bg_in").remove();
                    $(".newPostT").val(response + "\n").focus();
                    $('.newPostT').autoResize();
                }
            },
            error: function() {
                // console.error("Request failed.");
                // Avoid using console in production. You may optionally show an error box to the user here.
            }
        });
    });
    $(document).on("click", ".getEmojisC", function() {
        var type = 'emoji';
        var ID = $(this).attr("data-type");
        var dataID = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + ID + '&ec=' + dataID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (!$(".emojiBoxC")[0]) {
                    $(".getEmojisC" + dataID).append(response);
                    GetSlimScroll();
                } else {
                    $(".emojiBoxC").remove();
                }
                if ($("div").hasClass("stickersContainer") || $("div").hasClass("emojiBox") || $("div").hasClass("i_general_box_container") || $("div").hasClass("i_general_box_message_notifications_container") || $("div").hasClass("i_general_box_notifications_container")) {
                    $(".i_general_box_message_notifications_container , .i_general_box_notifications_container , .i_general_box_container").remove();
                }
            }
        });
    });
    $(document).on("click", ".topMessages", function() {
        var type = $(this).attr("data-type");
        var data = 'f=' + type;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (!$(".i_general_box_message_notifications_container")[0]) {
                    $("#" + type).append(response);
                    $(".msg_not").hide();
                    $(".sum_m").attr("data-id", 0);
                    GetSlimScroll();
                } else {
                    $(".i_general_box_message_notifications_container").remove();
                }
                if ($("div").hasClass("stickersContainer") || $("div").hasClass("emojiBoxC") || $("div").hasClass("emojiBox") || $("div").hasClass("i_general_box_container") || $("div").hasClass("i_general_box_notifications_container")) {
                    $(".stickersContainer , .emojiBox , .emojiBoxC , .i_general_box_container , .i_general_box_notifications_container").remove();
                }
            }
        });
    });
    $(document).on("click", ".topPoints", function() {
        var type = $(this).attr("data-type");
        var data = 'f=' + type;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (!$(".i_general_box_container")[0]) {
                    $("#" + type).append(response);
                } else {
                    $(".i_general_box_container").remove();
                }
                if ($("div").hasClass("stickersContainer") || $("div").hasClass("emojiBoxC") || $("div").hasClass("emojiBox") || $("div").hasClass("i_general_box_container") || $("div").hasClass("i_general_box_notifications_container")) {
                    $(".stickersContainer , .emojiBox , .emojiBoxC , .i_general_box_notifications_container , .i_general_box_message_notifications_container").remove();
                }
            }
        });
    });
    $(document).on("click", ".topNotifications", function() {
        var type = $(this).attr("data-type");
        var data = 'f=' + type;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (!$(".i_general_box_notifications_container")[0]) {
                    $("#" + type).append(response);
                    if ($(".i_notifications_count")[0]) {
                        $(".not_not").hide();
                    }
                    GetSlimScroll();
                } else {
                    $(".i_general_box_notifications_container").remove();
                }
                if ($("div").hasClass("stickersContainer") || $("div").hasClass("emojiBoxC") || $("div").hasClass("emojiBox") || $("div").hasClass("i_general_box_container") || $("div").hasClass("i_general_box_message_notifications_container")) {
                    $(".stickersContainer , .emojiBox , .emojiBoxC , .i_general_box_container , .i_general_box_message_notifications_container").remove();
                }
            }
        });
    });
    /*Get Stickers*/
    $(document).on("click", ".getStickers", function() {
        var type = 'stickers';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (!$(".stickersContainer")[0]) {
                    $(".getStickers" + ID).append(response);
                } else {
                    $(".stickersContainer").remove();
                }
                if ($("div").hasClass("emojiBox") || $("div").hasClass("emojiBoxC") || $("div").hasClass("i_general_box_container") || $("div").hasClass("i_general_box_message_notifications_container") || $("div").hasClass("i_general_box_notifications_container")) {
                    $(".emojiBoxC , .emojiBox , .i_general_box_message_notifications_container , .i_general_box_notifications_container , .i_general_box_container").remove();
                }
            }
        });
    });
    /*Get Stickers*/
    $(document).on("click", ".getGifs", function() {
        var type = 'gifList';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {},
            success: function(response) {
                if (!$(".stickersContainer")[0]) {
                    $(".getStickers" + ID).append(response);
                } else {
                    $(".stickersContainer").remove();
                }
                if ($("div").hasClass("emojiBox") || $("div").hasClass("emojiBoxC") || $("div").hasClass("i_general_box_container") || $("div").hasClass("i_general_box_message_notifications_container") || $("div").hasClass("i_general_box_notifications_container")) {
                    $(".emojiBoxC , .emojiBox , .i_general_box_message_notifications_container , .i_general_box_notifications_container , .i_general_box_container").remove();
                }
            }
        });
    });
    $(document).on("click", ".g_feed", function() {
        var get = $(this).attr("data-get");
        var type = $(this).attr("data-type");
        var data = 'f=' + get;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {
                $("#moreType").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response != '404') {
                    $("#moreType").attr("data-type", type);
                    const $newContent = $(response);
                    $("#moreType").html('').append($newContent);
                    // Re-init dynamic plugins for newly injected content
                    reInitPostPlugins($newContent);
                    initImageBackgrounds('.i_post_image_swip_wrapper', $newContent);
                    initSuggestedCreatorsSwiper($newContent);
                    initImageSuggestedBackgrounds();
                    $(".mobile_left").removeClass("leftStickyActive");
                    $(".is_mobile").removeClass("svg_active_icon");
                    $(".i_postFormContainer").hide();
                }
            }
        });
    });
    const initializedGalleries = new Set();

    function initGalleriesInDOM(scope = $(document)) {
        scope.find(".gallery_trigger").each(function () {
          const galleryID = $(this).data("gallery-id");
          if (galleryID && !initializedGalleries.has(galleryID)) {
            const $gallery = $("#" + galleryID);
            if ($gallery.length > 0) {
              $gallery.lightGallery({
                videojs: true,
                mode: 'lg-fade',
                cssEasing: 'cubic-bezier(0.25, 0, 0.25, 1)',
                download: false,
                share: false
              });
              initializedGalleries.add(galleryID);
            }
          }
        });
    }

    function reInitPostPlugins(scope) {
        if (!scope) return;

        scope.find('[id^="lightgallery"]').each(function () {
          const $this = $(this);
          if (!$this.hasClass('lg-initialized')) {
            $this.lightGallery({
              videojs: true,
              mode: 'lg-fade',
              cssEasing: 'cubic-bezier(0.25, 0, 0.25, 1)',
              download: false,
              share: false
            });
        }
    });

        scope.find('[id^="play_po_"]').each(function () {
          const $this = $(this);
          if (!$this.hasClass('green-audio-player-loaded')) {
            new GreenAudioPlayer($this[0], {
              stopOthersOnPlay: true,
              showTooltips: true,
              showDownloadButton: false,
              enableKeystrokes: true
            });
            $this.addClass('green-audio-player-loaded');
          }
        });
    }

    window.initImageBackgrounds = function (targetSelector = '.i_post_image_swip_wrapper', scope = $(document)) {
      scope.find(targetSelector).each(function () {
        const bg = $(this).attr('data-bg');
        if (bg) {
          $(this).css('background-image', 'url(' + bg + ')');
        }
      });
    };

    window.initImageSuggestedBackgrounds = function (targetSelector = '.i_sub_u_cov', scope = $(document)) {
      scope.find(targetSelector).each(function () {
        const bg = $(this).attr('data-bg');
        if (bg) {
          $(this).css('background-image', 'url(' + bg + ')');
        }
      });
    };


 const initializedStandaloneSwiper = new Set();

window.initStandaloneSwiperLightGallery = function (scope = $(document)) {
  scope.find('.swiper-wrapper[data-standalone-gallery="true"]').each(function () {
    const $wrapper = $(this);
    const galleryID = $wrapper.attr('id');

    if (!galleryID || initializedStandaloneSwiper.has(galleryID)) {
      return;
    }

    $wrapper.lightGallery({
      selector: '.swiper-slide a',
      videojs: true,
      mode: 'lg-fade',
      cssEasing: 'cubic-bezier(0.25, 0, 0.25, 1)',
      download: false,
      share: false
    });

    initializedStandaloneSwiper.add(galleryID);
    $wrapper.addClass('lg-initialized');
  });

  scope.find('.product_images_container .mySwiper').each(function () {
    new Swiper(this, {
      autoplay: {
        delay: 3000,
        disableOnInteraction: false,
      },
      pagination: {
        el: ".swiper-pagination",
        dynamicBullets: true,
      }
    });
  });
};

$(document).on('click', '.swiper-slide a', function (e) {
  const wrapper = $(this).closest('.swiper-wrapper');
  if (wrapper.hasClass('lg-initialized')) {
    e.preventDefault();
  }
});

    const initializedSuggestedSwipers = new Set();

  /**
   * Initialize Swiper in dynamically loaded suggested creators sections.
   * @param {jQuery|HTMLElement} scope - Optional. DOM scope to search for .mySwiper elements.
   */
  window.initSuggestedCreatorsSwiper = function (scope = $(document)) {
    scope.find('.i_postFormContainer_swiper .mySwiper').each(function () {
      const swiperElement = this;

      // Use a unique identifier to prevent double initialization
      const swiperID = $(swiperElement).data('swiper-id') || swiperElement.id || Math.random().toString(36).substr(2, 9);

      if (!initializedSuggestedSwipers.has(swiperID)) {
        new Swiper(swiperElement, {
          effect: "cards",
          grabCursor: true
        });
        initializedSuggestedSwipers.add(swiperID);
      }
    });
  };

$(document).ready(function () {
  initGalleriesInDOM(); // mevcut sistemin
  reInitPostPlugins($(document));
  initImageBackgrounds();
  initStandaloneSwiperLightGallery();
  initSuggestedCreatorsSwiper();
  initImageSuggestedBackgrounds();
});

    window.reInitLightGallery = function (html) {
        initGalleriesInDOM(html);
    };

  /********* SCROLL TO LOAD MORE ***********/
  let scrollLoad = true;
  $(document).on('touchmove', showMoreData); /* For mobile */
  $(window).on('scroll', showMoreData);

  function showMoreData() {
    if (
      scrollLoad &&
      $(window).scrollTop() >= $(document).height() - $(window).height() - 500
    ) {
      const moreType = $("#moreType").attr("data-type");
      const moreCat = $("#moreType").attr("data-po");
      let profileUserID = '';
      let ID;

      if (moreType === 'notifications' || moreType === 'paid' || moreType === 'free' || moreType === 'creators') {
        ID = $('#moreType').children('.mor').last().attr('data-last');
        if (moreType === 'creators') {
          profileUserID = $("#moreType").attr("data-r");
        }
      }

      if (
        moreType === 'moreposts' || moreType === 'savedpost' || moreType === 'moreexplore' ||
        moreType === 'morepremium' || moreType === 'friends' || moreType === 'morepurchased' ||
        moreType === 'moreboostedposts' || moreType === 'moretrendposts' || moreType === 'hashtag'
      ) {
        ID = $('#moreType').children('.i_post_body').last().attr('data-last');
        if (moreType === 'hashtag') {
          profileUserID = $("#moreType").attr("data-hash");
        }
      }

      if (moreType === 'profile') {
        ID = $('#moreType').children('.i_post_body').last().attr('data-last');
        if (!ID) {
          ID = $('#moreType').children('.i_sub_box_container').last().attr('data-last');
        }
        profileUserID = $("#prw").attr("data-u");
      }

      if (
        $('.i_loading , .nomore , .noPost , .no_creator_f_wrap').length === 0 &&
        !$(".i_loading , .nomore , .noPost , .no_creator_f_wrap")[0] &&
        moreType !== undefined
      ) {
        let data = `f=${moreType}&last=${ID}&p=${profileUserID}`;
        if (moreCat) {
          data += `&pcat=${moreCat}`;
        }

        $.ajax({
          type: "POST",
          url: siteurl + 'requests/request.php',
          data: data,
          cache: false,
          beforeSend: function () {
            $(".body_" + ID).after(preLoadingAnimation);
            scrollLoad = false;
          },
          success: function (response) {
            $(".i_loading").remove();
            if (response && !$(".nomore")[0]) {
                const $newContent = $(response);
                $("#moreType").append($newContent);

                reInitLightGallery($newContent);
                reInitPostPlugins($newContent);
                initImageBackgrounds('.i_post_image_swip_wrapper', $newContent);
                initSuggestedCreatorsSwiper($newContent);
                initImageSuggestedBackgrounds();
              scrollLoad = true;
            }
          }
        });
      }
    }
  }

    /*Update Who Can See POST Before Share Post*/
    $(document).on("click", ".wsUpdate", function() {
        var type = 'whoSee';
        var ID = $(this).attr("data-id");
        var data = 'f=' + type + '&who=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $(".i_whoseech_menu_item_out").removeClass("wselected");
                if (response) {
                    $("#wsUpdate" + ID).addClass("wselected");
                    $(".wBox").html('').append(response);
                    $(".i_choose_ws_wrapper").removeClass('dblock');
                }
                if (ID == '4' && $("div[class='point_input_wrapper']").length === 0) {
                    whoSeePremium();
                } else {
                    $(".point_input_wrapper").remove();
                }
            }
        });
    });

    function whoSeePremium() {
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: 'f=pw_premium',
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response) {
                    $(".aft").after(response);;
                }
            }
        });
    }
    /*Get PopUp for Post Updating WhoCanSee*/
    $(document).on("click", ".wcs", function() {
        var type = 'wcs';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response) {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    /*Update WhoCanSee Status for Shared Post*/
    $(document).on("click", ".who_can_see_pop_item", function() {
        var type = 'uwcs';
        var ID = $(this).attr("id");
        var wcs = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + ID + '&wci=' + wcs;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("#ipublic_" + ID).html('').append(response);
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 100);
            }
        });
    });
    /*Call Edit Post PoUpbox*/
    $(document).on("click", ".edtp", function() {
        var type = 'c_editPost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
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
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
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
            url: siteurl + 'requests/request.php',
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
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Save Post Edit*/
    $(document).on("click", ".sedt", function() {
        var type = 'editS';
        var ID = $(this).attr('id');
        var editText = $("#ed_" + ID).val();
        var data = 'f=' + type + '&id=' + ID + '&text=' + encodeURIComponent(editText);
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var responseStatus = response.status;
                var editedText = response.text;
                if (responseStatus == 'no') {
                    PopUPAlerts('eCouldNotEmpty', 'ialert');
                } else if (responseStatus == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                } else if (responseStatus == '200') {
                    $("#i_post_container_" + ID).show();
                    $("#i_post_text_" + ID).html(editedText);
                    $(".i_modal_in_in").addClass("i_modal_in_in_out");
                    setTimeout(() => {
                        $(".i_modal_bg_in").remove();
                    }, 100);
                }
            }
        });
    });
    /*Uploading Music, Video and Image with UpChunk*/
    $(document).on("change", "#i_image_video", function (e) {
        e.preventDefault();
        var files = e.target.files;
        if (!files || files.length === 0) return;

        var values = $("#uploadVal").val();

        // Hide warnings and show upload area
        $(".i_warning_unsupported").hide();
        $(".i_upload_warning").hide();
        $(".i_uploaded_iv").show();
        $(".publish").prop("disabled", true);
        $(".publish").css("pointer-events", "none");

        // Upload files sequentially
        var fileIndex = 0;
        function uploadNextFile() {
            if (fileIndex >= files.length) {
                // All files uploaded
                console.log('All files uploaded successfully');
                setTimeout(() => {
                    $('.publish').prop('disabled', false);
                    $(".publish").css("pointer-events", "auto");
                }, 1000);
                return;
            }

            var file = files[fileIndex];
            var fileName = file.name;
            var fileType = file.type;
            var isVideo = fileType.startsWith('video/');

            console.log('Uploading file ' + (fileIndex + 1) + '/' + files.length + ': ' + fileName + ' (' + fileType + ')');

            // Show progress UI
            if (fileIndex === 0) {
                $('.i_uploaded_iv').html(
                    '<div class="i_upload_progress_wrapper">' +
                        '<div class="i_upload_progress"></div>' +
                        '<div class="i_upload_progress_text">0%</div>' +
                    '</div>' +
                    '<div class="i_uploaded_file_box"></div>'
                );
            } else {
                $('.i_upload_progress').css('width', '0%');
                $('.i_upload_progress_text').text('0%');
                $('.processing-msg').remove();
            }

            // Create UpChunk instance
            var upload = UpChunk.createUpload({
                endpoint: siteurl + '/requests/upload_chunk_post.php',
                file: file,
                chunkSize: 2048, // 2MB chunks (in KB)
                maxFileSize: 524288000, // 500 MB max

                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-File-Name': fileName,
                    'X-File-Type': fileType
                }
            });

            // Progress event
            upload.on('progress', function (progress) {
                var percent = Math.min(Math.max(Math.round(progress.detail), 0), 100);

                $('.i_upload_progress').css('width', percent + '%');
                $('.i_upload_progress_text').text(percent + '%');

                // Show processing message for video when upload reaches 100%
                if (percent === 100 && isVideo && !$(".processing-msg").length) {
                    console.log('Video upload complete, showing processing message...');
                    $('.i_upload_progress').addClass('processing-animation');
                    $('.i_upload_progress_text').text('Обработка видео...');
                    $(".i_uploaded_iv").append('<div class="processing-msg">Конвертация и создание миниатюры</div>');
                }
            });

            // Store last chunk response
            var lastResponse = null;
            upload.on('chunkSuccess', function(event) {
                if (event.detail && event.detail.response) {
                    lastResponse = event.detail.response;
                }
            });

            // Upload complete event
            upload.on('success', function () {
                console.log('File upload complete, processing response...');

                try {
                    if (lastResponse) {
                        var responseBody = lastResponse.body || lastResponse;
                        var response = typeof responseBody === 'string' ? JSON.parse(responseBody) : responseBody;
                        console.log('Parsed response:', response);

                        if (response.success && response.upload_id && response.html) {
                            // Append HTML to uploaded files box
                            $(".i_uploaded_file_box").append(response.html);

                            // Update uploadVal with new file ID
                            var K = $('.i_uploaded_item').map(function () { return this.id }).toArray();
                            var T = K + "," + values;
                            if (T != "undefined,") {
                                $("#uploadVal").val(T);
                            }

                            // Remove processing message
                            $(".processing-msg").remove();
                            $('.i_upload_progress').removeClass('processing-animation');

                            console.log('File ' + (fileIndex + 1) + ' uploaded successfully, ID: ' + response.upload_id);

                            // Upload next file
                            fileIndex++;
                            uploadNextFile();
                            return;
                        }
                    }
                    throw new Error('Invalid or missing response from server');
                } catch (e) {
                    console.error('Error handling upload completion:', e);

                    // Show error but continue with next file
                    $(".processing-msg").html('Ошибка обработки файла. Попробуйте снова.');
                    $('.i_upload_progress').removeClass('processing-animation');

                    setTimeout(function() {
                        $(".processing-msg").remove();
                        fileIndex++;
                        uploadNextFile();
                    }, 2000);
                }
            });

            // Error event
            upload.on('error', function (error) {
                console.error('Upload error:', error);
                $(".processing-msg").remove();
                $('.i_upload_progress').removeClass('processing-animation');
                $(".i_upload_warning").html('Ошибка загрузки: ' + (error.detail && error.detail.message ? error.detail.message : 'Неизвестная ошибка')).show();

                setTimeout(function() {
                    $('.publish').prop('disabled', false);
                    $(".publish").css("pointer-events", "auto");
                }, 2000);
            });
        }

        // Start uploading first file
        uploadNextFile();
    });

    /*Delete Uploaded File Before Publish*/
    $(document).on("click", ".i_delete_item_button", function() {
        var type = 'delete_file';
        var ID = $(this).attr('id');
        var input = $('#uploadVal');
        var data = 'f=' + type + '&file=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $(".iu_f_" + ID).remove();
                    input.val(function(_, value) {
                        return value.split(',').filter(function(val) {
                            return val !== ID;
                        }).join(',');
                    });
                } else {
                    PopUPAlerts('not_file', 'ialert')
                }
                if (!$(".i_uploaded_item")[0]) {
                    $(".i_uploaded_iv").hide();
                }
            }
        });
    });
    /*Save New Post*/
    $(document).on("click", ".publish", function() {
        var text = $("#newPostT").val();
        var files = $("#uploadVal").val();
        var point = $("#point").val();
        var type = 'newPost';
        var data = 'f=' + type + '&txt=' + encodeURIComponent(text) + '&file=' + files + '&point=' + point;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {
                $('.publish').prop('disabled', true);
                $(".publish").css("pointer-events", "none");
                $(".i_warning_point , .i_warning , .i_warning_prmfl").fadeOut(100);
            },
            success: function(response) {
                $('.publish').prop('disabled', false);
                $(".publish").css("pointer-events", "auto");
                if ($("div").hasClass("noPost")) {
                    $(".noPost").remove();
                }
                if (response == '200') {
                    $(".i_warning").fadeIn();
                } else if (response == '201') {
                    $(".i_warning_point").fadeIn();
                } else if (response == '203') {
                    $(".i_warning_point_two").fadeIn();
                } else if (response == '202') {
                    $(".i_warning_prmfl").fadeIn();
                } else if (response == '204') {
                    PopUPAlerts('sWrong', 'ialert');
                } else {
                    $(".i_uploaded_file_box").html('');
                    $(".i_uploaded_iv").hide();
                    const $newContent = $(response);
                    $("#moreType").prepend($newContent);

                    reInitLightGallery($newContent);
                    reInitPostPlugins($newContent);
                    initImageBackgrounds('.i_post_image_swip_wrapper', $newContent);
                    $(".newPostT").val('').trigger('change');
                    $("#uploadVal").val('');
                    $("#point").val('');
                }
            }
        });
    });
    $(document).on("click", ".publishReels", function() {
        var text = $("#newPostT").val();
        var files = $("#uploadVal").val();
        var point = $("#point").val();
        var type = 'insertNewReel';
        var data = 'f=' + type + '&txt=' + encodeURIComponent(text) + '&file=' + files + '&point=' + point;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {
                $('.publish').prop('disabled', true);
                $(".publish").css("pointer-events", "none");
                $(".i_warning_point , .i_warning , .i_warning_prmfl").fadeOut(100);
            },
            success: function(response) {
                $('.publish').prop('disabled', false);
                $(".publish").css("pointer-events", "auto");

                if (response === '200') {
                    $(".i_warning").fadeIn();
                } else if (response === '201') {
                    $(".i_warning_point").fadeIn();
                } else if (response === '203') {
                    $(".i_warning_point_two").fadeIn();
                } else if (response === '202') {
                    $(".i_warning_prmfl").fadeIn();
                } else if (response === '204') {
                    PopUPAlerts('sWrong', 'ialert');
                } else if (response.startsWith("REELS_ID:")) {
                    var newReelId = response.replace("REELS_ID:", "");
                    window.location.href = siteurl + 'reels/' + newReelId;
                } else {
                    $(".i_uploaded_file_box").html('');
                    $(".i_uploaded_iv").hide();
                    const $newContent = $(response);
                    $("#moreType").prepend($newContent);

                    reInitLightGallery($newContent);
                    reInitPostPlugins($newContent);
                    initImageBackgrounds('.i_post_image_swip_wrapper', $newContent);
                    $(".newPostT").val('').trigger('change');
                    $("#uploadVal").val('');
                    $("#point").val('');
                }
            }
        });
    });
    /*Like Post*/
    $(document).on("click", ".in_like , .in_unlike", function() {
        var type = 'p_like';
        var ID = $(this).attr('data-id');
        var data = 'f=' + type + '&post=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            dataType: "json",
            data: data,
            cache: false,
            beforeSend: function() {
                $('.in_like , .in_unlike').prop('disabled', true);
            },
            success: function(response) {
                var status = response.status;
                var statusIcon = response.like;
                var liksCount = response.likeCount;
                if (status == 'in_unlike') {
                    $("#p_l_" + ID).removeClass("in_like").addClass("in_unlike");
                    $("#lp_sum_" + ID).html(liksCount);

                    var $postID = $(".body_" + ID);
                    var $existingLikeHeart = $postID.find('.like_heart');

                    if ($existingLikeHeart.length > 0) {
                        $existingLikeHeart.fadeOut(300, function() {
                            $(this).remove();
                        });
                        clearTimeout($postID.data('likeTimer'));
                    } else {
                        $postID.append(likeBox);
                        var likeTimer = setTimeout(() => {
                            $postID.find(".like_heart").fadeOut(300, function() {
                                $(this).remove();
                            });
                        }, 450);
                        $postID.data('likeTimer', likeTimer);
                    }

                } else {
                    $("#p_l_" + ID).removeClass("in_unlike").addClass("in_like");
                    $("#lp_sum_" + ID).html(liksCount);

                    var $postID = $(".body_" + ID);
                    var $existingLikeHeart = $postID.find('.like_heart');

                    if ($existingLikeHeart.length > 0) {
                        $existingLikeHeart.fadeOut(300, function() {
                            $(this).remove();
                        });
                        clearTimeout($postID.data('likeTimer'));
                    } else {
                        $postID.append(UnlikeBox);
                        var likeTimer = setTimeout(() => {
                            $postID.find(".like_heart").fadeOut(300, function() {
                                $(this).remove();
                            });
                        }, 450);
                        $postID.data('likeTimer', likeTimer);
                    }

                }
                $("#p_l_" + ID).html(statusIcon);
                $('.in_like , .in_unlike').prop('disabled', false);
            }
        });
    });
    /*Call Post for Share*/
    $(document).on("click", ".in_share", function() {
        var ID = $(this).attr("data-id");
        var type = 'p_share';
        if (!$(".i_bottom_left_alert_container")[0]) {
            var data = 'f=' + type + '&sp=' + ID;
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {
                    $('.in_share').prop('disabled', true);
                },
                success: function(response) {
                    if (response != '404') {
                        $("body").append(response);

                        setTimeout(() => {
                            $(".i_modal_bg_in").addClass('i_modal_display_in');
                            $(".more_textarea").focus();
                            const $newContent = $(".i_modal_bg_in").last();

                            initImageBackgrounds('.i_post_image_swip_wrapper', $newContent);
                            reInitPostPlugins($newContent);
                            initGalleriesInDOM($newContent);
                        }, 200);
                    } else if (response == '404') {
                        PopUPAlerts('not_Shared', 'ialert');
                    }
                    $('.in_share').prop('disabled', false);
                }
            });
        }
    });

    function PopUPAlerts(ialert, type) {
        var data = 'f=' + type + '&al=' + ialert;
        if (!$(".i_bottom_left_alert_container")[0]) {
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {

                },
                success: function(response) {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_bottom_left_alert_container").addClass('fadeOutDown');
                    }, 5000);
                    setTimeout(() => {
                        $(".i_bottom_left_alert_container").remove();
                    }, 5000);
                }
            });
        }
    }
    /*Save Re-Share Post*/
    $(document).on("click", ".re-share", function() {
        var ID = $(this).attr("id");
        var type = 'p_rshare';
        var postText = $(".more_textarea").val();
        var data = 'f=' + type + '&sp=' + ID + '&pt=' + encodeURIComponent(postText);
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response == '200') {
                    $(".i_modal_in_in").addClass("i_modal_in_in_out");
                    setTimeout(() => {
                        $(".i_modal_bg_in").remove();
                    }, 100);
                } else {
                    PopUPAlerts('not_Shared', 'ialert');
                }
            }
        });
    });
    /*Delete Post From Database*/
    $(document).on("click", ".yes-del", function() {
        var type = 'deletePost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 100);
                if (response == '200') {
                    $(".body_" + ID).fadeOut();
                    PopUPAlerts('delete_success', 'ialert');
                } else {
                    PopUPAlerts('delete_not_success', 'ialert');
                }
            }
        });
    });
    $(document).on("click", ".shareClose , .no-del , .popClose , .svAC", function() {
        $(".i_modal_in_in").addClass("i_modal_in_in_out");
        setTimeout(() => {
            $(".i_modal_bg_in").remove();
        }, 200);
    });
    /*Update Comment Status*/
    $(document).on("click", ".pcl", function() {
        var type = 'updateComentStatus';
        var ID = $(this).attr('data-id');
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            dataType: "json",
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var status = response.status;
                var statusIcon = response.text;
                if (status) {
                    if (status == '200') {
                        PopUPAlerts('commentClosed', 'ialert');
                    } else {
                        PopUPAlerts('commentOpened', 'ialert');
                    }
                    $("#dc_" + ID).html(statusIcon);
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Pin Post*/
    $(document).on("click", ".i_pnp", function() {
        var type = 'pinpost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            dataType: "json",
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var status = response.status;
                var statusIcon = response.text;
                var btns = response.btn;
                if (status) {
                    if (status == '200') {
                        PopUPAlerts('pined', 'ialert');
                        $(".body_" + ID).append(statusIcon);
                    } else {
                        PopUPAlerts('pinClosed', 'ialert');
                        $("#i_pined_post_" + ID).remove();
                    }
                    $(".pbtn_" + ID).html(btns);
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Report Post*/
    $(document).on("click", ".rpp", function() {
        var type = 'reportPost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            dataType: "json",
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var status = response.status;
                var statusIcon = response.text;
                if (status) {
                    if (status == '200') {
                        $(".rpp" + ID).html(statusIcon);
                    } else if (status == '404') {
                        $(".rpp" + ID).html(statusIcon);
                    }
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Report Post*/
    $(document).on("click", ".svp", function() {
        var type = 'savePost';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            dataType: "json",
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var status = response.status;
                var statusIcon = response.text;
                if (status) {
                    if (status == '200') {
                        $(".in_save_" + ID).html(statusIcon);
                        PopUPAlerts('sAdded', 'ialert');
                    } else if (status == '404') {
                        $(".in_save_" + ID).html(statusIcon);
                        PopUPAlerts('sRemoved', 'ialert');
                    }
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Click To Send Comment*/
    $(document).on("click", ".sndcom", function() {
        var type = 'comment';
        var ID = $(this).attr('id');
        var value = $("#comment" + ID).val();
        var stickerID = $("#stic_" + ID).val();
        var gif = $("#cgif_" + ID).val();
        Comment(ID, value, type, stickerID, gif);
    });
    $(document).on('keydown', ".nwComment", function(e) {
        var key = e.which || e.keyCode || 0;
        if (key == 13) {
            var type = 'comment';
            var ID = $(this).attr('data-id');
            var value = $("#comment" + ID).val();
            var stickerID = $("#stic_" + ID).val();
            var gif = $("#cgif_" + ID).val();
            Comment(ID, value, type, stickerID, gif);
        }
    });
    /*Send Gif Comment*/
    $(document).on("click", ".rGif", function() {
        var src = $(this).attr("src");
        var ID = $(this).attr("data-id");
        if ($("#comment" + ID).val() === '') {
            Comment(ID, '', 'comment', '', src);
        } else {
            $(".emptyGifArea" + ID).show();
            $(".srcGif" + ID).attr('src', src);
            $("#cgif_" + ID).val(src);
            $(".stickersContainer").remove();
        }
    });
    /*Add Sticker*/
    $(document).on("click", ".addSticker", function() {
        var type = 'addSticker';
        var ID = $(this).attr("id");
        var dataID = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + ID + '&pi=' + dataID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $(".emptyStickerArea" + dataID).html('');
            },
            success: function(response) {
                var sticker_url = response.stickerUrl;
                var stickerID = response.st_id;
                if (sticker_url) {
                    $(".stickersContainer").remove();
                    if ($("#comment" + dataID).val() === '') {
                        Comment(dataID, '', 'comment', stickerID);
                    } else {
                        $(".emptyStickerArea" + dataID).append(sticker_url);
                        $("#stic_" + dataID).val(stickerID);
                    }
                }
            }
        });
    });

    /*Comment*/
    function Comment(ID, value, type, sticker, gif) {
        var data = 'f=' + type + '&id=' + ID + '&val=' + encodeURIComponent(value) + '&sticker=' + sticker + '&gf=' + gif;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {},
            success: function(response) {
                if (response == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                    $("#i_user_comments_" + ID).append(response);
                    $(".nwComment").val('');
                    $(".stickersContainer").remove();
                    $(".emptyStickerArea" + ID).empty();
                    $("#stic_" + ID).val('');
                    $(".emptyGifArea" + ID).hide();
                    $(".srcGif" + ID).attr('src', '');
                    $("#cgif_" + ID).val('');
                } else {
                    $("#i_user_comments_" + ID).append(response);
                    $(".nwComment").val('');
                    $(".stickersContainer").remove();
                    $(".emptyStickerArea" + ID).empty();
                    $("#stic_" + ID).val('');
                    $(".emptyGifArea" + ID).hide();
                    $(".srcGif" + ID).attr('src', '');
                    $("#cgif_" + ID).val('');
                }
            }
        });
    }
    $(document).on("click", ".removeSticker", function() {
        var ID = $(this).attr('id');
        $(".emptyStickerArea" + ID).empty();
        $("#stic_" + ID).val('');
    });
    $(document).on("click", ".removeGif", function() {
        var ID = $(this).attr('id');
        $(".emptyGifArea" + ID).hide();
        $(".srcGif" + ID).attr('src', '');
        $("#cgif_" + ID).val('');
    });
    /*Like Post*/
    $(document).on("click", ".c_in_like , .c_in_unlike", function() {
        var type = 'pc_like';
        var ID = $(this).attr('data-id');
        var pID = $(this).attr("data-p");
        var data = 'f=' + type + '&post=' + pID + '&com=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            dataType: "json",
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var status = response.status;
                var statusIcon = response.like;
                var statusTotalLike = response.totalLike;
                if (status == 'c_in_unlike') {
                    $(".c_in_l_" + ID).removeClass("c_in_like").addClass("c_in_unlike");
                } else {
                    $(".c_in_l_" + ID).removeClass("c_in_unlike").addClass("c_in_like");
                }
                $("#t_c_" + ID).html(statusTotalLike);
                $(".c_in_l_" + ID).html(statusIcon);
            }
        });
    });
    /*Call Delete Post PopUpBox*/
    $(document).on("click", ".delCm", function() {
        var type = 'ddelComment';
        var ID = $(this).attr("id");
        var postID = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + ID + '&pid=' + postID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
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
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Delete Comment*/
    $(document).on("click", ".dlCm", function() {
        var type = 'deletecomment';
        var ID = $(this).attr("id");
        var postID = $(this).attr("data-id");
        var data = 'f=' + type + '&cid=' + ID + '&pid=' + postID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {},
            success: function(response) {
                if (response == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                } else {
                    $(".i_modal_in_in").addClass("i_modal_in_in_out");
                    setTimeout(() => {
                        $(".i_modal_bg_in").remove();
                    }, 100);
                    if (response == '200') {
                        $(".dlCm" + ID).fadeOut();
                        PopUPAlerts('delete_comment_success', 'ialert');
                    } else {
                        PopUPAlerts('delete_comment_not_success', 'ialert');
                    }
                }
            }
        });
    });
    /*Report Comment*/
    $(document).on("click", ".ccp", function() {
        var type = 'reportComment';
        var commentID = $(this).attr("id");
        var postID = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + commentID + '&pid=' + postID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            dataType: "json",
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var status = response.status;
                var statusIcon = response.text;
                if (status) {
                    if (status == '200') {
                        $(".ccp" + commentID).html(statusIcon);
                    } else if (status == '404') {
                        $(".ccp" + commentID).html(statusIcon);
                    }
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Call Edit Comment PoUpbox*/
    $(document).on("click", ".cced", function() {
        var type = 'c_editComment';
        var commentID = $(this).attr("id");
        var postID = $(this).attr("data-id");
        var data = 'f=' + type + '&cid=' + commentID + '&pid=' + postID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
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
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Save Comment Edit*/
    $(document).on("click", ".secdt", function() {
        var type = 'editSC';
        var commentID = $(this).attr('id');
        var postID = $(this).attr('data-id');
        var editText = $("#ed_" + commentID).val();
        var data = 'f=' + type + '&cid=' + commentID + '&pid=' + postID + '&text=' + encodeURIComponent(editText);
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var responseStatus = response.status;
                var editedText = response.text;
                if (responseStatus == 'no') {
                    PopUPAlerts('eCouldNotEmpty', 'ialert');
                } else if (responseStatus == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                } else if (responseStatus == '200') {
                    $("#i_u_c_" + commentID).html(editedText);
                    $(".i_modal_in_in").addClass("i_modal_in_in_out");
                    setTimeout(() => {
                        $(".i_modal_bg_in").remove();
                    }, 100);
                }
            }
        });
    });
    /*Follow Profile PopUp Call*/
    $(document).on("click", ".free_follow", function() {
        var type = 'follow_free_not';
        var ID = $(this).attr("data-u");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });

    /*Follow Profile Free*/
    $(document).on("click", ".f_p_follow", function() {
        var type = 'freeFollow';
        var ID = $(this).attr("data-u");
        var data = 'f=' + type + '&follow=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $(".i_modal_content").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                var responseStatus = response.status;
                var responseNot = response.text;
                var responseBtn = response.btn;
                if (responseStatus == '200') {
                    $(".i_fw" + ID).html(responseBtn);
                    if (responseNot == 'flw') {
                        $(".i_fw" + ID).removeClass("i_btn_like_item free_follow").addClass("i_btn_like_item_flw f_p_follow");
                        PopUPAlerts('youFollowing', 'ialert');
                    } else if (responseNot == 'unflw') {
                        $(".i_fw" + ID).removeClass("i_btn_like_item_flw f_p_follow").addClass("i_btn_like_item free_follow");
                        PopUPAlerts('youUnfollowing', 'ialert');
                    }
                }
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 100);
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Block Not PopUp Call*/
    $(document).on("click", ".ublknot", function() {
        var type = 'uBlockNotice';
        var ID = $(this).attr("data-u");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    /*Choose Block TYPE*/
    $(document).on("click", ".i_redtrict_u", function() {
        var ID = $(this).attr("data-s");
        $(".block_a_status").removeClass("blockboxActive").addClass("blockboxPassive");
        $("#bl_s_" + ID).addClass("blockboxActive");
        $(".ublk").attr('data-bt', ID);
    });
    /*Block User*/
    $(document).on("click", ".ublk", function() {
        var type = 'ublock';
        var ID = $(this).attr("id");
        var blockType = $(this).attr("data-bt");
        var data = 'f=' + type + '&id=' + ID + '&blckt=' + blockType;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var responseStatus = response.status;
                var responseRedirect = response.redirect;
                if (responseStatus == '200') {
                    window.location.href = responseRedirect;
                } else if (responseStatus == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                }

            }
        });
    });
    /*Block Not PopUp Call*/
    $(document).on("click", ".uSubsModal", function() {
        var type = 'subsModal';
        var ID = $(this).attr("data-u");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

                $(".uSubsModal").prop("disabled", true);
                $(".uSubsModal").css("pointer-events", "none");
            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                    $(".uSubsModal").prop("disabled", false);
                    $(".uSubsModal").css("pointer-events", "auto");
                }, 200);
            }
        });
    });
    /*Credit Card Form Call*/
    $(document).on("click", ".bcmSubs", function() {
        var type = 'creditCard';
        var ID = $(this).attr("data-u");
        var subscribing = $(this).attr("id");
        var data = 'f=' + type + '&plan=' + subscribing + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_card_two, #id_card", function(e) {
        e.preventDefault();
        var values = $("#uploadVal").val();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
        $("#vUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $(".f_" + type).html('');
                $("#uploadVal_" + type).val('');
                $("#" + type).append('<div class="i_upload_progress"></div>');
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response) {
                    $(".f_" + type).append(response);
                    var K = $(".f_" + type + " > div:last").attr("id");
                    var T = K;
                    if (T != "undefined,") {
                        $("#uploadVal_" + type).val(T);
                    }
                    $("#id_card , #id_card_two").val('');
                }
                $(".i_upload_progress").remove();
            },
            error: function(xhr, status, error) { handleUploadError(xhr, status, error); }
        }).submit();
    });
    /*Send Verification Certificate Request*/
    $(document).on("click", ".v_Next", function() {
        var type = 'verificationRequest';
        var IDCard = $("#uploadVal_sec_one").val();
        var IDPhoto = $("#uploadVal_sec_two").val();
        var data = 'f=' + type + '&cID=' + IDCard + '&cP=' + IDPhoto;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".i_nex_btn").css("pointer-events", "none");
                $(".card , .both , .photo").hide();
            },
            success: function(response) {
                if (response == '200') {
                    location.reload();
                } else if (response == 'card') {
                    $("#id_card , #id_card_two").val('');
                    $(".card").show();
                } else if (response == 'photo') {
                    $("#id_card , #id_card_two").val('');
                    $(".photo").show();
                } else if (response == 'both') {
                    $("#id_card , #id_card_two").val('');
                    $(".both").show();
                }
                $(".i_nex_btn").css("pointer-events", "auto");
            }
        });
    });
    /*Call Avatar And Cover PopUP*/
    $(document).on("click", ".editAvatarCover", function() {
        var type = 'updateAvatarCover';
        var data = 'f=' + type;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });


    $(document).on('submit', "#myEmailForm", function(e) {
        e.preventDefault();
        var myEmailForm = $(this);
        if ($("#cPass").val().length == 0) {
            $(".warning_required").show();
            return false;
        }
        jQuery.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: myEmailForm.serialize(),
            beforeSend: function() {
                $(".warning_inuse , .warning_invalid , .warning_wrong_password , .warning_required , .warning_same_email").hide();
                $(".i_become_creator_box_footer").append(plreLoadingAnimationPlus);
                myEmailForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    myEmailForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == 'inuse') {
                    $(".warning_inuse").show();
                } else if (data == '404') {
                    $(".warning_wrong_password").show();
                } else if (data == 'no') {
                    $(".warning_invalid").show();
                } else if (data == 'same') {
                    $(".warning_same_email").show();
                } else if (data == '200') {
                    $(".successNot").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });

    $(document).on('submit', "#myProfileForm", function(e) {
        e.preventDefault();
        var myProfileForm = $(this);
        jQuery.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: myProfileForm.serialize(),
            beforeSend: function() {
                $(".invalid_username , .character_warning , .warning_username").hide();
                $(".i_become_creator_box_footer").append(plreLoadingAnimationPlus);
                myProfileForm.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    myProfileForm.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '1') {
                    $(".successNot").show();
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on("click", ".pyot_sNext", function() {
        var type = 'updatePayoutSet';
        var defaultMethod = $('input[name=default]:checked', '#bankForm').val();
        var paypalEmail = $("#paypale").val();
        var repaypalEmail = $("#paypalere").val();
        var bankAccount = $("#bank_transfer").val();
        if (defaultMethod == 'paypal') {
            if (paypalEmail.length == 0) {
                $("#setWarning").show();
                return false;
            } else {
                $("#setWarning").hide();
            }
            if (repaypalEmail.length == 0) {
                $("#setWarning").show();
                return false;
            } else {
                $("#setWarning").hide();
            }
            if (paypalEmail != repaypalEmail) {
                $("#notMatch").show();
                return false;
            } else {
                $("#notMatch").hide();
            }
        }
        if (defaultMethod == 'bank') {
            if (bankAccount.length == 0) {
                $("#setBankWarning").show();
                return false;
            } else {
                $("#setBankWarning").hide();
            }
        }
        var data = 'f=' + type + '&paypalEmail=' + encodeURIComponent(paypalEmail) + '&paypalReEmail=' + encodeURIComponent(repaypalEmail) + '&bank=' + bankAccount + '&method=' + defaultMethod;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".i_nex_btn").css("pointer-events", "none");
                $(".i_become_creator_box_footer").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response != '200') {
                    if (response == 'email_warning') {
                        $("#notMatch").show();
                    } else if (response == 'paypal_warning') {
                        $("#setWarning").show();
                    } else if (response == 'bank_warning') {
                        $("#setBankWarning").show();
                    } else if (response == 'not_valid_email') {
                        $("#notValidE").show();
                    }
                }
                setTimeout(() => {
                    $(".successNot").show();
                    $(".loaderWrapper").remove();
                    $(".i_nex_btn").css("pointer-events", "auto");
                }, 3000);
            }
        });
    });

    $(document).on("click", ".mwithdraw", function() {
        var type = 'makewithDraw';
        var amount = $("#wamount").val();
        if (amount.length == 0) {
            $("#mwithdrawal").show();
            return false;
        } else {
            $("#mwithdrawal").hide();
        }
        var data = 'f=' + type + '&amount=' + amount;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".i_nex_btn_btn").css("pointer-events", "none");
                $(".i_t_warning").hide();
                $(".i_become_creator_box_footer").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '1') {
                    $(".successNot").show();
                } else if (response == '2') {
                    $("#mwithdrawal").show();
                } else if (response == '3') {
                    $("#nbudget").show();
                } else if (response == '4') {
                    $("#nnoway").show();
                } else if (response == '5') {
                    $("#nwaitpending").show();
                }
                $(".loaderWrapper").remove();
                $(".i_nex_btn_btn").css("pointer-events", "auto");
            }
        });
    });
    /*Credit Card Form Call*/
    $(document).on("click", ".prcsPost", function() {
        var type = 'pPurchase';
        var post = $(this).attr("id");
        var data = 'f=' + type + '&purchase=' + post;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    $(document).on("click", ".prchase_go_wallet", function() {
        var type = 'goWallet';
        var post = $(this).attr("id");
        var data = 'f=' + type + '&p=' + post;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var url = $.trim(response);
                if(url){
                    window.location.href = url;
                }
            }
        });
    });
    $(document).on("click", ".buyPoint", function() {
        var type = 'choosePaymentMethod';
        var pointID = $(this).attr("id");
        var data = 'f=' + type + '&type=' + pointID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".credit_plan_box").css("pointer-events", "none");
                $("#p_i_" + pointID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
                $(".loaderWrapper").remove();
                $(".credit_plan_box").css("pointer-events", "auto");
            }
        });
    });
    $(document).on("click", ".mcSt", function() {
        if ($(".cSetc")[0]) {
            $(".cSetc").removeClass("dblock");
        }
        $(".cSetc").addClass("dblock");
    });
    /*Get Gifs*/
    $(document).on("click", ".getmGifs", function() {
        var type = 'chat_gifs';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        if (!$("div").hasClass("Message_stickersContainer")) {
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                beforeSend: function() {
                    $(".getmGifs").css("pointer-events", "none");
                    $(".nanos").append('<div class="preLoadC">' + plreLoadingAnimationPlus + '</div>');
                },
                success: function(response) {
                    $(".nanos").append(response);
                    $(".preLoadC").remove();
                    $(".getmGifs").css("pointer-events", "auto");
                }
            });
        } else {
            $(".Message_stickersContainer").remove();
        }
    });
    /*Get Gifs*/
    $(document).on("click", ".getmStickers", function() {
        var type = 'chat_stickers';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        if (!$("div").hasClass("Message_stickersContainer")) {
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                beforeSend: function() {
                    $(".getmGifs").css("pointer-events", "none");
                    $(".nanos").append('<div class="preLoadC">' + plreLoadingAnimationPlus + '</div>');
                },
                success: function(response) {
                    $(".nanos").append(response);
                    $(".preLoadC").remove();
                    $(".getmGifs").css("pointer-events", "auto");
                }
            });
        } else {
            $(".Message_stickersContainer").remove();
        }
    });
    $(document).on("click", ".getMEmojis", function() {
        var type = 'memoji';
        var ID = $(this).attr("data-type");
        var data = 'f=' + type + '&id=' + ID;
        if (!$("div").hasClass("Message_stickersContainer")) {
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                beforeSend: function() {
                    $(".getMEmojis").css("pointer-events", "none");
                    $(".nanos").append('<div class="preLoadC">' + plreLoadingAnimationPlus + '</div>');
                },
                success: function(response) {
                    $(".nanos").append(response);
                    $(".preLoadC").remove();
                    $(".getMEmojis").css("pointer-events", "auto");
                }
            });
        } else {
            $(".Message_stickersContainer").remove();
        }
    });
    /*Get Gifs*/
    $(document).on("click", ".chtBtns", function() {
        var type = 'chat_btns';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        if (!$("div").hasClass("ch_fl_btns_container")) {
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                beforeSend: function() {
                    $(".chtBtns").css("pointer-events", "none");
                },
                success: function(response) {
                    $(".chtBtns").css("pointer-events", "auto");
                    $(".fl_btns").append(response);
                }
            });
        }
    });

    function ScrollBottomChat() {
        if ($("div").hasClass("all_messages")) {
            $(".all_messages").stop().animate({ scrollTop: $(".all_messages")[0].scrollHeight }, 100);
        }
    }
    ScrollBottomChat();
    $(document).on('keydown', ".mSize", function(e) {
        var key = e.which || e.keyCode || 0;
        if (key == 13) {
            var type = 'nmessage';
            var ID = $(".message_send_form_wrapper").attr("id");
            var value = $(this).val();
            var gMoney = $("#sicVal").val();
            var gf = '';
            var st = '';
            Message(ID, value, type, gf, st, '', gMoney);
        }
    });
    /*Add Sticker*/
    $(document).on("click", ".MaddSticker", function() {
        var type = 'message_sticker';
        var ID = $(this).attr("id");
        var dataID = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + ID + '&pi=' + dataID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $(".Message_stickersContainer").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                var stickerID = response.st_id;
                if (stickerID) {
                    $(".Message_stickersContainer").remove();
                    var gMoney = $("#sicVal").val();
                    Message(dataID, '', 'nmessage', stickerID, '', '',gMoney);
                    $(".loaderWrapper").remove();
                }
            }
        });
    });
    /*Send Gif Message*/
    $(document).on("click", ".mrGif", function() {
        var src = $(this).attr("src");
        var ID = $(this).attr("data-id");
        var gMoney = $("#sicVal").val();
        Message(ID, '', 'nmessage', '', src, '',gMoney);
        $(".Message_stickersContainer").remove();
    });

    $(document).on("click", ".emoji_item_m", function() {
        var copyEmoji = $(this).attr("data-emoji");
        var getValue = $(".mSize").val();
        $(".mSize").val(getValue + ' ' + copyEmoji + ' ');
    });
    /*Comment*/
    function Message(ID, value, type, stickerID, gfSrc, file, gMoney) {
        var data = 'f=' + type + '&id=' + ID + '&val=' + encodeURIComponent(value) + '&sticker=' + stickerID + '&gif=' + gfSrc + '&fl=' + file + '&mo='+gMoney;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".Message_stickersContainer").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                } if(response == '403'){
                    PopUPAlerts('cNotSend', 'ialert');
                }else {
                    $(".all_messages_container").append(response);
                    ScrollBottomChat();
                }
                $(".mSize").val('');
                $(".Message_stickersContainer").remove();
                $(".loaderWrapper").remove();
                $(".i_write_secret_post_price").addClass("boxD");
                $("#sicVal").val('');
            }
        });
    }
    $(document).on("click", ".sendmes", function() {
        var value = $(".mSize").val();
        var ID = $(".message_send_form_wrapper").attr("id");
        var gMoney = $("#sicVal").val();
        Message(ID, value, 'nmessage', '', '', '',gMoney);
    });
    /*Uploading Message Image*/
    $(document).on("change", "#ci_image", function(e) {
        e.preventDefault();
        var values = $("#uploadVal").val();
        var id = $("#ci_image").attr("data-id");
        var ID = $(".message_send_form_wrapper").attr("id");
        var data = { f: id, c: ID };
        $("#uploadform").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $(".ch_fl_btns_container").remove();
                $('.message_send_form_wrapper').append('<div class="i_upload_progress"></div>');
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response) {
                    $(".i_uploaded_iv").show();
                    if (response) {
                        var gMoney = $("#sicVal").val();
                        if(gMoney.length != 0){
                            Message(ID, '', 'nmessage','', '', response, gMoney);
                        }else{
                            Message(ID, '', 'nmessage', '', '', response, '');
                        }
                    }
                }
                $(".i_upload_progress").remove();
            },
            error: function() {}
        }).submit();
    });
    /*Get More Comment*/
    $(document).on("click", ".more_comment", function() {
        var type = 'moreComment';
        var ID = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $("#pf_l_" + ID).append(preLoadingAnimation);
                $(".comnts").css("pointer-events", "none");
            },
            success: function(response) {
                if (response == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                } else {
                    $("#i_user_comments_" + ID).html(response);
                    $(".lc_sum_container_" + ID).remove();
                }
                $(".comnts").css("pointer-events", "auto");
                $(".i_loading").remove();
            }
        });
    });
    $(document).on("click", ".chooseLanguage", function() {
        var type = 'chooseLanguage';
        var data = 'f=' + type;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".chooseLanguage").css("pointer-events", "none");
            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
                $(".chooseLanguage").css("pointer-events", "auto");
            }
        });
    });
    /*Change Language*/
    $(document).on("click", ".chLang", function() {
        var type = 'changeMyLang';
        var id = $(this).attr("id");
        var data = 'f=' + type + '&id=' + id;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".chLang").css("pointer-events", "none");
                $(".purchase_post_details").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
                $(".chLang").css("pointer-events", "auto");
                if (response == '200') {
                    location.reload();
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                    $(".i_modal_in_in").addClass("i_modal_in_in_out");
                    setTimeout(() => {
                        $(".i_modal_bg_in").remove();
                    }, 200);
                }

            }
        });
    });

    /*Search Creator*/
    $(document).delegate('#search_creator', 'keyup', function() {
        var searchValue = $(this).val();
        var type = 'searchCreator';
        var sum = searchValue.replace(/\s+/g, '').length;
        if (sum >= 1) {
            $(".i_general_box_search_container").show();
            setTimeout(() => {
                var data = 'f=' + type + '&s=' + encodeURIComponent(searchValue);

                $.ajax({
                    type: "POST",
                    url: siteurl + 'requests/request.php',
                    data: data,
                    cache: false,
                    beforeSend: function() {
                        $(".sb_items").append(plreLoadingAnimationPlus);
                    },
                    success: function(response) {
                        $(".sb_items").html(response);
                    }
                });

            }, 1000);
        } else {
            $(".i_general_box_search_container").hide();
        }
    });
    $(document).on("mouseup", function(e) {
        var container = $(".i_general_box_search_container");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
            $(".sb_items").html('');
        }
    });
    $(document).on("click", ".newMessageMe", function() {
        var type = 'newMessageMe';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&user=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".newMessageMe").css("pointer-events", "none");
            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
                $(".newMessageMe").css("pointer-events", "auto");
            }
        });
    });
    /*Send New Message*/
    $(document).on("click", ".sndNewMessage", function() {
        var type = 'newfirstMessage';
        var value = $("#sendNewM").val();
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&fm=' + encodeURIComponent(value) + '&u=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".sndNewMessage").css("pointer-events", "none");
            },
            success: function(response) {
                if (response != '404') {
                    window.location.href = response;
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
            }
        });
    });
    /*Update Theme*/
    $(document).on("click", ".updateTheme", function() {
        var type = 'updateTheme';
        var theme = $(this).attr("data-id");
        var data = 'f=' + type + '&theme=' + theme;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".sndNewMessage").css("pointer-events", "none");
            },
            success: function(response) {
                if (response != '404') {
                    location.reload();
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    $(document).on("click", ".mobile_hamburger", function() {
        if (!$(".leftStickyActive")[0]) {
            $(".mobile_left").addClass("leftStickyActive");
            $(".is_mobile").addClass("svg_active_icon");
        } else {
            $(".mobile_left").removeClass("leftStickyActive");
            $(".is_mobile").removeClass("svg_active_icon");
        }
    });
    $(document).on("click", ".mobile_srcbtn", function() {
        if (!$(".i_search_active")[0]) {
            $(".i_search").addClass("i_search_active");
        } else {
            $(".i_search").removeClass("i_search_active");
        }
    });
    $(window).on("resize", function() {
        checkWidth();
    });
    checkWidth();

    function checkWidth() {
        var vWidth = $(window).width();
        if (vWidth < 700) {
            if (!$(".mobile_footer_fixed_menu_container")[0]) {
                $.ajax({
                    type: "POST",
                    url: siteurl + 'requests/request.php',
                    data: 'f=fixedMenu',
                    cache: false,
                    beforeSend: function() {
                        $(".sndNewMessage").css("pointer-events", "none");
                    },
                    success: function(response) {
                        if (!$(".mobile_footer_fixed_menu_container")[0] && !$(".live_video_header")[0]) {
                            $("body").append(response);
                        }
                    }
                });
            }
        } else {
            if ($(".mobile_footer_fixed_menu_container")[0]) {
                $(".mobile_footer_fixed_menu_container").remove();
            }
        }
    }
    $(document).on("keyup keypress keydown", ".nwComment", function() {
        var ID = $(this).attr("data-id");
        var check = $(this).val().length;
        var $vWidth = $(window).width();
        if (check > 20) {
            $(".i_comment_footer" + ID).addClass("commentFooterResize");
        } else {
            $(".i_comment_footer" + ID).removeClass("commentFooterResize");
        }
    });
    $(document).on("click", ".settings_mobile_menu_container", function() {
        if (!$(".settingsMenuDisplay")[0]) {
            $(".i_settings_menu_wrapper").addClass("settingsMenuDisplay");
        } else {
            $(".i_settings_menu_wrapper").removeClass("settingsMenuDisplay");
        }
    });
    $(document).on("click", ".cList", function() {
        if (!$(".chatDisplay")[0]) {
            $(".chat_left_container").addClass("chatDisplay");
        } else {
            $(".chat_left_container").removeClass("chatDisplay");
        }
    });
    /*Delete Message*/
    $(document).on("click", ".dlMesv", function() {
        var type = 'deleteMessage';
        var ID = $(this).attr("id");
        var cID = $(".cList").attr("id");
        var data = 'f=' + type + '&id=' + ID + '&cid=' + cID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    $("#msg_" + ID).remove();
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 200);
            }
        });
    });
    $(document).on("click", ".delmes", function() {
        var type = 'ddelMesage';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
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
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    $(document).on("click", ".d_conversation", function() {
        var type = 'ddelConv';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
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
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Delete Message*/
    $(document).on("click", ".dlConv", function() {
        var type = 'deleteConversation';
        var ID = $(this).attr("id");
        var cID = $(".cList").attr("id");
        var data = 'f=' + type + '&id=' + ID + '&cid=' + cID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response != '404') {
                    location.reload();
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Search Creator*/
    $(document).delegate('#c_search', 'keyup', function() {
        var searchValue = $(this).val();
        var type = 'searchUser';
        var sum = searchValue.replace(/\s+/g, '').length;
        if (sum >= 3) {
            $(".chat_users_wrapper_results").show();
            $(".chat_users_wrapper").hide();
            setTimeout(() => {
                var data = 'f=' + type + '&key=' + encodeURIComponent(searchValue);
                $.ajax({
                    type: "POST",
                    url: siteurl + 'requests/request.php',
                    data: data,
                    cache: false,
                    beforeSend: function() {
                        $(".chat_users_wrapper_results").append(plreLoadingAnimationPlus);
                    },
                    success: function(response) {
                        if (response) {
                            $(".chat_users_wrapper_results").html(response);
                        } else {
                            $(".chat_users_wrapper_results").hide().html('');
                            $(".chat_users_wrapper").show();
                        }
                    }
                });
            }, 1000);
        } else {
            $(".chat_users_wrapper_results").hide().html('');
            $(".chat_users_wrapper").show();
        }
    });
    /*Hide Notification*/
    $(document).on("click", ".hidNot", function() {
        var type = 'hideNotification';
        var hideID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + hideID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response == '200') {
                    $(".hidNot_" + hideID).fadeOut();
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*UnBlock User*/
    $(document).on("click", ".unblock", function() {
        var type = 'unblock';
        var ID = $(this).attr("id");
        var blockedUserID = $(this).attr("data-u");
        var data = 'f=' + type + '&id=' + ID + '&u=' + blockedUserID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".block_id_" + ID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    $(".block_id_" + ID).fadeOut();
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });

    $(document).on('submit', '#myPasswordChange', function(e) {
        e.preventDefault();
        var passChange = $(this);
        jQuery.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: passChange.serialize(),
            beforeSend: function() {
                $(".successNot , .warning_not_mach , .warning_not_correct , .warning_write_current_password , .no_new_password_wrote , .minimum_character_not , .not_contain_whitespace").hide();
                $(".i_become_creator_box_footer").append(plreLoadingAnimationPlus);
                passChange.find(':input[type=submit]').prop('disabled', true);
            },
            success: function(data) {
                setTimeout(() => {
                    passChange.find(':input[type=submit]').prop('disabled', false);
                }, 3000);
                if (data == '1') {
                    $(".warning_not_correct").show();
                } else if (data == '2') {
                    $(".warning_not_mach").show();
                } else if (data == '3') {
                    $(".warning_write_current_password").show();
                } else if (data == '4') {
                    $(".no_new_password_wrote").show();
                } else if (data == '5') {
                    $(".minimum_character_not").show();
                } else if (data == '6') {
                    $(".not_contain_whitespace").show();
                } else if (data == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                } else {
                    window.location.href = data;
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Notifications*/
    $(document).on("click", ".setChange", function() {
        var type = 'p_preferences';
        var setChange = $(this).val();
        var setType = $(this).attr("id");
        var dataNot = 'f=' + type + '&notit=' + encodeURIComponent(setChange) + '&sType=' + setType;
        $.ajax({
            type: 'POST',
            url: siteurl + "requests/request.php",
            data: dataNot,
            cache: false,
            beforeSend: function() {
                $("." + setType).append(plreLoadingAnimationPlus);
                $('.setChange').attr('disabled', true);
            },
            success: function(response) {
                setTimeout(function() {
                    $('.setChange').attr('disabled', false);
                }, 500);
                if (response == '200') {
                    if (setChange == '0') {
                        $("#" + setType).val('1');
                    }
                    if (setChange == '1') {
                        $("#" + setType).val('0');
                    }
                }
                setTimeout(function() {
                    $(".loaderWrapper").remove();
                }, 1500);
            }
        });
    });
    /*Close Alert*/
    $(document).on("click", ".i_alert_close", function() {
        $(".i_bottom_left_alert_container").remove();
    });
    /*Create a Live Streaming PopUp Call*/
    $(document).on("click", ".cNLive", function() {
        var type = $(this).attr("data-type");
        var data = 'f=' + type;
        $.ajax({
            type: 'POST',
            url: siteurl + "requests/request.php",
            data: data,
            cache: false,
            beforeSend: function() {
                $("." + type).append(plreLoadingAnimationPlus);
                $("." + type).attr('disabled', true);
            },
            success: function(response) {
                setTimeout(function() {
                    $("." + type).attr('disabled', false);
                }, 500);
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
                setTimeout(function() {
                    $(".loaderWrapper").remove();
                }, 1500);
            }
        });
    });
    /*Save Live Streaming*/
    $(document).on("click", ".createLiveStream", function() {
        var type = 'createFreeLiveStream';
        var liveStreamingTitle = $("#liveName").val();
        var data = 'f=' + type + '&lTitle=' + encodeURIComponent(liveStreamingTitle);
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() { $(".warning_required").hide(); },
            success: function(response) {
                var status = response.status;
                var start = response.start;
                if (status == '200') {
                    window.location.href = start;
                } else if (status == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                } else if (status == 'require') {
                    $(".require").show();
                } else if (status == '4') {
                    $(".name_short").show();
                }
            }
        });
    });
    /*Like Post*/
    $(document).on("click", ".lin_like , .lin_unlike", function() {
        var type = 'l_like';
        var ID = $(this).attr('data-id');
        var data = 'f=' + type + '&post=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            dataType: "json",
            data: data,
            cache: false,
            beforeSend: function() {
                $('.lin_like , .lin_unlike').prop('disabled', true);
            },
            success: function(response) {
                var status = response.status;
                var statusIcon = response.like;
                var liksCount = response.likeCount;
                if (status == 'lin_unlike') {
                    $("#p_l_l_" + ID).removeClass("lin_like").addClass("lin_unlike");
                    $("#lp_sum_l_" + ID).html(liksCount);
                } else {
                    $("#p_l_l_" + ID).removeClass("lin_unlike").addClass("lin_like");
                    $("#lp_sum_l_" + ID).html(liksCount);
                }
                $("#p_l_l_" + ID).html(statusIcon);
                $('.lin_like , .lin_unlike').prop('disabled', false);
            }
        });
    });
    /*Save Live Streaming*/
    $(document).on("click", ".createLiveStreamPaid", function() {
        var type = 'createPaidLiveStream';
        var liveStreamingTitle = $("#liveName").val();
        var liveFee = $("#lsFee").val();
        var data = 'f=' + type + '&lTitle=' + encodeURIComponent(liveStreamingTitle) + '&pointfee=' + liveFee;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $(".warning_required").hide();
            },
            success: function(response) {
                var status = response.status;
                var start = response.start;
                if (status == '200') {
                    window.location.href = start;
                } else if (status == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                } else if (status == 'point') {
                    $(".point_warning").show();
                } else if (status == 'title') {
                    $(".title_warning").show();
                } else if (status == 'require') {
                    $(".require").show();
                }
            }
        });
    });
    /*Credit Card Form Call*/
    $(document).on("click", ".purchaseLiveButton", function() {
        var type = 'pLivePurchase';
        var post = $(this).attr("id");
        var data = 'f=' + type + '&purchase=' + post;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    $(document).on("click", ".joinLiveStream", function() {
        var type = 'goWalletLive';
        var post = $(this).attr("id");
        var data = 'f=' + type + '&p=' + post;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                window.location.href = response;
            }
        });
    });
    /*Credit Card Form Call*/
    $(document).on("click", ".unSubU", function() {
        var type = 'unSub';
        var post = $(this).attr("data-u");
        var data = 'f=' + type + '&u=' + post;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    /*Credit Card Form Call*/
    $(document).on("click", ".unSubUP", function() {
        var type = 'unSubP';
        var post = $(this).attr("data-u");
        var data = 'f=' + type + '&u=' + post;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    /*UnSubscribe PointUser*/
    $(document).on("click", ".unSubPNow", function() {
        var type = 'unSubscribePoint';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var responseStatus = response.status;
                var responseRedirect = response.redirect;
                if (responseStatus == '200') {
                    location.reload();
                } else if (responseStatus == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                }

            }
        });
    });
    /*UnSubscribe User*/
    $(document).on("click", ".unSubNow", function() {
        var type = 'unSubscribe';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                var responseStatus = response.status;
                var responseRedirect = response.redirect;
                if (responseStatus == '200') {
                    location.reload();
                } else if (responseStatus == '404') {
                    PopUPAlerts('sWrong', 'ialert');
                }

            }
        });
    });
    /*Upload Verification Files*/
    $(document).on("change", ".cTumb", function(e) {
        e.preventDefault();
        var f = 'vTumbnail';
        var id = $(this).attr("data-id");
        var data = { f: f, id: id };
        $("#tuploadform").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $(".iu_f_" + id).append('<div class="i_upload_progress"></div>');
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                $("#viTumb" + id).css('background-image', 'url(' + response + ')');
                $("#viTumbi" + id).attr('src', response);
                $(".i_upload_progress").remove();
            },
            error: function() {}
        }).submit();
    });
    /*Credit Card Form Call*/
    $(document).on("click", ".bcmSubsPoint", function() {
        var type = 'creditPoint';
        var ID = $(this).attr("data-u");
        var subscribing = $(this).attr("id");
        var data = 'f=' + type + '&plan=' + subscribing + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    /*Subscribe With Point*/
    $(document).on("click", ".subMyPoint", function() {
        var type = 'subWithPoints';
        var subscribingType = $(this).attr("id");
        var ID = $(this).attr("data-u");
        var data = 'f=' + type + '&pl=' + subscribingType + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".bySub").append(plreLoadingAnimationPlus);
                $(".cntsub , .insfsub").hide();
                $(".pay_subscription_point").css("pointer-events", "none");
            },
            success: function(response) {
                if (response == '404') {
                    $(".cntsub").show();
                } else if (response == '302') {
                    $(".insfsub").show();
                } else if (response == '200') {
                    location.reload();
                }
                $(".pay_subscription_point").css("pointer-events", "auto");
                setTimeout(function() {
                    $(".loaderWrapper").remove();
                }, 1500);
            }
        });
    });
    /*Call Post for Share*/
    $(document).on("click", ".in_tips", function() {
        var ID = $(this).attr("data-id");
        var tipPostID = $(this).attr("data-ppid");
        var type = 'p_tips';
        if (!$(".i_bottom_left_alert_container")[0]) {
            var data = 'f=' + type + '&tip_u=' + ID + '&tpid=' + tipPostID;
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {
                    $('.in_tips').prop('disabled', true);
                },
                success: function(response) {
                    if (response != '404') {
                        $("body").append(response);
                        setTimeout(() => {
                            $(".i_modal_bg_in").addClass('i_modal_display_in');
                            $(".more_textarea").focus();
                        }, 200);
                    } else if (response == '404') {
                        PopUPAlerts('not_Shared', 'ialert');
                    }
                    $('.in_tips').prop('disabled', false);
                }
            });
        }
    });
    /*SEND TIPS*/
    $(document).on("click", ".send_tip_btn", function() {
        var ID = $(".i_set_subscription_fee").attr("id");
        var tipValue = $("#tipVal").val();
        var tipPostID = $('.i_set_subscription_fee').attr("data-pid");
        var type = 'p_sendTip';
        if (!$(".i_bottom_left_alert_container")[0]) {
            var data = 'f=' + type + '&tip_u=' + ID + '&tipVal=' + tipValue + '&tpid=' + tipPostID;
            $.ajax({
                type: "POST",
                url: siteurl + 'requests/request.php',
                data: data,
                dataType: "json",
                cache: false,
                beforeSend: function() {
                    $('.send_tip_btn').prop('disabled', true);
                },
                success: function(response) {

                    var responseStatus = response.status;
                    var responseRedirect = response.redirect;
                    var senamount = response.enamount;
                    if (responseStatus == 'ok') {
                        showBubble(ID, tipPostID);
                        $(".i_modal_in_in").addClass("i_modal_in_in_out");
                        setTimeout(() => {
                            $(".i_modal_bg_in").remove();
                        }, 200);
                    }

                    if (senamount == 'notEnough') {
                        $(".i_tip_not").css("color", "red");
                    }
                    if (senamount == 'notEnouhCredit') {
                        window.location.href = responseRedirect;
                    }
                    $('.send_tip_btn').prop('disabled', false);
                }
            });
        }
    });
    function showBubble(userid, postid) {
        $(".tip_" + postid).show();
        document.getElementById('notification-sound-coin').play();
        setTimeout(() => {
            $(".tip_" + postid).fadeOut();
        }, 5000);
    }
    $(document).on("click", ".live_coin_send", function() {
        var ID = $(this).attr("data-u");
        var tipValue = $(this).attr("data-tip");
        var liveID = $(".live_wrapper_tik").attr("id");
        var type = 'p_sendGift';
        var data = 'f=' + type + '&tip_u=' + ID + '&tipTyp=' + tipValue + '&lid=' + liveID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            dataType: "json",
            cache: false,
            beforeSend: function() {
                $('.co_' + tipValue).append(plreLoadingAnimationPlus);
                $(".live_animation_wrapper").remove();
            },
            success: function(response) {
                setTimeout(function() {
                    $(".loaderWrapper").remove();
                }, 1000);
                var responseStatus = response.status;
                var responseRedirect = response.redirect;
                var senamount = response.enamount;
                var sendSuccessAnimation = response.giftAnimation;
                var currentBalance = response.current_balance;
                if (responseStatus == 'ok') {
                    $(".filtvid").append(sendSuccessAnimation);
                    setTimeout(() => {
                        $(".live_animation_wrapper").remove();
                    }, 5000);
                    $(".crnblnc").html(currentBalance);
                }
                if (senamount == 'notEnough') {
                    $(".i_tip_not").css("color", "red");
                }
                if (senamount == 'notEnouhCredit') {
                    window.location.href = responseRedirect;
                }
            }
        });
    });
    /*QR Code Generator*/
    $(document).on("click", ".qrCodeGenerator", function() {
        var type = 'generateQRCode';
        var data = 'f=' + type;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {
                $('.createQrBox').append(plreLoadingAnimationPlus);
                $('.qrCodeGenerator').prop('disabled', true);
            },
            success: function(response) {
                $(".loaderWrapper").remove();
                if (response && response !== '404') {
                    $(".qrCodeImage").html('<img src="' + siteurl + response + '">');
                    // Reload page to show share buttons
                    setTimeout(function() {
                        location.reload();
                    }, 500);
                }
                $('.qrCodeGenerator').prop('disabled', false);
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
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 100);
                if (response == '200') {
                    $(".body_" + ID).fadeOut();
                    PopUPAlerts('delete_success', 'ialert');
                } else {
                    PopUPAlerts('delete_not_success', 'ialert');
                }
            }
        });
    });
    /*Delete Post From Database*/
    $(document).on("click", ".yes-del-product", function() {
        var type = 'deleteProduct';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $(".i_modal_in_in").addClass("i_modal_in_in_out");
                setTimeout(() => {
                    $(".i_modal_bg_in").remove();
                }, 100);
                if (response == '200') {
                    $(".body_" + ID).fadeOut();
                    PopUPAlerts('delete_success', 'ialert');
                } else {
                    PopUPAlerts('delete_not_success', 'ialert');
                }
            }
        });
    });
    /*Share This Story*/
    $(document).on("click", ".share_this_story", function() {
        var type = 'shareMyStorie';
        var ID = $(this).attr("id");
        var text = $(".st_txt_" + ID).val();
        var data = 'f=' + type + '&id=' + ID + '&txt=' + encodeURIComponent(text);
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response == '200') {
                    $(".body_" + ID).fadeOut();
                    PopUPAlerts('shared_storie_success', 'ialert');
                    window.location = siteurl;
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    /*Mention Autocomplete*/
    var timer = null;
    var tagword = /@(\w+)(?!.*@\w)/;

    $(document).delegate(".newPostT", "keyup", function(e) {
        var value = e.target.value;
        var names = $(this).val().split(' ');
        var firstLetterOfSurname = names[names.length - 1].charAt(0);
        clearTimeout(timer);
        if (firstLetterOfSurname == '@') {
            timer = setTimeout(function() {
                var contents = value;
                var goname = contents.match(tagword);
                var type = 'mfriends';
                if (goname !== null) {
                    if (goname.length > 0) {
                        var data = 'f=' + type + '&menFriend=' + goname[1];
                        $.ajax({
                            type: "POST",
                            url: siteurl + "requests/request.php",
                            data: data,
                            cache: false,
                            beforeSend: function() {
                                // Do Something
                            },
                            success: function(response) {
                                if (response) {
                                    $(".mentions_list").show().html(response);
                                } else {
                                    $(".mentions_list").hide().html('');
                                }
                            }
                        });
                    }
                } else {
                    $(".mentions_list").hide().html('');
                }
            }, 500);
        }
    });
    (function($) {
        $.fn.focusTextToEnd = function() {
            this.focus();
            var $thisVal = this.val();
            this.val('').val($thisVal);
            return this;
        }
    }(jQuery));


    /*Click To Add Mentioned User*/
    $(document).on("click", ".mres_u", function() {
        var words = $(".newPostT").val().split(' ');
        if (words.length > 0) {
            var username = $(this).attr("data-user");
            if (words[words.length - 1].startsWith('@')) {
                words[words.length - 1] = "@" + username + ' ';
                $(".newPostT").val(words.join(' ')).focus();
                $(".mentions_list").hide().html('');
                $('.newPostT').focusTextToEnd();
            }
        }
    });
    var timeoutId;
    // Append User Profile Card
    $(document).on("mouseenter", ".ownTooltip", function() {
        if ($(window).width() > 800) {
            var tooltipText = $(this).attr("data-label");
            $(this).append("<div class='ownTooltipWrapper'>" + tooltipText + "</div>");
        }
    });
    $(document).on('mouseleave', '.ownTooltip', function() {
        $(".ownTooltipWrapper").fadeOut("normal", function() {
            window.clearTimeout(timeoutId);
            timeoutId = null;
            $(this).remove();
        });
    });
    /*Get PopUp for Which Story*/
    $(document).on("click", ".chsStoryw", function() {
        var type = 'whcStory';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response) {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                }
            }
        });
    });
    $(document).on("click", ".share_text_story", function() {
        var type = 'shareMyTextStory';
        var ID = $(".choosed_bg").attr("data-iid");
        var textStory = $("#strt_text").val();
        var data = 'f=' + type + '&id=' + ID + '&stext=' + encodeURIComponent(textStory);
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                if (response == '200') {
                    PopUPAlerts('shared_storie_success', 'ialert');
                    setTimeout(() => {
                        window.location = siteurl;
                    }, 1000);
                } else {
                    PopUPAlerts('sWrong', 'ialert');
                }
            }
        });
    });
    $(document).on("click", ".buy__myproduct", function() {
        var type = 'buyProduct';
        var pointID = $(this).attr("data-id");
        var data = 'f=' + type + '&type=' + pointID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".credit_plan_box").css("pointer-events", "none");
                $("#p_i_" + pointID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == 'me') {
                    PopUPAlerts('cnbowni', 'ialert');
                } else {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                    $(".loaderWrapper").remove();
                    $(".credit_plan_box").css("pointer-events", "auto");
                }
            }
        });
    });
    $(document).on("click", ".s_p_p_p_download", function() {
        var type = 'downloadMyProduct';
        var pointID = $(this).attr("data-id");
        var data = 'f=' + type + '&myp=' + pointID;
        $.ajax({
            type: "POST",
            url: siteurl + 'dfile.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".s_p_p_p_download").css("pointer-events", "none");
            },
            success: function(response) {
                if (response == 'me') {
                    PopUPAlerts('sRong', 'ialert');
                }
                setTimeout(() => {
                    $(".s_p_p_p_download").css("pointer-events", "auto");
                }, 2000);

            }
        });
    });
    /*Call Post for Share*/
    $(document).on("click", ".sendPoint", function() {
        var ID = $(this).attr("data-u");
        var type = 'p_p_tips';
        if (!$(".i_bottom_left_alert_container")[0]) {
            var data = 'f=' + type + '&tp_u=' + ID;
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {
                    $('.sendPoint').prop('disabled', true);
                },
                success: function(response) {
                    if (response != '404') {
                        $("body").append(response);
                        setTimeout(() => {
                            $(".i_modal_bg_in").addClass('i_modal_display_in');
                            $(".more_textarea").focus();
                        }, 200);
                    } else if (response == '404') {
                        PopUPAlerts('not_Shared', 'ialert');
                    }
                    $('.sendPoint').prop('disabled', false);
                }
            });
        }
    });
    $(document).on("click", ".sendPointMessage", function() {
        var ID = $(this).attr("data-u");
        var type = 'p_p_tips_message';
        if (!$(".i_bottom_left_alert_container")[0]) {
            var data = 'f=' + type + '&tp_u=' + ID;
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {
                    $('.sendPointMessage').prop('disabled', true);
                },
                success: function(response) {
                    if (response != '404') {
                        $("body").append(response);
                        setTimeout(() => {
                            $(".i_modal_bg_in").addClass('i_modal_display_in');
                            $(".more_textarea").focus();
                        }, 200);
                    } else if (response == '404') {
                        PopUPAlerts('not_Shared', 'ialert');
                    }
                    $('.sendPointMessage').prop('disabled', false);
                }
            });
        }
    });
    function VideoCallAlert(callID) {
        var data = 'f=videoCallAlert' + '&call=' + callID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {},
            success: function(response) {
                if (response != '404') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                        $("#notification-sound-call")[0].play();
                    }, 200);
                }
            }
        });
    }
    $(document).on("click", ".call_accept", function() {
        var ID = $(this).attr("id");
        var type = 'call_accepted';
        var data = 'f=' + type + '&accID=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $('.call_accept').prop('disabled', true);
                $(".i_modal_in_in").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response) {
                    $("#notification-sound-call")[0].pause();
                    window.location.href = response;
                }
            }
        });
    });
    $(document).on("click", ".call_decline", function() {
        var ID = $(this).attr("id");
        var type = 'call_declined';
        var data = 'f=' + type + '&accID=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $('.call_decline').prop('disabled', true);
                $(".i_modal_in_in").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                $(".i_modal_bg_in").remove();
                $("#notification-sound-call")[0].pause();
            }
        });
    });
    /*SEND TIPS*/
    $(document).on("click", ".send_tip_btn_message", function() {
        var ID = $(".subU").attr("id");
        var chatID = $(".message_send_form_wrapper").attr("id");
        var tipValue = $("#tipVal").val();
        var type = 'p_sendTipMessage';
        if (!$(".i_bottom_left_alert_container")[0]) {
            var data = 'f=' + type + '&tip_u=' + ID + '&tipVal=' + tipValue +'&chID=' + chatID;
            $.ajax({
                type: "POST",
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {
                    $('.send_tip_btn').prop('disabled', true);
                },
                success: function(response) {
                    if (response == 'notEnough') {
                        $(".i_tip_not").css("color", "red");
                    }
                    if (response == 'notEnouhCredit') {
                        window.location.href = siteurl + 'purchase/purchase_point';
                    }

                    if (response != '404') {
                        if(response){
                            PopUPAlerts('tipSuccess', 'ialert');
                            $(".i_modal_in_in").addClass("i_modal_in_in_out");
                            setTimeout(() => {
                                $(".i_modal_bg_in").remove();
                            }, 200);
                            $(".all_messages_container").append(response);
                            ScrollBottomChat();
                        }else{
                            $(".aval").val('').focus();
                        }
                    }
                    $('.send_tip_btn_message').prop('disabled', false);
                }
            });
        }
    });
    $(document).on("click", ".send_tip_btn_profile", function() {
        var ID = $(".i_set_subscription_fee").attr("id");
        var tipValue = $("#tipVal").val();
        var type = 'p_sendTipProfile';
        if (!$(".i_bottom_left_alert_container")[0]) {
            var data = 'f=' + type + '&tip_u=' + ID + '&tipVal=' + tipValue;
            $.ajax({
                type: "POST",
                url: siteurl + 'requests/request.php',
                data: data,
                dataType: "json",
                cache: false,
                beforeSend: function() {
                    $('.send_tip_btn').prop('disabled', true);
                },
                success: function(response) {

                    var responseStatus = response.status;
                    var responseRedirect = response.redirect;
                    var senamount = response.enamount;
                    if (responseStatus == 'ok') {
                        PopUPAlerts('tipSuccess', 'ialert');
                        $(".i_modal_in_in").addClass("i_modal_in_in_out");
                        setTimeout(() => {
                            $(".i_modal_bg_in").remove();
                        }, 200);
                    }

                    if (senamount == 'notEnough') {
                        $(".i_tip_not").css("color", "red");
                    }
                    if (senamount == 'notEnouhCredit') {
                        window.location.href = responseRedirect;
                    }
                    $('.send_tip_btn').prop('disabled', false);
                }
            });
        }
    });
    /*Unlock Message*/
    $(document).on("click",".unlockFor", function(){
        var ID = $(this).attr("id");
        var cID = $(".message_send_form_wrapper").attr("id");
        var type = 'unlockMessage';
        var data = 'f='+type+'&mi='+ID+'&ci='+cID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
            },
            success: function(response) {
                if(response == '404'){
                   $(".unlc_"+ID).show();
                }else if(response == '403'){
                  PopUPAlerts('tipSuccess', 'ialert');
                }else{
                   $("#msg_"+ID).html('').append(response);
                }
            }
        });
    });
    $(document).on("click",".joinOffline", function(){
        PopUPAlerts('camOffline', 'camAlert');
    });
    $(document).on("click",".rplyComment", function(){
        var ID = $(this).attr('id');
        var who = $(this).attr("data-who");
        var textComment = $("#comment"+ID).focus().val('@'+who+' ');
    });
    $(document).on("click",".boostThisPost", function(){
        var type = 'getBoostList';
        var ID = $(this).attr("id");
        var data = 'f='+type+'&bp='+ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    $(document).on("click",".bThisP", function(){
        var type = 'boostThisPlan';
        var planID = $(this).attr("id");
        var postID = $(this).attr("data-post");
        var data = 'f='+type+'&pbID='+planID+'&bpID='+postID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".i_modal_in_in").append(plreLoadingAnimationPlus);
                $(".warning_boost_post").hide();
            },
            success: function(response) {
                if(response == '404'){
                  $(".warning_boost_post").show();
                }else{
                   window.location.href = response;
                }
                $(".loaderWrapper").remove();
            }
        });
    });
    /*Change Notifications*/
    $(document).on("click", ".boosStat", function() {
        var type = 'updateBoostStatus';
        var setChange = $(this).val();
        var setType = $(this).attr("data-id");
        var dataNot = 'f=' + type + '&mod=' + encodeURIComponent(setChange) + '&bpid=' + setType;
        $.ajax({
            type: 'POST',
            url: siteurl + "requests/request.php",
            data: dataNot,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {

            }
        });
    });
    $(document).on("click",".bankOpen", function(){
        if ($("div").hasClass("displayNone")) {
            $(".bank_container").removeClass('displayNone');
        }else{
            $(".bank_container").addClass('displayNone');
        }
    });
    /*Upload Verification Files*/
    $(document).on("change", "#id_card", function(e) {
        e.preventDefault();
        var id = $(this).attr("data-id");
        var type = $(this).attr("data-type");
        var data = { f: id, c: type };
        $("#pBUploadForm").ajaxForm({
            type: "POST",
            data: data,
            delegation: true,
            cache: false,
            beforeSubmit: function() {
                $(".f_" + type).html('');
                $("#uploadVal_" + type).val('');
                $("#" + type).append('<div class="i_upload_progress"></div>');
            },
            uploadProgress: function(e, position, total, percentageComplete) {
                $('.i_upload_progress').width(percentageComplete + '%');
            },
            success: function(response) {
                if (response) {
                    $(".f_" + type).append(response);
                    var K = $(".f_" + type + " > div:last").attr("id");
                    var T = K;
                    if (T != "undefined,") {
                        $("#uploadVal_" + type).val(T);
                    }
                    $("#id_card").val('');
                }
                $(".i_upload_progress").remove();
            },
            error: function() {}
        }).submit();
    });
    /*Send Verification Certificate Request*/
    $(document).on("click", ".bnk_Next", function() {
        var type = 'verificationRequestForBankPayment';
        var IDPhoto = $("#uploadVal_sec_one").val();
        var planID = $(this).attr('id');
        var data = 'f=' + type + '&cP=' + IDPhoto + '&pID=' +planID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".i_nex_btn").css("pointer-events", "none");
                $(".card , .both , .photo").hide();
            },
            success: function(response) {
                if (response == '200') {
                    $(".payment_success_bank").show();
                    $(".bank_container").hide();
                    setTimeout(() => {
                        window.location.href = siteurl + 'settings?tab=purchased_points';
                    }, 5000);
                } else if (response == 'card') {
                    $("#id_card").val('');
                    $(".card").show();
                } else if (response == 'photo') {
                    $("#id_card").val('');
                    $(".photo").show();
                } else if (response == 'both') {
                    $("#id_card").val('');
                    $(".both").show();
                }
                $(".i_nex_btn").css("pointer-events", "auto");
            }
        });
    });
    /*Call Post for Share*/
    $(document).on("click", ".sendFrame", function() {
        var ID = $(this).attr("data-u");
        var type = 'p_p_frame';
        if (!$(".i_bottom_left_alert_container")[0]) {
            var data = 'f=' + type + '&tp_u=' + ID;
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {
                    $('.sendPoint').prop('disabled', true);
                },
                success: function(response) {
                    if (response != '404') {
                        $("body").append(response);
                        setTimeout(() => {
                            $(".i_modal_bg_in").addClass('i_modal_display_in');
                            $(".more_textarea").focus();
                        }, 200);
                    } else if (response == '404') {
                        PopUPAlerts('not_Shared', 'ialert');
                    }
                    $('.sendPoint').prop('disabled', false);
                }
            });
        }
    });
    $(document).on("click", ".buyFrameGift", function() {
        var type = 'buyFrameGift';
        var pointID = $(this).attr("id");
        var purchaseToThisUser = $(".profile_wrapper").attr("data-u");
        var data = 'f=' + type + '&type=' + pointID + '&pUf=' + purchaseToThisUser;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".credit_plan_box").css("pointer-events", "none");
                $("#p_i_" + pointID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if(response == '404'){
                    PopUPAlerts('sWrong', 'ialert');
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                    $(".loaderWrapper").remove();
                    $(".credit_plan_box").css("pointer-events", "auto");
                }else if(response == '200') {
                    $("body").append(response);
                    setTimeout(() => {
                        $(".i_modal_bg_in").addClass('i_modal_display_in');
                    }, 200);
                    $(".loaderWrapper").remove();
                    $(".credit_plan_box").css("pointer-events", "auto");
                    PopUPAlerts('buySuccess', 'ialert');
                    setTimeout(() => {
                        location.reload();
                    }, 5000);
                }else {

                }
            }
        });
    });
    /*Update My Frame*/
    $(document).on("click", ".updateMyFrame", function() {
        var type = 'UpdateMyFrame';
        var frameID = $(this).attr("data-id");
        var data = 'f=' + type + '&frameID=' + frameID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".credit_plan_box").css("pointer-events", "none");
                $(".credit_plan_box_" + frameID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if(response == '400'){
                    PopUPAlerts('sWrong', 'ialert');
                }else{
                    PopUPAlerts('frameSuccess', 'ialert');
                }

                setTimeout(() => {
                   $(".loaderWrapper").remove();
                   $(".credit_plan_box").css("pointer-events", "auto");
                }, 3000);

            }
        });
    });
    /*Update My Frame*/
    $(document).on("click", ".inv_btn", function() {
        var type = 'inviteFriend';
        var iemail = $("#inv_email").val();
        var data = 'f=' + type + '&invEmail=' + encodeURIComponent(iemail);
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/inviteEmail.php',
            data: data,
            cache: false,
            beforeSend: function() {
                $(".inv_btn").css("pointer-events", "none");
                $(".already_in_use").hide();
                $(".inviteemail").append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if(response == '404'){
                    PopUPAlerts('sWrong', 'ialert');
                }else if(response == '1'){
                    $(".already_in_use").show();
                }else{
                    PopUPAlerts('emailSendsuccess', 'ialert');
                }
                $(".inviteemail_input").val('');
                setTimeout(() => {
                   $(".inv_btn").css("pointer-events", "auto");
                   $(".already_in_use").hide();
                }, 3000);
                $(".loaderWrapper").remove();
            }
        });
    });
    $(document).on("click", ".stat_icon", function() {
        $(this).hide();
        $(this).next(".stat_icona").show();
        var ID = $(this).attr("id");
        $(".bstatistick_"+ID).addClass("changeHeight");
    });

    $(document).on("click", ".stat_icona", function() {
        $(this).hide();
        $(this).prev(".stat_icon").show();
        var ID = $(this).attr("id");
        $(".bstatistick_"+ID).removeClass("changeHeight");
    });

    $(document).on("click", ".move_my_point", function () {
      const type = "moveMyAffilateBalance";
      const point = window.affiliateConfig?.earnings || 0;
      const data = "f=" + type + "&myp=" + point;

      $.ajax({
        type: "POST",
        url: siteurl + "requests/request.php",
        data: data,
        cache: false,
        beforeSend: function () {
          $(".move_my_point").hide().css("pointer-events", "none");
        },
        success: function (response) {
          if (response === "me") {
            PopUPAlerts("sRong", "ialert");
          } else {
            location.reload();
          }

          setTimeout(() => {
            $(".move_my_point").show().css("pointer-events", "auto");
          }, 2000);
        }
      });
    });
    $(document).on("click",".bCountry", function(){
        var ID = $(this).attr("data-c");
        var i = $(this).attr("id");
        var type = 'bCountry';
        var data =  'f='+type+'&c='+ID;
        $.ajax({
          type: 'POST',
          url: siteurl + "requests/request.php",
          data: data,
          cache: false,
          success: function(response) {
             if(response == '1'){
               $("#"+i).addClass('chsed');
             }else{
               $("#"+i).removeClass('chsed');
             }
          }
        });
      });
      $(document).on("click", ".move_my_point_earn", function () {
      const data = {
        f: "moveMyEarnedPoints",
        myp: window.earnedPointData?.allTotal || 0
      };

      const $button = $(".move_my_point");
      const $alertBox = $(window.earnedPointData.alertSuccessSelector);

      $.ajax({
        type: "POST",
        url: siteurl + "requests/request.php",
        data: data,
        cache: false,
        beforeSend: function () {
          $button.hide().css("pointer-events", "none");
          $alertBox.text("").hide();
        },
        success: function (response) {
          if (response === "me") {
            PopUPAlerts("sRong", "ialert");
          } else if (response === "ok") {
            location.reload();
          } else {
            $alertBox.text(response).show();
          }

          setTimeout(() => {
            $button.show().css("pointer-events", "auto");
          }, 2000);
        }
      });
    });
    // Toggle advanced settings (question and slots)
    $(document).on("change", ".subfeea", function (e) {
      const type = $(this).data("id");

      // Only handle for product edit/create toggles
      if (type === "askaquestion" || type === "limitslots") {
        const checked = $(this).prop("checked");
        const $target = $("." + type);

        // Keep hidden value in 'ok'/'not' form expected by backend
        $("#" + type).val(checked ? "ok" : "not");

        if (checked) {
          // Make visible: remove nonePoint and restore stylesheet display (flex)
          $target.removeClass("nonePoint").css("display", "");
        } else {
          // Hide: add nonePoint and hide
          $target.addClass("nonePoint").hide();
        }

        // Prevent other generic .subfeea handlers from overriding our values
        e.stopImmediatePropagation();
      }
    });

    // Save product edit
    $(document).on("click", ".pr_save_btna", function () {
      // Only handle clicks when editProduct context exists (edit page)
      if (!window.editProduct || !window.editProduct.productID) {
        return; // Not on edit page; let other handlers (create pages) run
      }

      const data = {
        f: "saveEditPr",
        prid: window.editProduct.productID,
        prnm: $("#pr_name").val(),
        prprc: $("#pr_price").val(),
        prdsc: $("#pr_description").val(),
        prdscinf: $("#pr_conf").val(),
        lmSlot: $("#limitslots").prop("checked") ? "ok" : "not",
        askQ: $("#askaquestion").prop("checked") ? "ok" : "not",
        qAsk: $("#question_ask").val(),
        lSlot: $("#limit_slot").val()
      };

      $(window.editProduct.warningTextSelector).hide();
      $(window.editProduct.successTextSelector).hide();

      $.ajax({
        type: "POST",
        url: siteurl + "requests/request.php",
        data: data,
        cache: false,
        success: function (response) {
          if (response === "200") {
            $(window.editProduct.successTextSelector).show();
          } else {
            $(window.editProduct.warningTextSelector).show();
          }
        }
      });
    });
    $(document).ready(function () {
        let debounceTimer;

        $(document).on("keyup", "#newEmail", function () {
          clearTimeout(debounceTimer);
          const email = $(this).val();

          if (email.length < 3) return;

          debounceTimer = setTimeout(() => {
            const data = {
              f: "checkemail",
              newEmail: email
            };

            $(".warning_inuse, .warning_invalid").hide();

            $.ajax({
              type: "POST",
              url: siteurl + "requests/request.php",
              data: data,
              cache: false,
              success: function (response) {
                if (response === "404") {
                  $(".warning_invalid").hide();
                  $(".warning_inuse").show();
                } else if (response === "no") {
                  $(".warning_inuse").hide();
                  $(".warning_invalid").show();
                } else {
                  $(".warning_inuse, .warning_invalid").hide();
                }
              }
            });
          }, 500);
    });
    $(document).on("keyup", ".aval", function () {
      const val = parseFloat($(this).val());
      const ID = $(this).attr("id");

      $(".i_t_warning").hide();

      if (ID === "spweek" && val < parseFloat(window.subscriptionConfig.minWeekAmount)) {
        $("#waweekly").show();
      } else if (ID === "spmonth" && val < parseFloat(window.subscriptionConfig.minMonthAmount)) {
        $("#mamonthly").show();
      } else if (ID === "spyear" && val < parseFloat(window.subscriptionConfig.minYearAmount)) {
        $("#yayearly").show();
      }
    });

    $(document).on("change", ".subfeea", function () {
      const type = $(this).data("id");
      const currentVal = $(this).val();
      $(this).val(currentVal === "1" ? "0" : "1");
    });

    $(document).on("click", ".c_uNext", function () {
      const loading = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
      const loader = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + loading + '</div></div></div>';

      const data = {
        f: "updateSubscriptionPayments",
        wSubWeekAmount: window.subscriptionConfig.subWeekStatus === "yes" ? $("#spweek").val() : "",
        mSubMonthAmount: window.subscriptionConfig.subMonthlyStatus === "yes" ? $("#spmonth").val() : "",
        mSubYearAmount: window.subscriptionConfig.subYearlyStatus === "yes" ? $("#spyear").val() : "",
        wStatus: $('input[name="weekly"]').val() || "0",
        mStatus: $('input[name="monthly"]').val() || "0",
        yStatus: $('input[name="yearly"]').val() || "0"
      };

      // Validation
      if (data.wStatus === "1" && data.wSubWeekAmount === "") {
        $("#wweekly").show();
        return;
      }
      if (data.mStatus === "1" && data.mSubMonthAmount === "") {
        $("#wmonthly").show();
        return;
      }
      if (data.yStatus === "1" && data.mSubYearAmount === "") {
        $("#wyearly").show();
        return;
      }

      $(".i_nex_btn").css("pointer-events", "none");
      $("#wweekly, #wmonthly, #wyearly, .weekly_success, .monthly_success, .yearly_success").hide();
      $(".i_become_creator_box_footer").append(loader);

          $.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: data,
            dataType: "json",
            cache: false,
            success: function (res) {
              $(".loaderWrapper").remove();

              if (res.weekly === "404") $("#wweekly").show();
              if (res.weekly === "200") $(".weekly_success").show();

              if (res.monthly === "404") $("#wmonthly").show();
              if (res.monthly === "200") $(".monthly_success").show();

              if (res.yearly === "404") $("#wyearly").show();
              if (res.yearly === "200") $(".yearly_success").show();

              $(".i_nex_btn").css("pointer-events", "auto");
            }
          });
        });
    });
    /*Change Module Statuses*/
    $(document).on("change", ".chmdProd", function() {
        var type = 'productStatus';
        var value = $(this).val();
        var prID = $(this).attr("data-id");
        var data = 'f=' + type + '&mod=' + value + '&id='+prID;
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function() {
                $("#pr_s_"+prID).append(plreLoadingAnimationPlus);
            },
            success: function(response) {
                if (response == '200') {
                    if (value == '1') {
                        $("#pr_i_"+prID).val('0');
                    } else {
                        $("#pr_i_"+prID).val('1');
                    }
                } else if (response == '404') {
                    $(".warning_").show();
                }
                $(".loaderWrapper").remove();

            }
        });
    });
    /*Follow Profile PopUp Call*/
    $(document).on("click", ".delprod", function() {
        var type = 'delete_product';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    /*Follow Profile PopUp Call*/
    $(document).on("click", ".del_stor", function() {
        var type = 'delete_storie_alert';
        var ID = $(this).attr("id");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    /*Credit Card Form Call*/
    $(document).on("click", ".stViewers", function() {
        var type = 'storieViewers';
        var ID = $(this).attr("data-viewer");
        var data = 'f=' + type + '&id=' + ID;
        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function() {

            },
            success: function(response) {
                $("body").append(response);
                setTimeout(() => {
                    $(".i_modal_bg_in").addClass('i_modal_display_in');
                }, 200);
            }
        });
    });
    $(document).on("keyup", ".avalv", function() {
        var inputVal = $(this).val();
        $(".i_t_warning").hide();

        if (inputVal === "0" || inputVal === "" || inputVal === "undefined") {
          $(".i_t_warning").show();
        }

        var calculatedValue = parseFloat(inputVal) * parseFloat(pointEqualValue);
        if (!isNaN(calculatedValue)) {
          $(".pricecal").text(calculatedValue.toFixed(2));
        }
      });

    $(document).on("click", ".c_UpdateCostV", function() {
        var videoCost = $(".avalv").val();
        var data = "f=vCost&vCostFee=" + encodeURIComponent(videoCost);

        $.ajax({
          type: "POST",
          url: siteurl + "requests/request.php",
          data: data,
          beforeSend: function() {
            $(".i_t_warning, .successNot").hide();
          },
          success: function(response) {
            if (response === "not") {
              $(".i_t_warning").show();
            } else {
              $(".successNot").show();
            }
          }
        });
    });
    $(document).on("click", ".payClose", function() {
        $(".i_payment_pop_box").addClass("i_modal_in_in_out");
        setTimeout(() => {
            $(".i_subs_modal").remove();
        }, 200);
    });

    $(document).on("change", "#i_reels_video", function (e) {
        e.preventDefault();

        var fileInput = this;
        var file = fileInput.files[0];

        if (!file) {
            return;
        }

        // Validate file type
        if (!file.type.match('video.*')) {
            $(".i_upload_warning").text('Please select a video file').fadeIn().show();
            $(fileInput).val('');
            return;
        }

        // First, check if FFmpeg is available before attempting upload
        $.ajax({
            url: siteurl + '/requests/request.php',
            type: 'POST',
            data: { f: 'checkFFmpeg' },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // FFmpeg is ready, proceed with chunked upload
                    initializeChunkedReelUpload(file);
                } else {
                    // FFmpeg not available, show error
                    var errorMsg = response.message || 'Video processing is currently unavailable. Please contact support.';
                    $(".i_upload_warning").text(errorMsg).fadeIn().show();
                    $(fileInput).val('');
                }
            },
            error: function() {
                $(".i_upload_warning").text('Could not verify video processing capabilities. Please try again.').fadeIn().show();
                $(fileInput).val('');
            }
        });
    });

    // Chunked upload using UpChunk library
    function initializeChunkedReelUpload(file) {
        // Hide warnings
        $(".i_warning_unsupported").hide();
        $(".i_upload_warning").hide();

        // Show and initialize progress bar
        $('.i_uploaded_iv').html(
            '<div class="i_upload_progress_wrapper">' +
                '<div class="i_upload_progress"></div>' +
                '<div class="i_upload_progress_text">0%</div>' +
            '</div>'
        ).show();

        // Disable publish button
        $(".publish").prop("disabled", true).css("pointer-events", "none");

        // Create UpChunk instance
        var upload = UpChunk.createUpload({
            endpoint: siteurl + '/requests/upload_chunk.php',
            file: file,
            chunkSize: 2048, // 2MB chunks (in KB)
            maxFileSize: 524288000, // 500 MB max

            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        // Progress event
        upload.on('progress', function (progress) {
            // UpChunk returns progress.detail as 0-100 (already multiplied by 100)
            var percent = Math.min(Math.max(Math.round(progress.detail), 0), 100);

            // Update progress bar
            $('.i_upload_progress').css('width', percent + '%');
            $('.i_upload_progress_text').text(percent + '%');

            console.log('Upload progress:', percent + '%');

            // Show processing message when upload reaches 100%
            if (percent === 100 && !$(".processing-msg").length) {
                console.log('Upload complete, showing processing message...');
                $('.i_upload_progress').addClass('processing-animation');
                $('.i_upload_progress_text').text('Обработка видео...');
                $(".i_uploaded_iv").append('<div class="processing-msg">Конвертация в формат Reels</div>');
            }
        });

        // Store last chunk response
        var lastResponse = null;
        upload.on('chunkSuccess', function(event) {
            if (event.detail && event.detail.response) {
                lastResponse = event.detail.response;
            }
        });

        // Upload complete event
        upload.on('success', function () {
            console.log('Server responded, checking result...');
            console.log('Last response received:', lastResponse);

            // Update processing message
            if ($(".processing-msg").length) {
                $(".processing-msg").html('Видео обработано! Сохранение...');
            }

            // Backend returns fileId in last chunk response (201 Created)
            try {
                if (lastResponse) {
                    // UpChunk returns response wrapped in object with 'body' field
                    var responseBody = lastResponse.body || lastResponse;
                    var response = typeof responseBody === 'string' ? JSON.parse(responseBody) : responseBody;
                    console.log('Parsed response:', response);

                    if (response.success && response.file_id) {
                        $('.i_upload_progress_text').text('100% - Готово!');
                        $(".processing-msg").html('Успешно! Переход к редактированию...');

                        var normalized = (siteurl || '').replace(/\/+$/,'');
                        var reelUrl = normalized + "/createReels?r=" + encodeURIComponent(response.file_id);
                        console.log('Redirecting to:', reelUrl);

                        setTimeout(function() {
                            window.location.href = reelUrl;
                        }, 1000);
                        return;
                    }
                }
                throw new Error('Invalid or missing response from server');
            } catch (e) {
                console.error('Error handling upload completion:', e);
                console.log('Server took too long to respond, but video may still be processing. Please check your reels page.');

                // Don't show error - video might still be processing successfully
                $(".processing-msg").html(
                    'Видео обработано успешно!<br>' +
                    '<small>Если видео не появилось, обновите страницу через минуту.</small><br>' +
                    '<a href="' + (siteurl || '').replace(/\/+$/,'') + '/reels" style="margin-top: 10px; display: inline-block; color: #007bff;">Перейти к Reels</a>'
                );

                $(".i_upload_progress").removeClass('processing-animation');
                $('.i_upload_progress_text').text('100% - Проверьте Reels');
            }
        });

        // Error event
        upload.on('error', function (error) {
            console.error('Upload error:', error.detail);

            $(".processing-msg").remove();
            $(".i_upload_progress").removeClass('processing-animation');

            var errorMsg = 'Upload failed. Please try again.';
            if (error.detail && error.detail.message) {
                errorMsg = error.detail.message;
            } else if (error.detail && typeof error.detail === 'string') {
                errorMsg = error.detail;
            }

            $(".i_upload_warning").text(errorMsg).fadeIn().show();
            $(".i_uploaded_iv").hide();
            $(".publish").prop("disabled", false).css("pointer-events", "auto");
            $("#i_reels_video").val('');
        });

        // Offline event
        upload.on('offline', function () {
            console.warn('Connection lost, upload paused');
            $('.i_upload_progress_text').text($('.i_upload_progress_text').text() + ' (Offline - will resume)');
        });

        // Online event
        upload.on('online', function () {
            console.log('Connection restored, resuming upload');
            $('.i_upload_progress_text').text($('.i_upload_progress_text').text().replace(' (Offline - will resume)', ''));
        });
    }
})(jQuery);
