(function ($) {
  "use strict";

  $(document).on("change", "#id_fav", function (e) {
    e.preventDefault();

    const id = $(this).data("id");
    const type = $(this).data("type");

    $("#stBgUploadForm").ajaxForm({
      type: "POST",
      data: { f: id, c: type },
      delegation: true,
      cache: false,
      beforeSubmit: function () {
        $("#sec_logo").append('<div class="i_upload_progress"></div>');
      },
      uploadProgress: function (e, position, total, percentageComplete) {
        $(".i_upload_progress").width(percentageComplete + "%");
      },
      success: function (response) {
        if (response == "200") {
          location.reload();
        } else {
          $("body").append(
            '<div class="nnauthority"><div class="no_permis flex_ c3 border_one tabing">' +
              response +
              "</div></div>"
          );
          setTimeout(() => {
            $(".nnauthority").remove();
          }, 5000);
        }
      },
      error: function () {},
    }).submit();
  });
})(jQuery);