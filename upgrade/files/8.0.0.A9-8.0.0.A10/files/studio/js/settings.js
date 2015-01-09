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
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

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
/** @} */
