/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
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

BxDolStudioNavigationItems.prototype.onChangeSubmenu = function(oElement) {
	var oForm = $(oElement).parents(".bx-form-advanced:first");
	var oSubmenu = oForm.find(".bx-form-input-select[name='submenu_object']");
	var oSubmenuPopup = oForm.find(".bx-form-input-checkbox[name='submenu_popup']");

	var sSubmenu = oSubmenu.val();
	var bSubmenu = sSubmenu != '';
	var bSubmenuPopup = oSubmenuPopup.is(':checked');
	oSubmenuPopup.parents('.bx-form-element-wrapper:first').bx_anim(bSubmenu ? 'show' : 'hide', this.sAnimationEffect, this.iAnimationSpeed);

	
	var oLink = oForm.find(".bx-form-input-text[name='link']");
	var sLink = oLink.val();
	var oOnclick = oForm.find(".bx-form-input-text[name='onclick']");
	var sOnclick = oOnclick.val();
	if(bSubmenu && bSubmenuPopup) {
		if(sLink == '')
			oLink.val("javascript:void(0);");

		if(sOnclick == '' || sOnclick.substr(0, 13) == 'bx_menu_popup')
			oOnclick.val("bx_menu_popup('" + sSubmenu + "', this);");
	}
	else {
		if(sLink == 'javascript:void(0);')
			oLink.val("");

		if(sOnclick.substr(0, 13) == 'bx_menu_popup')
			oOnclick.val("");
	}
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
				bx_alert(oData.message);
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
