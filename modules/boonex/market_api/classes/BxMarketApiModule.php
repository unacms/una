<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    MarketApi MarketApi
 * @ingroup     TridentModules
 *
 * @{
 */

define('BX_MARKET_API_LICENSE_TYPE_NETWORK', 'network');

/**
 * MarketApi module
 */
class BxMarketApiModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

	public function actionGetUsers()
    {
        $sTerm = bx_get('term');

        $aResult = BxDolService::call('system', 'profiles_search', array($sTerm), 'TemplServiceProfiles');

        echoJson($aResult);
    }

    public function actionJsonBrowseAll()
    {
		$aParams = $this->getBrowseParams();

		$aResults = $this->getBrowseProducts('latest', $aParams);
		echoJson($aResults);
    }

	public function actionJsonBrowseFeatured()
    {
		$aParams = $this->getBrowseParams();
		$aParams['value'] = $this->_oConfig->getMarketMainSellerId();

       	$aResults = $this->getBrowseProducts('featured', $aParams);
       	echoJson($aResults);
    }

	public function actionJsonBrowseSelected()
    {
		$aParams = $this->getBrowseParams();
		$aParams['selected'] = array();
		if(bx_get('products') !== false)
			$aParams['selected'] = unserialize(base64_decode(bx_get('products')));

		$aResults = $this->getBrowseProducts('selected', $aParams);
		echoJson($aResults);
    }

	public function actionJsonBrowsePurchased()
    {
		$aParams = $this->getBrowseParams();
		$aParams['short'] = true;
		if(empty($aParams['client']) && !empty($aParams['key']))
			$aParams['client'] = $this->_getClientByKey($aParams['key']);

		if(empty($aParams['client']))
			return echoJson(array());

		$aResults = $this->getBrowseProducts('purchased', $aParams);
		echoJson($aResults);
    }

	public function actionJsonBrowseByCategory()
    {
		$aParams = $this->getBrowseParams();

        $aCatName2Id = array(
        	'templates' => 36, 
        	'translations' => 38
        );

        $aCategories = array_keys(BxDolFormQuery::getDataItems($this->_oConfig->CNF['MOBJECT_CATEGORY']));
        foreach($aCatName2Id as $sCat => $iId)
        	unset($aCategories[$iId]);

        $aCatName2Id['extensions'] = $aCategories;

		$aParams['value'] = $aCatName2Id[$aParams['value']];
		$aResults = $this->getBrowseProducts('category', $aParams);
		echoJson($aResults);
    }

	public function actionJsonBrowseByTag()
    {
		$aParams = $this->getBrowseParams();

		$aResults = $this->getBrowseProducts('tag', $aParams);
		echoJson($aResults);
    }

	public function actionJsonBrowseByVendor()
    {
		$aParams = $this->getBrowseParams();

        $aResults = $this->getBrowseProducts('vendor', $aParams);
        echoJson($aResults);
    }

	public function actionJsonBrowseByKeyword()
    {
		$aParams = $this->getBrowseParams();

		$aResults = $this->getBrowseProducts('keyword', $aParams);
		echoJson($aResults);
    }
	public function actionJsonBrowseUpdates()
    {
		$aResults = array(); 

		$sProducts = bx_get('products') !== false ? bx_get('products') : '';
		if(empty($sProducts))
			return echoJson($aResults);

		$aProducts = unserialize(base64_decode($sProducts));

		$aParams = $this->getBrowseParams();
		if(empty($aParams['key']))
			return echoJson($aResults);

		if(empty($aParams['client']))
			$aParams['client'] = $this->_getClientByKey($aParams['key']);

		$aResults = $this->getBrowseUpdates($aProducts, $aParams, false);
		echoJson($aResults);
    }

	public function actionJsonGetProductById()
    {
		$aResults = $this->getEntryBy('id');
		echoJson($aResults);
    }
	public function actionJsonGetProductByUri()
    {
    	//TODO: check where it's used. Need to recreated because there is no URI anymore.
		echoJson(array());
    }
	public function actionJsonGetProductByName()
    {
		$aResults = $this->getEntryBy('name');
		echoJson($aResults);
    }
	public function actionJsonDownloadUpdate()
    {
		$aResults = array();

		$sKey = bx_get('key') !== false ? bx_get('key') : '';
		$sProduct = bx_get('product') !== false ? bx_get('product') : '';
		if(empty($sProduct))
			return echoJson($aResults);

		$aProduct = unserialize(base64_decode($sProduct));

		$iClient = $this->_getClientByKey($sKey);
		if(empty($iClient))
			return echoJson($aResults);

		$aUpdate = $this->_getUpdate($iClient, $aProduct, $sKey, false);
		if(empty($aUpdate) || empty($aUpdate['file_id']))
			return echoJson($aResults);

		$aResults = $this->_getFile((int)$aUpdate['file_id']);
		echoJson($aResults);
    }

	public function actionPurchase($iVendor)
    {
    	$CNF = &$this->_oConfig->CNF;
    	$sSessionKeysPrefix = $this->_oConfig->getSessionKeysPrefix();

		if(empty($iVendor) || bx_get('products') === false)
			$this->_oTemplate->displayNoData('Wrong incomming paramaters. Please report.');

		$iModule = $this->_oConfig->getMarketModuleId();

		$bUser = isLogged();
		$sSid = bx_process_input(bx_get('sid'));

		$oSession = BxDolSession::getInstance();
		if($bUser && $oSession->isValue($sSessionKeysPrefix . 'sid') && $oSession->getValue($sSessionKeysPrefix . 'sid') != $sSid) {
			$bUser = false;
			bx_logout();
		}

		$sProducts = bx_process_input(bx_get('products'));
		$aProducts = explode(',', base64_decode($sProducts));

		$aPurchases = array();
		foreach($aProducts as $iProduct) {
			$aProduct = BxDolService::call($CNF['MARKET'], 'get_entries_by', array(array('type' => 'id', 'value' => $iProduct)));

			$aPurchases[] = array($iModule, $aProduct[$CNF['MFIELD_AUTHOR']], $aProduct[$CNF['MFIELD_ID']], 1);
		}

		$oSession->setValue($sSessionKeysPrefix . 'sid', $sSid);
		$oSession->setValue($sSessionKeysPrefix . 'purchase', serialize($aPurchases));
		$oSession->setValue($sSessionKeysPrefix . 'vendor', $iVendor);
		if(isset($_SERVER["HTTP_REFERER"]))
			$oSession->setValue($sSessionKeysPrefix . 'referer', bx_append_url_params($_SERVER["HTTP_REFERER"], array('vendor' => $iVendor, 'products' => $sProducts)));

		if($bUser)
			$this->addToCart();

		header('Location: ' . BX_DOL_URL_ROOT . 'page/login');
		exit;
    }

    public function actionSubscribe($iVendor, $iItem)
    {
    	$CNF = &$this->_oConfig->CNF;
    	$sSessionKeysPrefix = $this->_oConfig->getSessionKeysPrefix();

    	if(empty($iVendor) || empty($iItem))
			$this->_oTemplate->displayNoData('Wrong incomming paramaters. Please report.');

		$iModule = $this->_oConfig->getMarketModuleId();

		$bUser = isLogged();
		$sSid = bx_process_input(bx_get('sid'));

		$oSession = BxDolSession::getInstance();
		if($bUser && $oSession->isValue($sSessionKeysPrefix . 'sid') && $oSession->getValue($sSessionKeysPrefix . 'sid') != $sSid) {
			$bUser = false;
			bx_logout();
		}

		$oSession->setValue($sSessionKeysPrefix . 'sid', $sSid);
		$oSession->setValue($sSessionKeysPrefix . 'subscribe', serialize(array($iModule, $iVendor, $iItem, 1)));
		if(isset($_SERVER["HTTP_REFERER"]))
			$oSession->setValue($sSessionKeysPrefix . 'referer', bx_append_url_params($_SERVER["HTTP_REFERER"], array('page' => 'checkout', 'vendor' => $iVendor, 'products' => '')));

		if($bUser)
			$this->subscribe();

		header('Location: ' . BX_DOL_URL_ROOT . 'page/login');
		exit;
    }

	public function serviceBrowsePurchased()
    {
    	$CNF = &$this->_oConfig->CNF;

		$aParams = $this->getBrowseParams();
		if(empty($aParams['key']) || empty($aParams['domain']))
			return _t('_bx_market_api_err_wrong_data');

		$aClient = $this->_getOauthClientByKey($aParams['key']);
		if(empty($aClient) || !is_array($aClient))
			return _t('_bx_market_api_err_wrong_data');

		if(strcmp($aParams['domain'], $this->getBrowseParamsDomain($aClient['redirect_uri'])) != 0)
			return _t('_bx_market_api_err_ks_used');

		$aParams['parent'] = (int)$aClient['parent_id'];
		$aParams['client'] = (int)$aClient['user_id'];
		$aParams['products'] = bx_get('products') !== false ? unserialize(base64_decode(bx_get('products'))) : array();

		$aResults = $this->getBrowseProducts('purchased', $aParams);
		if(empty($aParams['parent']))
			return $aResults;

		$iProfile = (int)$aParams['parent'];
		$oProfile = BxDolProfile::getInstance($iProfile);
		if(!$oProfile)
			return $aResults;

		$aLicenses = $this->_oDb->getLicense(array('type' => 'profile_id_type', 'profile_id' => $iProfile, 'type' => BX_MARKET_API_LICENSE_TYPE_NETWORK));
		if(empty($aLicenses) || !is_array($aLicenses))
			return $aResults;

		foreach($aLicenses as $aLicense) {
			$aLicenseInfo = BxDolService::call($CNF['MARKET'], 'get_license', array(array('type' => 'id', 'id' => $aLicense[$CNF['FIELD_LICENSE_ID']])));

			$aResults = array_merge($aResults, $this->getBrowseProducts('granted', array(
				'value' => $this->_oConfig->getMarketMainSellerId(),
				'license' => $aLicenseInfo
			)));
		}

		return $aResults;
    }

	public function serviceBrowseUpdates()
    {
    	$CNF = &$this->_oConfig->CNF;

		$aParams = $this->getBrowseParams();
		if(empty($aParams['key']))
			return _t('_bx_market_api_err_wrong_data');

		$aClient = $this->_getOauthClientByKey($aParams['key']);
		if(empty($aClient) || !is_array($aClient))
			return _t('_bx_market_api_err_wrong_data');

		$aParams['client'] = (int)$aClient['user_id'];
		$aProducts = bx_get('products') !== false ? unserialize(base64_decode(bx_get('products'))) : array();

		return $this->getBrowseUpdates($aProducts, $aParams, true);
    }

	public function serviceDownloadFile()
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sKey = bx_process_input(bx_get('key'), BX_DATA_TEXT);
    	$aClient = $this->_getOauthClientByKey($sKey);
		if(empty($aClient) || !is_array($aClient))
			return _t('_bx_market_api_err_wrong_data');

		$iClient = (int)$aClient['user_id'];
		$iFile = (int)bx_get('file_id');

		$aEntry = BxDolService::call($CNF['MARKET'], 'get_entries_by', array(array(
			'type' => 'file_id',
			'value' => $iFile
		)));
		if(empty($aEntry))
			return array();

		$aFile = $this->_getFile($iFile);
		$bFree = (float)$aEntry[$CNF['MFIELD_PRICE_SINGLE']] == 0 && (float)$aEntry[$CNF['MFIELD_PRICE_RECURRING']] == 0;
		if(!empty($aFile) && !empty($sKey) && !$bFree && !in_array($aEntry['id'], $this->_getGranted($sKey))) {
			$aLicense = BxDolService::call($CNF['MARKET'], 'get_license', array(array(
				'type' => 'profile_id_file_id_key', 
				'profile_id' => $iClient,
				'file_id' => $iFile,
				'key' => $sKey
			)));
	        if(empty($aLicense))
	        	return array();

			if(empty($aLicense['domain']))
				BxDolService::call($CNF['MARKET'], 'update_license', array(array('domain' => $sKey), array('id' => $aLicense['id'])));
		}

		return $aFile;
    }

    public function serviceGetBlockKandsManage()
    {
    	$CNF = &$this->_oConfig->CNF;

	    $sMenu = '';
	    $sGrid = 'OBJECT_GRID_KANDS';

		$aLicenses = $this->_oDb->getLicense(array('type' => 'profile_id_type', 'profile_id' => bx_get_logged_profile_id(), 'type' => BX_MARKET_API_LICENSE_TYPE_NETWORK));
		if(!empty($aLicenses) && is_array($aLicenses)) {
			$sUriParam = 'clients';
			$bClients = (int)bx_get($sUriParam) == 1;
			if($bClients)
				$sGrid = 'OBJECT_GRID_KANDS_CLIENTS';

			$sLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_KANDS_MANAGE']);
            $oMenu = new BxTemplMenu(array(
            	'template' => 'menu_vertical.html', 
            	'menu_items' => array(
					array('id' => 'manage-myself', 'name' => 'manage-myself', 'class' => '', 'link' => $sLink, 'target' => '_self', 'title' => _t('_bx_market_api_menu_item_title_kands_manage_myself'), 'active' => 1),
					array('id' => 'manage-clients', 'name' => 'manage-clients', 'class' => '', 'link' => bx_append_url_params($sLink, array($sUriParam => 1)), 'target' => '_self', 'title' => _t('_bx_market_api_menu_item_title_kands_manage_clients'), 'active' => 1)
				)
            ), $this->_oTemplate);
            $oMenu->setSelected($this->_aModule['name'], 'manage-' . ($bClients ? 'clients' : 'myself'));
            $sMenu = $oMenu->getCode();
		}

    	$oGrid = BxDolGrid::getObjectInstance($CNF[$sGrid]);
        if($oGrid)
            return array(
            	'content' => $oGrid->getCode(),
            	'menu' => $sMenu
            );

        $this->_oTemplate->displayPageNotFound();
    }

	public function checkAllowedPass($aDataEntry, $isPerformAction = false)
    {
    	$iProfile = bx_get_logged_profile_id();
    	if($aDataEntry['user_id'] != $iProfile)
    		return CHECK_ACTION_RESULT_NOT_ALLOWED;

        if(isAdmin())
        	return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($iProfile, 'pass', $this->getName(), $isPerformAction);
        if($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
			return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function addToCart()
    {
    	$sSessionKeysPrefix = $this->_oConfig->getSessionKeysPrefix();

    	$oSession = BxDolSession::getInstance();
    	$sSid = $oSession->getValue($sSessionKeysPrefix . 'sid');
		$sPurchase = $oSession->getUnsetValue($sSessionKeysPrefix . 'purchase');
		$iVendor = (int)$oSession->getUnsetValue($sSessionKeysPrefix . 'vendor');
		if(empty($sSid) || empty($sPurchase) || empty($iVendor))
			return;

		$oPayment = BxDolPayments::getInstance();

		$aProducts = unserialize($sPurchase);
		foreach($aProducts as $aProduct) {
			list($iModuleId, $iVendorId, $iItemId, $iItemCount) = $aProduct;
			$oPayment->addToCart($iVendorId, $iModuleId, $iItemId, $iItemCount);
		}

		header('Location: ' . $oPayment->getCartUrl($iVendor));
		exit;
    }

	public function subscribe()
    {
		$sSessionKeysPrefix = $this->_oConfig->getSessionKeysPrefix();

    	$oSession = BxDolSession::getInstance();
    	$sSid = $oSession->getValue($sSessionKeysPrefix . 'sid');
		$sSubscribe = $oSession->getUnsetValue($sSessionKeysPrefix . 'subscribe');
		if(empty($sSid) || empty($sSubscribe))
			return;

		list($iModuleId, $iVendorId, $iItemId, $iItemCount) = unserialize($sSubscribe);
		$sVendorProvider = $this->_oConfig->getMarketMainSellerSbsProvider();

		header('Location: ' . BxDolPayments::getInstance()->getSubscribeUrl($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount));
		exit;
    }

    public function redirectAfterPayment()
    {
    	$sSessionKeysPrefix = $this->_oConfig->getSessionKeysPrefix();

    	$oSession = BxDolSession::getInstance();
    	$sSid = $oSession->getUnsetValue($sSessionKeysPrefix . 'sid');
    	$sReferer = $oSession->getUnsetValue($sSessionKeysPrefix . 'referer');
    	if(empty($sSid) || empty($sReferer))
			return;

		header('Location: ' . $sReferer);
		exit;
    }

	protected function getEntryBy($sType)
	{
		$CNF = &$this->_oConfig->CNF;

		if(!in_array($sType, array('id', 'name')))
			return array();

    	$aEntry = BxDolService::call($CNF['MARKET'], 'get_entry_by', array($sType, ($sType == 'id' ? (int)bx_get('value') : bx_process_input(bx_get('value'), BX_DATA_TEXT))));
    	if(empty($aEntry))
    		return array();

		$iClient = 0;
		$oClient = null;
		if(bx_get('client') !== false) {
			$iClient = (int)bx_get('client');
			$oClient = BxDolProfile::getInstance($iClient);
		}

		$sKey = '';
		if(bx_get('key') !== false)
			$sKey = bx_process_input(bx_get('key'), BX_DATA_TEXT);

		$oAuthor = BxDolProfile::getInstance($aEntry[$CNF['MFIELD_AUTHOR']]);

		$aCurrency = $this->_oConfig->getCurrency();

		$bFree = (float)$aEntry[$CNF['MFIELD_PRICE_SINGLE']] == 0 && (float)$aEntry[$CNF['MFIELD_PRICE_RECURRING']] == 0;
		$bPurchased = $oClient ? BxDolService::call($CNF['MARKET'], 'has_license', array($iClient, $aEntry[$CNF['MFIELD_ID']])) || in_array($aEntry[$CNF['MFIELD_ID']], $this->_getGranted($sKey)) : false;
		$bDownloadable = ($bFree || $bPurchased) && (int)$aEntry[$CNF['MFIELD_PACKAGE']] != 0;

		$sViewUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['MURL_VIEW_ENTRY'] . $aEntry[$CNF['MFIELD_ID']]);

		$sCategory = $sCategoryUrl = '';
		$iCategory = (int)$aEntry[$CNF['MFIELD_CATEGORY']];
		if($iCategory != 0) {
			$oCategory = BxDolCategory::getObjectInstance($CNF['MOBJECT_CATEGORY']);
			if($oCategory) {
				$aCategories = BxDolFormQuery::getDataItems($CNF['MOBJECT_CATEGORY']);

				$sCategory = isset($aCategories[$iCategory]) ? $aCategories[$iCategory] : '';
				$sCategoryUrl = $oCategory->getCategoryUrl($iCategory);
			}
		}

		$aFile = BxDolService::call($CNF['MARKET'], 'get_file', array($aEntry[$CNF['MFIELD_PACKAGE']]));

		$aScreenshots = array(); 
		$aImages = BxDolService::call($CNF['MARKET'], 'get_screenshots', array($aEntry[$CNF['MFIELD_ID']]));
		foreach($aImages as $aImage) {
			$aScreenshots[] = array(
				'id' => $aImage['img_id'], 
				'small' => $aImage['url_sm'],
				'big' => $aImage['url_bg']
			);
		}

    	return array(
    		'id' => $aEntry[$CNF['MFIELD_ID']],
    		'name' => $aEntry[$CNF['MFIELD_NAME']],
	    	'title' => $aEntry[$CNF['MFIELD_TITLE']],
	    	'url' => $sViewUrl,
	    	'description' => $aEntry[$CNF['MFIELD_TEXT']],
    		'description_plain' => BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($aEntry[$CNF['MFIELD_TEXT']]), (int)getParam($CNF['PARAM_PLAIN_DESCRIPTION_CHARS'])),
	    	'author_id' => $aEntry[$CNF['MFIELD_AUTHOR']],
	    	'author_name' => $oAuthor->getDisplayName(),
	    	'author_url' => $oAuthor->getUrl(),
    		'author_currency_code' => $aCurrency['code'],
    		'author_currency_sign' => $aCurrency['sign'],
	    	'created' => $aEntry[$CNF['MFIELD_ADDED']],
	    	'updated' => $aEntry[$CNF['MFIELD_CHANGED']],
    		'version' => $aFile[$CNF['MFIELD_FILE_VERSION']],
	    	//--- Single price related fields
	    	'price_single' => $aEntry[$CNF['MFIELD_PRICE_SINGLE']],
    		'discount_single' => array(), //TODO: need to have Discounted Price in Market if we need this feature at all.		$aEntry['entry_price_full'] != 0 && $aEntry['entry_price_full'] != $aEntry['entry_price_discounted'] ? $this->rScript->_prepareDiscount($aEntry['entry_price_full'], $aEntry['entry_price_discounted']) : array(),
	    	//--- Recurring price related fields
    		'price_recurring' => $aEntry[$CNF['MFIELD_PRICE_RECURRING']],
    		'duration_recurring' => $aEntry[$CNF['MFIELD_DURATION_RECURRING']],
    		'category' => $sCategory,
    		'category_url' => $sCategoryUrl,
    		'tags' => '', //TODO: integrate tags
    		'reviews_cnt' => $aEntry[$CNF['MFIELD_COMMENTS']],
    		'reviews_url' => $sViewUrl . '#cmts-anchor-bx-market-' . $aEntry[$CNF['MFIELD_ID']],
    		'views_cnt' => $aEntry[$CNF['MFIELD_VIEWS']],
    		'votes_cnt' => $aEntry[$CNF['MFIELD_VOTES']],
    		'rate' => $aEntry[$CNF['MFIELD_RATE']],
    		'screenshots' => $aScreenshots,
			'is_file' => $bDownloadable ? 1 : 0,
			'file_id' => $bDownloadable ? $aEntry[$CNF['MFIELD_PACKAGE']] : 0,
    		'is_free' => $bFree ? 1 : 0,
			'is_purchased' => $bPurchased ? 1 : 0,
    	);
    }

    protected function getBrowseParams()
    {
    	$aParams = array();

    	if(bx_get('value') !== false)
			$aParams['value'] = bx_process_input(bx_get('value'), BX_DATA_TEXT);

    	if(bx_get('order_by') !== false)
			$aParams['order_by'] = bx_process_input(bx_get('order_by'), BX_DATA_TEXT);
		if(bx_get('order_trend') !== false)
        	$aParams['order_trend'] = bx_process_input(bx_get('order_trend'), BX_DATA_TEXT);

		$aParams['start'] = bx_get('start') !== false ? (int)bx_get('start') : 0;
        $aParams['per_page'] = bx_get('per_page') !== false ? (int)bx_get('per_page') : 10;

        $aParams['key'] = bx_get('key') !== false ? bx_process_input(bx_get('key'), BX_DATA_TEXT) : '';
        $aParams['key_assigned'] = bx_get('key_assigned') !== false ? (int)bx_get('key_assigned') : 0;

        $aParams['parent'] = bx_get('parent') !== false ? (int)bx_get('parent') : 0;
        $aParams['client'] = bx_get('client') !== false ? (int)bx_get('client') : 0;

        $aParams['domain'] = $this->getBrowseParamsDomain();

        return $aParams;
    }

    protected function getBrowseParamsDomain($sDomain = '')
    {
    	if(empty($sDomain) && bx_get('domain') !== false)
    		$sDomain = bx_process_input(bx_get('domain'), BX_DATA_TEXT);

    	if(empty($sDomain))
    		return $sDomain;

        $aInfo = parse_url($sDomain);
        return !empty($aInfo['host']) ? $aInfo['host'] : '';
    }

    protected function getBrowseProducts($sType, $aParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aParams['type'] = $sType;
        $aEntries = BxDolService::call($CNF['MARKET'], 'get_entries_by', array($aParams));

		$this->_prepareForOutputEntries($aEntries, false, $aParams);
		return $aEntries;
    }

    protected function getBrowseUpdates($aProducts, $aParams = array(), $bAuthorized = true)
    {
		$aResults = array();

		$iClient = isset($aParams['client']) ? (int)$aParams['client'] : 0;
    	if(empty($aProducts) || !is_array($aProducts) || empty($iClient))
    		return $aResults;

		foreach($aProducts as $aProduct) {
			$aInfo = $this->_getUpdate($iClient, $aProduct, $aParams['key'], $bAuthorized);
			if(empty($aInfo) || !is_array($aInfo))
				continue;

			$aResults[] = $aInfo;
		}

		$this->_prepareForOutputEntries($aResults, true);
		return $aResults;
    }

    protected function _prepareForOutputEntries(&$aItems, $bForcePurchased = false, $aParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;

    	$oPermalink = BxDolPermalinks::getInstance();

    	$aCurrency = $this->_oConfig->getCurrency();

    	$iRateMin = $iRateMax = 1;
    	$aVoteSystems = BxDolVote::getSystems();
    	if(!empty($aVoteSystems[$CNF['MOBJECT_VOTES']]) && is_array($aVoteSystems[$CNF['MOBJECT_VOTES']])) {
    		$iRateMin = $aVoteSystems[$CNF['MOBJECT_VOTES']]['min_value'];
    		$iRateMax = $aVoteSystems[$CNF['MOBJECT_VOTES']]['max_value'];
    	}

    	$bShort = isset($aParams['short']) && $aParams['short'] === true;
    	$sKey = !empty($aParams['key']) ? $aParams['key'] : '';
    	$aInstalled = !empty($aParams['products']) && is_array($aParams['products']) ? $aParams['products'] : array();

    	$aResults = array();
		foreach($aItems as $aItem) {
			$oAuthor = BxDolProfile::getInstance($aItem[$CNF['MFIELD_AUTHOR']]);

			$bFree = (float)$aItem[$CNF['MFIELD_PRICE_SINGLE']] == 0 && (float)$aItem[$CNF['MFIELD_PRICE_RECURRING']] == 0;
			$bPurchased = (isset($aItem['purchased_on']) && (int)$aItem['purchased_on'] != 0) || $bForcePurchased;
			$bDownloadable = ($bFree || $bPurchased) && (int)$aItem['file_id'] != 0;

			/**
			 * Associate license with Key if App is installed and user has non-associated license for this App.
			 */
			$bPurchasedAndAvailable = $bPurchased && !empty($aItem['license']) && isset($aItem['purchased_for']) && $aItem['purchased_for'] == '';
			if(array_key_exists($aItem[$CNF['MFIELD_NAME']], $aInstalled) && $bPurchasedAndAvailable)
				if(BxDolService::call($CNF['MARKET'], 'update_license', array(array('domain' => $sKey), array('license' => $aItem['license']))))
					$aItem['purchased_for'] = $sKey;

			$aDiscount = array();
			/*
			//TODO: Process discount if the feature was added.
			if(!$bFree && $aItem[$CNF['MFIELD_PRICE_DISCOUNT']] != 0) {
				$aDiscount = array(
					'percent' => $aItem[$CNF['MFIELD_PRICE_DISCOUNT']], 
					'save' => round($aItem[$CNF['MFIELD_PRICE_DISCOUNT']] * $aItem[$CNF['MFIELD_PRICE_SINGLE']] / 100, 2),
					'price' => round((1 - $CNF['MFIELD_PRICE_DISCOUNT'] / 100) * $aItem[$CNF['MFIELD_PRICE_SINGLE']], 2)
				);
			}
			*/

			if($bShort)
				$aResult = array(
					'name' => $aItem[$CNF['MFIELD_NAME']],
				);
			else
				$aResult = array(
					'id' => $aItem[$CNF['MFIELD_ID']],
					'name' => $aItem[$CNF['MFIELD_NAME']],
					'title' => $aItem[$CNF['MFIELD_TITLE']],
					'description' => strip_tags($aItem[$CNF['MFIELD_TEXT']]),
					'description_plain' => BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($aItem[$CNF['MFIELD_TEXT']]), (int)getParam($CNF['PARAM_PLAIN_DESCRIPTION_CHARS'])),
					'url' => BX_DOL_URL_ROOT . $oPermalink->permalink($CNF['MURL_VIEW_ENTRY'] . $aItem[$CNF['MFIELD_ID']]),
					'thumbnail' => BxDolService::call($CNF['MARKET'], 'get_thumbnail', array($aItem[$CNF['MFIELD_THUMB']])),
					//--- Single price related fields
					'price_single' => $aItem[$CNF['MFIELD_PRICE_SINGLE']],
					'discount_single' => $aDiscount,
					//--- Recurring price related fields
					'price_recurring' => $aItem[$CNF['MFIELD_PRICE_RECURRING']],
		    		'duration_recurring' => $aItem[$CNF['MFIELD_DURATION_RECURRING']],
					'views_cnt' => $aItem[$CNF['MFIELD_VIEWS']],
					'votes_cnt' => $aItem[$CNF['MFIELD_VOTES']],
					'rate' => $aItem[$CNF['MFIELD_RATE']],
					'rate_min' => $iRateMin,
					'rate_max' => $iRateMax,
					'created_date' => $aItem[$CNF['MFIELD_ADDED']],
					'author_id' => $aItem[$CNF['MFIELD_AUTHOR']],
					'author_name' => $oAuthor->getDisplayName(),
					'author_url' => $oAuthor->getUrl(),
					'author_currency_code' => $aCurrency['code'],
					'author_currency_sign' => $aCurrency['sign'],
					'is_file' => $bDownloadable ? 1 : 0,
					'file_id' => $bDownloadable ? $aItem['file_id'] : 0,
					'file_version' => $bDownloadable ? $aItem['file_version'] : '',
					'file_version_to' => $bDownloadable && isset($aItem['file_version_to']) ? $aItem['file_version_to'] : '',
					'is_free' => $bFree ? 1 : 0,
					'is_purchased' => $bPurchased ? 1 : 0,
					'purchased_for' => $bPurchased && isset($aItem['purchased_for']) ? $aItem['purchased_for'] : '',
					'purchased_on' => $bPurchased && isset($aItem['purchased_on']) ? $aItem['purchased_on'] : 0,
					'license' => $bPurchased && isset($aItem['license']) ? $aItem['license'] : ''
				);

			if($bPurchased && !empty($aItem['purchased_by']))
				$aResult['hash'] = $this->_getHash((int)$aItem[$CNF['MFIELD_ID']], $aItem['purchased_by'], $sKey);

			$aResults[] = $aResult; 
		}

		$aItems = $aResults;
    }

    protected function _getHash($iProductId, $iClientId, $sClientKey)
    {
    	if(empty($iClientId))
    		return '';

		$aInfo = array(
			'product' => $iProductId,
			'client_ip' => $_SERVER['REMOTE_ADDR'],
			'client_key' => $sClientKey,
			'salt' => $iClientId
		);

    	return md5(implode('-', $aInfo));
    }

    protected function _getFile($iId)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aFile = BxDolService::call($CNF['MARKET'], 'get_file', array($iId));
    	$aEntry = BxDolService::call($CNF['MARKET'], 'get_entry_by', array('id', $aFile['content_id']));

    	if(empty($aFile) || !is_array($aFile) || empty($aEntry) || !is_array($aEntry))
    		return array();

		return array(
			'module_name' => $aEntry[$CNF['MFIELD_NAME']],
			'id' => $aFile['file_id'],
			'name' => $aFile['file_name'],
			'size' => $aFile['file_size'],
			'content' => urlencode(bx_file_get_contents($aFile['file_url']))
		);    	
    }

    protected function _getUpdate($iClient, $aEntry, $sKey, $bAuthorized = true)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aEntryInfo = BxDolService::call($CNF['MARKET'], 'get_entry_by', array('name', $aEntry[$CNF['MFIELD_NAME']]));
		if(empty($aEntryInfo) || !is_array($aEntryInfo) || version_compare($aEntryInfo['file_version'], $aEntry['version']) == 0)
			return array();

		$bFree = (float)$aEntryInfo[$CNF['MFIELD_PRICE_SINGLE']] == 0 && (float)$aEntryInfo[$CNF['MFIELD_PRICE_RECURRING']] == 0;
		$bGranted = in_array($aEntryInfo[$CNF['MFIELD_ID']], $this->_getGranted($sKey));
		if(!$bAuthorized && !$bFree && !$bGranted && strcmp($aEntry['hash'],  $this->_getHash($aEntryInfo[$CNF['MFIELD_ID']], $iClient, $sKey)) != 0)
			return array();

		if(!$bFree && !$bGranted && !BxDolService::call($CNF['MARKET'], 'has_license', array($iClient, $aEntryInfo[$CNF['MFIELD_ID']], $sKey)))
			return array();

		$aUpdate = BxDolService::call($CNF['MARKET'], 'get_updates', array($aEntryInfo[$CNF['MFIELD_ID']], $aEntry['version']));
		if(empty($aUpdate) || !is_array($aUpdate)) 
			return array();

		return array_merge($aEntryInfo, array(
			'file_id' => $aUpdate['file_id'],
			'file_name' => $aUpdate['file_name'],
			'file_version' => $aUpdate['version'], 						
			'file_version_to' => $aUpdate['version_to'],
		));
    }

    protected function _getGranted($sKey)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$aOauthClient = $this->_getOauthClientByKey($sKey);
    	if(empty($aOauthClient) || empty($aOauthClient['parent_id']))
    		return array();

    	$iParent = (int)$aOauthClient['parent_id'];
		$oParent = BxDolProfile::getInstance($iParent);
		if(!$oParent)
			return array();

		$aLicense = BxDolService::call($CNF['MARKET'], 'get_license', array(array('type' => 'profile_id', 'profile_id' => $iParent)));
    	if(empty($aLicense) || !is_array($aLicense))
    		return array();
    		
		$aLicenseInfo = $this->_oDb->getLicense(array(
			'type' => 'license_id_profile_id',
			'license_id' => $aLicense['id'],
			'profile_id' => $iParent));
		if(empty($aLicenseInfo) || !is_array($aLicenseInfo) || $aLicenseInfo[$CNF['FIELD_TYPE']] != BX_MARKET_API_LICENSE_TYPE_NETWORK)
			return array();

		$aEntries = BxDolService::call($CNF['MARKET'], 'get_entries_by', array(array(
			'type' => 'vendor',
			'value' => $this->_oConfig->getMarketMainSellerId()
		)));
		if(empty($aEntries) || !is_array($aEntries))
			return array();

		foreach($aEntries as $aEntry)
			$aResult[] = $aEntry['id'];

    	return $aResult;
    }

    protected function _getClientByKey($sKey)
    {
		$aOauthClient = $this->_getOauthClientByKey($sKey);

		return !empty($aOauthClient['user_id']) ? (int)$aOauthClient['user_id'] : 0;
    }

	protected function _getOauthClientByKey($sKey)
	{
    	return BxDolService::call($this->_oConfig->CNF['OAUTH'], 'get_clients_by', array(array('type' => 'client_id', 'client_id' => $sKey)));
    }
}

/** @} */
