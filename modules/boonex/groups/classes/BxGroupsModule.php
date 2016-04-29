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

    public function serviceGetMenuSetNameForMenuTrigger ($sMenuTriggerName)
    {
        if ('trigger_profile_view_submenu' == $sMenuTriggerName)
            return '';
        elseif ('trigger_group_view_submenu' == $sMenuTriggerName)
            return $this->_oConfig->CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'];
        else
            return parent::serviceGetMenuSetNameForMenuTrigger ($sMenuTriggerName);
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
}

/** @} */
