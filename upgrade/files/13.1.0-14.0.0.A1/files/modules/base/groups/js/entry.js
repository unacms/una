/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

function BxBaseModGroupsEntry(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxEventsEntry' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
}

BxBaseModGroupsEntry.prototype.connAction = function(oElement, sObject, sAction, iContentId) {
    var $this = this;
    var oParams = this._getDefaultData();
    oParams['s'] = 'entry';
    oParams['o'] = sObject;
    oParams['a'] = sAction;
    oParams['cpi'] = iContentId;

    if(oElement)
        this.loadingInButton(oElement, true);

    jQuery.get (
        this._sActionsUrl + 'get_questionnaire',
        oParams,
        function(oData) {
            if(oElement)
                $this.loadingInButton(oElement, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxBaseModGroupsEntry.prototype.connActionPerformed = function(oData) {
    bx_conn_action($('.bx-menu-item-profile-fan-' + oData.a + ' .bx-btn'), oData.o, oData.a, oData.cpi);
};

BxBaseModGroupsEntry.prototype.loadingInButton = function(e, bShow) {
    if($(e).length)
        bx_loading_btn($(e), bShow);
    else
        bx_loading($('body'), bShow);	
};

BxBaseModGroupsEntry.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
