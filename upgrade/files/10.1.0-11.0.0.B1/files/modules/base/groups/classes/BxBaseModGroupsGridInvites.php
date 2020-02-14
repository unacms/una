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

class BxBaseModGroupsGridinvites extends BxTemplGrid
{
    protected $_sContentModule;
    protected $_iGroupProfileId;
    protected $_oModule;
    protected $_aContentInfo = array();

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance($this->_sContentModule);

        parent::__construct ($aOptions, $oTemplate);
        
        $this->_aQueryAppend['profile_id'] = (int)bx_get('profile_id');
    }
    
    protected function _getCellName ($mixedValue, $sKey, $aField, $aRow)
    {
        $oProfile = BxDolProfile::getInstance($aRow['invited_profile_id']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        return parent::_getCellDefault ($oProfile->getUnit(), $sKey, $aField, $aRow);
    }
    
    protected function _getCellAdded ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $aCheck = checkActionModule(bx_get_logged_profile_id(), 'delete invites', $this->_sContentModule, false);
        
         if ($aRow['author_profile_id'] == bx_get_logged_profile_id() || $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
            return parent::_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
         
         return '';
    }
    
    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `group_profile_id` = ?", (int)bx_get('profile_id'));
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
