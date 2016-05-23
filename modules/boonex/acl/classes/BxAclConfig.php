<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ACL ACL
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAclConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;
    protected $_aHtmlIds;
    protected $_aCurrency;
    protected $_iOwner;

    protected $_iExpireNotificationDays;
    protected $_bExpireNotifyOnce;
    protected $_iRemoveExpiredFor;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
        	'TABLE_PRICES' => $aModule['db_prefix'] . 'level_prices',

        	'URL_ADMINISTRATION' => 'page.php?i=acl-administration',
        	'URL_VIEW' => 'page.php?i=acl-view',

        	'OBJECT_GRID_ADMINISTRATION' => 'bx_acl_administration',
        	'OBJECT_GRID_VIEW' => 'bx_acl_view',
        	'OBJECT_FORM_PRICE' => 'bx_acl_price',
            'OBJECT_FORM_PRICE_DISPLAY_ADD' => 'bx_acl_price_add',
        	'OBJECT_FORM_PRICE_DISPLAY_EDIT' => 'bx_acl_price_edit',
        );

		$this->_aGridObjects = array(
			'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
			'view' => $this->CNF['OBJECT_GRID_VIEW'],
		);

		$this->_aPrefixes = array(
        	'style' => 'bx-acl',
        	'option' => 'bx_acl_',
        );

        $this->_aJsClasses = array(
        	'main' => 'BxAclMain',
        	'administration' => 'BxAclAdministration',
        );
        $this->_aJsObjects = array(
            'main' => 'oAclMain',
	        'administration' => 'oAclAdministration',
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
        	'popup_price' => $sHtmlPrefix . '-popup-price'
        );

		$oPayments = BxDolPayments::getInstance();
		$this->_iOwner = (int)$oPayments->getOption('site_admin');
        $this->_aCurrency = array(
        	'code' => $oPayments->getOption('default_currency_code'),
        	'sign' => $oPayments->getOption('default_currency_sign')
        );

		$this->_bExpireNotifyOnce = true;
        $this->_iExpireNotificationDays = 1;
        $this->_iRemoveExpiredFor = 30;
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        $sOptionPrefix = $this->getPrefix('option');

        $this->_iExpireNotificationDays = (int)getParam($sOptionPrefix . 'expire_notification_days');
        $this->_bExpireNotifyOnce = getParam($sOptionPrefix . 'expire_notify_once') == 'on';
        $this->_iRemoveExpiredFor = (int)getParam($sOptionPrefix . 'remove_expired_for');
    }

	public function getExpireNotificationDays()
    {
        return $this->_iExpireNotificationDays;
    }

	public function isExpireNotifyOnce()
    {
        return $this->_bExpireNotifyOnce;
    }

	public function getRemoveExpiredFor()
    {
        return $this->_iRemoveExpiredFor;
    }

	public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }

    public function getOwner()
    {
    	return $this->_iOwner;
    }

    public function getCurrency()
    {
    	return $this->_aCurrency;
    }
}

/** @} */
