/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

function BxDolReport(options)
{
	this._sObjName = undefined == options.sObjName ? 'oReport' : options.sObjName; // javascript object name, to run current object instance from onTimer
	this._sSystem = options.sSystem; // current comment system
	this._iAuthorId = options.iAuthorId; // this comment's author ID.
    this._iObjId = options.iObjId; // this object id comments

    this._sActionsUri = 'report.php';
    this._sActionsUrl = options.sRootUrl + this._sActionsUri; // actions url address

    this._sAnimationEffect = 'fade';
    this._iAnimationSpeed = 'slow';
    this._sSP = undefined == options.sStylePrefix ? 'bx-report' : options.sStylePrefix;
    this._aHtmlIds = options.aHtmlIds;

    this._oParent = null;
}

BxDolReport.prototype.toggleByPopup = function(oLink) {
	var $this = this;
    var oData = this._getDefaultParams();
    oData['action'] = 'GetReportedBy';

	$(oLink).dolPopupAjax({
		id: this._aHtmlIds['by_popup'], 
		url: bx_append_url_params(this._sActionsUri, oData)
	});
};

BxDolReport.prototype.report = function(oLink) {
	var $this = this;
    var oData = this._getDefaultParams();
    oData['action'] = 'Report';

    this._oParent = oLink;

    $.get(
    	this._sActionsUrl,
    	oData,
    	function(oData) {
    		$this.processJson(oData, oLink);
    	},
    	'json'
    );
};

BxDolReport.prototype.onReport = function(oData, oElement)
{
	if(oData && oData.msg != undefined)
        alert(oData.msg);

	if(oData && oData.code != 0)
        return;

	if(oData && oData.label_icon)
		$(oElement).find('.sys-icon').attr('class', 'sys-icon ' + oData.label_icon);

	if(oData && oData.label_title) {
		$(oElement).attr('title', oData.label_title);
		$(oElement).find('span').html(oData.label_title);
	}

	if(oData && oData.disabled)
		$(oElement).removeAttr('onclick').addClass($(oElement).hasClass('bx-btn') ? 'bx-btn-disabled' : 'bx-report-disabled');

    var oCounter = this._getCounter(oElement);
    if(oCounter && oCounter.length > 0) {
    	oCounter.html(oData.countf);

    	oCounter.parents('.' + this._sSP + '-counter-holder:first').bx_anim(oData.count > 0 ? 'show' : 'hide');
    }
};

BxDolReport.prototype.processJson = function(oData, oElement) {
	oElement = oElement != undefined ? oElement : this._oParent;

    //--- Show Message
    if(oData && oData.msg != undefined)
        alert(oData.msg);
    if(oData && oData.message != undefined)
        alert(oData.message);

    //--- Show Popup
    if(oData && oData.popup != undefined) {
    	$('#' + oData.popup_id).remove();

    	$(oData.popup).hide().prependTo('body').dolPopup({
    		pointer: {
    			el: oElement
    		},
            fog: {
				color: '#fff',
				opacity: .7
            }
        });
    }

    //--- Evaluate JS code
    if (oData && oData.eval != undefined)
        eval(oData.eval);
};

BxDolReport.prototype._getButtons = function(oElement) {
	if($(oElement).hasClass(this._sSP))
		return $(oElement).find('.' + this._sSP + '-button');
	else
		return $(oElement).parents('.' + this._sSP + ':first').find('.' + this._sSP + '-button');
};

BxDolReport.prototype._getCounter = function(oElement) {
	if($(oElement).hasClass(this._sSP))
		return $(oElement).find('.' + this._sSP + '-counter');
	else 
		return $(oElement).parents('.' + this._sSP + ':first').find('.' + this._sSP + '-counter');
};

BxDolReport.prototype._loadingInButton = function(e, bShow) {
	if($(e).length)
		bx_loading_btn($(e), bShow);
	else
		bx_loading($('body'), bShow);	
};

BxDolReport.prototype._getDefaultParams = function() {
	var oDate = new Date();
    return {
        sys: this._sSystem,
        object_id: this._iObjId,
        _t: oDate.getTime()
    };
};

/** @} */
