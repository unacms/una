<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Donations Donations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDonationsConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;
    protected $_aHtmlIds;

    protected $_iOwner;
    protected $_bShowTitle;
    protected $_bEnableOther;
    protected $_aBillingTypes;
    protected $_aPeriodUnits;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
            // module icon
            'ICON' => 'donate col-blue3',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'entries',
            'TABLE_ENTRIES_DELETED' => $aModule['db_prefix'] . 'entries_deleted',
            'TABLE_TYPES' => $aModule['db_prefix'] . 'types',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_NAME' => 'name',
            'FIELD_TITLE' => 'title',
            'FIELD_PERIOD' => 'period',
            'FIELD_PERIOD_UNIT' => 'period_unit',
            'FIELD_AMOUNT' => 'amount',
            'FIELD_CUSTOM' => 'custom',

            // page URIs
            'URL_MAKE' => 'page.php?i=donations-make',
            'URL_LIST' => 'page.php?i=donations-list',
            'URL_LIST_ALL' => 'page.php?i=donations-list-all',

            // some params
            'PARAM_SHOW_TITLE' => 'bx_donations_show_title',
            'PARAM_ENABLE_OTHER' => 'bx_donations_enable_other',
            'PARAM_AMOUNT_PRECISION' => 'bx_donations_amount_precision',

            'PARAM_OTHER_NAME' => 'other',
            'PARAM_OTHER_PERIOD' => 1,
            'PARAM_OTHER_PERIOD_UNIT' => 'month',
            'PARAM_OTHER_PRICE_MIN' => 5,

            // objects 
            'OBJECT_GRID_LIST' => 'bx_donations_list',
            'OBJECT_GRID_LIST_ALL' => 'bx_donations_list_all',
            'OBJECT_GRID_TYPES' => 'bx_donations_types',
            'OBJECT_FORM_TYPE' => 'bx_donations_type',
            'OBJECT_FORM_TYPE_DISPLAY_ADD' => 'bx_donations_type_add',
            'OBJECT_FORM_TYPE_DISPLAY_EDIT' => 'bx_donations_type_edit',
            'OBJECT_FORM_PRELISTS_PERIOD_UNITS' => 'bx_donations_period_units',
            'OBJECT_MENU_LIST_SUBMENU' => 'bx_donations_list_submenu',

            // email templates
            'ETEMPLATE_DONATED' => 'bx_donations_donated',
        );

        $this->_aJsClasses = array(
            'main' => 'BxDonationsMain',
            'form' => 'BxDonationsForm',
        );

        $this->_aJsObjects = array(
            'main' => 'oBxDonationsMain',
            'form' => 'oBxDonationsForm',
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
            'popup_type' => $sHtmlPrefix . '-popup-type',
            'menu_billing_types' => $sHtmlPrefix . '-menu-billing_types',
            'link_billing_type' => $sHtmlPrefix . '-link-billing-',
        );

        $oPayments = BxDolPayments::getInstance();
        $this->_iOwner = (int)$oPayments->getOption('site_admin');

        $this->_aBillingTypes = array(
            BX_DONATIONS_BTYPE_SINGLE,
            BX_DONATIONS_BTYPE_RECURRING
        );

        $this->_aPeriodUnits = BxDolForm::getDataItems($this->CNF['OBJECT_FORM_PRELISTS_PERIOD_UNITS']);
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        $this->_bShowTitle = $this->_oDb->getParam($this->CNF['PARAM_SHOW_TITLE']) == 'on';
        $this->_bEnableOther = $this->_oDb->getParam($this->CNF['PARAM_ENABLE_OTHER']) == 'on';
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

    public function isShowTitle()
    {
        return $this->_bShowTitle;
    }

    public function isEnableOther()
    {
        return $this->_bEnableOther;
    }

    public function getBillingTypes()
    {
        return $this->_aBillingTypes;
    }

    public function getPeriodUnits()
    {
        return $this->_aPeriodUnits;
    }

    public function getTypeName($sName)
    {
        return uriGenerate($sName, $this->CNF['TABLE_TYPES'], $this->CNF['FIELD_NAME'], ['lowercase' => false]);
    }

    public function getTypeNameCustom()
    {
        return $this->getTypeName($this->CNF['PARAM_OTHER_NAME'] . '_' . bx_get_logged_profile_id());
    }
}

/** @} */
