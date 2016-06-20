<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Timeline Timeline
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAcl');
bx_import('BxBaseModNotificationsModule');

define('BX_TIMELINE_TYPE_ITEM', 'view_item');
define('BX_TIMELINE_TYPE_DEFAULT', BX_BASE_MOD_NTFS_TYPE_OWNER);

define('BX_TIMELINE_FILTER_ALL', 'all');
define('BX_TIMELINE_FILTER_OWNER', 'owner');
define('BX_TIMELINE_FILTER_OTHER', 'other');

define('BX_TIMELINE_PARSE_TYPE_POST', 'post');
define('BX_TIMELINE_PARSE_TYPE_SHARE', 'share');
define('BX_TIMELINE_PARSE_TYPE_DEFAULT', BX_TIMELINE_PARSE_TYPE_POST);

define('BX_TIMELINE_MEDIA_PHOTO', 'photo');
define('BX_TIMELINE_MEDIA_VIDEO', 'video');

class BxTimelineModule extends BxBaseModNotificationsModule
{
    protected $_sJsPostObject;
    protected $_sJsViewObject;
    protected $_aPostElements;
    protected $_sJsOutlineObject;

    protected $_sDividerTemplate;
    protected $_sBalloonTemplate;
    protected $_sCmtPostTemplate;
    protected $_sCmtViewTemplate;
    protected $_sCmtTemplate;

    /**
     * Constructor
     */
    function __construct($aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * ACTION METHODS
     */
    public function actionPost()
    {
        $sType = bx_process_input($_POST['type']);
        $sMethod = 'getForm' . ucfirst($sType);
        if(!method_exists($this, $sMethod)) {
            echoJson(array());
            return;
        }

        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $mixedAllowed = $this->isAllowedPost(true);
        if($mixedAllowed !== true) {
            echoJson(array('msg' => strip_tags($mixedAllowed)));
            return;
        }

        $aResult = $this->$sMethod();
        echoJson($aResult);
    }

	function actionPin()
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));

        $mixedAllowed = $this->{'isAllowed' . ((int)$aEvent['pinned'] == 0 ? 'Pin' : 'Unpin')}($aEvent, true);
        if($mixedAllowed !== true)
            return echoJson(array('code' => 1, 'msg' => strip_tags($mixedAllowed)));

		$aEvent['pinned'] = (int)$aEvent['pinned'] == 0 ? time() : 0;
        if(!$this->_oDb->updateEvent(array('pinned' => $aEvent['pinned']), array('id' => $iId)))
        	return echoJson(array('code' => 2));

        $aResult = array('code' => 0, 'id' => $iId);
		if($aEvent['pinned'] != 0)
			$aResult['content'] = $this->_oTemplate->getPost($aEvent, array('type' => 'owner', 'owner_id' => $this->_iOwnerId, 'dynamic_mode' => true));

		echoJson($aResult);
    }

    function actionDelete()
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));

        $mixedAllowed = $this->isAllowedDelete($aEvent, true);
        if($mixedAllowed !== true) {
            echoJson(array('code' => 1, 'msg' => strip_tags($mixedAllowed)));
            return;
        }

        if(!$this->deleteEvent($aEvent))
        	echoJson(array('code' => 2));
        else 
        	echoJson(array('code' => 0, 'id' => $iId));
    }

    public function actionShare()
    {
    	$iAuthorId = $this->getUserId();

        $iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);
        $aContent = array(
            'type' => bx_process_input(bx_get('type'), BX_DATA_TEXT),
            'action' => bx_process_input(bx_get('action'), BX_DATA_TEXT),
            'object_id' => bx_process_input(bx_get('object_id'), BX_DATA_INT),
        );

        $aShared = $this->_oDb->getShared($aContent['type'], $aContent['action'], $aContent['object_id']);
        if(empty($aShared) || !is_array($aShared)) {
            echoJson(array('code' => 1, 'msg' => _t('_bx_timeline_txt_err_cannot_share')));
            return;
        }

        $mixedAllowed = $this->isAllowedShare($aShared, true);
        if($mixedAllowed !== true) {
            echoJson(array('code' => 2, 'msg' => strip_tags($mixedAllowed)));
            return;
        }

        $bShared = $this->_oDb->isShared($aShared['id'], $iOwnerId, $iAuthorId);
		if($bShared) {
        	echoJson(array('code' => 3, 'msg' => _t('_bx_timeline_txt_err_already_shared')));
            return;
        }

        $iId = $this->_oDb->insertEvent(array(
            'owner_id' => $iOwnerId,
            'type' => $this->_oConfig->getPrefix('common_post') . 'share',
            'action' => '',
            'object_id' => $iAuthorId,
            'object_privacy_view' => $this->_oConfig->getPrivacyViewDefault('object'),
            'content' => serialize($aContent),
            'title' => '',
            'description' => ''
        ));

        if(empty($iId)) {
	        echoJson(array('code' => 4, 'msg' => _t('_bx_timeline_txt_err_cannot_share')));        
	        return;
        }

        $this->onShare($iId, $aShared);

        $aShared = $this->_oDb->getShared($aContent['type'], $aContent['action'], $aContent['object_id']);
		$sCounter = $this->_oTemplate->getShareCounter($aShared);

		echoJson(array(
			'code' => 0, 
			'msg' => _t('_bx_timeline_txt_msg_success_share'), 
			'count' => $aShared['shares'], 
			'counter' => $sCounter,
			'disabled' => !$bShared
		));
    }

    function actionGetPost()
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $iEvent = bx_process_input(bx_get('id'), BX_DATA_INT);
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iEvent));

        echoJson(array('item' => $this->_oTemplate->getPost($aEvent, array('type' => 'owner', 'owner_id' => $this->_iOwnerId, 'dynamic_mode' => true))));
    }

    function actionGetPosts()
    {
        $aParams = $this->_prepareParamsGet();
        list($sItems, $sLoadMore, $sBack) = $this->_oTemplate->getPosts($aParams);

        echoJson(array('items' => $sItems, 'load_more' => $sLoadMore, 'back' => $sBack));
    }

    public function actionGetPostForm($sType)
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $sMethod = 'getForm' . ucfirst($sType);
        if(!method_exists($this, $sMethod)) {
            echoJson(array());
            return;
        }
        $aResult = $this->$sMethod();

        echoJson($aResult);
    }

    public function actionGetComments()
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $sSystem = bx_process_input(bx_get('system'), BX_DATA_TEXT);
        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);
        $sComments = $this->_oTemplate->getComments($sSystem, $iId);

        echoJson(array('content' => $sComments));
    }

    public function actionAddAttachLink()
    {
        $aResult = $this->getFormAttachLink();

        echoJson($aResult);
    }

    public function actionDeleteAttachLink()
    {
    	$iUserId = $this->getUserId();
        $iLinkId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(empty($iLinkId)) {
            echoJson(array());
            return;
        }

        $aLink = $this->_oDb->getUnusedLinks($iUserId, $iLinkId);
    	if(empty($aLink) || !is_array($aLink)) {
            echoJson(array());
            return;
        }

		if(!empty($aLink['media_id']))
			BxDolStorage::getObjectInstance($this->_oConfig->getObject('storage_photos'))->deleteFile($aLink['media_id']);

        $aResult = array();
        if($this->_oDb->deleteUnusedLinks($iUserId, $iLinkId))
            $aResult = array('code' => 0);
        else
            $aResult = array('code' => 1, 'msg' => _t('_bx_timeline_form_post_input_link_err_delete'));

        echoJson($aResult);
    }

    public function actionGetAttachLinkForm()
    {
        echo $this->_oTemplate->getAttachLinkForm();
    }

    public function actionGetSharedBy()
    {
        $iSharedId = bx_process_input(bx_get('id'), BX_DATA_INT);

        echo $this->_oTemplate->getSharedBy($iSharedId);
    }

    function actionRss($iOwnerId)
    {
        list($sUserName) = $this->getUserInfo($iOwnerId);

        $sRssCaption = _t('_bx_timeline_txt_rss_caption', $sUserName);
        $sRssLink = $this->_oConfig->getViewUrl($iOwnerId);

        $aParams = $this->_prepareParams('owner', $iOwnerId, 0, $this->_oConfig->getRssLength(), '', array(), 0);
        $aEvents = $this->_oDb->getEvents($aParams);

        $aRssData = array();
        foreach($aEvents as $aEvent) {
            if(empty($aEvent['title'])) continue;

            $aRssData[$aEvent['id']] = array(
               'UnitID' => $aEvent['id'],
               'UnitTitle' => $aEvent['title'],
               'UnitLink' => $this->_oConfig->getItemViewUrl($aEvent),
               'UnitDesc' => $aEvent['description'],
               'UnitDateTimeUTS' => $aEvent['date'],
            );
        }

        $oRss = new BxDolRssFactory();

        header('Content-Type: application/xml; charset=utf-8');
        echo $oRss->GenRssByData($aRssData, $sRssCaption, $sRssLink);
    }

    /**
     * SERVICE METHODS
     * 
     * Get Post block for a separate page.
     */
    public function serviceGetBlockPost($iProfileId = 0)
    {
    	if(empty($iProfileId) && bx_get('profile_id') !== false)
			$iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

		if(empty($iProfileId) && isLogged())
			$iProfileId = bx_get_logged_profile_id();

        return $this->getBlockPost($iProfileId);
    }

    public function serviceGetBlockPostProfile($sProfileModule = 'bx_persons', $iProfileContentId = 0)
    {
        if(empty($sProfileModule))
    		return array();

    	if(empty($iProfileContentId) && bx_get('id') !== false)
    		$iProfileContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

		$oProfile = BxDolProfile::getInstanceByContentAndType($iProfileContentId, $sProfileModule);
		if(empty($oProfile))
			return array();

		return $this->getBlockPost($oProfile->id());
    }

    /*
     * Get View block for a separate page. Will return a block with "Empty" message if nothing found.
     */
    public function serviceGetBlockView($iProfileId = 0)
    {
    	if(empty($iProfileId) && bx_get('profile_id') !== false)
			$iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

		if(empty($iProfileId) && isLogged())
			$iProfileId = bx_get_logged_profile_id();

        $aBlock = $this->getBlockView($iProfileId);
        if(!empty($aBlock))
            return $aBlock;

        return array('content' => MsgBox(_t('_bx_timeline_txt_msg_no_results')));
    }

	public function serviceGetBlockViewProfile($sProfileModule = 'bx_persons', $iProfileContentId = 0, $iStart = -1, $iPerPage = -1, $sFilter = '', $aModules = array(), $iTimeline = -1)
    {
    	if(empty($sProfileModule))
    		return array();

    	if(empty($iProfileContentId) && bx_get('id') !== false)
    		$iProfileContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

		$oProfile = BxDolProfile::getInstanceByContentAndType($iProfileContentId, $sProfileModule);
		if(empty($oProfile))
			return array();

        return $this->getBlockView($oProfile->id(), $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
    }

    public function serviceGetBlockViewAccount($iProfileId = 0, $iStart = -1, $iPerPage = -1, $iTimeline = -1, $sFilter = '', $aModules = array())
    {
        $aParams = $this->_prepareParams(BX_BASE_MOD_NTFS_TYPE_CONNECTIONS, $iProfileId, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
        $aParams['per_page'] = (int)$iPerPage > 0 ? $iPerPage : $this->_oConfig->getPerPage('account');

        $this->_iOwnerId = $aParams['owner_id'];

        $sContent = $this->_oTemplate->getViewBlock($aParams);
        return array('content' => $sContent);
    }

	public function serviceGetBlockViewHome($iProfileId = 0, $iStart = -1, $iPerPage = -1, $iTimeline = -1, $sFilter = '', $aModules = array())
    {
        $aParams = $this->_prepareParams(BX_BASE_MOD_NTFS_TYPE_PUBLIC, $iProfileId, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
        $aParams['per_page'] = (int)$iPerPage > 0 ? $iPerPage : $this->_oConfig->getPerPage('home');

        $this->_iOwnerId = $aParams['owner_id'];

        $sContent = $this->_oTemplate->getViewBlock($aParams);
        return array('content' => $sContent);
    }

    public function serviceGetBlockItem()
    {
        $iItemId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(!$iItemId)
            return array();

        return array('content' => $this->_oTemplate->getItemBlock($iItemId));
    }

	/**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {
    	$sModule = $this->_aModule['name'];

        return array(
            'handlers' => array(
                array('group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'post_common', 'module_name' => $sModule, 'module_method' => 'get_notifications_post', 'module_class' => 'Module'),
                array('group' => $sModule . '_object', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'delete'),
                array('group' => $sModule . '_comment', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'commentPost', 'module_name' => $sModule, 'module_method' => 'get_notifications_comment', 'module_class' => 'Module'),
                array('group' => $sModule . '_comment', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'commentRemoved'),
                array('group' => $sModule . '_vote', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote', 'module_class' => 'Module'),
				array('group' => $sModule . '_vote', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'undoVote'),
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'post_common'),
                array('unit' => $sModule, 'action' => 'delete'),
                array('unit' => $sModule, 'action' => 'commentPost'),
                array('unit' => $sModule, 'action' => 'commentRemoved'),
                array('unit' => $sModule, 'action' => 'doVote'),
                array('unit' => $sModule, 'action' => 'undoVote'),
            )
        );
    }

    public function serviceGetNotificationsPost($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContent = (int)$aEvent['object_id'];
		$aContent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iContent));
        if(empty($aContent) || !is_array($aContent))
            return array();

        $sEntryCaption = !empty($aContent['title']) ? $aContent['title'] : strmaxtextlen($aContent['description'], 20, '...');

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single_ext'],
			'entry_url' => $this->_oConfig->getItemViewUrl($aContent),
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aContent['owner_id'],
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

    public function serviceGetNotificationsComment($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContent = (int)$aEvent['object_id'];
    	$aContent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iContent));
        if(empty($aContent) || !is_array($aContent))
            return array();

		$oComment = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContent);
        if(!$oComment || !$oComment->isEnabled())
            return array();

        $sEntryCaption = !empty($aContent['title']) ? $aContent['title'] : strmaxtextlen($aContent['description'], 20, '...');

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single'],
			'entry_url' => $this->_oConfig->getItemViewUrl($aContent),
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aContent['owner_id'],
			'subentry_sample' => $CNF['T']['txt_sample_comment_single'],
			'subentry_url' => $oComment->getViewUrl((int)$aEvent['subobject_id']),
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

    public function serviceGetNotificationsVote($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContent = (int)$aEvent['object_id'];
    	$aContent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iContent));
        if(empty($aContent) || !is_array($aContent))
            return array();

		$oVote = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $iContent);
        if(!$oVote || !$oVote->isEnabled())
            return array();

        $sEntryCaption = !empty($aContent['title']) ? $aContent['title'] : strmaxtextlen($aContent['description'], 20, '...');

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single'],
			'entry_url' => $this->_oConfig->getItemViewUrl($aContent),
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aContent['owner_id'],
			'subentry_sample' => $CNF['T']['txt_sample_vote_single'],
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

    public function serviceGetShareElementBlock($iOwnerId, $sType, $sAction, $iObjectId, $aParams = array())
    {
    	if(!$this->isEnabled())
    		return '';

        $aParams = array_merge($this->_oConfig->getShareDefaults(), $aParams);
        return $this->_oTemplate->getShareElement($iOwnerId, $sType, $sAction, $iObjectId, $aParams);
    }

    public function serviceGetShareCounter($sType, $sAction, $iObjectId)
    {
    	if(!$this->isEnabled())
    		return '';

		$aShared = $this->_oDb->getShared($sType, $sAction, $iObjectId);

        return $this->_oTemplate->getShareCounter($aShared);
    }

    public function serviceGetShareJsScript()
    {
    	if(!$this->isEnabled())
    		return '';

        return $this->_oTemplate->getShareJsScript();
    }

    public function serviceGetShareJsClick($iOwnerId, $sType, $sAction, $iObjectId)
    {
    	if(!$this->isEnabled())
    		return '';

        return $this->_oTemplate->getShareJsClick($iOwnerId, $sType, $sAction, $iObjectId);
    }

    public function serviceGetMenuItemAddonComment($sSystem, $iObjectId)
    {
        if(empty($sSystem) || empty($iObjectId))
            return '';

        $oCmts = $this->getCmtsObject($sSystem, $iObjectId);
        if($oCmts === false)
            return '';

        $iCounter = (int)$oCmts->getCommentsCount();
        return  $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => 'javascript:void(0)',
            'title' => _t('_bx_timeline_menu_item_title_item_comment'),
            'bx_repeat:attrs' => array(
                array('key' => 'onclick', 'value' => "javascript:" . $this->_oConfig->getJsObject('view') . ".commentItem(this, '" . $sSystem . "', " . $iObjectId . ")")
            ),
            'content' => $iCounter > 0 ? $iCounter : ''
        ));
    }

    public function serviceGetSettingsCheckerHelper()
    {
        bx_import('FormCheckerHelper', $this->_aModule);
        return 'BxTimelineFormCheckerHelper';
    }

    /*
     * COMMON METHODS
     */
	public function getBlockPost($iProfileId)
    {
    	if(!$iProfileId)
            return array();

        $this->_iOwnerId = $iProfileId;

        if($this->isAllowedPost() !== true)
            return array();

		$sContent = $this->_oTemplate->getPostBlock($this->_iOwnerId);
        return array('content' => $sContent);
    }

    public function getBlockView($iProfileId, $iStart = -1, $iPerPage = -1, $sFilter = '', $aModules = array(), $iTimeline = -1)
    {
        if(!$iProfileId)
			return array();

        $sJsObject = $this->_oConfig->getJsObject('view');
        $aParams = $this->_prepareParams(BX_BASE_MOD_NTFS_TYPE_OWNER, $iProfileId, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
        $aParams['per_page'] = (int)$iPerPage > 0 ? $iPerPage : $this->_oConfig->getPerPage('profile');

        $this->_iOwnerId = $aParams['owner_id'];
        list($sUserName, $sUserUrl) = $this->getUserInfo($aParams['owner_id']);

        $aMenu = array(
            array('id' => 'timeline-view-all', 'name' => 'timeline-view-all', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_all'), 'active' => 1),
            array('id' => 'timeline-view-owner', 'name' => 'timeline-view-owner', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_owner', $sUserName)),
            array('id' => 'timeline-view-other', 'name' => 'timeline-view-other', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_other')),
            array('id' => 'timeline-get-rss', 'name' => 'timeline-get-rss', 'class' => '', 'link' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'rss/' . $iProfileId . '/', 'target' => '_blank', 'title' => _t('_bx_timeline_menu_item_get_rss')),
        );

        $sContent = '';
        $oProfileOwner = BxDolProfile::getInstance($this->_iOwnerId);
        bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_view', $this->_iOwnerId, $this->getUserId(), array('override_content' => &$sContent, 'params' => &$aParams, 'menu' => &$aMenu));

        $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> 'timeline-view-all', 'menu_items' => $aMenu));
        $oMenu->setSelected('', 'timeline-view-all');

        if (!$sContent)
            $sContent = $this->_oTemplate->getViewBlock($aParams);

        return array('content' => $sContent, 'menu' => $oMenu);
    }
    public function deleteEvent($aEvent)
    {
    	if(empty($aEvent) || !is_array($aEvent) || !$this->_oDb->deleteEvent(array('id' => (int)$aEvent['id'])))
            return false;

        $this->onDelete($aEvent);
        return true;
    }

    public function getFormAttachLink()
    {
        $iUserId = $this->getUserId();

        $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject('form_attach_link'), $this->_oConfig->getObject('form_display_attach_link_add'), $this->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'add_attach_link/';
        $oForm->aInputs['url']['checker']['params']['preg'] = $this->_oConfig->getPregPattern('url');

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sLink = $oForm->getCleanValue('url');

            $aMatches = array();
            preg_match($this->_oConfig->getPregPattern('url'), $sLink, $aMatches);
            $sLink = (empty($aMatches[2]) ? 'http://' : '') . $aMatches[0];

            $aSiteInfo = bx_get_site_info($sLink, array(
                'thumbnailUrl' => array('tag' => 'link', 'content_attr' => 'href'),
                'OGImage' => array('name_attr' => 'property', 'name' => 'og:image'),
            ));

            $sTitle = !empty($aSiteInfo['title']) ? $aSiteInfo['title'] : _t('_Empty');
            $sDescription = !empty($aSiteInfo['description']) ? $aSiteInfo['description'] : _t('_Empty');

            $sMediaUrl = '';
            if(!empty($aSiteInfo['thumbnailUrl']))
            	$sMediaUrl = $aSiteInfo['thumbnailUrl'];
            else if(!empty($aSiteInfo['OGImage']))
            	$sMediaUrl = $aSiteInfo['OGImage'];

			$iMediaId = 0;
			$oStorage = null;
            if(!empty($sMediaUrl)) {
            	$oStorage = BxDolStorage::getObjectInstance($this->_oConfig->getObject('storage_' . BX_TIMELINE_MEDIA_PHOTO . 's'));

            	$iMediaId = $oStorage->storeFileFromUrl($sMediaUrl, true, $iUserId);
            }

            $iId = (int)$oForm->insert(array('profile_id' => $iUserId, 'media_id' => $iMediaId, 'url' => $sLink, 'title' => $sTitle, 'text' => $sDescription, 'added' => time()));
            if(!empty($iId)) {
            	if(!empty($oStorage) && !empty($iMediaId))
            		$oStorage->afterUploadCleanup($iMediaId, $iUserId);

                return array('item' => $this->_oTemplate->getAttachLinkItem($iUserId, $iId));
            }

            return array('msg' => _t('_bx_timeline_txt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

    public function getFormPost()
    {
        $iUserId = $this->getUserId();

        $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject('form_post'), $this->_oConfig->getObject('form_display_post_add'), $this->_oTemplate);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            list($sUserName) = $this->getUserInfo($iUserId);

            $sType = $oForm->getCleanValue('type');
            $sType = $this->_oConfig->getPrefix('common_post') . $sType;
            BxDolForm::setSubmittedValue('type', $sType, $oForm->aFormAttrs['method']);

            $aContent = array();

            //--- Process Text ---//
            $sText = $oForm->getCleanValue('text');
            unset($oForm->aInputs['text']);

            $aContent['text'] = $this->_prepareTextForSave($sText);

            //--- Process Link ---//
            $aLinkIds = $oForm->getCleanValue('link');

            //--- Process Media ---//
            $aPhotoIds = $oForm->getCleanValue(BX_TIMELINE_MEDIA_PHOTO);
            $aVideoIds = $oForm->getCleanValue(BX_TIMELINE_MEDIA_VIDEO);

            $sTitle = _t('_bx_timeline_txt_user_added_sample', $sUserName, _t('_bx_timeline_txt_sample'));
            $sDescription = !empty($aContent['text']) ? $aContent['text'] : '';

            $iId = $oForm->insert(array(
                'object_id' => $iUserId,
                'object_privacy_view' => $this->_oConfig->getPrivacyViewDefault('object'),
                'content' => serialize($aContent),
                'title' => $sTitle,
                'description' => $sDescription,
                'date' => time()
            ));

            if(!empty($iId)) {
            	$oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
 				$oMetatags->keywordsAdd($iId, $aContent['text']);
 				$oMetatags->locationsAddFromForm($iId, $this->_oConfig->CNF['FIELD_LOCATION_PREFIX']);

				//--- Process Link ---//
                if(!empty($aLinkIds) && is_array($aLinkIds))
                    foreach($aLinkIds as $iLinkId)
                        $this->_oDb->saveLink($iId, $iLinkId);

				//--- Process Media ---// 
				$this->_saveMedia(BX_TIMELINE_MEDIA_PHOTO, $iId, $aPhotoIds);
				$this->_saveMedia(BX_TIMELINE_MEDIA_VIDEO, $iId, $aVideoIds);

                $this->onPost($iId);

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

        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oCmts->isEnabled())
            return false;

        return $oCmts;
    }

    public function getVoteObject($sSystem, $iId)
    {
        if(empty($sSystem) || (int)$iId == 0)
            return false;

        $oVote = BxDolVote::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oVote->isEnabled())
            return false;

        return $oVote;
    }

	public function getReportObject($sSystem, $iId)
    {
        if(empty($sSystem) || (int)$iId == 0)
            return false;

        $oReport = BxDolReport::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oReport->isEnabled())
            return false;

        return $oReport;
    }

    public function getAttachmentsMenuObject()
    {
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_post_attachments'), $this->_oTemplate);
        $oMenu->addMarkers(array(
            'js_object' => $this->_oConfig->getJsObject('post'),
        ));

        return $oMenu;
    }

    public function getManageMenuObject()
    {
        return BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_manage'), $this->_oTemplate);
    }

    //--- Check permissions methods ---//

    public function isAllowedPost($bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = $this->getUserId();
        if($this->_iOwnerId == $this->getUserId())
            return true;

        $aCheckResult = checkActionModule($iUserId, 'post', $this->getName(), $bPerform);

        if ($oProfileOwner = BxDolProfile::getInstance($this->_iOwnerId))
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_post', $oProfileOwner->id(), $iUserId, array('check_result' => &$aCheckResult));

        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function isAllowedDelete($aEvent, $bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if((int)$aEvent['owner_id'] == $iUserId && $this->_oConfig->isAllowDelete())
           return true;

        $aCheckResult = checkActionModule($iUserId, 'delete', $this->getName(), $bPerform);

        if ($oProfileOwner = BxDolProfile::getInstance((int)$aEvent['owner_id']))
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_delete', $oProfileOwner->id(), $iUserId, array('check_result' => &$aCheckResult));

        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function isAllowedComment($aEvent, $bPerform = false)
    {
        $mixedComments = $this->getCommentsData($aEvent['comments']);
        if($mixedComments === false)
            return false;

        list($sSystem, $iObjectId) = $mixedComments;
        $oCmts = $this->getCmtsObject($sSystem, $iObjectId);
        $oCmts->addCssJs();

        $bResult = true;

        if ($oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']))
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_comment', $oProfileOwner->id(), (int)$this->getUserId(), array('result' => &$bResult));

        return $bResult;
    }

    public function isAllowedVote($aEvent, $bPerform = false)
    {
        $mixedVotes = $this->getVotesData($aEvent['votes']);
        if($mixedVotes === false)
            return false;

        list($sSystem, $iObjectId) = $mixedVotes;
        $oVote = $this->getVoteObject($sSystem, $iObjectId);
        $oVote->addCssJs();

        $bResult = true;

        if ($oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']))
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_vote', $oProfileOwner->id(), (int)$this->getUserId(), array('result' => &$bResult));

        return $bResult;
    }

    public function isAllowedReport($aEvent, $bPerform = false)
    {
        $mixedReports = $this->getReportsData($aEvent['reports']);
        if($mixedReports === false)
            return false;

        list($sSystem, $iObjectId) = $mixedReports;
        $oReport = $this->getReportObject($sSystem, $iObjectId);
        $oReport->addCssJs();

        $bResult = true;

        if ($oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']))
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_report', $oProfileOwner->id(), (int)$this->getUserId(), array('result' => &$bResult));

        return $bResult;
    }

    public function isAllowedShare($aEvent, $bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if($iUserId == 0)
            return false;

        $aCheckResult = checkActionModule($iUserId, 'share', $this->getName(), $bPerform);

        if ($oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']))
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_share', $oProfileOwner->id(), $iUserId, array('check_result' => &$aCheckResult));

        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

	public function isAllowedPin($aEvent, $bPerform = false)
    {
    	if((int)$aEvent['pinned'] != 0)
    		return false;

        return $this->_isAllowedPin($aEvent, $bPerform);
    }

	public function isAllowedUnpin($aEvent, $bPerform = false)
    {
    	if((int)$aEvent['pinned'] == 0)
    		return false;

        return $this->_isAllowedPin($aEvent, $bPerform);
    }

    public function isAllowedMore($aEvent, $bPerform = false)
    {
    	$oMoreMenu = $this->getManageMenuObject();
    	$oMoreMenu->setEventId($aEvent['id']);
    	return $oMoreMenu->isVisible();
    }

    public function onPost($iId)
    {
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));

        if($this->_oConfig->isSystem($aEvent['type'], $aEvent['action'])) {
            $sPostType = 'system';
            $iSenderId = $aEvent['owner_id'];
        } else {
            $sPostType = 'common';
            $iSenderId = $aEvent['object_id'];
        }

        //--- Event -> Post for Alerts Engine ---//
        $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'post_' . $sPostType, $iId, $iSenderId, array(
        	'object_author_id' => $aEvent['owner_id']
        ));
        $oAlert->alert();
        //--- Event -> Post for Alerts Engine ---//
    }

    public function onShare($iId, $aShared = array())
    {
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));

        if(empty($aShared)) {
            $aContent = unserialize($aEvent['content']);

            $aShared = $this->_oDb->getShared($aContent['type'], $aContent['action'], $aContent['object_id']);
            if(empty($aShared) || !is_array($aShared))
                return;
        }

        $iUserId = $this->getUserId();
        $this->_oDb->insertShareTrack($aEvent['id'], $iUserId, $this->getUserIp(), $aShared['id']);
        $this->_oDb->updateShareCounter($aShared['id'], $aShared['shares']);

        //--- Timeline -> Update for Alerts Engine ---//
        $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'share', $aShared['id'], $iUserId);
        $oAlert->alert();
        //--- Timeline -> Update for Alerts Engine ---//
    }

    public function onDelete($aEvent)
    {
    	$sCommonPostPrefix = $this->_oConfig->getPrefix('common_post');

    	//--- Delete attached photos, videos and links when common event was deleted.
    	if($aEvent['type'] == $sCommonPostPrefix . BX_TIMELINE_PARSE_TYPE_POST) {
    		$this->_deleteMedia(BX_TIMELINE_MEDIA_PHOTO, $aEvent['id']);
    		$this->_deleteMedia(BX_TIMELINE_MEDIA_VIDEO, $aEvent['id']);

	        $this->_deleteLinks($aEvent['id']);
    	}

    	//--- Update parent event when share event was deleted.
        if($aEvent['type'] == $sCommonPostPrefix . BX_TIMELINE_PARSE_TYPE_SHARE) {
            $this->_oDb->deleteShareTrack($aEvent['id']);

            $aContent = unserialize($aEvent['content']);
            $aShared = $this->_oDb->getShared($aContent['type'], $aContent['action'], $aContent['object_id']);
            if(!empty($aShared) && is_array($aShared))
                $this->_oDb->updateShareCounter($aShared['id'], $aShared['shares'], -1);
        }

        //--- Find and delete share events when parent event was deleted.
        $bSystem = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']);
	    $aShareEvents = $this->_oDb->getEvents(array('browse' => 'shared_by_descriptor', 'type' => $aEvent['type']));
		foreach($aShareEvents as $aShareEvent) {
			$aContent = unserialize($aShareEvent['content']);
			if(isset($aContent['type']) && $aContent['type'] == $aEvent['type'] && isset($aContent['object_id']) && (($bSystem && (int)$aContent['object_id'] == (int)$aEvent['object_id']) || (!$bSystem  && (int)$aContent['object_id'] == (int)$aEvent['id'])))
				$this->_oDb->deleteEvent(array('id' => (int)$aShareEvent['id']));
		}

		//--- Delete associated meta.
        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
        $oMetatags->onDeleteContent($aEvent['id']);

        //--- Event -> Delete for Alerts Engine ---//
        $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'delete', $aEvent['id'], $this->getUserId());
        $oAlert->alert();
        //--- Event -> Delete for Alerts Engine ---//
    }

    public function getVotesData(&$aVotes)
    {
        if(empty($aVotes) || !is_array($aVotes))
            return false;

        $sSystem = isset($aVotes['system']) ? $aVotes['system'] : '';
        $iObjectId = isset($aVotes['object_id']) ? (int)$aVotes['object_id'] : 0;
        $iCount = isset($aVotes['count']) ? (int)$aVotes['count'] : 0;
        if($sSystem == '' || $iObjectId == 0)
            return false;

        return array($sSystem, $iObjectId, $iCount);
    }

    public function getReportsData(&$aReports)
    {
        if(empty($aReports) || !is_array($aReports))
            return false;

        $sSystem = isset($aReports['system']) ? $aReports['system'] : '';
        $iObjectId = isset($aReports['object_id']) ? (int)$aReports['object_id'] : 0;
        $iCount = isset($aReports['count']) ? (int)$aReports['count'] : 0;
        if($sSystem == '' || $iObjectId == 0)
            return false;

        return array($sSystem, $iObjectId, $iCount);
    }

    public function getCommentsData(&$aComments)
    {
        if(empty($aComments) || !is_array($aComments))
            return false;

        $sSystem = isset($aComments['system']) ? $aComments['system'] : '';
        $iObjectId = isset($aComments['object_id']) ? (int)$aComments['object_id'] : 0;
        $iCount = isset($aComments['count']) ? (int)$aComments['count'] : 0;
        if($sSystem == '' || $iObjectId == 0 || ($iCount == 0 && !isLogged()))
            return false;

        return array($sSystem, $iObjectId, $iCount);
    }

	protected function _isAllowedPin($aEvent, $bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if($iUserId == 0)
            return false;

		if((int)$aEvent['owner_id'] == $iUserId)
           return true;

        $aCheckResult = checkActionModule($iUserId, 'pin', $this->getName(), $bPerform);
        if ($oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']))
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_pin', $oProfileOwner->id(), $iUserId, array('check_result' => &$aCheckResult));

        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

	protected function _deleteLinks($iId)
    {
	    $aLinks = $this->_oDb->getLinks($iId);
	    if(empty($aLinks) || !is_array($aLinks))
	    	return;

		$oStorage = BxDolStorage::getObjectInstance($this->_oConfig->getObject('storage_photos'));
		foreach($aLinks as $aLink)
			if(!empty($aLink['media_id']))
				$oStorage->deleteFile($aLink['media_id']);

		$this->_oDb->deleteLinks($iId);
    }

    protected function _saveMedia($sType, $iId, $aItemIds)
    {
    	if(empty($aItemIds) || !is_array($aItemIds))
    		return; 

    	$iUserId = $this->getUserId();

		$oStorage = BxDolStorage::getObjectInstance($this->_oConfig->getObject('storage_' . strtolower($sType) . 's'));
		foreach($aItemIds as $iItemId)
        	if($this->_oDb->saveMedia($sType, $iId, $iItemId))
            	$oStorage->afterUploadCleanup($iItemId, $iUserId);
    }

    protected function _deleteMedia($sType, $iId)
    {
	    $aItems = $this->_oDb->getMedia($sType, $iId);
	    if(empty($aItems) || !is_array($aItems))
	    	return;

		$oStorage = BxDolStorage::getObjectInstance($this->_oConfig->getObject('storage_' . strtolower($sType) . 's'));
		foreach($aItems as $iItemId)
			$oStorage->deleteFile($iItemId);

		$this->_oDb->deleteMedia($sType, $iId);
    }

    protected function _prepareParams($sType, $iOwnerId, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline)
    {
        $aParams = array();
        $aParams['browse'] = 'list';
        $aParams['type'] = !empty($sType) ? $sType : BX_TIMELINE_TYPE_DEFAULT;
        $aParams['owner_id'] = (int)$iOwnerId != 0 ? $iOwnerId : $this->getUserId();
        $aParams['start'] = (int)$iStart > 0 ? $iStart : 0;
        $aParams['per_page'] = (int)$iPerPage > 0 ? $iPerPage : $this->_oConfig->getPerPage();
        $aParams['filter'] = !empty($sFilter) ? $sFilter : BX_TIMELINE_FILTER_ALL;
        $aParams['modules'] = is_array($aModules) && !empty($aModules) ? $aModules : array();
        $aParams['timeline'] = (int)$iTimeline > 0 ? $iTimeline : 0;
        $aParams['active'] = 1;
        $aParams['hidden'] = 0;

        return $aParams;
    }

    protected function _prepareParamsGet()
    {
        $aParams = array();
        $aParams['browse'] = 'list';
        $aParams['dynamic_mode'] = true;

        $sType = bx_get('type');
        $aParams['type'] = $sType !== false ? bx_process_input($sType, BX_DATA_TEXT) : BX_TIMELINE_TYPE_DEFAULT;

        $aParams['owner_id'] = $sType !== false ? bx_process_input(bx_get('owner_id'), BX_DATA_INT) : $this->getUserId();

        $iStart = bx_get('start');
        $aParams['start'] = $iStart !== false ? bx_process_input($iStart, BX_DATA_INT) : 0;

        $iPerPage = bx_get('per_page');
        $aParams['per_page'] = $iPerPage !== false ? bx_process_input($iPerPage, BX_DATA_INT) : $this->_oConfig->getPerPage();

        $sFilter = bx_get('filter');
        $aParams['filter'] = $sFilter !== false ? bx_process_input($sFilter, BX_DATA_TEXT) : BX_TIMELINE_FILTER_ALL;

        $aModules = bx_get('modules');
        $aParams['modules'] = $aModules !== false ? bx_process_input($aModules, BX_DATA_TEXT) : array();

        $iTimeline = bx_get('timeline');
        $aParams['timeline'] = $iTimeline !== false ? bx_process_input($iTimeline, BX_DATA_INT) : 0;

        $aParams['active'] = 1;
        $aParams['hidden'] = 0;

        return $aParams;
    }

    protected function _prepareTextForSave($s)
    {
        return bx_process_input($s, BX_DATA_TEXT_MULTILINE);
    }
}

/** @} */
