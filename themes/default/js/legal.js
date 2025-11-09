(function($) {
    "use strict"; 
    const siteurl = $('body').data('siteurl');
    
    $(document).on("click",".check", function(){
        var validCode = $("#validate_purchase_code").val();
        var patt = new RegExp("(.*)-(.*)-(.*)-(.*)-(.*)");
        var res = patt.test(validCode);
        var data = 'f=vldcd&code='+validCode;
        if(validCode == '91X36x28-xxx5-4X70-x109-x9wc8xxc6X16'){
            return false;
        }
        $('#button-update').attr('disabled', 'true');
        $(".checking_notes").hide().html('');
        if(res){
            $.ajax({
                type: "POST",
                url: siteurl + "requests/request.php",
                data: data,
                cache: false,
                beforeSend: function() {
                    $(".i_settings_item_title_warning").hide().html('');
                    $(".checking_notes").show().html('Checking please wait...');
                },
                success: function(data) {
                    $('#button-update').removeAttr('disabled');
                    if(data == 'next'){
                        $(".checking_notes").html('Confirming your purchase code...');
                        setTimeout(() => {
                            $(".checking_notes").html('Your website is being activated, please wait....');
                            setTimeout(() => {
                                $(".checking_notes").html('Redirecting to your site...');
                                window.location.href = siteurl;}, 3000);
                            }, 3000);
                    }else{
                            $(".checking_notes").html('Checking availability...');
                            setTimeout(() => {
                                $(".i_settings_item_title_warning").show().html(data);
                            }, 3000);
                        }
                    }
            });
        }
    });
})(jQuery);