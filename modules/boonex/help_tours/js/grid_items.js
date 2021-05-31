/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Help Tours Help Tours
 * @ingroup     UnaModules
 *
 * @{
 */

function BxHelpToursGridItems(oOptions) {
    this.sModuleUrl = oOptions.sModuleUrl;
    this.sActionsUrl = oOptions.sActionUrl;
    this.sPageUrl = oOptions.sPageUrl;
    this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oAqbConditionalFieldsGridValues' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this.iTour = oOptions.iTour == undefined ? '' : oOptions.iTour;
}

BxHelpToursGridItems.prototype.onChangeTour = function (v) {
    this.iTour = v;
    this.reloadGrid();
};

BxHelpToursGridItems.prototype.reloadGrid = function () {
    var bReload = false;

    var oActions = $("[bx_grid_action_independent]");
    if(!this.iTour){
        oActions.addClass('bx-btn-disabled');
    } else {
        oActions.removeClass('bx-btn-disabled');
    }

    if (glGrids[this.sObjNameGrid]._oQueryAppend['tour'] != this.iTour) {
        glGrids[this.sObjNameGrid]._oQueryAppend['tour'] = this.iTour;
        bReload = true;
    }

    if (bReload) {
        glGrids[this.sObjNameGrid].reload(0);
    }
};
/** @} */