/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
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
    	$('.bx-grid-table').each(function() {
    		var oTable = $(this);

	        // calculate width of all table cols, except the one which contains item's preview
	        var iCalcWidth = 0;
	        oTable.find('tbody tr:first-child td').each(function () {
	            if(!$(this).find('.bx-forum-grid-preview').length)
	            	iCalcWidth += $(this).outerWidth();
	        });

	        // set width for items' previews
	        var iInnerWidth = oTable.parent().innerWidth();
	        oTable.find('.bx-forum-grid-preview').each(function () {
	            var eWrapper = $(this).parent();
	            var w = iInnerWidth - iCalcWidth - parseInt(eWrapper.css('padding-left')) - parseInt(eWrapper.css('padding-right'));
	            $(this).width(w + 'px');
	        }); 
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
