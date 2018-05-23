<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Convos Convos
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCnvAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_convos';
        parent::__construct();        
    }

    public function response($oAlert)
    {
        if ($this->MODULE == $oAlert->sUnit && 'commentPost' == $oAlert->sAction)
            BxDolService::call($this->MODULE, 'trigger_comment_post', array($oAlert->iObject, $oAlert->aExtras['comment_author_id'], $oAlert->aExtras['comment_id'], 0, $oAlert->aExtras['comment_text']));

        if ('profile' == $oAlert->sUnit && 'delete' == $oAlert->sAction && (!isset($oAlert->aExtras['delete_with_content']) || !$oAlert->aExtras['delete_with_content']))
            BxDolService::call($this->MODULE, 'remove_collaborator_from_all_conversations', array($oAlert->iObject));
        
        parent::response($oAlert);
    }
}

/** @} */
