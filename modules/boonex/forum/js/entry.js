/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
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