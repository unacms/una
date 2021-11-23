/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Attendant Attendant
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAttendant(oOptions) {
    this._sContainerId = oOptions.sContainerId == undefined ? 'oBxAttendant' : oOptions.sContainerId;
    this._sUrlAfterShow = oOptions.sUrlAfterShow == undefined ? '' : oOptions.sUrlAfterShow;
    this._sActionsUri = oOptions.sActionUri;
    var $this = this;
}

BxAttendant.prototype.showPopup = function (sModule = '', sEvent = '', sObject = 0) {
    var $this = this;
    
    $(window).dolPopupAjax({
        url: $this._sActionsUri + '/RecomendedPopup/' + sModule + '/' + sEvent + '/' + sObject + '/',
        closeOnOuterClick: true,
		removeOnClose: true,
        onLoad: function(sPopupSelector){
            $(sPopupSelector + ' .bx-pwropa-item-container').hide().first().show();
            $this.reInitFlickity(sPopupSelector);
            $(sPopupSelector + ' .bx-pwropa-button').click(function () {
                $oCurr = $(sPopupSelector + ' .bx-pwropa-item-container:visible').hide();
                $oNext = $oCurr.next(); 
                $oNext.show();
                $this.reInitFlickity(sPopupSelector);
                if ($oNext.length == 0) {
                    $(sPopupSelector).dolPopupHide();
                    if ($this._sUrlAfterShow != '') {
                        location.href = $this._sUrlAfterShow;
                    }
                }
            });
            
        }
    });
}

BxAttendant.prototype.reInitFlickity = function (sPopupSelector) {
    var $this = this;
    $(sPopupSelector + ' .bx-pwropa-item-container:visible .bx-base-unit-showcase-wrapper').flickity({
        cellSelector: '.bx-base-unit-showcase',
        cellAlign: 'left',
        pageDots: false,
        imagesLoaded: true
    });
    $('#' + $this._sContainerId)._dolPopupSetPosition();
}

BxAttendant.prototype.onActionComplete = function (data, e) {
    if (data.err == false) {
        var $e = $(e);
        $e.hide();
    }
}

function bx_attendant_conn_action(e, sObj, sAction, iContentId, bConfirm, fOnComplete) {
    return bx_conn_action(e, sObj, sAction, iContentId, bConfirm, oBxAttendant.onActionComplete)
}
