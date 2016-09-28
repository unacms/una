/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
 *
 * @{
 */

function BxForumMain(oOptions) {
	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oFormMain' : oOptions.sObjName;
    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

	var $this = this;
	$(document).ready(function () {
		$this.init();
	});
}

BxForumMain.prototype.init = function() {
	var ePreviews = $('.bx-forum-grid-preview');
    if(!ePreviews.length)
        return;

    var oFunction = function () {

        // calculate width of all table rows, except the one which contains messages preview
        var iInnerWidth = $('.bx-grid-table').parent().innerWidth();
        var iCalcWidth = 0;
        $('.bx-grid-table tbody tr:first-child td').each(function () {
            if (!$(this).find('.bx-forum-grid-preview').length)
                iCalcWidth += $(this).outerWidth();
        });

        // set width for messages previews
        $('.bx-forum-grid-preview').each(function () {
            var eWrapper = $(this).parent();
            var w = iInnerWidth - iCalcWidth - parseInt(eWrapper.css('padding-left')) - parseInt(eWrapper.css('padding-right'));
            $(this).width(w + 'px');
        }); 
    };

    $(window).resize(function() {
    	oFunction();
    });

    BxDolGrid.prototype.onDataReloaded = function (isSkipSearchInput) {
    	oFunction();
    };

    oFunction();
};
