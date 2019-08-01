/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolPrivacy(oOptions) {
    this.BX_DOL_PG_FRIENDS_SELECTED = 6;
    this.BX_DOL_PG_RELATIONS_SELECTED = 8;

    this._sObject = oOptions.sObject;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolPrivacy' : oOptions.sObjName;

    this._sRootUrl = oOptions.sRootUrl == undefined ? sUrlRoot : oOptions.sRootUrl;
    this._sActionsUri = 'privacy.php';
    this._sActionsUrl = this._sRootUrl + this._sActionsUri; // actions url address

    this._aGroupSettings = oOptions.aGroupSettings == undefined ? {} : oOptions.aGroupSettings;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;

    this._iProfileId = 0;
    this._iContentId = 0;
}

BxDolPrivacy.prototype.loadGroupCustom = function(aParams)
{
    var $this = this;

    aParams = aParams || {};
    if(aParams && aParams.profile_id != undefined)
        this._iProfileId = parseInt(aParams.profile_id);

    if(aParams && aParams.content_id != undefined)
        this._iContentId = parseInt(aParams.content_id);

    $(document).ready(function() {
        var oElement = $this._getSelectGroupElement();

        if(!aParams.group_id && oElement && oElement.length > 0)
            aParams.group_id = parseInt($(oElement).val());

        if(!aParams.group_id || !$this._aGroupSettings[aParams.group_id])
            return;

        var oData = $.extend({}, $this._getDefaultParams(), {action: 'load_group_custom'}, aParams);

        $this._loadingInFormElement(oElement, true);

        $.get(
            $this._sActionsUrl, 
            oData,
            function(oData) {
                $this._loadingInFormElement(oElement, false);

                processJsonData(oData);
            }, 
            'json'
        );
    });
};

BxDolPrivacy.prototype.selectGroup = function(oElement)
{
    $('#' + this._aHtmlIds['group_custom_element']).remove();

    var iGroupId = parseInt($(oElement).val());
    if(!this._aGroupSettings[iGroupId])
        return;

    this.selectUsers(oElement, iGroupId);
};

BxDolPrivacy.prototype.onSelectGroup = function(oData)
{
    var oElement = this._getSelectGroupElement();

    if(oData && oData.content != undefined && oElement.length > 0)
        oElement.after(oData.content);
};

BxDolPrivacy.prototype.editGroup = function(oElement)
{
    var iGroupId = parseInt($(oElement).parents('.bx-form-element:first').find('.sys-privacy-group').val());

    this.selectUsers(oElement, iGroupId, {
        popup_only: 1
    });
};

BxDolPrivacy.prototype.selectUsers = function(oElement, iGroupId, aParams)
{
    var $this = this;
    var oData = $.extend({}, $this._getDefaultParams(), {action: 'select_group', group_id: iGroupId}, (aParams || {}));

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

BxDolPrivacy.prototype.onSelectUsers = function(oData)
{
    $('#' + this._aHtmlIds['group_custom_element']).remove();

    this.onSelectGroup(oData);
};

BxDolPrivacy.prototype._loading = function(e, bShow)
{
    var oParent = $(e).length ? $(e) : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolPrivacy.prototype._loadingInBlock = function(e, bShow)
{
    var oParent = $(e).length ? $(e).parents('.bx-db-content:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolPrivacy.prototype._loadingInFormElement = function(e, bShow)
{
    var oParent = $(e).length ? $(e).parents('.bx-form-element:first') : $('body'); 
    bx_loading(oParent, bShow);
};

BxDolPrivacy.prototype._getSelectGroupElement = function()
{
    return $('#' + this._aHtmlIds['group_element'] + ' .sys-privacy-group');
};

BxDolPrivacy.prototype._getDefaultParams = function() 
{
    var oDate = new Date();
    return {
        profile_id: this._iProfileId,
        content_id: this._iContentId,
        object: this._sObject,
        _t: oDate.getTime()
    };
};

/** @} */
