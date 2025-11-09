(function ($) {
  "use strict";

  // Mask input field for birthday format (e.g. 00/00/0000)
  $(document).ready(function () {
    $('#date1').mask('00/00/0000');
  });

  // Debounced username availability check
  let usernameTimer = null;

  $(document).on('keyup', '#uname', function () {
    clearTimeout(usernameTimer);
    usernameTimer = setTimeout(function () {
      const username = $("#uname").val();

      if (username.length < 3) {
        // Don't send request for very short inputs
        return;
      }

      $.ajax({
        type: 'POST',
        url: siteurl + "requests/request.php",
        data: {
          f: 'checkusername',
          username: username
        },
        cache: false,
        success: function (response) {
          // Hide all warnings first
          $('.invalid_username, .character_warning, .warning_username').hide();

          switch (response) {
            case '1':
              // Valid username
              break;
            case '2':
              $('.warning_username').show();
              break;
            case '3':
              $('.invalid_username').show();
              break;
            case '4':
              $('.character_warning').show();
              break;
            default:
              // Hide all again in case of unexpected result
              $('.invalid_username, .character_warning, .warning_username').hide();
          }
        }
      });
    }, 500); // Delay to reduce AJAX calls
  });

})(jQuery);