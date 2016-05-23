function BxAclAdministration(oOptions) {
	this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjNameGrid = oOptions.sObjNameGrid;
    this._sObjName = oOptions.sObjName == undefined ? 'oAclAdministration' : oOptions.sObjName;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxAclAdministration.prototype.onChangeLevel = function() {
	this.reloadGrid($('#bx-grid-level-' + this._sObjNameGrid).val());
};

BxAclAdministration.prototype.reloadGrid = function(iLevel) {
	var oSearch = $('#bx-form-element-keyword');
	var oActions = $("[bx_grid_action_independent]");
	if(parseInt(iLevel) > 0) {
		oSearch.show();
		oActions.removeClass('bx-btn-disabled');
	}
	else {
		oSearch.hide();
		oActions.addClass('bx-btn-disabled');
	}

	if(glGrids[this._sObjNameGrid]._oQueryAppend['level'] == iLevel)
		return;

	glGrids[this._sObjNameGrid]._oQueryAppend['level'] = iLevel;
	glGrids[this._sObjNameGrid].reload(0);
};
