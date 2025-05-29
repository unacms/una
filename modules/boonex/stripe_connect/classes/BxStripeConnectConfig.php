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

class BxStripeConnectConfig extends BxBaseModConnectConfig
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

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_PROFILE_ID' => 'profile_id',
            'FIELD_LIVE_ACCOUNT_ID' => 'live_account_id',
            'FIELD_LIVE_DETAILS' => 'live_details',
            'FIELD_TEST_ACCOUNT_ID' => 'test_account_id',
            'FIELD_TEST_DETAILS' => 'test_details',

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
            'OBJECT_GRID_ACCOUNTS' => 'bx_stripe_connect_accounts',

            // Related Stripe payment provider name in Payments module 
            'STRIPE' => 'stripe_connect',
        ];

        $this->_aJsClasses = [
            'main' => 'BxStripeConnectMain',
        ];

        $this->_aJsObjects = [
            'main' => 'oStripeConnectMain',
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

    public function getFee($sType, $fAmount = 0)
    {
        $iResult = 0;

        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                $mixedValue = getParam($this->CNF['PARAM_FEE_SINGLE']);
                if(is_numeric($mixedValue))
                    $iResult = (int)(100 * (float)$mixedValue);
                else if(strpos($mixedValue, '%') !== false)
                    $iResult = (int)round($fAmount * (int)trim($mixedValue, '%') / 100);
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $iResult = (int)trim(getParam($this->CNF['PARAM_FEE_RECURRING']), '%');
                break;
        }

        return $iResult;
    }
}

/** @} */
