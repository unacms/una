<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Events Events
 * @ingroup     TridentModules
 * 
 * @{
 */

class BxEventsGridConnections extends BxBaseModGroupsGridConnections
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sContentModule = 'bx_events';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
