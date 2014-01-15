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

    this.sVersion = oOptions.sVersion == undefined ? '' : oOptions.sVersion;

    var $this = this;
    $(document).ready(function() {
    	$('.bx-dbd-block-content').bxTime();

    	$this.checkForUpdateScript();
    });
}

BxDolStudioDashboard.prototype.checkForUpdateScript = function() {
	var $this = this;
	var sDivId = 'bx-dbd-update-script';

	$.get(
		sUrlRoot + 'get_rss_feed.php?ID=boonex_version&member=0',
		{},
		function(sData) {
			if(!sData)
			    return;

			var sVersion = $(sData).find('dolphin').html();
			if(sVersion != undefined && sVersion != null && sVersion != '' && sVersion != $this.sVersion)
				$('#' + sDivId + ' span').html(_t('_adm_dbd_txt_dolphin_n_available', sVersion)).parents('#' + sDivId + ':hidden').show();
		},
		'text'
	);
};
/** @} */