<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

require_once BX_DIRECTORY_PATH_PLUGINS . 'twilio/sdk/src/Twilio/autoload.php';

class BxDolSmsTwilio extends BxDolSms
{
    protected $_sSid;
    protected $_sToken;
    protected $_sFromNumber;

    protected function __construct($aObject, $oTemplate = null, $sDbClassName = '')
    {
        parent::__construct($aObject, $oTemplate, $sDbClassName);

        $this->_sSid = getParam('sys_sms_twilio_sid');
        $this->_sToken = getParam('sys_sms_twilio_token');
        $this->_sFromNumber = getParam('sys_sms_twilio_from_number');
    }

    public function send($sTo, $sMessage, $sFrom = '')
    {
        try {
            $oClient = new Twilio\Rest\Client($this->_sSid, $this->_sToken);
            $oClient->messages->create($this->normalizePhone($sTo), [
                'body' => $sMessage, 
                'from' => $sFrom != '' ? $this->normalizePhone($sFrom) : $this->normalizePhone($this->_sFromNumber)
            ]);

            return true;
        }
        catch (Exception $oException) {
            $this->_writeLog($oException->getFile() . ':' . $oException->getLine() . ' ' . $oException->getMessage());

            return false;
        }
    }
}

/** @} */
