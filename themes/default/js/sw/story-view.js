/* Helpers and constructor wrapped in strict mode */
(function($, window, document){
  "use strict";
  var self = this;
var svTransitionEnd = 'webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend';

/**
 * @class StoryViewFlagger
 * @desc --
 * @param {} view -
 */
function StoryViewFlagger() {
    this.fired = false;
    this.set = function() {
        this.fired = true;
    }
    return this;
}

/**
 * @class StoryView
 * @desc --
 * @param {} view -
 */
function StoryView(options) {
    var closeIcon = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADQAAAA0CAYAAADFeBvrAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAJySURBVGhD7ZnPThRBEIcXuRAiCSe94ZVXEHwBPfIGgM+iMR68obwGCRfinwfR6FGPetGDya5fTVfFEJadnurqndH0l3R6p7vq1/0dgAw7azQajcZ/z3w+v79YLHb1MRRytxh7+lgfZJ4xhF+MY10OgbyHjG8ILZgvmLZ1qw4c8koOM3gWTnW7CHIOiPyekhOsvWOqI0X4NRlDjKBIiv4bMgZ78VKELpUxxAhcUvTdKmNQEydF2PMUuxoxgkFS1PfKGNReMW1qqw9C7sktU2Q/UgtZUtRlyxj0PNZ2HwTsMH5qXhbUCycasRT2B8sojzTCD4cfyw01MAuph6VSrLtk6DvTiHIIeyo31OwspB6uSfHslTln2tCYGAgtkmKejoxBuFfqBR+nJWNwyGApD2uRMWpLEf2GaT0yRi2pUWSMaKlRZYwoqUnIGKVSk5IxuNTLdL1h0PeBaXIyh4wf6YrDoE8IeUkMgcscMFwyRqc0BSkuUSxjdEpjSnF4mIzRKY0hxaHhMkantE4pDqsmY3RKPS+JIXCIS4ae93JDfcxC6qGeFOHe95nXTBvMg//4Sj3ESxFaJKMxknMqN0y7eUg9xEkRFiJjsD6eFFlbBH1NsfnQs1TGYN8j9ZtpXyN8ELDXpQ2gT8ZwSh1puw8y5If5MsX1Q22WjEH9CSNLirJPjB1t9UPWNkHyv+WVUDNIxqCvV4rtL4wH2lIOmSul2HPJGPTfKsXyZ0acjEG2SL1Nx/yFtSIZg5wbUjzWkTE4Q6TkW4AOPp8xFcsY5ImU/DaT7I9M9b+a5JA7HPaEcahLoZC/T/YR811dajQajUbjH2E2+wNwCp/oUNHGtwAAAABJRU5ErkJggg==';
    var fsvTemplate = [
        '<div class="sv-view flex_ tabing">',
        '<span class="close"><img src="' + (options.closeIcon || closeIcon) + '" /></span>',
        '<div class="loading"><span></span></div>',
        '<div class="profile">',
        '<div class="image"></div>',
        '<span class="name"></span>',
        '</div>',
        '<div class="hereText"></div><div class="content"><div class="media-container"></div></div>',
        '</div>'
    ];
    this.options = options;
    this.itemSelector = '.story-view-item';
    this.fsvTemplate = fsvTemplate.join('\n');
    this.container = $(options.container);
    this.reset();
    this.init();
}

StoryView.prototype.Helpers = {

    /**
     * @desc
     * @param {} element -
     */
    getBackground: function(element) {
        var bg = element.css('background-image');
        return bg.replace(/.*\s?url\([\'\"]?/, '').replace(/[\'\"]?\).*/, '')
    },

    /**
     * @desc
     * @param {} element -
     */
    isTouchDevice: function() {
        return 'ontouchstart' in window || 'onmsgesturechange' in window;
    },

    /**
     * @desc
     */
    generateID: function() {
        return "x".split('-').map(function(x) {
            return x.replace(x, Math.random().toString(16).substring(2) + Date.now().toString(16))
        }).join('-');
    },

    /**
     * @desc
     */
    containerPositon: function() {
        var container = this.storyContainer,
            matrix;

        matrix = container.css('transform').split(',').map(function(val) {
            return parseFloat(val.replace(/[a-z\s()]/g, ''))
        });

        return {
            x: matrix[4],
            y: matrix[5]
        }
    },

    /**
     * @desc
     */
    oneTimeFire: function(callback) {
        var flagger = false;
        return function(event) {
            if (!flagger) callback(event);
            flagger = true;
        }
    }
}



/**
 * @desc
 *
 */
StoryView.prototype.reset = function() {
    this.stories = [];
    this.mediaItems = [];
    this.mask = null;
    this.view = null;
    this.storyContainer = null;
    this.currentStory = null;
    this.currentMediaProgress = null;
    this.currentMedia = 0;
    this.paused = false;
    this.lastProgressVal = null;
    this.removingState = false;
    this.lastTouchStartTime = null;
    this.mediaLoading = false;
    this.mediaStartTime = null;
    this.mediaTempItem = null;
    this.mediaTempLoadFn = null;
    this.gestureStartOffset = null;
    this.elements = {};
    if (this.hammer) this.hammer.destroy();
    this.hammer = null;
}

/**
 * @desc
 *
 */
StoryView.prototype.init = function() {
    this.getItems();
};

/**
 * @desc
 *
 */
StoryView.prototype.getItems = function() {
    var items = this.container.find(this.itemSelector);
    var self = this; 

    items.each(function(idx, item) {
        var $item = $(item);
        $item.on('click', self.storyClick.bind(self, $item));
        $item.data('has-next', idx != items.length - 1);
        $item.data('profile-image', $item.data('profile-image'));
        $item.data('profile-name', $item.data('profile-name'));
        self.stories.push($item);
    });
}

/**
 * @desc
 * @param {} story -
 */
StoryView.prototype.storyClick = function(story) {
    if (story.hasClass('activated')) return;
    this.removingState = false;
    this.openStory(story);
}

/**
 * @desc
 * @param {} x -
 */
StoryView.prototype.changeMediaByGesture = function(x, type) {
    var wv = $(window).width();

    // Get next media
    // #
    if (x > wv / 2) {
        this.nextMedia(true);
    }

    // Get previous media
    // #
    if (x < wv / 2) {
        this.prevMedia(true);
    }
};

/**
 * @desc
 * @param {} event -
 */
StoryView.prototype.storyViewMouse = function(event) {
    var diff, evX = event.clientX || (event.changedTouches && event.changedTouches[0].clientX),
        evType = event.type;
    // Detecting pause state
    // #
    if (evType == 'touchstart' || evType == 'mousedown')
        this.lastTouchStartTime = Date.now();

    if (evType == 'touchend' || evType == 'mouseup')
        diff = Date.now() - this.lastTouchStartTime;

    if (!diff) {
        this.paused = true;
        return;
    } else this.paused = false;

    if ((evType == 'touchend' || evType == 'mouseup') && diff < 400) {
        this.changeMediaByGesture(evX, event.type);
    }
}

/**
 * @desc
 * @param {} event -
 */
StoryView.prototype.gestureVertical = function(event) {

    var pos;

    if (typeof this.gestureStartOffset.y == 'undefined')
        return;

    //
    // #
    if (event.direction == Hammer.DIRECTION_UP) {
        pos = this.gestureStartOffset.y + event.deltaY;
    }

    //
    // #
    if (event.direction == Hammer.DIRECTION_DOWN) {
        pos = this.gestureStartOffset.y + event.deltaY;
    }

    if (pos < 0) pos = 0

    this.storyContainer.removeClass('transition');
    this.storyContainer.css('transform', 'translateY( ' + pos + 'px )');
}

/**
 * @desc
 * @param {} event -
 */
StoryView.prototype.gestureStartStop = function(event) {

    var containerPosition = this.Helpers.containerPositon.call(this);

    if (event.type == 'swipedown') {
        this.mask.addClass('swipe-close');
        this.closeView()
        return;
    }

    // Start
    // #
    if (event.type == 'panstart') {
        this.gestureStartOffset = { x: containerPosition.x, y: containerPosition.y };
    }

    // Stop
    // #
    if (event.type == 'panend') {
        this.storyContainer.addClass('transition').css('transform', 'translateY( 0 )');

    }
}

/**
 * @desc
 *
 */
StoryView.prototype.preserveProfileName = function() {
    this.elements.profile.find('.name').css('min-width',
        this.elements.profile.find('.name').width()
    );
}


/**
 * @desc
 * @param {} mediaIndex -
 */
StoryView.prototype.closeView = function() {
    if (this.removingState) return;

    var that = this,
        t1, t2;

    this.removingState = true;
    this.view.removeClass('open');

    t1 = setTimeout(function() {
        clearTimeout(t1);
        this.view.addClass('removing');
    }.bind(this), 100);

    this.preserveProfileName();

    /*
     *
     *
     * [ jQuery Event: one --> this.view ]
     */
    this.view.one(svTransitionEnd, new this.Helpers.oneTimeFire(function(event) {

        this.view.removeClass('move')
        this.mask.removeClass('open')
        this.currentStory.removeClass('activated');

        t2 = setTimeout(function() {
            clearTimeout(t2);
            this.mask.remove();
            this.view.remove();
            this.storyContainer.remove();
            this.currentStory.removeClass('activated');
            clearTimeout(this.currentMediaProgress);
            this.reset();
            $('body').removeClass('story-view--shown');
        }.bind(this), 100);
    }.bind(this)));
}

/**
 * @desc
 * @param {} story -
 * @param isNextStory -
 */
StoryView.prototype.openStory = function(story, isNextStory, mediaIndex) {
    var that = this;

    this.view = this.view || $(this.fsvTemplate);
    this.storyContainer = this.storyContainer || $('<div />');

    var background = this.Helpers.getBackground(story),
        fsLeft = story.position().left,
        fsTop = story.position().top - $(window).scrollTop(),
        hammerPan, hammerSwipe, t1, t2;

    this.view.css({
        transform: 'translateX( ' + fsLeft + 'px) translateY( ' + fsTop + 'px )',
        width: story.width(),
        height: story.height()
    });

    if (!isNextStory) {
        this.mask = $('<div class="sv-mask" />');

        // Create hammer instance
        // #
        this.hammer = new Hammer.Manager(this.view.get(0));

        hammerPan = new Hammer.Pan({
            direction: Hammer.DIRECTION_ALL,
            threshold: 0
        });

        hammerSwipe = new Hammer.Swipe({
            direction: Hammer.DIRECTION_DOWN,
            threshold: 10,
            velocity: 1.5
        });

        hammerSwipe.recognizeWith(hammerPan);

        this.hammer.add(hammerPan);
        this.hammer.add(hammerSwipe);

        this.elements = {
            mediaContainer: this.view.find('.media-container'),
            loading: this.view.find('.loading'),
            profile: this.view.find('.profile')
        };

        this.storyContainer.attr('id', 'sv-' + this.Helpers.generateID());
        this.storyContainer.attr('class', 'sv-container');
        this.storyContainer.append(this.view);
        this.storyContainer.append(this.mask);
        $('body').append(this.storyContainer);

        /**
         *
         * [ jQuery Event: [ click ] --> view .close ]
         */
        this.view.find('.close').on('click touchend', function() {
            that.closeView();
        });
        $(".hereText").removeClass("hereTextClicked");
        this.view.find('.hereText').on('click', ".gradient", function(event) {
            event.preventDefault();
            //that.storyViewMouse(event);
            //that.mediaLoading = false;
            $(".hereText").addClass("hereTextClicked");
        });
        /**
         *
         * [ jQuery event: [ mousedown mouseup touchstart touchend ] --> view ]
         */
        var eventList = 'mousedown mouseup touchstart touchend';
        this.view.on(eventList, function(event) {
            event.preventDefault();

            var $target = $(event.target);
            if ($target.parents('.close , .hereText').length == 0)
                that.storyViewMouse(event);
        });

        /**
         *
         *
         * [ HammerJs event:  ]
         */
        this.hammer.on('swipedown panstart panend', this.gestureStartStop.bind(this));
        this.hammer.on('pandown panup', this.gestureVertical.bind(this));

    }

    // Reset container position
    // #
    this.storyContainer.css('transform', 'translate( 0, 0 )');

    // Reset profile on new story
    // #
    this.elements.profile.removeClass('show');
    // this.elements.profile.find( '.image' ).removeAttr( 'style' );
    // this.elements.profile.find( '.name' ).text( '' );

    // Fill story view
    // #
    // if( isNextStory )
    this.storyView(story);

    // Animate
    // #
    t1 = setTimeout(function() {
        clearInterval(t1);

        if (!isNextStory) {
            this.view.addClass('move')
            this.mask.addClass('open')
        }

        this.currentStory = story;
        story.addClass('activated');
    }.bind(this));

    t2 = setTimeout(function() {
        clearInterval(t2);

        if (!isNextStory) {
            this.view.addClass('open')

            /**
             *
             *
             * [ jQuery Event: transitionend --> StoryView.view ]
             */
            this.view.one(svTransitionEnd, new this.Helpers.oneTimeFire(function(event) {
                this.elements.profile.addClass('can-visible');
            }.bind(this)));
        }
    }.bind(this), 200);

    $('body').addClass('story-view--shown');
}

/**
 * @desc
 * @param {} view -
 * @param {} story -
 */
StoryView.prototype.storyView = function(story) {
    var that = this;
    var bars = $('<ul class="media-bars" />'),
        content = this.view.find('.content'),
        bar, $media, type, src, alt, profileImage, profileName;

    profileImage = story.data().profileImage;
    profileName = story.data().profileName;

    story.find('.media li *').each(function(idx, el) {
        var $media = $(el);
        var id = $media.attr('data-id');
        var bar = $('<li><span class="progress"></span></li>');
        bar.attr('data-type', $media.get(0).tagName.toLowerCase());
        bar.attr('data-src', $media.attr('src'));
        bar.attr('data-alt', $media.attr('alt'));
        bar.attr('data-id', id);
        bar.attr('data-ts', $media.attr('data-ts'));
        bars.append(bar);
    
        that.mediaItems.push({
            duration: $media.parent().data('duration'),
            type: $media.get(0).tagName.toLowerCase(),
            src: $media.attr('src'),
            alt: $media.attr('alt'),
            id: id,
            text_style: $media.attr('data-ts')
        });
    });


    // Fill Profile
    // #
    if (profileImage || profileName) {
        this.elements.profile.removeClass('sv-profile-image sv-profile-name');
        this.elements.profile.find('.image').css('background-image', 'url(' + (profileImage ? profileImage : '') + ')');
        this.elements.profile.find('.name').text(profileName ? profileName : '');

        if (profileImage) this.elements.profile.addClass('sv-profile-image');
        if (profileName) this.elements.profile.addClass('sv-profile-name');

        this.elements.profile.addClass('show');
    }

    content.find('.media-bars').remove();
    content.prepend(bars);

    // Start from first
    // #
    this.showMedia(this.currentMedia, 'next');
}
function generateRandomInteger(min, max) {
    return Math.floor(min + Math.random() * (max - min + 1));
}
var NumberGenerated = generateRandomInteger(1, 3);
var d = new Date();
var GeneragedDate = d.getDate();
/**
 * @desc
 * @param {} mediaIndex -
 */
StoryView.prototype.showMedia = function(mediaIndex, direction) {
    var that = this;
    var item = this.mediaItems[mediaIndex],
        content = this.view.find('.content .media-container'),
        textContent = this.view.find('.content .hereText'),
        prevProgressBars = this.view.find('.media-bars li:lt(' + this.currentMedia + ') .progress'),
        nextProgressBars = this.view.find('.media-bars li:gt(' + this.currentMedia + ') .progress'),
        progressBar = this.view
        .find('.media-bars li')
        .eq(mediaIndex)
        .find('.progress');

    this.mediaLoading = true;
    this.elements.loading.addClass('show');

    // Cancel previous source loading
    // #
    removeMediaEvents.call(this);


    // Remove media if exist
    // #
    content.empty();
    textContent.empty();
    progressBar.css('width', 0);
    nextProgressBars.css('width', '0');
    prevProgressBars.css('width', '100%');
    this.lastProgressVal = 0;
    this.mediaTempItem = null;
    this.mediaTempLoadFn = null;

    // Create image
    // #
    if (item.type == 'img') {
        this.mediaTempItem = new Image();

        /**
         * @desc
         */
        this.mediaTempLoadFn = function() {
            startMedia(that.mediaTempItem, mediaIndex, item.duration, progressBar, direction);
        };

        /**
         *
         * [ Image event: load --> StoryView.mediaTemplate ]
         */
        this.mediaTempItem.addEventListener('load', this.mediaTempLoadFn);
        this.mediaTempItem.src = item.src;
        this.mediaTempItem.id = item.id;
        this.mediaTempItem.text_style = item.text_style;

        $('.sv-view div.hereText').attr('class', 'hereText');
        if (this.mediaTempItem.text_style == 'one') {
            $(".hereText").addClass("hereTextStyle_one");
        }
        //alert(this.mediaTempItem.id);
        if (this.mediaTempItem.id) {
            var type = 'storieSeen';
            var id = this.mediaTempItem.id;
            var data = 'f=' + type + '&id=' + id;
            $.ajax({
                type: 'POST',
                url: siteurl + 'requests/request.php',
                data: data,
                cache: false,
                beforeSend: function() {},
                success: function() {}
            });
        }
        // New One
        this.mediaTempItem.alt = item.alt;
        $(".hereText").html(this.mediaTempItem.alt);
        $(".hereText").removeClass("hereTextClicked");
        setTimeout(() => {
            if (this.mediaTempItem.alt.length > 50 && this.mediaTempItem.text_style == 'not') {
                $(".hereText").html('<p>' + this.mediaTempItem.alt + '</p><span class="gradient"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,0A12,12,0,1,0,24,12,12,12,0,0,0,12,0Zm1,18V14H6V10h7V6l6,6Z"/></svg></span>');
            } else {
                $(".hereText").html(this.mediaTempItem.alt);
            }
        }, 200);
    }
    // Create Video
    // #
if (item.type == 'video') {
    this.mediaTempItem = document.createElement('video');

    this.mediaTempItem.src = item.src;
    this.mediaTempItem.alt = item.alt;
    this.mediaTempItem.controls = false;
    this.mediaTempItem.autoplay = true;
    this.mediaTempItem.muted = true;
    this.mediaTempItem.playsInline = true;
    this.mediaTempItem.setAttribute('autoplay', 'autoplay');
    this.mediaTempItem.setAttribute('playsinline', '');
    this.mediaTempItem.setAttribute('muted', '');

    const animateProgress = () => {
        if (!this.mediaTempItem || this.mediaTempItem.paused || this.mediaTempItem.ended) return;

        const currentTime = this.mediaTempItem.currentTime;
        const duration = this.mediaTempItem.duration;
        const percent = (currentTime / duration) * 100;

        progressBar.css('width', percent + '%');

        requestAnimationFrame(animateProgress); 
    };

    this.mediaTempItem.onplaying = () => {
        this.elements.loading.removeClass('show');
        startMedia(this.mediaTempItem, mediaIndex, this.mediaTempItem.duration, progressBar, direction);
        animateProgress(); 
    };

    this.mediaTempItem.oncanplay = () => {
        this.mediaTempItem.play(); 
    };

    $(".hereText").html(this.mediaTempItem.alt);
    $(".hereText").removeClass("hereTextClicked");

    setTimeout(() => {
        if (this.mediaTempItem.alt.length > 50) {
            $(".hereText").html(
                '<p>' + this.mediaTempItem.alt + '</p><span class="gradient"><svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,0A12,12,0,1,0,24,12,12,12,0,0,0,12,0Zm1,18V14H6V10h7V6l6,6Z"/></svg></span>'
            );
        } else {
            $(".hereText").html(this.mediaTempItem.alt);
        }
    }, 200);
}

    /**
     * @desc
     *
     */
    function removeMediaEvents() {
        if (this.mediaTempItem && this.mediaTempLoadFn) {
            this.mediaTempItem.removeEventListener(
                this.mediaTempItem.tagName === 'IMG' ? 'load' : 'canplay',
                this.mediaTempLoadFn
            );
        }
    }

    /**
     * @desc
     *
     */
    function startMedia(media, mediaIndex, duration, progressBar, direction) {
        content.append(media); 
    
        // Class ekle
        $(that.mediaTempItem).addClass('current-media ' + direction);
        that.mediaLoading = false;
        that.elements.loading.removeClass('show');
    
        that.mediaStartTime = Date.now();
        that.mediaProgressTick(mediaIndex, duration, progressBar);
        removeMediaEvents.call(that);
    
        if (that.mediaTempItem.tagName === 'VIDEO') { 
            const playPromise = that.mediaTempItem.play();
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        requestAnimationFrame(animateProgress); 
                    })
                    .catch((error) => {
                        console.warn("Video playback engellendi:", error);
                    });
            }
        }
    
        setTimeout(function () {
            $(that.mediaTempItem).addClass('effect');
        }, 250);
    }
    }
function generateRandomIntegerTwo(min, max) {return Math.floor(min + Math.random() * (max - min + 1));}
var NumberGeneratedTwo = generateRandomIntegerTwo(1, 7);

/**
 * @desc
 * @param {} mediaIndex -
 * @param {} itemDuration -
 */
StoryView.prototype.mediaProgressTick = function(mediaIndex, itemDuration, progressBar) {
    var that = this,
        timeDiff = 0,
        tempWidth = 0;


    // Reset progress timer
    // #
    clearInterval(this.currentMediaProgress);

    this.currentMediaProgress = setInterval(function(a) {
        if (that.mediaLoading) return;

        // Stop when removing
        // #
        if (that.removingState)
            clearInterval(that.currentMediaProgress);

        if (!that.paused) {

            // Play when state is in paused
            // #
            if (that.mediaTempItem.tagName == 'VIDEO')
                that.mediaTempItem.play();

            timeDiff = Date.now() - that.mediaStartTime;
            tempWidth = (timeDiff / 1000 / itemDuration * 100);
            tempWidth = (tempWidth + that.lastProgressVal) * 1;

            progressBar.css('width', tempWidth + '%')

            // Get next media when finished
            // #
            if (parseInt(tempWidth) >= 100) {
                that.nextMedia();
            }
        }
        //
        // #
        else {
            that.lastProgressVal = tempWidth;
            that.mediaStartTime = Date.now();

            // Pause video when story paused
            // #
            if (that.mediaTempItem.tagName == 'VIDEO')
                that.mediaTempItem.pause();
        }
    });
}

/**
 * @desc
 * @param {} fromClick -
 */
StoryView.prototype.nextMedia = function(fromClick) {
    // if user clicked next close story view
    // on last media item of last story
    if (this.currentMedia + 1 == this.mediaItems.length && this.currentStory.next().length == 0 && fromClick) {
        this.closeView();
        return;
    }

    var next = this.currentMedia + 1 == this.mediaItems.length ?
        this.finish() :
        this.currentMedia++;


    // Show next media
    // #
    if (!!Number(next) || next === 0)
        this.showMedia(this.currentMedia, 'next');
}

/**
 * @desc
 * @param {} fromClick -
 */
StoryView.prototype.prevMedia = function(fromClick) {

    var prev = this.currentMedia - 1 == -1 && this.currentStory && this.currentStory.prev().length == 0 ?
        this.finish(true) :
        this.currentMedia - 1 == -1 && this.currentStory.prev().length == 0 ?
        this.currentMedia = 0 :
        this.currentMedia--;

    // Show next media
    // #
    if (!!Number(prev) || prev === 0)
        this.showMedia(this.currentMedia, 'prev');
}

/**
 * @desc
 * @param {} mediaIndex -
 * @param {} prev -
 */
StoryView.prototype.finish = function(prev) {
    var story;

    clearInterval(this.currentMediaProgress);

    // Get next story
    // #
    if (!prev && this.currentStory.data().hasNext) {
        story = this.currentStory.next();
        this.currentMedia = 0;
    }
    // Get prev story
    // #
    else if (prev && this.currentStory.prev().length > 0) {
        story = this.currentStory.prev();
        this.currentMedia = story.find('.media li').length - 1;
    }

    if (story) {
        this.mediaItems = [];
        this.currentStory.removeClass('activated');
        this.openStory(story, true);
    } else this.options.autoClose && this.closeView();
}
    window.StoryView = StoryView;
    window.StoryViewFlagger = StoryViewFlagger;
})(jQuery, window, document);