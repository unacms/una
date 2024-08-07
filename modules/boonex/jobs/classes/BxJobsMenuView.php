<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Jobs Jobs
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * 'View group' menu.
 */
class BxJobsMenuView extends BxBaseModGroupsMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_jobs';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
