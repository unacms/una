function BxSitesMain(oOptions) {
    this._sSystem = oOptions.sSystem;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oSitesMain' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxSitesMain.prototype.onCancelSubscription = function(oForm) {
	return confirm(aDolLang['_bx_sites_form_site_input_do_cancel_confirm']) ? true : false;
};

/*
 * Can be removed if it's not used.
 * 
BxSitesMain.prototype.reactivate = function(iId, oButton) {
    var oDate = new Date();
    var oParams = {
        _t:oDate.getTime()
    };

    bx_loading($(oButton).parents('.bx-page-block-container:first'), true);

    $.get(
        this._sActionsUrl + 'reactivate/' + iId,
        oParams,
        function(oData) {
        	if(oData.code != 0) {
        		alert(oData.message);
        		return;
        	}

        	if(oData.redirect != undefined)
        		parent.window.open(oData.redirect, '_self');
        },
        'json'
    );
};
*/
