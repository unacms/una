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

class BxBaseModGroupsGridBans extends BxDolGridConnections
{
    protected $_oModule;
    protected $_sContentModule;

    protected $_iGroupProfileId;
    protected $_aContentInfo = [];

    protected $_bManageMembers;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance($this->_sContentModule);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sObjectConnections = 'sys_profiles_bans';

        parent::__construct ($aOptions, $oTemplate);
        if(!$this->_bInit) 
            return;

        $this->_iGroupProfileId = $this->_oProfile->id();

        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_oProfile->getContentId());
        if($this->_oModule->checkAllowedEdit($this->_aContentInfo) === CHECK_ACTION_RESULT_ALLOWED || $this->_iGroupProfileId == bx_get_logged_profile_id())
            $this->_bOwner = true;

        $this->_bManageMembers = $this->_oModule->checkAllowedManageFans($this->_iGroupProfileId) === CHECK_ACTION_RESULT_ALLOWED || $this->_oModule->checkAllowedManageAdmins($this->_iGroupProfileId) === CHECK_ACTION_RESULT_ALLOWED;

        $aSQLParts = $this->_oConnection->getConnectedContentAsSQLParts('p', 'id', $this->_iGroupProfileId, $this->_bOwner ? false : true);
        $this->addMarkers(array(
            'profile_id' => $this->_iGroupProfileId,
            'join_connections' => $aSQLParts['join'],
            'content_module' => $this->_sContentModule,
        ));
    }

    protected function _getCellAdded ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!$this->_bManageMembers)
            return $this->_bIsApi ? [] : '';

        if (isset($aRow[$this->_aOptions['field_id']]))
            $a['attr']['bx_grid_action_data'] = $aRow[$this->_aOptions['field_id']] . ':' . $this->_iGroupProfileId;

        return parent::_getActionDelete($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _delete ($mixedId)
    {
        list($iProfileId, $iContextId) = $this->__prepareIds($mixedId);

        if($this->_oConnection->isConnected($iContextId, $iProfileId))
            $a = $this->_oConnection->removeConnection($iContextId, $iProfileId);

        if(isset($a['err']) && $a['err'])
            return false;

        return true;
    }
}

/** @} */
