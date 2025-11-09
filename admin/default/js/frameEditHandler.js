(function ($) {
  "use strict";

  $(document).on("keyup", ".pnt", function () {
    const inputPointVal = parseFloat($(this).val());
    const data = JSON.parse(document.getElementById("frameEditData").textContent);
    const onePointEqual = parseFloat(data.onePointEqual);

    if (!isNaN(inputPointVal)) {
      const translatePointToMoney = (inputPointVal * onePointEqual).toLocaleString(undefined, {
        minimumFractionDigits: 1,
        maximumFractionDigits: 1
      });
      $(".totsm").text(translatePointToMoney);
      $("input[name=pointAmount]").val(translatePointToMoney);
    }
  });
})(jQuery);