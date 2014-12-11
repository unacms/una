/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioPermissionsActions(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
	this.sPageUrl = oOptions.sPageUrl;
	this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioPermissionsActions' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;
}

BxDolStudioPermissionsActions.prototype.onChangeLevel = function() {
	var iLevel = parseInt($('#bx-grid-level-' + this.sObjNameGrid).val().replace('id-', ''));
	document.location.href = this.sPageUrl + (iLevel > 0 ? '&level=' + iLevel : ''); 
};

BxDolStudioPermissionsActions.prototype.onChangeFilter = function() {
	var sValueModule = $('#bx-grid-module-' + this.sObjNameGrid).val();

	var sValueSearch = $('#bx-grid-search-' + this.sObjNameGrid).val();
	if(sValueSearch == this.sTextSearchInput)
		sValueSearch = '';

	glGrids[this.sObjNameGrid].setFilter(sValueModule + this.sParamsDivider + sValueSearch, true);
};
/** @} */
