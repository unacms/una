/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ads Ads
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAdsForm(oOptions) {
    this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjNameForm = oOptions.sObjNameForm;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxAdsForm' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxAdsForm.prototype.selectCategory = function(oButton) {
    var oCategory = $(oButton).parents('form:first').find("[name='category_select']");
    if(!oCategory || oCategory.length == 0)
        return;

    var $this = this;
    var oParams = this._getDefaultData();
    oParams['category'] = oCategory.val();

    this.loadingInBlock(oButton, true);

    jQuery.get (
        this._sActionsUrl + 'get_category_form',
        oParams,
        function(oData) {
            if(oButton)
                $this.loadingInBlock(oButton, false);

            processJsonData(oData);
        },
        'json'
    );
};

BxAdsForm.prototype.onSelectCategory = function(oData) {
    if(!oData || !oData.content && oData.content.length == 0) 
        return;

    var oContent = $(oData.content);
    var sFormId = oContent.filter('form').attr('id');
    if(!sFormId)
        return;

    $('form#' + sFormId).replaceWith(oContent);

    if(window[this._sObjNameForm] != undefined)
        window[this._sObjNameForm].resetChanged();
};

BxAdsForm.prototype.checkName = function(oSource, sTitleId, sNameId, iId) {
    var oDate = new Date();
    var oForm = jQuery(oSource).parents('.bx-form-advanced:first');

    var oName = oForm.find("[name='" + sNameId + "']");
    var sName = oName.val();
    var bName = sName.length != 0;

    var oTitle = oForm.find("[name='" + sTitleId + "']");
    var sTitle = oTitle.val();
    var bTitle = sTitle.length != 0;

    if(!bName && !bTitle)
        return;

    var sTitleCheck = '';
    if(bName)
        sTitleCheck = sName;
    else if(bTitle) {
        sTitleCheck = sTitle;

        sTitle = sTitle.replace(/[^A-Za-z0-9_]/g, '-');
        sTitle = sTitle.replace(/[-]{2,}/g, '-');
        oName.val(sTitle);
    }

    jQuery.get(
        this._sActionsUrl + 'check_name',
        {
            title: sTitleCheck,
            id: iId && parseInt(iId) > 0 ? iId : 0,
            _t: oDate.getTime()
        },
        function(oData) {
            if(!oData || oData.name == undefined)
                return;

            oName.val(oData.name);
        },
        'json'
    );
};

BxAdsForm.prototype.loadingInBlock = function(e, bShow) {
    var oParent = $(e).length ? $(e).parents('.bx-db-container:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxAdsForm.prototype._getDefaultData = function() {
    var oDate = new Date();
    return jQuery.extend({}, this._oRequestParams, {_t:oDate.getTime()});
};

/** @} */
