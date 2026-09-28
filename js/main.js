(function ($) {
    "use strict";

    // Enforce permanent mute on all video elements
    function enforceMute() {
        document.querySelectorAll('video').forEach(function (video) {
            video.muted = true;
            video.defaultMuted = true;
            video.volume = 0;
            video.addEventListener('volumechange', function () {
                if (!video.muted || video.volume > 0) {
                    video.muted = true;
                    video.volume = 0;
                }
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', enforceMute);
    } else {
        enforceMute();
    }

    // Instantly hide spinner if present
    if ($('#spinner').length > 0) {
        $('#spinner').removeClass('show').hide();
    }
    
    // Initiate WOW.js safely
    if (typeof WOW !== 'undefined') {
        try {
            new WOW().init();
        } catch (e) {
            console.log(e);
        }
    }

    // Sticky Navbar Setup
    $(window).scroll(function () {
        if ($(this).scrollTop() > 30) {
            $('.sticky-top').addClass('shadow-sm');
        } else {
            $('.sticky-top').removeClass('shadow-sm');
        }
    });

})(jQuery);
