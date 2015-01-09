/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioPage(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioPage' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDolStudioPage.prototype.processJson = function (oData) {
	bx_loading('bx-std-page-columns', false);

    //--- Show Message
    if(oData && oData.msg != undefined)
        alert(oData.msg);
    if(oData && oData.message != undefined)
        alert(oData.message);

    //--- Show Popup
    if(oData && oData.popup != undefined) {
    	var oPopup = $(oData.popup).hide(); 

    	$('#' + oPopup.attr('id')).remove();
        oPopup.prependTo('body').dolPopup({
            fog: {
				color: '#fff',
				opacity: .7
            },
            closeOnOuterClick: false
        });
    }

    //--- Evaluate JS code
    if (oData && oData.eval != undefined)
        eval(oData.eval);
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
				onShow: function() {
					var oPopup = $(this);
					var oPopupRss = oPopup.find('.RSSAggrCont');
					if(!oPopupRss.find('.bx-loading-ajax').length)
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
				}
			});
			break;
	}

	if($(sId).html().length > 0)
		$(sId).dolPopup(oPopupOptions);
};

BxDolStudioPage.prototype.showMessage = function(oData) {
	if(oData.message)
		alert(oData.message);

	if(oData.on_result)
		eval(oData.on_result);

	if(oData.redirect)
		window.location.href = oData.redirect;
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
				alert(oData.message);
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
