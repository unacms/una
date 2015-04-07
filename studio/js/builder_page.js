/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioBuilderPage(oOptions) {
	this.sActionUrl = oOptions.sActionUrl;
	this.sPageUrl = oOptions.sPageUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioBuilderPage' : oOptions.sObjName;
    this.sType = oOptions.sType == undefined ? '' : oOptions.sType;
    this.sPage = oOptions.sPage == undefined ? '' : oOptions.sPage;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.oHtmlIds = oOptions.oHtmlIds == undefined ? {} : oOptions.oHtmlIds;
    this.oLanguages = oOptions.oLanguages == undefined ? {} : oOptions.oLanguages;

    this.aSortingConf = {
    	parent: '.adm-bp-cell-cnt',
    	parent_prefix: 'adm-bpc-',
		item: '.adm-bp-block',
		placeholder: 'adm-bp-block adm-bp-block-empty'
    };

    var $this = this;
    $(document).ready(function() {
    	$($this.aSortingConf.parent).sortable({
    		handle: $($this.aSortingConf.item + ' .adm-bpb-drag-handle'),
    		items: $this.aSortingConf.item, 
    		placeholder: $this.aSortingConf.placeholder,
    		connectWith: $this.aSortingConf.parent,
    		start: function(oEvent, oUi) {
                oUi.item.addClass('adm-bp-block-dragging').width(300);
    		},
    		stop: function(oEvent, oUi) {
                oUi.item.removeClass('adm-bp-block-dragging');
                $this.reorder(oUi.item);
    		}
    	});
    });
}

/**
 * Main page methods.
 */
BxDolStudioBuilderPage.prototype.onChangePage = function(oSelect) {
	document.location.href = this.parsePageUrl({page: $(oSelect).val()});
};

BxDolStudioBuilderPage.prototype.getUri = function(oElement) {
	var mixedParams = {};

	var sUri = $('#' + this.oHtmlIds['uri_field_id']).val();
	if(sUri.length > 0)
		mixedParams['uri'] = sUri;
	else {
		var bGot = false;

		$.each(this.oLanguages, function(sKey, sValue) {
			mixedParams[sKey] = $('#bx-form-input-title-' + sKey).val();
			if(mixedParams[sKey].length > 0)
				bGot = true;
		});

		if(!bGot)
			return;
	}

	bx_loading(this.oHtmlIds['add_popup_id'], true);

	this.performAction('uri_get', mixedParams);
};

BxDolStudioBuilderPage.prototype.onGetUri = function(oData) {
	bx_loading(this.oHtmlIds['add_popup_id'], false);

	$('#' + this.oHtmlIds['uri_field_id']).val(oData.uri);
	$('#' + this.oHtmlIds['url_field_id']).val(oData.url);
};

BxDolStudioBuilderPage.prototype.onCreatePage = function(sType, sPage) {
	window.location.href = this.parsePageUrl({type: sType, page: sPage});
};

BxDolStudioBuilderPage.prototype.deletePage = function() {
	if(!confirm(aDolLang['_adm_bp_wrn_page_delete']))
		return;

	this.performAction('page_delete');
};

BxDolStudioBuilderPage.prototype.reorder = function(oDraggable) {
	var $this = this;
	var oDate = new Date();

	var aParams = new Array();
	$(this.aSortingConf.parent).each(function(iIndex, oElement){
		var sId = $(oElement).attr('id');
		aParams.push($('#' + sId).sortable('serialize', {key: 'bp_items_' + sId.replace($this.aSortingConf.parent_prefix, '') + '[]'}));
	});

	$.post(
		this.sActionUrl + (this.sActionUrl.indexOf('?') == -1 ? '?' : '&') + aParams.join('&'),
		{
			bp_action: 'reorder',
			bp_page: $this.sPage,
			_t:oDate.getTime()
		},
		function(oData) {
			if(oData.code != 0) {
				alert(oData.message);
				return;
			}
		},
		'json'
	);

	return true;
};

/**
 * "Add Block" popup methods. 
 */
BxDolStudioBuilderPage.prototype.onChangeModule = function(sName, oLink) {
	var $this = this;
	var oDate = new Date();

	var sClass = 'bx-menu-tab-active';
	$(oLink).parents('.bx-std-pmen-item:first').addClass(sClass).siblings('.bx-std-pmen-item').removeClass(sClass);

	var sId = '#' + $this.oHtmlIds['block_list_id'] + sName;
	if($(sId).length != 0) {
		$('#' + this.oHtmlIds['block_lists_id'] + ' > div:visible').bx_anim('hide', this.sAnimationEffect, 0, function() {
			$(sId).show();
		});

		return;
	}

	bx_loading(this.oHtmlIds['create_block_popup_id'], true);

	$.post(
		this.sActionUrl,
		{
			bp_action: 'block_list',
			bp_module: sName,
			_t:oDate.getTime()
		},
		function(oData) {
			bx_loading($this.oHtmlIds['create_block_popup_id'], false);

			$('#' + $this.oHtmlIds['block_lists_id'] + ' > div:visible').bx_anim('hide', $this.sAnimationEffect, 0, function() {
				$(this).parent().append(oData.content);
			});
		},
		'json'
	);
};

BxDolStudioBuilderPage.prototype.onSelectBlock = function(oCheckbox) {
	var iCounter = parseInt($('#adm-bp-cbf-counter').html());
	iCounter += $(oCheckbox).attr('checked') == 'checked' ? 1 : -1;

	$('#adm-bp-cbf-counter').html(iCounter);
};

BxDolStudioBuilderPage.prototype.onCreateBlock = function(oData) {
	window.location.href = this.parsePageUrl({page: this.sPage});
};

BxDolStudioBuilderPage.prototype.deleteBlockImage = function(iId) {
	bx_loading(this.oHtmlIds['edit_block_popup_id'], true);
	this.performAction('image_delete', {id:iId});
};

BxDolStudioBuilderPage.prototype.onChangeVisibleFor = function(oSelect) {
	$(oSelect).parents('form:first').find('#bx-form-element-visible_for_levels').bx_anim($(oSelect).val() == 'all' ? 'hide' : 'show', this.sAnimationEffect, this.iAnimationSpeed);
};

BxDolStudioBuilderPage.prototype.onEditBlock = function(oData) {
	window.location.href = this.parsePageUrl({page: this.sPage});
};

BxDolStudioBuilderPage.prototype.deleteBlock = function(iId) {
	if(!confirm(aDolLang['_adm_bp_wrn_page_block_delete']))
		return;

	bx_loading(this.oHtmlIds['edit_block_popup_id'], true);
	this.performAction('block_delete', {id:iId});
};

BxDolStudioBuilderPage.prototype.onDeleteBlock = function(iId, oData) {
	bx_loading(this.oHtmlIds['edit_block_popup_id'], false);
	$('.bx-popup-applied:visible').dolPopupHide();

	$('#' + this.oHtmlIds['block_id'] + iId).bx_anim('hide', this.sAnimationEffect, this.iAnimationSpeed, function() {
		$(this).remove();
	});
};

/**
 * "Settings" popup methods.
 */
BxDolStudioBuilderPage.prototype.onChangeSettingGroup = function(sName, oLink) {
	var $this = this;

	var sClass = 'bx-menu-tab-active';
	$(oLink).parents('.bx-std-pmen-item:first').addClass(sClass).siblings('.bx-std-pmen-item').removeClass(sClass);

	$('#' + this.oHtmlIds['settings_groups_id'] + ' > div:visible').bx_anim('hide', this.sAnimationEffect, 0, function() {
		$('#' + $this.oHtmlIds['settings_group_id'] + sName).show();
	});
};

BxDolStudioBuilderPage.prototype.onChangeLayout = function(iId, oLink) {
	$('#' + this.oHtmlIds['settings_group_id'] + 'layout > .adm-bp-layout-active').removeClass('adm-bp-layout-active');
	$('#' + this.oHtmlIds['layout_id'] + iId).addClass('adm-bp-layout-active');
	$("[name = 'layout_id']").val(iId);
};

BxDolStudioBuilderPage.prototype.onSaveSettings = function() {
	window.location.href = this.parsePageUrl({page: this.sPage});
};

/**
 * General methods.
 */
BxDolStudioBuilderPage.prototype.performAction = function(sAction, aParams) {
	var $this = this;
	var oDate = new Date();

	if(aParams == undefined)
		aParams = {};

	aParams.bp_action = sAction;
	aParams.bp_type = $this.sType;
	aParams.bp_page = $this.sPage;
	aParams._t = oDate.getTime();

	if($('.bx-loading-ajax:visible').length == 0)
		bx_loading('bx-std-page-columns', true);

	$.post(
		this.sActionUrl,
		aParams,
		function(oData) {
			oBxDolStudioPage.processJson(oData);
		},
		'json'
	);
};

BxDolStudioBuilderPage.prototype.parsePageUrl = function(aParams) {
	var sType = aParams.type != undefined ? aParams.type : this.sType;
	var sPage = aParams.page != undefined ? aParams.page : '';

	return this.sPageUrl.replace('{0}', sType).replace('{1}', sPage);
};
/** @} */
