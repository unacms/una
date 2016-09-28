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

/**
 * Browse entries pages.
 */
class BxEventsPageBrowse extends BxBaseModGroupsPageBrowse
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_events';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
