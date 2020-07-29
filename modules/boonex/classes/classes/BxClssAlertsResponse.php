<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

class BxClssAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_classes';
        parent::__construct();
    }

    public function response($oAlert)
    {
        parent::response($oAlert);

        $CNF = $this->_oModule->_oConfig->CNF;

        if ('bx_classes' == $oAlert->sUnit && 'commentPost' == $oAlert->sAction) {
            $this->_oModule->_oDb->updateClassStatus($oAlert->iObject, $oAlert->iSender, 'replied');
        }
    }
}

/** @} */
