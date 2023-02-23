<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Channels Channels
 * @indroup     UnaModules
 *
 * @{
 */

/**
 * Channels profiles module.
 */

class BxCnlModule extends BxBaseModGroupsModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    public function _modGroupsCheckAllowedSubscribeAdd(&$aDataEntry, $isPerformAction = false)
    {
        return parent::_modProfileCheckAllowedSubscribeAdd($aDataEntry, $isPerformAction);
    }

    /**
     * Process Hash Tag
     * 
     * @param string $sHashtag - hashtag to be processed.
     * @param string $sModuleName - module name.
     * @param integer $iContentId - ID of the content which has the hashtag.
     * @param integer $iAuthorId - action's author id.
     */
    function processHashtag($sHashtag, $sModuleName, $iContentId, $iAuthorId = 0)
    {
        $CNF = &$this->_oConfig->CNF;

        /*
         * Note! For now metatag object name is used here as module name, because usually it's equal to module's name. This should be changed in Ticket #1596
         * For now if module cannot be created then a channel for such tag shouldn't be created too.
         */

        $oModule = BxDolModule::getInstance($sModuleName);
        if(empty($oModule) && $sModuleName != 'sys_cmts')
            return;

        /*
         * Use content's author profile when Author ID wasn't provided. 
         * Usually it happens when tags were processed with cron.
         */
        if(empty($iAuthorId))
            $iAuthorId = BxDolService::call($sModuleName, 'get_author', array($iContentId));

        $aCheck = checkActionModule($iAuthorId, 'create channel auto', $this->getName(), false);
        $mixedCnlId = $this->_oDb->getChannelIdByName($sHashtag);
        if(empty($mixedCnlId) && ($aCheck[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED)) {
            $iProfileId = (int)$this->_oDb->getParam($CNF['PARAM_DEFAULT_AUTHOR']);
            if(empty($iProfileId)) {
                $aOperators = BxDolAccountQuery::getInstance()->getOperators();
                if(count($aOperators) > 0)
                    $iProfileId = BxDolProfile::getInstanceByAccount(array_shift($aOperators))->id();
            }

            $aContent = $this->serviceEntityAdd($iProfileId, array($CNF['FIELD_NAME'] => $sHashtag, $CNF['FIELD_CF'] => 1));
            checkActionModule($iAuthorId, 'create channel auto', $this->getName(), true);
            if(isset($aContent['content']) && isset($aContent['content']['id']))
                $mixedCnlId = $aContent['content']['id'];
        }

        if(empty($mixedCnlId) || (int)$this->_oDb->checkContentInChannel($iContentId, $mixedCnlId, $sModuleName, $iAuthorId) != 0)
            return;

        $iId = (int)$this->_oDb->addContentToChannel($iContentId, $mixedCnlId, $sModuleName, $iAuthorId);
        if($iId == 0)
            return;

        $oCnlProfile = BxDolProfile::getInstanceByContentAndType($mixedCnlId, $this->_oConfig->getName());
        if(!$oCnlProfile)
            return;

        $iCnlProfileId = $oCnlProfile->id();

        bx_alert($this->_aModule['name'], 'hashtag_added', $iId, $iCnlProfileId, array(
            'object_author_id' => $iAuthorId, 
            'privacy_view' => -$iCnlProfileId,
            'subobject_id' => $iId,
			'content_module' => $sModuleName,
            'content_id' => $iContentId,
            'content_author_id' => $iAuthorId,
            'timeline_group' => array(
                'by' => $sModuleName . '_' . $iAuthorId . '_' . $iContentId,
                'field' => 'owner_id'
            )
        ));
        $aParams = array(
            'object_author_id' => $iAuthorId, 
            'privacy_view' => -$iCnlProfileId, 
            'subobject_id' => $iId,
			'content_module' => $sModuleName,
            'content_id' => $iContentId,
            'content_author_id' => $iAuthorId
        );
        bx_alert('system', 'prepare_alert_params', 0, 0, array('unit'=> $this->_aModule['name'], 'action' => 'hashtag_added_notif', 'object_id' => $mixedCnlId, 'sender_id' => $iCnlProfileId, 'extras' => &$aParams));
        bx_alert($this->_aModule['name'], 'hashtag_added_notif', $mixedCnlId, $iCnlProfileId, $aParams);
    }
    
    function removeContentFromChannel($iContentId, $sModuleName)
    {
        $oDolProfileQuery = BxDolProfileQuery::getInstance();
        
        $aData = $this->_oDb->getDataByContent($iContentId, $sModuleName);
        foreach ($aData as $aRow) {
            $iProfileInfo = $oDolProfileQuery->getProfileByContentAndType($aRow['cnl_id'], $this->_aModule['name']);
            if(is_array($iProfileInfo)){
                bx_alert($this->_aModule['name'], 'hashtag_deleted', $aRow['id'], $iProfileInfo['id']);
                bx_alert($this->_aModule['name'], 'hashtag_deleted_notif', $aRow['cnl_id'], $iProfileInfo['id'], array('subobject_id' => $aRow['id']));
            }
        }
        
        return $this->_oDb->removeContentFromChannel($iContentId, $sModuleName);
    }

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();
        unset($a['GetCreatePostForm']);
        unset($a['EntityCreate']);
        return array_merge($a, array (
            'EntityBreadcrumb' => '',
            'EntityParent' => '',
            'EntityChilds' => '',
            'SearchResultByHashtag' => '',
        ));
    }

    public function serviceGetWidgetNotices()
    {
        $CNF = &$this->_oConfig->CNF;

        $iResult = 0;
        if(empty($this->_oDb->getParam($CNF['PARAM_DEFAULT_AUTHOR'])))
            $iResult += 1;

        return $iResult;
    }

    public function serviceGetOptionsDefaultAuthor()
    {
        $aResult = array(
            array('key' => '', 'value' => _t('_Select_one'))
        );

        $aAccountsIds = BxDolAccountQuery::getInstance()->getOperators();
        foreach($aAccountsIds as $iAccountId) {
            $aProfilesIds = BxDolAccount::getInstance($iAccountId)->getProfilesIds();
            foreach($aProfilesIds as $iProfileId)
                $aResult[] = array(
                    'key' => $iProfileId,
                    'value' => BxDolProfile::getInstance($iProfileId)->getDisplayName()
                );
        }

        return $aResult;
    }

    /**
     * @page service Service Calls
     * @section bx_channels Channels
     * @subsection bx_channels-page_blocks Page Blocks
     * @subsubsection bx_channels-entity_breadcrumb entity_breadcrumb
     * 
     * @code bx_srv('bx_channels', 'entity_breadcrumb', [...]); @endcode
     * 
     * Display channel breadcrumb
     * @param $iContentId channel content ID
     * 
     * @see BxCnlModule::serviceEntityBreadcrumb
     */
    /** 
     * @ref bx_channels-entity_breadcrumb "entity_breadcrumb"
     */
    public function serviceEntityBreadcrumb($iContentId = 0)
    {
    	if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return false;

        return $this->_oTemplate->entryBreadcrumb($aContentInfo);
    }

    /**
     * @page service Service Calls
     * @section bx_channels Channels
     * @subsection bx_channels-page_blocks Page Blocks
     * @subsubsection bx_channels-entity_parent entity_parent
     * 
     * @code bx_srv('bx_channels', 'entity_parent', [...]); @endcode
     * 
     * Display block with parent channel
     * @param $iContentId channel content ID
     * 
     * @see BxCnlModule::serviceEntityParent
     */
    /** 
     * @ref bx_channels-entity_parent "entity_parent"
     */
    public function serviceEntityParent($iContentId = 0)
    {
        return $this->_serviceTemplateFunc('entryParent', $iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_channels Channels
     * @subsection bx_channels-page_blocks Page Blocks
     * @subsubsection bx_channels-entity_childs entity_childs
     * 
     * @code bx_srv('bx_channels', 'entity_childs', [...]); @endcode
     * 
     * Display block with child channels
     * @param $iContentId channel content ID
     * 
     * @see BxCnlModule::serviceEntityChilds
     */
    /** 
     * @ref bx_channels-entity_childs "entity_childs"
     */
    public function serviceEntityChilds($iContentId = 0)
    {
        return $this->_serviceTemplateFunc('entryChilds', $iContentId);
    }

    /**
     * @page service Service Calls
     * @section bx_channels Channels
     * @subsection bx_channels-page_blocks Page Blocks
     * @subsubsection bx_channels-search_result_by_hashtag search_result_by_hashtag
     * 
     * @code bx_srv('bx_channels', 'search_result_by_hashtag', [...]); @endcode
     * 
     * Display search results by hashtag for particular channel
     * @param $iContentId channel content ID
     * 
     * @see BxCnlModule::serviceSearchResultByHashtag
     */
    /** 
     * @ref bx_channels-search_result_by_hashtag "search_result_by_hashtag"
     */
    public function serviceSearchResultByHashtag($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $oSearch = new BxTemplSearch();
        $oSearch->setLiveSearch(0);
        $oSearch->setMetaType('keyword');
        $aContentInfo = $this->_oDb->getContentInfoById(bx_get('id'));
        $_GET['keyword'] = $aContentInfo[$CNF['FIELD_NAME']];
        $sCode = $oSearch->response();
        if (!$sCode)
            $sCode = $oSearch->getEmptyResult();
        
        return $sCode;
    }
    
    /**
     * Data for Timeline module
     */
    public function serviceGetTimelineData()
    {
        $sModule = $this->_aModule['name'];

        return array(
            'handlers' => array(
                array('group' => $sModule . '_hastag', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'hashtag_added', 'module_name' => $sModule, 'module_method' => 'get_timeline_post_hashtag', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_hastag', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'hashtag_deleted')
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'hashtag_added'),
                array('unit' => $sModule, 'action' => 'hashtag_deleted')
            )
        );
    }

    public function serviceGetTimelinePostAllowedView ($aEvent)
    {
        $sError = _t('_sys_txt_access_denied');

        if(empty($aEvent) || !is_array($aEvent))
            return $sError;

        $aEventContent = $this->_oDb->getContentById($aEvent['object_id']);
        
        if ($aEventContent['module_name'] == 'sys_cmts')
            return CHECK_ACTION_RESULT_ALLOWED;
            
        if(empty($aEventContent) || !is_array($aEventContent))
            return $sError;

        if(!BxDolRequest::serviceExists($aEventContent['module_name'], 'get_timeline_post_allowed_view'))
            return $sError;

        return BxDolService::call($aEventContent['module_name'], 'get_timeline_post_allowed_view', array(array('id' => $aEvent['id'], 'owner_id' => $aEventContent['author_id'], 'object_id' => $aEventContent['content_id'])));
    }

    public function serviceGetTimelinePostHashtag($aEvent, $aBrowseParams = array())
    {
        if(empty($aEvent) || !is_array($aEvent))
            return false;
        
        $aEventContent = $this->_oDb->getContentById($aEvent['object_id']);
        if(empty($aEventContent) || !is_array($aEventContent))
            return false;
       
        $sModule = $aEventContent['module_name'];
        $sClass = 'Module';
        if($sModule == 'sys_cmts'){
            $sClass = 'TemplCmtsServices';
            $sModule = 'system';
        }
        
        if(!BxDolRequest::serviceExists($sModule, 'get_timeline_post', $sClass))
            return false;
        /**
         * Prepare fake event array (only mandatory parameters) to get
         * necessary data (related to an 'original' event with hashtag/label) 
         * from associated content module.
         */
        $iOwnerId = (int)$aEventContent['author_id'];
        $iContentId = (int)$aEventContent['content_id'];
        $mixedObjectPrivacyView = BX_DOL_PG_ALL;
        if(BxDolRequest::serviceExists($sModule, 'get_privacy_view')) {
            $mixedAllowViewTo = BxDolService::call($sModule, 'get_privacy_view', array($iContentId));
            if($mixedAllowViewTo !== false)
                $mixedObjectPrivacyView = $mixedAllowViewTo;
            if(is_numeric($mixedAllowViewTo) && (int)$mixedAllowViewTo < 0)
                $iOwnerId = abs($mixedAllowViewTo);
        }
        $aResult = BxDolService::call($sModule, 'get_timeline_post', array(array(
            'owner_id' => $iOwnerId,
            'object_id' => $iContentId,
            'object_privacy_view' => $mixedObjectPrivacyView
        ), $aBrowseParams), $sClass);

        if(empty($aResult) || !is_array($aResult))
            return $aResult;
        
        /**
         * Note. The context shouldn't be changed therefore 
         * use input event's context (owner_id) in returned results.
         */
        return array_merge($aResult, array(
            'owner_id' => $aEvent['owner_id']
        ));
    }
    
    /**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {      
        $a = parent::serviceGetNotificationsData();

        $sModule = $this->_aModule['name'];

        $a['handlers'][] = array('group' => $sModule . '_hastag_notif', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'hashtag_added_notif', 'module_name' => $sModule, 'module_method' => 'get_notifications_post_hashtag', 'module_class' => 'Module');
        $a['handlers'][] = array('group' => $sModule . '_hastag_notif', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'hashtag_deleted_notif');

        $a['settings'][] = array('group' => 'hastag', 'unit' => $sModule, 'action' => 'hashtag_added_notif', 'types' => array('follow_context'));

        $a['alerts'][] = array('unit' => $sModule, 'action' => 'hashtag_added_notif');
        $a['alerts'][] = array('unit' => $sModule, 'action' => 'hashtag_deleted_notif');

        return $a;
    }

    public function serviceGetNotificationsPostHashtag($aEvent)
    {
        if(empty($aEvent) || !is_array($aEvent))
            return '';

        $aContentEvent = $this->_oDb->getContentById($aEvent['subobject_id']);
        if(empty($aContentEvent) || !is_array($aContentEvent))
            return '';

        $oModule = BxDolModule::getInstance($aContentEvent['module_name']);
        if ($oModule) {
            if (isset($oModule->_oConfig->CNF['OBJECT_PRIVACY_VIEW'])){
                $oPrivacy = BxDolPrivacy::getObjectInstance($oModule->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);
                if (!$oPrivacy->check($aContentEvent['content_id']))
                    return '';
            }

            $aRv = $oModule->serviceGetNotificationsPost(array(
                'object_id' => $aContentEvent['content_id']
            ));

            $aRv['lang_key'] = '_bx_channels_ntfs_txt_subobject_added';
            if(method_exists($oModule, 'serviceActAsProfile') && $oModule->serviceActAsProfile())
                $aRv['lang_key'] = '_bx_channels_ntfs_txt_subobject_added_profile';

            $aRv['entry_url'] = bx_absolute_url(str_replace(BX_DOL_URL_ROOT, '', $this->serviceGetLink($aContentEvent['cnl_id'])), '{bx_url_root}');
            return $aRv;
        }

        return '';
    }
    
    public function serviceBrowseByLevel ($iLevelId = 0, $bDisplayEmptyMsg = false)
    {
        return $this->_serviceBrowse ('level', array('level' => $iLevelId), BX_DB_PADDING_DEF, $bDisplayEmptyMsg);
    }
    
    public function serviceBrowseFollowed($iProfileId = 0, $aParams = array())
    {
        return $this->_serviceBrowseWithParam ('followed_entries', 'profile_id', $iProfileId, $aParams);
    }

    public function serviceBrowseAuthor($iProfileId = 0, $aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($iProfileId))
            $iProfileId = bx_get_logged_profile_id();

        $sResult = isset($aParams['empty_message']) && (bool)$aParams['empty_message'] === true ? MsgBox(_t('_Empty')) : '';

        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
        if(!$oConnection)
            return $sResult;

        $aProfiles = $oConnection->getConnectedContentByType($iProfileId, $this->getName());

        $aTmplVars = [];
        foreach ($aProfiles as $iProfileId) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if(!$oProfile)
                continue;

            $aTmplVars[] = [
                'unit' => $oProfile->getUnit(0, ['template' => 'unit_wo_cover'])
            ];
        }

        if(empty($aTmplVars) || !is_array($aTmplVars))
            return $sResult;

        return $this->_oTemplate->parseHtmlByName('my_channels.html', [
            'bx_repeat:items' => $aTmplVars
        ]);
    }

    public function serviceDeleteProfileFromFansAndAdmins ($iProfileId)
    {
        return true;
    }

    public function serviceGetFollowingChannelsNames($iProfileId)
    {
        if (!($oConn = BxDolConnection::getObjectInstance('sys_profiles_subscriptions')))
            return array();

        if (!($aIds = $oConn->getConnectedContent($iProfileId)))
            return array();

        $a = array();
        foreach ($aIds as $iId) {
            if (!($oProfile = BxDolProfile::getInstance($iId)))
                continue;
            if ($oProfile->getModule() != $this->getName())
                continue;
            $a[] = $oProfile->getDisplayName();
        }
        return $a;
    }

    public function checkAllowedCompose(&$aDataEntry, $isPerformAction = false)
    {
        return _t('_sys_txt_access_denied');
    }

    public function checkAllowedContact($aDataEntry, $isPerformAction = false)
    {
        return _t('_sys_txt_access_denied');
    }

    public function followLabels($sModule, $iContentId)
    {
        if (!getParam('bx_channels_labels_autofollow'))
            return;
        
        if (!($oModuleProfiles = BxDolModule::getInstance($sModule)))
            return;

        if (!($aContentInfo = $oModuleProfiles->_oDb->getContentInfoById($iContentId)) || !($oProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $sModule)))
            return;

        $CNF = &$oModuleProfiles->_oConfig->CNF;
        
        if (!$oModuleProfiles->serviceActAsProfile() || !isset($CNF['FIELD_LABELS']) || empty($aContentInfo[$CNF['FIELD_ID']]))
            return;

        if (!($oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS'])) || !$oMetatags->keywordsIsEnabled() || !($aLabels = $oMetatags->keywordsGet($aContentInfo[$CNF['FIELD_ID']])))
            return;

        if (!($oConn = BxDolConnection::getObjectInstance('sys_profiles_subscriptions')))
            return;

        foreach ($aLabels as $sLabel) {

            if (!($iContentIdChannel = $this->_oDb->getChannelIdByName($sLabel)))
                continue;

            if (!($oProfileChannel = BxDolProfile::getInstanceByContentAndType($iContentIdChannel, 'bx_channels')))
                continue;

            $oConn->addConnection($oProfile->id(), $oProfileChannel->id());
        }
    }

    /** Returns profile's subscribed channels  React Jot
     *
     * @param int $iProfileId
     * @param array $aParams
     * @return array
     */

    public function serviceBrowseAuthorChannels($iProfileId = 0, $aParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($iProfileId))
            $iProfileId = bx_get_logged_profile_id();

        $oConnection = BxDolConnection::getObjectInstance('sys_profiles_subscriptions');
        $aProfile = $oConnection->getConnectedContent($iProfileId);
        $aVars = array();
        foreach ($aProfile as $iProfileId) {
            $oProfile = BxDolProfile::getInstance($iProfileId);
            if (!$oProfile || $oProfile->getModule() != $this->getName())
                continue;

            $iContentId = $oProfile->getContentId();
            $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

            if (isset($aContentInfo[$CNF['FIELD_NAME']]))
                array_push($aVars, array(
                    'title' => $aContentInfo[$CNF['FIELD_NAME']],
                    'link' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId)),
                    'followers' => $oConnection -> getConnectedInitiatorsCount($iProfileId),
                    'id' => $iProfileId,
                    'icon' => $oProfile -> getThumb()
                ));
        }

        return $aVars;
    }
}

/** @} */
