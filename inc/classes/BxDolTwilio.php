<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolTwilio extends BxDolFactory implements iBxDolSingleton
{
    protected $_sSid;
    protected $_sToken;
    protected $_sFromNumber;
    
    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_sSid = getParam('sys_twilio_gate_sid');
        $this->_sToken = getParam('sys_twilio_gate_token');
        $this->_sFromNumber = getParam('sys_twilio_gate_from_number');
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolTwilio();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }
    
    public function sendSms($sTo, $sMessage, $sFrom = '')
    {
        try{
            require_once BX_DIRECTORY_PATH_PLUGINS . 'Twilio/sdk/src/Twilio/autoload.php'; // Loads the library
            $client = new Twilio\Rest\Client($this->_sSid, $this->_sToken);
            $aParams = array('body' => $sMessage, 'from' => $sFrom != '' ? $this->normalizePhone($sFrom) : $this->normalizePhone($this->_sFromNumber));
            $client->messages->create($this->normalizePhone($sTo), $aParams);
            return true;
        }
        catch (Exception $oException) {
            $this->writeLog($oException->getFile() . ':' . $oException->getLine() . ' ' . $oException->getMessage());
            return false;
        }
    }
    
    public function normalizePhone($sPhone){
		$sPhone = trim($sPhone);
		if (substr($sPhone, 0, 1) != '+'){
			$sPhone = '+' . $sPhone;	
		}
		return $sPhone;
	}
    
    private function writeLog($sString)
	{
        bx_log('sys_twilio', $sString);
	}
}

/** @} */
