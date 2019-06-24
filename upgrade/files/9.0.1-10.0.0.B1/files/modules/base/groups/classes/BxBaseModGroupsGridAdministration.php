<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModGroupsGridAdministration extends BxBaseModProfileGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
    }

    protected function _getCellName($mixedValue, $sKey, $aField, $aRow)
    {
        $oProfile = $this->_getProfileObject($aRow['id']);

        return parent::_getCellDefault($oProfile->getUnit(0, array('template' => 'unit_wo_cover')), $sKey, $aField, $aRow);
    }
}

/** @} */
