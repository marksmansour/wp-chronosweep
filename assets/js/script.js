$(function () {
    /*********************
     ALL CLICKS
     *********************/
    $("input, form").attr("autocomplete", "off");
    $("#billing_email, #username, #reg_email").attr("autocomplete", "new-email");
    $("#account_password, #password, #reg_password").attr("autocomplete", "new-password");

    $('body').on('click', 'a[data-scroll]', function (e) {
        e.preventDefault();
        var el = $(this),
            sel = el.data('scroll');
        $('html, body').animate({
            'scrollTop': $(sel).offset().top
        }, 1000);
    });

    //Mobile Navabar
    $('body').on('click', '.menuMenuBtn', function () {
        if ($('html').hasClass('openMobileMenu')) {
            $('.viewport').removeClass('lock');
            $('html').removeClass('openMobileMenu');
        } else {
            $('.viewport').addClass('lock');
            $('html').addClass('openMobileMenu');
        }
    });

    //Image Accordions
    $('body').on('click', '.accordionTitle', function () {
        var p = $(this).parent().parent().attr('id');
        if ($(this).hasClass('active')) {
            $(this).next('.accordionText').slideUp('fast');
            $(this).removeClass("active");
        } else {
            $('#' + p).find('.accordionTitle').removeClass("active");
            $('#' + p).find('.accordionText').slideUp('fast');
            $(this).addClass("active");
            $(this).next('.accordionText').slideDown('fast');
            $('#' + p).find('.accordionTitle').not(this).removeClass("active");
            $('#' + p).find('.accordionText').not($(this).next('.accordionText')).slideUp('fast');
        }
    });

    //Sidebar Dropdown
    $('body').on('click', '.sideBarPlaceholder', function () {
        if ($('.sideBarLinkListWrap').hasClass('show')) {
            $('.sideBarLinkListWrap').removeClass('show');
        } else {
            $('.sideBarLinkListWrap').addClass('show');
        }
    });

    $('body').on('click', '.tabItem a', function () {
        $('.dashBoardTap').css('display', 'none');
        $('.tabItem a').removeClass('tabActiveBlue');
        $(this).addClass('tabActiveBlue');
        $('#' + $(this).attr('data-id')).css('display', 'block');

    });

    $('body').on('submit', '#subscribeForm', function (e) {
        e.preventDefault();
        $('.footerSubmitBtn').addClass('loadingBtn');
        var formElements = $('#subscribeForm').serialize();
        formElements += '&action=productSubscribeForm';
        $.ajaxq.abort('productSubscribeForm');
        $.ajaxq('productSubscribeForm', {
            url: ajax_url,
            data: formElements,
            type: 'post',
            success: function (data) {
                $('.footerSubmitBtn').removeClass('loadingBtn');
                if (data == 200) {
                    $('#subscribeForm .formRow').css({
                        'display': 'none',
                    })
                    $('#subscribeForm .successMsg').css({
                        'display': 'flex',
                    })
                } else {
                    productPopup.showToast('Try again later');
                }
            }
        })
    });

    $('body').on('submit', '.productSubscribeForm', function (e) {
        e.preventDefault();
        var id = $(this).attr('id');
        $('.submitBtn').addClass('loadingBtn');
        var formElements = $('#' + id).serialize();
        formElements += '&action=productSubscribeForm';
        $.ajaxq.abort('productSubscribeForm');
        $.ajaxq('productSubscribeForm', {
            url: ajax_url,
            data: formElements,
            type: 'post',
            success: function (data) {
                $('.submitBtn').removeClass('loadingBtn');
                if (data == 200) {
                    $('.productSubscribeForm .formRow').css({
                        'display': 'none',
                    })
                    $('.productSubscribeForm .successMsg').css({
                        'display': 'flex',
                    })
                } else {
                    productPopup.showToast('Try again later');
                }
            }
        })
    });

    $('#account-details-form').on('submit', function (e) {
        e.preventDefault();
        $('#account-details-form .btn').addClass('loadingBtn');
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: ajax_url,
            data: formData + '&action=save_account_details_ajax',
            dataType: "json",
            success: function (response) {
                $('#account-details-form .btn').removeClass('loadingBtn');
                if (response.status == true) {
                    var userData = $.parseJSON(response.data);
                    if (userData.firstname) {
                        $('#account_first_name').val(userData.firstname);
                    }
                    if (userData.lastName) {
                        $('#account_last_name').val(userData.lastName);
                    }
                    if (userData.email) {
                        $('#account_email').val(userData.email);
                    }
                    if (userData.phone) {
                        $('#phone').val(userData.phone);
                    }
                    if (userData.referralId != "") {
                        $('#referId').val(userData.referralId);
                    }
                    productPopup.showToast(response.message, 'success');
                } else {
                    productPopup.showToast(response.message);
                }
            },
            error: function (xhr, status, error) {

            }
        });
    });

    $('#billing-details-form').on('submit', function (e) {
        e.preventDefault();
        $('#billing-details-form .btn').addClass('loadingBtn');
        var formData = $(this).serialize();
        $.ajax({
            type: 'POST',
            url: ajax_url,
            data: formData + '&action=save_billing_details_ajax',
            dataType: "json",
            success: function (response) {
                $('#billing-details-form .btn').removeClass('loadingBtn');
                if (response.status == true) {
                    var userData = $.parseJSON(response.data);
                    productPopup.showToast(response.message, 'success');
                } else {
                    productPopup.showToast(response.message);
                }
            },
            error: function (xhr, status, error) {

            }
        });
    });

    function showPopup() {
        $('#popup.accountCreatePopup').fadeIn();
        $('.viewport').addClass('lock');
    }

    function closePopup() {
        $('#popup.accountCreatePopup').fadeOut();
        var nextDay = new Date();
        nextDay.setDate(nextDay.getDate() + 1);
        document.cookie = "popupClosed=true; expires=" + nextDay.toUTCString() + "; path=/";
        $('.viewport').removeClass('lock');
    }

    // Check if the cookie exists
    function checkCookie() {
        return document.cookie.split(';').some((item) => item.trim().startsWith('popupClosed='));
    }

    // If the cookie doesn't exist, show the popup
    if (!checkCookie() && $('#popup.accountCreatePopup')[0]) {
        // Show the popup after 5 seconds
        setTimeout(showPopup, 10000);
    }

    // Event handler for closing the popup
    $('#closeBtn').click(closePopup);

    $('body').on('click', '.popUpBtn', function () {
        if ($(this).attr('data-live-draw-link')) {
            var url = $(this).attr('data-live-draw-link');
            $('#instaVideoLink').attr('src', url);
        }
        $('#popup').fadeIn();
        $('.viewport').addClass('lock');
    });

    $('body').on('click', '.close', function () {
        $('#popup').fadeOut();
        $('#instaVideoLink').attr("src",'');
        $('.viewport').removeClass('lock');
    });

    $('body').on('click', '.moreDetailsBtn', function () {
        if ($('html').hasClass('mobileProductDetailsActive')) {
            $('html').removeClass("mobileProductDetailsActive");
            $('.productDetailsOnlyMobileWrap').slideUp('fast');
        } else {
            $('html').addClass("mobileProductDetailsActive");
            $('.productDetailsOnlyMobileWrap').slideDown('fast');
        }
    });

    //addToCartBtn
    $('body').on('click', '.addToCartBtn', function () {
        let productId = $(this).attr('data-product-id');
        let userId = $(this).attr('data-check');
        let itemQuantity = $('.itemQuantity').val();
        let thiss = $(this);
        thiss.addClass('loadingBtn');
        browser.updateCart(productId, itemQuantity, thiss, userId);
    });

    //Ticket Value Change
    $('body').on('change', '.ticketCheckbox', function () {
        var ticket = $(".ticketCheckbox:checked").val();
        var price = $(".ticketCheckbox:checked").attr('data-value');
        var odds = $(".ticketCheckbox:checked").attr('data-odds');
        $('.counterButtonValue').val(ticket);
        $('.billAmount').text(price + ".00");
        var label = "Ticket";
        if (parseInt(ticket) != 1)
            label = "Tickets";
        $('.counterButtonLabel').text(ticket + " " + label);
        $('.oddsRightValue').text(odds);
    });

    //counterButton
    if ($('.counterButton')[0]) {
        var json = JSON.parse($('.counterButton').attr('data-json'));
        var currentIndex = 0;

        $('body').on('click', '.counterButtonDecrement', function () {

            if ($("input[name='ticket']:checked").val()) {
                currentIndex = parseInt($("input[name='ticket']:checked").val() - 1);
            }

            var limit = atob($('.counterButton').attr('data-limit'));

            var inputBox = $(this).siblings('.itemQuantity');
            var itemName = $(this).attr('data-item');

            if (json.hasOwnProperty(itemName)) {
                currentIndex = currentIndex === 0 ? Object.keys(json).length - 1 : currentIndex - 1;
                if (currentIndex < limit) {
                    var id = parseInt(json[Object.keys(json)[currentIndex]].quantity);
                    if ($('#ticketCheckbox' + id)[0]) {
                        $('#ticketCheckbox' + id).prop('checked', true);
                    } else {
                        $('.ticketCheckbox').prop('checked', false);
                    }
                    var price = json[Object.keys(json)[currentIndex]].price;
                    $('.billAmount').text(price + ".00");
                    var odds = json[Object.keys(json)[currentIndex]].odds;
                    roundOdds = odds.split(':');
                    var roundedNumber = Math.round(parseFloat(roundOdds[1]));
                    $('.oddsRightValue').text(roundOdds[0] + ':' + roundedNumber);
                    inputBox.val(id);
                    var label = "Ticket";
                    if (parseInt(json[Object.keys(json)[currentIndex]]) != 1)
                        label = "Tickets";
                    $('.counterButtonLabel').text(id + " " + label);
                }
            }
        })

        $('body').on('click', '.counterButtonIncrement', function () {

            if ($("input[name='ticket']:checked").val()) {
                currentIndex = parseInt($("input[name='ticket']:checked").val() - 1);
            }

            var limit = atob($('.counterButton').attr('data-limit'));

            var inputBox = $(this).siblings('.itemQuantity');
            var currentValue = parseInt(inputBox.val());
            if (currentValue < limit) {
                var itemName = $(this).attr('data-item');
                if (json.hasOwnProperty(itemName)) {
                    currentIndex = (currentIndex + 1) % Object.keys(json).length;
                    var id = parseInt(json[Object.keys(json)[currentIndex]].quantity);
                    if ($('#ticketCheckbox' + id)[0]) {
                        $('#ticketCheckbox' + id).prop('checked', true);
                    } else {
                        $('.ticketCheckbox').prop('checked', false);
                    }
                    var price = json[Object.keys(json)[currentIndex]].price;
                    $('.billAmount').text(price + ".00");
                    var odds = json[Object.keys(json)[currentIndex]].odds;
                    roundOdds = odds.split(':');
                    var roundedNumber = Math.round(parseFloat(roundOdds[1]));
                    $('.oddsRightValue').text(roundOdds[0] + ':' + roundedNumber);
                    inputBox.val(id);
                    var label = "Ticket";
                    if (parseInt(json[Object.keys(json)[currentIndex]]) != 1)
                        label = "Tickets";
                    $('.counterButtonLabel').text(id + " " + label);
                }
            } else {
                productPopup.showToast('Limit reached');
            }
        })
    }

    if ($('.ticketCheckbox')[0]) {
        setTimeout(function () {
            $('.counterButtonLabel').text($(".ticketCheckbox:checked").val() + " Ticket");
            $('.counterButtonValue').val($(".ticketCheckbox:checked").val());
            var price = $(".ticketCheckbox:checked").attr('data-value');
            var odds = $(".ticketCheckbox:checked").attr('data-odds');
            $('.oddsRightValue').text(odds);
            $('.billAmount').text(price + ".00");
        }, 200);
    }

    if ($('.product-quantity')[0]) {
        var json = JSON.parse($('.product-quantity').attr('data-json'));
        var currentIndex = 0;

        $('body').on('click', '.cartQuantityDecreaseBtn', function (e) {
            e.preventDefault();
            var inputBox = $("#" + $(this).attr('data-id'));
            var itemName = $('.cartQuantityDecreaseBtn').attr('data-item');

            var limit = atob($('.product-quantity').attr('data-limit'));
            if (json.hasOwnProperty(itemName)) {
                var inputBoxValue = parseInt(inputBox.val(), 10);
                currentIndex = inputBoxValue - 1;
                if (currentIndex < limit) {
                    inputBox.val(parseInt(json[Object.keys(json)[currentIndex - 1]].quantity));
                    $('.update_cart').removeAttr("disabled");
                } else {
                    currentIndex = 1;
                }
            }
        })

        $('body').on('click', '.cartQuantityIncreaseBtn', function (e) {
            e.preventDefault();
            var itemName = $('.cartQuantityIncreaseBtn').attr('data-item');
            var inputBox = $("#" + $(this).attr('data-id'));
            var limit = atob($('.product-quantity').attr('data-limit'));
            if (json.hasOwnProperty(itemName)) {
                var inputBoxValue = parseInt(inputBox.val(), 10);
                currentIndex = inputBoxValue;
                if (currentIndex < limit) {
                    inputBox.val(parseInt(json[Object.keys(json)[currentIndex]].quantity));
                    $('.update_cart').removeAttr("disabled");
                } else {
                    currentIndex = limit - 1;
                }
            }
        });
    }

    // Input Placeholder
    $('body').on('blur', 'input,select', function (e) {
        var val = $(this).val();
        if ($.trim(val) == "")
            $(this).parent().parent().removeClass('hasInput');
        else
            $(this).parent().parent().addClass('hasInput');
    });
    $('body').on('focus', '.customInputStyle', function (e) {
        $(this).addClass('hasInput');
    });

    $('body').on('click', '.applyCouponBtn', function (event) {
        event.preventDefault();
        var couponCode = $('#coupon_code').val();
        $('.applyCouponBtn').addClass('loadingBtn');
        $.ajax({
            type: 'POST',
            url: ajax_url,
            data: {
                action: 'apply_custom_coupon',
                coupon_code: couponCode,
            },
            success: function (response) {
                location.reload();
            },
        });
    });

    if ($('.woocommerce-error').length > 0) {
        $('body').addClass('errorMsgYes');
    };

    //CopyBtn
    $('body').on("click", '.copyBtn', function () {
        var input = $(".referId");
        input.select();
        document.execCommand("copy");
        $(".copyBtnRow").addClass("active");
        window.getSelection().removeAllRanges();
        setTimeout(function () {
            $(".copyBtnRow").removeClass("active");
        }, 2500);
    });

    //Popform
    $('body').on('keyup', '.accountCreatePopupForm input', function () {
        var disableButton = false;
        $('.accountCreatePopupForm input').each(function () {
            if ($(this).attr('type') === 'email') {
                if (!browser.isValidEmail($(this).val())) {
                    disableButton = true;
                    return false; // Exit the loop
                }
            } else if ($(this).attr('type') === 'number') {
                if ($(this).is(':visible')) {
                    if ($(this).val() === '') {
                        disableButton = true;
                        return false; // Exit the loop
                    }
                }
            } else if ($(this).val() === '') {
                disableButton = true;
                return false; // Exit the loop
            }
        });
        $('.accountCreatePopupForm button').prop('disabled', disableButton);
    });

    $('.accountCreatePopupForm').on('submit', function (e) {
        e.preventDefault();

        $('.accountCreatePopupForm button').addClass('loadingBtn');
        var formElements = $('.accountCreatePopupForm').serialize();
        formElements += '&action=accountCreatePopupForm';
        $.ajaxq.abort('accountCreatePopupForm');
        $.ajaxq('accountCreatePopupForm', {
            url: ajax_url,
            dataType: 'json',
            data: formElements,
            type: 'post',
            success: function (data) {
                $('.accountCreatePopupForm button').removeClass('loadingBtn');
                if (data.status == true) {
                    closePopup();
                    productPopup.showToast(data.message, 'success');
                } else {
                    productPopup.showToast(data.message);
                }
            }
        })
    });

    if ($('.cookiePrefernceWrap').length > 0) {
        $('body').on('click', '.cookiePrefernce', function () {
            window.displayPreferenceModal()
        })
    }

    //All the click events must go here

    /********************
     ONE TIME INIT
     *********************/
    browser.setup(1);

    $(window).resize(function () {
        browser.setup(0);
    });

    $(window).scroll(browser.scrollEvent);
});

$(window).on('load', function () {
    browser.scrollEvent();
    setTimeout(function () {
        $('#loaderSpinner').hide();
    }, 500);

});

$(document).ready(function () {
    $(document).ajaxComplete(function () {
        if ($('body').hasClass('woocommerce-checkout') || $('body').hasClass('woocommerce-cart')) {
            $('html, body').stop();
        }
    });
});
$(document).ready(function () {
    // Check if the element exists
    if ($('.loginForm .woocommerce-form .mo-openid-app-icons .mo_btn-google').length > 0) {
        var container = $('.loginForm .woocommerce-form .mo-openid-app-icons');
        var google_login_btn_img = container.find('.mo_btn-google img');
        var google_login_btn = container.find('.mo_btn-google');
        if (google_login_btn.length > 0) {
            google_login_btn.each(function () {
                this.style.setProperty('padding', '8px 22px', 'important');
                this.style.setProperty('width', '100%', 'important');
                this.style.setProperty('display', 'flex', 'important');
                this.style.setProperty('justify-content', 'center', 'important');
                this.style.setProperty('align-items', 'center', 'important');
                this.style.setProperty('border-radius', '12px', 'important');
            });
        }
        if (google_login_btn_img.length > 0) {
            google_login_btn_img.each(function () {
                this.style.setProperty('padding-top', '0px', 'important');
                this.style.setProperty('position', 'static', 'important');
                this.style.setProperty('transform', 'none', 'important')
                this.style.setProperty('border', 'none', 'important')
                this.style.setProperty('margin-right', '10px', 'important')
            });
        }
    }
});

$(document).ready(function () {
    $('body').on('click', '.navTabItem', function () {
        const tab = $(this);
        const target = $(tab.data('tab-target'));

        $('.navTabContent').removeClass('active');
        $('.navTabItem').removeClass('active');

        tab.addClass('active');
        target.addClass('active');
    });

    // var tabItems = document.querySelectorAll('.navTabItem');
    // var tabContents = document.querySelectorAll('.navTabContent');

    // // Function to remove and add 'active' class
    // function switchTab(newActive) {
    //     // Remove 'active' class from all tabs and contents
    //     tabItems.forEach(item => {
    //         item.classList.remove('active');
    //     });
    //     tabContents.forEach(content => {
    //         content.classList.remove('active');
    //     });

    //     // Add 'active' class to the clicked tab and the corresponding content
    //     newActive.classList.add('active');
    //     var targetId = newActive.getAttribute('data-tab-target');
    //     var targetContent = document.querySelector(targetId);
    //     if (targetContent) {
    //         targetContent.classList.add('active');
    //     }
    // }

    // // Attach click event to each tab item
    // tabItems.forEach(item => {
    //     item.addEventListener('click', function() {
    //         switchTab(item);
    //     });
    // });

});

$(document).ready(function($) {
        // Function to save input values to local storage
        function saveToLocalStorage() {
            $('form.checkout').find('input, select, textarea').each(function() {
                var input = $(this);
                var id = input.attr('id');
                if (id && id != "account_password") {
                    localStorage.setItem(id, input.val());
                }
            });
        }

        // Save values on input change
        $('form.checkout').on('change', 'input, select, textarea', saveToLocalStorage);

        // Populate inputs with saved values from local storage
        $('form.checkout').find('input, select, textarea').each(function() {
            var input = $(this);
            var id = input.attr('id');
            if (id && localStorage.getItem(id)) {
                if(id != "account_password"){
                    input.val(localStorage.getItem(id));
                    if($('#'+id).parent().parent().hasClass('hasInput')){
                    }else{
                        $('#'+id).parent().parent().addClass('hasInput');
                    }
                }else{
                    localStorage.removeItem('account_password');
                    if($('#'+id).parent().parent().hasClass('hasInput')){
                    }else{
                        $('#'+id).parent().parent().addClass('hasInput');
                    }
                }
            }
        });

        // Clear local storage on form submit
        $('form.checkout').on('submit', function() {
            localStorage.clear();
        });
});