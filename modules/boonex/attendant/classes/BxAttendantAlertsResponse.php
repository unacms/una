<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Attendant Attendant
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAttendantAlertsResponse extends BxDolAlertsResponse
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function response($oAlert)
    {
        $iObjectId  = $oAlert->iObject;
        $iProfileId = bx_get_logged_profile_id();
        
        if ($oAlert->sUnit == 'profile' && $oAlert->sAction == 'add'){
            $iProfileId = $oAlert->iObject;
        }
        
        $oModule = BxDolModule::getInstance('bx_attendant');
        $oModule->initPopupByEvent($iObjectId, $oAlert->sUnit, $oAlert->sAction, $iProfileId);
    }
}

/** @} */
