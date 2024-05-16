/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioPageAgents(oOptions)
{
    BxDolStudioPage.call(this, oOptions);

    this.sPageUrl = oOptions.sPageUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioPageAgents' : oOptions.sObjName;

    this.sActionUrlCmts = oOptions.sActionUrlCmts == undefined ? this.sActionUrl : oOptions.sActionUrlCmts;

    /*
     * Note. Are needed for Grid and don't used for now.
     * 
    this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;
    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;
    */
}

BxDolStudioPageAgents.prototype = Object.create(BxDolStudioPage.prototype);
BxDolStudioPageAgents.prototype.constructor = BxDolStudioPageAgents;

BxDolStudioPageAgents.prototype.onChangeType = function(oSelect) {
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

BxDolStudioPageAgents.prototype.approveCode = function(oSource, iCmtId) {
    var $this = this;
    var oData = this._getDefaultData();
    oData = jQuery.extend({}, oData, {action: 'approveCode', Cmt: iCmtId});

    oSource = $(oSource);
    bx_loading_btn(oSource, true);

    jQuery.post (
        this.sActionUrlCmts,
        oData,
        function(oData) {
            bx_loading_btn(oSource, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxDolStudioPageAgents.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
