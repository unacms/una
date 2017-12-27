<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolEmailQueue extends BxDolFactory implements iBxDolSingleton
{
    protected $_oQuery;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_oQuery = new BxDolEmailQueueQuery();
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
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolEmailQueue();

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
	 * Send some number of email form mail queue
	 *
	 * @param int $iLimit - number of emails to send
	 * @return real number of sent emails
	 */
    function send($iLimit = 1)
    {
        $aSent = array();

    	$aMails = $this->_oQuery->getItems(array('type' => 'to_send', 'start' => 0, 'per_page' => $iLimit));
    	foreach($aMails as $iId => $aMail)
    	    if(call_user_func_array('mail', array_slice($aMail, 1)))
    	        $aSent[] = $iId;

        if(!empty($aSent) && is_array($aSent))
            $this->_oQuery->deleteItem($aSent);

    	return count($aSent);
    }
}

/** @} */
