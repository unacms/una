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
 * 'View group' actions menu.
 */
class BxEventsMenuViewActions extends BxBaseModGroupsMenuViewActions
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_events';

        parent::__construct($aObject, $oTemplate);

        $this->addMarkers([
            'js_object_entry' => $this->_oModule->_oConfig->getJsObject('entry')
        ]);
    }
}

/** @} */
