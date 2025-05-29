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
