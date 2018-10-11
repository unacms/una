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

    protected $_sParamTime;
    protected $_iExecutionLiveTime;

    protected $_iLimitSend;
    protected $_iLimitSendPerRecipient;

    protected $_aSentTo;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_sParamTime = '';
        $this->_iExecutionLiveTime = 86400;
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public function getBusy()
    {
        return (int)getParam($this->_sParamTime);
    }

    public function setBusy($iTime = 0)
    {
        setParam($this->_sParamTime, $iTime);
    }

    public function isBusy()
    {
        $iNow = time();
        $iTime = $this->getBusy();

        $bBusy = !empty($iTime) && ($iNow - $iTime) < $this->_iExecutionLiveTime;
        if(!$bBusy)
            $this->setBusy($iNow);

        return $bBusy;
    }

    /**
     * Send some number of items (email/push) from queue
     *
     * @param int $iLimit - number of queue items to send
     * @return real number of sent queue items or false, if sending process was already started with a previous call and wasn't finished yet.
     */
    public function send($iLimit = 0)
    {
        if($this->isBusy())
            return false;

        $aSent = array();

        if(empty($iLimit))
            $iLimit = $this->_iLimitSend;

    	$aItems = $this->_oQuery->getItems(array('type' => 'to_send', 'start' => 0, 'per_page' => $iLimit));
    	foreach($aItems as $iId => $aItem)
    	    if(call_user_func_array(array($this, '_send'), array_slice($aItem, 1))) {
                $this->_oQuery->deleteItem($iId);

    	        $aSent[] = $iId;
            }            

        $this->setBusy();

    	return count($aSent);
    }
}

/** @} */
