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

/**
 * Groups profiles module.
 */
class BxGroupsModule extends BxBaseModProfileModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function serviceActAsProfile ()
    {
        return false;
    }

    public function servicePrepareFields ($aFieldsProfile)
    {
        $aFieldsProfile['group_name'] = $aFieldsProfile['name'];
        $aFieldsProfile['group_desc'] = isset($aFieldsProfile['description']) ? $aFieldsProfile['description'] : '';
        unset($aFieldsProfile['name']);
        unset($aFieldsProfile['description']);
        return $aFieldsProfile;
    }

    public function serviceAddMutualConnection ($iContentId, $iInitiatorId)
    {
        $aContentInfo = $this->_oDb->getContentInfoById((int)$iContentId);
        if (!$aContentInfo || $aContentInfo['join_confirmation'])
            return false;

        $oConnection = BxDolConnection::getObjectInstance('bx_groups_fans');
        return $oConnection->addConnection((int)$iContentId, (int)$iInitiatorId);
    }

    public function serviceFansTable ()
    {
        $oGrid = BxDolGrid::getObjectInstance('bx_groups_fans');
        if (!$oGrid)
            return false;

        return $oGrid->getCode();
    }

    public function serviceFans ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        bx_import('BxDolConnection');
        $s = $this->serviceBrowseConnectionsQuick ($iContentId, 'bx_groups_fans', BX_CONNECTIONS_CONTENT_TYPE_CONTENT, true);
        if (!$s)
            return MsgBox(_t('_sys_txt_empty'));
        return $s;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFanAdd (&$aDataEntry, $isPerformAction = false)
    {
        return $this->_checkAllowedConnectContent ($aDataEntry, $isPerformAction, 'bx_groups_fans', true, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFanRemove (&$aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED === $this->_checkAllowedConnectContent ($aDataEntry, $isPerformAction, 'sys_profiles_friends', false, true, true))
            return CHECK_ACTION_RESULT_ALLOWED;
        return $this->_checkAllowedConnectContent ($aDataEntry, $isPerformAction, 'bx_groups_fans', false, true, false);
    }

    public function checkAllowedManageAdmins ($mixedDataEntry, $isPerformAction = false)
    {
        $aDataEntry = is_array($mixedDataEntry) ? $mixedDataEntry : $this->_oDb->getContentInfoById((int)$mixedDataEntry);

        return parent::checkAllowedEdit ($aDataEntry, $isPerformAction);
    }

    public function checkAllowedEdit ($aDataEntry, $isPerformAction = false)
    {
        if ($this->_oDb->isAdmin($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], bx_get_logged_profile_id()))
            return CHECK_ACTION_RESULT_ALLOWED;
        return parent::checkAllowedEdit ($aDataEntry, $isPerformAction);
    }

    public function checkAllowedChangeCover ($aDataEntry, $isPerformAction = false)
    {
        if ($this->_oDb->isAdmin($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], bx_get_logged_profile_id()))
            return CHECK_ACTION_RESULT_ALLOWED;
        return parent::checkAllowedChangeCover ($aDataEntry, $isPerformAction);
    }

    public function checkAllowedDelete (&$aDataEntry, $isPerformAction = false)
    {
        if ($this->_oDb->isAdmin($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], bx_get_logged_profile_id()))
            return CHECK_ACTION_RESULT_ALLOWED;
        return parent::checkAllowedDelete ($aDataEntry, $isPerformAction);
    }
}

/** @} */
