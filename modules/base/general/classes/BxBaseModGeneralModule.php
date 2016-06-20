<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAcl');

/**
 * Base module class.
 */
class BxBaseModGeneralModule extends BxDolModule
{
    protected $_iProfileId;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);
        $this->_iProfileId = bx_get_logged_profile_id();
    }

    // ====== ACTIONS METHODS

    public function actionRss ()
    {
        $aArgs = func_get_args();
        $this->_rss($aArgs);
    }

    // ====== SERVICE METHODS

    public function serviceModuleIcon ()
    {
        return isset($this->_oConfig->CNF['ICON']) ? $this->_oConfig->CNF['ICON'] : '';
    }

	public function serviceManageTools($sType = 'common')
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getGridObject($sType));
        if(!$oGrid)
            return '';

		$CNF = &$this->_oConfig->CNF;

		$sMenu = '';
		if(BxDolAcl::getInstance()->isMemberLevelInSet(192)) {
			$oPermalink = BxDolPermalinks::getInstance();

			$aMenuItems = array();
			if(!empty($CNF['OBJECT_GRID_COMMON']) && !empty($CNF['T']['menu_item_manage_my']))
				$aMenuItems[] = array('id' => 'manage-common', 'name' => 'manage-common', 'class' => '', 'link' => $oPermalink->permalink($CNF['URL_MANAGE_COMMON']), 'target' => '_self', 'title' => _t($CNF['T']['menu_item_manage_my']), 'active' => 1);
			if(!empty($CNF['OBJECT_GRID_ADMINISTRATION']) && !empty($CNF['T']['menu_item_manage_all']))
				$aMenuItems[] = array('id' => 'manage-administration', 'name' => 'manage-administration', 'class' => '', 'link' => $oPermalink->permalink($CNF['URL_MANAGE_ADMINISTRATION']), 'target' => '_self', 'title' => _t($CNF['T']['menu_item_manage_all']), 'active' => 1);

			if(count($aMenuItems) > 1) {
	            $oMenu = new BxTemplMenu(array(
	            	'template' => 'menu_vertical.html', 
	            	'menu_items' => $aMenuItems
	            ), $this->_oTemplate);
	            $oMenu->setSelected($this->_aModule['name'], 'manage-' . $sType);
	            $sMenu = $oMenu->getCode();
			}
		}

		if(!empty($CNF['OBJECT_MENU_SUBMENU'])) {
			BxDolMenu::getObjectInstance($CNF['OBJECT_MENU_SUBMENU'])->setSelected($this->_aModule['name'], $CNF['URI_MANAGE_COMMON']);
		}

        $this->_oTemplate->addCss(array('manage_tools.css'));
        $this->_oTemplate->addJs(array('manage_tools.js'));
        $this->_oTemplate->addJsTranslation(array('_sys_grid_search'));
        return array(
        	'content' => $this->_oTemplate->getJsCode('manage_tools', array('sObjNameGrid' => $this->_oConfig->getGridObject($sType))) . $oGrid->getCode(),
        	'menu' => $sMenu
        );
    }

    public function serviceGetMenuAddonManageTools()
    {
    	return 0;
    }
    
    public function serviceGetMenuAddonManageToolsProfileStats()
    {
    	return 0;
    }

    public function serviceFormsHelper ()
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        return new $sClass($this);
    }

    /**
     * Add entry form
     * @return HTML string
     */
    public function serviceEntityCreate ()
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->addDataForm();
    }

    public function serviceEntityEdit ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('editDataForm', $iContentId);
    }

    public function serviceEntityDelete ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('deleteDataForm', $iContentId);
    }

    public function serviceEntityTextBlock ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('viewDataEntry', $iContentId);
    }

    public function serviceEntityInfo ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('viewDataForm', $iContentId);
    }

	public function serviceEntityInfoFull ($iContentId = 0)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sDisplayName = 'OBJECT_FORM_ENTRY_DISPLAY_VIEW_FULL';
        return $this->_serviceEntityForm ('viewDataForm', $iContentId, !empty($CNF[$sDisplayName]) ? $CNF[$sDisplayName] : false);
    }

	public function serviceEntityInfoExtended ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryInfo', $iContentId);
    }
    
    /**
     * Delete content entry
     * @param $iContentId content id 
     * @return error message or empty string on success
     */
    public function serviceDeleteEntity ($iContentId, $sFuncDelete = 'deleteData')
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_oConfig->getClassPrefix() . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->$sFuncDelete($iContentId);
    }

    /**
     * Entry actions block
     */
    public function serviceEntityActions ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * Entry social sharing block
     */
    public function serviceEntitySocialSharing ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if (!$aContentInfo)
            return false;

        $CNF = &$this->_oConfig->CNF;

        return $this->_entitySocialSharing ($iContentId, $iContentId, $aContentInfo[$CNF['FIELD_THUMB']], $aContentInfo[$CNF['FIELD_TITLE']], $CNF['OBJECT_STORAGE'], false, $CNF['OBJECT_VOTES'], $CNF['OBJECT_REPORTS'], $CNF['URI_VIEW_ENTRY']);
    }

	/**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {
    	$sModule = $this->_aModule['name'];

    	$sEventPrivacy = $sModule . '_allow_view_event_to';
		if(BxDolPrivacy::getObjectInstance($sEventPrivacy) === false)
			$sEventPrivacy = '';

        return array(
            'handlers' => array(
                array('group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'added', 'module_name' => $sModule, 'module_method' => 'get_notifications_post', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
                array('group' => $sModule . '_object', 'type' => 'update', 'alert_unit' => $sModule, 'alert_action' => 'edited'),
                array('group' => $sModule . '_object', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'deleted'),
                
                array('group' => $sModule . '_comment', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'commentPost', 'module_name' => $sModule, 'module_method' => 'get_notifications_comment', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
                array('group' => $sModule . '_comment', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'commentRemoved'),
                
                array('group' => $sModule . '_vote', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
				array('group' => $sModule . '_vote', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'undoVote'),
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'added'),
                array('unit' => $sModule, 'action' => 'edited'),
                array('unit' => $sModule, 'action' => 'deleted'),

                array('unit' => $sModule, 'action' => 'commentPost'),
                array('unit' => $sModule, 'action' => 'commentRemoved'),
                
                array('unit' => $sModule, 'action' => 'doVote'),
                array('unit' => $sModule, 'action' => 'undoVote'),
            )
        );
    }

    /**
     * Entry post for Notifications module
     */
    public function serviceGetNotificationsPost($aEvent)
    {
		$CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single'],
			'entry_url' => $sEntryUrl,
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
			'entry_privacy' => '', //may be empty or not specified. In this case Public privacy will be used.
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

	/**
     * Entry post comment for Notifications module
     */
    public function serviceGetNotificationsComment($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

		$oComment = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId);
        if(!$oComment || !$oComment->isEnabled())
            return array();

        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single'],
			'entry_url' => $sEntryUrl,
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
			'subentry_sample' => $CNF['T']['txt_sample_comment_single'],
			'subentry_url' => $oComment->getViewUrl((int)$aEvent['subobject_id']),
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

	/**
     * Entry post vote for Notifications module
     */
    public function serviceGetNotificationsVote($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

		$oVote = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $iContentId);
        if(!$oVote || !$oVote->isEnabled())
            return array();

        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
        $sEntryCaption = isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...');

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single'],
			'entry_url' => $sEntryUrl,
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aContentInfo[$CNF['FIELD_AUTHOR']],
			'subentry_sample' => $CNF['T']['txt_sample_vote_single'],
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

    public function _serviceGetNotification($aEvent, $sLangKey)
    {
    	$CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aEvent['object_id'];
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType((int)$iContentId, $this->getName());
        if (!$oGroupProfile)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();
        
        $oProfile = BxDolProfile::getInstance((int)$aEvent['subobject_id']);
        if (!$oProfile)
            return false;

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single'],
			'entry_url' => $oGroupProfile->getUrl(),
			'entry_caption' => $oGroupProfile->getDisplayName(),
			'entry_author' => $oGroupProfile->id(),
			'subentry_sample' => $oProfile->getDisplayName(),
			'subentry_url' => $oProfile->getUrl(),
			'lang_key' => $sLangKey,
		);
    }
    
    /**
     * Data for Timeline module
     */
    public function serviceGetTimelineData()
    {
    	$sModule = $this->_aModule['name'];

        return array(
            'handlers' => array(
                array('group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'added', 'module_name' => $sModule, 'module_method' => 'get_timeline_post', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_object', 'type' => 'update', 'alert_unit' => $sModule, 'alert_action' => 'edited'),
                array('group' => $sModule . '_object', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'deleted')
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'added'),
                array('unit' => $sModule, 'action' => 'edited'),
                array('unit' => $sModule, 'action' => 'deleted'),
            )
        );
    }

    /**
     * Entry post for Timeline module
     */
    public function serviceGetTimelinePost($aEvent)
    {
        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return '';

        $CNF = &$this->_oConfig->CNF;

        //--- Votes
        $oVotes = isset($CNF['OBJECT_VOTES']) ? BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aEvent['object_id']) : null;

        $aVotes = array();
        if ($oVotes && $oVotes->isEnabled())
            $aVotes = array(
                'system' => $CNF['OBJECT_VOTES'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['votes']
            );

		//--- Reports
        $oReports = isset($CNF['OBJECT_REPORTS']) ? BxDolReport::getObjectInstance($CNF['OBJECT_REPORTS'], $aEvent['object_id']) : null;

        $aReports = array();
        if ($oReports && $oReports->isEnabled())
            $aReports = array(
                'system' => $CNF['OBJECT_REPORTS'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['reports']
            );

        //--- Comments
        $oCmts = isset($CNF['OBJECT_COMMENTS']) ? BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $aEvent['object_id']) : null;

        $aComments = array();
        if($oCmts && $oCmts->isEnabled())
            $aComments = array(
                'system' => $CNF['OBJECT_COMMENTS'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['comments']
            );

        return array(
            'owner_id' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'content' => $this->_getContentForTimelinePost($aEvent, $aContentInfo), //a string to display or array to parse default template before displaying.
            'date' => $aContentInfo[$CNF['FIELD_ADDED']],
            'votes' => $aVotes,
            'reports' => $aReports,
            'comments' => $aComments,
            'title' => '', //may be empty.
            'description' => '' //may be empty.
        );
    }

    // ====== PERMISSION METHODS

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    public function checkAllowedBrowse ()
    {
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedView ($aDataEntry, $isPerformAction = false)
    {
        $CNF = &$this->_oConfig->CNF;

        // moderator and owner always have access
        if ($aDataEntry[$CNF['FIELD_AUTHOR']] == $this->_iProfileId || $this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'view entry', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        // check privacy
        if (!empty($CNF['OBJECT_PRIVACY_VIEW'])) {
            $oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW']);
            if ($oPrivacy && !$oPrivacy->check($aDataEntry[$CNF['FIELD_ID']]))
                return _t('_sys_access_denied_to_private_content');
        }

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedAdd ($isPerformAction = false)
    {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'create entry', $this->getName(), $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedEdit ($aDataEntry, $isPerformAction = false)
    {
        // moderator and owner always have access
        if ($aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']] == $this->_iProfileId || $this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;
        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedDelete (&$aDataEntry, $isPerformAction = false)
    {
        // moderator always has access
        if ($this->_isModerator($isPerformAction))
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'delete entry', $this->getName(), $isPerformAction);
        if ($aDataEntry[$this->_oConfig->CNF['FIELD_AUTHOR']] == $this->_iProfileId && $aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
            return CHECK_ACTION_RESULT_ALLOWED;

        return _t('_sys_txt_access_denied');
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedSetMembership (&$aDataEntry, $isPerformAction = false)
    {
        // admin always has access
        if (isAdmin())
            return CHECK_ACTION_RESULT_ALLOWED;

        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'set acl level', 'system', $isPerformAction);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedEditAnyEntry ($isPerformAction = false)
    {
    	$aCheck = checkActionModule($this->_iProfileId, 'edit any entry', $this->getName(), $isPerformAction);
    	if($aCheck[CHECK_ACTION_RESULT] === CHECK_ACTION_RESULT_ALLOWED)
    		return CHECK_ACTION_RESULT_ALLOWED;

    	return _t('_sys_txt_access_denied');
    }

    public function _serviceBrowse ($sMode, $aParams = false, $iDesignBox = BX_DB_PADDING_DEF, $bDisplayEmptyMsg = false, $bAjaxPaginate = true, $sClassSearchResult = 'SearchResult')
    {
        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedBrowse()))
            return MsgBox($sMsg);

        bx_import($sClassSearchResult, $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . $sClassSearchResult;
        $o = new $sClass($sMode, $aParams);

        $o->setDesignBoxTemplateId($iDesignBox);
        $o->setDisplayEmptyMsg($bDisplayEmptyMsg);
        $o->setAjaxPaginate($bAjaxPaginate);
        $o->setUnitParams(array('context' => $sMode));

        if ($o->isError)
            return '';

        if ($s = $o->processing())
            return $s;
        else
            return '';
    }

    // ====== PROTECTED METHODS

    protected function _isModerator ($isPerformAction = false)
    {
        return CHECK_ACTION_RESULT_ALLOWED === $this->checkAllowedEditAnyEntry ($isPerformAction);
    }

    protected function _serviceEntityForm ($sFormMethod, $iContentId = 0, $sDisplay = false)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->$sFormMethod((int)$iContentId, $sDisplay);
    }

    protected function _serviceTemplateFunc ($sFunc, $iContentId, $sFuncGetContent = 'getContentInfoById')
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        $aContentInfo = $this->_oDb->$sFuncGetContent($iContentId);
        if (!$aContentInfo)
            return false;

        return $this->_oTemplate->$sFunc($aContentInfo);
    }

    protected function _rss ($aArgs, $sClass = 'SearchResult')
    {
        $sMode = array_shift($aArgs);

        if (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedBrowse())) {
            $this->_oTemplate->displayAccessDenied ($sMsg);
            exit;
        }

        $aParams = $this->_buildRssParams($sMode, $aArgs);

        bx_import ($sClass, $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . $sClass;
        $o = new $sClass($sMode, $aParams);

        if ($o->isError)
            $this->_oTemplate->displayPageNotFound ();
        else
            $o->outputRSS();

        exit;
    }

    protected function _getContentForTimelinePost($aEvent, $aContentInfo)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

    	//--- Image(s)
        $aImages = $this->_getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl);

    	return array(
			'sample' => _t($CNF['T']['txt_sample_single']),
			'url' => $sUrl,
			'title' => isset($CNF['FIELD_TITLE']) && isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : 
			(isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]) ? strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...') : ''),
			'text' => isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]) ? $aContentInfo[$CNF['FIELD_TEXT']] : '',
			'images' => $aImages,
		);
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl)
    {
        $CNF = &$this->_oConfig->CNF;

        $sImage = '';
        if (isset($CNF['FIELD_THUMB']) && isset($aContentInfo[$CNF['FIELD_THUMB']]) && $aContentInfo[$CNF['FIELD_THUMB']]) {
        	
        	$oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_GALLERY']);
        	if($oTranscoder)
                $sImage = $oTranscoder->getFileUrl($aContentInfo[$CNF['FIELD_THUMB']]);
        }

        if (empty($sImage))
            return array();

        return array(
		    array('url' => $sUrl, 'src' => $sImage),
		);
    }

    protected function _entitySocialSharing ($iId, $iIdForTimeline, $iIdThumb, $sTitle, $sObjectStorage, $sObjectTranscoder, $sObjectVote, $sObjectReport, $sUriViewEntry, $sCommentsObject = '', $bEnableSocialSharing = true)
    {
        $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $sUriViewEntry . '&id=' . $iId);

        $sComments = '';
        if ($sCommentsObject && ($oComments = BxTemplCmts::getObjectInstance($sCommentsObject, $iId))) {
            $iNum = $oComments->getCommentsCountAll();
            $sComments = $this->_oTemplate->parseHtmlByName('comments-item.html', array (
                'url' => $sUrl . '#' . $oComments->getListAnchor(),
                'bx_if:comments' => array (
                    'condition' => $iNum,
                    'content' => array (
                        'num' => $iNum,
                    ),
                ),
            ));
        }

        $aCustomParams = false;
        if ($iIdThumb && $bEnableSocialSharing) {
            if ($sObjectTranscoder)
                $o = BxDolTranscoder::getObjectInstance($sObjectTranscoder);
            else
                $o = BxDolStorage::getObjectInstance($sObjectStorage);

            if ($sImgUrl = $o->getFileUrlById($iIdThumb)) {
                $aCustomParams = array (
                    'img_url' => $sImgUrl,
                    'img_url_encoded' => rawurlencode($sImgUrl),
                );
            }
        }

        //TODO: Rebuild using menus engine when it will be ready for such elements like Vote, Share, etc.
        $sVotes = '';
        $oVotes = BxDolVote::getObjectInstance($sObjectVote, $iId);
        if ($oVotes)
            $sVotes = $oVotes->getElementBlock(array('show_do_vote_as_button' => true));

        $sShare = '';
        if ($iIdForTimeline && BxDolRequest::serviceExists('bx_timeline', 'get_share_element_block'))
            $sShare = BxDolService::call('bx_timeline', 'get_share_element_block', array(bx_get_logged_profile_id(), $this->_aModule['name'], 'added', $iIdForTimeline, array('show_do_share_as_button' => true)));

		$sReport = '';
        $oReport = BxDolReport::getObjectInstance($sObjectReport, $iId);
        if ($oReport)
            $sReport = $oReport->getElementBlock(array('show_do_report_as_button' => true));

        $sSocial = $bEnableSocialSharing ? BxTemplSocialSharing::getInstance()->getCode($iId, $this->_aModule['name'], BX_DOL_URL_ROOT . $sUrl, $sTitle, $aCustomParams) : '';

        return $this->_oTemplate->parseHtmlByName('entry-share.html', array(
            'comments' => $sComments,
            'vote' => $sVotes,
            'share' => $sShare,
        	'report' => $sReport,
            'social' => $sSocial,
        ));
        //TODO: Rebuild using menus engine when it will be ready for such elements like Vote, Share, etc.
    }
}

/** @} */
