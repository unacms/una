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

class BxEventsMenuSnippetMeta extends BxBaseModGroupsMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_events';

        parent::__construct($aObject, $oTemplate);

        unset($this->_aConnectionToFunctionCheck['sys_profiles_friends']);
        unset($this->_aConnectionToFunctionTitle['sys_profiles_friends']);
    }

    protected function _getMenuItemDateStart($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return $this->getUnitMetaItemText(bx_time_js($this->_aContentInfo[$CNF['FIELD_DATE_START']], BX_FORMAT_DATE_TIME, true));
    }

    protected function _getMenuItemDateEnd($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return $this->getUnitMetaItemText(bx_time_js($this->_aContentInfo[$CNF['FIELD_DATE_END']], BX_FORMAT_DATE_TIME, true));
    }
}

/** @} */
