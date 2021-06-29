/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */
function BxDolPage(oOptions) {
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolPage' : oOptions.sObjName;
    this._isStickyColumns = oOptions.isStickyColumns == undefined ? false : oOptions.isStickyColumns;
    this._iLastSc = 0;
    var $this = this;
    $(document).ready(function () {
        $this.init();
    });
}

BxDolPage.prototype.init = function () {
    var $this = this;
    if ($this._isStickyColumns && !$('html').hasClass('bx-media-phone')) {
        $('.bx-layout-col').theiaStickySidebar({
            additionalMarginTop: 30
        });
    }
};

/** @} */
