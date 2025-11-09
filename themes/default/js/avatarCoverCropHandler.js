(function ($) {
  "use strict";

  const preLoadingAnimation = '<div class="i_loading"><div class="dot-pulse"></div></div>';
  const plreLoadingAnimationPlus = '<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">' + preLoadingAnimation + '</div></div></div>';

  let $image_crop, $avatar_image_crop;

  // Cover Image Cropping
  function initCoverCropper() {
      const containerWidth = $(".cropier_container").width(); // Get dynamic width
      const viewportHeight = Math.round(containerWidth * 0.46); // Example: 16:7 ratio
    
      $image_crop = $("#cover_image").croppie({
        enableExif: true,
        enableOrientation: true,
        viewport: {
          width: containerWidth,
          height: viewportHeight,
          type: "square"
        },
        boundary: {
          width: containerWidth,
          height: viewportHeight
        }
      });
    }

  // Avatar Image Cropping
  function initAvatarCropper() {
    $avatar_image_crop = $("#avatar_image").croppie({
      enableExif: true,
      viewport: {
        width: 200,
        height: 200,
        type: "square"
      },
      boundary: {
        width: 200,
        height: 200
      }
    });
  }

  function isValidImageType(fileType) {
    const validTypes = ["image/gif", "image/jpeg", "image/png", "image/svg+xml", "image/jpg"];
    return $.inArray(fileType, validTypes) >= 0;
  }

  $(document).ready(function () {
    initCoverCropper();
    initAvatarCropper();

    $(document).on("click", ".cnclcrp", function () {
      $(".i_modal_avatar_resize_bg_in").removeClass("i_modal_display_in");
    });

    $("body").on("change", "#cover", function () {
      const file = this.files[0];
      if (!isValidImageType(file.type)) { 
        return;
      }
      const reader = new FileReader();
      reader.onload = function (event) {
        $image_crop.croppie("bind", {
          url: event.target.result
        }).then(function () {
          if (file.type === "image/gif") {
            $(".cropTypeisGif").show();
          }
        });
      };
      reader.readAsDataURL(file);
      $(".i_modal_cover_resize_bg_in").addClass("i_modal_display_in");
    });

    $("body").on("click", ".finishCrop", function () {
      $image_crop.croppie("result", {
        type: "canvas",
        size: "original",
        circle: false
      }).then(function (response) {
        $(".i_modal_content").append(plreLoadingAnimationPlus);
        $.post(siteurl + "requests/request.php", { image: response, f: "coverUpload" }, function (html) {
          $(".loaderWrapper").remove();
          if (html === "404") {
            $(".i_cover_upload_error").fadeIn().delay(5000).fadeOut();
          } else {
            $(".coverImageArea").css("background-image", "url(" + html + ")");
            $(".i_modal_cover_resize_bg_in").addClass("i_modal_in_in_out");
            setTimeout(function () {
              $("#cover_image").croppie("bind");
              $(".i_modal_cover_resize_bg_in").removeClass("i_modal_display_in");
            }, 200);
          }
        });
      });
    });

    $("body").on("change", "#avatar", function () {
      const file = this.files[0];
      if (!isValidImageType(file.type)) { 
        return;
      }
      const reader = new FileReader();
      reader.onload = function (event) {
        $avatar_image_crop.croppie("bind", {
          url: event.target.result
        }).then(function () {
          if (file.type === "image/gif") {
            $(".cropTypeisGif").show();
          }
        });
      };
      reader.readAsDataURL(file);
      $(".i_modal_avatar_resize_bg_in").addClass("i_modal_display_in");
    });

    $("body").on("click", ".finishACrop", function () {
      $avatar_image_crop.croppie("result", {
        type: "canvas",
        size: "original",
        circle: false
      }).then(function (response) {
        $(".i_modal_content").append(plreLoadingAnimationPlus);
        $.post(siteurl + "requests/request.php", { image: response, f: "avatarUpload" }, function (html) {
          $(".loaderWrapper").remove();
          if (html === "404") {
            $(".i_cover_upload_error").fadeIn().delay(5000).fadeOut();
          } else {
            $(".avatarImage").css("background-image", "url(" + html + ")");
            $(".i_modal_avatar_resize_bg_in").addClass("i_modal_in_in_out");
            setTimeout(function () {
              $("#avatar_image").croppie("bind");
              $(".i_modal_avatar_resize_bg_in").removeClass("i_modal_display_in");
            }, 200);
          }
        });
      });
    });

    $("body").on("click", ".coverCropClose", function () {
      $(".i_modal_cover_resize_bg_in, .i_modal_avatar_resize_bg_in").addClass("i_modal_in_in_out");
      setTimeout(function () {
        $(".i_modal_cover_resize_bg_in, .i_modal_avatar_resize_bg_in").removeClass("i_modal_display_in");
      }, 200);
    });
  });

})(jQuery);