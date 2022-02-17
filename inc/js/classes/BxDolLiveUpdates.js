/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

function BxDolLiveUpdates(oOptions)
{
    this._sActionsUrl = oOptions.sActionsUrl == undefined ? sUrlRoot + 'live_updates.php' : oOptions.sActionsUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oLiveUpdates' : oOptions.sObjName;
    this._iInterval = oOptions.iInterval == undefined ? 3000 : oOptions.iInterval;
    this._aSystemsActive = oOptions.aSystemsActive == undefined ? {} : oOptions.aSystemsActive;
    this._aSystemsTransient = oOptions.aSystemsTransient == undefined ? {} : oOptions.aSystemsTransient;
    this._bServerRequesting = oOptions.bServerRequesting == undefined ? false : oOptions.bServerRequesting;
    this._sHash = oOptions.sHash == undefined ? '' : oOptions.sHash;

    this._iIndex = 0;
    this._iHandler = 0;
    this._bBusy = false;

    this.init();
}

BxDolLiveUpdates.prototype.init = function() {
    var $this = this;

    if(this._iHandler)
        this.destroy();

    $(document).ready(function() {
        $this.perform(); // make first call straight away to load addons for menus

    	$this._iHandler = setInterval(function() {
            $this.perform();
    	}, $this._iInterval);
    });
};

BxDolLiveUpdates.prototype.add = function(oData) {
	if(!oData)
		return;

	if(oData.name != undefined && oData.value != undefined) {
		if(!this._aSystemsActive[oData.name])
			this._aSystemsActive[oData.name] = oData.value;
	
		if(!this._aSystemsTransient[oData.name])
			this._aSystemsTransient[oData.name] = 1;
	}

	if(oData.hash != undefined)
		this._sHash = oData.hash;
};

BxDolLiveUpdates.prototype.destroy = function() {
	if(this._iHandler)
		clearInterval(this._iHandler);
};

BxDolLiveUpdates.prototype.perform = function() {
	if(!this._bServerRequesting || this._bBusy || ('undefined' !== typeof(document.hidden) && document.hidden))
		return;

	var $this = this;
	var oDate = new Date();

	this._bBusy = true;

    $.post(
    	this._sActionsUrl,
        {
    		index: this._iIndex,
    		systems_active: this._aSystemsActive,
    		systems_transient: this._aSystemsTransient,
    		hash: this._sHash,
    		_t: oDate.getTime()
        },
        function(aData) {
        	$.each(aData, function(iIndex, oValue) {
        		if(oValue.system && $this._aSystemsActive[oValue.system] != undefined && oValue.data)
        			$this._aSystemsActive[oValue.system] = oValue.data.count_new;

        		if(oValue.method) {
        			var oFunc = function(oData) {
        				eval(oValue.method);
        			};
        			oFunc(oValue.data);
        		}
        	});

        	$this._bBusy = false;
        },
        'json'
    );

    this._iIndex += 1;
};

/** @} */
