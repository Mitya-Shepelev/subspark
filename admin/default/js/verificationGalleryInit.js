(function($) {
  "use strict";

  $(document).ready(function() {
    $('.lightGalleryInit').each(function() {
      const userID = $(this).data('uid');
      const $gallery = $('#lightgallery' + userID);

      if ($gallery.length) {
        $gallery.lightGallery({
          videojs: true,
          mode: 'lg-fade',
          cssEasing: 'cubic-bezier(0.25, 0, 0.25, 1)',
          download: false,
          share: false
        });

        // Set background-image for each image wrapper
        $gallery.find('.i_post_image_swip_wrapper').each(function() {
          const bg = $(this).data('bg');
          if (bg) {
            $(this).css('background-image', 'url("' + bg + '")');
          }
        });
      }
    });
  });
})(jQuery);