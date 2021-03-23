/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolLabel(oOptions) {
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolLabel' : oOptions.sObjName;

    this._sRootUrl = oOptions.sRootUrl == undefined ? sUrlRoot : oOptions.sRootUrl;
    this._sActionsUrl = this._sRootUrl + 'label.php'; // actions url address

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;

    this._sClassItem = 'sys-labels-list-item';
    this._sClassSublist = 'sys-labels-li-sublist';
}

BxDolLabel.prototype.selectLabels = function(oElement, sName, aParams)
{
    var $this = this;

    var aValue = new Array();
    $(oElement).find('input:hidden').each(function() {
        aValue.push($(this).val());
    });

    var oData = $.extend({}, $this._getDefaultParams(), {action: 'select_labels', name: sName, value: aValue}, (aParams || {}));

    this._loadingInFormElement(oElement, true);

    $.get(
        this._sActionsUrl, 
        oData,
        function(oData) {
            $this._loadingInFormElement(oElement, false);

            processJsonData(oData);
        }, 
        'json'
    );
};

BxDolLabel.prototype.onSelectLabels = function(oData)
{
    if(oData.name == undefined || oData.content == undefined)
        return;

    var oElement = $('#' + this._aHtmlIds['labels_element'] + oData.name + ' .bx-form-input-labels');
    if(!oElement.length)
        return;

    oElement.find('b.val:not(.val-placeholder)').remove();
    if(oData.content.length > 0)
        oElement.prepend(oData.content);
};

BxDolLabel.prototype.showSublist = function(oLink)
{
    $(oLink).find('.sys-icon').toggleClass('chevron-down').toggleClass('chevron-up').parents('.' + this._sClassItem + ':first').find('.' + this._sClassSublist + ':first').bx_anim('toggle', this._sAnimationEffect, this._iAnimationSpeed);
};

BxDolLabel.prototype._loading = function(e, bShow)
{
    var oParent = $(e).length ? $(e) : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolLabel.prototype._loadingInBlock = function(e, bShow)
{
    var oParent = $(e).length ? $(e).parents('.bx-db-content:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolLabel.prototype._loadingInFormElement = function(e, bShow)
{
    var oParent = $(e).length ? $(e).parents('.bx-form-element:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolLabel.prototype._getSelectLabelsElement = function()
{
    return $('#' + this._aHtmlIds['form'] + ' #' + this._aHtmlIds['labels_element'] + ' input');
};

BxDolLabel.prototype._getDefaultParams = function() 
{
    var oDate = new Date();
    return {
        _t: oDate.getTime()
    };
};

/** @} */
