(function($) {
  "use strict";

  $(function() {
    // LightGallery initialization
    $('.lightGalleryInit').each(function() {
      const postID = $(this).data('id');
      const $galleryEl = $('#lightgallery' + postID);
      if ($galleryEl.length) {
        $galleryEl.lightGallery({
          videojs: true,
          mode: 'lg-fade',
          cssEasing: 'cubic-bezier(0.25, 0, 0.25, 1)',
          download: false,
          share: false
        });
      }
    });

    // Apply background styles from data-style attributes
    $('[data-style]').each(function() {
      $(this).attr('style', $(this).data('style'));
    });

    // GreenAudioPlayer initialization (safe delay)
    setTimeout(() => {
      $('.green-audio-player').each(function() {
        const id = $(this).attr('id');
        if ($('#' + id + ' audio').length) {
          new GreenAudioPlayer('#' + id, {
            stopOthersOnPlay: true,
            showTooltips: true,
            showDownloadButton: false,
            enableKeystrokes: true
          });
        }
      });
    }, 300);
  });
})(jQuery);