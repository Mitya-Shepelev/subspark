(function ($) {
    "use strict";

    $(document).ready(function () {
        $('[id^="lightgallery"]').each(function () {
            $(this).lightGallery({
                videojs: true,
                mode: 'lg-fade',
                cssEasing : 'cubic-bezier(0.25, 0, 0.25, 1)',
                download: false,
                share: false
            });
        });
    });
    $('.i_post_image_swip_wrapper').each(function () {
        const $this = $(this);
        const bgUrl = $this.data('bg');
        if (bgUrl) {
            const img = new Image();
            img.onload = function () {
                $this.css('background-image', 'url("' + bgUrl + '")');
                $this.removeClass('image-skeleton');
            };
            img.src = bgUrl;
        }
    });
})(jQuery);