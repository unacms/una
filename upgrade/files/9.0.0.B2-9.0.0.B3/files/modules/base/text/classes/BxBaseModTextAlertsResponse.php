<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModTextAlertsResponse extends BxBaseModGeneralAlertsResponse
{
    public function response($oAlert)
    {
        parent::response($oAlert);

        if ('profile' != $oAlert->sUnit || 'delete' != $oAlert->sAction || !isset($oAlert->aExtras['delete_with_content']) || !$oAlert->aExtras['delete_with_content'])
            return;

        BxDolService::call($this->MODULE, 'delete_entities_by_author', array($oAlert->iObject));
    }
}

/** @} */
