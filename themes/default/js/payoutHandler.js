(function($){
  "use strict";

  $(document).ready(function(){

    $("body").on("click", ".pyot_Next", function(){
      const defaultMethod = $('input[name="default"]:checked').val();
      const paypalEmail = $("#paypale").val().trim();
      const repaypalEmail = $("#paypalere").val().trim();
      const bankAccount = $("#bank_transfer").val().trim();

      const $setWarning = $("#setWarning").hide();
      const $notMatch = $("#notMatch").hide();
      const $notValidE = $("#notValidE").hide();
      const $setBankWarning = $("#setBankWarning").hide();

      if(defaultMethod === "paypal"){
        if(paypalEmail === "" || repaypalEmail === ""){
          $("#setWarning").show();
          return false;
        }
        if(!validateEmail(paypalEmail) || !validateEmail(repaypalEmail)){
          $("#notValidE").show();
          return false;
        }
        if(paypalEmail !== repaypalEmail){
          $("#notMatch").show();
          return false;
        }
      }

      if(defaultMethod === "bank"){
        if(bankAccount === ""){
          $("#setBankWarning").show();
          return false;
        }
      }

      const data = {
        f: "payoutSet",
        paypalEmail: encodeURIComponent(paypalEmail),
        paypalReEmail: encodeURIComponent(repaypalEmail),
        bank: bankAccount,
        method: defaultMethod
      };

      $.ajax({
        type: "POST",
        url: siteurl + "requests/request.php",
        data: data,
        cache: false,
        beforeSend: function() {
          $(".i_nex_btn").css("pointer-events", "none");
        },
        success: function(response) {
          $(".i_nex_btn").css("pointer-events", "auto");

          if(String(response).trim() === "200"){
            location.reload();
          } else if(response === "email_warning"){
            $("#notMatch").show();
          } else if(response === "paypal_warning"){
            $("#setWarning").show();
          } else if(response === "bank_warning"){
            $("#setBankWarning").show();
          } else if(response === "not_valid_email"){
            $("#notValidE").show();
          }
        }
      });
    });

    function validateEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    }

  });

})(jQuery);
