/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
function BxDolStudioStore(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioStore' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDolStudioStore.prototype.install = function(sValue, oInput) {
	var $this = this;
	var onSuccess = function() {
		$(oInput).hide(0, function() {
			$(this).siblings(':hidden').show(0);
		});
	};

	return this.perform('install', sValue, onSuccess);
};

BxDolStudioStore.prototype.remove = function(sValue) {
    return this.perform('delete', sValue);
};

BxDolStudioStore.prototype.perform = function(sType, sValue, onSuccess) {
	var oDate = new Date();
	var $this = this;

	if(!sValue)
        return false;

    $.post(
    	this.sActionsUrl,
    	{
    		str_action: sType,
    		str_value: sValue,
    		_t:oDate.getTime()
    	},
    	function (oData) {
    		if(oData.message.length > 0) {
    			var sId = 'bx-std-str-popup-' + sType;
    	        $('#' + sId).remove();
    	        $('<div id="' + sId + '" style="display: none;"></div>').prependTo('body').html(oData.message);
	            $('#' + sId).dolPopup({});
    		}

    		if(oData.code == 0 && typeof onSuccess == 'function')
    			onSuccess();
    	},
    	'json'
    );
};

/**
 * Is needed if AJAX is used to change (reload) pages. 
 */
BxDolStudioStore.prototype.changePage = function(sType) {
	var oDate = new Date();
	var $this = this;

	$.get(
		this.sActionsUrl,
		{
			str_action: 'get-products-by-type',
			str_value: sType,
			_t:oDate.getTime()
		},
		function(oData) {
			if(oData.code != 0) {
				alert(oData.message);
				return;
			}

			$('#bx-std-pc-menu > .bx-std-pmi-active').removeClass('bx-std-pmi-active');
			$('#bx-std-pmi-' + sType).addClass('bx-std-pmi-active');

			$('#bx-std-pc-content').bx_anim('hide', $this.sAnimationEffect, $this.iAnimationSpeed, function() {
				$(this).html(oData.content).bx_anim('show', $this.sAnimationEffect, $this.iAnimationSpeed);
			});
		},
		'json'
	);

	return true;
};
/** @} */