/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

function BxDolLiveUpdates(oOptions)
{
	this._sActionsUrl = oOptions.sActionsUrl == undefined ? sUrlRoot + 'live_updates.php' : oOptions.sActionsUrl;
	this._sObjName = oOptions.sObjName == undefined ? 'oLiveUpdates' : oOptions.sObjName;
	this._iInterval = oOptions.iInterval == undefined ? 3000 : oOptions.iInterval;
	this._bServerRequesting = oOptions.bServerRequesting == undefined ? {} : oOptions.bServerRequesting;

	this._iHandler = 0;

	this.init();
}

BxDolLiveUpdates.prototype.init = function() {
	var $this = this;

	if(this._iHandler)
		this.destroy();

    $(document).ready(function() {
    	$this._iHandler = setInterval(function() {
    		$this.perform();
    	}, $this._iInterval);
    });
};

BxDolLiveUpdates.prototype.destroy = function() {
	if(this._iHandler)
		clearInterval(this._iHandler);
};

BxDolLiveUpdates.prototype.perform = function() {
	if(!this._bServerRequesting)
		return;

	var $this = this;
	var oDate = new Date();

    $.get(
    	this._sActionsUrl,
        {
    		_t: oDate.getTime()
        },
        function(aData) {
        	$.each(aData, function(iIndex, oValue) {
        		if(oValue.method) {
        			var oFunc = function(oData) {
        				eval(oValue.method);
        			};
        			oFunc(oValue.data);
        		}
        	});
        },
        'json'
    );
};
