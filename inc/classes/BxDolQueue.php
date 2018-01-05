<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolQueue extends BxDolFactory
{
    protected $_oQuery;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
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
	 * Send some number of email form mail queue
	 *
	 * @param int $iLimit - number of emails to send
	 * @return real number of sent emails
	 */
    public function send($iLimit = 1)
    {
        $aSent = array();

    	$aMails = $this->_oQuery->getItems(array('type' => 'to_send', 'start' => 0, 'per_page' => $iLimit));
    	foreach($aMails as $iId => $aMail)
    	    if(call_user_func_array(array($this, '_send'), array_slice($aMail, 1)))
    	        $aSent[] = $iId;

        if(!empty($aSent) && is_array($aSent))
            $this->_oQuery->deleteItem($aSent);

    	return count($aSent);
    }
}

/** @} */
