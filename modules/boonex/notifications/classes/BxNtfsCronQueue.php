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

    public function processing()
    {
        $iDeliveryTimeout = $this->_oModule->_oConfig->getDeliveryTimeout();
        if($iDeliveryTimeout == 0)
            return;

        $aItems = $this->_oModule->_oDb->queueGet(array('type' => 'all_to_send', 'timeout' => $iDeliveryTimeout));
        if(empty($aItems) || !is_array($aItems))
            return;

        $this->_oModule->_oDb->queueDeleteByIds(array_keys($aItems));

        $aSent = array();
        foreach($aItems as $aItem)
            if($this->_oModule->{'sendNotification' . bx_gen_method_name($aItem['delivery'])}($aItem['profile_id'], unserialize($aItem['content'])) !== false)
                $aSent[] = $aItem['id'];
    }
}

/** @} */
