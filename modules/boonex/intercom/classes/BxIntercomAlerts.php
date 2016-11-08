<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Intercom Intercom integration module
 * @ingroup     UnaModules
 *
 * @{
 */

class BxIntercomAlerts extends BxDolAlertsResponse
{
    protected $_oModule;
    function __construct()
    {
        parent::__construct();
        $this->_oModule = BxDolModule::getInstance('bx_intercom');
    }

    public function response($o)
    {        
        if ('account' == $o->sUnit && 'delete' == $o->sAction) {
            $this->_oModule->serviceRemoveAccount($o->iObject);
        }
        
        if ('account' != $o->sUnit && ('deleted' == $o->sAction || 'edited' == $o->sAction || 'added' == $o->sAction)) {
            $oProfile = BxDolProfile::getInstanceByContentAndType($o->iObject, $o->sUnit);
            $oAccount = $oProfile ? $oProfile->getAccountObject() : null;
            
            if ($oAccount)
                $this->_oModule->serviceUpdateAccount($oAccount->id());
        }
    }    
}

/** @} */
