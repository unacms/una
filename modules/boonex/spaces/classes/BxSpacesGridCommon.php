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

class BxSpacesGridCommon extends BxBaseModGroupsGridCommon
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->MODULE = 'bx_spaces';
        parent::__construct ($aOptions, $oTemplate);
    }
    
    protected function _getActionSettings($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if ($this->_oModule->_oDb->getCountEntriesByParent($aRow["id"]))
            return '';
        return parent::_getActionSettings($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
       
    protected function _isCheckboxDisabled($aRow)
    {
        if ($this->_oModule->_oDb->getCountEntriesByParent($aRow["id"]))
            return true;
        return parent::_isCheckboxDisabled($aRow);
    }

}

/** @} */
