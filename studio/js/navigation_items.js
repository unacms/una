/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioNavigationItems(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
	this.sPageUrl = oOptions.sPageUrl;
	this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioNavigationItems' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;
}

BxDolStudioNavigationItems.prototype.onChangeSubmenu = function(oSelect) {
	var sSubmenu = $(oSelect).val();
	$('#bx-form-element-submenu_popup').bx_anim(sSubmenu == '' ? 'hide' : 'show',  this.sAnimationEffect, this.iAnimationSpeed);
	$('#bx-form-element-link, #bx-form-element-target').bx_anim(sSubmenu == '' ? 'show' : 'hide',  this.sAnimationEffect, this.iAnimationSpeed);
};

BxDolStudioNavigationItems.prototype.onChangeModule = function() {
	var $this = this;
	var oDate = new Date();
	var sModule = $('#bx-grid-module-' + this.sObjNameGrid).val();

	this.reloadGrid();

	bx_loading($('body'), true);

	$.post(
		this.sPageUrl,
		{
			nav_action: 'get_sets',
			nav_module: sModule,
			_t: oDate.getTime()
		},
		function(oData) {
			bx_loading($('body'), false);

			if(oData.code != 0) {
				alert(oData.message);
				return;
			}

			$('#' + $(oData.content).attr('id')).replaceWith(oData.content);
		},
		'json'
	);
};

BxDolStudioNavigationItems.prototype.onChangeSet = function() {
	this.reloadGrid($('#bx-grid-module-' + this.sObjNameGrid).val(), $('#bx-grid-set-' + this.sObjNameGrid).val());
};

BxDolStudioNavigationItems.prototype.reloadGrid = function(sModule, sSet) {
	var bReload = false;

	if(!sModule) 
		sSet = '';

	var oSearch = $('#bx-form-element-keyword');
	var oActions = $("[bx_grid_action_independent]");
	if(!sSet) {
		oSearch.hide();
		oActions.addClass('bx-btn-disabled');
	}
	else {
		oSearch.show();
		oActions.removeClass('bx-btn-disabled');
	}

	if(glGrids[this.sObjNameGrid]._oQueryAppend['module'] != sModule) {
		glGrids[this.sObjNameGrid]._oQueryAppend['module'] = sModule;
		bReload = true;
	}

	if(glGrids[this.sObjNameGrid]._oQueryAppend['set'] != sSet) {
		glGrids[this.sObjNameGrid]._oQueryAppend['set'] = sSet;
		bReload = true;
	}

	if(bReload)
		glGrids[this.sObjNameGrid].reload(0);
};

BxDolStudioNavigationItems.prototype.onChangeVisibleFor = function(oSelect) {
	$('#bx-form-element-visible_for_levels').bx_anim($(oSelect).val() == 'all' ? 'hide' : 'show', this.sAnimationEffect, this.iAnimationSpeed);
};

BxDolStudioNavigationItems.prototype.onDeleteIcon = function(oData) {
	if (oData && oData.preview != undefined) {
		var sPreviewId = $(oData.preview).attr('id');
		$('#' + sPreviewId).replaceWith(oData.preview);
	}
};
/** @} */
