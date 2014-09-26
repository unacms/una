/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
function BxDolStudioStore(oOptions) {
	this.sActionsUrl = oOptions.sActionUrl;
    this.sObjName = oOptions.sObjName == undefined ? 'oBxDolStudioStore' : oOptions.sObjName;
    this.sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this.iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;

    this.sIdPageContent = 'bx-std-pc-content';
    this.sIdPopupProduct = 'bx-std-str-popup-product';
    this.sIdPopupFiles = 'bx-std-str-popup-files';
    this.sIdPopupFile = 'bx-std-str-popup-file';
}

BxDolStudioStore.prototype.addToCart = function(sVendor, iProduct, oButton) {
	var oDate = new Date();
	var $this = this;
	bx_loading(this.sIdPageContent, true);

	$.get(
		this.sActionsUrl,
		{
			str_action: 'add-to-cart',
			str_vendor: sVendor,
			str_item: iProduct,
			_t:oDate.getTime()
		},
		function(oData) {
			bx_loading($this.sIdPageContent, false);

			$this.showPopup($this.sIdPopupFile, oData.message, oButton);

			if(parseInt(oData.code) == 0) {
				var oCounter = $('#bx-std-pmi-checkout .bx-std-pmen-item-counter span');
				oCounter.html(parseInt(oCounter.html()) + 1);

				if(parseInt(oCounter.html()) > 0)
					oCounter.parent('.bx-std-pmen-item-counter').show();
			}
		},
		'json'
	);
};

BxDolStudioStore.prototype.deleteFromCart = function(sVendor, iProduct, oButton) {
	var oDate = new Date();
	var $this = this;
	bx_loading(this.sIdPageContent, true);

	$.get(
		this.sActionsUrl,
		{
			str_action: 'delete-from-cart',
			str_vendor: sVendor,
			str_item: iProduct,
			_t:oDate.getTime()
		},
		function(oData) {
			bx_loading($this.sIdPageContent, false);

			if(oData.message.length > 0)
				$this.showPopup($this.sIdPopupFile, oData.message, iProduct != 0 ? oButton : null);

			var iCode = parseInt(oData.code);
			var oCounter = $('#bx-std-pmi-checkout .bx-std-pmen-item-counter span');
			if(iCode == 0 && iProduct != 0) {
				var oProduct = $('#bx-std-product-' + iProduct);
				if(oProduct.siblings('.bx-std-product').length == 0)
					oProduct.parents('.bx-std-block:first').bx_anim('hide', 'fade', 'fast', function() {
						$(this).remove();
					});
				else
					oProduct.bx_anim('hide', 'fade', 'fast', function() {
						$(this).remove();
					});

				oCounter.html(parseInt(oCounter.html()) - 1);
			}
			else if(iCode == 0 && iProduct == 0) {
				var oBlock = $(oButton).parents('.bx-std-block:first');
				oCounter.html(parseInt(oCounter.html()) - oBlock.find('.bx-std-product').length);

				oBlock.bx_anim('hide', 'fade', 'fast', function() {
					$(this).remove();
				});
			}

			if(parseInt(oCounter.html()) <= 0)
				oCounter.parent('.bx-std-pmen-item-counter').hide();
		},
		'json'
	);
};

BxDolStudioStore.prototype.deleteAllFromCart = function(sVendor, oButton) {
	this.deleteFromCart(sVendor, 0, oButton);
};

BxDolStudioStore.prototype.checkoutCart = function(sVendor, oButton) {
	var oDate = new Date();
	var $this = this;
	bx_loading(this.sIdPageContent, true);

	$.get(
		this.sActionsUrl,
		{
			str_action: 'checkout-cart',
			str_vendor: sVendor,
			_t:oDate.getTime()
		},
		function(oData) {
			bx_loading($this.sIdPageContent, false);

			if(oData.message.length > 0)
				$this.showPopup($this.sIdPopupFile, oData.message, oButton);

			if(parseInt(oData.code) == 0 && oData.redirect.length > 0)
				document.location=oData.redirect;
		},
		'json'
	);
};

BxDolStudioStore.prototype.getFile = function(iFileId, oButton) {
	this._getFile('get-file', iFileId, '', oButton);
};

BxDolStudioStore.prototype.getUpdate = function(sModuleName, oButton) {
	this._getFile('get-update', sModuleName, '', oButton);
};

BxDolStudioStore.prototype.getUpdateAndInstall = function(sModuleName, oButton) {
	var $this = this;

	var onResult = function(oData, oButton) {
		if(oData.code != 0) {
			$this._onGetFile(oData, oButton);
			return;
		}

		bx_loading($this.sIdPageContent, false);

		$(oButton).parents('.bx-std-product:first').hide();
	};

	this._getFile('get-update-and-install', sModuleName, onResult, oButton);
};

BxDolStudioStore.prototype._getFile = function(sAction, mixedId, onResult, oButton) {
	var oDate = new Date();
	var $this = this;

	bx_loading(this.sIdPageContent, true);
	$(oButton).addClass('bx-btn-disabled');

	$.get(
		this.sActionsUrl,
		{
			str_action: sAction,
			str_id: mixedId,
			_t:oDate.getTime()
		},
		function(oData) {
			if(typeof onResult == 'function')
				onResult(oData, oButton);
			else
				$this._onGetFile(oData, oButton);
		},
		'json'
	);
};

BxDolStudioStore.prototype._onGetFile = function(oData, oButton) {
	bx_loading(this.sIdPageContent, false);
	if(oData.code != 0)
		$(oButton).removeClass('bx-btn-disabled');

	if(oData.message)
		this.showPopup(this.sIdPopupFile, oData.message, oButton);
};

BxDolStudioStore.prototype.info = function(sModuleName, oLink) {
	var oDate = new Date();
	var $this = this;
	bx_loading(this.sIdPageContent, true);

	$.get(
		this.sActionsUrl,
		{
			str_action: 'get-product',
			str_id: sModuleName,
			_t:oDate.getTime()
		},
		function(oData) {
			bx_loading($this.sIdPageContent, false);

			var sId = $this.sIdPopupProduct;
			if(oData.code == 0 && oData.popup.length > 0) {
		        $('#' + sId).remove();
				$(oData.popup).appendTo('body');
				$('#' + sId).dolPopup({
					onShow: function() {
						$this.initScreenshots(oData.screenshots);
					}
				});
			}
			else
				$this.showPopup(sId, oData.message, oLink);
		},
		'json'
	);
};

BxDolStudioStore.prototype.initScreenshots = function(iCount) {
	var iWidth = 202;
	var iPadding = 20;
	var iWidthOuter = iWidth + iPadding;
	var bBusy = false;

	$(".bx-std-pv-screenshots a[rel=group]").fancybox({
		transitionIn: 'elastic',
		transitionOut: 'elastic',
		speedIn: 600,
		speedOut: 200
	});
	if(iCount <= 4)
		return;

	$(".bx-std-pvs-left").bind('click', function() {
		if(bBusy || parseInt($(".bx-std-pvs-cnt").css('left')) >= 0)
			return;

		bBusy = true;
		$(".bx-std-pvs-cnt").animate({left: '+=' + iWidthOuter}, 500, function() {
			bBusy = false;
		});
	});
	$(".bx-std-pvs-right").bind('click', function() {
		if(bBusy || parseInt($(".bx-std-pvs-cnt").css('left')) <= 460 - $(".bx-std-pvs-cnt").width())
			return;

		bBusy = true;
		$(".bx-std-pvs-cnt").animate({left: '-=' + iWidthOuter}, 500, function() {
			bBusy = false;
		});
	});

	$(".bx-std-pv-screenshots").hover(function() {
		$(".bx-std-pvs-left, .bx-std-pvs-right").bx_anim('show', 'fade', 'fast');
	}, function() {
		$(".bx-std-pvs-left, .bx-std-pvs-right").bx_anim('hide', 'fade', 'fast');;
	});
};

BxDolStudioStore.prototype.install = function(sValue, oInput) {
	var $this = this;
	var onSuccess = function() {
		$(oInput).parent('.bx-std-pc-buttons').hide(0, function() {
			$(this).siblings(':hidden').show(0);
		});
	};

	return this.perform('install', sValue, onSuccess);
};

BxDolStudioStore.prototype.update = function(sValue, oInput) {
	var $this = this;
	var onSuccess = function() {
		$(oInput).parents('.bx-std-product:first').hide();
	};

	return this.perform('update', sValue, onSuccess);
};

BxDolStudioStore.prototype.remove = function(sValue, oInput) {
	var onSuccess = function() {
		$(oInput).parents('.bx-std-product:first').hide();
	};

    return this.perform('delete', sValue, onSuccess);
};

BxDolStudioStore.prototype.perform = function(sType, sValue, onSuccess) {
	var oDate = new Date();
	var $this = this;

	if(!sValue)
        return false;

	bx_loading(this.sIdPageContent, true);

    $.post(
    	this.sActionsUrl,
    	{
    		str_action: sType,
    		str_value: sValue,
    		_t:oDate.getTime()
    	},
    	function (oData) {
    		bx_loading($this.sIdPageContent, false);

    		if(oData.message.length > 0)
    			$this.showPopup('bx-std-str-popup-' + sType, oData.message);

    		if(oData.code == 0 && typeof onSuccess == 'function')
    			onSuccess();
    	},
    	'json'
    );
};

/**
 * Is needed if AJAX is used to change (reload) pages. 
 */
BxDolStudioStore.prototype.changePage = function(sType) {
	var oDate = new Date();
	var $this = this;

	$.get(
		this.sActionsUrl,
		{
			str_action: 'get-products-by-type',
			str_value: sType,
			_t:oDate.getTime()
		},
		function(oData) {
			if(oData.code != 0) {
				$this.showPopup('bx-std-str-popup-browsing', oData.message);
				return;
			}

			$('#bx-std-pc-menu > .bx-std-pmi-active').removeClass('bx-std-pmi-active');
			$('#bx-std-pmi-' + sType).addClass('bx-std-pmi-active');

			$('#' + $this.sIdPageContent).bx_anim('hide', $this.sAnimationEffect, $this.iAnimationSpeed, function() {
				$(this).html(oData.content).bx_anim('show', $this.sAnimationEffect, $this.iAnimationSpeed);
			});
		},
		'json'
	);

	return true;
};

BxDolStudioStore.prototype.changePagePaginate = function(oButton, sType, iStart, iPerPage) {
	var oDate = new Date();
	var $this = this;

	$.get(
		this.sActionsUrl,
		{
			str_action: 'get-products-by-page',
			str_type: sType,
			str_start: iStart,
			str_per_page: iPerPage,
			_t:oDate.getTime()
		},
		function(oData) {
			if(oData.code != 0) {
				$this.showPopup('bx-std-str-popup-browsing', oData.message);
				return;
			}

			$(oButton).parents('.bx-std-block-content:first').bx_anim('hide', $this.sAnimationEffect, $this.iAnimationSpeed, function() {
				$(this).html(oData.content).bx_anim('show', $this.sAnimationEffect, $this.iAnimationSpeed);
			});
		},
		'json'
	);

	return true;
};

BxDolStudioStore.prototype.showPopup = function(sId, sContent, mixedPointer) {
    $('#' + sId).remove();
    $('<div id="' + sId + '" style="display: none;"></div>').appendTo('body').html(sContent);

    var oParams = {};
    if(mixedPointer)
    	oParams.pointer = {
    		el:$(mixedPointer)
    	};

    $('#' + sId).dolPopup(oParams);
};
/** @} */