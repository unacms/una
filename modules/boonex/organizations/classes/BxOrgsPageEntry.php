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
 * Profile create/edit/delete pages.
 */
class BxOrgsPageEntry extends BxBaseModGroupsPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_organizations';

        parent::__construct($aObject, $oTemplate);

        $this->_sCoverClass = $this->_oModule->getName() . '_cover';
    }
}

/** @} */
