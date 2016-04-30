<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 * 
 * @{
 */

class BxGroupsGridConnections extends BxDolGridConnections
{
    protected $_iContentId;

    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sContentModule = 'bx_groups';
        $this->_sObjectConnections = 'bx_groups_fans';

        parent::__construct ($aOptions, $oTemplate);

        $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return;

        $oProfile = BxDolProfile::getInstance($iProfileId);
        if (!$oProfile)
            return;

        $oConnection = BxDolConnection::getObjectInstance($this->_sObjectConnections);
        if (!$oConnection)
            return;

        $aProfileInfo = $oProfile->getInfo();
        $this->_iContentId = $aProfileInfo['content_id'];

        $oModule = BxDolModule::getInstance($aProfileInfo['type']);
        $aContentInfo = BxDolService::call($aProfileInfo['type'], 'get_content_info_by_id', array($this->_iContentId));
        if (CHECK_ACTION_RESULT_ALLOWED === $oModule->checkAllowedEdit($aContentInfo) || $oProfile->id() == bx_get_logged_profile_id())
            $this->_bOwner = true;

        $aSQLParts = $oConnection->getConnectedInitiatorsAsSQLParts('p', 'id', $this->_iContentId, $this->_bOwner ? false : true);
        $this->addMarkers(array(
            'profile_id' => $oProfile->id(),
            'join_connections' => $aSQLParts['join'],
            'content_module' => $this->_sContentModule,
        ));
    }

    protected function _getActionAccept ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (isset($aRow[$this->_aOptions['field_id']]))
            $a['attr']['bx_grid_action_data'] = $aRow[$this->_aOptions['field_id']] . ':' . $this->_iContentId;

        return parent::_getActionAccept ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionDelete ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (isset($aRow[$this->_aOptions['field_id']]))
            $a['attr']['bx_grid_action_data'] = $aRow[$this->_aOptions['field_id']] . ':' . $this->_iContentId;

        return parent::_getActionDelete ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
}

/** @} */
