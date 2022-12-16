/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */


function BxBaseModGroupsInvitePopup(oOptions) {
    this._sActionsUrl = oOptions.sActionUrl;
    this._sPopupId = oOptions.sPopupId == undefined ? {} : oOptions.sPopupId;
    this._sKey = oOptions.sKey == undefined ? {} : oOptions.sKey;
    this._sAcceptUrl = oOptions.sAcceptUrl == undefined ? {} : oOptions.sAcceptUrl;
    this._sDeclineUrl = oOptions.sDeclineUrl == undefined ? {} : oOptions.sDeclineUrl;
    this._iGroupProfileId = oOptions.iGroupProfileId == undefined ? {} : oOptions.iGroupProfileId;

    var $this = this;
    $(document).ready(function () {
        $this.init();
    });
}

BxBaseModGroupsInvitePopup.prototype.init = function () {
    var $this = this;
    $('#' + $this._sPopupId + ' .bx-invite-accept').click(function () {
        $this.onClickAccept();
    });
    $('#' + $this._sPopupId + ' .bx-invite-decline').click(function () {
        $this.onClickDecline();
    });

    $('#' + $this._sPopupId).dolPopup();
};

BxBaseModGroupsInvitePopup.prototype.onClickAccept = function () {
    var $this = this;
    $.post($this._sActionsUrl + 'ProcessInvite/' + $this._sKey + '/' + $this._iGroupProfileId + '/1/', function () {
        location.href = $this._sAcceptUrl
    });
};

BxBaseModGroupsInvitePopup.prototype.onClickDecline = function () {
    var $this = this;
    $.post($this._sActionsUrl + 'ProcessInvite/' + $this._sKey + '/' + $this._iGroupProfileId + '/0/', function () {
        location.href = $this._sDeclineUrl
    });
};
