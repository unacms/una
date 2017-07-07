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
 * Groups profiles module.
 */
class BxGroupsModule extends BxBaseModGroupsModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_aSearchableNamesExcept[] = $this->_oConfig->CNF['FIELD_JOIN_CONFIRMATION'];
    }
}

/** @} */
