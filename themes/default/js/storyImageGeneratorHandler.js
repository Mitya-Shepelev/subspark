(function($) {
  "use strict";

  $(document).on("change", "#storie_img", function(e) {
    e.preventDefault();
    const id = $("#storie_img").attr("data-id");
    const data = { f: id };

    $("#storiesform").ajaxForm({
      type: "POST",
      data: data,
      delegation: true,
      cache: false,
      beforeSubmit: function() {
        $(".create_sotry_form_container").append('<div class="i_upload_progress"></div>');
        $(".i_uploading_not_story").show();
      },
      xhr: function() {
        const xhr = $.ajaxSettings.xhr();
        if (xhr.upload) {
          xhr.upload.addEventListener("progress", function(event) {
            let percent = 0;
            const position = event.loaded || event.position;
            const total = event.total;
            if (event.lengthComputable) {
              percent = Math.ceil((position / total) * 100);
            }
            $(".i_upload_progress").css("width", percent + "%");
          }, true);
        }
        return xhr;
      },
      success: function(response) {
        if (response) {
          $(".edit_created_stories").prepend(response);
          $(".i_upload_progress").remove();
          $(".i_uploading_not_story").hide();
        }
      },
      error: function() {
        $(".i_upload_progress").remove();
        $(".i_uploading_not_story").hide();
        console.error("Story image upload failed.");
      }
    });

    $("#storiesform").trigger("submit");
  });

  $(document).on("click", ".dmyStory", function() {
    const type = "delete_storie_alert";
    const ID = $(this).attr("id");
    const data = "f=" + type + "&id=" + ID;

    $.ajax({
      type: "POST",
      url: siteurl + "requests/request.php",
      data: data,
      cache: false,
      success: function(response) {
        $("body").append(response);
        setTimeout(() => {
          $(".i_modal_bg_in").addClass("i_modal_display_in");
        }, 200);
      }
    });
  });

  function adjustUploadedStoryImages() {
    $(".uploaded_storie_container img[id^='img']").each(function() {
      const $img = $(this);
      const img = $img.get(0);

      function setSize() {
        if (img.height > img.width) {
          $img.css("height", "100%");
        } else {
          $img.css("width", "100%");
        }
        $img.closest(".uploaded_storie_container").show();
      }

      if (img.complete) {
        setSize();
      } else {
        $img.on("load", setSize);
      }
    });
  }

  $(document).ready(function() {
    adjustUploadedStoryImages();
  });
})(jQuery);