/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Attendant Attendant
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAttendantPopupWithRecommendedOnProfileAdd(oOptions) {
    this._sContainerId = oOptions.sContainerId == undefined ? 'oBxAttendantPopupWithRecommendedOnProfileAdd' : oOptions.sContainerId;
    var $this = this;
    $(document).ready(function () {
        $this.init();
    });
}

BxAttendantPopupWithRecommendedOnProfileAdd.prototype.init = function () {
    var $this = this;

    $('.bx-pwropa-item-container').hide().first().show();
    $('#' + $this._sContainerId).dolPopup({ onShow: $this.Show() });
    $('.bx-pwropa-button').click(function () {
        $oCurr = $('.bx-pwropa-item-container:visible').hide();
        $oNext = $oCurr.next(); $oNext.show();
        $this.ReInitFlickity();
        if (!$oNext.length) {
            $('.bx-pwropa-container').hide();
        }
        
    });
}

BxAttendantPopupWithRecommendedOnProfileAdd.prototype.Show = function () {
    var $this = this;
    $('#' + $this._sContainerId + ' .bx-base-pofile-unit-cnt').css('height', 'auto');
    $('#' + $this._sContainerId + ' .bx-pwropa-item-container:visible .bx-base-unit-showcase-wrapper').flickity();
    $this.ReInitFlickity();
}

BxAttendantPopupWithRecommendedOnProfileAdd.prototype.ReInitFlickity = function () {
    var $this = this;
    $('#' + $this._sContainerId + ' .bx-pwropa-item-container:visible .bx-base-unit-showcase-wrapper').flickity({
        cellSelector: '.bx-base-unit-showcase',
        cellAlign: 'left',
        pageDots: false,
        imagesLoaded: true
    });
}
