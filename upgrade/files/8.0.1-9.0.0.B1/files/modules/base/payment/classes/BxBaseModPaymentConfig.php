<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModPaymentConfig extends BxBaseModGeneralConfig
{
	protected $_oDb;

	protected $_iSiteId;
	protected $_sCurrencySign;
    protected $_sCurrencyCode;

    protected $_aPerPage;
    protected $_aHtmlIds;

    protected $_sAnimationEffect;
    protected $_iAnimationSpeed;

    protected static $_aCurrencies = array(
    	'AUD' => 'A&#36;', 'CAD' => 'C&#36;', 'EUR' => '&#128;', 'GBP' => '&#163;', 'USD' => '&#36;', 'YEN' => '&#165;'
    );

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array_merge($this->CNF, array(
        	'KEY_SESSION_PENDING' => $this->_sName . '_pending_id',
        	'KEY_REQUEST_PENDING' => $this->_sName . '_pending_id',
        	
        	'DIVIDER_DESCRIPTOR' => '_',
        	'DIVIDER_DESCRIPTORS' => ':',
        ));

		$this->_aPrefixes = array(
			'general' => $this->_sName . '_',
			'langs' => '_' . $this->_sName . '_',
        	'options' => $this->_sName . '_'
		);

		$this->_aObjects = array_merge($this->_aObjects, array(
			'form_pendings' => $this->_sName . '_form_pendings',
			'form_processed' => $this->_sName . '_form_processed',
			'display_pendings_process' => $this->_sName . '_form_pendings_process',
			'display_processed_add' => $this->_sName . '_form_processed_add',

			'menu_cart_submenu' => $this->_sName . '_menu_cart_submenu',
        	'menu_orders_submenu' => $this->_sName . '_menu_orders_submenu',

			'grid_history' => $this->_sName . '_grid_orders_history',
			'grid_processed' => $this->_sName . '_grid_orders_processed',
			'grid_pending' => $this->_sName . '_grid_orders_pending',
			'grid_carts' => $this->_sName . '_grid_carts',
			'grid_cart' => $this->_sName . '_grid_cart',
		));

		$this->_aPerPage = array();
		$this->_aHtmlIds = array();

		$this->_sAnimationEffect = 'fade';
        $this->_iAnimationSpeed = 'slow';
    }

    public function init(&$oDb)
    {
    	$this->_oDb = $oDb;
    	
    	$sPrefix = $this->getPrefix('options');
        $this->_iSiteId = (int)$this->_oDb->getParam($sPrefix . 'site_admin');

        $this->_sCurrencyCode = $this->_oDb->getParam($sPrefix . 'default_currency_code');
        $this->_sCurrencySign = self::$_aCurrencies[$this->_sCurrencyCode];
    }

    public function getSiteId()
    {
        if(empty($this->_iSiteId))
            return $this->_oDb->getFirstAdminId();

        return $this->_iSiteId;
    }

    public function getDefaultCurrencySign()
    {
        return $this->_sCurrencySign;
    }

    public function getDefaultCurrencyCode()
    {
        return $this->_sCurrencyCode;
    }

	public function getKey($sType)
    {
    	$sResult = '';
    	if(empty($sType) || !isset($this->CNF[$sType]))
            return $sResult;

        return $this->CNF[$sType];
    }

    public function getUrl($sType, $aParams = array(), $bSsl = false)
    {
		$sResult = '';
		if(empty($sType) || !isset($this->CNF[$sType]))
			return $sResult;

		if(strncmp($this->CNF[$sType], BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)) == 0)
			$sResult = bx_append_url_params($this->CNF[$sType], $aParams);
		else
			$sResult = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->CNF[$sType], $aParams);

    	return $bSsl ? $this->http2https($sResult) : $sResult;
    }

	public function getDivider($sType)
    {
    	$sResult = '';
    	if(empty($sType) || !isset($this->CNF[$sType]))
            return $sResult;

        return $this->CNF[$sType];
    }

	public function getPerPage($sType = 'default')
    {
    	if(empty($sType))
            return $this->_aPerPage;

        return isset($this->_aPerPage[$sType]) ? $this->_aPerPage[$sType] : '';
    }

	public function getHtmlIds($sType, $sKey = '')
    {
        if(empty($sKey))
            return isset($this->_aHtmlIds[$sType]) ? $this->_aHtmlIds[$sType] : array();

        return isset($this->_aHtmlIds[$sType][$sKey]) ? $this->_aHtmlIds[$sType][$sKey] : '';
    }

    public function getAnimationEffect()
    {
        return $this->_sAnimationEffect;
    }

	public function getAnimationSpeed()
    {
        return $this->_iAnimationSpeed;
    }
}

/** @} */
