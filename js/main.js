(function ($) {
    "use strict";

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
