<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MapJoined Display last joined users on map
 * @ingroup     UnaModules
 *
 * @{
 */

class BxMapJoinedAlertsResponse extends BxDolAlertsResponse
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        if ($oAlert->sUnit == 'account' && $oAlert->sAction == 'added'){
            $oModule = BxDolModule::getInstance('bx_mapjoined');
            $oModule->addIpInfoToDb($oAlert->iObject, $_SERVER['REMOTE_ADDR']);
        }
    }
}
/** @} */