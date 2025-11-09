(function ($) {
    "use strict";

    $(document).on("keyup", ".pnt", function () {
        const inputPointVal = parseFloat($(this).val());
        const onePointEqual = window.onePointEqual || 0;

        if (!isNaN(inputPointVal) && onePointEqual > 0) {
            const money = (inputPointVal * onePointEqual).toLocaleString(undefined, {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            });
            $(".totsm").text(money);
            $("input[name='pointAmount']").val(money);
        } else {
            $(".totsm").text("0.0");
            $("input[name='pointAmount']").val("0.0");
        }
    });

})(jQuery);