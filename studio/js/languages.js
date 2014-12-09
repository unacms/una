/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioLanguages(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioLanguages' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDolStudioLanguages.prototype.uninstall = function(iId, sValue, iConfirm) {
	var $this = this;
	var oDate = new Date();
	var sAction = 'uninstall'; 

	if(!sValue)
        return false;

	$('.bx-popup-applied:visible').dolPopupHide();

	$.post(
    	sUrlStudio + 'languages.php',
    	{
    		lang_action: sAction,
    		lang_page_name: sValue,
    		lang_widget_id: iId,
    		lang_confirmed: parseInt(iConfirm),
    		_t:oDate.getTime()
    	},
    	function (oData) {
    		if(oData.message.length > 0)
    			$this.popup(sAction, oData.message);

    		if(oData.code == 0) {
    			$('#bx-std-widget-' + iId).bx_anim('hide', $this.sAnimationEffect, $this.iAnimationSpeed, function() {
    				$(this).remove();
    			});
    		}
    	},
    	'json'
    );
};

BxDolStudioLanguages.prototype.popup = function(sType, sValue) {
	var sId = 'bx-std-lang-popup-' + sType;

    $('#' + sId).remove();
    $('<div id="' + sId + '" style="display: none;"></div>').prependTo('body').html(sValue);
    $('#' + sId).dolPopup({});
};
/** @} */
