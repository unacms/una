/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */

function BxGroupsManageTools(oOptions) {
	this._iSearchTimeoutId = false;
	this._sActionsUrl = oOptions.sActionUrl;
	this._sObjNameGrid = oOptions.sObjNameGrid;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxGroupsManageTools' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxGroupsManageTools.prototype.onChangeFilter = function(oFilter) {
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

BxGroupsManageTools.prototype.onClickSettings = function(sMenuObject, oButton) {
	if($(oButton).hasClass('bx-btn-disabled'))
		return false;

	bx_menu_popup(sMenuObject, oButton, {}, {
		content_id: $(oButton).attr('bx_grid_action_data')
	});
};

BxGroupsManageTools.prototype.onClickDelete = function(iContentId) {
	$('.bx-popup-applied:visible').dolPopupHide();

	glGrids[this._sObjNameGrid].actionWithId(iContentId, 'delete', {}, '', false, 1);
};

BxGroupsManageTools.prototype.onClickDeleteWithContent = function(iContentId) {
	$('.bx-popup-applied:visible').dolPopupHide();

	glGrids[this._sObjNameGrid].actionWithId(iContentId, 'delete_with_content', {}, '', false, 1);
};

/** @} */
