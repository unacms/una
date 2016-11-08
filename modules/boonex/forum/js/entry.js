/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

function BxForumEntry(oOptions) {
	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oFormEntry' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxForumEntry.prototype.updateStatus = function(oButton, sAction, iId) {
	this._performAction(oButton, sAction, iId);
};

BxForumEntry.prototype._performAction = function(oButton, sAction, iId) {
    var $this = this;
    var oDate = new Date();

    var oParams = {
    	action: sAction,
    	id: iId,
    	_t: oDate.getTime()
    };

    $.get(
        this._sActionsUrl + 'update_status/',
        oParams,
        function(oData){
        	processJsonData(oData);
        },
        'json'
    );
};
