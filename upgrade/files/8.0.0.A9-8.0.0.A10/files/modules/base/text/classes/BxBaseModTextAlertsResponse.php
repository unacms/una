<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModTextAlertsResponse extends BxDolAlertsResponse
{
    protected $MODULE;

    public function response($oAlert)
    {
        if ('profile' != $oAlert->sUnit || 'delete' != $oAlert->sAction || !isset($oAlert->aExtras['delete_with_content']) || !$oAlert->aExtras['delete_with_content'])
            return;

        BxDolService::call($this->MODULE, 'delete_entities_by_author', array($oAlert->iObject));
    }
}

/** @} */
