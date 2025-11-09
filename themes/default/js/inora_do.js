(function ($) {
    "use strict";
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
    // Preloader animations
    const preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
    const plreLoadingAnimationPlus = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';

    /**
     * Open post action menu
     */
    $(document).on("click", ".openPostMenu", function () {
        const id = $(this).attr("id");
        $(".mnoBox" + id).addClass("dblock");
    });

    /**
     * AJAX Login Form Submit
     */
    $(document).on('submit', "#ilogin", function (e) {
        e.preventDefault();
        const form = $("#ilogin");

        $.ajax({
            type: "POST",
            url: siteurl + "requests/login.php",
            data: form.serialize(),
            success: function (response) {
                const resp = (typeof response === 'string') ? response.trim() : '';
                if (resp === "go_inside") {
                    // Refresh or redirect to ensure fresh state
                    if (window.siteurl) {
                        window.location.href = window.siteurl;
                    } else {
                        location.reload();
                    }
                } else {
                    $(".i_error").html(resp || "Unexpected response").show();
                    setTimeout(() => {
                        $(".i_error").html('').hide();
                    }, 5000);
                }
            },
            error: function () {
                $(".i_error").html("Network error. Please try again.").show();
                setTimeout(() => {
                    $(".i_error").html('').hide();
                }, 5000);
            }
        });
    });

    /**
     * AJAX Register Form Submit
     */
    $(document).on('submit', "#iregister", function (e) {
        e.preventDefault();
        const form = $("#iregister");

        $.ajax({
            type: "POST",
            url: siteurl + "requests/register.php",
            data: form.serialize(),
            beforeSend: function () {
                $(".register_warning").hide();
                $(".i_modal_content").append(plreLoadingAnimationPlus);
            },
            success: function (response) {
                $(".loaderWrapper").remove();
                const resp = (response || '').toString().replace(/^\uFEFF/, '').trim();

                switch (resp) {
                    case '1': $(".fill_all").show(); break;
                    case '2': $(".fill_username_used").show(); break;
                    case '3': $(".fill_email_used").show(); break;
                    case '4': $(".fill_username_short").show(); break;
                    case '5': $(".fill_username_invalid").show(); break;
                    case '6': $(".fill_email_invalid").show(); break;
                    case '7': $(".fill_pass").show(); break;
                    case '8': {
                        const root = (typeof siteurl !== 'undefined' && siteurl) ? siteurl : (window.siteurl || '/');
                        window.location.href = root + 'settings';
                        break;
                    }
                    case '9': {
                        $(".fill_email_not_send").show();
                        const root = (typeof siteurl !== 'undefined' && siteurl) ? siteurl : (window.siteurl || '/');
                        setTimeout(() => { window.location.href = root + 'settings'; }, 1500);
                        break;
                    }
                    default:
                        // Surface unexpected response to help debugging
                        $(".i_error").text(resp || 'Unexpected response').show();
                        setTimeout(() => { $(".i_error").hide().text(''); }, 5000);
                }
            }
        });
    });

    /**
     * Modal open/close for login/forgot password
     */
    $(document).on("click", ".loginForm", function () {
        $(".i_modal_bg").addClass("i_modal_display");
    });

    $(document).on("click", ".i_modal_close", function () {
        $(".i_modal_bg").removeClass("i_modal_display");
        $(".i_modal_in").removeAttr("style");
        $(".i_modal_forgot").hide();
    });

    $(document).on("click", ".password-reset", function () {
        $(".i_modal_in").hide();
        $(".i_modal_forgot").show();
    });

    $(document).on("click", ".already-member", function () {
        $(".i_modal_in").show();
        $(".i_modal_forgot").hide();
    });

    /**
     * Open share menu
     */
    $(document).on("click", ".openShareMenu", function () {
        const id = $(this).attr("id");
        $(".mnsBox" + id).addClass("dblock");
    });

    /**
     * Close post/share menu on outside click
     */
    $(document).on("mouseup touchend", function (e) {
        const container = $(".mnsBox, .mnoBox");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.removeClass("dblock");
        }
    });

    /**
     * Creator Search
     */
    $(document).on("keyup", "#search_creator", function () {
        const searchValue = $(this).val();
        const keyword = searchValue.trim();

        if (keyword.length >= 1) {
            $(".i_general_box_search_container").show();

            setTimeout(() => {
                const data = 'f=searchCreator&s=' + encodeURIComponent(searchValue);
                $.ajax({
                    type: "POST",
                    url: siteurl + 'requests/request.php',
                    data: data,
                    cache: false,
                    beforeSend: function () {
                        $(".sb_items").append(plreLoadingAnimationPlus);
                    },
                    success: function (response) {
                        $(".sb_items").html(response);
                    }
                });
            }, 1000);
        } else {
            $(".i_general_box_search_container").hide();
        }
    });

    /**
     * Send forgot password email
     */
    $(document).on("click", ".i_forgot_button", function () {
        const email = $("#i_nora_forgot_password").val();
        const data = 'f=forgotPass&email=' + encodeURIComponent(email);

        $.ajax({
            type: "POST",
            url: siteurl + 'requests/request.php',
            data: data,
            cache: false,
            beforeSend: function () {
                $(".i_modal_forgot").append(plreLoadingAnimationPlus);
            },
            success: function (response) {
                $(".loaderWrapper").remove();

                if (String(response).trim() === '200') {
                    $(".s_e").hide();
                    $(".s_e_success").show();
                } else if (response === '2') {
                    $(".no_this_email").show();
                } else if (response === '3') {
                    $(".system_no_send").show();
                }
            }
        });
    });

    /**
     * Reset password form submission
     */
    $(document).on("submit", "#iresetpass", function (e) {
        e.preventDefault();
        const form = $('#iresetpass');

        $.ajax({
            type: "POST",
            url: siteurl + "requests/request.php",
            data: form.serialize(),
            beforeSend: function () {
                $(".successNot, .warning_not_mach, .warning_not_correct, .warning_write_current_password, .no_new_password_wrote, .minimum_character_not, .not_contain_whitespace").hide();
                $(".i_become_creator_container").append(plreLoadingAnimationPlus);
                form.find(':input[type=submit]').prop('disabled', true);
            },
            success: function (data) {
                setTimeout(() => {
                    form.find(':input[type=submit]').prop('disabled', false);
                }, 3000);

                $(".loaderWrapper").remove();

                switch (data) {
                    case '2': $(".warning_not_mach").show(); break;
                    case '4': $(".no_new_password_wrote").show(); break;
                    case '5': $(".minimum_character_not").show(); break;
                    case '200':
                        $(".i_settings_item_title_for").remove();
                        $(".warning_success").show();
                        $(".i_res_button").remove();
                        break;
                }
            }
        });
    });

    /**
     * Toggle mobile search input
     */
    $(document).on("click", ".mobile_srcbtn", function () {
        $(".i_search").toggleClass("i_search_active");
    });

    /**
     * Earnings simulator logic
     */
    $(document).ready(function () {
        /**
         * Format number with commas and currency symbol
         */
        function decimalFormat(nStr) {
            const decimalDot = ".";
            const decimalComma = ",";
            const currencyLeft = $("body").data("currencyleft");
            const currencyRight = "";

            nStr += "";
            let x = nStr.split(".");
            let x1 = x[0];
            let x2 = x.length > 1 ? decimalDot + x[1] : "";
            const rgx = /(\d+)(\d{3})/;

            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, "$1" + decimalComma + "$2");
            }

            return currencyLeft + x1 + x2 + currencyRight;
        }

        /**
         * Calculate estimated earnings
         */
        function earnAvg() {
            const fee = parseFloat($("body").data("adminfee"));
            const monthlySubscription = parseFloat($("#rangeMonthlySubscription").val());
            const numberFollowers = parseFloat($("#rangeNumberFollowers").val());

            const estimatedFollowers = numberFollowers * 0.2;
            const total = estimatedFollowers * monthlySubscription;
            const platformCut = (total * fee) / 100;
            const result = total - platformCut;

            return decimalFormat(result.toFixed(2));
        }

        // Initial render
        $("#estimatedEarn").html(earnAvg());

        // Update on input
        $("#rangeNumberFollowers").on("input change", function () {
            $("#numberFollowers").html($(this).val());
            $("#estimatedEarn").html(earnAvg());
        });

        $("#rangeMonthlySubscription").on("input change", function () {
            $("#monthlySubscription").html($(this).val());
            $("#estimatedEarn").html(earnAvg());
        });

        // Toggle accordion
        $(".toggle").on("click", function (e) {
            e.preventDefault();
            const $this = $(this);
            const $next = $this.next();

            if ($next.hasClass("show")) {
                $next.removeClass("show").slideUp(350);
                $this.removeClass("activeTogg");
            } else {
                $this.closest("ul").find("li .inner").removeClass("show");
                $next.toggleClass("show").slideToggle(350);
                $this.addClass("activeTogg");
            }
        });

        /**
         * Claim username logic
         */
        $("body").on("click", ".claimname", function () {
            const username = $("#clName").val();
            const data = "f=claim&clnm=" + username;

            $.ajax({
                type: "POST",
                url: siteurl + "requests/request.php",
                dataType: "json",
                data: data,
                cache: false,
                beforeSend: function () {
                    $(".error_report").hide(); // Hide all errors first
                    $(".claimname").prop("disabled", true);
                },
                success: function (response) {
                    const res = response.status;

                    if (res === "200") {
                        window.location.href = siteurl + "register?claim=" + username;
                    } else if (res === "2") {
                        $(".unmexist").show();
                    } else if (res === "5") {
                        $(".invldcharctr").show();
                    } else if (res === "3") {
                        $(".unmempt").show();
                    }

                    setTimeout(() => {
                        $(".claimname").prop("disabled", false);
                    }, 1000);
                    setTimeout(() => {
                        $(".unmexist").hide();
                    }, 5000);
                }
            });
        });
    });

})(jQuery);
