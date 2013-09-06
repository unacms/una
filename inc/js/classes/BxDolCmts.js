/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

function BxDolCmts (options) {
    this.oCmtElements = {}; // form elements
    this._sObjName = undefined == options.sObjName ? 'oCmts' : options.sObjName;    // javascript object name, to run current object instance from onTimer
    this._sSystem = options.sSystem; // current comment system
    this._sSystemTable = options.sSystemTable; // current comment system table name
    this._iAuthorId = options.iAuthorId; // this comment's author ID.
    this._iObjId = options.iObjId; // this object id comments
    this._sActionsUrl = options.sBaseUrl + 'cmts.php'; // actions url address

    this._sPostFormPosition = undefined == options.sPostFormPosition ? 'top' : options.sPostFormPosition;
    this._sBrowseType = undefined == options.sBrowseType ? 'tail' : options.sBrowseType;
    this._sDisplayType = undefined == options.sDisplayType ? 'threaded' : options.sDisplayType;

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';

    this._oSavedTexts = {};
    this._sRootId = '#cmts-box-' + this._sSystem + '-' + this._iObjId;

    // init post comment form (because browser remeber last inputs, we need to clear it)
    if ($('#cmts-box-' + this._sSystem + '-' + this._iObjId + ' .cmt-post-reply form').length) {
    	$('#cmts-box-' + this._sSystem + '-' + this._iObjId + ' .cmt-post-reply form')[0].reset();
    	$('#cmts-box-' + this._sSystem + '-' + this._iObjId + ' .cmt-post-reply form > [name=CmtParent]').val(0);    
    }
}

/*--- Main layout functionality ---*/
BxDolCmts.prototype.cmtSubmit = function (f)
{
    var eSubmit = $(f).children().find(':submit').get();
    var $this = this;
    var oData = this._getDefaultActions();

    // hide any errors before submitting
    $this._error(eSubmit, false);

    // get and check form elements
    if (!this._getCheckElements (f, oData)) 
    	return;

    // submit form
    oData['action'] = 'CmtPost';

    this._loading (eSubmit, true);
    jQuery.post (
        this._sActionsUrl,
        oData,
        function (s) {
            $this._loading (eSubmit, false);

            var iNewCmtId = parseInt(s);
            if(iNewCmtId > 0) {
                $(f).find(':input:not(:button,:submit,[type = hidden],[type = radio],[type = checkbox])').val('');
                $this._getCmt(f, oData['CmtParent'], iNewCmtId); // display just posted comment
            }
            else if (!jQuery.trim(s).length) {
                $this._error(eSubmit, true, aDolLang['_Error occured']); // display error
            }
            else {
                $this._error(eSubmit, true, s); // display error
            }
        }
    );
};

BxDolCmts.prototype.cmtUpdate = function (f, iCmtId)
{
    var eSubmit = $(f).find(':submit').get();
    var $this = this;
    var oData = this._getDefaultActions();

    $this._error(eSubmit, false); // hide any errors before submitting

    if (!this._getCheckElements (f, oData)) return; // get and check form elements

    this._oSavedTexts[iCmtId] = '';

    // submit form
    oData['action'] = 'CmtEditSubmit';
    oData['Cmt'] = iCmtId;
    this._loading (eSubmit, true);
    jQuery.post (
        this._sActionsUrl,
        oData,
        function (oResponse) {
            $this._loading (eSubmit, false);
            if (!jQuery.trim(oResponse.text).length)
                $this._error(eSubmit, true, jQuery.trim(oResponse.err).length ? oResponse.err : aDolLang['_Error occured']); // display error
            else
                $('#cmt' + iCmtId + ' .cmt-body').bx_anim(
                    'hide',
                    $this._sAnimationEffect,
                    $this._iAnimationSpeed,
                    function() {
                        $(this).html(oResponse.text).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
                    });
        },
        'json'
    );
};

BxDolCmts.prototype.cmtReload = function(iCmtId) {
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtGet';
    oData['Cmt'] = iCmtId;
    oData['Type'] = 'reload';

    var eUl = $('#cmts-box-' + $this._sSystem + '-' + $this._iObjId + ' > div.cmts > ul').get();
    this._loading (eUl, true);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function (s) {
            $this._loading (eUl, false);
            $('#cmts-box-' + $this._sSystem + '-' + $this._iObjId + ' li#cmt' + iCmtId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                $(this).replaceWith(s);
            });

        }
    );

};

BxDolCmts.prototype.cmtRemove = function(e, iCmtId) {
    if (!this._confirm()) return;

    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtRemove';
    oData['Cmt'] = iCmtId;

    this._loading (e, true);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function(s) {
            $this._loading (e, false);

            if (jQuery.trim(s).length)
                alert(s);
            else
                $('#cmt' + iCmtId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                	var oCounter = $(this).parent('ul.cmts').siblings('.cmt-cont').find('.cmt-actions a.cmt-comment-replies span');
                	if(oCounter)
                		oCounter.html(oCounter.html() - 1);

                	$(this).remove();
                });
        }
    );
};

BxDolCmts.prototype.cmtEdit = function(oLink, iCmtId) {
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtEdit';
    oData['Cmt'] = iCmtId;

    var sBodyId = this._sRootId + ' #cmt' + iCmtId + ' .cmt-body:first';
    if ($(sBodyId + ' > form').length) {
        $(sBodyId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
            $(this).html($this._oSavedTexts[iCmtId]).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
        });
        return;
    }
    else
        this._oSavedTexts[iCmtId] = $(sBodyId).html();

    this._loading (oLink, true);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function (s) {
        	$this._loading (oLink, false);

        	if(s.substring(0,3) == 'err')
        		alert(s.substring(3));
            else
                $(sBodyId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                    $(this).html(s).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
                });
        }
    );
};

BxDolCmts.prototype.cmtRate = function(e, iCmtId, iRate) 
{
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtRate';
    oData['Cmt'] = iCmtId;
    oData['Rate'] = iRate;

    this._loading (e, true);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function (s) {
            $this._loading (e, false);
            if(jQuery.trim(s).length)
                alert(s);
            else if(iRate == 1) {
                var oPoints = $(e).parents('.cmt:first').find('.cmt-points span');
                oPoints.html(parseInt(oPoints.html()) + iRate);
            }
            else if(iRate == -1) {
                $this.cmtReload(iCmtId);
            }
        }
    );
};

BxDolCmts.prototype.cmtMore = function(oLink, iCmtParentId, iStart, iPerView)
{
	var $this = this;
	this._getCmts(oLink, iCmtParentId, iStart, iPerView, this._sBrowseType, this._sDisplayType, function(sListId, sContent) {
		$this._cmtsReplace($(oLink).parents('li:first'), sContent);
	});
};

BxDolCmts.prototype.cmtChangeDisplay = function(oLink, sType)
{
	var $this = this;
	this._getCmts(oLink, 0, undefined, undefined, this._sBrowseType, sType, function(sListId, sContent) {
		$this._sDisplayType = sType;
		$this._cmtsReplaceContent($(sListId), sContent);
	});
};

BxDolCmts.prototype.cmtChangeBrowse = function(oLink, sType)
{
	var $this = this;
	this._getCmts(oLink, 0, undefined, undefined, sType, this._sDisplayType, function(sListId, sContent) {
		$this._sBrowseType = sType;
		$this._cmtsReplaceContent($(sListId), sContent);
	});
};

BxDolCmts.prototype.showReplacement = function(iCmtId)
{
    $('#cmt' + iCmtId + '-hidden').bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function(){
        $(this).next('#cmt' + iCmtId).bx_anim('show', this._sAnimationEffect, this._iAnimationSpeed);
    });
};

BxDolCmts.prototype.seeMore = function(oLink)
{
	$(oLink).next('span').show().prev('a').remove();
};

BxDolCmts.prototype.toggleReply = function(e, iCmtParentId)
{
	var sParentId = this._sRootId + ' #cmt' + iCmtParentId;
	var sReplyId = sParentId + ' > .cmt-reply';

    if ($(sReplyId).length)
		$(sReplyId).bx_anim('toggle', this._sAnimationEffect, this._iAnimationSpeed);
    else {
        var $this = this;
        var oData = this._getDefaultActions();
        oData['action'] = 'FormGet';
        oData['CmtType'] = 'reply';
        oData['CmtParent'] = iCmtParentId;
        oData['CmtBrowse'] = this._sBrowseType;
        oData['CmtDisplay'] = this._sDisplayType;

        $this._loading (e, true);

        jQuery.post (
            this._sActionsUrl,
            oData,
            function (s) {
                $this._loading(e, false);

                var oForm = $(s).css('display', 'none');
                var sFormClass = oForm.attr('class');
                var oFormSibling = $(sParentId + ' > ul.cmts:first');
                switch($this._sPostFormPosition) {
                	case 'top':
                		oForm.addClass(sFormClass + '-' + $this._sPostFormPosition);
                		oFormSibling.before(oForm);
                		break;

                	case 'bottom':
                		oForm.addClass(sFormClass + '-' + $this._sPostFormPosition);
                		oFormSibling.after(oForm);
                		break;

                	case 'both':
                		var oFormClone = oForm.clone();

                		oForm.addClass(sFormClass + '-top');
                		oFormSibling.before(oForm);

                		oFormClone.addClass(sFormClass + '-bottom');
                		oFormSibling.after(oFormClone);
                		break;
                }
            	$(sParentId).children('.cmt-reply').bx_anim('toggle', $this._sAnimationEffect, $this._iAnimationSpeed);
            }
        );
    }
};

// get comment replies via ajax request
BxDolCmts.prototype._getCmts = function (e, iCmtParentId, iStart, iPerView, sBrowseType, sDisplayType, onLoad)
{
    var $this = this;
    var oData = this._getDefaultActions();    
    oData['action'] = 'CmtsGet';
    oData['CmtParent'] = iCmtParentId;
    if(parseInt(iStart) >= 0)
        oData['CmtStart'] = iStart;
    if(parseInt(iPerView) >= 0)
        oData['CmtPerView'] = iPerView;
    if(sDisplayType)
        oData['CmtDisplay'] = sDisplayType;
    if(sBrowseType)
        oData['CmtBrowse'] = sBrowseType;

    var sListId =  this._sRootId + ' #cmt' + iCmtParentId + ' > ul:first';

    if(e)
        this._loading (e, true);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function(s) {
        	$this._loading(e, false);

        	if(typeof onLoad == 'function')
    			onLoad(sListId, s);
        	else
        		$this._cmtsAppend(sListId, s);
        }
    );
};

BxDolCmts.prototype._cmtsAppend = function(sIdTo, sContent)
{
	$(sIdTo).append($(sContent).hide()).children(':hidden').bx_anim('show', this._sAnimationEffect, this._iAnimationSpeed);
};

BxDolCmts.prototype._cmtsPrepend = function(sIdTo, sContent)
{
	$(sIdTo).prepend($(sContent).hide()).children(':hidden').bx_anim('show', this._sAnimationEffect, this._iAnimationSpeed);
};

BxDolCmts.prototype._cmtsReplace = function(oReplace, sContent)
{
	var $this = this;
	$(oReplace).bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
		$(this).after($(sContent).hide()).nextAll(':hidden').bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
		$(this).remove();
	});
};
BxDolCmts.prototype._cmtsReplaceContent = function(oParent, sContent)
{
	var $this = this;
	$(oParent).bx_anim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
		$(this).html(sContent).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
	});
};

// get just posted 1 comment via ajax request
BxDolCmts.prototype._getCmt = function (f, iCmtParentId, iCmtId)
{
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtGet';
    oData['Cmt'] = iCmtId;
    oData['CmtBrowse'] = this._sBrowseType;
    oData['CmtDisplay'] = this._sDisplayType;

    this._loading (f);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function (oData) {
            $this._loading (f, false);

            var sListId = $this._sRootId + ' #cmt' + oData.vparent_id + ' > ul';
            var sReplyFormId = $this._sRootId + ' #cmt' + oData.parent_id + ' > .cmt-reply';

            //--- Hide reply form ---//
            if($(sReplyFormId).length)
            	$(sReplyFormId).bx_anim('hide', $this._sAnimationEffect, $this._iAnimationSpeed);

            //--- Some number of comments already loaded ---//
            if($(sListId + ' > li.cmt').length)
                $(sListId + ' > li.cmt:last').after($(oData.content).hide()).next('li.cmt:hidden').bx_anim('toggle', $this._sAnimationEffect, $this._iAnimationSpeed);
            //-- There is no comments at all ---//
            else
            	$(sListId).hide().append(oData.content).bx_anim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
        },
        'json'
    );
};

// check and get post new comment form elements
BxDolCmts.prototype._getCheckElements = function(f, oData) {
    var $this = this;
    var bSuccess = true;
    // check/get form elements
    jQuery.each( $(f).find(':input'), function () {
        if (this.name.length && $this.oCmtElements[this.name]) {
            var isValid = true;

            //--- Check form's data ---//
            if ($this.oCmtElements[this.name]['reg']) {
                try {
                	var r = new RegExp($this.oCmtElements[this.name]['reg']); 
                 	isValid = r.test(this.value.replace(/(\n|\r)/g, ''));
                } catch (ex) {};
            }
            if (!isValid) {
                bSuccess = false;
                $this._error(this, true, $this.oCmtElements[this.name]['msg']);
            }
            else {
                $this._error(this, false);
            }

            //--- Fill in data array ---//
            if(this.type == 'radio') {
                if(this.checked)
                    oData[this.name] = this.value;
            }
            else
                oData[this.name] = this.value;
        }
    });
    return bSuccess;
};

BxDolCmts.prototype._getDefaultActions = function() {
    return {
        'sys': this._sSystem,
        'id': this._iObjId
    };
};

BxDolCmts.prototype._loading = function(e, bShow) {
	var oParent = $(e).length ? $(e).parents('.bx-db-content:first') : $('body'); 
	bx_loading(oParent, bShow);
};

BxDolCmts.prototype._error = function(e, bShow, s) {
    if (bShow && !$(e).next('.cmt-err').length)
        $(e).after(' <b class="cmt-err">' + s + '</b>');
    else if (!bShow && $(e).next('.cmt-err').length)
        $(e).next('.cmt-err').remove();
};

BxDolCmts.prototype._confirm = function() {
    return confirm(aDolLang['_Are you sure?']);
};