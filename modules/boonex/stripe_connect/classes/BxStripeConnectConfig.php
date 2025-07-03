<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

class BxStripeConnectConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    protected $_sMode;

    protected $_sApiPublicKey;
    protected $_sApiSecretKey;
    protected $_sAccountType;
    
    protected $_sPayMode;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = [
            // module icon
            'ICON' => 'cc-stripe col-blue1',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'accounts',
            'TABLE_COMMISSIONS' => $aModule['db_prefix'] . 'commissions',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_PROFILE_ID' => 'profile_id',
            'FIELD_LIVE_ACCOUNT_ID' => 'live_account_id',
            'FIELD_LIVE_DETAILS' => 'live_details',
            'FIELD_TEST_ACCOUNT_ID' => 'test_account_id',
            'FIELD_TEST_DETAILS' => 'test_details',

            'FIELD_CMS_ID' => 'id',
            'FIELD_CMS_NAME' => 'name',
            'FIELD_CMS_ACL_ID' => 'acl_id',
            'FIELD_CMS_FEE_SINGLE' => 'fee_single',
            'FIELD_CMS_FEE_RECURRING' => 'fee_recurring',

            // page URIs
            'URL_API_AUTHORIZE' => 'https://connect.stripe.com/oauth/authorize',
            'URL_API_DEAUTHORIZE' => 'https://connect.stripe.com/oauth/deauthorize',
            'URL_API_TOKEN' => 'https://connect.stripe.com/oauth/token',

            'URI_REDIRECT' => 'result',
            'URI_NOTIFY' => 'notify',

            // some params
            'PARAM_MODE' => 'bx_stripe_connect_mode',
            'PARAM_API_PUBLIC_LIVE' => 'bx_stripe_connect_api_public_live',
            'PARAM_API_SECRET_LIVE' => 'bx_stripe_connect_api_secret_live',
            'PARAM_API_PUBLIC_TEST' => 'bx_stripe_connect_api_public_test',
            'PARAM_API_SECRET_TEST' => 'bx_stripe_connect_api_secret_test',
            'PARAM_PMODE' => 'bx_stripe_connect_pmode',
            'PARAM_FEE_SINGLE' => 'bx_stripe_connect_fee_single',
            'PARAM_FEE_RECURRING' => 'bx_stripe_connect_fee_recurring',

            // objects
            'OBJECT_FORM_COMMISSIONS' => 'bx_stripe_connect_form_commissions',
            'OBJECT_FORM_COMMISSIONS_DISPLAY_ADD' => 'bx_stripe_connect_form_commissions_add',
            'OBJECT_FORM_COMMISSIONS_DISPLAY_EDIT' => 'bx_stripe_connect_form_commissions_edit',
            'OBJECT_GRID_ACCOUNTS' => 'bx_stripe_connect_accounts',
            'OBJECT_GRID_COMMISSIONS' => 'bx_stripe_connect_commissions',

            // Related Stripe payment provider name in Payments module 
            'STRIPE' => 'stripe_connect',
        ];

        $this->_aJsClasses = [
            'main' => 'BxStripeConnectMain',
            'embeds' => 'BxStripeConnectEmbeds',
        ];

        $this->_aJsObjects = [
            'main' => 'oStripeConnectMain',
            'embeds' => 'oBxStripeConnectEmbeds',
        ];

        $sPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = [
            'balances' => $sPrefix . '-balances',
            'notification-banner' => $sPrefix . '-notification-banner',
            'payments' => $sPrefix . '-payments',
            'reporting-chart' => $sPrefix . '-reporting-chart',

            'commissions_form' => $sPrefix . '-commissions-form-',
            'commissions_popup' => $sPrefix . '-commissions-popup-',
        ];

        $this->_sAccountType = 'standard'; //'express'
    }

    public function init(&$oDb)
    {
    	$this->_oDb = &$oDb;
        $sOptionPrefix = $this->getName();

    	$this->_sMode = $this->_oDb->getParam($this->CNF['PARAM_MODE']);
    	$this->_sApiPublicKey = $this->_oDb->getParam($this->CNF['PARAM_API_PUBLIC_' . ($this->_sMode == BX_STRIPE_CONNECT_MODE_LIVE ? 'LIVE' : 'TEST')]);
    	$this->_sApiSecretKey = $this->_oDb->getParam($this->CNF['PARAM_API_SECRET_' . ($this->_sMode == BX_STRIPE_CONNECT_MODE_LIVE ? 'LIVE' : 'TEST')]);
        $this->_sPayMode = getParam($this->CNF['PARAM_PMODE']);
    }

    public function getMode()
    {
        return $this->_sMode;
    }

    public function getApiPublicKey()
    {
        return $this->_sApiPublicKey;
    }

    public function getApiSecretKey()
    {
        return $this->_sApiSecretKey;
    }

    public function getAccountType()
    {
        return $this->_sAccountType;
    }

    public function getPayMode()
    {
        return $this->_sPayMode;
    }

    public function getFee($sType, $iVendorId, $fAmount = 0)
    {
        $iResult = 0;

        $aVendorCms = [];
        if(($aVendorAcl = BxDolAcl::getInstance()->getMemberMembershipInfo($iVendorId)) && is_array($aVendorAcl))
            $aVendorCms = $this->_oDb->getCommissions(['type' => 'acl_id', 'acl_id' => (int)$aVendorAcl['id']]);
        $bVendorCms = !empty($aVendorCms) && is_array($aVendorCms);

        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                if($bVendorCms && !empty($aVendorCms['fee_single']))
                    $iResult = $this->_getFeeSingle($aVendorCms['fee_single'], $fAmount);

                if(!$iResult && ($mixedFee = getParam($this->CNF['PARAM_FEE_SINGLE'])))
                    $iResult = $this->_getFeeSingle($mixedFee, $fAmount);
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                if($bVendorCms && !empty($aVendorCms['fee_recurring']))
                    $iResult = $this->_getFeeRecurring($aVendorCms['fee_recurring']);

                if(!$iResult && ($mixedFee = getParam($this->CNF['PARAM_FEE_RECURRING'])))
                    $iResult = $this->_getFeeRecurring($mixedFee);
                break;
        }

        return $iResult;
    }

    public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }

    protected function _getFeeSingle($mixedFee, $fAmount = false)
    {
        $iResult = 0;

        if(strpos($mixedFee, '%') !== false) {
            $iResult = (int)trim($mixedFee, '%');
            if($fAmount !== false)
                $iResult = (int)round($fAmount * $iResult / 100);
        }
        else if(is_numeric($mixedFee))
            $iResult = (int)(100 * (float)$mixedFee);

        return $iResult;
    }

    protected function _getFeeRecurring($mixedFee)
    {
        return (int)trim($mixedFee, '%');
    }
}

/** @} */
