<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolQueueEmail extends BxDolQueue implements iBxDolSingleton
{
    protected function __construct()
    {
        parent::__construct();

        $this->_oQuery = new BxDolQueueEmailQuery();
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolQueueEmail();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Add message to mail queue
     *
     * @param string $sEmail - recipient email
     * @param string $sSubject - message subject
     * @param string $sBody - message body
     * @return true on success, false on error
     */
    public function add($sEmail, $sSubject, $sBody, $sHeaders = '', $sParams = '')
    {
        return (int)$this->_oQuery->insertItem(array(
        	'email' => $sEmail,
            'subject' => $sSubject,
            'body' => $sBody,
            'headers' => $sHeaders,
        	'params' => $sParams
        )) > 0;
    }

    /**
     * Internal method which performs sending using predefined list of params.
     */
    protected function _send($sEmail, $sSubject, $sBody, $sHeaders = '', $sParams = '')
    {
        return mail($sEmail, $sSubject, $sBody, $sHeaders, $sParams);
    }
}

/** @} */
