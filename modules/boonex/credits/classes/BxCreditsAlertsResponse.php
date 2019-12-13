<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Credits Credits
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCreditsAlertsResponse extends BxBaseModGeneralAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_credits';

        parent::__construct();
    }

    public function response($oAlert)
    {
        parent::response($oAlert);

        if($oAlert->sUnit == 'profile' && $oAlert->sAction == 'delete' && !empty($oAlert->aExtras['delete_with_content']))
            return BxDolService::call($this->MODULE, 'delete_entities_by_author', array($oAlert->iObject));
    }
}

/** @} */
