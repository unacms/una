<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    PaidLevels Paid Levels
 * @ingroup     UnaModules
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
            // database tables
            'TABLE_PRICES' => $aModule['db_prefix'] . 'level_prices',
            'TABLE_LICENSES' => $aModule['db_prefix'] . 'licenses',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_LEVEL_ID' => 'level_id',
            'FIELD_NAME' => 'name',

            // page URIs
            'URL_ADMINISTRATION' => 'page.php?i=acl-administration',
            'URL_VIEW' => 'page.php?i=acl-view',

            // some params
            'PARAM_DATE_FORMAT' => 'd.m.Y',
            'PARAM_RECURRING_RESERVE' => 'bx_acl_recurring_reserve',
            'PARAM_RECURRING_PRIORITIZE' => 'bx_acl_recurring_prioritize',

            // objects 
            'OBJECT_GRID_ADMINISTRATION' => 'bx_acl_administration',
            'OBJECT_GRID_VIEW' => 'bx_acl_view',
            'OBJECT_FORM_PRICE' => 'bx_acl_price',
            'OBJECT_FORM_PRICE_DISPLAY_ADD' => 'bx_acl_price_add',
            'OBJECT_FORM_PRICE_DISPLAY_EDIT' => 'bx_acl_price_edit',
            'OBJECT_FORM_PRELISTS_PERIOD_UNITS' => 'bx_acl_period_units',

            // email templates
            'ETEMPLATE_SBS_CANCEL_REQUIRED' => 'bx_acl_subscription_cancel_required',
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
            'form' => 'BxAclForm',
            'administration' => 'BxAclAdministration',
        );
        $this->_aJsObjects = array(
            'main' => 'oAclMain',
            'form' => 'oAclForm',
            'administration' => 'oAclAdministration',
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
            'popup_price' => $sHtmlPrefix . '-popup-price'
        );

        $this->_iOwner = 0;
        $this->_aCurrency = [];

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
        if(empty($this->_iOwner))
            $this->_iOwner = (int)BxDolPayments::getInstance()->getOption('site_admin');

    	return $this->_iOwner;
    }

    public function getCurrency()
    {
        if(empty($this->_aCurrency) || !is_array($this->_aCurrency))
            $this->_aCurrency = BxDolPayments::getInstance()->getCurrencyInfo($this->getOwner());

    	return $this->_aCurrency;
    }

    public function getPriceName($sName)
    {
        return uriGenerate($sName, $this->CNF['TABLE_PRICES'], $this->CNF['FIELD_NAME'], ['lowercase' => false]);
    }

    public function formatDate($iTs)
    {
        return gmdate($this->CNF['PARAM_DATE_FORMAT'], $iTs);
    }
}

/** @} */
