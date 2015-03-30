<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioStore extends BxDolStudioStore
{
    protected $iPerPageDefault = 24;

    function __construct($sPage = "")
    {
        parent::__construct($sPage);
    }

    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('modules.css', 'store.css', 'store-media-tablet.css', 'store-media-desktop.css'));
    }

    function getPageJs()
    {
    	BxDolStudioTemplate::getInstance()->addJsTranslation(array('_adm_btn_queued_submit'));
        return array_merge(parent::getPageJs(), array('jquery.fancybox.pack.js', 'store.js'));
    }

    function getPageJsObject()
    {
        return 'oBxDolStudioStore';
    }

    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aMenu = array();
        $aMenuItems = array(
	        'goodies' => array('icon' => 'home'), 
	        'featured' => array('icon' => 'thumbs-up'), 
	        'purchases' => array('icon' => 'shopping-cart'), 
	        'updates' => array('icon' => 'refresh'), 
	        'checkout' => array('icon' => 'credit-card'), 
	        'downloaded' => array('icon' => 'list')
        );
        foreach($aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => $aItem['icon'],
                'link' => BX_DOL_URL_STUDIO . 'store.php?page=' . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            );

        $iCounter = BxDolStudioCart::getInstance()->getCount();

        $aMarkers = array(
            'checkout_counter' => $oTemplate->parseHtmlByName('menu_side_counter.html', array(
                'bx_if:hide_counter' => array(
                    'condition' => $iCounter <= 0,
                    'content' => array()
                ),
                'counter' => $iCounter
            ))
        );

        return parent::getPageMenu($aMenu, $aMarkers);
    }

    function getPageCode($bHidden = false)
    {
        $sMethod = 'get' . ucfirst($this->sPage) . 'List';
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod();
    }

    function getPageContent()
    {
        $sMethod = 'get' . ucfirst($this->sPage) . 'List';
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod(false);
    }

    protected function getGoodiesList()
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $mixedResult = BxDolStudioInstallerUtils::getInstance()->getAccessObject(true)->doAuthorize();
        if($mixedResult === true) {
	        $aProducts = $this->loadGoodies();

	        $sContent = "";
	        foreach($aProducts as $aBlock) {
	            $aBlock['items'] = $oTemplate->parseHtmlByName('str_products.html', array(
	                'list' => $this->displayProducts($aBlock['items']),
	                'paginate' => ''
	            ));

	            $sContent .= $this->getBlockCode($aBlock);
	        }
        }
        else 
        	$sContent = $this->getBlockCode(array(
                'caption' => '_adm_block_cpt_goodies',
                'items' => $mixedResult,
            ));

        return $oTemplate->parseHtmlByName('store.html', array(
            'js_object' => $sJsObject,
            'content' => $sContent
        ));
    }

    protected function getFeaturedList($bWrapInBlock = true)
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $mixedResult = BxDolStudioInstallerUtils::getInstance()->getAccessObject(true)->doAuthorize();
        if($mixedResult === true) {
	        $iStart = (int)bx_get('str_start');
	        $iPerPage = (int)bx_get('str_per_page');
	        if(empty($iPerPage))
	            $iPerPage = $this->iPerPageDefault;

	        $aProducts = $this->loadFeatured($iStart, $iPerPage + 1);

	        $oPaginate = new BxTemplPaginate(array(
	            'start' => $iStart,
	            'per_page' => $iPerPage,
	            'on_change_page' => $sJsObject . ".changePagePaginate(this, 'featured', {start}, {per_page})"
	        ));
	        $oPaginate->setNumFromDataArray($aProducts);

	        $sContent = $oTemplate->parseHtmlByName('str_products.html', array(
	            'list' => $this->displayProducts($aProducts),
	            'paginate' => $oPaginate->getSimplePaginate()
	        ));
        }
        else 
        	$sContent = $mixedResult;

		if(!$bWrapInBlock)
			return $sContent;

        return $oTemplate->parseHtmlByName('store.html', array(
            'js_object' => $sJsObject,
            'content' => $this->getBlockCode(array(
				'caption' => '_adm_block_cpt_featured',
				'items' => $sContent
			))
        ));
    }

    protected function getModulesList($bWrapInBlock = true)
    {
        return $this->getTag('modules', $bWrapInBlock);
    }

    protected function getTemplatesList($bWrapInBlock = true)
    {
        return $this->getTag('templates', $bWrapInBlock);
    }

    protected function getLanguagesList($bWrapInBlock = true)
    {
        return $this->getTag('languages', $bWrapInBlock);
    }

    protected function getTag($sTag, $bWrapInBlock = true)
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $iStart = (int)bx_get('str_start');
        $iPerPage = (int)bx_get('str_per_page');
        if(empty($iPerPage))
            $iPerPage = $this->iPerPageDefault;

        $aProducts = $this->loadTag($sTag, $iStart, $iPerPage + 1);

        $oPaginate = new BxTemplPaginate(array(
            'start' => $iStart,
            'per_page' => $iPerPage,
            'on_change_page' => $sJsObject . ".changePagePaginate(this, '" . $sTag . "', {start}, {per_page})"
        ));
        $oPaginate->setNumFromDataArray($aProducts);

        $sContent = $oTemplate->parseHtmlByName('str_products.html', array(
            'list' => $this->displayProducts($aProducts),
            'paginate' => $oPaginate->getSimplePaginate()
        ));

        if(!$bWrapInBlock)
            return $sContent;

        return $oTemplate->parseHtmlByName('store.html', array(
            'js_object' => $sJsObject,
            'content' => $this->getBlockCode(array(
				'caption' => '_adm_block_cpt_' . $sTag,
				'items' => $sContent,
			))
        ));
    }

    protected function getPurchasesList()
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();

        $aProducts = $this->loadPurchases();
        return $oTemplate->parseHtmlByName('store.html', array(
            'js_object' => $this->getPageJsObject(),
            'content' => $this->getBlockCode(array(
				'caption' => '_adm_block_cpt_purchases',
				'items' => $oTemplate->parseHtmlByName('str_products.html', array(
		            'list' => $this->displayProducts($aProducts),
		            'paginate' => ''
		        ))
			))
        ));
    }

    protected function getUpdatesList()
    {
    	$oTemplate = BxDolStudioTemplate::getInstance();

        $aUpdates = $this->loadUpdates();
        return $oTemplate->parseHtmlByName('store.html', array(
            'js_object' => $this->getPageJsObject(),
            'content' => $this->getBlockCode(array(
				'caption' => '_adm_block_cpt_updates',
				'items' => $oTemplate->parseHtmlByName('str_products.html', array(
		            'list' => $this->displayUpdates($aUpdates),
		            'paginate' => ''
		        ))
			))
        ));
    }

    protected function getCheckoutList()
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        //--- Empty cart by vendor if the payment was accepted.
        $mixedVendor = bx_get('vendor');
        $mixedProducts = bx_get('products');
        if($mixedVendor !== false && $mixedProducts !== false) {
            $oCart = BxDolStudioCart::getInstance();

            $aProducts = explode(',', base64_decode($mixedProducts));
            foreach($aProducts as $iId)
                $oCart->delete($mixedVendor, $iId);

            return $this->getMessage('_adm_block_cpt_checkout', '_adm_msg_modules_success_checkouted');
        }

        $aVendors = $this->loadCheckout();
        if(empty($aVendors))
            return $this->getMessage('_adm_block_cpt_checkout', '_Empty');        

        $sContent = '';
        foreach($aVendors as $sName => $aInfo) {
            $fTotal = 0;
            $sCurrency = '';
            foreach($aInfo['products'] as $aProduct) {
                $iCount = isset($aInfo['counts'][$aProduct['id']]) ? (int)$aInfo['counts'][$aProduct['id']] : 1;
                $fTotal += $iCount * $aProduct['price'];

                if($sCurrency == '' && isset($aProduct['currency_sign']))
                    $sCurrency = $aProduct['currency_sign'];
            }

            $aMenu = array(
                array('id' => 'checkout-' . $sName, 'name' => 'checkout-' . $sName, 'link' => 'javascript:void(0)', 'onclick' => $sJsObject . ".checkoutCart('" . $sName . "', this);", 'target' => '_self', 'title' => '_adm_action_cpt_checkout', 'active' => 1),
                array('id' => 'delete-all-' . $sName, 'name' => 'delete-all-' . $sName, 'link' => 'javascript:void(0)', 'onclick' => $sJsObject . ".deleteAllFromCart('" . $sName . "', this)", 'target' => '_self', 'title' => '_adm_action_cpt_delete_all', 'active' => 1)
            );
	        $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_buttons_hor.html', 'menu_id'=> 'timeline-view-all', 'menu_items' => $aMenu));

	        $sContent .= $this->getBlockCode(array(
                'caption' => _t('_adm_block_cpt_checkout_by_vendor_csign', $sName, $sCurrency, $fTotal),
                'items' => $oTemplate->parseHtmlByName('str_products.html', array(
		            'list' => $this->displayProducts($aInfo['products'], array('is_shopping_cart' => true, 'counts' => $aInfo['counts'])),
		            'paginate' => ''
		        )),
                'panel_bottom' => $oMenu->getCode()
            ));
        }

        return $oTemplate->parseHtmlByName('store.html', array(
            'js_object' => $sJsObject,
            'content' => $sContent
        ));
    }

    protected function getDownloadedList()
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sContent = $sModules = $sUpdates = "";
        $aProducts = $this->loadDownloaded();

        //--- Prepare modules.
        foreach($aProducts['modules'] as $aModule) {
        	$sIcon = BxDolStudioUtils::getModuleIcon($aModule, 'store');
        	$bIcon = strpos($sIcon, '.') === false;

        	$bInstalled = $aModule['installed'];
        	$bQueued = !$bInstalled && $this->oDb->isQueued('action', $aModule['dir']);
        	

            $sModules .= $oTemplate->parseHtmlByName('str_product_v1.html', array(
                'js_object' => $sJsObject,
             	'name' => $aModule['name'],
                'bx_if:icon' => array (
	                'condition' => $bIcon,
	                'content' => array('icon' => $sIcon),
	            ),
                'bx_if:image' => array (
	                'condition' => !$bIcon,
	                'content' => array('icon_url' => $sIcon),
	            ),
                'title' => $aModule['title'],
                'vendor' => $aModule['vendor'],
                'version' => $aModule['version'],
                'dir' => $aModule['dir'],
                'bx_if:hide_install' => array(
                    'condition' => $bInstalled || $bQueued,
                    'content' => array()
                ),
                'bx_if:hide_queued' => array(
                    'condition' => !$bQueued,
                    'content' => array()
                ),
                'bx_if:hide_installed' => array(
                    'condition' => !$bInstalled || $bQueued,
                    'content' => array()
                )
            ));
        }

        //--- Prepare updates.
        foreach($aProducts['updates'] as $aUpdate) {
        	$sIcon = BxDolStudioUtils::getModuleIcon(array('type' => $aUpdate['module_type'], 'name' => $aUpdate['module_name'], 'dir' => $aUpdate['module_dir']), 'store');
        	$bIcon = strpos($sIcon, '.') === false;

            $sUpdates .= $oTemplate->parseHtmlByName('str_update_v1.html', array(
                'js_object' => $sJsObject,
            	'name' => $aUpdate['module_name'],
                'bx_if:icon' => array (
	                'condition' => $bIcon,
	                'content' => array('icon' => $sIcon),
	            ),
                'bx_if:image' => array (
	                'condition' => !$bIcon,
	                'content' => array('icon_url' => $sIcon),
	            ),
                'title' => $aUpdate['title'],
                'vendor' => $aUpdate['vendor'],
                'versions' => _t('_adm_str_txt_update_from_to', $aUpdate['version_from'], $aUpdate['version_to']),
                'dir' => $aUpdate['dir']
            ));
        }

        if(!empty($sModules))
        	$sContent .= $this->getBlockCode(array(
				'caption' => '_adm_block_cpt_downloaded_modules',
				'items' => $oTemplate->parseHtmlByName('str_products.html', array(
		            'list' => $sModules,
		            'paginate' => ''
		        )),
			));

        if(!empty($sUpdates))
            $sContent .= $this->getBlockCode(array(
				'caption' => '_adm_block_cpt_downloaded_updates',
				'items' => $oTemplate->parseHtmlByName('str_products.html', array(
		            'list' => $sUpdates,
		            'paginate' => ''
		        )),
			));

        return $oTemplate->parseHtmlByName('store.html', array(
            'js_object' => $sJsObject,
        	'content' => $sContent
        ));
    }

    protected function getMessage($sCaption, $sContent, $aActions = array())
    {
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('store.html', array(
            'js_object' => $this->getPageJsObject(),
            'content' => $this->getBlockCode(array(
				'caption' => $sCaption,
        		'actions' => $aActions,
				'items' => MsgBox(_t($sContent))
			))
        ));
    }

    protected function getProduct($sModuleName)
    {
        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aProduct = $this->loadProduct($sModuleName);
        if(empty($aProduct) || !is_array($aProduct))
            return array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => (!empty($aProduct) ? $aProduct : _t('_adm_str_err_no_product_info')));

		$aDownloaded = $this->getDownloadedModules(false);

        $bFree = (int)$aProduct['is_free'] == 1;
        $bPurchased = (int)$aProduct['is_purchased'] == 1;
        $bPurchase = !$bFree && !$bPurchased;

		$bInCart = $bPurchase && BxDolStudioCart::getInstance()->exists($aProduct['author_name'], $aProduct['id']);

        $bDownloadable = (int)$aProduct['is_file'] == 1;
        $bDownloaded = array_key_exists($sModuleName, $aDownloaded);
        $bDownload = ($bFree || $bPurchased) && $bDownloadable;

        $bDiscount = !empty($aProduct['discount']);
        $sVersion = $bDownloaded && version_compare($aDownloaded[$sModuleName]['version'], $aProduct['version']) != 0 ? _t('_adm_str_txt_pv_version_mask', $aDownloaded[$sModuleName]['version'], $aProduct['version']) : $aProduct['version'];

        $iScreenshots = 0;
        $aScreenshots = array();
        $bScreenshots = is_array($aProduct['screenshots']) && !empty($aProduct['screenshots']);
        if($bScreenshots) {
            $iScreenshots = count($aProduct['screenshots']);
            foreach($aProduct['screenshots'] as $aScreenshot)
                $aScreenshots[] = array(
                    'view_url' => $aProduct['url'],
                    'image_url' => $aScreenshot['big']
                );
        }

        $sContent = $oTemplate->parseHtmlByname('str_product_view.html', array(
            'id' => $aProduct['id'],
            'title' => $aProduct['title'],
            'url' => $aProduct['url'],
            'author_name' => $aProduct['author_name'],
            'author_url' => $aProduct['author_url'],
            'version' => $sVersion,
            'price' => !$bFree ? _t('_adm_str_txt_price_csign', $aProduct['author_currency_sign'], $aProduct['price']) : _t('_adm_str_txt_price_free'),
            'bx_if:show_discount' => array(
                'condition' => !$bFree && $bDiscount,
                'content' => array(
                    'discount' => $bDiscount ? _t('_adm_str_txt_pv_discount_off_csign', $aProduct['discount']['percent'], $aProduct['author_currency_sign'], $aProduct['discount']['save']) : ''
                )
            ),
            'category' => $aProduct['category'],
            'category_url' => $aProduct['category_url'],
            'tags' => implode(', ', explode(',', $aProduct['tags'])),
            'reviews' => _t('_adm_str_txt_pv_stats_reviews', $aProduct['reviews_cnt']),
            'reviews_url' => $aProduct['reviews_url'],
            'likes' => _t('_adm_str_txt_pv_stats_likes', $aProduct['likes_cnt']),
            'views' => _t('_adm_str_txt_pv_stats_views', $aProduct['views_cnt']),
            'created' => $aProduct['created'],
            'updated' => $aProduct['updated'],
            'description' => $aProduct['description'],
            'bx_if:show_screenshots' => array(
                'condition' => $bScreenshots,
                'content' => array(
                    'width' => 222 * $iScreenshots - 20,
                    'bx_repeat:screenshots' => $aScreenshots
                )
            ),
            'bx_if:show_purchase' => array(
                'condition' => $bPurchase && !$bInCart,
                'content' => array(
                    'js_object' => $sJsObject,
                    'id' => $aProduct['id'],
                    'vendor' => $aProduct['author_name'],
                )
            ),
            'bx_if:show_checkout' => array(
                    'condition' => $bPurchase,
                    'content' => array(
                        'js_object' => $sJsObject,
                        'id' => $aProduct['id'],
                        'vendor' => $aProduct['author_name'],
                		'bx_if:show_as_hidden' => array(
                			'condition' => !$bInCart,
                			'content' => array()
                		)
                    )
                ),
            'bx_if:show_download' => array(
                'condition' => $bDownload && !$bDownloaded,
                'content' => array(
                    'js_object' => $sJsObject,
                    'file_id' => $aProduct['file_id'],
                )
            ),
            'bx_if:show_download_disabled' => array(
				'condition' => $bDownload && $bDownloaded,
				'content' => array()
			),
        ));

        return array('code' => BX_DOL_STUDIO_IU_RC_SUCCESS, 'message' => '', 'popup' => PopupBox('bx-std-str-popup-product', $aProduct['title'], $sContent, true), 'screenshots' => $iScreenshots);
    }

    protected function getFile($iFileId)
    {
        $mixedResult = $this->loadFile($iFileId);
        if($mixedResult === true)
        	return array('code' => BX_DOL_STUDIO_IU_RC_SUCCESS, 'message' => _t('_adm_str_msg_download_successfully'));

        if(is_string($mixedResult))
			return array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => (!empty($mixedResult) ? $mixedResult : _t('_adm_str_err_download_failed')));

		if(is_array($mixedResult))
			return array_merge(array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_str_err_download_failed')), $mixedResult);
    }

	protected function getUpdate($sModuleName, $bAutoUpdate = false)
    {
        $mixedResult = $this->loadUpdate($sModuleName, $bAutoUpdate);
        if($mixedResult === true)
        	return array('code' => BX_DOL_STUDIO_IU_RC_SUCCESS, 'message' => _t('_adm_str_msg_download' . ($bAutoUpdate ? '_and_install' : '') . '_successfully'));

		if(is_string($mixedResult))
			return array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => (!empty($mixedResult) ? $mixedResult : _t('_adm_str_err_download_failed')));

		if(is_array($mixedResult))
        	return array_merge(array('code' => BX_DOL_STUDIO_IU_RC_FAILED, 'message' => _t('_adm_str_err_download_failed')), $mixedResult);
    }

    protected function displayProducts($mixedItems, $aParams = array())
    {
        if(!is_array($mixedItems))
            return MsgBox($mixedItems);

        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $aDownloaded = $this->getDownloadedModules();
        $bShoppingCart = isset($aParams['is_shopping_cart']) && $aParams['is_shopping_cart'];

        $sResult = '';
        foreach($mixedItems as $aItem) {
            $bFree = (int)$aItem['is_free'] == 1;
            $bPurchased = (int)$aItem['is_purchased'] == 1;
            $bPurchase = !$bShoppingCart && !$bFree && !$bPurchased;

            $bInCart = $bPurchase && BxDolStudioCart::getInstance()->exists($aItem['author'], $aItem['id']);

            $bDownloadable = (int)$aItem['is_file'] == 1;
            $bDownloaded = in_array($aItem['name'], $aDownloaded);
            $bDownload = !$bShoppingCart && ($bFree || $bPurchased) && $bDownloadable;
            $bQueued = $this->oDb->isQueued('download', $aItem['name']);

            $sPrice = !$bFree ? _t('_adm_str_txt_price_csign', $aItem['currency_sign'], $aItem['price']) : _t('_adm_str_txt_price_free');
            $sDiscount = !$bFree && !empty($aItem['discount']) ? _t('_adm_str_txt_discount_csign', $aItem['currency_sign'], $aItem['discount']['price']) : '';

            $sIcon = !empty($aItem['thumbnail']['big']) ? $aItem['thumbnail']['big'] : BxDolStudioUtils::getIconDefault(BX_DOL_MODULE_TYPE_MODULE);
			$bIcon = strpos($sIcon, '.') === false;

            $sResult .= $oTemplate->parseHtmlByName('str_product_v2.html', array(
                'js_object' => $sJsObject,
                'id' => $aItem['id'],
            	'name' => $aItem['name'],
                'url' => $aItem['url'],
                'bx_if:icon' => array (
	                'condition' => $bIcon,
	                'content' => array('icon' => $sIcon),
	            ),
                'bx_if:image' => array (
	                'condition' => !$bIcon,
	                'content' => array('icon_url' => $sIcon),
	            ),
                'title' => $aItem['title'],
                'bx_if:show_vendor_price' => array(
                    'condition' => !$bShoppingCart,
                    'content' => array(
                        'vendor' => $aItem['author'],
                        'price' => $sPrice,
                        'discount' => $sDiscount,
                    )
                ),
                'bx_if:show_count_price' => array(
                    'condition' => $bShoppingCart,
                    'content' => array(
                        'count' => isset($aParams['counts'][$aItem['id']]) ? (int)$aParams['counts'][$aItem['id']] : 1,
                        'price' => $sPrice,
                        'discount' => $sDiscount,
                    )
                ),
                'bx_if:show_purchase' => array(
                    'condition' => $bPurchase && !$bInCart,
                    'content' => array(
                        'js_object' => $sJsObject,
                        'id' => $aItem['id'],
                        'vendor' => $aItem['author']
                    )
                ),
                'bx_if:show_checkout' => array(
                    'condition' => $bPurchase,
                    'content' => array(
                        'js_object' => $sJsObject,
                        'id' => $aItem['id'],
                        'vendor' => $aItem['author'],
                		'bx_if:show_as_hidden' => array(
                			'condition' => !$bInCart,
                			'content' => array()
                		)
                    )
                ),
                'bx_if:show_download' => array(
                    'condition' => $bDownload && !$bQueued && !$bDownloaded,
                    'content' => array(
                        'js_object' => $sJsObject,
                        'file_id' => $aItem['file_id']
                    )
                ),
                'bx_if:show_download_disabled' => array(
					'condition' => $bDownload && !$bQueued && $bDownloaded,
					'content' => array()
				),
				'bx_if:show_queued_disabled' => array(
					'condition' => $bDownload && $bQueued && !$bDownloaded,
					'content' => array()
				),
                'bx_if:show_delete' => array(
                    'condition' => $bShoppingCart,
                    'content' => array(
                        'js_object' => $sJsObject,
                        'id' => $aItem['id'],
                        'vendor' => $aItem['author']
                    )
                )
            ));
        }

        return $sResult;
    }

    protected function displayUpdates($mixedItems, $aParams = array())
    {
        if(!is_array($mixedItems))
            return MsgBox($mixedItems);

		if(empty($mixedItems))
			return MsgBox(_t('_Empty'));

        $sJsObject = $this->getPageJsObject();
        $oTemplate = BxDolStudioTemplate::getInstance();

        $sResult = '';
        foreach($mixedItems as $aItem) {
            $bDownloadable = (int)$aItem['is_file'] == 1;

            $sIcon = !empty($aItem['thumbnail']['big']) ? $aItem['thumbnail']['big'] : BxDolStudioUtils::getIconDefault(BX_DOL_MODULE_TYPE_MODULE);
			$bIcon = strpos($sIcon, '.') === false;

            $sResult .= $oTemplate->parseHtmlByName('str_update_v2.html', array(
                'js_object' => $sJsObject,
                'id' => $aItem['id'],
            	'name' => $aItem['name'],
                'url' => $aItem['url'],
            	'bx_if:icon' => array (
	                'condition' => $bIcon,
	                'content' => array('icon' => $sIcon),
	            ),
                'bx_if:image' => array (
	                'condition' => !$bIcon,
	                'content' => array('icon_url' => $sIcon),
	            ),
                'title' => $aItem['title'],
                'vendor' => $aItem['author'],
                'versions' => _t('_adm_str_txt_update_from_to', $aItem['file_version'], $aItem['file_version_to']),
                'bx_if:show_download' => array(
                    'condition' => $bDownloadable,
                    'content' => array(
	            		'caption' => _t($this->bAuthAccessUpdates ? '_adm_btn_download_submit' : '_adm_btn_install_submit'),
	            		'on_click' => $sJsObject . "." . ($this->bAuthAccessUpdates ? "getFile(" . $aItem['file_id'] . ", this)" : "getUpdateAndInstall('" . $aItem['name'] . "', this)")
                    )
                )
            ));
        }

        return $sResult;
    }

    private function getDownloadedModules($bNamesOnly = true)
    {
		$aModules = BxDolStudioInstallerUtils::getInstance()->getModules(false);

        return $bNamesOnly ? array_keys($aModules) : $aModules;
    }
}

/** @} */
