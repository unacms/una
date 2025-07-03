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

/**
 * General docs: https://docs.stripe.com/connect/how-connect-works
 * Testing: https://docs.stripe.com/connect/testing
 */
class BxStripeConnectApi extends BxDol
{
    protected $_sModule;
    protected $_oModule;

    protected $_oStripe;

    public function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_sModule = 'bx_stripe_connect';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_oStripe = null;
    }

    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }
    
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxStripeConnectApi();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
    
    public function getStripe()
    {
        if(empty($this->_oStripe))
            $this->_oStripe = new \Stripe\StripeClient($this->_oModule->_oConfig->getApiSecretKey());

        return $this->_oStripe;
    }

    public function createAccount($sEmail)
    {        
        $oAccount = null;

        try {
            $oAccount = $this->getStripe()->accounts->create([
                'type' => $this->_oModule->_oConfig->getAccountType(),
                'email' => $sEmail,
            ]);
        }
        catch (Exception $oException) {
            $oAccount = null;

            return $this->_processException('Create Account Error: ', $oException);
        }

        return $oAccount;
    }

    public function retrieveAccount($sAccountId)
    {
        try {
            $oAccount = $this->getStripe()->accounts->retrieve($sAccountId, []);
        }
        catch (Exception $oException) {
            return $this->_processException('Retrieve Account Error: ', $oException);
        }

        return $oAccount;
    }
    
    public function deleteAccount($sAccountId)
    {        
        $oAccount = null;

        try {
            $oAccount = $this->getStripe()->accounts->delete($sAccountId, []);
        }
        catch (Exception $oException) {
            $oAccount = null;

            return $this->_processException('Delete Account Error: ', $oException);
        }

        return $oAccount;
    }

    public function createAccountLinks($sAccountId, $sRefreshLink, $sReturnLink)
    {
        $oAccountLink = null;

        try {
            $oAccountLink = $this->getStripe()->accountLinks->create([
                'account' => $sAccountId,
                'refresh_url' => $sRefreshLink,
                'return_url' => $sReturnLink,
                'type' => 'account_onboarding',
            ]);
        }
        catch (Exception $oException) {
            $oAccountLink = null;

            return $this->_processException('Create Account Links Error: ', $oException);
        }

        return $oAccountLink;
    }

    /**
     * https://docs.stripe.com/connect/get-started-connect-embedded-components
     */
    public function createAccountSessions($sAccountId)
    {
        $oAccountSession = null;

        try {
            $oAccountSession = $this->getStripe()->accountSessions->create([
                'account' => $sAccountId,
                'components' => [
                    'balances' => [
                        'enabled' => true,
                        'features' => [
                            'instant_payouts' => true,
                            'standard_payouts' => true,
                            'edit_payout_schedule' => true,
                            'external_account_collection' => true
                        ],
                    ],
                    'notification_banner' => [
                        'enabled' => true,
                        'features' => [
                            'external_account_collection' => true
                        ]
                    ],
                    'payments' => [
                        'enabled' => true,
                        'features' => [
                            'refund_management' => true,
                            'dispute_management' => true,
                            'capture_payments' => true,
                        ],
                    ],
                    /*
                    'reporting_chart' => [
                        'enabled' => true
                    ],
                    */
                ]
            ]);
        }
        catch (Exception $oException) {
            $oAccountSession = null;

            return $this->_processException('Create Account Session Error: ', $oException);
        }

        return $oAccountSession;
    }

    protected function _processException($sMessage, &$oException)
    {
        if(method_exists($oException, 'getError')) {
            $sError = $oException->getError()->message;
            $aError = $oException->getError()->toArray();
        }
        else { 
            $sError = $oException->getMessage();
            $aError = array();
        }

        $this->_log($sMessage . $sError);
        if(!empty($aError))
            $this->_log($aError);

        return false;
    }

    protected function _log($sContents)
    {
        if (is_array($sContents))
            $sContents = var_export($sContents, true);	
        else if (is_object($sContents))
            $sContents = json_encode($sContents);

        bx_log('bx_stripe_connect', $sContents);
    }
}

/** @} */
