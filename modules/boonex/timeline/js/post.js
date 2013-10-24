function BxTimelinePost(oOptions) {    
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oTimelinePost' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : parseInt(oOptions.iOwnerId);
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;
}

BxTimelinePost.prototype = new BxTimelineMain();

BxTimelinePost.prototype.initFormPost = function(sFormId)
{
	var $this = this;
	var oForm = $('#' + sFormId);

	oForm.ajaxForm({
        dataType: "json",
        beforeSubmit: function (formData, jqForm, options) {
        	window[$this._sObjName].beforeFormPostSubmit(oForm);
        },
        success: function (oData) {
        	window[$this._sObjName].afterFormPostSubmit(oForm, oData);
        }
    });
};

BxTimelinePost.prototype.beforeFormPostSubmit = function(oForm)
{
	this.loadingInButton($(oForm).children().find(':submit'), true);
};

BxTimelinePost.prototype.afterFormPostSubmit = function (oForm, oData)
{
	this.loadingInButton($(oForm).children().find(':submit'), false);

	if(oData && oData.msg != undefined)
        alert(oData.msg);

	if(oData && oData.id != undefined) {
		var iId = parseInt(oData.id);
        if(iId <= 0) 
        	return;

        this._getPost(oForm, iId);
        this._getForm(oForm, $(oForm).find("input[name = 'type']").val());

        return;
	}

	if(oData && oData.form != undefined && oData.form_id != undefined) {
		$('#' + oData.form_id).replaceWith(oData.form);
		this.initFormPost(oData.form_id);

		return;
	}
};

BxTimelinePost.prototype.changePostType = function(oLink, sType) {    
    var $this = this;

    var oContent = $('#timeline-ptype-cnt-' + sType);
    if(oContent.html() != '') {
    	$('.timeline-ptype-cnt:visible').bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
    		oContent.bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
    	});
    	return;
    }

    if(sType == 'photo' || sType == 'music' || sType == 'video') {
        jQuery.post (
            $this._sActionsUrl + 'get_' + sType + '_uploaders/' + this._iOwnerId,
            {},
            function(sResult) {
            	if($.trim(sResult).length) {
            		oContent.filter(':visible').find('iframe[name=upload_file_frame]').remove();
            		oContent.filter('.wall_' + sType).html(sResult);            	   
            		$this._animContent(oLink, sType);
            	}
            }
        );
    }    
};

BxTimelinePost.prototype._getForm = function(oElement, sType) {
	var $this = this;
    var oData = this._getDefaultData();

	jQuery.post (
        this._sActionsUrl + 'get_post_form/' + sType + '/',
        oData,
        function(oData) {
        	if(oData && oData.form != undefined && oData.form_id != undefined) {
	        	$('#' + oData.form_id).replaceWith(oData.form);
	        	$this.initFormPost(oData.form_id);
        	}
        },
        'json'
    );
};

BxTimelinePost.prototype._getPost = function(oElement, iPostId) {
    var $this = this;
    var oData = this._getDefaultData();
    oData['post_id'] = iPostId;

    var oView = $(this.sIdView);
    this.loadingInBlock(oView, true);

    jQuery.post (
        this._sActionsUrl + 'get_post/',
        oData,
        function(oData) {
        	$this.loadingInBlock(oView, false);

        	if($.trim(oData.item).length) {
        		if(!oView.find('.bx-tl-load-more').is(':visible'))
        			oView.find('.bx-tl-load-more').show();

        		if(oView.find('.bx-tl-empty').is(':visible'))
        			oView.find('.bx-tl-empty').hide();

        		var oItems = oView.find('.bx-tl-items');
        		if(oItems.prepend(oData.item).hasClass('masonry'))
        			oItems.masonry('reload');
        		else
        			$this.initMasonry();
        	}
        },
        'json'
    );
};