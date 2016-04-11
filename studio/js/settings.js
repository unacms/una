/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioSettings(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioSettings' : oOptions.sObjName;

    this.sType = oOptions.sType == undefined ? '' : oOptions.sType;
    this.sCategory = oOptions.sCategory == undefined ? '' : oOptions.sCategory;
    this.sMix = oOptions.sMix == undefined ? '' : oOptions.sMix;

    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDolStudioSettings.prototype.mixSelect = function(oSelect) {
	var $this = this;
	var oDate = new Date();

	$.post(
    	sUrlStudio + 'settings.php',
    	{
    		page: this.sType,
    		category: this.sCategory,
    		stg_action: 'select-mix',
    		stg_value: $(oSelect).val(),
    		_t:oDate.getTime()
    	},
    	function (oData) {
    		$this.processResult(oData);
    	},
    	'json'
    );
};

BxDolStudioSettings.prototype.onMixSelect = function(oData) {
	document.location.href = document.location.href;
};

BxDolStudioSettings.prototype.mixCreate = function(oButton) {
	var $this = this;
	var oDate = new Date();

	$('.bx-popup-applied:visible').dolPopupHide();

	$.post(
    	sUrlStudio + 'settings.php',
    	{
    		page: this.sType,
    		category: this.sCategory,
    		stg_action: 'create-mix',
    		_t:oDate.getTime()
    	},
    	function (oData) {
    		$this.processResult(oData);
    	},
    	'json'
    );
};

BxDolStudioSettings.prototype.onMixCreate = function(oData) {
	document.location.href = document.location.href;
};

BxDolStudioSettings.prototype.mixDelete = function(oButton, iId) {
	var $this = this;
	var oDate = new Date();

	$.post(
    	sUrlStudio + 'settings.php',
    	{
    		page: this.sType,
    		category: this.sCategory,
    		stg_action: 'delete-mix',
    		stg_value: iId,
    		_t:oDate.getTime()
    	},
    	function (oData) {
    		$this.processResult(oData);
    	},
    	'json'
    );
};

BxDolStudioSettings.prototype.onMixDelete = function(oData) {
	document.location.href = document.location.href;
};

/**
 * Is needed if AJAX is used to change (reload) pages. 
 */
BxDolStudioSettings.prototype.changePage = function(sType) {
	var oDate = new Date();
	var $this = this;

	$.get(
		this.sActionsUrl,
		{
			stg_action: 'get-page-by-type',
			stg_value: sType,
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

BxDolStudioSettings.prototype.processResult = function(oData) {
	var $this = this;

	if(oData && oData.message != undefined && oData.message.length != 0)
    	alert(oData.message);

    if(oData && oData.reload != undefined && parseInt(oData.reload) == 1)
    	document.location = document.location;

    if(oData && oData.popup != undefined) {
    	var oPopup = $(oData.popup).hide(); 

    	$('#' + oPopup.attr('id')).remove();
        oPopup.prependTo('body').dolPopup({
            fog: {
				color: '#fff',
				opacity: .7
            },
            closeOnOuterClick: false
        });
    }

    if (oData && oData.eval != undefined)
        eval(oData.eval);
};
/** @} */
