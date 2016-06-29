<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Convos Convos
 * @ingroup     TridentModules
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

        parent::response($oAlert);
    }
}

/** @} */
