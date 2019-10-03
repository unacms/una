/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioFormsCategories(oOptions) {
    this.sActionsUrl = oOptions.sActionUrl;
    this.sPageUrl = oOptions.sPageUrl;
    this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioFormsCategories' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;
}

BxDolStudioFormsCategories.prototype.onChangeModule = function () {
    this.reloadGrid($('#bx-grid-module-' + this.sObjNameGrid).val());
};

BxDolStudioFormsCategories.prototype.reloadGrid = function (sModule) {
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
        var sValueSearch = $('#bx-grid-search-' + this.sObjNameGrid).val();
        if (sValueSearch == this.sTextSearchInput)
            sValueSearch = '';

        glGrids[this.sObjNameGrid].setFilter(sModule + this.sParamsDivider + sValueSearch, true);
    }
};
/** @} */
