<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModPaymentConfig extends BxBaseModGeneralConfig
{
	protected $_oDb;

	protected $_sCurrencySign;
    protected $_sCurrencyCode;

    protected $_aPerPage;
    protected $_aHtmlIds;

    protected $_sAnimationEffect;
    protected $_iAnimationSpeed;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array_merge($this->CNF, array(
        	'OBJECT_FORM_PRELISTS_CURRENCIES' => '',

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

			'menu_dashboard' => 'sys_account_dashboard',
			'menu_cart_submenu' => $this->_sName . '_menu_cart_submenu',
        	'menu_orders_submenu' => $this->_sName . '_menu_orders_submenu',
    		'menu_sbs_submenu' => $this->_sName . '_menu_sbs_submenu',
		    'menu_sbs_actions' => $this->_sName . '_menu_sbs_actions',

			'grid_history' => $this->_sName . '_grid_orders_history',
			'grid_processed' => $this->_sName . '_grid_orders_processed',
			'grid_pending' => $this->_sName . '_grid_orders_pending',
			'grid_carts' => $this->_sName . '_grid_carts',
			'grid_cart' => $this->_sName . '_grid_cart',
			'grid_sbs_list_my' => $this->_sName . '_grid_sbs_list_my',
			'grid_sbs_list_all' => $this->_sName . '_grid_sbs_list_all',
			'grid_sbs_history' => $this->_sName . '_grid_sbs_history',
		));

		$this->_aPerPage = array();
		$this->_aHtmlIds = array();

		$this->_sAnimationEffect = 'fade';
        $this->_iAnimationSpeed = 'slow';
    }

    public function init(&$oDb)
    {
    	$this->_oDb = $oDb;
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

		if(strncmp($this->CNF[$sType], BX_DOL_URL_ROOT, strlen(BX_DOL_URL_ROOT)) === 0)
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
