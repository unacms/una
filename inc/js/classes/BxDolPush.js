function BxDolPush(oOptions) {
    this._sObjName = oOptions.sObjName == undefined ? 'oBxDolPush' : oOptions.sObjName;
    this._sSiteName = oOptions.sSiteName == undefined ? '' : oOptions.sSiteName;
    this._iProfileId = oOptions.iProfileId == undefined ? 0 : oOptions.iProfileId;
    this._sAppId = oOptions.sAppId == undefined ? '' : oOptions.sAppId;
    this._sShortName = oOptions.sShortName == undefined ? '' : oOptions.sShortName;
    this._sSafariWebId = oOptions.sSafariWebId == undefined ? '' : oOptions.sSafariWebId;
    this._sSubfolder = oOptions.sSubfolder == undefined ? '/plugins_public/onesignal/' : oOptions.sSubfolder;
    this._sNotificationUrl = oOptions.sNotificationUrl == undefined ? '' : oOptions.sNotificationUrl;

    this._sTxtNotificationRequest = oOptions.sTxtNotificationRequest == undefined ? 'Would you like to get notifications' : oOptions.sTxtNotificationRequest;
    this._sTxtNotificationRequestYes = oOptions.sTxtNotificationRequestYes == undefined ? 'Yes' : oOptions.sTxtNotificationRequestYes;
    this._sTxtNotificationRequestNo = oOptions.sTxtNotificationRequestNo == undefined ? 'No' : oOptions.sTxtNotificationRequestNo;

    var $this = this;
    $(document).ready(function() {
    	$this.init();
    });
}

BxDolPush.prototype.init = function() {
	var $this = this;
	window.OneSignal = window.OneSignal || [];

	OneSignal.push(function() {
		OneSignal.SERVICE_WORKER_UPDATER_PATH = "OneSignalSDKUpdaterWorker.js.php";
    	OneSignal.SERVICE_WORKER_PATH = "OneSignalSDKWorker.js.php";
    	OneSignal.SERVICE_WORKER_PARAM = { scope: '/' }; /* This registers the workers at the root scope, which is allowed by the HTTP header "Service-Worker-Allowed: /" */
	});

	OneSignal.push(["init", {
		appId: $this._sAppId,
		autoRegister: true, /* Set to true to automatically prompt visitors */
		subdomainName: $this._sShortName, /* required only for http  sites */
		safari_web_id: $this._sSafariWebId,
		path: $this._sSubfolder, /* A trailing slash is required */
		persistNotification: false,
		welcomeNotification: {
			disable:false
		},
		httpPermissionRequest: {
			enable: true
		},
		promptOptions: {
			/* These prompt options values configure both the HTTP prompt and the HTTP popup. */
			/* actionMessage limited to 90 characters */
			actionMessage: $this._sTxtNotificationRequest, 
			/* acceptButtonText limited to 15 characters */
			acceptButtonText: $this._sTxtNotificationRequestYes,
			/* cancelButtonText limited to 15 characters */
			cancelButtonText: $this._sTxtNotificationRequestNo
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

    if (navigator.userAgent.indexOf('gonative') > -1) {
        var data = {user: $this._iProfileId};
        var json = JSON.stringify(data);
        $(document).ready(function () {
            window.location.href='gonative://registration/send?customData=' + encodeURIComponent(json);        
        });
    }
};
