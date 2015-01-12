function BxTimelineShare(oOptions) {
	this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oTimelineShare' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxTimelineShare.prototype = new BxTimelineMain();

BxTimelineShare.prototype.shareItem = function(oLink, iOwnerId, sType, sAction, iId) {
	var $this = this;
	var oDate = new Date();
	var oParams = {
		owner_id: iOwnerId,
		type: sType,
		action: sAction,
		object_id: iId,
		_t: oDate.getTime()	
	};

	this.loadingInItem(oLink, true);

	jQuery.post(
        this._sActionsUrl + 'share/',
        oParams,
        function(oData) {
        	$this.loadingInItem(oLink, false);

        	if(oData && oData.msg != undefined && oData.msg.length > 0)
                alert(oData.msg);

        	if(oData && oData.counter != undefined) {
        		var sCounter = $(oData.counter).attr('id');
        		$('#' + sCounter).replaceWith(oData.counter);
        		$('#' + sCounter).parents('.' + $this.sSP + '-share-counter-holder:first').bx_anim(oData.count > 0 ? 'show' : 'hide');
        	}

        	if(oData && oData.disabled)
    			$(oLink).removeAttr('onclick').addClass($(oLink).hasClass('bx-btn') ? 'bx-btn-disabled' : $this.sSP + '-share-disabled');
        },
        'json'
    );
};

BxTimelineShare.prototype.toggleByPopup = function(oLink, iId) {
    var oData = this._getDefaultData();
    oData['id'] = iId;

	$(oLink).dolPopupAjax({
		id: this._aHtmlIds['by_popup'] + iId, 
		url: bx_append_url_params(this._sActionsUri + 'get_shared_by/', oData)
	});

	return false;
};
