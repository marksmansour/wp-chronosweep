var adjustHeights = {
    setHeightByRow: function (element) {
        if ($(element).length) {
            $(element).matchHeight({
                property: 'height',
                byRow: true
            });
        }
    },
    setHeightByAll: function (element) {
        if ($(element).length) {
            $(element).matchHeight({
                property: 'height',
                byRow: false
            });
        }
    },
};