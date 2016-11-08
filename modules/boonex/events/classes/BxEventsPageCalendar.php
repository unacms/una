<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Browse entries pages.
 */
class BxEventsPageCalendar extends BxBaseModGeneralPageBrowse
{
    public function __construct($aOptions, $oTemplate = null)
    {
        $this->MODULE = 'bx_events';
        parent::__construct($aOptions, $oTemplate);
    }
}

/** @} */
