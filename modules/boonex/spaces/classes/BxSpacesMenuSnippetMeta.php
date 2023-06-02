<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 *
 * @{
 */

class BxSpacesMenuSnippetMeta extends BxBaseModGroupsMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_spaces';

        parent::__construct($aObject, $oTemplate);

        unset($this->_aConnectionToFunctionCheck['sys_profiles_friends']);
    }

    protected function _getMenuItemParent($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['FIELD_PARENT']) || empty($this->_aContentInfo[$CNF['FIELD_PARENT']]))
            return false;

        $oParent = BxDolProfile::getInstance((int)$this->_aContentInfo[$CNF['FIELD_PARENT']]);
        if(!$oParent)
            return false;

        return $this->getUnitMetaItemCustom($oParent->getUnit(0, ['template' => ['name' => 'unit_wo_cover', 'size' => 'icon']]));
    }
}

/** @} */
