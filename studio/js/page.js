/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */
function BxDolStudioPage(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioPage' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this.oPopupOptions = {
		fog: {
			color: '#fff',
			opacity: .7
	    },
	    closeOnOuterClick: false
    };
}

BxDolStudioPage.prototype.processJson = function (oData) {
	bx_loading('bx-std-page-columns', false);

	processJsonData(oData);
};

BxDolStudioPage.prototype.togglePopup = function(sName, oLink) {
	var $this = this;

	var sId = '#bx-std-pcap-menu-popup-' + sName;
	if($(sId + ':visible').length > 0) {
		$(sId).dolPopupHide();
		return;
	}

	$(oLink).parent().addClass('bx-menu-tab-active');

	var oPopupOptions = {
		pointer:{
			el:$(oLink)
		},
		onHide: function() {
			$(oLink).parent().removeClass('bx-menu-tab-active');
		}
	};

	switch(sName) {
		case 'help':
			oPopupOptions = $.extend({}, oPopupOptions, {
				onBeforeShow: function() {
					var oPopup = $(sId);
					var oPopupRss = oPopup.find('.RSSAggrCont');
					if(oPopupRss.contents().length)
						return;

					oPopupRss.dolRSSFeed({
						onError: function() {
							oPopupRss.html(_t('_adm_txt_show_help_content_empty'));
							oPopup._dolPopupSetPosition(oPopupOptions);
						},
						onShow: function() {
							oPopup._dolPopupSetPosition(oPopupOptions);
						}
					});

					oPopup._dolPopupSetPosition(oPopupOptions);
				}
			});
			break;
	}

	if($(sId).html().length > 0)
		$(sId).dolPopup(oPopupOptions);
};

BxDolStudioPage.prototype.bookmark = function(sPageName, oLink) {
	var oDate = new Date();

	$.get(
		this.sActionsUrl,
		{
			action: 'page-bookmark',
			page: sPageName,
			_t:oDate.getTime()
		},
		function(oData) {
			if(oData.code != 0) {
				bx_alert(oData.message);
				return;
			}

			$(oLink).attr('title', oData.title);
			$(oLink).find('img').attr('src', oData.icon).attr('title', oData.title);
		},
		'json'
	);
	return true;
};
/** @} */
