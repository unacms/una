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

    protected $_aKeys;
    protected $_aUrls;
    protected $_aDividers;

    protected $_aPerPage;
    protected $_aHtmlIds;

    protected $_sAnimationEffect;
    protected $_iAnimationSpeed;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aKeys = array(
        	'session_key_pending' => $this->_sName . '_pending_id',
        	'request_key_pending' => $this->_sName . '_pending_id'
        );

        $this->_aUrls = array();

        $this->_aDividers = array(
        	'descriptor' => '_',
        	'descriptors' => ':'
        );

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
			'grid_pending' => $this->_sName . '_grid_orders_pending'
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
        $this->_sCurrencySign = $this->_oDb->getParam($sPrefix . 'default_currency_sign');
        $this->_sCurrencyCode = $this->_oDb->getParam($sPrefix . 'default_currency_code');
    }

    public function getSiteId()
    {
        if(empty($this->_iSiteId))
            return $this->_oDb->getFirstAdminId();

        return $this->_iSiteId;
    }

    public function getCurrencySign()
    {
        return $this->_sCurrencySign;
    }

    public function getCurrencyCode()
    {
        return $this->_sCurrencyCode;
    }

	public function getKey($sType)
    {
    	if(empty($sType))
            return $this->_aKeys;

        return isset($this->_aKeys[$sType]) ? $this->_aKeys[$sType] : '';
    }

    public function getUrl($sType, $aParams = array(), $bSsl = false)
    {
    	if(empty($sType))
            return $this->_aUrls;

		$sResult = '';
		if(!isset($this->_aUrls[$sType]))
			return $sResult;

		if(strncmp($this->_aUrls[$sType], BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)) == 0)
			$sResult = bx_append_url_params($this->_aUrls[$sType], $aParams);
		else
			$sResult = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($this->_aUrls[$sType], $aParams);

    	return $bSsl ? $this->http2https($sResult) : $sResult;
    }

	public function getDivider($sType)
    {
    	if(empty($sType))
            return $this->_aDividers;

        return isset($this->_aDividers[$sType]) ? $this->_aDividers[$sType] : '';
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
