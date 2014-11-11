/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

function BxDolCmts (options) {
    this._sObjName = undefined == options.sObjName ? 'oCmts' : options.sObjName;    // javascript object name, to run current object instance from onTimer
    this._sSystem = options.sSystem; // current comment system
    this._iAuthorId = options.iAuthorId; // this comment's author ID.
    this._iObjId = options.iObjId; // this object id comments
    this._sActionsUrl = options.sRootUrl + 'cmts.php'; // actions url address

    this._sPostFormPosition = undefined == options.sPostFormPosition ? 'top' : options.sPostFormPosition;
    this._sDisplayType = undefined == options.sDisplayType ? 'threaded' : options.sDisplayType;
    this._sBrowseType = undefined == options.sBrowseType ? 'tail' : options.sBrowseType;
    this._sBrowseFilter = undefined == options.sBrowseFilter ? 'all' : options.sBrowseFilter;

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';

    this._oSavedTexts = {};
    this._sRootId = '#cmts-box-' + this._sSystem + '-' + this._iObjId;

    // init post comment form
    if ($(this._sRootId + ' .cmt-post-reply form').length) {
    	var sFormId = $(this._sRootId + ' .cmt-post-reply form').attr('id');
    	this.cmtInitFormPost(sFormId);
    }
}

/*--- Main layout functionality ---*/
BxDolCmts.prototype.cmtInitFormPost = function(sCmtFormId)
{
	var $this = this;
	var oCmtForm = $('#' + sCmtFormId);

	oCmtForm.ajaxForm({
        dataType: "json",
        beforeSubmit: function (formData, jqForm, options) {
        	window[$this._sObjName].cmtBeforePostSubmit(oCmtForm);
        },
        success: function (oData) {
        	window[$this._sObjName].cmtAfterPostSubmit(oCmtForm, oData);
        }
    });
};

BxDolCmts.prototype.cmtBeforePostSubmit = function(oCmtForm)
{
	this._loadingInButton($(oCmtForm).children().find(':submit'), true);
};

BxDolCmts.prototype.cmtAfterPostSubmit = function (oCmtForm, oData)
{
	this._loadingInButton($(oCmtForm).children().find(':submit'), false);

	if(oData && oData.msg != undefined)
        alert(oData.msg);

	if(oData && oData.id != undefined) {
		var iCmtId = parseInt(oData.id);
        if(iCmtId > 0) {
        	var $this = this;

            this._getCmt(oCmtForm, iCmtId);
            this._getForm(undefined, parseInt(oData.parent_id), function(sForm) {
            	var oForm = $(sForm);
            	oCmtForm.parents('.cmt-reply:first').hide().html(oForm.html()).show();

            	var sFormId = oForm.find('form').attr('id');
            	$this.cmtInitFormPost(sFormId);
            });
        }

        return;
	}

	if(oData && oData.form != undefined && oData.form_id != undefined) {
		$('#' + oData.form_id).replaceWith(oData.form);
		this.cmtInitFormPost(oData.form_id);

		return;
	}
};

BxDolCmts.prototype.cmtInitFormEdit = function(sCmtFormId)
{
	var $this = this;
	var oCmtForm = $('#' + sCmtFormId);

	oCmtForm.ajaxForm({
        dataType: "json",
        beforeSubmit: function (formData, jqForm, options) {
        	window[$this._sObjName].cmtBeforeEditSubmit(oCmtForm);
        },
        success: function (oData) {
        	window[$this._sObjName].cmtAfterEditSubmit(oCmtForm, oData);
        }
    });
};

BxDolCmts.prototype.cmtBeforeEditSubmit = function(oCmtForm)
{
	this._loadingInButton($(oCmtForm).children().find(':submit'), true);
};

BxDolCmts.prototype.cmtAfterEditSubmit = function (oCmtForm, oData)
{
	var $this = this;
	this._loadingInButton($(oCmtForm).children().find(':submit'), false);

	if(oData && oData.msg != undefined)
        alert(oData.msg);

	if(oData && oData.id != undefined && oData.text != undefined) {
		var iCmtId = parseInt(oData.id);
		if(iCmtId > 0) {
			$('#cmt' + iCmtId + ' .cmt-body').bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
				$(this).html(oData.text).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
			});
        }

        return;
	}

	if(oData && oData.form != undefined && oData.form_id != undefined) {
		$('#' + oData.form_id).replaceWith(oData.form);
		this.cmtInitFormEdit(oData.form_id);

		return;
	}
};

BxDolCmts.prototype.cmtEdit = function(oLink, iCmtId) {
    var $this = this;
    var oParams = this._getDefaultActions();
    oParams['action'] = 'GetFormEdit';
    oParams['Cmt'] = iCmtId;

    $(oLink).parents('.cmt-comment-manage:first').dolPopupHide();

    var sBodyId = this._sRootId + ' #cmt' + iCmtId + ' .cmt-body:first';
    if ($(sBodyId + ' > form').length) {
        $(sBodyId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
            $(this).html($this._oSavedTexts[iCmtId]).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
        });
        return;
    }
    else
        this._oSavedTexts[iCmtId] = $(sBodyId).html();

    this._loadingInContent (oLink, true);

    jQuery.post (
        this._sActionsUrl,
        oParams,
        function (oData) {
        	$this._loadingInContent (oLink, false);

        	if(oData && oData.msg != undefined)
                alert(oData.msg);

        	if(oData && oData.form != undefined && oData.form_id != undefined) {
        		$(sBodyId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                    $(this).html(oData.form).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                    	$this.cmtInitFormEdit(oData.form_id);
                    });
                });
        	}
        },
        'json'
    );
};

BxDolCmts.prototype.cmtRemove = function(e, iCmtId) {
    if (!this._confirm()) 
    	return;

    var $this = this;
    var oParams = this._getDefaultActions();
    oParams['action'] = 'Remove';
    oParams['Cmt'] = iCmtId;

    this._loadingInContent(e, true);

    jQuery.post (
        this._sActionsUrl,
        oParams,
        function(oData) {
            $this._loadingInContent(e, false);

            if(oData && oData.msg != undefined)
                alert(oData.msg);

            if(oData && oData.id != undefined) {
            	$('#cmt' + oData.id).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                	var oCounter = $(this).parent('ul.cmts').siblings('.cmt-cont').find('.cmt-actions a.cmt-comment-replies span');
                	if(oCounter)
                		oCounter.html(oCounter.html() - 1);

                	$(this).remove();
                });
            }
        },
        'json'
    );
};

BxDolCmts.prototype.cmtLoad = function(oLink, iCmtParentId, iStart, iPerView)
{
	var $this = this;
	var bButton = $(oLink).hasClass('bx-btn');

	if(bButton)
		this._loadingInButton(oLink, true);
	else 
		this._loading($(oLink).parents('ul.cmts:first'), true);

	this._getCmts(null, iCmtParentId, iStart, iPerView, this._sDisplayType, this._sBrowseType, this._sBrowseFilter, function(sListId, sContent) {
		if(bButton)
			$this._loadingInButton(oLink, false);
		else 
			$this._loading($(oLink).parents('ul.cmts:first'), false);

		$this._cmtsReplace($(oLink).parents('li:first'), sContent);
	});
};

BxDolCmts.prototype.cmtChangeDisplay = function(oLink, sType)
{
	var $this = this;
	this._getCmts(oLink, 0, undefined, undefined, sType, this._sBrowseType, this._sBrowseFilter, function(sListId, sContent) {
		$this._sDisplayType = sType;
		$this._cmtsReplaceContent($(sListId), sContent);
	});
};

BxDolCmts.prototype.cmtChangeBrowse = function(oLink, sType)
{
	var $this = this;
	this._getCmts(oLink, 0, undefined, undefined, this._sDisplayType, sType, this._sBrowseFilter, function(sListId, sContent) {
		$this._sBrowseType = sType;
		$this._cmtsReplaceContent($(sListId), sContent);
	});
};

BxDolCmts.prototype.cmtChangeFilter = function(oLink, sType)
{
	var $this = this;
	this._getCmts(oLink, 0, undefined, undefined, this._sDisplayType, $this._sBrowseType, sType, function(sListId, sContent) {
		$this._sBrowseFilter = sType;
		$this._cmtsReplaceContent($(sListId), sContent);
	});
};
BxDolCmts.prototype.showLiveUpdate = function(oData)
{
	if(!oData.code)
		return;

	var iCountNew = parseInt(oData.count_new);
	var iCountOld = parseInt(oData.count_old);
	if(iCountNew <= iCountOld)
		return;

	var oNotification = $(oData.code);
	oNotification.find('.cmt-message span').html(iCountNew - iCountOld);

	var sId = oNotification.attr('id');
	$('#' + sId).remove();

    $(oNotification).prependTo('body').dolPopup({
    	position: 'fixed',
    	top: ($(window).height() - oNotification.height()) + 'px',
    	left: ($(window).width() - oNotification.width())/2 + 'px',
    	fog: false
    });
};
BxDolCmts.prototype.showReplacement = function(iCmtId)
{
    $('#cmt' + iCmtId + '-hidden').bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function(){
        $(this).next('#cmt' + iCmtId).bx_anim('show', this._sAnimationEffect, this._iAnimationSpeed);
    });
};

BxDolCmts.prototype.showMore = function(oLink)
{
	$(oLink).parent('span').next('span').show().prev('span').remove();
};

BxDolCmts.prototype.showImage = function(oLink, sUrl) {
	$(this._sRootId + '-view-image-popup').dolPopupImage(sUrl, $(oLink).parent());
};

BxDolCmts.prototype.toggleReply = function(e, iCmtParentId)
{
	var $this = this;
	var sParentId = this._sRootId + ' #cmt' + iCmtParentId;
	var fOnShow = function() {
		$(this).find('textarea:first').focus();
	};

	var sReplyId = sParentId + ' > .cmt-reply';
    if ($(sReplyId).length) {
		$(sReplyId).bx_anim('toggle', this._sAnimationEffect, this._iAnimationSpeed, fOnShow);
		return;
    }

	this._getForm(e, iCmtParentId, function(sForm) {
		var oForm = $(sForm).hide().addClass('cmt-reply-' + $this._sPostFormPosition).addClass('cmt-reply-margin');
        var oFormSibling = $(sParentId + ' > ul.cmts:first');
        switch($this._sPostFormPosition) {
        	case 'top':
        		oFormSibling.before(oForm);
        		break;

        	case 'bottom':
        		oFormSibling.after(oForm);
        		break;
        }

        var sFormId = oForm.find('form').attr('id');
        $this.cmtInitFormPost(sFormId);

    	$(sParentId).children('.cmt-reply').bx_anim('toggle', $this._sAnimationEffect, $this._iAnimationSpeed, fOnShow);    		
	});
};

BxDolCmts.prototype.toggleManagePopup = function(oLink, iCmtId) {
	var oPopup = $('#' + this._sSystem + '-manage-' + iCmtId);
	if(oPopup.is(':visible')) {
		oPopup.dolPopupHide();
		return;
	}

	oPopup.dolPopup({
		pointer:{
			el:$(oLink)
		}
	});
};

BxDolCmts.prototype._getCmt = function (e, iCmtId)
{
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'GetCmt';
    oData['Cmt'] = iCmtId;
    oData['CmtBrowse'] = this._sBrowseType;
    oData['CmtDisplay'] = this._sDisplayType;

    this._loadingInBlock (e);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function (oData) {
            $this._loadingInBlock (e, false);

            var sListId = $this._sRootId + ' #cmt' + oData.vparent_id + ' > ul';
            var sReplyFormId = $this._sRootId + ' #cmt' + oData.parent_id + ' > .cmt-reply';

            //--- Hide reply form ---//
            if($(sReplyFormId).length)
            	$(sReplyFormId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed);

            //--- Some number of comments already loaded ---//
            if($(sListId + ' > li.cmt').length)
                $(sListId + ' > li.cmt:last').after($(oData.content).hide()).next('li.cmt:hidden').bxTime().bx_anim('toggle', $this._sAnimationEffect, $this._iAnimationSpeed);
            //-- There is no comments at all ---//
            else
            	$(sListId).hide().html(oData.content).bxTime().bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
        },
        'json'
    );
};

BxDolCmts.prototype._getCmts = function (e, iCmtParentId, iStart, iPerView, sDisplayType, sBrowseType, sBrowseFilter, onLoad)
{
    var $this = this;
    var oData = this._getDefaultActions();    
    oData['action'] = 'GetCmts';
    oData['CmtParent'] = iCmtParentId;
    if(parseInt(iStart) >= 0)
        oData['CmtStart'] = iStart;
    if(parseInt(iPerView) >= 0)
        oData['CmtPerView'] = iPerView;
    if(sDisplayType)
        oData['CmtDisplay'] = sDisplayType;
    if(sBrowseType)
        oData['CmtBrowse'] = sBrowseType;
    if(sBrowseFilter)
        oData['CmtFilter'] = sBrowseFilter;

    var sListId =  this._sRootId + ' #cmt' + iCmtParentId + ' > ul:first';

    if(e)
        this._loadingInBlock(e, true);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function(s) {
        	if(e)
        		$this._loadingInBlock(e, false);

        	if(typeof onLoad == 'function')
    			onLoad(sListId, s);
        	else
        		$this._cmtsAppend(sListId, s);
        }
    );
};

BxDolCmts.prototype._getForm = function (e, iCmtParentId, onLoad)
{
	var $this = this;

    var oData = this._getDefaultActions();
    oData['action'] = 'GetFormPost';
    oData['CmtType'] = 'reply';
    oData['CmtParent'] = iCmtParentId;
    oData['CmtBrowse'] = this._sBrowseType;
    oData['CmtDisplay'] = this._sDisplayType;

    if(e)
    	this._loadingInContent(e, true);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function (s) {
        	if(e)
        		$this._loadingInContent(e, false);

            if(typeof onLoad == 'function')
    			onLoad(s);
        }
    );
};

BxDolCmts.prototype._cmtsAppend = function(sIdTo, sContent)
{
	$(sIdTo).append($(sContent).hide()).children(':hidden').bxTime().bx_anim('show', this._sAnimationEffect, this._iAnimationSpeed);
};

BxDolCmts.prototype._cmtsPrepend = function(sIdTo, sContent)
{
	$(sIdTo).prepend($(sContent).hide()).children(':hidden').bxTime().bx_anim('show', this._sAnimationEffect, this._iAnimationSpeed);
};

BxDolCmts.prototype._cmtsReplace = function(oReplace, sContent)
{
	var $this = this;
	$(oReplace).bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
		$(this).after($(sContent).hide()).nextAll(':hidden').bxTime().bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
		$(this).remove();
	});
};
BxDolCmts.prototype._cmtsReplaceContent = function(oParent, sContent)
{
	var $this = this;
	$(oParent).bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
		$(this).html(sContent).bxTime().bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
	});
};

BxDolCmts.prototype._getDefaultActions = function() {
	var oDate = new Date();
    return {
        sys: this._sSystem,
        id: this._iObjId,
        _t: oDate.getTime()
    };
};

BxDolCmts.prototype._loading = function(e, bShow) {
	var oParent = $(e).length ? $(e) : $('body'); 
	bx_loading(oParent, bShow);
};

BxDolCmts.prototype._loadingInBlock = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-db-content:first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxDolCmts.prototype._loadingInContent = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('li.cmt:first,.cmt-reply:first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxDolCmts.prototype._loadingInButton = function(e, bShow) {
	if($(e).length)
		bx_loading_btn($(e), bShow);
	else
		bx_loading($('body'), bShow);	
};

BxDolCmts.prototype._confirm = function() {
    return confirm(_t('_are you sure?'));
};