//Scrollbar toggle
var scrollbar = {
    disable: function(classname, callback, duration) {
        //Disable all scroll events
        $(window).off("scroll", browser.scrollEvent);
        //Pause all visibility speciftc events on viewport
        browser.pauseAllIntensiveEvents();
        //Save scroll position
        browser._position = $(window).scrollTop();
        //Fake it using position:fixed on .viewport
        $('.viewport').css('top', (-1 * browser._position) + 'px').addClass('lock');
        //Set scroll postion to top of the window
        $(window).scrollTop(0);
        //Add classname
        $('html').addClass(classname);
        //Execute callback function
        if(typeof callback != "undefined") {
            if(typeof duration == "undefined") 
                duration = 500;
            setTimeout(function() {
                if ($.isFunction(callback)){
                    callback();
                }
            }, duration);
        }
    },
    enable: function(classname, callback, duration) {
        //Check undefined
        if(typeof duration == "undefined") 
            duration = 500;
        //Remove classname
        $('html').removeClass(classname);
        setTimeout(function() {
            //Remove faked position from .viewport
            $('.viewport').removeClass('lock').css('top', 'auto');
            //Set the previous scroll position of viewport to browser
            $(window).scrollTop(browser._position);
            //Execute callback function
            if ($.isFunction(callback)){
                callback();
            }
            //Resume all paused events
            browser.playVisibleEvents();
            //Set scroll postion index to zero
            browser._position = 0;
            //Enable all scroll events
            $(window).scroll(browser.scrollEvent);
        }, duration);
    },
    touchListen: function() {
        $(document).one('click touchstart', function closeOverlay (e) {
            if($('.overlay').has(e.target).length === 0)
                scrollbar.enable('has-overlay');
            else
                $(document).one('click touchstart', closeOverlay);
            return false;
        });
    }
};