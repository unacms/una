/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */
function BxDolPage(oOptions)
{
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolPage' : oOptions.sObjName;
    this._isStickyColumns = oOptions.isStickyColumns == undefined ? false : oOptions.isStickyColumns;
    var $this = this;
    $(document).ready(function () {
        $this.init();
    });
}

BxDolPage.prototype.init = function () {
    var $this = this;
    if ($this._isStickyColumns && !$('html').hasClass('bx-media-phone')) {
        $(window).resize(function () { $this.stickyBlocks() });
        $(window).scroll(function () { $this.stickyBlocks() });
    }
};

BxDolPage.prototype.stickyBlocks = function () {
    $.each($('.bx-layout-col'), function (index, val) {
        if ($(this).css('position') == 'sticky') {
            var iCh = $(this).height();
            var iWh = $(window).height();
            if (iCh > iWh) {
                if (iCh - $(window).scrollTop() - $(window).height() < 0) {
                    $(this).css('top', -iCh + $(window).height());
                }
            }
        }
    });
}


/** @} */
