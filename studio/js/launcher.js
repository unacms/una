/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
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

    	//--- Check for Featured mode.
    	if($this._isFeatured())
            $('.bx-menu-tab-featured').addClass('bx-menu-tab-active');
    });
};

BxDolStudioLauncher.prototype.browser = function(oLink) {
    var oDate = new Date();

    $.get(
        this.sActionsUrl,
        {
            action: 'launcher-browser',
            _t: oDate.getTime()
        },
        function(oData) {
            processJsonData(oData);
        },
        'json'
    );
    return true;
};

BxDolStudioLauncher.prototype.browserChangeType = function(oLink, sType) {
    var sMenuActive = 'bx-menu-tab-active';
    var sContentActive = 'bx-std-lbw-active';

    $('.bx-std-launcher-browser .bx-std-lb-menu .bx-std-pmen-item.' + sMenuActive).removeClass(sMenuActive).siblings('.bx-std-pmen-item-' + sType).addClass(sMenuActive);
    $('.bx-std-launcher-browser .bx-std-lb-content .bx-std-lb-widgets.' + sContentActive).removeClass(sContentActive).siblings('.bx-std-lbw-' + sType).addClass(sContentActive);
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
                bx_alert(oData.message);
                return;
            }
        },
        'json'
    );

    return true;
};

BxDolStudioLauncher.prototype.featured = function(sPageName, oLink) {
    var $this = this;
    var oDate = new Date();

    $.get(
        this.sActionsUrl,
        {
            action: 'page-featured',
            page: sPageName,
            _t: oDate.getTime()
        },
        function(oData) {
            $('.bx-popup-applied:visible').dolPopupHide();

            if(oData.code != 0) {
                bx_alert(oData.message);
                return;
            }

            var oSettings = $(oLink).parents('.bx-mod-popup-settings:first');
            if(oSettings.length > 0 && oData.widget_id != undefined && oData.widget.length > 0) {
                $('#bx-std-widget-' + oData.widget_id).replaceWith(oData.widget);
                if($this.bInit)
                    oBxDolStudioLauncher.enableJitter();
            }
        },
        'json'
    );
    return true;
};

BxDolStudioLauncher.prototype.bookmark = function(sPageName, oLink) {
    var oDate = new Date();

    $.get(
        this.sActionsUrl,
        {
            action: 'page-bookmark',
            page: sPageName,
            _t: oDate.getTime()
        },
        function(oData) {
            $('.bx-popup-applied:visible').dolPopupHide();

            if(oData.code != 0) {
                bx_alert(oData.message);
                return;
            }
        },
        'json'
    );

    return true;
};

BxDolStudioLauncher.prototype.rearrange = function(iWidgetId, oSelect) {
    var oDate = new Date();
    var oSelect = $(oSelect);
    var oPopup = oSelect.parents('.bx-popup');

    bx_loading(oPopup, true);

    $.get(
        this.sActionsUrl,
        {
            action: 'widget-rearrange',
            widget_id: iWidgetId,
            type: oSelect.val(),
            _t: oDate.getTime()
        },
        function(oData) {
            bx_loading(oPopup, false);

            processJsonData(oData);
        },
        'json'
    );

    return true;
};

BxDolStudioLauncher.prototype.enableJitter = function() {
    this._disableFeatured();
    $('.bx-menu-tab-featured').removeClass('bx-menu-tab-active');

    $(this.aJitterConf.elements).fadeIn('fast');
    $(this.aJitterConf.item).removeClass('bx-std-widget-icon-trans');
    $(this.aSortingConf.parent).sortable('option', 'disabled', false);
};

BxDolStudioLauncher.prototype.disableJitter = function() {
    $(this.aJitterConf.elements).fadeOut('fast');	
    $(this.aJitterConf.item).addClass('bx-std-widget-icon-trans');
	$(this.aSortingConf.parent).sortable('option', 'disabled', true);
};

BxDolStudioLauncher.prototype.enableFeatured = function() {
    this.disableJitter();
    $('.bx-menu-tab-edit').removeClass('bx-menu-tab-active');

    $.cookie('bx_studio_featured', '1');

    this._enableFeatured();
};

BxDolStudioLauncher.prototype.disableFeatured = function() {
    $.cookie('bx_studio_featured', '0');

    this._disableFeatured();
};

BxDolStudioLauncher.prototype._isFeatured = function() {
    return parseInt($.cookie('bx_studio_featured')) == 1;
};

BxDolStudioLauncher.prototype._enableFeatured = function() {
    var $this = this;

    $('.bx-std-widget:not(.bx-std-widget-icon-featured)').bx_anim('hide', this.sAnimationEffect, this.iAnimationSpeed, function() {
        $this.resize();
    });
};

BxDolStudioLauncher.prototype._disableFeatured = function() {
    var $this = this;

    $('.bx-std-widget:not(.bx-std-widget-icon-featured)').bx_anim('show', this.sAnimationEffect, this.iAnimationSpeed, function() {
        $this.resize();
    });
};
/** @} */
