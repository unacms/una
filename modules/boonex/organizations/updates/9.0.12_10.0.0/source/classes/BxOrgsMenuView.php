<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * 'View organization' menu.
 */
class BxOrgsMenuView extends BxBaseModGroupsMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_organizations';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
