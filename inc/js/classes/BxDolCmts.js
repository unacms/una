/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

$.fn.bxdolcmtanim = function(action, effect, speed, h)
{
   return this.each(function()
   {
           var sFunc = '';
           var sEval;

           if (0 == speed)
               effect = 'default';

          switch (action)
          {
              case 'show':
                  switch (effect)
                  {
                      case 'slide': sFunc = 'slideDown'; break;
                      case 'fade': sFunc = 'fadeIn'; break;
                      default: sFunc = 'show';
                  }
                  break;
              case 'hide':
                  switch (effect)
                  {
                      case 'slide': sFunc = 'slideUp'; break;
                      case 'fade': sFunc = 'fadeOut'; break;
                      default: sFunc = 'hide';
                  }
                  break;
              default:
              case 'toggle':
                  switch (effect)
                  {
                      case 'slide': sFunc = 'slideToggle'; break;
                      case 'fade': sFunc = ($(this).filter(':visible').length) ? 'fadeOut' : 'fadeIn'; break;
                      default: sFunc = 'toggle';
                  }
          }

          if ((0 == speed || undefined == speed) && undefined == h) {
              sEval = '$(this).' + sFunc + '();';
          }
          else if ((0 == speed || undefined == speed) && undefined != h) {
              sEval = '$(this).' + sFunc + '(); $(this).each(h);';
          }
          else {
              sEval = '$(this).' + sFunc + "('" + speed + "', h);";
          }
          eval(sEval);

          return this;
   });
};


function BxDolCmts (options) {
    this.oCmtElements = {}; // form elements
    this._sObjName = undefined == options.sObjName ? 'oCmts' : options.sObjName;    // javascript object name, to run current object instance from onTimer
    this._sSystem = options.sSystem; // current comment system
    this._sSystemTable = options.sSystemTable; // current comment system table name
    this._iAuthorId = options.iAuthorId; // this comment's author ID.
    this._iObjId = options.iObjId; // this object id comments
    this._sOrder = options.sOrder == 'asc' || options.sOrder == 'desc' ? options.sOrder : 'asc'; // comments' order
    this._sActionsUrl = options.sBaseUrl + 'cmts.php'; // actions url address
    this._sDefaultErrMsg = undefined == options.sDefaultErrMsg ? 'Errod Occured' : options.sDefaultErrMsg; // default error message
    this._sConfirmMsg = undefined == options.sConfirmMsg ? 'Are you sure?' : options.sConfirmMsg; // confirm message
    this._sAnimationEffect = 'slide';
    this._iAnimationSpeed = 'slow';

    this._oSavedTexts = {};

    this._sAnimationEffect = undefined == options.sAnimationEffect ? 'slide' : options.sAnimationEffect;
    this._iAnimationSpeed = undefined == options.sAnimationSpeed ? 'slow' : options.sAnimationSpeed;
    this._sRootId = '#cmts-box-' + this._sSystem + '-' + this._iObjId;

    //'A' Use global allow HTML param
    this._sTextAreaId = undefined == options.sTextAreaId || options.sTextAreaId == '' ? 'cmtTextAreaParent' : options.sTextAreaId;

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
                $this._error(eSubmit, true, $this._sDefaultErrMsg); // display error
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
                $this._error(eSubmit, true, jQuery.trim(oResponse.err).length ? oResponse.err : $this._sDefaultErrMsg); // display error
            else
                $('#cmt' + iCmtId + ' .cmt-body').bxdolcmtanim(
                    'hide',
                    $this._sAnimationEffect,
                    $this._iAnimationSpeed,
                    function() {
                        $(this).html(oResponse.text).bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
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
            $('#cmts-box-' + $this._sSystem + '-' + $this._iObjId + ' li#cmt' + iCmtId).bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
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
                $('#cmt' + iCmtId).bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
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

    var sBodyId = this._sRootId + ' #cmt' + iCmtId + ' .cmt-body';
    
    this._loading (oLink, true);

    if ($(sBodyId + ' > form').length) {
        $(sBodyId).bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
            $(this).html($this._oSavedTexts[iCmtId]).bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
        });
        return;
    }
    else
        this._oSavedTexts[iCmtId] = $(sBodyId).html();

    jQuery.post (
        this._sActionsUrl,
        oData,
        function (s) {
        	$this._loading (oLink, false);

        	if(s.substring(0,3) == 'err')
        		alert(s.substring(3));
            else
                $(sBodyId).bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                    $(this).html(s).bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
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
	this._getCmts(oLink, iCmtParentId, iStart, iPerView, function(sListId, sContent) {
		$this._replaceCmts($(oLink).parent('.cmt-more'), sContent);
	});
};

BxDolCmts.prototype.showReplacement = function(iCmtId)
{
    $('#cmt' + iCmtId + '-hidden').bxdolcmtanim('hide', this._sAnimationEffect, this._iAnimationSpeed, function(){
        $(this).next('#cmt' + iCmtId).bxdolcmtanim('show', this._sAnimationEffect, this._iAnimationSpeed);
    });
};

BxDolCmts.prototype.seeMore = function(oLink)
{
	$(oLink).next('span').show().prev('a').remove();
};

BxDolCmts.prototype.toggleReply = function(e, iCmtParentId)
{
	var sParentId = this._sRootId + ' #cmt' + iCmtParentId;
	var sReplyId = sParentId + ' .cmt-reply';

    if ($(sReplyId).length)
		$(sReplyId).bxdolcmtanim('toggle', this._sAnimationEffect, this._iAnimationSpeed);
    else {
        var $this = this;
        var oData = this._getDefaultActions();
        oData['action'] = 'FormGet';
        oData['CmtType'] = 'reply';
        oData['CmtParent'] = iCmtParentId;

        $this._loading (e, true);

        jQuery.post (
            this._sActionsUrl,
            oData,
            function (s) {
                $this._loading(e, false);
            	$(sParentId).append($(s).addClass('cmt-reply-expanded').css('display', 'none')).children('.cmt-reply').bxdolcmtanim('toggle', $this._sAnimationEffect, $this._iAnimationSpeed);
            }
        );
    }
};

BxDolCmts.prototype.toggleCmts = function(e, iCmtParentId)
{
	var sListId =  this._sRootId + ' #cmt' + iCmtParentId + ' > ul';

	if(!$(sListId + ' > li').length)
		this._getCmts(e, iCmtParentId);
	else
		$( sListId).bxdolcmtanim('toggle', this._sAnimationEffect, this._iAnimationSpeed);
};

// get comment replies via ajax request
BxDolCmts.prototype._getCmts = function (e, iCmtParentId, iStart, iPerView, onLoad)
{
    var $this = this;
    var oData = this._getDefaultActions();    
    oData['action'] = 'CmtsGet';
    oData['CmtParent'] = iCmtParentId;
    if(parseInt(iStart) >= 0)
        oData['CmtStart'] = iStart;
    if(parseInt(iPerView) >= 0)
        oData['CmtPerView'] = iPerView;

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
        		$this._appendCmts(sListId, s);
        }
    );
};

BxDolCmts.prototype._appendCmts = function(sIdTo, sContent)
{
	$(sIdTo).append($(sContent).hide()).children(':hidden').bxdolcmtanim('show', this._sAnimationEffect, this._iAnimationSpeed);
};

BxDolCmts.prototype._prependCmts = function(sIdTo, sContent)
{
	$(sIdTo).prepend($(sContent).hide()).children(':hidden').bxdolcmtanim('show', this._sAnimationEffect, this._iAnimationSpeed);
};

BxDolCmts.prototype._replaceCmts = function(oReplace, sContent)
{
	var $this = this;
	$(oReplace).bxdolcmtanim('hide', this._sAnimationEffect, this._iAnimationSpeed, function() {
		$(this).after($(sContent).hide()).nextAll(':hidden').bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
		$(this).remove();
	});
};


// get just posted 1 comment via ajax request
BxDolCmts.prototype._getCmt = function (f, iCmtParentId, iCmtId)
{
    var $this = this;
    var oData = this._getDefaultActions();
    oData['action'] = 'CmtGet';
    oData['Cmt'] = iCmtId;

    var sParentId =  this._sRootId + ' #cmt' + iCmtParentId;
    var sListId =  sParentId + ' > ul';

    var oList = $(sListId).get();
    this._loading (oList);

    jQuery.post (
        this._sActionsUrl,
        oData,
        function (s) {
            $this._loading (oList, false);

            if (iCmtParentId == 0) {
                //--- Some number of comments already loaded ---//
                if($(sListId + ' > li.cmt').length)
                    $(sListId + ' > li.cmt:last').after($(s).hide()).next('li.cmt:hidden').bxdolcmtanim('toggle', $this._sAnimationEffect, $this._iAnimationSpeed);
                //-- There is no comments at all ---//
                else
                    $(sListId + ' > li.cmt-no').bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {                    	
                    	$(this).after($(s).hide()).next('li').bxdolcmtanim('toggle', $this._sAnimationEffect, $this._iAnimationSpeed);
                    	$(this).remove();
                    });
            }
            else {
                $(sParentId + ' > .cmt-reply').bxdolcmtanim('hide', $this._sAnimationEffect, $this._iAnimationSpeed, function() {
                	var sRepliesId = sParentId + ' > .cmt-cont > .cmt-actions > a.cmt-comment-replies';
                	var sCounterId = sRepliesId + ' > span';
                	
                    //--- there was no comments and we added new
                    if(!$(sListId + ' > li.cmt').length && !$(sRepliesId).length)
                        $(sListId).hide().append(s).bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
                    //--- there is some number of comments but they are not loaded.
                    else if(!$(sListId + ' > li.cmt').length && parseInt($(sCounterId).html()) > 0) {
                    	$(sCounterId).html(parseInt($(sCounterId).html()) + 1);
                        $this._getCmts(f, iCmtParentId);
                    }
                    //--- there is some number of comments and they are loaded.
                    else if($(sListId + ' > li.cmt').length) {
                    	$(sCounterId).html(parseInt($(sCounterId).html()) + 1);

                        if($(sListId).is(':visible'))
                            $(sListId).append($(s).hide()).children('li.cmt:last-of-type').bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
                        else
                            $(sListId).append(s).bxdolcmtanim('show', $this._sAnimationEffect, $this._iAnimationSpeed);
                    }
                });
            }
        }
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
                	eval('var isValid = this.value.match(' + $this.oCmtElements[this.name]['reg'] + ');');
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
    if(bShow && !$(e).parent().find('b').length)
        $(e).parent().append(' <b>' + aDolLang['_sys_txt_cmt_loading'] + '</b>');
    else if (!bShow && $(e).parent().find('b').length)
    	$(e).parent().find('b').remove();
};

BxDolCmts.prototype._error = function(e, bShow, s) {
    if (bShow && !$(e).next('.cmt-err').length)
        $(e).after(' <b class="cmt-err">' + s + '</b>');
    else if (!bShow && $(e).next('.cmt-err').length)
        $(e).next('.cmt-err').remove();
};

BxDolCmts.prototype._confirm = function() {
    return confirm(this._sConfirmMsg);
};