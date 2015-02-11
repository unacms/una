<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAlbumsAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_albums';
        parent::__construct();
    }

    public function response($oAlert)
    {
        if ('bx_albums_files' == $oAlert->sUnit && 'file_deleted' == $oAlert->sAction)
            BxDolService::call($this->MODULE, 'delete_file_associations', array($oAlert->iObject));

        parent::response($oAlert);
    }
}

/** @} */
