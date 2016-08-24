/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
 *
 * @{
 */

function BxForumStudio(oOptions) {
	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oFormStudio' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
}

BxForumStudio.prototype.onChangeVisibleFor = function(oSelect) {
	$('#bx-form-element-visible_for_levels').bx_anim($(oSelect).val() == 'all' ? 'hide' : 'show', this.sAnimationEffect, this.iAnimationSpeed, function() {
		$('.bx-popup-applied:visible')._dolPopupSetPosition();
	});
};