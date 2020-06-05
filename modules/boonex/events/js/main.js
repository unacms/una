/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

function BxEventsMain(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxEventsMain' : oOptions.sObjName;
    this._sObjNameGrid = oOptions.sObjNameGrid == undefined ? '' : oOptions.sObjNameGrid;
    
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
}

BxEventsMain.prototype.onClickSetRole = function(iFanProfileId, iRole) {
    $('.bx-popup-applied:visible').dolPopupHide();

    if(glGrids[this._sObjNameGrid] != undefined)
        glGrids[this._sObjNameGrid].actionWithId(iFanProfileId, 'set_role_submit', {role: iRole}, '', false, false);
};

/** @} */
