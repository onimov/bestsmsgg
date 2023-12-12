(function(window, document, html) {
    var isRetina = function() {
        if (window.matchMedia) {
            var mq = window.matchMedia('only screen and (-moz-min-device-pixel-ratio: 1.3), only screen and (-o-min-device-pixel-ratio: 2.6/2), only screen and (-webkit-min-device-pixel-ratio: 1.3), only screen  and (min-device-pixel-ratio: 1.3), only screen and (min-resolution: 1.3dppx)');
            if (mq && mq.matches) {
                return true;
            }
        }

        var dpr = window.devicePixelRatio || (window.screen.deviceXDPI / window.screen.logicalXDPI) || 1;
        return dpr > 1;
    };

    var supportsSVG = function() {
        return !!document.createElementNS && !!document.createElementNS('http://www.w3.org/2000/svg', 'svg').createSVGRect;
    };

    var getBrowser = function() {
        var ua = navigator.userAgent.match(/(opera|chrome|safari|firefox|msie)\/?\s*(\.?\d+(\.\d+)*)/i);

        if (navigator.userAgent.match(/Trident/i)) {
            return 'ms ie';
        } else if (navigator.userAgent.match(/Edge/i)) {
            return 'ms edge';
        } else {
            return ua && ua.length > 1 ? ua[1].toLowerCase() : '';
        }
    };

    html.className += ' ' + getBrowser();
    html.className += ' retina_' + (isRetina() ? 'yes' : 'no');
    html.className += ' svg_' + (supportsSVG() ? 'yes' : 'no');
})(window, document, document.documentElement);