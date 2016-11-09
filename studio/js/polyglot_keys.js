/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioPolyglotKeys(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
	this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioPolyglotKeys' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput; 
}

BxDolStudioPolyglotKeys.prototype.onChangeFilter = function() {
	var sValueModule = $('#bx-grid-module-' + this.sObjNameGrid).val().replace('id-', '');

	var sValueSearch = $('#bx-grid-search-' + this.sObjNameGrid).val();
	if(sValueSearch == this.sTextSearchInput)
		sValueSearch = '';

	glGrids[this.sObjNameGrid].setFilter(sValueModule + '#-#' + sValueSearch, true);
};
/** @} */
