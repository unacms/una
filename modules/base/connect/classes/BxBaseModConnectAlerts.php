<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseConnect Base classes for OAuth connect modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModConnectAlerts extends BxDolAlertsResponse
{
    protected $oModule;

    public function response($o)
    {
        if ($o->sUnit == 'profile') {
            switch ($o->sAction) {
                case 'delete':
                    // remove remote account
                    $this->oModule->_oDb->deleteRemoteAccount($o->iObject);
                    break;
            }
        }
    }
}

/** @} */
