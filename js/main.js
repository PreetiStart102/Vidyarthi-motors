(function ($) {
    "use strict";

    // Initialize default mute on video elements for browser autoplay compliance
    function initVideoMute() {
        document.querySelectorAll('video[autoplay]').forEach(function (video) {
            video.muted = true;
            video.defaultMuted = true;
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initVideoMute);
    } else {
        initVideoMute();
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

    // Testimonials Dynamic Category Filter
    $(document).ready(function () {
        $('.testimonial-filter-btn').on('click', function () {
            $('.testimonial-filter-btn').removeClass('active');
            $(this).addClass('active');

            var filter = $(this).attr('data-filter');
            var carousel = $('#testimonialCarousel');

            if (filter === 'all') {
                carousel.find('.carousel-item').removeClass('d-none active');
                carousel.find('.carousel-item').first().addClass('active');
            } else {
                carousel.find('.carousel-item').each(function () {
                    var categories = $(this).attr('data-category') || '';
                    if (categories.indexOf(filter) !== -1) {
                        $(this).removeClass('d-none').addClass('active').siblings().removeClass('active');
                    } else {
                        $(this).addClass('d-none').removeClass('active');
                    }
                });
            }
        });
    });

})(jQuery);
