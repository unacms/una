/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Shopify Shopify
 * @ingroup     UnaModules
 *
 * @{
 */

function BxShopifyShop(oOptions) {
	this._sAPIKey = oOptions.sAPIKey;
	this._sDomain = oOptions.sDomain;
	this._sAppId = oOptions.sAppId;

	this._sActionsUrl = oOptions.sActionUrl;
    this._sObjName = oOptions.sObjName == undefined ? 'oBxShopifyShop' : oOptions.sObjName;

    this._sAnimationEffect = oOptions.sAnimationEffect == undefined ? 'fade' : oOptions.sAnimationEffect;
    this._iAnimationSpeed = oOptions.iAnimationSpeed == undefined ? 'slow' : oOptions.iAnimationSpeed;
    this._sParamsDivider = oOptions.sParamsDivider == undefined ? '#-#' : oOptions.sParamsDivider;

    this._aHtmlIds = oOptions.aHtmlIds == undefined ? {} : oOptions.aHtmlIds;
    this._oRequestParams = oOptions.oRequestParams == undefined ? {} : oOptions.oRequestParams;

    this._sClassCover = 'bx-cover-wrapper';
    this._sClassLoading = 'bx-spf-loading';
    this._sClassLoaded = 'bx-spf-loaded';

    this._oShopClient = ShopifyBuy.buildClient({
	  accessToken: this._sAPIKey,
	  domain: this._sDomain,
	  appId: this._sAppId
	});
}

BxShopifyShop.prototype.getUnit = function(iProduct) {
	var $this = this;

	var oUnit = $('.' + this._aHtmlIds['unit'] + iProduct + ':not(.' + this._sClassLoading + ', .' + this._sClassLoaded + '):first');
	if(!oUnit.length)
		return;

	bx_loading(oUnit.addClass(this._sClassLoading), true);

	this.fetchProduct(iProduct, function (oProduct) {
		$this.onGetUnit(oProduct);
	}, function() {
		bx_loading(oUnit.removeClass(this._sClassLoading), false);

		bx_alert(_t('_bx_shopify_err_load_product'));
	});
};

BxShopifyShop.prototype.onGetUnit = function(oProduct) {
	if(!oProduct)
		return;

	var oUnit = $('.' + this._aHtmlIds['unit'] + oProduct.id + '.' + this._sClassLoading + ':first');
	if(!oUnit.length)
		return;

	//--- Fetch product main image.
	if(oProduct.selectedVariantImage.src) {
		//--- for Extended view 
		oUnit.find('.bx-spf-unit-cover img').attr('src', oProduct.selectedVariantImage.src).parents('.bx-spf-unit-cover:first').show();

		//--- for Gallery view
		oUnit.find('.bx-spf-unit-thumb').css('background-image', 'url(' + oProduct.selectedVariantImage.src + ')');
	}

	//--- Fetch product description.
	oUnit.find('.bx-spf-unit-summary').html(oProduct.description);

	//--- Fetch price.
	if(oProduct.selectedVariant.formattedPrice) {
		var oBuy = oUnit.find('.bx-shopify-buy');
		var sBuyContent = oBuy.html();
		oBuy.html(sBuyContent.replace('{price}', oProduct.selectedVariant.formattedPrice));
		oBuy.attr('href', oProduct.selectedVariant.checkoutUrl(1));
		oBuy.show();
	}

	bx_loading(oUnit.removeClass(this._sClassLoading).addClass(this._sClassLoaded), false);
};

BxShopifyShop.prototype.getEntry = function(iProduct) {
	var $this = this;
	var oLoading = $('.' + this._sClassCover);

	bx_loading(oLoading, true);

	this.fetchProduct(iProduct, function (oProduct) {
		$this.onGetEntry(oProduct);
	}, function() {
		bx_loading(oLoading, false);

		bx_alert(_t('_bx_shopify_err_load_product'));
	});
};

BxShopifyShop.prototype.onGetEntry = function(oProduct) {
	if(!oProduct)
		return;

	//--- Fetch product main image.
	var iCoverImageId = oProduct.selectedVariantImage.id;
	$('.' + this._sClassCover).css('background-image', 'url(' + oProduct.selectedVariantImage.src + ')');

	//--- Fetch product description.
	$('#' + this._aHtmlIds['entry_content']).find('.bx-spf-text').html(oProduct.description);

	//--- Fetch product images.
	var oSample = $('#' + this._aHtmlIds['entry_attachment_sample']);
	var oContainer = $('#' + this._aHtmlIds['entry_attachments']);
	oProduct.images.map(function(oImage) {
		if(oImage.id == iCoverImageId)
			return;

		var oAttachment = oSample.clone().removeAttr('id').removeClass('bx-spf-attachment-sample');

		var oLink = oAttachment.find('.bx-spf-attachment-link');
		var sLinkOnClick = oLink.attr('onclick');
		oLink.attr('onclick', sLinkOnClick.replace('{id}', oImage.id).replace('{url}', oImage.src));
		oLink.find('.bx-spf-attachment-img').attr('src', oImage.src);

		var oPopup = oAttachment.find('.bx-popup-wrapper');
		var sPopupId = oPopup.attr('id');
		oPopup.attr('id', sPopupId.replace('{id}', oImage.id));
		oPopup.find('.bx-spf-attachment-popup-img').attr('src', oImage.src);

		oContainer.prepend(oAttachment);
	});
	oSample.remove();

	bx_center_content('.bx-base-text-attachments', '.bx-base-text-attachment', true);

	//--- Fetch price.
	var oBuy = $('#' + this._aHtmlIds['entry_buy']);
	var sBuyContent = oBuy.html();
	oBuy.html(sBuyContent.replace('{price}', oProduct.selectedVariant.formattedPrice));
	oBuy.attr('href', oProduct.selectedVariant.checkoutUrl(1));
	oBuy.show();

	bx_loading($('.' + this._sClassCover), false);
};

BxShopifyShop.prototype.fetchProduct = function(iProduct, onComplete, onError) {
	var $this = this;

	this._oShopClient.fetchProduct(iProduct).then(function(oProduct) {
		if(typeof(onComplete) == 'function')
			onComplete(oProduct);
        else 
        	$this.onFetchProduct(oProduct);
	}).catch(function() {
		if(typeof(onError) == 'function')
			onError();
        else 
        	bx_alert(_t('_bx_shopify_err_load_product'));
	});
};

BxShopifyShop.prototype.fetchCollection = function(iCollection, onComplete, onError) {
	var $this = this;

	this._oShopClient.fetchQueryProducts({collection_id: iCollection}).then(function(oProducts) {
		if(typeof(onComplete) == 'function')
			onComplete(oProduct);
        else 
        	$this.onFetchCollection(oProducts);
	}).catch(function() {
		if(typeof(onError) == 'function')
			onError();
        else 
        	bx_alert(_t('_bx_shopify_err_load_collection'));
	});
};

BxShopifyShop.prototype.onFetchProduct = function(oProduct) {
	//--- Some default action can be put here.
};

BxShopifyShop.prototype.onFetchCollection = function(oProducts) {
	//--- Some default action can be put here.
};

/** @} */
