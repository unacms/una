/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioNavigationImport(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
	this.sPageUrl = oOptions.sPageUrl;
	this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioNavigationImport' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;
}

BxDolStudioNavigationImport.prototype.onChangeFilter = function() {
	var sValueSet = $('#bx-grid-set-' + this.sObjNameGrid).val();

	var sValueModule = $('#bx-grid-module-' + this.sObjNameGrid).val();

	var sValueSearch = $('#bx-grid-search-' + this.sObjNameGrid).val();
	if(sValueSearch == this.sTextSearchInput)
		sValueSearch = '';

	glGrids[this.sObjNameGrid].setFilter(sValueSet + this.sParamsDivider + sValueModule + this.sParamsDivider + sValueSearch, true);
};

BxDolStudioNavigationImport.prototype.onImport = function(oData) {
	$.each(oData.disable, function (iKey, iValue){
		$("[bx_grid_action_single = 'import'][bx_grid_action_data = '" + iValue + "']").addClass('bx-btn-disabled');
	});
	glGrids.sys_studio_nav_items.processJson({grid: oData.parent_grid, blink: oData.parent_blink}, 'import', false);
};
/** @} */
