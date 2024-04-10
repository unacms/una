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
    protected $_oModule;
    protected $_sContentModule;

    protected $_iGroupProfileId;    

    protected $_bManageMembers;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance($this->_sContentModule);

        parent::__construct ($aOptions, $oTemplate);

        $this->_iGroupProfileId = 0;
        if(($iProfileId = bx_get('profile_id')) !== false)
            $this->_iGroupProfileId = (int)$iProfileId;

        $this->_bManageMembers = $this->_oModule->checkAllowedManageFans($this->_iGroupProfileId) === CHECK_ACTION_RESULT_ALLOWED || $this->_oModule->checkAllowedManageAdmins($this->_iGroupProfileId) === CHECK_ACTION_RESULT_ALLOWED;

        $this->_aQueryAppend['profile_id'] = $this->_iGroupProfileId;
    }

    public function getCode ($isDisplayHeader = true)
    {
        if(!$this->_bManageMembers)
            return '';

        return parent::getCode($isDisplayHeader);        
    }

    public function getCodeAPI()
    {
        if(!$this->_bManageMembers)
            return [];

        return parent::getCodeAPI();
    }

    protected function _getCellName ($mixedValue, $sKey, $aField, $aRow)
    {
        $oProfile = BxDolProfile::getInstance($aRow['invited_profile_id']);
        if(!$oProfile && ($sMessage = _t('_sys_txt_error_occured')) !== false)
            return $this->_bIsApi ? ['type' => 'text', 'value' => $sMessage] : $sMessage;

        if($this->_bIsApi)
            return ['type' => 'profile', 'data' => BxDolProfile::getData($oProfile->id())];

        return parent::_getCellDefault ($oProfile->getUnit(), $sKey, $aField, $aRow);
    }
    
    protected function _getCellAdded ($mixedValue, $sKey, $aField, $aRow)
    {
        if($this->_bIsApi)
            return ['type' => 'time', 'data' => $mixedValue];

        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = [])
    {
        $iLoggedId = bx_get_logged_profile_id();

        $aCheck = checkActionModule($iLoggedId, 'delete invites', $this->_sContentModule, false);
        if($aRow['author_profile_id'] != $iLoggedId && $aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $this->_bIsApi ? [] : '';

        return parent::_getActionDelete($sType, $sKey, $a, $isSmall, $isDisabled, $aRow); 
    }
    
    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `group_profile_id` = ?", (int)bx_get('profile_id'));
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
