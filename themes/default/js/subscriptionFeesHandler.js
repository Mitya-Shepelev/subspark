(function ($) {
  "use strict";

  function decimalFormat(nStr) {
    var $decimalDot = ".";
    var $decimalComma = ",";
    nStr += "";
    var x = nStr.split(".");
    var x1 = x[0];
    var x2 = x.length > 1 ? $decimalDot + x[1] : "";
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, "$1" + $decimalComma + "$2");
    }
    return x1 + x2;
  }

  $(document).ready(function () {
    const { weeklyMin, monthlyMin, yearlyMin, onePointEqual, adminFee } = window.subscriptionData;
    const $decimal = 2;

    $("body").on("keyup", ".paval", function () {
      const val = parseFloat($(this).val());
      const ID = $(this).attr("id");

      $(".i_t_warning, .i_t_warning_earning").hide();

      if (!isNaN(val)) {
        if (ID === "spweek" && val < weeklyMin) {
          $("#waweekly").show();
        } else if (ID === "spmonth" && val < monthlyMin) {
          $("#mamonthly").show();
        } else if (ID === "spyear" && val < yearlyMin) {
          $("#yayearly").show();
        } else {
          let earning = (val * onePointEqual) - ((val * onePointEqual) * adminFee);
          let formatted = decimalFormat(earning.toFixed($decimal));
          if (ID === "spweek") {
            $(".weekly_earning").show();
            $("#weekly_earning").html(formatted);
          } else if (ID === "spmonth") {
            $(".mamonthly_earning").show();
            $("#mamonthly_earning").html(formatted);
          } else if (ID === "spyear") {
            $(".yayearly_earning").show();
            $("#yayearly_earning").html(formatted);
          }
        }
      }
    });

    $("body").on("change", ".subpointfee", function () {
      const id = $(this).attr("data-id");
      const current = $(this).val();
      $('#' + id).val(current === '1' ? '0' : '1');
    });

    $(document).on("click", ".c_pNext", function () {
      const type = 'updateSubscriptionPayments';
      const preLoadingAnimation = '<div class="i_loading product_page_loading"><div class="dot-pulse"></div></div>';
      const plreLoadingAnimationPlus = `<div class="loaderWrapper"><div class="loaderContainer"><div class="loader">${preLoadingAnimation}</div></div></div>`;

      const weekly = $("#spweek").val();
      const weeklyStatus = $('input[name="weekly"]').val();
      const monthly = $("#spmonth").val();
      const monthlyStatus = $('input[name="monthly"]').val();
      const yearly = $("#spyear").val();
      const yearlyStatus = $('input[name="yearly"]').val();

      // Required field validation
      if (weeklyStatus === '1' && weekly.length === 0) return $("#wweekly").show();
      if (monthlyStatus === '1' && monthly.length === 0) return $("#wmonthly").show();
      if (yearlyStatus === '1' && yearly.length === 0) return $("#wyearly").show();

      const data = `f=${type}&wSubWeekAmount=${weekly}&mSubMonthAmount=${monthly}&mSubYearAmount=${yearly}&wStatus=${weeklyStatus}&mStatus=${monthlyStatus}&yStatus=${yearlyStatus}`;

      $.ajax({
        type: "POST",
        url: siteurl + 'requests/request.php',
        data: data,
        dataType: "json",
        cache: false,
        beforeSend: function () {
          $(".i_nex_btn").css("pointer-events", "none");
          $("#wweekly, #wmonthly, #wyearly, .weekly_success, .monthly_success, .yearly_success").hide();
          $(".i_become_creator_box_footer").append(plreLoadingAnimationPlus);
        },
        success: function (response) {
          if (response.weekly === '404') $("#wweekly").show();
          else if (response.weekly === '200') $(".weekly_success").show();

          if (response.monthly === '404') $("#wmonthly").show();
          else if (response.monthly === '200') $(".monthly_success").show();

          if (response.yearly === '404') $("#wyearly").show();
          else if (response.yearly === '200') $(".yearly_success").show();

          $(".loaderWrapper").remove();
          $(".i_nex_btn").css("pointer-events", "auto");
        }
      });
    });
  });
})(jQuery);