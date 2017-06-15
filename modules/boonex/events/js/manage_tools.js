/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

function BxEventsManageTools(oOptions) {
	this._iSearchTimeoutId = false;
	this._sActionsUrl = oOptions.sActionUrl;
	this._sObjNameGrid = oOptions.sObjNameGrid;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxEventsManageTools' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxEventsManageTools.prototype.onChangeFilter = function(oFilter) {
	var $this = this;
	var oFilter1 = $('#bx-grid-filter1-' + this._sObjNameGrid);
	var sValueFilter1 = oFilter1.length > 0 ? oFilter1.val() : '';

	var oSearch = $('#bx-grid-search-' + this._sObjNameGrid);
	var sValueSearch = oSearch.length > 0 ? oSearch.val() : '';
	if(sValueSearch == _t('_sys_grid_search'))
		sValueSearch = '';

	clearTimeout($this._iSearchTimeoutId);
    $this._iSearchTimeoutId = setTimeout(function () {
        glGrids[$this._sObjNameGrid].setFilter(sValueFilter1 + $this._sParamsDivider + sValueSearch, true);
    }, 500);
};

BxEventsManageTools.prototype.onClickSettings = function(sMenuObject, oButton) {
	if($(oButton).hasClass('bx-btn-disabled'))
		return false;

	bx_menu_popup(sMenuObject, oButton, {}, {
		content_id: $(oButton).attr('bx_grid_action_data')
	});
};

BxEventsManageTools.prototype.onClickDelete = function(iContentId) {
	$('.bx-popup-applied:visible').dolPopupHide();

	glGrids[this._sObjNameGrid].actionWithId(iContentId, 'delete', {}, '', false, 1);
};

BxEventsManageTools.prototype.onClickDeleteWithContent = function(iContentId) {
	$('.bx-popup-applied:visible').dolPopupHide();

	glGrids[this._sObjNameGrid].actionWithId(iContentId, 'delete_with_content', {}, '', false, 1);
};

/** @} */
