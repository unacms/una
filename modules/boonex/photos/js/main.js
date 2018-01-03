/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

function BxPhotosMain(oOptions) {
	this._sActionUrl = oOptions.sActionUrl;
	this._sActionUri = oOptions.sActionUri;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxPhotosMain' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxPhotosMain.prototype.viewEntry = function(iId, sMode, oParams) {
	var oData = $.extend({}, this._getDefaultData(), {id: iId, mode: sMode}, (oParams != undefined ? oParams : {}));

	$(".bx-popup-full-screen.bx-popup-applied:visible").dolPopupHide();

	$(window).dolPopupAjax({
		id: {value: this._aHtmlIds['view_entry_brief_popup'] + iId, force: true},
		url: bx_append_url_params(this._sActionUri + 'view_entry_brief', oData),
		removeOnClose: true,
		fullScreen: true
	});

	return false;
};

BxPhotosMain.prototype._getDefaultData = function() {
	var oDate = new Date();

    return {
		_t:oDate.getTime()
    };
};
/** @} */
