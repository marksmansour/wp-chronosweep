//Custom jquery function
( function($) {
    $.cleanHTML = function(data) {
        var tempDiv = $('<div/>').html(data);
        tempDiv.find('script[src]').remove();
        return tempDiv.html();
    };
    $.fn.equalHeights = function (minHeight, maxHeight) {
        tallest = (minHeight) ? minHeight : 0;
        this.each(function () {
            if ($(this).height() > tallest) {
                tallest = $(this).height();
            }
        });
        if ((maxHeight) && tallest > maxHeight) tallest = maxHeight;
        return this.each(function () {
            $(this).height(tallest);
        });
    };
}) (jQuery);