function BxNtfsView(oOptions) {
	this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oNtfsView' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxNtfsView.prototype = new BxNtfsMain();

/**
 * Is needed to switch between different browsing types dynamically.
 * NOTE. The function isn't used for now.
 */
BxNtfsView.prototype.changeType = function(oElement, sType) {
	this._oRequestParams.start = 0;
	this._oRequestParams.type = sType;

    this._getPosts(oElement);
};

BxNtfsView.prototype.changePage = function(oElement, iStart, iPerPage) {
	this._oRequestParams.start = iStart;
    this._oRequestParams.per_page = iPerPage;

    this._getPosts(oElement);
};

BxNtfsView.prototype.markAsClicked = function(oElement, iId) {
    var oData = this._getDefaultData();
    oData['id'] = iId;

    jQuery.get(
        this._sActionsUrl + 'mark_as_clicked/',
        oData,
        function(oData) {
            if(!oData || parseInt(oData.code) !== 0)
                return;

            var sLink = $(oElement).attr('href');
            if(sLink)
                document.location = sLink;
        },
        'json'
    );

    return false;
};

BxNtfsView.prototype._getPosts = function(oElement) {
    var $this = this;

    this.loadingInBlock(oElement, true);

    jQuery.get(
        this._sActionsUrl + 'get_posts/',
        this._getDefaultData(),
        function(oData) {
        	if(oData && oData.events != undefined) {
        		var sEvents = $.trim(oData.events);

        		$this.loadingInBlock(oElement, false);

    			$('#' + $this._aHtmlIds['events']).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
	                $(this).html(sEvents).show().bxProcessHtml();
	            });
        	}
        },
        'json'
    );
};
