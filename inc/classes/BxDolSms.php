<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolSms extends BxDolFactoryObject
{
    protected function __construct($aObject, $oTemplate = null, $sDbClassName = 'BxDolSmsQuery')
    {
        parent::__construct($aObject, $oTemplate, $sDbClassName);
    }

    /**
     * Get SMS provider object instance by object name
     * @param $sObject object name
     * @return object instance or false on error
     */
    static public function getObjectInstance($sObject = false, $oTemplate = false)
    {
        if(!$sObject)
            $sObject = getParam('sys_sms_default');
        if(!$sObject)
            return false;

        return parent::getObjectInstanceByClassNames($sObject, $oTemplate, 'BxDolSms', 'BxDolSmsQuery');
    }

    public function normalizePhone($sPhone){
        $sPhone = trim($sPhone);
        if(substr($sPhone, 0, 1) != '+')
            $sPhone = '+' . $sPhone;

        return $sPhone;
    }

    public function send($sTo, $sMessage, $sFrom = '') {}

    protected function _writeLog($sString)
    {
        bx_log('sys_sms', $sString);
    }
}

/** @} */
