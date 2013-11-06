<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolAcl');
bx_import('BxDolModule');

require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

define('BX_TIMELINE_HANDLER_TYPE_INSERT', 'insert');
define('BX_TIMELINE_HANDLER_TYPE_UPDATE', 'update');
define('BX_TIMELINE_HANDLER_TYPE_DELETE', 'delete');

define('BX_TIMELINE_FILTER_ALL', 'all');
define('BX_TIMELINE_FILTER_OWNER', 'owner');
define('BX_TIMELINE_FILTER_OTHER', 'other');

define('BX_TIMELINE_DIVIDER_ID', ',');

define('BX_TIMELINE_PARSE_TYPE_TEXT', 'text');
define('BX_TIMELINE_PARSE_TYPE_LINK', 'link');
define('BX_TIMELINE_PARSE_TYPE_PHOTO', 'photo');
define('BX_TIMELINE_PARSE_TYPE_DEFAULT', BX_TIMELINE_PARSE_TYPE_TEXT);


class BxTimelineModule extends BxDolModule
{
    var $_iOwnerId;
    var $_sJsPostObject;
    var $_sJsViewObject;
    var $_aPostElements;
    var $_sJsOutlineObject;

    var $_sDividerTemplate;
    var $_sBalloonTemplate;
    var $_sCmtPostTemplate;
    var $_sCmtViewTemplate;
    var $_sCmtTemplate;

    /**
     * Constructor
     */
    function __construct($aModule)
    {
        parent::BxDolModule($aModule);
        $this->_oConfig->init($this->_oDb);
        $this->_oTemplate->init();
        $this->_iOwnerId = 0;
    }

	/**
     * ACTION METHODS
     */
    public function actionPost()
    {
    	$sType = bx_process_input($_POST['type']);
	    $sMethod = 'getForm' . ucfirst($sType);
		if(!method_exists($this, $sMethod)) {
			$this->_echoResultJson(array());
        	return;
		}

        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);
        if (!$this->isAllowedPost(true)) {
        	$this->_echoResultJson(array('msg' => bx_js_string(_t('_bx_timeline_txt_msg_not_allowed_post'))));
			return;
        }
        $aResult = $this->$sMethod();

        $this->_echoResultJson($aResult);
    }

	function actionDelete()
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);
        if(!$this->isAllowedDelete(true)) {
            $this->_echoResultJson(array('code' => 1));
            return;
        }

        $iPostId = bx_process_input(bx_get('post_id'), BX_DATA_INT);
        if(!$this->_oDb->deleteEvent(array('id' => $iPostId))) {
        	$this->_echoResultJson(array('code' => 2));
            return;
        }

        $aPhotos = $this->_oDb->getPhotos($iPostId);
		if(is_array($aPhotos) && !empty($aPhotos)) {
			bx_import('BxDolStorage');
			$oStorage = BxDolStorage::getObjectInstance($this->_oConfig->getObject('storage'));

			foreach($aPhotos as $aPhoto)
				$oStorage->deleteFile($aPhoto['id']);

			$this->_oDb->deletePhotos($iPostId);
		}
			

		//--- Event -> Delete for Alerts Engine ---//
        bx_import('BxDolAlerts');
        $oAlert = new BxDolAlerts($this->_oConfig->getSystemName('alert'), 'delete', $iPostId, $this->getUserId());
        $oAlert->alert();
        //--- Event -> Delete for Alerts Engine ---//

        $this->_echoResultJson(array('code' => 0, 'id' => $iPostId));
    }

    function actionGetPost()
    {
        $this->_oConfig->setJsMode(true);

        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);
        $aEvent = $this->_oDb->getEvents(array('type' => 'id', 'object_id' => bx_process_input(bx_get('post_id'), BX_DATA_INT)));

        $this->_echoResultJson(array('item' => $this->_oTemplate->getCommon($aEvent, array('type' => 'owner', 'owner_id' => $this->_iOwnerId))));
    }

	function actionGetPosts()
    {
        $this->_oConfig->setJsMode(true);

		list($iStart, $iPerPage, $sFilter, $iTimeline, $aModules) = $this->_prepareParams();
		list($sItems, $sLoadMore) = $this->_oTemplate->getPosts(array(
			'type' => 'owner',
			'owner_id' => $this->_iOwnerId, 
			'order' => 'desc', 
			'start' => $iStart, 
			'per_page' => $iPerPage, 
			'filter' => $sFilter, 
			'timeline' => $iTimeline, 
			'modules' => $aModules
		));

		$this->_echoResultJson(array('items' => $sItems, 'load_more' => $sLoadMore));
    }

    public function actionGetPostForm($sType)
    {
    	$this->_oConfig->setJsMode(true);
    	$this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

    	$sMethod = 'getForm' . ucfirst($sType);
		if(!method_exists($this, $sMethod)) {
			$this->_echoResultJson(array());
        	return;
		}
    	$aResult = $this->$sMethod();

    	$this->_echoResultJson($aResult);
    }

	public function actionGetTimeline()
    {
    	$this->_oConfig->setJsMode(true);

        list($iStart, $iPerPage, $sFilter, $iTimeline, $aModules) = $this->_prepareParams();
        $sTimeline = $this->_oTemplate->getTimeline($this->_iOwnerId, $iStart, $iPerPage, $sFilter, $iTimeline, $aModules);

        $this->_echoResultJson(array('timeline' => $sTimeline));
    }

    public function actionGetComments()
    {
    	$this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

    	$sSystem = bx_process_input(bx_get('system'), BX_DATA_TEXT);
    	$iId = bx_process_input(bx_get('id'), BX_DATA_INT);

    	$sComments = $this->_oTemplate->getComments($sSystem, $iId);

    	$this->_echoResultJson(array('content' => $sComments));
    }

    public function actionGetPostPopup()
    {
    	$iItemId = bx_process_input(bx_get('id'), BX_DATA_INT);
    	if(!$iItemId) {
    		$this->_echoResultJson(array());
    		return;
    	}

    	$sContent = $this->_oTemplate->getViewItemPopup($iItemId);

    	$this->_echoResultJson(array('popup' => $sContent));
    }

	/**
     * SERVICE METHODS
     */
    public function serviceAddHandlers($sModuleUri = 'all')
    {
    	$this->_updateHandlers($sModuleUri, true);
    }

    public function serviceDeleteHandlers($sModuleUri = 'all')
    {
    	$this->_updateHandlers($sModuleUri, false);
    }

	function serviceGetActionsChecklist()
    {
        $aHandlers = $this->_oConfig->getHandlers();

        $aResults = array();
        foreach($aHandlers as $aHandler) {
        	if($aHandler['type'] != BX_TIMELINE_HANDLER_TYPE_INSERT)
        		continue;

            $aModule = $this->_oDb->getModuleByName($aHandler['module_name']);
            if(empty($aModule))
                $aModule['title'] = _t('_bx_timeline_alert_module_' . $aHandler['alert_unit']);

            $aResults[$aHandler['id']] = $aModule['title'] . ' (' . _t('_bx_timeline_alert_action_' . $aHandler['alert_action']) . ')';
        }

        asort($aResults);
        return $aResults;
    }

	public function serviceResponse($oAlert)
    {
    	bx_import('Response', $this->_aModule);
        $oResponse = new BxTimelineResponse($this);
        $oResponse->response($oAlert);
    }

	public function serviceGetBlockPostProfile($iProfileId = 0)
	{
		if (!$iProfileId && bx_get('id') !== false) {
			$iProfileId = bx_process_input(bx_get('id'), BX_DATA_INT);
            $iProfileId = BxDolProfile::getInstanceByContentAndType($iProfileId, 'bx_persons')->id();
		}

        if (!$iProfileId)
            return array();

		$this->_iOwnerId = $iProfileId;
        list($sUserName, $sUserUrl) = $this->getUserInfo($iProfileId);

        if($this->_iOwnerId != $this->getUserId() && !$this->isAllowedPost())
            return array();

		$sJsObject = $this->_oConfig->getJsObject('post');
        $aMenu = array(
			array('id' => 'timeline-ptype-text', 'name' => 'timeline-ptype-text', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".changePostType(this, 'text')", 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_write')),
			array('id' => 'timeline-ptype-link', 'name' => 'timeline-ptype-link', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".changePostType(this, 'link')", 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_share_link')),
			array('id' => 'timeline-ptype-photo', 'name' => 'timeline-ptype-photo', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".changePostType(this, 'photo')", 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_add_photo'))
        );

        if($this->_oDb->isModule('sounds'))
            $aMenu[] = array('id' => 'timeline-ptype-sound', 'name' => 'timeline-ptype-sound', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".changePostType(this, 'sound')", 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_add_music'));
        if($this->_oDb->isModule('videos'))
            $aMenu[] = array('id' => 'timeline-ptype-video', 'name' => 'timeline-ptype-video', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".changePostType(this, 'video')", 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_add_video'));

		bx_import('BxTemplMenuInteractive');
		$oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> 'timeline-post-menu', 'menu_items' => $aMenu));
		$oMenu->setSelected('', 'timeline-ptype-text');

        $sContent = $this->_oTemplate->getPostBlock($this->_iOwnerId);
        return array('content' => $sContent, 'menu' => $oMenu);
	}

	public function serviceGetBlockViewProfile($iProfileId = 0, $iStart = -1, $iPerPage = -1, $iTimeline = -1, $sFilter = '', $aModules = array())
	{
		if (!$iProfileId) {
			$iProfileId = bx_process_input(bx_get('id'), BX_DATA_INT);
            $iProfileId = BxDolProfile::getInstanceByContentAndType($iProfileId, 'bx_persons')->id();
		}

        if (!$iProfileId)
            return array();

		$this->_iOwnerId = $iProfileId;
        list($sUserName, $sUserUrl) = $this->getUserInfo($iProfileId);

        $sJsObject = $this->_oConfig->getJsObject('view');
        $aMenu = array(
			array('id' => 'timeline-view-all', 'name' => 'timeline-view-all', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_all'), 'active' => 1),
            array('id' => 'timeline-view-owner', 'name' => 'timeline-view-owner', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_owner', $sUserName)),
            array('id' => 'timeline-view-other', 'name' => 'timeline-view-other', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_other')),
            //array('id' => 'timeline-get-rss', 'name' => 'timeline-get-rss', 'class' => '', 'link' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'rss/' . $iProfileId . '/', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_get_rss')),
        );

        bx_import('BxTemplMenuInteractive');
		$oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> 'timeline-view-all', 'menu_items' => $aMenu));
		$oMenu->setSelected('', 'timeline-view-all');

		$sContent = $this->_oTemplate->getViewBlock($this->_iOwnerId, $iStart, $iPerPage, $sFilter, $iTimeline, $aModules);
        return array('content' => $sContent, 'menu' => $oMenu);
    }

    public function serviceGetBlockItem()
    {
    	$iItemId = bx_process_input(bx_get('id'), BX_DATA_INT);
    	if(!$iItemId)
    		return array();

    	return array('content' => $this->_oTemplate->getViewItemBlock($iItemId));
    }

    /*
     * COMMON METHODS 
     */
	public function getFormText()
    {
    	bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('mod_tml_text', 'mod_tml_text_add');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'post/';
        $oForm->aInputs['owner_id']['value'] = $this->_iOwnerId;

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$iUserId = $this->getUserId();
        	list($sUserName) = $this->getUserInfo($iUserId);

        	$sType = $oForm->getCleanValue('type');
        	$sType = $this->_oConfig->getPrefix('common_post') . $sType;
        	BxDolForm::setSubmittedValue('type', $sType, $oForm->aFormAttrs['method']);

        	$sContent = $sDescription = $oForm->getCleanValue('content');
        	$sContent = serialize(array('text' => $sContent));
        	BxDolForm::setSubmittedValue('content', $sContent, $oForm->aFormAttrs['method']);

        	bx_import('BxDolPrivacy');
        	$iId = $oForm->insert(array(
        		'object_id' => $iUserId,
        		'object_privacy_view' => BX_DOL_PG_ALL,
				'title' => bx_process_input($sUserName . ' ' . _t('_bx_timeline_txt_wrote')),
				'description' => $sDescription,
        		'date' => time()
			));

			if($iId != 0) {
				$this->_onPost($iId, $iUserId);

                return array('id' => $iId);
			}

			return array('msg' => _t('_bx_timeline_txt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

	public function getFormLink()
    {
        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('mod_tml_link', 'mod_tml_link_add');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'post/';
        $oForm->aInputs['owner_id']['value'] = $this->_iOwnerId;

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$iUserId = $this->getUserId();
        	list($sUserName) = $this->getUserInfo($iUserId);

        	$sType = $oForm->getCleanValue('type');
        	$sType = $this->_oConfig->getPrefix('common_post') . $sType;
        	BxDolForm::setSubmittedValue('type', $sType, $oForm->aFormAttrs['method']);

        	$sUrl = $oForm->getCleanValue('content');
        	$sContent = bx_file_get_contents($sUrl);

	        preg_match("/<title>(.*)<\/title>/", $sContent, $aMatch);
	        $sTitle = $aMatch ? $aMatch[1] : '';
	
	        preg_match("/<meta.*name[='\" ]+description['\"].*content[='\" ]+(.*)['\"].*><\/meta>/", $sContent, $aMatch);
	        $sDescription = $aMatch ? $aMatch[1] : '';

	        $sContent = serialize(array(
				'url' => strpos($sUrl, 'http://') === false && strpos($sUrl, 'https://') === false ? 'http://' . $sUrl : $sUrl,
	        	'title' => $sTitle,
				'text' => $sDescription
			));
	        BxDolForm::setSubmittedValue('content', $sContent, $oForm->aFormAttrs['method']);

        	bx_import('BxDolPrivacy');
        	$iId = $oForm->insert(array(
        		'object_id' => $iUserId,
        		'object_privacy_view' => BX_DOL_PG_ALL,
				'title' => bx_process_input($sUserName . ' ' . _t('_bx_timeline_txt_shared_link')),
				'description' => bx_process_input($sUrl . ' - ' . $sTitle),
        		'date' => time()
			));

			if($iId != 0) {
				$this->_onPost($iId, $iUserId);

                return array('id' => $iId);
			}

			return array('msg' => _t('_bx_timeline_txt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

    public function getFormPhoto()
    {
    	$aFormNested = array(
			'inputs' => array(
		    	'file_title' => array(
		        	'type' => 'text',
		            'name' => 'file_title[]',
		            'value' => '{file_title}',
		            'caption' => _t('_bx_timeline_form_photo_input_title'),
		            'required' => true,
		            'checker' => array(
		            	'func' => 'length',
		                'params' => array(1, 150),
		                'error' => _t('_bx_timeline_form_photo_input_err_title')
					),
					'db' => array (
 						'pass' => 'Xss',
 					),
				),

				'file_text' => array(
		        	'type' => 'textarea',
		            'name' => 'file_text[]',
		            'caption' => _t('_bx_timeline_form_photo_input_description'),
		            'required' => true,
		            'checker' => array(
		            	'func' => 'length',
		                'params' => array(10, 5000),
		                'error' => _t('_bx_timeline_form_photo_input_err_description')
					),
					'db' => array (
 						'pass' => 'Xss',
 					),
				),
			),
		);

    	bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance('mod_tml_photo', 'mod_tml_photo_add');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'post/';
        $oForm->aInputs['owner_id']['value'] = $this->_iOwnerId;

	    bx_import('BxDolFormNested');
	    $oFormNested = new BxDolFormNested('content', $aFormNested, 'do_submit');

        $oForm->aInputs['content']['storage_object'] = $this->_oConfig->getObject('storage');
        $oForm->aInputs['content']['images_transcoder'] = $this->_oConfig->getObject('transcoder_preview');
        $oForm->aInputs['content']['uploaders'] = $this->_oConfig->getUploaders('image');
        $oForm->aInputs['content']['multiple'] = false;
        $oForm->aInputs['content']['ghost_template'] = $oFormNested;

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
        	$iUserId = $this->getUserId();
        	list($sUserName) = $this->getUserInfo($iUserId);

        	$sType = $oForm->getCleanValue('type');
        	$sType = $this->_oConfig->getPrefix('common_post') . $sType;
        	BxDolForm::setSubmittedValue('type', $sType, $oForm->aFormAttrs['method']);

        	$aPhIds = $oForm->getCleanValue('content');
        	BxDolForm::setSubmittedValue('content', serialize(array()), $oForm->aFormAttrs['method']);

        	bx_import('BxDolPrivacy');
        	$iId = $oForm->insert(array(
        		'object_id' => $iUserId,
        		'object_privacy_view' => BX_DOL_PG_ALL,
				'title' => bx_process_input($sUserName . ' ' . _t('_bx_timeline_txt_added_photo')),
				'description' => '',
        		'date' => time()
			));

			if($iId != 0) {
				$iPhIds = count($aPhIds);
				if($iPhIds > 0) {
					$aPhTitles = $oForm->getCleanValue('file_title');
					$aPhTexts = $oForm->getCleanValue('file_text');

					bx_import('BxDolStorage');
					$oStorage = BxDolStorage::getObjectInstance($this->_oConfig->getObject('storage'));

					for($i = 0; $i < $iPhIds; $i++) 
						if($this->_oDb->savePhoto($iId, $aPhIds[$i], $aPhTitles[$i], $aPhTexts[$i]))
							$oStorage->afterUploadCleanup($aPhIds[$i], $iUserId);
				}

				$this->_onPost($iId, $iUserId);

                return array('id' => $iId);
			}

			return array('msg' => _t('_bx_timeline_txt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

    public function getCmtsObject($sSystem, $iId)
    {
    	if(empty($sSystem) || (int)$iId == 0)
    		return false;

    	bx_import('BxDolCmts');
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iId);
		if(!$oCmts->isEnabled())
			return false;

		return $oCmts;
    }

	public function getUserId()
    {
        return isLogged() ? bx_get_logged_profile_id() : 0;
    }

	public function getUserInfo($iUserId = 0)
    {
    	bx_import('BxDolProfile');
		$oProfile = BxDolProfile::getInstance($iUserId);
		if (!$oProfile) {
			bx_import('BxDolProfileUndefined');
			$oProfile = BxDolProfileUndefined::getInstance();
		}

		return array(
			$oProfile->getDisplayName(), 
			$oProfile->getUrl(), 
			$oProfile->getIcon(),
			$oProfile->getUnit()
		);
    }

    public function isAllowedPost($bPerform = false)
    {
		if(isAdmin())
			return true;

        $iUserId = $this->getUserId();
		if($iUserId == 0 && $this->_oConfig->isAllowGuestComments())
			return true;

        $aCheckResult = checkActionModule($iUserId, 'post', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    public function isAllowedDelete($bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if($this->_iOwnerId == $iUserId)
           return true;

        $aCheckResult = checkActionModule($iUserId, 'delete', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

	public function isAllowedComment($bPerform = false)
    {
		if(isAdmin())
			return true;

        $iUserId = $this->getUserId();
		if($iUserId == 0 && $this->_oConfig->isAllowGuestComments())
			return true;

        $aCheckResult = checkActionModule($iUserId, 'comment', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] == CHECK_ACTION_RESULT_ALLOWED;
    }

    protected function _prepareParams()
    {
    	$this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);
        if(strpos($this->_iOwnerId, BX_TIMELINE_DIVIDER_ID) !== false)
            $this->_iOwnerId = explode(BX_TIMELINE_DIVIDER_ID, $this->_iOwnerId);

        $iStart = bx_get('start');
        $iStart = $iStart !== false ? bx_process_input($iStart, BX_DATA_INT) : 0;

        $iPerPage = bx_get('per_page');
		$iPerPage = $iPerPage !== false ? bx_process_input($iPerPage, BX_DATA_INT) : $this->_oConfig->getPerPage();

        $sFilter = bx_get('filter');
		$sFilter = $sFilter !== false ? bx_process_input($sFilter, BX_DATA_TEXT) : BX_TIMELINE_FILTER_ALL;

		$iTimeline = bx_get('timeline');
		$iTimeline = $iTimeline !== false ? bx_process_input($iTimeline, BX_DATA_INT) : 0;
        
		$aModules = bx_get('modules');
		$aModules = $aModules !== false ? bx_process_input($aModules, BX_DATA_TEXT) : array();

		return array($iStart, $iPerPage, $sFilter, $iTimeline, $aModules);
    }

	protected function _updateHandlers($sModuleUri = 'all', $bInstall = true)
    {
        $aModules = $sModuleUri == 'all' ? $this->_oDb->getModules() : array($this->_oDb->getModuleByUri($sModuleUri));

        foreach($aModules as $aModule) {
			if(!BxDolRequest::serviceExists($aModule, 'get_timeline_data'))
				continue;

			$aData = BxDolService::call($aModule['name'], 'get_timeline_data');
			if(empty($aData) || !is_array($aData))
				continue;

			if($bInstall)
				$this->_oDb->insertData($aData);
			else
				$this->_oDb->deleteData($aData);
        }

        BxDolAlerts::cache();
    }

    protected function _onPost($iId, $iUserId)
    {
    	//--- Event -> Post for Alerts Engine ---//
		bx_import('BxDolAlerts');
        $oAlert = new BxDolAlerts($this->_oConfig->getSystemName('alert'), 'post', $iId, $iUserId);
        $oAlert->alert();
        //--- Event -> Post for Alerts Engine ---//
    }

	protected function _echoResultJson($a, $isAutoWrapForFormFileSubmit = false) {

        header('Content-type: text/html; charset=utf-8');    

        require_once(BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php');

        $oParser = new Services_JSON();
        $s = $oParser->encode($a);
        if ($isAutoWrapForFormFileSubmit && !empty($_FILES)) 
            $s = '<textarea>' . $s . '</textarea>'; // http://jquery.malsup.com/form/#file-upload
        echo $s;
    }
    
    
    
    
    
    
    
   
    /**
     * Get photo uploading form.
     *
     * @return string with form.
     */
    function actionGetPhotoUploaders($iOwnerId)
    {
        $this->_iOwnerId = $iOwnerId;
        header('Content-Type: text/html; charset=utf-8');
        return BxDolService::call('photos', 'get_uploader_form', array(array('mode' => 'single', 'category' => 'wall', 'album'=>_t('_wall_photo_album', getNickName(getLoggedId())), 'albumPrivacy' => BX_DOL_PG_ALL, 'from_wall' => 1, 'owner_id' => $this->_iOwnerId)), 'Uploader');
    }
    /**
     * Get music uploading form.
     *
     * @return srting with form.
     */
    function actionGetMusicUploaders($iOwnerId)
    {
        $this->_iOwnerId = $iOwnerId;
        header('Content-Type: text/html; charset=utf-8');
        return BxDolService::call('sounds', 'get_uploader_form', array(array('mode' => 'single', 'category' => 'wall', 'album'=>_t('_wall_sound_album', getNickName(getLoggedId())), 'albumPrivacy' => BX_DOL_PG_ALL, 'from_wall' => 1, 'owner_id' => $this->_iOwnerId)), 'Uploader');
    }
    /**
     * Get video uploading form.
     *
     * @return string with form.
     */
    function actionGetVideoUploaders($iOwnerId)
    {
        $this->_iOwnerId = $iOwnerId;
        header('Content-Type: text/html; charset=utf-8');
        return BxDolService::call('videos', 'get_uploader_form', array(array('mode' => 'single', 'category' => 'wall', 'album'=>_t('_wall_video_album', getNickName(getLoggedId())), 'albumPrivacy' => BX_DOL_PG_ALL, 'from_wall' => 1, 'owner_id' => $this->_iOwnerId)), 'Uploader');
    }
    /**
     * Get RSS for specified owner.
     *
     * @param  string $sUsername wall owner username
     * @return string with RSS.
     */
    function actionRss($sUsername)
    {
        $aOwner = $this->_oDb->getUser($sUsername, 'username');

        $aEvents = $this->_oDb->getEvents(array(
            'type' => 'owner',
            'owner_id' => $aOwner['id'],
            'order' => 'desc',
            'start' => 0,
            'count' => $this->_oConfig->getRssLength(),
            'filter' => ''
        ));

        $sRssBaseUrl = $this->_oConfig->getBaseUri() . 'index/' . $aOwner['username'] . '/';
        $aRssData = array();
        foreach($aEvents as $aEvent) {
            if(empty($aEvent['title'])) continue;

            $aRssData[$aEvent['id']] = array(
               'UnitID' => $aEvent['id'],
               'OwnerID' => $aOwner['id'],
               'UnitTitle' => $aEvent['title'],
               'UnitLink' => BX_DOL_URL_ROOT . $sRssBaseUrl . '#wall-event-' . $aEvent['id'],
               'UnitDesc' => $aEvent['description'],
               'UnitDateTimeUTS' => $aEvent['date'],
               'UnitIcon' => ''
            );
        }

        $oRss = new BxDolRssFactory();

        header('Content-Type: text/html; charset=utf-8');
        return $oRss->GenRssByData($aRssData, $aOwner['username'] . ' ' . _t('_wall_rss_caption'), $sRssBaseUrl);
    }

    
    /**
     * Display Post block on profile page.
     *
     * @param  integer $mixed - owner ID or Username.
     * @return array   containing block info.
     */
    function serviceViewBlockAccount($mixed, $iStart = -1, $iPerPage = -1, $sFilter = '', $sTimeline = '', $sType = 'id', $aModules = array())
    {
        $sContent = '';

        $aOwner = $this->_oDb->getUser($mixed, $sType);
        $this->_iOwnerId = $aOwner['id'];

        $aFriends = getMyFriendsEx($this->_iOwnerId, '', '', 'LIMIT 20');
        if(empty($aFriends))
            return $this->_oTemplate->getEmpty(true);

        $this->_iOwnerId = array_keys($aFriends);

        if($iStart == -1)
           $iStart = 0;
        if($iPerPage == -1)
           $iPerPage = $this->_oConfig->getPerPage('account');
        if(empty($sFilter))
            $sFilter = BX_WALL_FILTER_ALL;

        //--- Prepare JavaScript paramaters ---//
        $oJson = new Services_JSON();
        $sOwnerId = implode(BX_TIMELINE_DIVIDER_ID, $this->_iOwnerId);

        ob_start();
?>
        var <?=$this->_sJsViewObject; ?> = new BxTimelineView({
            sActionUrl: '<?php echo BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(); ?>',
            sObjName: '<?php echo $this->_sJsViewObject; ?>',
            iOwnerId: '<?php echo $sOwnerId; ?>',
            sAnimationEffect: '<?php echo $this->_oConfig->getAnimationEffect(); ?>',
            iAnimationSpeed: '<?php echo $this->_oConfig->getAnimationSpeed(); ?>',
            oRequestParams: <?php echo $oJson->encode(array('WallOwnerId' => $sOwnerId, 'WallStart' => $iStart, 'WallPerPage' => $iPerPage, 'WallFilter' => $sFilter, 'WallTimeline' => $sTimeline, 'WallModules' => $aModules)); ?>
        });
<?php
        $sJsContent = ob_get_clean();

        //--- Is used with common Pagination
        //$oPaginate = $this->_getPaginate($sFilter, $sTimeline, $aModules);

        $aVariables = array(
            'timeline' => $this->_getTimeline($iStart, $iPerPage, $sFilter, $sTimeline, $aModules),
            'content' => $this->_getPosts('desc', $iStart, $iPerPage, $sFilter, $sTimeline, $aModules),
            'view_js_content' => $sJsContent,
            //--- Is used with common Pagination
            //'paginate' => $oPaginate->getPaginate()
        );

        bx_import('BxTemplFormView');
		$oForm = new BxTemplFormView(array());
		$oForm->addCssJs(true, true);

        $this->_oTemplate->addCss(array('forms_adv.css', 'view.css'));
        $this->_oTemplate->addJs(array('main.js', 'view.js'));
        return array($this->_oTemplate->parseHtmlByName('view.html', $aVariables), array(), LoadingBox('bx-wall-view-loading'), false, 'getBlockCaptionMenu');
    }

    function serviceViewBlockIndex($iStart = -1, $iPerPage = -1, $sFilter = '', $aModules = array())
    {
        $sContent = '';
        $this->_iOwnerId = 0;

        if($iStart == -1)
           $iStart = 0;
        if($iPerPage == -1)
           $iPerPage = $this->_oConfig->getPerPage('index');
        if(empty($sFilter))
            $sFilter = BX_WALL_FILTER_ALL;

        //--- Prepare JavaScript paramaters ---//
        $oJson = new Services_JSON();

        ob_start();
?>
        var <?=$this->_sJsOutlineObject; ?> = new BxTimelineOutline({
            sActionUrl: '<?=BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(); ?>',
            sObjName: '<?=$this->_sJsOutlineObject; ?>',
            iOwnerId: '0',
            sAnimationEffect: '<?php echo $this->_oConfig->getAnimationEffect(); ?>',
            iAnimationSpeed: '<?php echo $this->_oConfig->getAnimationSpeed(); ?>',
            oRequestParams: <?php echo $oJson->encode(array('WallFilter' => $sFilter, 'WallModules' => $aModules)); ?>
        });
<?php
        $sJsContent = ob_get_clean();

        list($sContent, $sPaginate) = $this->_getPostsOutline('desc', $iStart, $iPerPage, $sFilter, $aModules);
        if(empty($sContent))
            return;

        $aTmplVars = array(
            'outline_js_content' => $sJsContent,
            'content' => $sContent,
            'paginate' => $sPaginate
        );

        $this->_oTemplate->addCss(array('outline.css'));
        $this->_oTemplate->addJs(array('jquery.masonry.min.js', 'main.js', 'outline.js'));
        return array($this->_oTemplate->parseHtmlByName('outline.html', $aTmplVars), array(), LoadingBox('bx-wall-view-loading'), true, 'getBlockCaptionMenu');
    }

    



    function serviceGetMemberMenuItem()
    {
        $oMemberMenu = bx_instance('BxDolMemberMenu');

        $aLanguageKeys = array(
            'wall' => _t( '_wall_pc_view' ),
        );

        // fill all necessary data;
        $aLinkInfo = array(
            'item_img_src'  => 'time',
            'item_img_alt'  => $aLanguageKeys['wall'],
            'item_link'     => BX_DOL_URL_ROOT . $this -> _oConfig -> getBaseUri(),
            'item_onclick'  => null,
            'item_title'    => $aLanguageKeys['wall'],
            'extra_info'    => null,
        );

        return $oMemberMenu -> getGetExtraMenuLink($aLinkInfo);
    }
    

    /**
     * Private Methods
     * Is used for content displaying
     */
    function _getPostsOutline($sOrder, $iStart, $iPerPage, $sFilter, $aModules)
    {
        $iStartEv = $iStart;
        $iPerPageEv = $iPerPage;

        //--- Check for Next
        $iPerPageEv += 1;
        $aEvents = $this->_oDb->getEvents(array(
        	'type' => 'outline', 
        	'order' => $sOrder, 
        	'start' => $iStartEv, 
        	'count' => $iPerPageEv, 
        	'filter' => $sFilter, 
        	'modules' => $aModules
        ));

        //--- Check for Next
        $bNext = false;
        if(count($aEvents) > $iPerPage) {
            $aEvent = array_pop($aEvents);
            $bNext = true;
        }

        $iEvents = count($aEvents);
        foreach($aEvents as $aEvent) {
            if(empty($aEvent['action']))
                continue;

            $aEvent['content'] = $this->_oTemplate->getSystem($aEvent, BX_WALL_VIEW_OUTLINE);
            if(empty($aEvent['content']))
                continue;

            $sContent .= $aEvent['content'];
        }

        $sPaginate = $this->_getLoadMoreOutline($iStart, $iPerPage, $bNext, $iEvents > 0);
        return array($sContent, $sPaginate);
    }


    
    
    /*
	function _getLoadMoreOutline($iStart, $iPerPage, $bEnabled = true, $bVisible = true)
    {
        return $this->_oTemplate->getLoadMoreOutline($iStart, $iPerPage, $bEnabled, $bVisible);
    }
    function _getPaginate($sFilter, $sTimeline, $aModules)
    {
        return new BxDolPaginate(array(
            'page_url' => 'javascript:void(0);',
            'start' => 0,
            'count' => $this->_oDb->getEventsCount($this->_iOwnerId, $sFilter, $sTimeline, $aModules),
            'per_page' => $this->_oConfig->getPerPage(),
            'on_change_page' => $this->_sJsViewObject . '.changePage({start}, {per_page})',
            'on_change_per_page' => $this->_sJsViewObject . '.changePerPage(this)',
            'page_reloader' => true
        ));
    }
    function _addHidden($sPostType = "photos", $sContentType = "upload", $sAction = "post")
    {
        return array(
            'WallOwnerId' => array (
                'type' => 'hidden',
                'name' => 'WallOwnerId',
                'value' => $this->_iOwnerId,
            ),
            'WallPostAction' => array (
                'type' => 'hidden',
                'name' => 'WallPostAction',
                'value' => $sAction,
            ),
            'WallPostType' => array (
                'type' => 'hidden',
                'name' => 'WallPostType',
                'value' => $sPostType,
            ),
            'WallContentType' => array (
                'type' => 'hidden',
                'name' => 'WallContentType',
                'value' => $sContentType,
            ),
        );
    }
    function serviceGetSubscriptionParams($sUnit, $sAction, $iObjectId)
    {
        $sUnit = str_replace('bx_', '_', $sUnit);
        if(empty($sAction))
            $sAction = 'main';

        $aProfileInfo = getProfileInfo($iObjectId);
        return array(
            'template' => array(
                'Subscription' => _t($sUnit . '_sbs_' . $sAction, $aProfileInfo['NickName']),
                'ViewLink' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri()  . 'index/' . $aProfileInfo['NickName']
            )
        );
    }

    function serviceGetSpyData()
    {
        $AlertName = $this->_oConfig->getAlertSystemName();

        return array(
            'handlers' => array(
                array('alert_unit' => $AlertName, 'alert_action' => 'post', 'module_uri' => $this->_oConfig->getUri(), 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
                array('alert_unit' => $AlertName, 'alert_action' => 'commentPost', 'module_uri' => $this->_oConfig->getUri(), 'module_class' => 'Module', 'module_method' => 'get_spy_post'),
            ),
            'alerts' => array(
                array('unit' => $AlertName, 'action' => 'post'),
                array('unit' => $AlertName, 'action' => 'delete'),
                array('unit' => $AlertName, 'action' => 'commentPost'),
                array('unit' => $AlertName, 'action' => 'commentRemoved')
            )
        );
    }

    function serviceGetSpyPost($sAction, $iObjectId = 0, $iSenderId = 0, $aExtraParams = array())
    {
        $aEvent = $this->_oDb->getEvents(array('type' => 'id', 'object_id' => $iObjectId));
        $aEvent = array_shift($aEvent);

        $sLangKey = '';
        switch ($sAction) {
            case 'post':
                $sLangKey = '_wall_spy_post';
                break;
            case 'commentPost':
                $sLangKey = '_wall_spy_post_comment';
                break;
        }

        return array(
            'params'    => array(
                'profile_link'  => getProfileLink($iSenderId),
                'profile_nick'  => getNickName($iSenderId),
                'recipient_p_link' => getProfileLink($aEvent['owner_id']),
                'recipient_p_nick' => getNickName($aEvent['owner_id']),
            ),
            'recipient_id' => $aEvent['owner_id'],
            'lang_key' => $sLangKey
        );
    }
	*/
}

/** @} */ 
