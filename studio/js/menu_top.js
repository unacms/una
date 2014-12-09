/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioMenuTop(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioMenuTop' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'slide' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxDolStudioMenuTop.prototype.clickEdit = function(oItem) {
	var oParent = $(oItem).parent();
	if(oParent.hasClass('bx-menu-tab-active')) {
		oParent.removeClass('bx-menu-tab-active');
		oBxDolStudioLauncher.disableJitter();
	}
	else {
		oParent.addClass('bx-menu-tab-active');
		oBxDolStudioLauncher.enableJitter();
	}
};

BxDolStudioMenuTop.prototype.clickFavorite = function(oItem) {
	var oParent = $(oItem).parent();
	if(oParent.hasClass('bx-menu-tab-active')) {
		oParent.removeClass('bx-menu-tab-active');
		oBxDolStudioLauncher.disableFavorites();
	}
	else {
		oParent.addClass('bx-menu-tab-active');
		oBxDolStudioLauncher.enableFavorites();
	}
};

BxDolStudioMenuTop.prototype.clickLogout = function(oItem) {
	$(oItem).parent().toggleClass('bx-menu-tab-active');
};
/** @} */
