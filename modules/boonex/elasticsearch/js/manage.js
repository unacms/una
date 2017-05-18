/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    ElasticSearch ElasticSearch
 * @ingroup     UnaModules
 *
 * @{
 */

function BxElsManage(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxElsManage' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxElsManage.prototype.onIndex = function(oData) {
	if(oData.notification && oData.notification.length > 0)
		this.showNotification(oData.notification);
};

BxElsManage.prototype.showNotification = function(sContent) {
	$(sContent).appendTo('body').dolPopupInline({
		removeOnClose: true
	});   
};

/** @} */
