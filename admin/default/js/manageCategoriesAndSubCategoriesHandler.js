(function($) {
    "use strict";
    $(document).on("click", ".sbEd , .sceEd", function() {
        var ID = $(this).attr("id");
        $(".se_" + ID).toggle();
        $(".sc_" + ID).toggle();
        $(".sc_e_" + ID).toggle();
        $(".sc_ed_" + ID).toggle();
    });
    $(document).on("click", ".newSubC", function() {
        var ID = $(this).attr("data-id");
        $(".n_s_c_" + ID).toggle();
    });
    $(document).on("click", ".edittCat , .svcEdt", function() {
        var ID = $(this).attr("id");
        $(".cse_" + ID).toggle();
        $(".cse_i_" + ID).toggle();
        $(".s_h_" + ID).toggle();
        $(".edtt_cat_" + ID).toggle();
    });
})(jQuery);