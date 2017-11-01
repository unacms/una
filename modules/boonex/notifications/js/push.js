function BxNtfsPush(oOptions) {
	this._sActionsUri = oOptions.sActionUri;
    this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxNtfsPush' : oOptions.sObjName;
    this._iOwnerId = oOptions.iOwnerId == undefined ? 0 : oOptions.iOwnerId;
    this._sSiteName = oOptions.sSiteName == undefined ? '' : oOptions.sSiteName;
    this._iProfileId = oOptions.iProfileId == undefined ? 0 : oOptions.iProfileId;
    this._sAppId = oOptions.sAppId == undefined ? '' : oOptions.sAppId;
    this._sShortName = oOptions.sShortName == undefined ? '' : oOptions.sShortName;
    this._sSafariWebId = oOptions.sSafariWebId == undefined ? '' : oOptions.sSafariWebId;
    this._sNotificationUrl = oOptions.sNotificationUrl == undefined ? '' : oOptions.sNotificationUrl;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;

    var $this = this;
    $(document).ready(function() {
    	$this.init();
    });
}

BxNtfsPush.prototype.init = function() {
	var $this = this;
	var OneSignal = window.OneSignal || [];
	OneSignal.push(["init", {
		appId: $this._sAppId,
		autoRegister: true, /* Set to true to automatically prompt visitors */
		subdomainName: $this._sShortName, /* required only for http  sites */
		safari_web_id: $this._sSafariWebId,
		persistNotification: false,
		welcomeNotification: {disable:false},
		httpPermissionRequest: {enable: true},
		promptOptions: {
			/* These prompt options values configure both the HTTP prompt and the HTTP popup. */
			/* actionMessage limited to 90 characters */
			actionMessage: _t('_bx_ntfs_push_notification_request', $this._sSiteName), 
			/* acceptButtonText limited to 15 characters */
			acceptButtonText: _t('_bx_ntfs_push_notification_request_yes'),
			/* cancelButtonText limited to 15 characters */
			cancelButtonText: _t('_bx_ntfs_push_notification_request_no')
		}
	}]);

	OneSignal.push(function() {
		var isPushSupported = OneSignal.isPushNotificationsSupported();
		OneSignal.setDefaultNotificationUrl($this._sNotificationUrl);
		OneSignal.isPushNotificationsEnabled().then(function(isEnabled) {								
			OneSignal.sendTag('user', $this._iProfileId); // set tag for onesiganl, this tag will be used to send notification
			if(!isEnabled && isPushSupported)
				OneSignal.showHttpPermissionRequest();													
		});
	});
};
