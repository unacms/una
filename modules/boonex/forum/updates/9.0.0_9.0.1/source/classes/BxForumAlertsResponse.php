<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

class BxForumAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_forum';
        parent::__construct();        
    }

    public function response($oAlert)
    {
        if ($this->MODULE == $oAlert->sUnit && 'commentPost' == $oAlert->sAction)
            BxDolService::call($this->MODULE, 'trigger_comment_post', array($oAlert->iObject, $oAlert->aExtras['comment_author_id'], $oAlert->aExtras['comment_id'], 0, $oAlert->aExtras['comment_text']));

        parent::response($oAlert);
    }
}

/** @} */
