/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Accounts Accounts
 * @ingroup     UnaModules
 *
 * @{
 */

function BxAccntManageTools(oOptions) {
    this._iSearchTimeoutId = false;
	this._sActionsUrl = oOptions.sActionUrl;    
	this._sObjNameGrid = oOptions.sObjNameGrid;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxAccntManageTools' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxAccntManageTools.prototype.onChangeFilter = function(oFilter) {
    var $this = this;
	var oFilter1 = $('#bx-grid-filter1-' + this._sObjNameGrid);
	var sValueFilter1 = oFilter1.length > 0 ? oFilter1.val() : '';

	var oFilter2 = $('#bx-grid-filter2-' + this._sObjNameGrid);
	var sValueFilter2 = oFilter2.length > 0 ? oFilter2.val() : '';

	var oSearch = $('#bx-grid-search-' + this._sObjNameGrid);
	var sValueSearch = oSearch.length > 0 ? oSearch.val() : '';
	if(sValueSearch == _t('_sys_grid_search'))
		sValueSearch = '';

    clearTimeout($this._iSearchTimeoutId);
    $this._iSearchTimeoutId = setTimeout(function () {
        glGrids[$this._sObjNameGrid].setFilter(sValueFilter1 + $this._sParamsDivider + sValueFilter2 + $this._sParamsDivider + sValueSearch, true); //TODO ANT
    }, 500);
};

BxAccntManageTools.prototype.onClickSettings = function(sMenuObject, oButton) {
	if($(oButton).hasClass('bx-btn-disabled'))
		return false;

	bx_menu_popup(sMenuObject, oButton, {}, {
		content_id: $(oButton).attr('bx_grid_action_data')
	});
};

BxAccntManageTools.prototype.onClickEditEmail = function(iContentId, oLink) {
	$('.bx-popup-applied:visible').dolPopupHide();

	var oGrid = glGrids[this._sObjNameGrid];
	var oData = oGrid._checkAppend(oLink, oGrid._getActionDataForReload());
	oGrid.actionWithId(iContentId, 'edit_email', oData, '', false, 0);
};

BxAccntManageTools.prototype.onClickResendCemail = function(iContentId, oLink) {
	$('.bx-popup-applied:visible').dolPopupHide();

	var oGrid = glGrids[this._sObjNameGrid];
	var oData = oGrid._checkAppend(oLink, oGrid._getActionDataForReload());
	oGrid.actionWithId(iContentId, 'resend_cemail', oData, '', false, 0);
};

BxAccntManageTools.prototype.onClickResetPassword = function(iContentId, oLink) {
	$('.bx-popup-applied:visible').dolPopupHide();

	var oGrid = glGrids[this._sObjNameGrid];
	var oData = oGrid._checkAppend(oLink, oGrid._getActionDataForReload());
	oGrid.actionWithId(iContentId, 'reset_password', oData, '', false, 0);
};

BxAccntManageTools.prototype.onClickDelete = function(iContentId, oLink) {
	$('.bx-popup-applied:visible').dolPopupHide();

	var oGrid = glGrids[this._sObjNameGrid];
	var oData = oGrid._checkAppend(oLink, {});
	oGrid.actionWithId(iContentId, 'delete', oData, '', false, 1);
};

BxAccntManageTools.prototype.onClickDeleteWithContent = function(iContentId, oLink) {
	$('.bx-popup-applied:visible').dolPopupHide();

	var oGrid = glGrids[this._sObjNameGrid];
	var oData = oGrid._checkAppend(oLink, {});
	oGrid.actionWithId(iContentId, 'delete_with_content', oData, '', false, 1);
};

BxAccntManageTools.prototype.onClickMakeOperator = function(iContentId, oLink) {
	$('.bx-popup-applied:visible').dolPopupHide({
		onHide: function(oPopup) {
			$(oPopup).remove();
		} 
	});

	var oGrid = glGrids[this._sObjNameGrid];
	var oData = oGrid._checkAppend(oLink, oGrid._getActionDataForReload());
    oGrid.actionWithId(iContentId, 'make_operator', oData, '', false, 0);
};

BxAccntManageTools.prototype.onClickUnmakeOperator = function(iContentId, oLink) {
	$('.bx-popup-applied:visible').dolPopupHide({
		onHide: function(oPopup) {
			$(oPopup).remove();
		} 
	});

	var oGrid = glGrids[this._sObjNameGrid];
	var oData = oGrid._checkAppend(oLink, oGrid._getActionDataForReload());
	oGrid.actionWithId(iContentId, 'unmake_operator', oData, '', false, 0);
};

/** @} */
