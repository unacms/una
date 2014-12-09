/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */
function BxDolStudioLauncher(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioLauncher' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'fast' : oOptions.iAnimationSpeed;
    this.bInit = oOptions.bInit == undefined ? true : oOptions.bInit;

	//--- Jitter Settings ---//
    this.aJitterConf = {
		item: '.bx-std-widget-icon',
		elements: '.bx-std-widget-actions,.bx-std-widget-icon-jitter,.bx-std-widget-caption-jitter'
    };

    this.aSortingConf = {
    	parent: '.bx-std-widgets',
		item: '.bx-std-widget',
		placeholder: 'bx-std-widget bx-std-widget-empty'
    };

    if(this.bInit)
    	this.init();
}

BxDolStudioLauncher.prototype.init = function() {
    var $this = this;

    $(window).bind('resize', function(e) {
		$this.resize();
	});

    $(document).ready(function() {
    	//--- Enable Sorting for Page Edit mode ---//
    	$($this.aSortingConf.parent).sortable({
    		disabled: true,
            handle: '.bx-std-widget-icon-jitter > img',
    		items: $this.aSortingConf.item,
    		placeholder: $this.aSortingConf.placeholder,
    		start: function(oEvent, oUi) {
                oUi.item.addClass('bx-std-widget-dragging');
                $this.resize();
    		},
    		change: function(oEvent, oUi) {
    			$this.resize();
    		},
    		stop: function(oEvent, oUi) {
                oUi.item.removeClass('bx-std-widget-dragging');
                $this.resize();
    			$this.reorder(oUi.item);
    		}
    	});

    	//--- Enable autoresizing for correct alignment ---//
    	$this.resize();

    	//--- Check for Favorite mode.
    	if($this._isFavorites())
    		$('.bx-menu-tab-favorite').addClass('bx-menu-tab-active');
    });
};

BxDolStudioLauncher.prototype.updateCache = function() {
	var oDate = new Date();

	$.get(
		this.sActionsUrl,
		{
			action: 'launcher-update-cache',
			_t:oDate.getTime()
		}
	);
};

BxDolStudioLauncher.prototype.resize = function() {
	$('.bx-std-widgets').each(function() {
		var oWidgets = $(this);
		var oWidget = oWidgets.find('.bx-std-widget').removeClass('bx-std-widget-nl').filter(':visible:not(.bx-std-widget-dragging)');

		var iWidgetsTotal = oWidget.length;
		var iWidgetsPerLine = oWidgets.width()/oWidget.innerWidth()>>0;

		if(iWidgetsTotal <= iWidgetsPerLine)
			return;

		for(var i = 1; i * iWidgetsPerLine <= iWidgetsTotal; i++)
			oWidget.eq(i * iWidgetsPerLine).addClass('bx-std-widget-nl');
	});
};

BxDolStudioLauncher.prototype.reorder = function(oDraggable) {
	var oDate = new Date();

	$.post(
		this.sActionsUrl + '?' + oDraggable.parent('.bx-std-widgets').sortable('serialize', {key: 'items[]'}),
		{
			action: 'launcher-reorder',
			page: this.sScrollCurrent,
			_t:oDate.getTime()
		},
		function(oData) {
			if(oData.code != 0) {
				alert(oData.message);
				return;
			}
		},
		'json'
	);

	return true;
};

BxDolStudioLauncher.prototype.enableJitter = function() {
	this._disableFavorites();
	$('.bx-menu-tab-favorite').removeClass('bx-menu-tab-active');

	$(this.aJitterConf.elements).fadeIn('fast');
    $(this.aJitterConf.item).removeClass('bx-std-widget-icon-trans');
	$(this.aSortingConf.parent).sortable('option', 'disabled', false);
};

BxDolStudioLauncher.prototype.disableJitter = function() {
    $(this.aJitterConf.elements).fadeOut('fast');	
    $(this.aJitterConf.item).addClass('bx-std-widget-icon-trans');
	$(this.aSortingConf.parent).sortable('option', 'disabled', true);
};

BxDolStudioLauncher.prototype.enableFavorites = function() {
	this.disableJitter();
	$('.bx-menu-tab-edit').removeClass('bx-menu-tab-active');

	$.cookie('bx_studio_bookmark', '1');

	this._enableFavorites();
};

BxDolStudioLauncher.prototype.disableFavorites = function() {
	$.cookie('bx_studio_bookmark', '0');

	this._disableFavorites();
};

BxDolStudioLauncher.prototype._isFavorites = function() {
	return parseInt($.cookie('bx_studio_bookmark')) == 1;
};

BxDolStudioLauncher.prototype._enableFavorites = function() {
	var $this = this;

	$('.bx-std-widget:not(.bx-std-widget-icon-bookmarked)').bx_anim('hide', this.sAnimationEffect, this.iAnimationSpeed, function() {
		$this.resize();
	});
};

BxDolStudioLauncher.prototype._disableFavorites = function() {
	var $this = this;

	$('.bx-std-widget:not(.bx-std-widget-icon-bookmarked)').bx_anim('show', this.sAnimationEffect, this.iAnimationSpeed, function() {
		$this.resize();
	});
};
/** @} */
