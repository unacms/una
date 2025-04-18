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

/**
 * View entry all actions menu
 */
class BxBaseModGroupsMenuViewActionsAll extends BxBaseModProfileMenuViewActionsAll
{
    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
        
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        if(isset($CNF['OBJECT_CONNECTIONS']))
            $this->_aConnectionToFunctionCheck[$CNF['OBJECT_CONNECTIONS']] = [
                'add' => 'checkAllowedFanAdd', 
                'remove' => 'checkAllowedFanRemove'
            ];
    }

    protected function _isContentPublic($iContentId, $aPublicGroups = [])
    {
        return parent::_isContentPublic($iContentId, [BX_DOL_PG_ALL, BX_DOL_PG_MEMBERS, 'c']);
    }

    protected function _getMenuItemProfileFanAdd($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        $mixedResult = $this->_getMenuItemByNameActions($aItem);
        if(!$mixedResult)
            return $mixedResult;

        if($this->_bIsApi && $CNF['OBJECT_CONNECTIONS'])
            return $this->_getMenuItemConnectionApi($CNF['OBJECT_CONNECTIONS'], 'add', $aItem);

        return $mixedResult;
    }

    protected function _getMenuItemProfileFanAddPaid($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemProfileFanRemove($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        
        $mixedResult = $this->_getMenuItemByNameActions($aItem);
        if(!$mixedResult)
            return $mixedResult;

        if($this->_bIsApi && $CNF['OBJECT_CONNECTIONS'])
            return $this->_getMenuItemConnectionApi($CNF['OBJECT_CONNECTIONS'], 'remove', $aItem);

        return $mixedResult;
    }
}

/** @} */
