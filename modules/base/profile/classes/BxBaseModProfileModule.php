<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Base class for profile modules.
 */
class BxBaseModProfileModule extends BxBaseModGeneralModule implements iBxDolProfileService
{
    protected $_iAccountId;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);
        $this->_iAccountId = getLoggedId();
    }

    public function actionDeleteProfileImg($iFileId, $iContentId, $sFieldPicture) 
    {
        $aResult = array();
        $CNF = &$this->_oConfig->CNF;

        $oSrorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        if (!($aFile = $oSrorage->getFile((int)$iFileId)) || !($aContentInfo = $this->_oDb->getContentInfoById($iContentId)) || $aContentInfo[$sFieldPicture] != (int)$iFileId)
            $aResult = array('error' => 1, 'msg' => _t('_sys_storage_err_file_not_found'));

        $oAccountProfile = BxDolProfile::getInstanceAccountProfile();
        if ($oAccountProfile)
            $iAccountProfileId = $oAccountProfile->id();

        if ((!$aResult && !isLogged()) || (!$aResult && $aFile['profile_id'] != $iAccountProfileId && !$this->_isModerator()))           
            $aResult = array('error' => 2, 'msg' => _t('_Access denied'));

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'], $this->_oTemplate);

        if (!$aResult && !$oForm->_deleteFile($iContentId, $sFieldPicture, (int)$iFileId, true))
            $aResult = array('error' => 3, 'msg' => _t('_Failed'));
        elseif (!$aResult)            
            $aResult = array('error' => 0, 'msg' => '');

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($aResult);
    }

    // ====== SERVICE METHODS

	public function servicePrivateProfileMsg()
    {
        return MsgBox(_t('_sys_access_denied_to_private_content'));
    }
    
	public function serviceGetContentInfoById($iContentId)
    {
        return $this->_oDb->getContentInfoById((int)$iContentId);
    }

	public function serviceGetMenuAddonManageTools()
	{
		bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass();
        $o->fillFilters(array(
			'perofileStatus' => BX_PROFILE_STATUS_PENDING
        ));
        $o->unsetPaginate();

        return $o->getNum();
	}

	public function serviceGetMenuAddonManageToolsProfileStats()
	{
		bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass();
        $o->fillFilters(array(
			'account_id' => getLoggedId(),
        	'perofileStatus' => ''
        ));
        $o->unsetPaginate();

        return $o->getNum();
	}

    public function serviceGetSubmenuObject ()
    {
        return $this->_oConfig->CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'];
    }

    public function serviceGetMenuSetNameForMenuTrigger ($sMenuTriggerName)
    {
        $CNF = &$this->_oConfig->CNF;
        if ($CNF['TRIGGER_MENU_PROFILE_VIEW_SUBMENU'] == $sMenuTriggerName)
            return $CNF['OBJECT_MENU_SUBMENU_VIEW_ENTRY'];
        elseif ($CNF['TRIGGER_MENU_PROFILE_VIEW_ACTIONS'] == $sMenuTriggerName)
            return $CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY'];
        return '';
    }

	public function serviceGetPageObjectForPageTrigger ($sPageTriggerName)
    {
        if (isset($this->_oConfig->CNF['TRIGGER_PAGE_VIEW_ENTRY']) && $this->_oConfig->CNF['TRIGGER_PAGE_VIEW_ENTRY'] == $sPageTriggerName)
        	return $this->_oConfig->CNF['OBJECT_PAGE_VIEW_ENTRY'];

        return '';
    }

    public function serviceProfilesSearch ($sTerm, $iLimit)
    {
        $aRet = array();
        $a = $this->_oDb->searchByTerm($sTerm, $iLimit);
        foreach ($a as $r)
            $aRet[] = array ('label' => $this->serviceProfileName($r['content_id']), 'value' => $r['profile_id']);
        return $aRet;
    }

    public function serviceProfileUnit ($iContentId)
    {
        return $this->_serviceTemplateFunc('unit', $iContentId);
    }

    public function serviceProfilePicture ($iContentId)
    {
        return $this->_serviceTemplateFunc('urlPicture', $iContentId);
    }

    public function serviceProfileAvatar ($iContentId)
    {
        return $this->_serviceTemplateFunc('urlAvatar', $iContentId);
    }

    public function serviceProfileEditUrl ($iContentId)
    {
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->_oConfig->CNF['URI_EDIT_ENTRY'] . '&id=' . $iContentId);
    }

    public function serviceProfileThumb ($iContentId)
    {
        return $this->_serviceTemplateFunc('thumb', $iContentId);
    }

    public function serviceProfileIcon ($iContentId)
    {
        return $this->_serviceTemplateFunc('icon', $iContentId);
    }

    public function serviceProfileName ($iContentId)
    {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        return bx_process_output($aContentInfo[$this->_oConfig->CNF['FIELD_NAME']]);
    }

    public function serviceProfileCreateUrl ($bAbsolute = true)
    {
    	$CNF = $this->_oConfig->CNF;
    	if(empty($CNF['URL_CREATE']))
    		return false;

    	return $bAbsolute ? BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_CREATE']) : $CNF['URL_CREATE'];
    }

    public function serviceProfileUrl ($iContentId)
    {
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;
        $CNF = $this->_oConfig->CNF;
        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
    }

    public function servicePrepareFields ($aFieldsProfile)
    {
        return $aFieldsProfile;
    }

    public function serviceFormsHelper ()
    {
        return parent::serviceFormsHelper ();
    }

    public function serviceEntityCreate ()
    {
	    BxDolInformer::getInstance($this->_oTemplate)->setEnabled(false);

	    return parent::serviceEntityCreate ();
    }

    public function serviceActAsProfile ()
    {
        return true;
    }

    public function serviceBrowseRecentProfiles ($bDisplayEmptyMsg = false)
    {
        return $this->_serviceBrowse ('recent', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }

    public function serviceBrowseActiveProfiles ($bDisplayEmptyMsg = false)
    {
        return $this->_serviceBrowse ('active', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }

    public function serviceBrowseTopProfiles ($bDisplayEmptyMsg = false)
    {
        return $this->_serviceBrowse ('top', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }
    
	public function serviceBrowseOnlineProfiles ($bDisplayEmptyMsg = false)
    {
        return $this->_serviceBrowse ('online', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }

    public function serviceBrowseConnections ($iProfileId, $sObjectConnections = 'sys_profiles_friends', $sConnectionsType = 'content', $iMutual = false, $iDesignBox = BX_DB_PADDING_DEF, $iProfileId2 = 0)
    {
        return $this->_serviceBrowse (
            'connections',
            array(
                'object' => $sObjectConnections,
                'type' => $sConnectionsType,
                'mutual' => $iMutual,
                'profile' => (int)$iProfileId,
                'profile2' => (int)$iProfileId2),
            $iDesignBox
        );
    }

    public function serviceBrowseConnectionsQuick ($iProfileId, $sObjectConnections = 'sys_profiles_friends', $sConnectionsType = 'content', $iMutual = false, $iProfileId2 = 0)
    {
        // get connections object
        $oConnection = BxDolConnection::getObjectInstance($sObjectConnections);
        if (!$oConnection)
            return '';

        // set some vars
        $iLimit = empty($this->_oConfig->CNF['PARAM_NUM_CONNECTIONS_QUICK']) ? 4 : getParam($this->_oConfig->CNF['PARAM_NUM_CONNECTIONS_QUICK']);
        if (!$iLimit)
            $iLimit = 4;
        $iStart = (int)bx_get('start');

        // get connections array
        bx_import('BxDolConnection');
        $a = $oConnection->getConnectionsAsArray ($sConnectionsType, $iProfileId, $iProfileId2, $iMutual, (int)bx_get('start'), $iLimit + 1, BX_CONNECTIONS_ORDER_ADDED_DESC);
        if (!$a)
            return '';

        // get paginate object
        $oPaginate = new BxTemplPaginate(array(
            'on_change_page' => "return !loadDynamicBlockAutoPaginate(this, '{start}', '{per_page}');",
            'num' => count($a),
            'per_page' => $iLimit,
            'start' => $iStart,
        ));

        // remove last item from connection array, because we've got one more item for pagination calculations only
        if (count($a) > $iLimit)
            array_pop($a);

        // get profiles HTML
        $s = '';
        foreach ($a as $iProfileId) {
            if (!($o = BxDolProfile::getInstance($iProfileId))) {
                continue;
            }
            $s .= $o->getUnit();
        }

        // return profiles + paginate
        return $s . (!$iStart && $oPaginate->getNum() <= $iLimit ?  '' : $oPaginate->getSimplePaginate());
    }

    public function serviceEntityEditCover ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('editDataForm', $iContentId, $this->_oConfig->CNF['OBJECT_FORM_ENTRY_DISPLAY_EDIT_COVER']);
    }

    public function serviceProfileMembership ($iContentId = 0)
    {
    	if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

		$aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

		return BxDolAcl::getInstance()->getProfileMembership($aContentInfo['profile_id']);
    }
    public function serviceProfileFriends ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        bx_import('BxDolConnection');
        $s = $this->serviceBrowseConnectionsQuick ($aContentInfo['profile_id'], 'sys_profiles_friends', BX_CONNECTIONS_CONTENT_TYPE_CONTENT, true);
        if (!$s)
            return MsgBox(_t('_sys_txt_empty'));
        return $s;
    }

    /**
     * For internal usage only.
     */
    public function serviceDeleteEntityService ($iContentId, $bDeleteWithContent = false)
    {
        return parent::serviceDeleteEntity ($iContentId, 'deleteDataService');
    }

	/**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {
        $a = parent::serviceGetNotificationsData();

        $sModule = $this->_aModule['name'];
        
        $a['handlers'][] = array('group' => $sModule . '_timeline_post_common', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'timeline_post_common', 'module_name' => $sModule, 'module_method' => 'get_notifications_timeline_post_common', 'module_class' => 'Module');

        $a['alerts'][] = array('unit' => $sModule, 'action' => 'timeline_post_common');

        return $a;
    }

    /**
     * Notification about new member requst in the group
     */
    public function serviceGetNotificationsTimelinePostCommon($aEvent)
    {
        return $this->_serviceGetNotification($aEvent, '_bx_' . $this->_oConfig->getUri() . '_txt_ntfs_timeline_post_common');
    }

    public function serviceGetConnectionButtonsTitles($iProfileId, $sConnectionsObject = 'sys_profiles_friends')
    {
        if (!isLogged())
            return array();

        if (!($oConn = BxDolConnection::getObjectInstance($sConnectionsObject)))
            return array();

        $CNF = $this->_oConfig->CNF;

        if ($oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $iProfileId)) {
            return array(
                'add' => _t($CNF['T']['menu_item_title_befriend_sent']),
                'remove' => _t($CNF['T']['menu_item_title_unfriend_cancel_request']),
            );
        } elseif ($oConn->isConnectedNotMutual($iProfileId, bx_get_logged_profile_id())) {
            return array(
                'add' => _t($CNF['T']['menu_item_title_befriend_confirm']),
                'remove' => _t($CNF['T']['menu_item_title_unfriend_reject_request']),
            );
        } elseif ($oConn->isConnected($iProfileId, bx_get_logged_profile_id(), true)) {
            return array(
                'add' => '',
                'remove' => _t($CNF['T']['menu_item_title_unfriend']),
            );
        } else {
            return array(
                'add' => _t($CNF['T']['menu_item_title_befriend']),
                'remove' => '',
            );
        }
    }
    
    // ====== PERMISSION METHODS

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedView ($aDataEntry, $isPerformAction = false)
    {
        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->getName());
        if ($oProfile && $oProfile->id() == $this->_iProfileId)
            return CHECK_ACTION_RESULT_ALLOWED;
        return parent::checkAllowedView ($aDataEntry, $isPerformAction);
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedViewProfileImage ($aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedView($aDataEntry)) && BX_DOL_PG_FRIENDS == $aDataEntry[$this->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']])
            return CHECK_ACTION_RESULT_ALLOWED;
        
        return $sMsg;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedViewCoverImage ($aDataEntry, $isPerformAction = false)
    {
        return $this->checkAllowedView($aDataEntry);
    }
    
    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedPost ($aDataEntry, $isPerformAction = false)
    {
        return $this->checkAllowedView ($aDataEntry, $isPerformAction);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedEdit ($aDataEntry, $isPerformAction = false)
    {
        // moderator always has access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // owner (checked by account! not as profile as ususal) always have access
        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->_aModule['name']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        if ($oProfile->getAccountId() == $this->_iAccountId)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * Check if user can change cover image
     */
    public function checkAllowedChangeCover ($aDataEntry, $isPerformAction = false)
    {
        // moderator always has access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // owner (checked by account! not as profile as ususal) always have access
        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->_aModule['name']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        if ($oProfile->getAccountId() == $this->_iAccountId)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedDelete (&$aDataEntry, $isPerformAction = false)
    {
        // moderator always has access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL and owner (checked by account! not as profile as ususal)
        $aCheck = checkActionModule($this->_iProfileId, 'delete entry', $this->getName(), $isPerformAction);

        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$this->_oConfig->CNF['FIELD_ID']], $this->_aModule['name']);
        if (!$oProfile)
            return _t('_sys_txt_error_occured');

        if ($oProfile->getAccountId() == $this->_iAccountId && $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedViewMoreMenu (&$aDataEntry, $isPerformAction = false)
    {
        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE']);
        if (!$oMenu || !$oMenu->getCode())
            return _t('_sys_txt_access_denied');
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFriendAdd (&$aDataEntry, $isPerformAction = false)
    {
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_friends', true, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedFriendRemove (&$aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED === $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_friends', false, true, true))
            return CHECK_ACTION_RESULT_ALLOWED;
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_friends', false, true, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedSubscribeAdd (&$aDataEntry, $isPerformAction = false)
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedView($aDataEntry)))
            return $sMsg;
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_subscriptions', false, false);
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden.
     */
    public function checkAllowedSubscribeRemove (&$aDataEntry, $isPerformAction = false)
    {
        return $this->_checkAllowedConnect ($aDataEntry, $isPerformAction, 'sys_profiles_subscriptions', false, true);
    }

    public function checkMyself($iContentId)
    {
		$iLogged = (int)bx_get_logged_profile_id();
    	if(empty($iLogged))
    		return false;

    	$oProfile = BxDolProfile::getInstanceByContentAndType((int)$iContentId, $this->_oConfig->getName());
    	if(!$oProfile)
    		return false;

		return $oProfile->id() == $iLogged;
    }

    // ====== PROTECTED METHODS

    protected function _checkAllowedConnect (&$aDataEntry, $isPerformAction, $sObjConnection, $isMutual, $isInvertResult, $isSwap = false)
    {
        if (!$this->_iProfileId)
            return _t('_sys_txt_access_denied');

        $CNF = &$this->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstanceByContentAndType($aDataEntry[$CNF['FIELD_ID']], $this->_aModule['name']);
        if (!$oProfile || $oProfile->id() == $this->_iProfileId)
            return _t('_sys_txt_access_denied');

        $oConn = BxDolConnection::getObjectInstance($sObjConnection);
        if ($isSwap)
            $isConnected = $oConn->isConnected($oProfile->id(), $this->_iProfileId, $isMutual);
        else
            $isConnected = $oConn->isConnected($this->_iProfileId, $oProfile->id(), $isMutual);

        if ($isInvertResult)
            $isConnected = !$isConnected;

        return $isConnected ? _t('_sys_txt_access_denied') : CHECK_ACTION_RESULT_ALLOWED;
    }

    protected function _buildRssParams($sMode, $aArgs)
    {
        $aParams = array ();
        $sMode = bx_process_input($sMode);
        switch ($sMode) {
            case 'connections':
                $aParams = array(
                    'object' => isset($aArgs[0]) ? $aArgs[0] : '',
                    'type' => isset($aArgs[1]) ? $aArgs[1] : '',
                    'profile' => isset($aArgs[2]) ? (int)$aArgs[2] : 0,
                    'mutual' => isset($aArgs[3]) ? (int)$aArgs[3] : 0,
                    'profile2' => isset($aArgs[4]) ? (int)$aArgs[4] : 0,
                );
                break;
        }

        return $aParams;
    }
}

/** @} */
