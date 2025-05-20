/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioFormsPreValues(oOptions) {
    this.sActionsUrl = oOptions.sActionUrl;
    this.sPageUrl = oOptions.sPageUrl;
    this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioFormsValues' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;
}

BxDolStudioFormsPreValues.prototype.onChangeIconType = function(oSource) {
    var oItems = $(oSource).parents('form:first').find('.bx-pre-values-icon');
    if(!oItems || !oItems.length)
        return;

    oItems.hide().filter('.bx-pre-values-icon.bx-pvi-' + $(oSource).val()).show();
};

BxDolStudioFormsPreValues.prototype.onChangeModule = function() {
    var $this = this;
    var oDate = new Date();
    var sModule = $('#bx-grid-module-' + this.sObjNameGrid).val();

    this.reloadGrid();

    bx_loading($('body'), true);

    $.post(
        this.sPageUrl,
        {
            form_action: 'get_lists',
            form_module: sModule,
            _t: oDate.getTime()
        },
        function(oData) {
            bx_loading($('body'), false);

            if(oData.code != 0) {
                bx_alert(oData.message);
                return;
            }

            $('#' + $(oData.content).attr('id')).replaceWith(oData.content);
        },
        'json'
    );
};

BxDolStudioFormsPreValues.prototype.onChangeList = function() {
    this.reloadGrid($('#bx-grid-module-' + this.sObjNameGrid).val(), $('#bx-grid-list-' + this.sObjNameGrid).val());
};

BxDolStudioFormsPreValues.prototype.reloadGrid = function(sModule, sList) {
    var bReload = false;

    if(!sModule) 
        sList = '';

    var oSearch = $('#bx-form-element-keyword');
    var oActions = $("[bx_grid_action_independent]");
    if(!sList) {
        oSearch.hide();
        oActions.addClass('bx-btn-disabled');
    }
    else {
        oSearch.show();
        oActions.removeClass('bx-btn-disabled');
    }

    if(glGrids[this.sObjNameGrid]._oQueryAppend['module'] != sModule) {
        glGrids[this.sObjNameGrid]._oQueryAppend['module'] = sModule;
        bReload = true;
    }

    if(glGrids[this.sObjNameGrid]._oQueryAppend['list'] != sList) {
        glGrids[this.sObjNameGrid]._oQueryAppend['list'] = sList;
        bReload = true;
    }

    if(bReload)
        glGrids[this.sObjNameGrid].reload(0);
};
/** @} */
