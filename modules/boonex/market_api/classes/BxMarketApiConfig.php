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

class BxMarketApiConfig extends BxBaseModGeneralConfig
{
	protected $_aHtmlIds;
	
    protected $_sAnimationEffect;
    protected $_iAnimationSpeed;

    protected $_sSessionKeysPrefix;

	protected $_aCurrency;
	protected $_aMarket;

	protected $_iMainSellerId;
	protected $_sMainSellerSbsProvider;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (

            // module icon
            'ICON' => 'file-text col-red3',

        	// database tables
            'TABLE_LICENSES' => $aModule['db_prefix'] . 'licenses',

        	// database fields
            'FIELD_ID' => 'id',
        	'FIELD_LICENSE_ID' => 'license_id',
            'FIELD_PROFILE_ID' => 'profile_id',
        	'FIELD_TYPE' => 'type',

        	// page URIs
            'URL_KANDS_MANAGE' => 'page.php?i=kands-manage',

        	// some params
            'PARAM_PLAIN_DESCRIPTION_CHARS' => 'bx_market_api_plain_description_chars',

			// objects
			'OBJECT_GRID_KANDS' => 'bx_market_api_kands',
        	'OBJECT_GRID_KANDS_CLIENTS' => 'bx_market_api_kands_clients',

        	// 'OAuth' module
        	'OAUTH' => 'bx_oauth',

        	// Parent 'Market' module 
        	'MARKET' => 'bx_market',

        	// Parent 'Market' database fields
            'MFIELD_ID' => 'id',
            'MFIELD_AUTHOR' => 'author',
            'MFIELD_ADDED' => 'added',
            'MFIELD_CHANGED' => 'changed',
        	'MFIELD_NAME' => 'name',
            'MFIELD_TITLE' => 'title',
            'MFIELD_TEXT' => 'text',
        	'MFIELD_CATEGORY' => 'cat',
        	'MFIELD_PRICE_SINGLE' => 'price_single',
        	'MFIELD_PRICE_RECURRING' => 'price_recurring',
        	'MFIELD_DURATION_RECURRING' => 'duration_recurring',
        	'MFIELD_THUMB' => 'thumb',
        	'MFIELD_PACKAGE' => 'package',
        	'MFIELD_COMMENTS' => 'comments',
	        'MFIELD_VIEWS' => 'views',
	        'MFIELD_VOTES' => 'votes',
        	'MFIELD_RATE' => 'rate',
        	'MFIELD_FILE_VERSION' => 'version',

	        // Parent 'Market' objects
	        'MOBJECT_VOTES' => 'bx_market',
        	'MOBJECT_CATEGORY' => 'bx_market_cats',

	        // page URIs
        	'MURL_VIEW_ENTRY' => 'page.php?i=view-product&id=',
        );

        $this->_aJsClasses = array(
        	'kands' => 'BxMarketApiKands',
        );

        $this->_aJsObjects = array(
        	'kands' => 'oBxMarketApiKands',
        );

        $this->_aHtmlIds = array(
        	'field_user' => 'bx-market-api-user',
        	'field_user_id' => 'bx-market-api-user-id'
        );

		$this->_sAnimationEffect = 'fade';
        $this->_iAnimationSpeed = 'slow';

        $this->_sSessionKeysPrefix = 'bx_uno_';

        $oPayments = BxDolPayments::getInstance();
        $this->_aCurrency = array(
        	'code' => $oPayments->getOption('default_currency_code'),
        	'sign' => $oPayments->getOption('default_currency_sign')
        );

        $this->_aMarket = BxDolModuleQuery::getInstance()->getModuleByName($this->CNF['MARKET']);

        $this->_iMainSellerId = 2;
        $this->_sMainSellerSbsProvider = 'chargebee';
    }

	public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }

    public function getAnimationEffect()
    {
        return $this->_sAnimationEffect;
    }

    public function getAnimationSpeed()
    {
        return $this->_iAnimationSpeed;
    }

    public function getSessionKeysPrefix()
    {
    	return $this->_sSessionKeysPrefix;
    }

	public function getCurrency()
    {
    	return $this->_aCurrency;
    }

    public function getMarketModuleId()
    {
    	return !empty($this->_aMarket) && is_array($this->_aMarket) ? (int)$this->_aMarket['id'] : 0;
    }

    public function getMarketMainSellerId()
    {
    	return $this->_iMainSellerId;
    }

    public function getMarketMainSellerSbsProvider()
    {
    	return $this->_sMainSellerSbsProvider;
    }
}

/** @} */
