<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
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

    protected $_sApiId;
    protected $_sApiPublicKey;
    protected $_sApiSecretKey;

	protected $_sLogFile;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
	        // module icon
            'ICON' => 'cc-stripe col-blue1',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'accounts',

        	// page URIs
        	'URL_API_AUTHORIZE' => 'https://connect.stripe.com/oauth/authorize',
        	'URL_API_DEAUTHORIZE' => 'https://connect.stripe.com/oauth/deauthorize',
        	'URL_API_TOKEN' => 'https://connect.stripe.com/oauth/token',

        	'URI_REDIRECT' => 'result',
        	'URI_NOTIFY' => 'notify',

	        // some params
	        'PARAM_MODE' => 'bx_stripe_connect_mode',
            'PARAM_API_ID_LIVE' => 'bx_stripe_connect_api_id_live',
        	'PARAM_API_PUBLIC_LIVE' => 'bx_stripe_connect_api_public_live',
        	'PARAM_API_SECRET_LIVE' => 'bx_stripe_connect_api_secret_live',
        	'PARAM_API_ID_TEST' => 'bx_stripe_connect_api_id_test',
        	'PARAM_API_PUBLIC_TEST' => 'bx_stripe_connect_api_public_test',
        	'PARAM_API_SECRET_TEST' => 'bx_stripe_connect_api_secret_test',
        	'PARAM_API_SCOPE' => 'bx_stripe_connect_api_scope',
        	'PARAM_PMODE_SINGLE' => 'bx_stripe_connect_pmode_single',
        	'PARAM_FEE_SINGLE' => 'bx_stripe_connect_fee_single',
        	'PARAM_PMODE_RECURRING' => 'bx_stripe_connect_pmode_recurring',
        	'PARAM_FEE_RECURRING' => 'bx_stripe_connect_fee_recurring',

	        // objects
        	'OBJECT_GRID_ACCOUNTS' => 'bx_stripe_connect_accounts',

	        // Related Stripe payment provider name in Payments module 
        	'STRIPE' => 'stripe',
        );

        $this->_aJsClasses = array(
        	'main' => 'BxStripeConnectMain',
        );

        $this->_aJsObjects = array(
        	'main' => 'oStripeConnectMain',
        );

        $this->_sLogFile = BX_DIRECTORY_PATH_LOGS . 'bx_stripe_connect.log';
    }

    public function init(&$oDb)
    {
    	$this->_oDb = &$oDb;
        $sOptionPrefix = $this->getName();

    	$this->_sMode = $this->_oDb->getParam($this->CNF['PARAM_MODE']);
    	$this->_sApiId = $this->_oDb->getParam($this->CNF['PARAM_API_ID_' . ($this->_sMode == BX_STRIPE_CONNECT_MODE_LIVE ? 'LIVE' : 'TEST')]);
    	$this->_sApiPublicKey = $this->_oDb->getParam($this->CNF['PARAM_API_PUBLIC_' . ($this->_sMode == BX_STRIPE_CONNECT_MODE_LIVE ? 'LIVE' : 'TEST')]);
    	$this->_sApiSecretKey = $this->_oDb->getParam($this->CNF['PARAM_API_SECRET_' . ($this->_sMode == BX_STRIPE_CONNECT_MODE_LIVE ? 'LIVE' : 'TEST')]);
    }

    public function getMode()
    {
        return $this->_sMode;
    }

    public function getApiId()
	{
		return $this->_sApiId;
	}

    public function getApiPublicKey()
	{
		return $this->_sApiPublicKey;
	}

	public function getApiSecretKey()
	{
		return $this->_sApiSecretKey;
	}

    public function getPayMode($sType)
    {
        $sResult = '';

        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                $sResult = getParam($this->CNF['PARAM_PMODE_SINGLE']);
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $sResult = getParam($this->CNF['PARAM_PMODE_RECURRING']);
                break;
        }

        return $sResult;
    }

    public function getFee($sType, $fAmount = 0)
    {
        $iResult = 0;

        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                $mixedValue = getParam($this->CNF['PARAM_FEE_SINGLE']);
                if(is_numeric($mixedValue))
                    $iResult = (int)$mixedValue;
                else if(strpos($mixedValue, '%') !== false)
                    $iResult = (int)round($fAmount * (int)trim($mixedValue, '%') / 100);
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $iResult = (int)trim(getParam($this->CNF['PARAM_FEE_RECURRING']), '%');
                break;
        }

        return $iResult;
    }

    public function getLogFile()
    {
    	return $this->_sLogFile;
    }
}

/** @} */
