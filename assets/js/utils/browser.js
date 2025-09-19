// Create cross browser requestAnimationFrame method:
window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame || function (f) {
    setTimeout(f, 1000 / 60)
};

//Page functions
var browser = {
    _csrf: null,
    _width: 0,
    _height: 0,
    _header_height: 0,
    _filter_position: 0,
    _position: 0,
    _isotope: null,
    _coords: [],
    _mobile_logo_swiper: '',
    _mobile_logo_swiper_init: false,
    _mobile_textcontent_swiper: '',
    _mobile_textcontent_swiper_init: false,
    setup: function (init) {
        this._width = $(window).width();
        this._height = $(window).height();

        if (init === 1) {
            browser.initWow();
            browser.msgWrap();
            if($('.wc-empty-cart-message')[0]){
                $('body').addClass('empty-cart');
                $('.shoppingCount').removeClass('active');
            }else{
                $('body').removeClass('empty-cart');
            }
            browser.testimonialsList();
            browser.contributedLogoSlider();

            //Code that should be executed only once goes here

            adjustHeights.setHeightByAll('.cardTextContentEqualHeight');
            adjustHeights.setHeightByAll('.textListItemEqualHeight');
            if($('.error404')[0]){
                browser.notFoundPageAniamtion();
            }
            if($('.productBanner')[0]){
                browser.bannerProductBanner();
                browser.productBannerCountDown();
            }
            if($('.imageSlider')[0]){
                $('.imageSlider').each(function(index, element){
                    browser.profileGallery('#'+element.id);
                });
            }
            if($('.leftImageAndRightTextLogoSlider')[0]){
                browser.mobileOnlySlider('.leftImageAndRightTextLogoSlider');
            }
            if($('.leftImageAndRightTextTextContentSlider')[0]){
                browser.mobileOnlySlider('.leftImageAndRightTextTextContentSlider');
            }
        }

        //Code that should execute on window resize goes here
        if($('.productBanner')[0]){
            browser.productBannerCountDown();
        }
        if($('.leftImageAndRightTextLogoSlider')[0]){
            browser.mobileOnlySlider('.leftImageAndRightTextLogoSlider');
        }
        if($('.leftImageAndRightTextTextContentSlider')[0]){
            browser.mobileOnlySlider('.leftImageAndRightTextTextContentSlider');
        }
        adjustHeights.setHeightByAll('.cardTextContentEqualHeight');
    },
    scrollEvent: function (init) {
        requestAnimationFrame(function () {
            //Add layer behind sticky menu
            var st = $(window).scrollTop();
            if (st >= 100)
                $('html').addClass('has-scrolled');
            else
                $('html').removeClass('has-scrolled');

            //Pause or play Slideshows based on visibility
            browser.playVisibleEvents();
        });
    },
    pauseAllIntensiveEvents: function () {
        //All slideshow/videos that needs to be paused should be written here
        $('.cycle').each(function () {
            $(this).cycle('pause');
        });
    },
    playVisibleEvents: function () {
        //Slideshow/videos that needs to be paused when out of view should be written here
        $('.cycle').each(function () {
            var slider = $(this);
            if (slider.is(':in-viewport'))
                slider.cycle('resume');
            else
                slider.cycle('pause');
        });
    },
    get: function (key, default_) {
        //Function to get the value of url parameters
        if (default_ == null)
            default_ = "";
        key = key.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
        var regex = new RegExp("[\\?&]" + key + "=([^&#]*)");
        var qs = regex.exec(window.location.href);
        if (qs == null)
            return default_;
        else
            return qs[1];
    },
    testimonialsList: function() {
        if($('.testimonialsListSlider')[0]){
            var slidesPerView = 1.4;
            var slidesPerView1920 = 1.4;
            var slidesPerView1440 = 1.4;
            var slidesPerView1028 = 1.4;
            var slidesPerView840 = 1.1;
            var slidesPerView740 = 1.1;
            var slidesPerView600 = 1.1;
            var slidesPerView420 = 1.03;
            var slidesPerView390 = 1.03;
            var slidesPerView300 = 1;

            var testimonialsList = new Swiper(`${'.testimonialsListSlider'}`, {
                slidesPerView: slidesPerView,
                spaceBetween: 24,
                loop: false,
                grabCursor: false,
                initialSlide: 0,
                speed: 200,
                autoplay:false,
                scrollbar: {
                    el: scrollbar,
                    draggable: true,
                    hide: false,
                },
                freeMode: true,
                mousewheel: {
                    forceToAxis: true,
                    releaseOnEdges: true,
                },
                breakpoints: {
                    1920: {
                        spaceBetween: 24,
                        slidesPerView: slidesPerView1920,
                    },
                    1920: {
                        spaceBetween: 24,
                        slidesPerView: slidesPerView1920,
                    },
                    1440: {
                        spaceBetween: 24,
                        slidesPerView: slidesPerView1440,
                    },
                    1028: {
                        spaceBetween: 24,
                        slidesPerView: slidesPerView1028,
                    },
                    840: {
                        spaceBetween: 16,
                        slidesPerView: slidesPerView840,
                    },
                    740: {
                        spaceBetween: 16,
                        slidesPerView: slidesPerView740,
                    },
                    600: {
                        spaceBetween: 16,
                        slidesPerView: slidesPerView600,
                    },
                    420: {
                        spaceBetween: 16,
                        slidesPerView: slidesPerView420,
                    },
                    390: {
                        spaceBetween: 16,
                        slidesPerView: slidesPerView390,
                    },
                    340: {
                        spaceBetween: 8,
                        slidesPerView: 1,
                    },
                    300: {
                        spaceBetween: 8,
                        slidesPerView: 1,
                    },
                    280: {
                        spaceBetween: 8,
                        slidesPerView: 1,
                    },
                    240: {
                        spaceBetween: 8,
                        slidesPerView: 1,
                    },
                },
                on: {
                    init: function() {
                    }
                }
            });
        }
    },
    contributedLogoSlider: function(){
        if($('.contributedLogoSlider')[0]){
            var slidesPerView = 6;
            var slidesPerView1920 = 6;
            var slidesPerView1440 = 6;
            var slidesPerView1028 = 6;
            var slidesPerView840 = 6;
            var slidesPerView740 = 3;
            var slidesPerView600 = 3;
            var slidesPerView420 = 2;
            var slidesPerView390 = 2;
            var slidesPerView300 = 1;

            var testimonialsList = new Swiper(`${'.contributedLogoSlider'}`, {
                slidesPerView: slidesPerView,
                spaceBetween: 24,
                loop: false,
                grabCursor: false,
                initialSlide: 0,
                speed: 200,
                autoplay:false,
                scrollbar: {
                    el: scrollbar,
                    draggable: true,
                    hide: false,
                },
                breakpoints: {
                    1920: {
                        spaceBetween: 74,
                        slidesPerView: slidesPerView1920,
                    },
                    1440: {
                        spaceBetween: 74,
                        slidesPerView: slidesPerView1440,
                    },
                    1028: {
                        spaceBetween: 50,
                        slidesPerView: slidesPerView1028,
                    },
                    840: {
                        spaceBetween: 40,
                        slidesPerView: slidesPerView840,
                    },
                    740: {
                        spaceBetween: 32,
                        slidesPerView: slidesPerView740,
                    },
                    600: {
                        spaceBetween: 32,
                        slidesPerView: slidesPerView600,
                    },
                    420: {
                        spaceBetween: 16,
                        slidesPerView: slidesPerView420,
                    },
                    390: {
                        spaceBetween: 16,
                        slidesPerView: slidesPerView390,
                    },
                    340: {
                        spaceBetween: 8,
                        slidesPerView: 1,
                    },
                    300: {
                        spaceBetween: 8,
                        slidesPerView: 1,
                    },
                    280: {
                        spaceBetween: 8,
                        slidesPerView: 1,
                    },
                    240: {
                        spaceBetween: 8,
                        slidesPerView: 1,
                    },
                },
                on: {
                    init: function() {
                    }
                }
            });
        }
    },
    bannerProductBanner: function() {
        var productGalleryBottomSlider = new Swiper('.productGalleryBottomSlider', {
            slidesPerView: 4,
            spaceBetween: 16,
            freeMode: false,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            touchRatio: 1, // Adjust to make touch more sensitive
            allowTouchMove: true, // Ensure touch/move is enabled
            touchStartPreventDefault: false // Don't prevent default action
        });
    
        var productGalleryTopSlider = new Swiper('.productGalleryTopSlider', {
            slidesPerView: 1,
            loop: true,
            autoplay: false,
            effect: "fade",
            navigation: {
                nextEl: '.sliderNavWrap .nextBtn',
                prevEl: '.sliderNavWrap .prevBtn',
            },
            thumbs: {
                swiper: productGalleryBottomSlider
            },
            on: {
                slideChange: function() {
                    requestAnimationFrame(function() {
                        $('.productGalleryTopItemVideo video').each(function() {
                            this.pause();
                            this.currentTime = 0;
                        });
                        var currentSlideVideo = $(productGalleryTopSlider.slides[productGalleryTopSlider.activeIndex]).find('video')[0];
                        if (currentSlideVideo) {
                            currentSlideVideo.play();
                        }
                    });
                }
            }
        });
    
        $('.sliderNavWrap .nextBtn, .sliderNavWrap .prevBtn').on('click', function() {
            productGalleryTopSlider.slideNext();
            productGalleryTopSlider.update();
        });
    
        $('.productGalleryBottomSlider .swiper-slide').on('click', function() {
            var index = $(this).index();
            productGalleryTopSlider.slideToLoop(index);
            productGalleryTopSlider.update();
        });
    },    

    
    notFoundPageAniamtion: function(){
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 5,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#fcfcfc"
                },
                "shape": {
                    "type": "circle",
                },
                "opacity": {
                    "value": 0.5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.2,
                        "sync": false
                    }
                },
                "size": {
                    "value": 140,
                    "random": false,
                    "anim": {
                        "enable": true,
                        "speed": 10,
                        "size_min": 40,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": false,
                },
                "move": {
                    "enable": true,
                    "speed": 8,
                    "direction": "none",
                    "random": false,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": false,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": false
                    },
                    "onclick": {
                        "enable": false
                    },
                    "resize": true
                }
            },
            "retina_detect": true
        });
    },
    productBannerCountDown: function(){
        if($('.productStatus').val() == 1){
            var getDate = $('.endDate').val();
            var getDate = $('.endDate').val();
            var dateArray = getDate.split("/");
            // Assuming the date format is "dd/mm/yyyy/hh/mm/ss"
            var targetDate = new Date(dateArray[2], dateArray[1] - 1, dateArray[0], dateArray[3], dateArray[4], dateArray[5]);
            // Adjust the target date to UTC
            targetDate.setUTCHours(targetDate.getUTCHours()); // Adjust to UK time (UTC+0)

            function updateCountdown() {
                // Get the current date in UTC
                var currentDate = new Date();
                // Convert the current date to UK time
                var currentDateUK = new Date(currentDate.getTime() + (currentDate.getTimezoneOffset() * 60000) + (60 * 60000)); // Adjust to UK time (UTC+0)
                //currentDateUK.setUTCHours(currentDateUK.getUTCHours() - 1);
                currentDateUK.setUTCHours(currentDateUK.getUTCHours());

                var difference = targetDate - currentDateUK;

                if (difference <= 0) {
                    browser.productSubscribeForm();
                    if($('.rightMenu .enterBtnLink')[0])
                        $('.rightMenu .enterBtnLink').attr({
                            'href': 'https://www.instagram.com/chronosweep/',
                            'target': '_blank'
                        })
                        .text('Live instagram draw');
                        $('.postalEntryBtnWrap').hide();
                } else {
                    var days = Math.floor(difference / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((difference % (1000 * 60)) / 1000);
                    $('.countDownDates').text(days);
                    $('.countDownHours').text(hours);
                    $('.countDownMins').text(minutes);
                    $('.countDownSecs').text(seconds);
                }
            }

            setInterval(updateCountdown, 1000);


        }else{
            browser.productSubscribeForm();
            if($('.rightMenu .enterBtnLink')[0])
                $('.rightMenu .enterBtnLink').attr({
                    'href': 'https://www.instagram.com/chronosweep/',
                    'target': '_blank'
                })
                .text('Live instagram draw');
                $('.postalEntryBtnWrap').hide();
        }

    },
    productSubscribeForm: function(){
        $('.bannerEnterNowBtn').hide();
        if(browser._width > 840){
            $('#productSubscribeFormMobile').css('display','none');
            $('#productSubscribeFormDesktop').css('display','block');
        }else{
            $('#productSubscribeFormDesktop').css('display','none');
            $('#productSubscribeFormMobile').css('display','block');
        }
    },
    updateCart: function (productId, itemQuantity, thiss, userId) {
        $.ajax({
            url: `${_wp_json_url}/addToCartManually`,
            type: 'post',
            data: { productId: productId,itemQuantity: itemQuantity,userId: userId },
            dataType: "json",
            success: function (data) {
                if (thiss) {
                    if (data.status == true && data.cartCount) {
                        if(data.cartCount > 0){
                            $('.cartIconMenu').addClass('active');
                        }else{
                            if($('.cartIconMenu').hasClass('active')){
                                $('.cartIconMenu').removeClass('active');
                            }
                        }
                        window.location.href = checkout_page;
                    } else {
                        thiss.removeClass('loadingBtn');
                        productPopup.showToast(data.message);
                    }
                }
            },
            error: function (xhr, status, error) {
                if (thiss) {
                    thiss.removeClass('loadingBtn');
                    productPopup.showToast('Failed to add to cart. Please try again.');
                }
            },
            complete: function () {
                if (thiss) {
                    thiss.removeClass('loadingBtn');
                }
            }
        });
    },
    profileGallery: function(el){
        var profileGallery = new Swiper(`${el}`, {
            slidesPerView: 1,
            spaceBetween: 0,
            loop : false,
            autoplay: false,
            effect: "fade",
            pagination: {
                el: '.pagination',
                clickable: true,
            },
        });
    },
    mobileOnlySlider: function(el){
        if(el == ".leftImageAndRightTextLogoSlider"){
            if(browser._width <= 840){
                if (!browser._mobile_logo_swiper_init) {
                    browser._mobile_logo_swiper_init = true;
                    browser._mobile_logo_swiper = new Swiper(`${el}`, {
                        slidesPerView: 1.5,
                        spaceBetween: 16,
                        loop : false,
                        autoplay: false,
                        breakpoints: {
                            840: {
                                spaceBetween: 16,
                                slidesPerView: 3,
                            },
                            740: {
                                spaceBetween: 16,
                                slidesPerView: 2.5,
                            },
                            600: {
                                spaceBetween: 16,
                                slidesPerView: 1.5,
                            },
                        }
                    });
                }
            }else{
                if (browser._mobile_logo_swiper) {
                    browser._mobile_logo_swiper.destroy(true, true);
                    browser._mobile_logo_swiper_init = false;
                }
            }
        }else if(el == ".leftImageAndRightTextTextContentSlider"){
            if(browser._width <= 840){
                if (!browser._mobile_textcontent_swiper_init) {
                    browser._mobile_textcontent_swiper_init = true;
                    browser._mobile_textcontent_swiper = new Swiper(`${el}`, {
                        slidesPerView: 1.3,
                        spaceBetween: 16,
                        loop : false,
                        autoplay: false,
                    });
                }
            }else{
                if(browser._mobile_textcontent_swiper_init === true){
                    if (browser._mobile_textcontent_swiper !== undefined) {
                        browser._mobile_textcontent_swiper.destroy(true, true);
                        browser._mobile_textcontent_swiper_init = false;
                    }
                }
            }
        }
    },
    msgWrap: function(){
        if($('.woocommerce-notices-wrapper')[0]){
            var notices = $('.woocommerce-notices-wrapper').clone()
            $('.woocommerce-notices-wrapper').remove();
            $('.msgWrap').prepend(notices);
        }
    },
    isValidEmail: function(email) {
        // Regular expression for basic email validation
        var emailRegex = /\S+@\S+\.\S+/;
        return emailRegex.test(email);
    },
    initWow: function () {
        /* WOW JS */
        var wow = new WOW({
            boxClass: 'wow',
            animateClass: 'animate__animated',
            offset: 0,
            mobile: false,
            live: true,
            callback: function (box) { }
        });
        $('.wow-removed').addClass('wow').removeClass('wow-removed');
        wow.init();
        /* WOW JS */
    },
};
