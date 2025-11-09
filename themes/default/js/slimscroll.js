(function ($) {
    "use strict";
    $(function () {
        $('.emojis_Container').slimscroll({
            height: '348px',
            color: '#ccc',
            size: '4px',
            alwaysVisible: false
        });
    });
    $(function () {
        $('.Message_stickers_wrapper').slimscroll({
            width: '100%',
            height: '375px',
            touchScrollStep: 1,
            wheelStep: 100
        });
    });
})(jQuery);