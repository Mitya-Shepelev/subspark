(function($) {
  "use strict";

  $(document).ready(function () {
    if (typeof $.gdprcookie !== "undefined") {
      $.gdprcookie.init({
        title: window.cookie_title || "Cookies",
        message: window.cookie_desc || "We use cookies to improve experience.",
        delay: 600,
        expires: 30,
        acceptBtnLabel: window.cookie_accept || "Accept"
      });

      $(document.body)
        .on("gdpr:show", function () {
          // GDPR dialog shown
        })
        .on("gdpr:accept", function () {
          var preferences = $.gdprcookie.preference();
          // Preferences saved
        })
        .on("gdpr:advanced", function () {
          // Advanced settings shown
        });

      if ($.gdprcookie.preference("marketing") === true) {
        // Marketing preference accepted
      }
    }
  });

})(jQuery);