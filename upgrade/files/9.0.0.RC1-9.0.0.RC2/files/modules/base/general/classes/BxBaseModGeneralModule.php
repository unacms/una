<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
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
    protected $_aSearchableNamesExcept;

    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_iProfileId = bx_get_logged_profile_id();
        $this->_aSearchableNamesExcept = array(
            'allow_view_to'
        );
    }

    // ====== ACTIONS METHODS

    public function actionRss ()
    {
        $aArgs = func_get_args();
        $this->_rss($aArgs);
    }

    // ====== SERVICE METHODS
    public function serviceGetAuthor ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_AUTHOR', $iContentId);
        return $mixedResult !== false ? (int)$mixedResult : 0; 
    }

    public function serviceGetDateAdded ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_ADDED', $iContentId);
        return $mixedResult !== false ? (int)$mixedResult : 0; 
    }

    public function serviceGetDateChanged ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_CHANGED', $iContentId);
        return $mixedResult !== false ? (int)$mixedResult : 0;
    }

    public function serviceGetLink ($iContentId)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['URI_VIEW_ENTRY']))
            return '';

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo))
            return '';

        return BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
    }

    public function serviceGetTitle ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_TITLE', $iContentId);
        return $mixedResult !== false ? $mixedResult : '';
    }

    public function serviceGetText ($iContentId)
    {
        $mixedResult = $this->_getFieldValue('FIELD_TEXT', $iContentId);
        return $mixedResult !== false ? $mixedResult : '';
    }

    public function serviceGetInfo ($iContentId, $bSearchableFieldsOnly = true)
    {
        $CNF = &$this->_oConfig->CNF;

        $aContentInfo = $this->_getFields($iContentId);
        if(empty($aContentInfo))
            return array();

        if(!$bSearchableFieldsOnly)
            return $aContentInfo;

        if(empty($CNF['PARAM_SEARCHABLE_FIELDS']))
            return array();

        $aFields = explode(',', getParam($CNF['PARAM_SEARCHABLE_FIELDS']));
        if(empty($aFields))
            return array();

        $aResult = array();
        foreach($aFields as $sField)
            if(isset($aContentInfo[$sField]))
                $aResult[$sField] = $aContentInfo[$sField];

        return $aResult;
    }

    public function serviceGetSearchResultUnit ($iContentId, $sUnitTemplate = '')
    {
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo))
            return '';

        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit.html';

        return $this->_oTemplate->unit($aContentInfo, true, $sUnitTemplate);
    }

    public function serviceGetAll ($aParams = array())
    {
        if(empty($aParams) || !is_array($aParams))
            $aParams = array('type' => 'all');

        return $this->_oDb->getEntriesBy($aParams);
    }

    public function serviceGetSearchableFieldsExtended()
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_FORM_ENTRY']) || empty($CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']))
            return array();

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'], $this->_oTemplate);
        if(!$oForm)
            return array();

        $aResult = array();
        if(!empty($CNF['FIELD_AUTHOR']) && !in_array($CNF['FIELD_AUTHOR'], $this->_aSearchableNamesExcept))
            $aResult[$CNF['FIELD_AUTHOR']] = array(
            	'type' => 'text_auto', 
            	'caption' => $CNF['T']['form_field_author'],
            	'values' => '' 
            );

        foreach ($oForm->aInputs as $aInput)
            if (in_array($aInput['type'], BxDolSearchExtended::$SEARCHABLE_TYPES) && !in_array($aInput['name'], $this->_aSearchableNamesExcept))
                $aResult[$aInput['name']] = array(
                	'type' => $aInput['type'], 
                	'caption' => $aInput['caption_src'],
                    'values' => !empty($aInput['values_src']) ? $aInput['values_src'] : '',
                );

        return $aResult;
    }

    public function serviceGetSearchResultExtended($aParams)
    {
        if(empty($aParams) || !is_array($aParams))
            return array();

        return $this->_oDb->getEntriesBy(array('type' => 'search_ids', 'search_params' => $aParams));
    }

    public function serviceModuleIcon ()
    {
        return isset($this->_oConfig->CNF['ICON']) ? $this->_oConfig->CNF['ICON'] : '';
    }

    public function serviceGetSearchableFields ()
    {
        $CNF = $this->_oConfig->CNF;

        if (!isset($CNF['PARAM_SEARCHABLE_FIELDS']) || !isset($CNF['OBJECT_FORM_ENTRY']) || !isset($CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD']))
            return array();

        $aTextTypes = array('text', 'textarea');
        $aTextFields = array();
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_ENTRY'], $CNF['OBJECT_FORM_ENTRY_DISPLAY_ADD'], $this->_oTemplate);
        foreach ($oForm->aInputs as $r) {
            if (in_array($r['type'], $aTextTypes))
                $aTextFields[$r['name']] = $r['caption'];
        }
        return $aTextFields;
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

	/**
     * Display featured entries
     * @return HTML string
     */
    public function serviceBrowseFeatured ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('featured', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

	/**
     * Display entries favored by a member
     * @return HTML string
     */
    public function serviceBrowseFavorite ($iProfileId = 0, $aParams = array())
    {
        $oProfile = null;
        if((int)$iProfileId)
            $oProfile = BxDolProfile::getInstance($iProfileId);
        if(!$oProfile && bx_get('profile_id') !== false)
            $oProfile = BxDolProfile:: getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT));
        if(!$oProfile)
            $oProfile = BxDolProfile::getInstance();
        if(!$oProfile)
            return '';

        return $this->_serviceBrowse ('favorite', array_merge(array('user' => $oProfile->id()), $aParams), BX_DB_PADDING_DEF, true);
    }

    public function serviceFormsHelper ()
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        return new $sClass($this);
    }

	/**
     * Add entry using provided fields' values.
     * @return HTML string
     */
    public function serviceEntityAdd ($iAccount, $aValues)
    {
        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->addData($iAccount, $aValues);
    }

    /**
     * Create entry form
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
     * Entry location info
     */
    public function serviceEntityLocation ($iContentId = 0)
    {
        $iContentId = $this->_getContent($iContentId, false);
        if($iContentId === false)
            return false;

        return $this->_oTemplate->entryLocation ($iContentId);
    }

    /**
     * Entry comments
     */
    public function serviceEntityComments ($iContentId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF['OBJECT_COMMENTS']))
            return '';

        return $this->_entityComments($CNF['OBJECT_COMMENTS'], $iContentId);
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
     * Entry actions and social sharing block
     */
    public function serviceEntityAllActions ($iContentId = 0)
    {
        return $this->_oTemplate->entryAllActions('', $this->serviceEntitySocialSharing($iContentId));
    }

    /**
     * Entry actions block
     */
    public function serviceEntityActions ($iContentId = 0)
    {
        $iContentId = $this->_getContent($iContentId, false);
        if($iContentId === false)
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_VIEW_ENTRY']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * Entry social sharing block
     */
    public function serviceEntitySocialSharing ($iContentId = 0)
    {
        $mixedContent = $this->_getContent($iContentId);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

        $CNF = &$this->_oConfig->CNF;
        return $this->_entitySocialSharing ($iContentId, array(
            'id_timeline' => $iContentId,
        	'id_thumb' => !empty($CNF['FIELD_THUMB']) && !empty($aContentInfo[$CNF['FIELD_THUMB']]) ? $aContentInfo[$CNF['FIELD_THUMB']] : '',
        	'title' => !empty($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : '',
        	'object_storage' => !empty($CNF['OBJECT_STORAGE']) ? $CNF['OBJECT_STORAGE'] : '',
            'object_transcoder' => false,
        	'object_view' => !empty($CNF['OBJECT_VIEWS']) ? $CNF['OBJECT_VIEWS'] : '',
        	'object_vote' => !empty($CNF['OBJECT_VOTES']) ? $CNF['OBJECT_VOTES'] : '',
        	'object_favorite' => !empty($CNF['OBJECT_FAVORITES']) ? $CNF['OBJECT_FAVORITES'] : '',
            'object_feature' => !empty($CNF['OBJECT_FEATURED']) ? $CNF['OBJECT_FEATURED'] : '',
        	'object_report' => !empty($CNF['OBJECT_REPORTS']) ? $CNF['OBJECT_REPORTS'] : '',
        	'uri_view_entry' => !empty($CNF['URI_VIEW_ENTRY']) ? $CNF['URI_VIEW_ENTRY'] : '',
        ));
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
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return '';

        $CNF = &$this->_oConfig->CNF;
        
        //--- Views
        $oViews = isset($CNF['OBJECT_VIEWS']) ? BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $aEvent['object_id']) : null;

        $aViews = array();
        if ($oViews && $oViews->isEnabled())
            $aViews = array(
                'system' => $CNF['OBJECT_VIEWS'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['views']
            );

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

        //--- Title & Description
        $sTitle = !empty($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : '';
        if(empty($sTitle) && !empty($aContentInfo[$CNF['FIELD_TEXT']]))
            $sTitle = $aContentInfo[$CNF['FIELD_TEXT']];

        return array(
            'owner_id' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'icon' => !empty($CNF['ICON']) ? $CNF['ICON'] : '',
        	'sample' => isset($CNF['T']['txt_sample_single_with_article']) ? $CNF['T']['txt_sample_single_with_article'] : $CNF['T']['txt_sample_single'],
        	'sample_wo_article' => $CNF['T']['txt_sample_single'],
    	    'sample_action' => isset($CNF['T']['txt_sample_single_action']) ? $CNF['T']['txt_sample_single_action'] : '',
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]),
            'content' => $this->_getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams), //a string to display or array to parse default template before displaying.
            'date' => $aContentInfo[$CNF['FIELD_ADDED']],
            'views' => $aViews,
            'votes' => $aVotes,
            'reports' => $aReports,
            'comments' => $aComments,
            'title' => $sTitle, //may be empty.
            'description' => '' //may be empty.
        );
    }

    /**
     * Check particular action permission without content
     * @param $sAction action to check, for example: Browse, Add
     * @param $iContentId content ID
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */ 
    public function serviceCheckAllowed($sAction, $isPerformAction = false)
    {
        $sMethod = 'checkAllowed' . bx_gen_method_name($sAction);
        if (!method_exists($this, $sMethod))
            return _t('_sys_request_method_not_found_cpt');

        return $this->$sMethod($isPerformAction);
    }
    
    /**
     * Check particular action permission with content
     * @param $sAction action to check, for example: View, Edit
     * @param $iContentId content ID
     * @return message on error, or CHECK_ACTION_RESULT_ALLOWED when allowed
     */ 
    public function serviceCheckAllowedWithContent($sAction, $iContentId, $isPerformAction = false)
    {
        if (!$iContentId || !($aContentInfo = $this->_oDb->getContentInfoById($iContentId)))
            return _t('_sys_request_page_not_found_cpt');

        $sMethod = 'checkAllowed' . bx_gen_method_name($sAction);
        if (!method_exists($this, $sMethod))
            return _t('_sys_request_method_not_found_cpt');

        return $this->$sMethod($aContentInfo, $isPerformAction);
    }
    
    public function serviceCheckAllowedCommentsView($iContentId, $sObjectComments) 
    {
        return $this->serviceCheckAllowedWithContent('comments_view', $iContentId);
    }
    
    public function serviceCheckAllowedCommentsPost($iContentId, $sObjectComments) 
    {
        return $this->serviceCheckAllowedWithContent('comments_post', $iContentId);
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

	/**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    public function checkAllowedCommentsView ($aContentInfo, $isPerformAction = false)
    {
        return CHECK_ACTION_RESULT_ALLOWED;
    }

	/**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make "true === " checking.
     */
    public function checkAllowedCommentsPost ($aContentInfo, $isPerformAction = false)
    {
        return CHECK_ACTION_RESULT_ALLOWED;
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

	// ====== COMMON METHODS

    public function getProfileId()
    {
    	return bx_get_logged_profile_id();
    }

	public function getProfileInfo($iUserId = 0)
    {
        $oProfile = $this->getObjectUser($iUserId);

        $oAccount = null;
        if($oProfile && !($oProfile instanceof BxDolProfileUndefined))
            $oAccount = $oProfile->getAccountObject();
        $bAccount = !empty($oAccount);

        return array(
        	'id' => $oProfile->id(),
            'name' => $oProfile->getDisplayName(),
        	'email' => $bAccount ? $oAccount->getEmail() : '',
            'link' => $oProfile->getUrl(),
            'icon' => $oProfile->getIcon(),
        	'thumb' => $oProfile->getThumb(),
        	'avatar' => $oProfile->getAvatar(),
            'unit' => $oProfile->getUnit(),
        	'active' => $oProfile->isActive(),
        );
    }
    
    public function getObjectUser($iUserId = 0)
    {
    	bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($iUserId);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        return $oProfile;
    }

    public function getObjectFavorite($sSystem = '', $iId = 0)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($sSystem) && !empty($CNF['OBJECT_FAVORITES']))
            $sSystem = $CNF['OBJECT_FAVORITES'];

        if(empty($sSystem))
            return false;

        $oFavorite = BxDolFavorite::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oFavorite->isEnabled())
            return false;

        return $oFavorite;
    }

	public function getUserId()
    {
        return isLogged() ? bx_get_logged_profile_id() : 0;
    }

    public function getUserIp()
    {
        return getVisitorIP();
    }

    public function getUserInfo($iUserId = 0)
    {
        $oProfile = BxDolProfile::getInstance($iUserId);
        if (!$oProfile)
            $oProfile = BxDolProfileUndefined::getInstance();

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit(),
            $oProfile->getUnit(0, array('template' => 'unit_wo_info'))
        );
    }

    public function _isModerator ($isPerformAction = false)
    {
        return CHECK_ACTION_RESULT_ALLOWED === $this->checkAllowedEditAnyEntry ($isPerformAction);
    }

    // ====== PROTECTED METHODS

    protected function _serviceEntityForm ($sFormMethod, $iContentId = 0, $sDisplay = false)
    {
        $iContentId = $this->_getContent($iContentId, false);
        if($iContentId === false)
            return false;

        bx_import('FormsEntryHelper', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this);
        return $oFormsHelper->$sFormMethod((int)$iContentId, $sDisplay);
    }

    protected function _serviceTemplateFunc ($sFunc, $iContentId, $sFuncGetContent = 'getContentInfoById')
    {
        $mixedContent = $this->_getContent($iContentId, $sFuncGetContent);
        if($mixedContent === false)
            return false;

        list($iContentId, $aContentInfo) = $mixedContent;

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

    protected function _getContent($iContentId = 0, $sFuncGetContent = 'getContentInfoById')
    {
        if(!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if(!$iContentId)
            return false;

        if(empty($sFuncGetContent) || !method_exists($this->_oDb, $sFuncGetContent))
            return $iContentId;

        $aContentInfo = $this->_oDb->$sFuncGetContent($iContentId);
        if(!$aContentInfo)
            return false;

        return array($iContentId, $aContentInfo);
    }

    protected function _getContentForTimelinePost($aEvent, $aContentInfo, $aBrowseParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;

    	$sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

    	//--- Image(s)
        $aImages = $this->_getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams);

    	return array(
    		'sample' => isset($CNF['T']['txt_sample_single_with_article']) ? $CNF['T']['txt_sample_single_with_article'] : $CNF['T']['txt_sample_single'],
    		'sample_wo_article' => $CNF['T']['txt_sample_single'],
    	    'sample_action' => isset($CNF['T']['txt_sample_single_action']) ? $CNF['T']['txt_sample_single_action'] : '',
			'url' => $sUrl,
			'title' => isset($CNF['FIELD_TITLE']) && isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : 
			(isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]) ? strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...') : ''),
			'text' => isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]) ? $aContentInfo[$CNF['FIELD_TEXT']] : '',
			'images' => $aImages,
		);
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $sImage = '';
        if(isset($CNF['FIELD_COVER']) && isset($aContentInfo[$CNF['FIELD_COVER']]) && $aContentInfo[$CNF['FIELD_COVER']])
            $sImage = $this->_oConfig->getImageUrl($aContentInfo[$CNF['FIELD_COVER']], array('OBJECT_IMAGES_TRANSCODER_GALLERY', 'OBJECT_IMAGES_TRANSCODER_THUMB'));

        if($sImage == '' && isset($CNF['FIELD_THUMB']) && isset($aContentInfo[$CNF['FIELD_THUMB']]) && $aContentInfo[$CNF['FIELD_THUMB']])
            $sImage = $this->_oConfig->getImageUrl($aContentInfo[$CNF['FIELD_THUMB']], array('OBJECT_IMAGES_TRANSCODER_GALLERY', 'OBJECT_IMAGES_TRANSCODER_THUMB'));

        if(empty($sImage))
            return array();

        return array(
		    array('url' => $sUrl, 'src' => $sImage),
		);
    }

    protected function _entityComments ($sObject, $iId = 0)
    {
        if (!$iId)
            $iId = bx_process_input(bx_get('id'), BX_DATA_INT);

        if (!$iId)
            return false;

        $oCmts = BxDolCmts::getObjectInstance($sObject, $iId);
        if (!$oCmts || !$oCmts->isEnabled())
            return false;

        return $oCmts->getCommentsBlock(array(), array('in_designbox' => false, 'show_empty' => true));
    }

    protected function _entitySocialSharing ($iId, $aParams = array())
    {
        $bShowAsButton = !isset($aParams['show_as_button']) || $aParams['show_as_button'] === true;

        $sUrl = !empty($aParams['uri_view_entry']) ? BxDolPermalinks::getInstance()->permalink('page.php?i=' . $aParams['uri_view_entry'] . '&id=' . $iId) : '';
        $sTitle = !empty($aParams['title']) ? $aParams['title'] : '';

        //--- Comments
        $sComments = '';
        $oComments = !empty($aParams['object_comments']) ? BxTemplCmts::getObjectInstance($aParams['object_comments'], $iId) : false;
        if ($oComments) {
            $iNum = $oComments->getCommentsCountAll();
            $sComments = $this->_oTemplate->parseHtmlByName('comments-item.html', array (
                'class' => 'bx-base-general-comments-' . ($bShowAsButton ? 'button' : 'link'),
                'class_do' => $bShowAsButton ? ' bx-btn' : '',
            	'class_counter' => $bShowAsButton ? ' bx-btn-height' : '',
                'url' => $sUrl . '#' . $oComments->getListAnchor(),
                'bx_if:comments' => array (
                    'condition' => $iNum,
                    'content' => array (
                        'num' => $iNum,
                    ),
                ),
            ));
        }

        $aCustomParams = array();
        $iIdThumb = isset($aParams['id_thumb']) ? (int)$aParams['id_thumb'] : 0;
        $bSocialSharing = isset($aParams['social_sharing']) ? (bool)$aParams['social_sharing'] : true;
        if ($iIdThumb && $bSocialSharing) {
            if(!empty($aParams['object_transcoder']))
                $o = BxDolTranscoder::getObjectInstance($aParams['object_transcoder']);
            else if(!empty($aParams['object_storage']))
                $o = BxDolStorage::getObjectInstance($aParams['object_storage']);

            $sImgUrl = $o ? $o->getFileUrlById($iIdThumb) : '';
            if($sImgUrl)
                $aCustomParams = array (
                    'img_url' => $sImgUrl,
                    'img_url_encoded' => rawurlencode($sImgUrl),
                );
        }

        //TODO: Rebuild using menus engine when it will be ready for such elements like Vote, Share, etc.
        //--- Views
        $sViews = '';
        $oViews = !empty($aParams['object_view']) ? BxDolView::getObjectInstance($aParams['object_view'], $iId) : false;
        if ($oViews)
            $sViews = $oViews->getElementBlock(array('show_do_view_as_button' => $bShowAsButton));

        //--- Votes
        $sVotes = '';
        $oVotes = !empty($aParams['object_vote']) ? BxDolVote::getObjectInstance($aParams['object_vote'], $iId) : false;
        if ($oVotes)
            $sVotes = $oVotes->getElementBlock(array('show_do_vote_as_button' => $bShowAsButton));

        //--- Favorite
        $sFavorites = '';
        $oFavorites = !empty($aParams['object_favorite']) ? BxDolFavorite::getObjectInstance($aParams['object_favorite'], $iId) : false;
        if ($oFavorites)
            $sFavorites = $oFavorites->getElementBlock(array('show_do_favorite_as_button' => $bShowAsButton));

        //--- Featured
        $sFeatured = '';
        $oFeatured = !empty($aParams['object_feature']) ? BxDolFeature::getObjectInstance($aParams['object_feature'], $iId) : false;
        if ($oFeatured)
            $sFeatured = $oFeatured->getElementBlock(array('show_do_feature_as_button' => $bShowAsButton));

        //--- Timeline Repost
        $sRepost = '';
        $iIdTimeline = isset($aParams['id_timeline']) ? (int)$aParams['id_timeline'] : $iId;
        if ($iIdTimeline && BxDolRequest::serviceExists('bx_timeline', 'get_repost_element_block'))
            $sRepost = BxDolService::call('bx_timeline', 'get_repost_element_block', array(bx_get_logged_profile_id(), $this->_aModule['name'], 'added', $iIdTimeline, array('show_do_repost_as_button' => $bShowAsButton)));

        //--- Report
		$sReport = '';
        $oReport = !empty($aParams['object_report']) ? BxDolReport::getObjectInstance($aParams['object_report'], $iId) : false;
        if ($oReport)
            $sReport = $oReport->getElementBlock(array('show_do_report_as_button' => $bShowAsButton));

        $sSocial = '';
        if($bSocialSharing) {
            $oSocial = BxDolMenu::getObjectInstance('sys_social_sharing');
            $oSocial->addMarkers(array_merge(array(
                'id' => $iId,
            	'module' => $this->_aModule['name'],
            	'url' => BX_DOL_URL_ROOT . $sUrl,
            	'title' => $sTitle,
            ), $aCustomParams));
            $sSocial = $oSocial->getCode();
        }

        if(empty($sComments)  && empty($sViews) && empty($sVotes) && empty($sFavorites)  && empty($sFeatured) && empty($sRepost) && empty($sReport) && empty($sSocial))
            return '';

        return $this->_oTemplate->parseHtmlByName('entry-share.html', array(
            'comments' => $sComments,
        	'view' => $sViews,
            'vote' => $sVotes,
            'favorite' => $sFavorites,
            'feature' => $sFeatured,
            'repost' => $sRepost,
        	'report' => $sReport,
            'social' => $sSocial,
        ));
        //TODO: Rebuild using menus engine when it will be ready for such elements like Vote, Repost, etc.
    }

    protected function _getFields($iContentId)
    {
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo))
            return array();

        return BxDolContentInfo::formatFields($aContentInfo);
    }

    protected function _getFieldValue($sField, $iContentId)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF[$sField]))
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || empty($aContentInfo[$CNF[$sField]]))
            return false;

        return $aContentInfo[$CNF[$sField]];
    }

    protected function _getFieldValueThumb($sField, $iContentId, $sTranscoder = '') 
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($sTranscoder) || empty($CNF[$sField]))
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || empty($aContentInfo[$CNF[$sField]]))
            return false;

        $oImagesTranscoder = BxDolTranscoderImage::getObjectInstance($sTranscoder);
        if(!$oImagesTranscoder)
            return false;

        return $oImagesTranscoder->getFileUrl($aContentInfo[$CNF[$sField]]);
    }
}

/** @} */
