(function($) {
    "use strict";
    function GetSlimScroll() {
        if ($(window).width() < 330) {
            $(".btest").slimScroll({
                height: '100%',
                width: '100%',
                railVisible: false,
                alwaysVisible: false,
                wheelStep: 1,
                railOpacity: .1
            });
        }
    }
    GetSlimScroll();
    $(document).on("click", ".getEmojisCr", function () {
        var type = 'emoji';
        var ID = $(this).attr("data-type");
        var dataID = $(this).attr("data-id");
        var data = 'f=' + type + '&id=' + ID + '&ec=' + dataID;
        var $this = $(this);
    
        $.ajax({
            type: 'POST',
            url: siteurl + 'requests/request.php',
            data: data,
            beforeSend: function () {
                $(".emojiBoxC, .emojiBox, .stickersContainer").remove();
            },
            success: function (response) { 
                var $wrapper = $this.closest('.reels_comments_cont');
                $wrapper.append(response);
    
                var popup = $wrapper.find('.emojiBoxC');
                var btn = $this[0];
    
                if (btn && typeof btn.getBoundingClientRect === "function") {
                    var btnRect = btn.getBoundingClientRect();
                    var wrapperRect = $wrapper[0].getBoundingClientRect();
    
                    var relativeLeft = btnRect.left - wrapperRect.left;
                    var relativeTop = btnRect.top - wrapperRect.top;
    
                    popup.css({
                        position: 'absolute',
                        left: Math.min(relativeLeft, $wrapper.width() - popup.outerWidth() - 10) + 'px',
                        top: (relativeTop - popup.outerHeight() - 8) + 'px',
                        zIndex: 9999
                    });
                }
    
                GetSlimScroll();
            }
        });
    });
    $(document).on("click", ".getStickersr", function () {
    var type = 'stickers';
    var ID = $(this).attr("id");
    var data = 'f=' + type + '&id=' + ID;
    var $this = $(this);

    $.ajax({
        type: 'POST',
        url: siteurl + 'requests/request.php',
        data: data,
        beforeSend: function () {
            $(".stickersContainer, .emojiBox, .emojiBoxC").remove();
        },
        success: function (response) {
            var $wrapper = $this.closest('.reels_comments_cont');
            $wrapper.append(response);

            var popup = $wrapper.find('.stickersContainer');
            var btn = $this[0];

            if (btn && typeof btn.getBoundingClientRect === "function") {
                var btnRect = btn.getBoundingClientRect();
                var wrapperRect = $wrapper[0].getBoundingClientRect();

                var relativeLeft = btnRect.left - wrapperRect.left;
                var relativeTop = btnRect.top - wrapperRect.top;
                var popupWidth = popup.outerWidth();
                var wrapperWidth = $wrapper.width();

                // Taşmayı engelle
                if ((relativeLeft + popupWidth) > wrapperWidth) {
                    relativeLeft = wrapperWidth - popupWidth - 10;
                }

                popup.css({
                    position: 'absolute',
                    left: relativeLeft + 'px',
                    top: (relativeTop - popup.outerHeight() - 8) + 'px',
                    zIndex: 99999
                });
            }
        }
    });
});
$(document).on("click", ".getGifsr", function () {
    var type = 'gifList';
    var ID = $(this).attr("id");
    var data = 'f=' + type + '&id=' + ID;
    var $this = $(this);

    $.ajax({
        type: 'POST',
        url: siteurl + 'requests/request.php',
        data: data,
        beforeSend: function () {
            $(".stickersContainer, .emojiBox, .emojiBoxC").remove();
        },
        success: function (response) {
            var $wrapper = $this.closest('.reels_comments_cont');
            $wrapper.append(response);

            var popup = $wrapper.find('.stickersContainer'); 
            var btn = $this[0];

            if (btn && typeof btn.getBoundingClientRect === "function") {
                var btnRect = btn.getBoundingClientRect();
                var wrapperRect = $wrapper[0].getBoundingClientRect();

                var relativeLeft = btnRect.left - wrapperRect.left;
                var relativeTop = btnRect.top - wrapperRect.top;
                var popupWidth = popup.outerWidth();
                var wrapperWidth = $wrapper.width();

                if ((relativeLeft + popupWidth) > wrapperWidth) {
                    relativeLeft = wrapperWidth - popupWidth - 10;
                }

                popup.css({
                    position: 'absolute',
                    left: relativeLeft + 'px',
                    top: (relativeTop - popup.outerHeight() - 8) + 'px',
                    zIndex: 99999
                });
            }
        }
    });
});
$(document).on("click", ".emoji_item_c", function() {
        var copyEmoji = $(this).attr("data-emoji");
        var ID = $(this).attr("data-id");
        var getValue = $(".comment_reel_item_" + ID).val();
        $(".comment_reel_item_" + ID).val(getValue + ' ' + copyEmoji + ' ');
    });
    
})(jQuery);