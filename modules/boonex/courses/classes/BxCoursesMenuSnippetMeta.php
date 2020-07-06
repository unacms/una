<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Courses Courses
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCoursesMenuSnippetMeta extends BxBaseModGroupsMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_courses';

        parent::__construct($aObject, $oTemplate);

        unset($this->_aConnectionToFunctionCheck['sys_profiles_friends']);
        unset($this->_aConnectionToFunctionTitle['sys_profiles_friends']);
    }

    protected function _getMenuItemConnectionsTitle($sAction, &$oConnection)
    {
        $iProfile = bx_get_logged_profile_id();
        $iContentProfile = $this->_oContentProfile->id();

        $aResult = array();
        if($oConnection->isConnectedNotMutual($iProfile, $iContentProfile))
            $aResult = array(
                'add' => '',
                'remove' => _t('_bx_courses_menu_item_title_sm_leave_cancel'),
            );
        else if($oConnection->isConnectedNotMutual($iContentProfile, $iProfile))
            $aResult = array(
                'add' => _t('_bx_courses_menu_item_title_sm_join_confirm'),
                'remove' => _t('_bx_courses_menu_item_title_sm_leave_reject'),
            );
        else if($oConnection->isConnected($iProfile, $iContentProfile, true))
            $aResult = array(
                'add' => '',
                'remove' => _t('_bx_courses_menu_item_title_sm_leave'),
            );
        else
            $aResult = array(
                'add' => _t('_bx_courses_menu_item_title_sm_join'),
                'remove' => '',
            );

        return !empty($sAction) && isset($aResult[$sAction]) ? $aResult[$sAction] : $aResult;
    }
}

/** @} */
