var bIgnorePixelRatio = true;
$(document).ready(function() {
    $(window.frameElement, window.parent.document).height($('.bx-embed').height() + 2);
});