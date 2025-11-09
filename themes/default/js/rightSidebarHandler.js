(function ($) {
  "use strict";

  $(document).ready(function () {
    var btestarDiv = $(".leftSidebarWrapper");
    if (btestarDiv.length > 0) {
      var btestarHeight = btestarDiv.get(0).offsetHeight;
      if (btestarHeight > 700) {
        $(".i_yesScrollable").show();
        $(".leftSidebarWrapper").animate({ scrollTop: "+=30px" }, 1000, function () {
          $(".leftSidebarWrapper").animate({ scrollTop: "-=30px" }, 1000);
        });

        setTimeout(() => {
          $(".i_yesScrollable").remove();
        }, 6000);
      }
    }
  });

  // Context menu block (for non-creators)
  if (window.userIsLoggedIn && !window.userIsCreator) {
    document.addEventListener("contextmenu", function (event) {
      event.preventDefault();
    });
  }
})(jQuery);