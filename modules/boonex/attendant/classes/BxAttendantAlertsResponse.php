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
        if ($oAlert->sUnit == 'profile' && $oAlert->sAction == 'add'){
            $oModule = BxDolModule::getInstance('bx_attendant');
            $oModule->initPopupWithRecommendedOnProfileAdd($oAlert->iObject);
        }
    }
}

/** @} */
