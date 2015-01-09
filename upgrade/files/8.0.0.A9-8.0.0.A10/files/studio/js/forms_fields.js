/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioFormsFields(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
	this.sPageUrl = oOptions.sPageUrl;
	this.sObjNameGrid = oOptions.sObjNameGrid;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioFormsFields' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this.sTextSearchInput = oOptions.sTextSearchInput == undefined ? '' : oOptions.sTextSearchInput;
}

BxDolStudioFormsFields.prototype.onChangeModule = function() {
	var $this = this;
	var oDate = new Date();
	var sModule = $('#bx-grid-module-' + this.sObjNameGrid).val();

	this.reloadGrid();

	bx_loading($('body'), true);

	$.post(
		this.sPageUrl,
		{
			form_action: 'get_displays',
			form_module: sModule,
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

BxDolStudioFormsFields.prototype.onChangeDisplay = function() {
	var sObject, sDisplay = $('#bx-grid-display-' + this.sObjNameGrid).val();
	if(sDisplay.indexOf(this.sParamsDivider) != -1) {
		var aDisplay = sDisplay.split(this.sParamsDivider);

		sObject = aDisplay[0];
		sDisplay = aDisplay[1];
	}

	this.reloadGrid($('#bx-grid-module-' + this.sObjNameGrid).val(), sObject, sDisplay);
};

BxDolStudioFormsFields.prototype.reloadGrid = function(sModule, sObject, sDisplay) {
	var bReload = false;

	if(!sModule) {
		sObject = '';
		sDisplay = '';
	}

	var oSearch = $('#bx-form-element-keyword');
	var oActions = $("[bx_grid_action_independent]");
	if(!sObject && !sDisplay) {
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

	if(glGrids[this.sObjNameGrid]._oQueryAppend['object'] != sObject) {
		glGrids[this.sObjNameGrid]._oQueryAppend['object'] = sObject;
		bReload = true;
	}

	if(glGrids[this.sObjNameGrid]._oQueryAppend['display'] != sDisplay) {
		glGrids[this.sObjNameGrid]._oQueryAppend['display'] = sDisplay;
		bReload = true;
	}

	if(bReload)
		glGrids[this.sObjNameGrid].reload(0);
};

BxDolStudioFormsFields.prototype.onSelectType = function(sType, oLink) {
	var oPopup = $(oLink).parents('.bx-popup-applied');
	bx_loading(oPopup, true);

	glGrids[this.sObjNameGrid].action('add', {}, 'type=' + sType, false, false);
};

BxDolStudioFormsFields.prototype.onChangeType = function(iDiId) {
	var sType = $('#bx-form-field-type').val();
	glGrids[this.sObjNameGrid].action('edit', {}, 'type=' + sType + '&di_id=' + iDiId, false, false);
};

BxDolStudioFormsFields.prototype.onSelectChecker = function(oSelect) {
	var sChecker = $(oSelect).val();

	$('#bx-form-element-checker_params_length_min, #bx-form-element-checker_params_length_max, #bx-form-element-checker_params_preg').hide().find('input').val('');

	switch(sChecker) {
		case 'length':
			$('#bx-form-element-checker_params_length_min, #bx-form-element-checker_params_length_max').show();
			break;
		case 'preg':
			$('#bx-form-element-checker_params_preg').show();
			break;
	}
};

BxDolStudioFormsFields.prototype.onCheckRequired = function(oCheckbox) {
	var sId = 'bx-form-element-checker_func';
	if($(oCheckbox).attr('checked') != undefined)
		$('#' + sId + ',#bx-form-element-checker_error').show();
	else {
		$('#' + sId).hide().find('select').val('');
		$('#bx-form-element-checker_params_length_min, #bx-form-element-checker_params_length_max, #bx-form-element-checker_params_preg, #bx-form-element-checker_error').hide().find('input').val('');
	}
};

BxDolStudioFormsFields.prototype.onChangeVisibleFor = function(oSelect) {
	$('#bx-form-element-visible_for_levels').bx_anim($(oSelect).val() == 'all' ? 'hide' : 'show', this.sAnimationEffect, this.iAnimationSpeed);
};

BxDolStudioFormsFields.prototype.onChangeValues = function(iUseForSets, oSelect) {
	var $this = this;
	var oDate = new Date();
	var oPopup = $(oSelect).parents('.bx-popup-applied');

	bx_loading(oPopup, true);

	$.post(
		this.sPageUrl,
		{
			form_action: 'values_list', 
			form_list: $(oSelect).val(),
			form_use_for_sets: iUseForSets,
			_t: oDate.getTime() 			
		},
		function(oData) {
			bx_loading(oPopup, false);

			if(oData.content)
				$('#adm-form-field-add-value').html(oData.content);
		},
		'json'
	);
};
/** @} */
