/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Jobs Jobs
 * @ingroup     UnaModules
 *
 * @{
 */

function BxJobsMain(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxJobsMain' : oOptions.sObjName;
    this._sObjNameGrid = oOptions.sObjNameGrid == undefined ? '' : oOptions.sObjNameGrid;
    
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
}

BxJobsMain.prototype.onClickSetRole = function(iFanProfileId, iRole) {
    $('.bx-popup-applied:visible').dolPopupHide();

    if(glGrids[this._sObjNameGrid] != undefined)
        glGrids[this._sObjNameGrid].actionWithId(iFanProfileId, 'set_role_submit', {role: iRole}, '', false, false);
};

BxJobsMain.prototype.onClickSetRoleMulti = function(oElement, iFanProfileId, iRole) {
    if(!glGrids[this._sObjNameGrid])
        return;

    var oSiblings = $(oElement).parents('.bx-base-group-sr-role:first').siblings();
    if(iRole == 0)
        oSiblings.find('input:checked').removeAttr('checked');
    else
        oSiblings.find("input[value = '0']:checked").removeAttr('checked');

    var sRoles = $(oElement).parents('.bx-base-group-sr-roles:first').find('input:checked').serialize();
    glGrids[this._sObjNameGrid].actionWithId(iFanProfileId, 'set_role_submit', {}, sRoles, false, false);
};

/** @} */
