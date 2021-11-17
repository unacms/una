/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioFormsGroupsRoles(oOptions) {
    this.sActionsUrl = oOptions.sActionUrl;
    this.sPageUrl = oOptions.sPageUrl;
    this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioFormsGroupsRoles' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDolStudioFormsGroupsRoles.prototype.onChangeModule = function () {
    this.reloadGrid($('#bx-grid-module-' + this.sObjNameGrid).val());
};

BxDolStudioFormsGroupsRoles.prototype.reloadGrid = function (sModule) {
    var bReload = false;
    var oActions = $("[bx_grid_action_independent]");
    if(!sModule){
        oActions.addClass('bx-btn-disabled');
    }
    else {
        oActions.removeClass('bx-btn-disabled');
    }

    if (glGrids[this.sObjNameGrid]._oQueryAppend['module'] != sModule) {
        glGrids[this.sObjNameGrid]._oQueryAppend['module'] = sModule;
        bReload = true;
    }

    if (bReload) {
        glGrids[this.sObjNameGrid].reload(0);
    }
};
/** @} */
