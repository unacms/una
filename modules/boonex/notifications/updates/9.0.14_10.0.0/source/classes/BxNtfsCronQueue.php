<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNtfsCronQueue extends BxDolCron
{
    protected $_sModule;
    protected $_oModule;

    public function __construct()
    {
    	$this->_sModule = 'bx_notifications';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct();
    }

    function processing()
    {
        $iDeliveryTimeout = $this->_oModule->_oConfig->getDeliveryTimeout();
        if($iDeliveryTimeout == 0)
            return;

        $aProcessed = $aSent = array();
        $aItems = $this->_oModule->_oDb->queueGet(array('type' => 'all_to_send', 'timeout' => $iDeliveryTimeout));
        foreach($aItems as $aItem) {
            $sMethod = 'sendNotification' . bx_gen_method_name($aItem['delivery']);
            $oProfile = BxDolProfile::getInstance($aItem['profile_id']);

            if($this->_oModule->$sMethod($oProfile, unserialize($aItem['content'])) !== false)
                $aSent[] = $aItem['id'];

            $aProcessed[] = $aItem['id'];
        }
    
        $this->_oModule->_oDb->queueDeleteByIds($aProcessed);
    }
}

/** @} */
