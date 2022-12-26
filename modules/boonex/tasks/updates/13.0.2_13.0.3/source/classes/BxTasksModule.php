<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Tasks module
 */
class BxTasksModule extends BxBaseModTextModule implements iBxDolCalendarService 
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_PUBLISHED'],
            $CNF['FIELD_ALLOW_COMMENTS']
        ));
    }
	
    /**
    * Action methods
    */
	
    /**
     * Get possible recipients for start conversation form
     */
    public function actionAjaxGetInitialMembers ()
    {
        $sTerm = bx_get('term');

        $a = BxDolService::call('system', 'profiles_search', array($sTerm), 'TemplServiceProfiles');

        header('Content-Type:text/javascript; charset=utf-8');
        echo(json_encode($a));
    }
          
    public function actionSetCompleted($iContentId, $iValue)
    {
        if (!$this->isAllowManage($iContentId))
            return;
        
        $CNF = &$this->_oConfig->CNF;

        $this->_oDb->updateEntriesBy(array($CNF['FIELD_COMPLETED'] => $iValue), array($CNF['FIELD_ID'] => (int)$iContentId));

        $sActionName = 'completed';
        if($iValue == '0')
            $sActionName = 'reopened';

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        $iContentAuthor = (int)$aContentInfo[$CNF['FIELD_AUTHOR']];
        bx_alert($this->getName(), $sActionName, $iContentId, false, array(
            'object_author_id' => $iContentAuthor,
            'privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]
        ));

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);
        if($oConnection) {
            $aProfileIds = $oConnection->getConnectedContent($iContentId);
            if(!empty($aProfileIds) && is_array($aProfileIds))
                foreach($aProfileIds as $iProfileId) {
                    if($iProfileId == $iContentAuthor)
                        continue;

                    bx_alert($this->getName(), $sActionName, $iContentId, false, array(
                        'object_author_id' => $iProfileId,
                        'privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]
                    ));
                }
        }

        echo 'ok';
    }
	
	public function actionSetFilterValue($iListId, $sValue)
	{
		$CNF = &$this->_oConfig->CNF;
		$aTmp = array();
		if (isset($_COOKIE[$CNF['COOKIE_SETTING_KEY']]))
			$aTmp =	json_decode($_COOKIE[$CNF['COOKIE_SETTING_KEY']], true);
			
		if ($sValue != '')
			$aTmp[$iListId] = $sValue;
		else
			unset($aTmp[$iListId]);
        bx_setcookie($CNF['COOKIE_SETTING_KEY'], json_encode($aTmp), time() + 60*60*24*365);
	}
	
	public function actionProcessTaskListForm($iContextId, $iId)
    {
        if (!$this->isAllowAdd(-$iContextId))
            return;
        
		$CNF = &$this->_oConfig->CNF;
		$oForm = null;
		$sPopupTitle = "";
		$aContentInfo = array();
		if ($iId == 0){
			$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_LIST_ENTRY'], $CNF['OBJECT_FORM_LIST_ENTRY_DISPLAY_ADD']);
			$sPopupTitle = _t('_bx_tasks_form_list_entry_display_add');
		}
		else{
			$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_LIST_ENTRY'], $CNF['OBJECT_FORM_LIST_ENTRY_DISPLAY_EDIT']);
			$aContentInfo = $this->_oDb->getList($iId);
			$sPopupTitle = _t('_bx_tasks_form_list_entry_display_edit');
		}
		
		$oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'process_task_list_form/' . $iContextId . '/' . $iId . '/';
		if (!$oForm)
            return '';
		
		$oForm->initChecker($aContentInfo, array());
		
        if($oForm->isSubmittedAndValid()) {
			if ($iId == 0){
				$aValsToAdd['context_id'] = $iContextId;
				$iId = $oForm->insert($aValsToAdd);
			}
			else{
				$iId = $oForm->update($iId);
			}

			return echoJson(array(
				 'eval' => $this->_oConfig->getJsObject('tasks') . '.reloadData(oData, ' . $iContextId . ')',
			));
        }
        else {	
			$sContent = $this->_oTemplate->parseHtmlByName('popup_form.html', array(
				'form_id' => $oForm->getId(),
				'form' => $oForm->getCode(true)
			));
																	 
			if (!$oForm->isSubmitted()){
				echo $sContent;
				return;
			}
			
            return echoJson(array('form' => $sContent, 'form_id' => $oForm->getId()));;
        }
	}
    
    public function actionDeleteTaskList($iId, $iContextId)
    {
        if (!$this->isAllowManageByContext($iContextId))
            return;
        
        $this->_oDb->deleteList($iId);  
        echoJson(array(
            'context_id' => $iContextId,
        ));
    }
	
	public function actionProcessTaskForm($iContextId, $iListId)
    {
        if (!$this->isAllowAdd(-$iContextId))
            return;
        
		$CNF = &$this->_oConfig->CNF;
		$oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']);
		
		$oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'process_task_form/' . $iContextId . '/' . $iListId . '/';
		if (!$oForm)
            return '';
		
		$oForm->initChecker();
		
        if($oForm->isSubmittedAndValid()) {
			$aValsToAdd[$CNF['FIELD_ALLOW_VIEW_TO']] = $iContextId;
			$aValsToAdd[$CNF['FIELD_TASKLIST']] = $iListId;
			$iContentId = $oForm->insert($aValsToAdd);
			$this->onPublished($iContentId);
			
			return echoJson(array(
				 'eval' => $this->_oConfig->getJsObject('tasks') . '.reloadData(oData, ' . $iContextId . ')',
			));
        }
        else {	
			$sContent = $this->_oTemplate->parseHtmlByName('popup_form.html', array(
				'form_id' => $oForm->getId(),
				'form' => $oForm->getCode(true)
			));
																	 
			if (!$oForm->isSubmitted()){
				echo $sContent;
				return;
			}
            return echoJson(array('form' => $sContent, 'form_id' => $oForm->getId()));;
        }
	}
	
	public function actionCalendarData()
    {
        // check permissions
        $aSQLPart = array();
        $iContextId = (int)bx_get('context_id');
        
        if (!$this->isAllowView($iContextId))
            return; 
		
		$oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);
		
		if($iContextId){
			$aSQLPart = $oPrivacy ? $oPrivacy->getContentByGroupAsSQLPart(- $iContextId) : array();
		}
        // get entries
        $aEntries = $this->_oDb->getEntriesByDate(bx_get('start'), bx_get('end'), bx_get('event'), $aSQLPart);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($aEntries);
    }
	
	/**
     * Data for Timeline module
     */
    public function serviceGetTimelineData()
    {
    	$sModule = $this->_aModule['name'];
        return array(
            'handlers' => array(
                array('group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'added', 'module_name' => $sModule, 'module_method' => 'get_timeline_post', 'module_class' => 'Module', 'groupable' => 0, 'group_by' => ''),
				array('group' => $sModule . '_completed', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'completed', 'module_name' => $sModule, 'module_method' => 'get_timeline_completed', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
				array('group' => $sModule . '_reopened', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'reopened', 'module_name' => $sModule, 'module_method' => 'get_timeline_reopened', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
                array('group' => $sModule . '_object', 'type' => 'update', 'alert_unit' => $sModule, 'alert_action' => 'edited'),
                array('group' => $sModule . '_object', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'deleted'),
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'added'),
				array('unit' => $sModule, 'action' => 'completed'),
				array('unit' => $sModule, 'action' => 'reopened'),
                array('unit' => $sModule, 'action' => 'edited'),
                array('unit' => $sModule, 'action' => 'deleted'),
            )
        );
    }
	
    /**
     * Entry task for Timeline module
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;
        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];
        return $aResult;
    }
	
	public function serviceGetTimelineCompleted($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
		$aResult['sample_action'] = $aResult['content']['sample_action'] = _t('_bx_tasks_txt_action_completed');
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;
		
        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];
        return $aResult;
    }
	
	public function serviceGetTimelineReopened($aEvent, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
		$aResult['sample_action'] = $aResult['content']['sample_action'] = _t('_bx_tasks_txt_action_reopened');
        if(empty($aResult) || !is_array($aResult) || empty($aResult['date']))
            return $aResult;
		
        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if($aContentInfo[$CNF['FIELD_PUBLISHED']] > $aResult['date'])
            $aResult['date'] = $aContentInfo[$CNF['FIELD_PUBLISHED']];
        return $aResult;
    }

    public function serviceGetNotificationsData()
    {
        $sModule = $this->_aModule['name'];

        $sEventPrivacy = $sModule . '_allow_view_event_to';
        if(BxDolPrivacy::getObjectInstance($sEventPrivacy) === false)
                $sEventPrivacy = '';

        $aResult = parent::serviceGetNotificationsData();
        $aResult['handlers'] = array_merge($aResult['handlers'], array(
            array('group' => $sModule . '_completed', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'completed', 'module_name' => $sModule, 'module_method' => 'get_notifications_completed', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_reopened', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'reopened', 'module_name' => $sModule, 'module_method' => 'get_notifications_reopened', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_expired', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'expired', 'module_name' => $sModule, 'module_method' => 'get_notifications_expired', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),

            array('group' => $sModule . '_assign', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'assigned', 'module_name' => $sModule, 'module_method' => 'get_notifications_assigned', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_assign', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'unassigned'),
        ));

        $aResult['settings'] = array_merge($aResult['settings'], array(
            array('group' => 'content', 'unit' => $sModule, 'action' => 'completed', 'types' => array('personal', 'follow_member', 'follow_context')),
            array('group' => 'content', 'unit' => $sModule, 'action' => 'reopened', 'types' => array('personal', 'follow_member', 'follow_context')),
            array('group' => 'content', 'unit' => $sModule, 'action' => 'expired', 'types' => array('personal')),
            array('group' => 'content', 'unit' => $sModule, 'action' => 'assigned', 'types' => array('personal')),
        ));

        $aResult['alerts'] = array_merge($aResult['alerts'], array(
            array('unit' => $sModule, 'action' => 'completed'),
            array('unit' => $sModule, 'action' => 'reopened'),
            array('unit' => $sModule, 'action' => 'expired'),

            array('unit' => $sModule, 'action' => 'assigned'),
            array('unit' => $sModule, 'action' => 'unassigned'),
        ));

        return $aResult; 
    }

    public function serviceGetNotificationsCompleted($aEvent)
    {
        return $this->_serviceGetNotificationsByAction($aEvent, 'completed');
    }

    public function serviceGetNotificationsReopened($aEvent)
    {
        return $this->_serviceGetNotificationsByAction($aEvent, 'reopened');
    }

    public function serviceGetNotificationsExpired($aEvent)
    {
        return $this->_serviceGetNotificationsByAction($aEvent, 'expired');
    }

    public function serviceGetNotificationsAssigned($aEvent)
    {
        return $this->_serviceGetNotificationsByAction($aEvent, 'assigned');
    }

    protected function _serviceGetNotificationsByAction($aEvent, $sAction)
    {
        $CNF = &$this->_oConfig->CNF;

        $aResult = parent::serviceGetNotificationsPost($aEvent);
        if(empty($aResult) || !is_array($aResult))
            return $aResult;

        $aResult['entry_author'] = $aEvent['object_owner_id'];
        $aResult['entry_author_name'] = '';
        if(($oAuthor = BxDolProfile::getInstance($aResult['entry_author'])) !== false)
            $aResult['entry_author_name'] = $oAuthor->getDisplayName();

        $sLangKey = '_bx_tasks_txt_notification_' . $sAction;
        if((int)$aEvent['object_privacy_view'] < 0)
            $sLangKey .= '_in_context';

        $aResult['lang_key'] = _t($sLangKey);
        return $aResult;
    }

    public function serviceIsCompleted($iContentId)
    {
        if (!$this->serviceIsAllowManage($iContentId))
            return false;
        
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        return $aContentInfo[$CNF['FIELD_COMPLETED']] ? false: true;
    }
    
    public function serviceIsUncompleted($iContentId)
    {
        if (!$this->serviceIsAllowManage($iContentId))
            return false;
        
		$CNF = &$this->_oConfig->CNF;
		$aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        return $aContentInfo[$CNF['FIELD_COMPLETED']] ? true : false;
    }
    
    public function serviceIsAllowManage($iContentId)
    {
        if (!$this->isAllowManage($iContentId))
            return false;
        return true; 
    }
    
    public function serviceIsAllowBadges($iContentId)
    {
        if (!$this->isAllowManage($iContentId))
            return false;
        
        if (!$this->serviceIsBadgesAvaliable())
            return false;
        
        return true; 
    }
	
	public function serviceEntityAssignments($iContentId = 0, $bAsArray = false)
	{
		if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

		$mixedResult = BxDolConnection::getObjectInstance($this->_oConfig->CNF['OBJECT_CONNECTION'])->getConnectedContent($iContentId);
        if(!$bAsArray) {
			$s = '';
            foreach ($mixedResult as $mixedProfile) {
				$bProfile = is_array($mixedProfile);

				$oProfile = BxDolProfile::getInstance($bProfile ? (int)$mixedProfile['id'] : (int)$mixedProfile);
				if(!$oProfile)
					continue;

				$aUnitParams = array('template' => array('name' => 'unit', 'size' => 'thumb'));

				if($bProfile && is_array($mixedProfile['info']))
					$aUnitParams['template']['vars'] = $mixedProfile['info'];

				$s .= $oProfile->getUnit(0, $aUnitParams);
			}
			$mixedResult = $s;
			
            if (!$mixedResult)
                return MsgBox(_t('_sys_txt_empty'));
        }

        return $mixedResult;
    }
	
    public function serviceCheckAllowedCommentsTask($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if ($aContentInfo[$CNF['FIELD_ALLOW_COMMENTS']] == 0)
            return false;

        return parent::serviceCheckAllowedCommentsTask($iContentId, $sObjectComments);
    }
	
	public function serviceCheckAllowedCommentsView($iContentId, $sObjectComments) 
    {
        $CNF = &$this->_oConfig->CNF;
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if ($aContentInfo[$CNF['FIELD_ALLOW_COMMENTS']] == 0)
            return false;

        return parent::serviceCheckAllowedCommentsView($iContentId, $sObjectComments);
    }
	
	/**
     * @page service Service Calls
     * @section bx_tasks Tasks
     * @subsection bx_tasks-page_blocks Page Blocks
     * @subsubsection bx_tasks-calendar calendar
     * 
     * @code bx_srv('bx_tasks', 'calendar', [...]); @endcode
     * 
     * Shows tasks calendar baced on die date
     * 
     * @param $aData additional data to point which events to show, leave empty to show all events, specify event's ID in 'event' array key to show calendar for one event only, specify context's ID in 'context_id' array key to show calendar for one context events only. If only one event is specified then it will show calendar only if it's repeating event.
     * @param $sTemplate template to use to show calendar, or leave empty for default template, possible options: calendar.html, calendar_compact.html
     * @return HTML string with calendar to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. On error empty string is returned.
     *
     * @see BxTasksModule::serviceCalendar
     */
    /** 
     * @ref bx_tasks-calendar "calendar"
     */
    public function serviceCalendar($aData = array(), $sTemplate = 'calendar.html')
    {
        if (!$this->isAllowView($aData['context_id']))
            return; 
        
        $o = new BxTemplCalendar(array(
            'eventSources' => array (
                bx_append_url_params(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'calendar_data', $aData),
            ),
        ), $this->_oTemplate);
        return $o->display($sTemplate);
    }
	
	public function serviceGetCalendarEntries($iProfileId)
    {
		$CNF = &$this->_oConfig->CNF;
        $oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);
        $aData = $oConn->getConnectedInitiators($iProfileId);
		$aData2 = array(0);
        foreach($aData as $iProfileId2){
            $oProfile = BxDolProfile::getInstance($iProfileId2);
            array_push($aData2, $oProfile->getContentId());
        }
        $aSQLPart['where'] = " AND " . $CNF['TABLE_ENTRIES'] . ".`" . $CNF['FIELD_ID'] . "` IN(" . implode(',', $aData2) . ")";
        return $this->_oDb->getEntriesByDate(bx_get('start'), bx_get('end'), null, $aSQLPart);
	}
	
	public function serviceBrowseContext ($iProfileId = 0, $aParams = array())
    {
		if(!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if(!$iProfileId)
            return '';
        return $this->serviceBrowseTasks (-$iProfileId, $aParams);
    }
	
	public function serviceBrowseTasks ($iContextId = 0, $aParams = array())
    {
        if (!$this->isAllowView(-$iContextId))
            return;  
        
        $oProfileContext = BxDolProfile::getInstance(-$iContextId);
        $mixedResult = $oProfileContext->checkAllowedProfileView(-$iContextId);
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return false;
        
		$CNF = &$this->_oConfig->CNF;
		
		$this->_oTemplate->addCssJs();
		$aVars = array();
		$aLists = $this->_oDb->getLists($iContextId);
		$aListsVars = array();
		$oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);
		
		$aFilterValues = array();
		if (isset($_COOKIE[$CNF['COOKIE_SETTING_KEY']]))
			$aFilterValues = json_decode($_COOKIE[$CNF['COOKIE_SETTING_KEY']], true);

        $bAllowAdd = $this->isAllowAdd(-$iContextId);
        $bAllowManage = $this->isAllowManageByContext(-$iContextId);
        
        foreach($aLists as $aList) {
			$aTasks = $this->_oDb->getTasks($iContextId, $aList['id']);
			$aTasksVars = array();
			foreach($aTasks as $aTask) {
				$aMembers = $oConn->getConnectedContent($aTask[$CNF['FIELD_ID']]);
				$aMembersVars = array();
				foreach($aMembers as $iMember) {
					$oProfile = BxDolProfile::getInstance($iMember);
					$aMembersVars[] = array('info' => $oProfile->getUnit(0, array('template' => 'unit_wo_info')));
				}
				$aTasksVars[] = array(
					'id' => $aTask[$CNF['FIELD_ID']],
					'title' => $aTask[$CNF['FIELD_TITLE']],
					'created' => bx_time_js($aTask[$CNF['FIELD_ADDED']]),
					'class' => $aTask[$CNF['FIELD_COMPLETED']] == 1 ? 'completed' : 'uncompleted',
					'due' => $aTask[$CNF['FIELD_DUEDATE']] > 0 ? bx_time_js($aTask[$CNF['FIELD_DUEDATE']]) : '',
					'bx_repeat:members' => $aMembersVars,
					'badges' => $this->serviceGetBadges($aTask[$CNF['FIELD_ID']], true),
					'url' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aTask[$CNF['FIELD_ID']])),
					'object' => $this->_oConfig->getJsObject('tasks'),
                    'bx_if:allow_manage' => array(
				        'condition' => $bAllowManage,
				        'content' => array(
                            'id' => $aTask[$CNF['FIELD_ID']],
                            'object' => $this->_oConfig->getJsObject('tasks'),
                            'checked' => $aTask[$CNF['FIELD_COMPLETED']] == 1 ? 'checked' : '',
                        )
			        ),
                    'bx_if:deny_manage' => array(
				        'condition' => !$bAllowManage,
				        'content' => array(
                            'id' => $aTask[$CNF['FIELD_ID']],
                            'checked' => $aTask[$CNF['FIELD_COMPLETED']] == 1 ? 'checked' : '',
                        )
			        ),
				);
			}
            
			$sClass = $sCompleted = $sAll = "";
			if (isset($aFilterValues[$aList[$CNF['FIELD_ID']]])){
				$sClass = $aFilterValues[$aList[$CNF['FIELD_ID']]];
				if ($sClass == 'completed')
					$sCompleted= 'selected';
				if ($sClass == 'all')
					$sAll = 'selected';
			}
            
			$aListsVars[] = array(
               'bx_if:allow_edit_list' => array(
				    'condition' => $bAllowAdd,
				    'content' => array(
                        'title' => $aList[$CNF['FIELD_TITLE']],
                        'context_id' => $iContextId,
                        'list_id' => $aList[$CNF['FIELD_ID']],
                        'object' => $this->_oConfig->getJsObject('tasks'),
                    )
			    ),
               'bx_if:allow_add' => array(
				    'condition' => $bAllowAdd,
				    'content' => array(
                        'context_id' => $iContextId,
                        'list_id' => $aList[$CNF['FIELD_ID']],
                        'object' => $this->_oConfig->getJsObject('tasks'),
                    )
			    ),
                'bx_if:allow_delete_list' => array(
				    'condition' => $bAllowManage,
				    'content' => array(
                        'context_id' => $iContextId,
                        'list_id' => $aList[$CNF['FIELD_ID']],
                        'object' => $this->_oConfig->getJsObject('tasks'),
                    )
			    ),
                'bx_if:deny_edit_list' => array(
				    'condition' => !$bAllowAdd,
				    'content' => array(
                        'title' => $aList[$CNF['FIELD_TITLE']],
                    )
			    ),
				'id' => $aList['id'],
				'bx_repeat:tasks' =>  $aTasksVars,
				'context_id' => $iContextId,
				'list_id' => $aList[$CNF['FIELD_ID']],
				'object' => $this->_oConfig->getJsObject('tasks'),
				'class' => $sClass,
				'completed' => $sCompleted,
				'all' => $sAll,
			);
		}
		
		$aVars = array(
			'bx_repeat:task_lists' => $aListsVars,
			'bx_if:allow_add_list' => array(
				'condition' => $bAllowAdd,
				'content' => array(
                    'context_id' => $iContextId,
                    'object' => $this->_oConfig->getJsObject('tasks'),
                )
			),
		);
		
		$this->_oTemplate->addJs(array(
			'jquery-ui/jquery-ui.min.js',
			'tasks.js',
            'modules/base/general/js/|forms.js'
    	));
		
		return $this->_oTemplate->getJsCode('tasks', array('t_confirm_block_deletion' => _t('_bx_tasks_confirm_tasklist_deletion'))) . $this->_oTemplate->parseHtmlByName('browse_tasks.html', $aVars);
    }
	
    /**
    * Common methods
    */
    public function onExpired($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(!$aContentInfo)
            return;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);
        if($oConnection) {
            $aProfileIds = $oConnection->getConnectedContent($iContentId);
            if(!empty($aProfileIds) && is_array($aProfileIds))
                foreach($aProfileIds as $iProfileId)
                    bx_alert($this->getName(), 'expired', $iContentId, false, array(
                        'object_author_id' => $iProfileId,
                        'privacy_view' => $aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]
                    ));
        }
    }
    
    function isAllowView($iContextId)
    {
        $oProfileContext = BxDolProfile::getInstance($iContextId);
        $mixedResult = $oProfileContext->checkAllowedProfileView($iContextId);
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return false;
        return true;
    }
    
    function isAllowAdd($iContextId)
    {
        $oProfileContext = BxDolProfile::getInstance($iContextId);
        $mixedResult = $oProfileContext->checkAllowedPostInProfile($iContextId);
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return false;
        return true;
    }
    
    function isAllowManageByContext($iContextId)
    {
        if(isAdmin())
            return true;
      
        $oProfileContext = BxDolProfile::getInstance($iContextId);
        if(BxDolService::call($oProfileContext->getModule(), 'is_admin', array($iContextId)))
            return true;
    }
    
    function isAllowManage($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;
        
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        $bRv = $this->isAllowManageByContext(-$aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']]);
        if ($bRv)
            return $bRv;
        
        if ($aContentInfo[$CNF['FIELD_AUTHOR']] == bx_get_logged_profile_id())
            return true;
       
        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION']);
        if($oConnection) {
            $aProfileIds = $oConnection->getConnectedContent($iContentId);
            if(!empty($aProfileIds) && is_array($aProfileIds)){
                if (in_array(bx_get_logged_profile_id(), $aProfileIds))
                    return true;
            }
        }

        return false;
    }
}

/** @} */
