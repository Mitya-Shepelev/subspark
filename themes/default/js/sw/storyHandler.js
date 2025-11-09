(function($) {
  "use strict";

  // Set background images from data attributes
  function applyBackgrounds() {
    $('[data-background-image]').each(function() {
      const bgUrl = $(this).data('background-image');
      if (bgUrl) {
        $(this).css('background-image', 'url(' + bgUrl + ')');
      }
    });
  }

  // Set avatars from data-avatar attributes
  function applyAvatars() {
    $('.story-view-pr-avatar').each(function() {
      const avatarUrl = $(this).data('avatar');
      if (avatarUrl) {
        $(this).css({
          'background-image': 'url(' + avatarUrl + ')',
          'background-size': 'cover',
          'background-position': 'center',
          'background-repeat': 'no-repeat'
        });
      }
    });
  }

  // Initialize StoryView for each container
  function initStoryViews() {
    $('.my-stories-wrapper').each(function() {
      if (typeof StoryView === 'function') {
        try {
          new StoryView({
            container: this,
            autoClose: true
          });
        } catch (e) {
          console.warn('StoryView initialization failed:', e);
        }
      }
    });
  }

  // Public method for reinitialization (e.g., after AJAX load)
  window.reInitStories = function() {
    applyBackgrounds();
    applyAvatars();
    initStoryViews();
  };

  // Initial run on document ready
  $(function() {
    window.reInitStories();
  });

  // Video duration extraction after metadata is loaded
  $(document).on('loadedmetadata', 'video', function() {
    const videoId = $(this).attr('id');
    const duration = this.duration;
    if (videoId) {
      $('.move_' + videoId.replace('video_', '')).attr('data-duration', Math.round(duration));
    }
  });

})(jQuery);