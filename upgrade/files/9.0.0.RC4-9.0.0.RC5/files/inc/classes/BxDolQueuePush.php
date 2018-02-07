<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolQueuePush extends BxDolQueue implements iBxDolSingleton
{
    protected function __construct()
    {
        parent::__construct();

        $this->_oQuery = new BxDolQueuePushQuery();
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolQueuePush();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    /**
     * Add message to push queue
     *
     * @param integer $iProfileId - recipient profile ID
     * @param array $aMessage - array with Push message 
     * @return true on success, false on error
     */
    public function add($iProfileId, $aMessage)
    {
        return (int)$this->_oQuery->insertItem(array(
        	'profile_id' => $iProfileId,
            'message' => serialize($aMessage)
        )) > 0;
    }

    /**
     * Internal method which performs sending using predefined list of params.
     */
    protected function _send($iProfileId, $sMessage)
    {
        return BxDolPush::getInstance()->send($iProfileId, unserialize($sMessage));
    }
}

/** @} */
