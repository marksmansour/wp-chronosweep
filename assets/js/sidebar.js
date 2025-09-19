$(function() {
    $('.sidebarWithAccordion').each(function() {
        var sidebarAccordion = $(this);
        var sidebarId = sidebarAccordion.attr('data-id');
        var headerHeight = $('.header').height();
        var sectionPadding = parseInt($(`.sidebarWithAccordion[data-id="${sidebarId}"]`).css('padding-top'));
        if(sectionPadding == 0){
            sectionPadding = parseInt($(`.sidebarWithAccordion[data-id="${sidebarId}"]`).prev().css('padding-top'));
        }
        var totalHeight = headerHeight + sectionPadding;
        if ($('#wpadminbar').length) {
            totalHeight += $('#wpadminbar').height();
        }

        $(window).scroll(function() {
            headerHeight = $('.header').height();
            sectionPadding = parseInt($(`.sidebarWithAccordion[data-id="${sidebarId}"]`).css('padding-top'));
            if(sectionPadding == 0){
                sectionPadding = parseInt($(`.sidebarWithAccordion[data-id="${sidebarId}"]`).prev().css('padding-top'));
            }
            totalHeight = headerHeight + sectionPadding;
            if ($('#wpadminbar').length) {
                totalHeight += $('#wpadminbar').height();
            }
            var position = window.pageYOffset;
            sidebarAccordion.find('.faqWrap').each(function () {
                var target = $(this).offset().top - totalHeight;
                var id = $(this).attr('id');
                var navLinks = $(`.sidebarWithAccordion[data-id="${sidebarId}"] .sideBarLinkListItem a`);
                if (position >= target) {
                    navLinks.parent().removeClass('sideBarLinkListItemActive');
                    sidebarAccordion.find('.sideBarLinkListItem a[data-id="'+id+'"]').parent().addClass('sideBarLinkListItemActive');
                    sidebarAccordion.find('.mobileSideBarPlaceholderLabelValue').text(sidebarAccordion.find('.sideBarLinkListItem a[data-id="'+id+'"] .sideBarLinkListItemLabel').attr('data-title'));
                }
            });
        });

        $(window).resize(function () {
            headerHeight = $('.header').height();
            sectionPadding = parseInt($(`.sidebarWithAccordion[data-id="${sidebarId}"]`).css('padding-top'));
            if(sectionPadding == 0){
                sectionPadding = parseInt($(`.sidebarWithAccordion[data-id="${sidebarId}"]`).prev().css('padding-top'));
            }
            totalHeight = headerHeight + sectionPadding;
            if ($('#wpadminbar').length) {
                totalHeight += $('#wpadminbar').height();
            }
            sidebar.options.topSpacing = totalHeight;
            sidebar.updateSticky();
        });

        var sidebar = new StickySidebar(`.sidebarWithAccordion[data-id="${sidebarId}"] #sideBar`, {
            containerSelector: `.sidebarWithAccordion[data-id="${sidebarId}"] #sideBarYes`,
            innerWrapperSelector: `.sidebarWithAccordion[data-id="${sidebarId}"] #sideBarWrap`,
            topSpacing: totalHeight,
            bottomSpacing: totalHeight,
            resizeSensor: false
        });

        $(`.sidebarWithAccordion[data-id="${sidebarId}"] .sidebarWithAccordionSideBarLinkList a`).on('click', function(event) {
            var getDiv = $(this).attr('data-id');
            if($(`.sidebarWithAccordion[data-id="${sidebarId}"] .mobileSideBarPlaceholderLabelValue`).css("display") == "block" && getDiv == 1){
                totalHeight = totalHeight + $('.sidebarWithAccordion .mobileSideBarPlaceholderLabelValue').height();
            }
            totalHeight = totalHeight;
            totalHeight = totalHeight - 1;
            $('.sidebarWithAccordion .mobileSideBarPlaceholderLabelValue').text($(this).children().attr('data-title'));
            $('html,body').animate({scrollTop: $(`.sidebarWithAccordion[data-id="${sidebarId}"] .faqWrap${getDiv}`).offset().top - totalHeight},'fast');
            $('.sidebarWithAccordionSideBarLinkList .sideBarLinkListWrap').removeClass('show');
        });

        $(`.sidebarWithAccordion[data-id="${sidebarId}"] .mobileSideBarPlaceholderLabelValue`).text($(`.sidebarWithAccordion[data-id="${sidebarId}"] .sideBarLinkListItem a:first .sideBarLinkListItemLabel`).attr('data-title'));
    });    

    var $imageAccordions = $('.imageAccordion[data-id]');

    if ($imageAccordions.length > 0 && $(window).width() > 840) {
        $imageAccordions.each(function () {
            var $accordion = $(this);
            var headerHeight = $('.header').height();
            var sectionPadding = parseInt($accordion.css('padding-top'));
            var totalHeight = headerHeight + sectionPadding;

            if ($('#wpadminbar').length) {
                totalHeight += $('#wpadminbar').height();
            }

            var accordionId = $accordion.data('id');
            var accordionSelector = '.imageAccordion[data-id="' + accordionId + '"]';
            var accordionSidebar = new StickySidebar(accordionSelector + ' #imageAccordionImageBlock', {
                containerSelector: accordionSelector + ' .imageAccordionBlockWrap',
                innerWrapperSelector: accordionSelector + ' #imageAccordionImageBlockWrap',
                topSpacing: totalHeight - sectionPadding,
                bottomSpacing: totalHeight,
            });

            $(window).on('scroll resize', function () {
                headerHeight = $('.header').height();
                sectionPadding = parseInt($accordion.css('padding-top'));
                totalHeight = headerHeight + sectionPadding;
                if ($('#wpadminbar').length) {
                    totalHeight += $('#wpadminbar').height();
                }
                accordionSidebar.options.topSpacing = totalHeight - sectionPadding + 20;
                accordionSidebar.updateSticky();
            });
        });
    }

});