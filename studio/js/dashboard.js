/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
function BxDolStudioDashboard(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioDashboard' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    var $this = this;
    $(document).ready(function() {
    	$('.bx-dbd-block-content').bxTime();

    	$this.checkForUpdateScript();
    });
}

BxDolStudioDashboard.prototype.checkForUpdateScript = function() {
	var $this = this;
	var oDate = new Date();
	var sDivId = 'bx-dbd-update-script';

	$.get(
		this.sActionsUrl,
		{
			dbd_action: 'check_update_script',
			_t: oDate.getTime()
		},
		function(oData) {
			if(!oData.version)
			    return;

			$('#' + sDivId + ' span').html(_t('_adm_dbd_txt_dolphin_n_available', oData.version)).parents('#' + sDivId + ':hidden').show();
		},
		'json'
	);
};
/** @} */