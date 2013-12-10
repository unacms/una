function BxTimelinePost(oOptions) {
	this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oTimelinePost' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : parseInt(oOptions.iOwnerId);
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    var $this = this;
    $(document).ready(function () {
    	$($this.sIdPost + ' form').each(function() {
    		var sId = $(this).attr('id');
    		$this.initFormPost(sId);
    	});
	});
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

BxTimelinePost.prototype.showLinkField = function(oElement) {
	$(oElement).parents('form:first').find('#bx-form-element-link').bx_anim('toggle', this._sAnimationEffect, this._iAnimationSpeed);
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
    oData['id'] = iPostId;

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

        		if(!$this.isMasonry())
        			$this.initMasonry();

        		$this.prependMasonry($(oData.item).bxTime());
        	}
        },
        'json'
    );
};