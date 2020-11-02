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

    public function serviceGetOptionsMembersMode()
    {
        $CNF = &$this->_oConfig->CNF;

        $aOptions = parent::serviceGetOptionsMembersMode();
        $aOptions[] = array('key' => BX_BASE_MOD_GROUPS_MMODE_PAID_JOIN, 'value' => _t($CNF['T']['option_members_mode_' . BX_BASE_MOD_GROUPS_MMODE_PAID_JOIN]));

        return $aOptions;
    }
}

/** @} */
