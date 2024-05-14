/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioAgentsAutomators(oOptions) {
    this.sActionsUrl = oOptions.sActionUrl;
    this.sPageUrl = oOptions.sPageUrl;
    this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioAgentsAutomators' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;
}

BxDolStudioAgentsAutomators.prototype.onChangeType = function(oSelect) {
    var aHide = [];
    var aShow = [];
    switch($(oSelect).val()) {
        case 'event':
            aHide = []; //['scheduler_time'];
            aShow = []; //['alert_unit', 'alert_action'];
            break;

        case 'scheduler':
            aHide = []; //['alert_unit', 'alert_action'];
            aShow = []; //['scheduler_time'];
            break;
            
        default:
            aHide = []; //['alert_unit', 'alert_action', 'scheduler_time'];
            aShow = [];
    }

    var sHide = '';
    aHide.forEach((sItem) => {
        sHide += ".bx-form-advanced #bx-form-element-" + sItem + ",";
    });

    var sShow = '';
    aShow.forEach((sItem) => {
        sShow += ".bx-form-advanced #bx-form-element-" + sItem + ",";
    });

    $(sHide.substring(0, sHide.length - 1)).bx_anim('hide', this.sAnimationEffect, 0);
    $(sShow.substring(0, sShow.length - 1)).bx_anim('show', this.sAnimationEffect, 0);
};

/** @} */
