/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolForm(oOptions)
{
    if(typeof oOptions === 'undefined')
        return;

    this._sObjName = oOptions.sObjName === undefined ? 'oForm' : oOptions.sObjName; // javascript object name, to run current object instance from onTimer
    this._sObject = oOptions.sObject; // form object
    this._sDisplay = oOptions.sDisplay; // form display

    this._sActionsUri = 'form.php';
    this._sActionsUrl = oOptions.sRootUrl + this._sActionsUri; // actions url address

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';
    this._aHtmlIds = oOptions.aHtmlIds;
    
    var $this = this;
	$(document).ready(function () {
		$this.checkError();
	});
}

BxDolForm.prototype.showHelp = function(oLink, sInputName)
{
    var oData = this._getDefaultParams();
    oData['a'] = 'get_help';
    oData['input'] = sInputName;

    $(oLink).dolPopupAjax({
        id: {value:this._aHtmlIds['help_popup'] + sInputName, force:1}, 
        url: bx_append_url_params(this._sActionsUri, oData),
        closeOnOuterClick: true,
        removeOnClose: true,
        onBeforeShow: function(oPopup) {
            oPopup.addClass('bx-popup-help');
        }
    });
};

BxDolForm.prototype.pgcTogglePopup = function(oLink, iInputId, sPrivacyObject)
{
    var oData = this._getDefaultParams();
    oData['a'] = 'get_privacy_group_chooser';
    oData['input_id'] = iInputId;
    oData['privacy_object'] = sPrivacyObject;

    $(oLink).dolPopupAjax({
        id: {value:this._aHtmlIds['pgc_popup'] + iInputId, force:1},
        url: bx_append_url_params(this._sActionsUri, oData),
        closeOnOuterClick: false,
        removeOnClose: true,
    });
};

BxDolForm.prototype.pgcOnSelectGroup = function(oData)
{
    if(oData && parseInt(oData.code) != 0)
        return;

    if(oData.form_id && oData.chooser_id && oData.icon)
        $('#' + oData.form_id + ' #' + oData.chooser_id + ' .bx-form-input-pgc-current .sys-icon').removeClass().addClass('sys-icon ' + oData.icon);
};

BxDolForm.prototype._getDefaultParams = function() 
{
    var oDate = new Date();
    return {
        o: this._sObject,
        d: this._sDisplay,
        _t: oDate.getTime()
    };
};

BxDolForm.setCheckBoxValue = function (obj) {
    var oHidden = $(obj).parent('div').find('INPUT[type=hidden]')
    var val = 0;
    if ($(obj).attr("checked") == 'checked') {
        val = 1;
    }
    oHidden.val(val);
}

BxDolForm.prototype.checkError = function () {

    var $aErr = $('form[name="' + this._sObject + '"]').find('.bx-form-warn:visible');
    
    if ($aErr.length > 0){
        $([document.documentElement, document.body]).animate({
            scrollTop: $aErr.first().closest('.bx-form-element-wrapper').offset().top - 50
        }, 200);
    }
}

/** @} */
