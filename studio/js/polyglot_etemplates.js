/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioPolyglotEtemplates(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
	this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioPolyglotEtemplates' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput; 
}

BxDolStudioPolyglotEtemplates.prototype.onChangeFilter = function() {
	var sValueModule = $('#bx-grid-module-' + this.sObjNameGrid).val().replace('id-', '');

	var sValueSearch = $('#bx-grid-search-' + this.sObjNameGrid).val();
	if(sValueSearch == this.sTextSearchInput)
		sValueSearch = '';

	glGrids[this.sObjNameGrid].setFilter(sValueModule + '#-#' + sValueSearch, true);
};
/** @} */
