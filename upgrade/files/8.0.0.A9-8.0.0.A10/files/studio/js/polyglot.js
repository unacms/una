/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioPolyglot(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioPolyglot' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.sPage = oOptions.sPage == undefined ? 'general' : oOptions.sPage;
}

/**
 * Is needed if AJAX is used to change (reload) pages. 
 */
BxDolStudioPolyglot.prototype.changePage = function(sType, iStart, iLength) {
	var oDate = new Date();
	var $this = this;
	var oParams = {
		pgt_action: 'get-page-by-type',
		pgt_value: sType,
		_t:oDate.getTime()
	};

	if(this.bShowWarning && !confirm(aDolLang['_adm_pgt_wrn_need_to_save']))
		return true;

	if(sType == 'keys') {
		oParams.pgt_category = $('#pgt-keys-category').val();
		oParams.pgt_language = $('#pgt-keys-language').val();
		oParams.pgt_keyword = $('#pgt-keys-keyword').val();
		if(iStart)
			oParams.pgt_start = iStart;
		if(iLength)
			oParams.pgt_length = iLength;
	}

	$.get(
		this.sActionsUrl,
		oParams,
		function(oData) {
			if(oData.code != 0) {
				alert(oData.message);
				return true;
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
