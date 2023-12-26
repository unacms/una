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

class BxEventsFormQuestion extends BxBaseModGroupsFormQuestion
{
    public function __construct($aInfo, $oTemplate = false)
    {
        $this->_sModule = 'bx_events';

        parent::__construct($aInfo, $oTemplate);
    }
}

/** @} */
