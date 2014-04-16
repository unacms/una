<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Convos Convos
 * @ingroup     DolphinModules
 *
 * @{
 */

/**
 * alerts handler
 */
class BxCnvAlertsResponse extends BxDolAlertsResponse 
{
    protected $MODULE;

    public function __construct() 
    {
        parent::__construct();
        $this->MODULE = 'bx_convos';
    }
    
    public function response($oAlert) 
    {
        if ($this->MODULE == $oAlert->sUnit) {

            switch ($oAlert->sAction) {
                case 'commentPost':
                    BxDolService::call($this->MODULE, 'trigger_comment_post', array($oAlert->iObject, $oAlert->aExtras['comment_author_id'], $oAlert->aExtras['comment_id']));
                    break;
            }

        }
    }
}

/** @} */
