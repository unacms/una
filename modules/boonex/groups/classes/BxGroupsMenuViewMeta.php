<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry meta menu
 */
class BxGroupsMenuViewMeta extends BxBaseModGroupsMenuViewMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_groups';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
