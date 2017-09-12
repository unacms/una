<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolAcl');
bx_import('BxBaseModNotificationsModule');

define('BX_TIMELINE_TYPE_ITEM', 'view_item');
define('BX_TIMELINE_TYPE_OWNER_AND_CONNECTIONS', 'owner_and_connections');
define('BX_TIMELINE_TYPE_DEFAULT', BX_BASE_MOD_NTFS_TYPE_OWNER);

define('BX_TIMELINE_VIEW_TIMELINE', 'timeline');
define('BX_TIMELINE_VIEW_OUTLINE', 'outline');
define('BX_TIMELINE_VIEW_SEARCH', 'search');
define('BX_TIMELINE_VIEW_DEFAULT', BX_TIMELINE_VIEW_OUTLINE);

define('BX_TIMELINE_FILTER_ALL', 'all');
define('BX_TIMELINE_FILTER_OWNER', 'owner');
define('BX_TIMELINE_FILTER_OTHER', 'other');

define('BX_TIMELINE_PARSE_TYPE_POST', 'post');
define('BX_TIMELINE_PARSE_TYPE_REPOST', 'repost');
define('BX_TIMELINE_PARSE_TYPE_DEFAULT', BX_TIMELINE_PARSE_TYPE_POST);

define('BX_TIMELINE_MEDIA_PHOTO', 'photo');
define('BX_TIMELINE_MEDIA_VIDEO', 'video');

class BxTimelineModule extends BxBaseModNotificationsModule implements iBxDolContentInfoService
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

		echoJson(array(
			'code' => 0, 
			'id' => $iId, 
			'eval' => $this->_oConfig->getJsObject('view') . '.onPinPost(oData)'
		));
    }

    function actionDelete()
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => bx_process_input(bx_get('id'), BX_DATA_INT)));

        $mixedAllowed = $this->isAllowedDelete($aEvent, true);
        if($mixedAllowed !== true)
            return echoJson(array('code' => 1, 'msg' => strip_tags($mixedAllowed)));

        if(!$this->deleteEvent($aEvent))
            return echoJson(array('code' => 2));

        echoJson(array(
        	'code' => 0, 
        	'id' => $aEvent['id'], 
        	'eval' => $this->_oConfig->getJsObject('view') . '.onDeletePost(oData)'
        ));
    }

    public function actionRepost()
    {
    	$iAuthorId = $this->getUserId();

        $iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);
        $aContent = array(
            'type' => bx_process_input(bx_get('type'), BX_DATA_TEXT),
            'action' => bx_process_input(bx_get('action'), BX_DATA_TEXT),
            'object_id' => bx_process_input(bx_get('object_id'), BX_DATA_INT),
        );

        $aReposted = $this->_oDb->getReposted($aContent['type'], $aContent['action'], $aContent['object_id']);
        if(empty($aReposted) || !is_array($aReposted)) {
            echoJson(array('code' => 1, 'msg' => _t('_bx_timeline_txt_err_cannot_repost')));
            return;
        }

        $mixedAllowed = $this->isAllowedRepost($aReposted, true);
        if($mixedAllowed !== true) {
            echoJson(array('code' => 2, 'msg' => strip_tags($mixedAllowed)));
            return;
        }

        $bReposted = $this->_oDb->isReposted($aReposted['id'], $iOwnerId, $iAuthorId);
		if($bReposted) {
        	echoJson(array('code' => 3, 'msg' => _t('_bx_timeline_txt_err_already_reposted')));
            return;
        }

        $iId = $this->_oDb->insertEvent(array(
            'owner_id' => $iOwnerId,
            'type' => $this->_oConfig->getPrefix('common_post') . 'repost',
            'action' => '',
            'object_id' => $iAuthorId,
            'object_privacy_view' => $this->_oConfig->getPrivacyViewDefault('object'),
            'content' => serialize($aContent),
            'title' => '',
            'description' => ''
        ));

        if(empty($iId)) {
	        echoJson(array('code' => 4, 'msg' => _t('_bx_timeline_txt_err_cannot_repost')));        
	        return;
        }

        $this->onRepost($iId, $aReposted);

        $aReposted = $this->_oDb->getReposted($aContent['type'], $aContent['action'], $aContent['object_id']);
		$sCounter = $this->_oTemplate->getRepostCounter($aReposted);

		echoJson(array(
			'code' => 0, 
			'msg' => _t('_bx_timeline_txt_msg_success_repost'), 
			'count' => $aReposted['reposts'], 
			'counter' => $sCounter,
			'disabled' => !$bReposted
		));
    }

    function actionGetPost()
    {
        $sView = bx_process_input(bx_get('view'));
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => bx_process_input(bx_get('id'), BX_DATA_INT)));
        if(empty($aEvent) || !is_array($aEvent))
            return echoJson(array());

        echoJson(array(
            'id' => $aEvent['id'],
            'view' => $sView,
        	'item' => $this->_oTemplate->getPost($aEvent, array(
        		'view' => $sView, 
        		'type' => 'owner', 
        		'owner_id' => $this->_iOwnerId, 
        		'dynamic_mode' => true
            )),
            'eval' => $this->_oConfig->getJsObject('post') . "._onGetPost(oData)"
        ));
    }

    function actionGetPosts()
    {
        $aParams = $this->_prepareParamsGet();
        list($sItems, $sLoadMore, $sBack, $sEmpty) = $this->_oTemplate->getPosts($aParams);

        echoJson(array(
        	'view' => $aParams['view'],
        	'items' => $sItems, 
        	'load_more' => $sLoadMore, 
        	'back' => $sBack,
            'empty' => $sEmpty,
        	'eval' => $this->_oConfig->getJsObject('view') . "._onGetPosts(oData)"
        ));
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
        $sComments = $this->_oTemplate->getComments($sSystem, $iId, true);

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

    public function actionGetRepostedBy()
    {
        $iRepostedId = bx_process_input(bx_get('id'), BX_DATA_INT);

        echo $this->_oTemplate->getRepostedBy($iRepostedId);
    }

    function actionRss()
    {
        $aArgs = func_get_args();

        $sType = array_shift($aArgs);
        $iOwnerId = 0;

        switch($sType) {
            case BX_BASE_MOD_NTFS_TYPE_OWNER:
                $iOwnerId = array_shift($aArgs);
                list($sUserName) = $this->getUserInfo($iOwnerId);

                $sRssCaption = _t('_bx_timeline_txt_rss_caption', $sUserName);
                $sRssLink = $this->_oConfig->getViewUrl($iOwnerId);
                break;

            case BX_BASE_MOD_NTFS_TYPE_PUBLIC:
                $sRssCaption = _t('_bx_timeline_page_title_view_home');
                $sRssLink = $this->_oConfig->getHomeViewUrl();
                break;
        }
        

        $aParams = $this->_prepareParams(BX_TIMELINE_VIEW_DEFAULT, $sType, $iOwnerId, 0, $this->_oConfig->getRssLength(), '', array(), 0);
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
     */
    public function serviceGetAuthor ($iContentId)
    {
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iContentId));
        if(empty($aEvent) || !is_array($aEvent))
            return 0;

        return $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']) ? (int)$aEvent['owner_id'] : (int)$aEvent['object_id'];
    }

    public function serviceGetDateChanged ($iContentId)
    {
        return 0;
    }

    public function serviceGetLink ($iContentId)
    {
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iContentId));
        if(empty($aEvent) || !is_array($aEvent))
            return '';

        return $this->_oConfig->getItemViewUrl($aEvent);
    }

    public function serviceGetThumb ($iContentId, $sTranscoder = '') 
    {
        return '';
    }

    public function serviceGetInfo ($iContentId, $bSearchableFieldsOnly = true)
    {
        $aContentInfo = $this->_oDb->getEvents(array(
        	'browse' => 'id', 
        	'value' => $iContentId)
        );

        return BxDolContentInfo::formatFields($aContentInfo);
    }

    public function serviceGetSearchResultUnit ($iContentId, $sUnitTemplate = '')
    {
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iContentId));
        if(empty($aEvent) || !is_array($aEvent))
            return '';

        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit.html';

        return $this->_oTemplate->unit($aEvent, true, $sUnitTemplate);
    }

    /**
     * Get Post block for a separate page.
     */
    public function serviceGetBlockPost($iProfileId = 0)
    {
    	if(empty($iProfileId) && bx_get('profile_id') !== false)
			$iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

		if(empty($iProfileId) && isLogged())
			$iProfileId = bx_get_logged_profile_id();

        if(!$iProfileId)
            return array();

        return $this->_getBlockPost($iProfileId);
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

		return $this->_getBlockPost($oProfile->id());
    }

    public function serviceGetBlockPostHome()
    {
        $iProfileId = 0;
        return $this->_getBlockPost($iProfileId, array(
            'form_display' => 'form_display_post_add_public'
        ));
    }

    public function serviceGetBlockPostAccount()
    {
        if(!isLogged())
            return '';

        $iProfileId = $this->getProfileId();
		return $this->_getBlockPost($iProfileId);
    }

    /*
     * Get View block for a separate page. Will return a block with "Empty" message if nothing found.
     */
    public function serviceGetBlockView($iProfileId = 0)
    {
    	return $this->_serviceGetBlockView($iProfileId, BX_TIMELINE_VIEW_TIMELINE);
    }

    public function serviceGetBlockViewOutline($iProfileId = 0)
    {
        return $this->_serviceGetBlockView($iProfileId, BX_TIMELINE_VIEW_OUTLINE);
    }

    public function serviceGetBlockViewProfile($sProfileModule = 'bx_persons', $iProfileContentId = 0, $iStart = -1, $iPerPage = -1, $sFilter = '', $aModules = array(), $iTimeline = -1)
    {
        $sView = BX_TIMELINE_VIEW_TIMELINE;

        return $this->_serviceGetBlockViewProfile($sProfileModule, $iProfileContentId, $sView, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
    }

	public function serviceGetBlockViewProfileOutline($sProfileModule = 'bx_persons', $iProfileContentId = 0, $iStart = -1, $iPerPage = -1, $sFilter = '', $aModules = array(), $iTimeline = -1)
    {
        $sView = BX_TIMELINE_VIEW_OUTLINE;

        return $this->_serviceGetBlockViewProfile($sProfileModule, $iProfileContentId, $sView, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
    }

    public function serviceGetBlockViewHome($iProfileId = 0, $iStart = -1, $iPerPage = -1, $iTimeline = -1, $sFilter = '', $aModules = array())
    {
        return $this->_serviceGetBlockViewHome($iProfileId, BX_TIMELINE_VIEW_TIMELINE, $iStart, $iPerPage, $this->_oConfig->getPerPage('home'), $iTimeline, $sFilter, $aModules);
    }

	public function serviceGetBlockViewHomeOutline($iProfileId = 0, $iStart = -1, $iPerPage = -1, $iTimeline = -1, $sFilter = '', $aModules = array())
    {
        return $this->_serviceGetBlockViewHome($iProfileId, BX_TIMELINE_VIEW_OUTLINE, $iStart, $iPerPage, $this->_oConfig->getPerPage('home'), $iTimeline, $sFilter, $aModules);
    }

    public function serviceGetBlockViewAccount($iProfileId = 0, $iStart = -1, $iPerPage = -1, $iTimeline = -1, $sFilter = '', $aModules = array())
    {
        if(!isLogged())
            return '';

        return $this->_serviceGetBlockViewByType($iProfileId, BX_TIMELINE_VIEW_TIMELINE, BX_TIMELINE_TYPE_OWNER_AND_CONNECTIONS, $iStart, $iPerPage, $this->_oConfig->getPerPage('account'), $iTimeline, $sFilter, $aModules);
    }

    public function serviceGetBlockViewAccountOutline($iProfileId = 0, $iStart = -1, $iPerPage = -1, $iTimeline = -1, $sFilter = '', $aModules = array())
    {
        if(!isLogged())
            return '';

        return $this->_serviceGetBlockViewByType($iProfileId, BX_TIMELINE_VIEW_OUTLINE, BX_TIMELINE_TYPE_OWNER_AND_CONNECTIONS, $iStart, $iPerPage, $this->_oConfig->getPerPage('account'), $iTimeline, $sFilter, $aModules);
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
                array('group' => $sModule . '_repost', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'repost', 'module_name' => $sModule, 'module_method' => 'get_notifications_repost', 'module_class' => 'Module'),
                array('group' => $sModule . '_repost', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'delete_repost'),
                array('group' => $sModule . '_comment', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'commentPost', 'module_name' => $sModule, 'module_method' => 'get_notifications_comment', 'module_class' => 'Module'),
                array('group' => $sModule . '_comment', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'commentRemoved'),
                array('group' => $sModule . '_vote', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote', 'module_class' => 'Module'),
				array('group' => $sModule . '_vote', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'undoVote'),
            ),
            'alerts' => array(
                array('unit' => $sModule, 'action' => 'post_common'),
                array('unit' => $sModule, 'action' => 'delete'),
                array('unit' => $sModule, 'action' => 'repost'),
                array('unit' => $sModule, 'action' => 'delete_repost'),
                array('unit' => $sModule, 'action' => 'commentPost'),
                array('unit' => $sModule, 'action' => 'commentRemoved'),
                array('unit' => $sModule, 'action' => 'doVote'),
                array('unit' => $sModule, 'action' => 'undoVote'),
            )
        );
    }

    public function serviceGetNotificationsRepost($aEvent)
    {
        $aResult = $this->serviceGetNotificationsPost($aEvent);
        $aResult['lang_key'] = '_bx_timeline_txt_object_reposted';

        return $aResult;
    }

    public function serviceGetNotificationsPost($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iContent = (int)$aEvent['object_id'];
		$aContent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iContent));
        if(empty($aContent) || !is_array($aContent))
            return array();

        $sEntryCaption = !empty($aContent['title']) ? $aContent['title'] : $this->_oConfig->getTitle($aContent['description']);

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single_ext'],
			'entry_url' => $this->_oConfig->getItemViewUrl($aContent),
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aContent['owner_id'],
			'lang_key' => '_bx_timeline_ntfs_txt_object_added', //may be empty or not specified. In this case the default one from Notification module will be used.
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

        $sEntryCaption = !empty($aContent['title']) ? $aContent['title'] : $this->_oConfig->getTitle($aContent['description']);

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

        $sEntryCaption = !empty($aContent['title']) ? $aContent['title'] : $this->_oConfig->getTitle($aContent['description']);

		return array(
			'entry_sample' => $CNF['T']['txt_sample_single'],
			'entry_url' => $this->_oConfig->getItemViewUrl($aContent),
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aContent['owner_id'],
			'subentry_sample' => $CNF['T']['txt_sample_vote_single'],
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

    public function serviceGetRepostElementBlock($iOwnerId, $sType, $sAction, $iObjectId, $aParams = array())
    {
    	if(!$this->isEnabled())
    		return '';

        $aParams = array_merge($this->_oConfig->getRepostDefaults(), $aParams);
        return $this->_oTemplate->getRepostElement($iOwnerId, $sType, $sAction, $iObjectId, $aParams);
    }

    public function serviceGetRepostCounter($sType, $sAction, $iObjectId)
    {
    	if(!$this->isEnabled())
    		return '';

		$aReposted = $this->_oDb->getReposted($sType, $sAction, $iObjectId);

        return $this->_oTemplate->getRepostCounter($aReposted);
    }

    public function serviceGetRepostJsScript()
    {
    	if(!$this->isEnabled())
    		return '';

        return $this->_oTemplate->getRepostJsScript();
    }

    public function serviceGetRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId)
    {
    	if(!$this->isEnabled())
    		return '';

        return $this->_oTemplate->getRepostJsClick($iOwnerId, $sType, $sAction, $iObjectId);
    }

    public function serviceGetMenuItemAddonComment($sSystem, $iObjectId)
    {
        if(empty($sSystem) || empty($iObjectId))
            return '';

        $oCmts = $this->getCmtsObject($sSystem, $iObjectId);
        if($oCmts === false)
            return '';

        $iCounter = (int)$oCmts->getCommentsCount();
        return  $this->_oTemplate->parseLink('javascript:void(0)', $iCounter > 0 ? $iCounter : '', array(
            'title' => _t('_bx_timeline_menu_item_title_item_comment'),
        	'onclick' => "javascript:" . $this->_oConfig->getJsObject('view') . ".commentItem(this, '" . $sSystem . "', " . $iObjectId . ")" 
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

    public function getFormPost($aParams = array())
    {
        $iUserId = $this->getUserId();

        $sFormObject = !empty($aParams['form_object']) ? $aParams['form_object'] : 'form_post';
        $sFormDisplay = !empty($aParams['form_display']) ? $aParams['form_display'] : 'form_display_post_add';
        $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject($sFormObject), $this->_oConfig->getObject($sFormDisplay), $this->_oTemplate);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            list($sUserName) = $this->getUserInfo($iUserId);

            $sType = $oForm->getCleanValue('type');
            $sType = $this->_oConfig->getPrefix('common_post') . $sType;
            BxDolForm::setSubmittedValue('type', $sType, $oForm->aFormAttrs['method']);

            $aContent = array();

            //--- Process Text ---//
            $sText = $oForm->getCleanValue('text');
            $sText = $this->_prepareTextForSave($sText);
            $bText = !empty($sText);
            unset($oForm->aInputs['text']);

            if($bText)
            	$aContent['text'] = $sText;

            //--- Process Privacy ---//
            $iObjectPrivacyView = (int)$oForm->getCleanValue('object_privacy_view');
            if(empty($iObjectPrivacyView))
                $iObjectPrivacyView = $this->_oConfig->getPrivacyViewDefault('object');

            //--- Process Link ---//
            $aLinkIds = $oForm->getCleanValue('link');
            $bLinkIds = !empty($aLinkIds) && is_array($aLinkIds);

            //--- Process Media ---//
            $aPhotoIds = $oForm->getCleanValue(BX_TIMELINE_MEDIA_PHOTO);
            $bPhotoIds = !empty($aPhotoIds) && is_array($aPhotoIds);

            $aVideoIds = $oForm->getCleanValue(BX_TIMELINE_MEDIA_VIDEO);
            $bVideoIds = !empty($aVideoIds) && is_array($aVideoIds);

            if(!$bText && !$bLinkIds && !$bPhotoIds && !$bVideoIds)
            	return array('msg' => _t('_bx_timeline_txt_err_empty_post'));

            $sSample = '<i class="sys-icon picture-o"></i>';
            $sTitle = $bText ? $this->_oConfig->getTitle($sText) : $sSample;
            $sDescription = _t('_bx_timeline_txt_user_added_sample', $sUserName, $sSample);

            $iId = $oForm->insert(array(
                'object_id' => $iUserId,
                'object_privacy_view' => $iObjectPrivacyView,
                'content' => serialize($aContent),
                'title' => $sTitle,
                'description' => $sDescription,
                'date' => time()
            ));

            if(!empty($iId)) {
            	$oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
            	if($bText)
 					$oMetatags->keywordsAdd($iId, $sText);
 				$oMetatags->locationsAddFromForm($iId, $this->_oConfig->CNF['FIELD_LOCATION_PREFIX']);

				//--- Process Link ---//
                if($bLinkIds)
                    foreach($aLinkIds as $iLinkId)
                        $this->_oDb->saveLink($iId, $iLinkId);

				//--- Process Media ---//
				if($bPhotoIds) 
					$this->_saveMedia(BX_TIMELINE_MEDIA_PHOTO, $iId, $aPhotoIds);

				if($bVideoIds)
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
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        return $oCmts;
    }

    public function getViewObject($sSystem, $iId)
    {
        if(empty($sSystem) || (int)$iId == 0)
            return false;

        $oView = BxDolView::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oView || !$oView->isEnabled())
            return false;

        return $oView;
    }

    public function getVoteObject($sSystem, $iId)
    {
        if(empty($sSystem) || (int)$iId == 0)
            return false;

        $oVote = BxDolVote::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oVote || !$oVote->isEnabled())
            return false;

        return $oVote;
    }

	public function getReportObject($sSystem, $iId)
    {
        if(empty($sSystem) || (int)$iId == 0)
            return false;

        $oReport = BxDolReport::getObjectInstance($sSystem, $iId, true, $this->_oTemplate);
        if(!$oReport || !$oReport->isEnabled())
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
        $aCheckResult = checkActionModule($iUserId, 'post', $this->getName(), $bPerform);

        $oProfileOwner = BxDolProfile::getInstance($this->_iOwnerId);
        if($oProfileOwner !== false) {
            if($oProfileOwner->checkAllowedPostInProfile() !== CHECK_ACTION_RESULT_ALLOWED)
                return _t('_sys_txt_access_denied');

            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_post', $oProfileOwner->id(), $iUserId, array('check_result' => &$aCheckResult));
        }

        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function isAllowedView($aEvent, $bPerform = false)
    {
        $CNF = $this->_oConfig->CNF;

        $mixedResult = BxDolProfile::getInstance($aEvent[$CNF['FIELD_OWNER_ID']])->checkAllowedProfileView();
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

		return true;
    }

    public function isAllowedDelete($aEvent, $bPerform = false)
    {
        if(!isLogged())
            return false;

        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if((int)$aEvent['owner_id'] == $iUserId && $this->_oConfig->isAllowDelete())
           return true;

        $aCheckResult = checkActionModule($iUserId, 'delete', $this->getName(), $bPerform);

        $oProfileOwner = BxDolProfile::getInstance((int)$aEvent['owner_id']);
        if($oProfileOwner !== false)
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

        $oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']);
        if($oProfileOwner !== false) {
            if($oProfileOwner->checkAllowedPostInProfile() !== CHECK_ACTION_RESULT_ALLOWED)
                return false;

            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_comment', $oProfileOwner->id(), (int)$this->getUserId(), array('result' => &$bResult));
        }

        return $bResult;
    }

    public function isAllowedViewCounter($aEvent, $bPerform = false)
    {
        $mixedViews = $this->getViewsData($aEvent['views']);
        if($mixedViews === false)
            return false;

        list($sSystem, $iObjectId) = $mixedViews;
        $oView = $this->getViewObject($sSystem, $iObjectId);

        $bResult = true;

        $oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']);
        if($oProfileOwner !== false)
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_view_counter', $oProfileOwner->id(), (int)$this->getUserId(), array('result' => &$bResult));

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

        $oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']);
        if($oProfileOwner !== false)
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

        $oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']);
        if($oProfileOwner !== false)
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_report', $oProfileOwner->id(), (int)$this->getUserId(), array('result' => &$bResult));

        return $bResult;
    }

    public function isAllowedRepost($aEvent, $bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if($iUserId == 0)
            return false;

        $aCheckResult = checkActionModule($iUserId, 'repost', $this->getName(), $bPerform);

        $oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']);
        if($oProfileOwner !== false)
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_repost', $oProfileOwner->id(), $iUserId, array('check_result' => &$aCheckResult));

        return $aCheckResult[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function isAllowedSend($aEvent, $bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if($iUserId == 0)
            return false;

        $aCheckResult = checkActionModule($iUserId, 'send', $this->getName(), $bPerform);

        $oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']);
        if($oProfileOwner !== false)
            bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_send', $oProfileOwner->id(), $iUserId, array('check_result' => &$aCheckResult));

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

    public function checkAllowedView ($aContentInfo, $isPerformAction = false)
    {
        if(!$this->isAllowedView($aContentInfo, $isPerformAction))
            return _t('_sys_txt_access_denied');

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkAllowedCommentsView ($aContentInfo, $isPerformAction = false)
    {
        $CNF = $this->_oConfig->CNF;

        $mixedResult = BxDolProfile::getInstance($aContentInfo[$CNF['FIELD_OWNER_ID']])->checkAllowedProfileView();
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function checkAllowedCommentsPost ($aContentInfo, $isPerformAction = false)
    {
        $sError = '_sys_txt_access_denied';

        $aContentInfo = $this->_oTemplate->getData($aContentInfo);
        if($aContentInfo === false)
            return _t($sError);

        if(!$this->isAllowedComment($aContentInfo, $isPerformAction))
            return _t($sError);

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function onPost($iId)
    {
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));

        if($this->_oConfig->isSystem($aEvent['type'], $aEvent['action'])) {
            //--- Request event's data from content module and update it in the Timeline DB.
            $this->_oTemplate->getData($aEvent);

            $sPostType = 'system';
            $iSenderId = $aEvent['owner_id'];
        } else {
            $sPostType = 'common';
            $iSenderId = $aEvent['object_id'];
        }

        //--- Event -> Post for Alerts Engine ---//
        $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'post_' . $sPostType, $iId, $iSenderId, array(
        	'privacy_view' => $aEvent['object_privacy_view'],
        	'object_author_id' => $aEvent['owner_id'],
        ));
        $oAlert->alert();
        //--- Event -> Post for Alerts Engine ---//
    }

    public function onRepost($iId, $aReposted = array())
    {
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));

        if(empty($aReposted)) {
            $aContent = unserialize($aEvent['content']);

            $aReposted = $this->_oDb->getReposted($aContent['type'], $aContent['action'], $aContent['object_id']);
            if(empty($aReposted) || !is_array($aReposted))
                return;
        }

        $iUserId = $this->getUserId();
        $this->_oDb->insertRepostTrack($aEvent['id'], $iUserId, $this->getUserIp(), $aReposted['id']);
        $this->_oDb->updateRepostCounter($aReposted['id'], $aReposted['reposts']);

        //--- Timeline -> Update for Alerts Engine ---//
        $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'repost', $aReposted['id'], $iUserId, array(
        	'privacy_view' => $aEvent['object_privacy_view'],
        	'object_author_id' => $aReposted['owner_id'],
        	'repost_id' => $iId,
        ));
        $oAlert->alert();
        //--- Timeline -> Update for Alerts Engine ---//
    }

    public function onDelete($aEvent)
    {
        $iUserId = $this->getUserId();
    	$sCommonPostPrefix = $this->_oConfig->getPrefix('common_post');

    	//--- Delete attached photos, videos and links when common event was deleted.
    	if($aEvent['type'] == $sCommonPostPrefix . BX_TIMELINE_PARSE_TYPE_POST) {
    		$this->_deleteMedia(BX_TIMELINE_MEDIA_PHOTO, $aEvent['id']);
    		$this->_deleteMedia(BX_TIMELINE_MEDIA_VIDEO, $aEvent['id']);

	        $this->_deleteLinks($aEvent['id']);
    	}

    	//--- Update parent event when repost event was deleted.
    	$bRepost = $aEvent['type'] == $sCommonPostPrefix . BX_TIMELINE_PARSE_TYPE_REPOST;
        if($bRepost) {
            $this->_oDb->deleteRepostTrack($aEvent['id']);

            $aContent = unserialize($aEvent['content']);
            $aReposted = $this->_oDb->getReposted($aContent['type'], $aContent['action'], $aContent['object_id']);
            if(!empty($aReposted) && is_array($aReposted))
                $this->_oDb->updateRepostCounter($aReposted['id'], $aReposted['reposts'], -1);
        }

        //--- Find and delete repost events when parent event was deleted.
        $bSystem = $this->_oConfig->isSystem($aEvent['type'], $aEvent['action']);
	    $aRepostEvents = $this->_oDb->getEvents(array('browse' => 'reposted_by_descriptor', 'type' => $aEvent['type']));
		foreach($aRepostEvents as $aRepostEvent) {
			$aContent = unserialize($aRepostEvent['content']);
			if(isset($aContent['type']) && $aContent['type'] == $aEvent['type'] && isset($aContent['object_id']) && (($bSystem && (int)$aContent['object_id'] == (int)$aEvent['object_id']) || (!$bSystem  && (int)$aContent['object_id'] == (int)$aEvent['id'])) && (int)$this->_oDb->deleteEvent(array('id' => (int)$aRepostEvent['id'])) > 0) {
                $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'delete_repost', $aEvent['id'], $iUserId, array(
                    'repost_id' => $aRepostEvent['id'],
                ));
                $oAlert->alert();
            }
		}

		//--- Delete associated meta.
        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
        $oMetatags->onDeleteContent($aEvent['id']);

        //--- Event -> Delete for Alerts Engine ---//
        if($bRepost)
            $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'delete_repost', $aReposted['id'], $iUserId, array(
                'repost_id' => $aEvent['id'],
            ));
        else
            $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'delete', $aEvent['id'], $iUserId);
        $oAlert->alert();
        //--- Event -> Delete for Alerts Engine ---//
    }

    public function getParams($sView = '', $sType = '', $iOwnerId = 0, $iStart = 0, $iPerPage = 0, $sFilter = '', $aModules = array(), $iTimeline = 0)
    {
        return $this->_prepareParams($sView, $sType, $iOwnerId, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
    }

    public function getViewsData(&$aViews)
    {
        if(empty($aViews) || !is_array($aViews))
            return false;

        $sSystem = isset($aViews['system']) ? $aViews['system'] : '';
        $iObjectId = isset($aViews['object_id']) ? (int)$aViews['object_id'] : 0;
        $iCount = isset($aViews['count']) ? (int)$aViews['count'] : 0;
        if($sSystem == '' || $iObjectId == 0)
            return false;

        return array($sSystem, $iObjectId, $iCount);
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

    /**
     * Protected Methods 
     */
    protected function _serviceGetBlockView($iProfileId = 0, $sView = BX_TIMELINE_VIEW_DEFAULT)
    {
        if(empty($iProfileId) && bx_get('profile_id') !== false)
			$iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);

		if(empty($iProfileId) && isLogged())
			$iProfileId = bx_get_logged_profile_id();

        $aBlock = $this->_getBlockView($iProfileId, $sView);
        if(!empty($aBlock))
            return $aBlock;

        return array('content' => MsgBox(_t('_bx_timeline_txt_msg_no_results')));
    }

    protected function _serviceGetBlockViewProfile($sProfileModule = 'bx_persons', $iProfileContentId = 0, $sView = BX_TIMELINE_VIEW_DEFAULT, $iStart = -1, $iPerPage = -1, $sFilter = '', $aModules = array(), $iTimeline = -1)
    {
    	if(empty($sProfileModule))
    		return array();

    	if(empty($iProfileContentId) && bx_get('id') !== false)
    		$iProfileContentId = bx_process_input(bx_get('id'), BX_DATA_INT);

		$oProfile = BxDolProfile::getInstanceByContentAndType($iProfileContentId, $sProfileModule);
		if(empty($oProfile))
			return array();

        return $this->_getBlockView($oProfile->id(), $sView, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
    }

    protected function _serviceGetBlockViewHome($iProfileId = 0, $sView = BX_TIMELINE_VIEW_DEFAULT, $iStart = -1, $iPerPage = -1, $iPerPageDefault = -1,  $iTimeline = -1, $sFilter = '', $aModules = array())
    {
        $sRssUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'rss/' . BX_BASE_MOD_NTFS_TYPE_PUBLIC . '/';
        BxDolTemplate::getInstance()->addPageRssLink(_t('_bx_timeline_page_title_view_home'), $sRssUrl);

        return $this->_serviceGetBlockViewByType($iProfileId, $sView, BX_BASE_MOD_NTFS_TYPE_PUBLIC, $iStart, $iPerPage, $this->_oConfig->getPerPage('home'), $iTimeline, $sFilter, $aModules);
    } 

    protected function _serviceGetBlockViewByType($iProfileId = 0, $sView = BX_TIMELINE_VIEW_DEFAULT, $sType = BX_TIMELINE_TYPE_DEFAULT, $iStart = -1, $iPerPage = -1, $iPerPageDefault = -1,  $iTimeline = -1, $sFilter = '', $aModules = array())
    {
        $aParams = $this->_prepareParams($sView, $sType, $iProfileId, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);

        $aParams['view'] = $sView;
        $aParams['per_page'] = (int)$iPerPage > 0 ? $iPerPage : ((int)$iPerPageDefault > 0 ? $iPerPageDefault : $this->_oConfig->getPerPage());

        $this->_iOwnerId = $aParams['owner_id'];

        $sContent = $this->_oTemplate->getViewBlock($aParams);
        return array('content' => $sContent);
    }

    protected function _getBlockPost($iProfileId, $aParams = array())
    {
        $this->_iOwnerId = $iProfileId;

        if($this->isAllowedPost() !== true)
            return array();

		$sContent = $this->_oTemplate->getPostBlock($this->_iOwnerId, $aParams);
        return array('content' => $sContent);
    }

    protected function _getBlockView($iProfileId, $sView = BX_TIMELINE_VIEW_DEFAULT, $iStart = -1, $iPerPage = -1, $sFilter = '', $aModules = array(), $iTimeline = -1)
    {
        if(!$iProfileId)
			return array();

        $aParams = $this->_prepareParams($sView, BX_BASE_MOD_NTFS_TYPE_OWNER, $iProfileId, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline);
        $aParams['view'] = $sView;
        $aParams['per_page'] = (int)$iPerPage > 0 ? $iPerPage : $this->_oConfig->getPerPage('profile');

        $this->_iOwnerId = $aParams['owner_id'];
        $oProfileOwner = BxDolProfile::getInstance($this->_iOwnerId);

        $mixedResult = $oProfileOwner->checkAllowedProfileView();
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return array('content' => MsgBox($mixedResult));

        list($sUserName, $sUserUrl) = $this->getUserInfo($aParams['owner_id']);

        $sRssUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'rss/' . BX_BASE_MOD_NTFS_TYPE_OWNER . '/' . $iProfileId . '/';
        $sJsObject = $this->_oConfig->getJsObject('view');
        $aMenu = array(
            array('id' => $sView . '-view-all', 'name' => $sView . '-view-all', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_all'), 'active' => 1),
            array('id' => $sView . '-view-owner', 'name' => $sView . '-view-owner', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_owner', $sUserName)),
            array('id' => $sView . '-view-other', 'name' => $sView . '-view-other', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => 'javascript:' . $sJsObject . '.changeFilter(this)', 'target' => '_self', 'title' => _t('_bx_timeline_menu_item_view_other')),
            array('id' => $sView . '-get-rss', 'name' => $sView . '-get-rss', 'class' => '', 'link' => $sRssUrl, 'target' => '_blank', 'title' => _t('_bx_timeline_menu_item_get_rss')),
        );

        $sContent = '';
        bx_alert($oProfileOwner->getModule(), $this->_oConfig->getUri() . '_view', $this->_iOwnerId, $this->getUserId(), array('override_content' => &$sContent, 'params' => &$aParams, 'menu' => &$aMenu));

        $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $sView . '-view-all', 'menu_items' => $aMenu));
        $oMenu->setSelected('', $sView . '-view-all');

        if (!$sContent)
            $sContent = $this->_oTemplate->getViewBlock($aParams);

        BxDolTemplate::getInstance()->addPageRssLink(_t('_bx_timeline_page_title_view'), $sRssUrl);

        return array('content' => $sContent, 'menu' => $oMenu);
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

        $oProfileOwner = BxDolProfile::getInstance($aEvent['owner_id']);
        if($oProfileOwner !== false)
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

    protected function _prepareParams($sView, $sType, $iOwnerId, $iStart, $iPerPage, $sFilter, $aModules, $iTimeline)
    {
        return array(
            'view' => !empty($sView) ? $sView : BX_TIMELINE_VIEW_DEFAULT,

            'browse' => 'list',
            'type' => !empty($sType) ? $sType : BX_TIMELINE_TYPE_DEFAULT,
            'owner_id' => (int)$iOwnerId != 0 ? $iOwnerId : $this->getUserId(),
            'filter' => !empty($sFilter) ? $sFilter : BX_TIMELINE_FILTER_ALL,
            'modules' => is_array($aModules) && !empty($aModules) ? $aModules : array(),
            'timeline' => (int)$iTimeline > 0 ? $iTimeline : 0,
            'active' => 1,
            'hidden' => 0,

            'start' => (int)$iStart > 0 ? $iStart : 0,
            'per_page' => (int)$iPerPage > 0 ? $iPerPage : $this->_oConfig->getPerPage(),
        );
    }

    protected function _prepareParamsGet()
    {
        $aParams = array(
            'browse' => 'list',
            'dynamic_mode' => true,
        );

        $aParams['view'] = bx_get('view');
        $aParams['view'] = $aParams['view'] !== false ? bx_process_input($aParams['view'], BX_DATA_TEXT) : BX_TIMELINE_VIEW_DEFAULT;

        $aParams['type'] = bx_get('type');
        $aParams['type'] = $aParams['type'] !== false ? bx_process_input($aParams['type'], BX_DATA_TEXT) : BX_TIMELINE_TYPE_DEFAULT;

        $aParams['owner_id'] = bx_get('owner_id');
        $aParams['owner_id'] = $aParams['owner_id'] !== false ? bx_process_input($aParams['owner_id'], BX_DATA_INT) : $this->getUserId();

        $aParams['start'] = bx_get('start');
        $aParams['start'] = $aParams['start'] !== false ? bx_process_input($aParams['start'], BX_DATA_INT) : 0;

        $aParams['per_page'] = bx_get('per_page');
        $aParams['per_page'] = $aParams['per_page'] !== false ? bx_process_input($aParams['per_page'], BX_DATA_INT) : $this->_oConfig->getPerPage();

        $aParams['filter'] = bx_get('filter');
        $aParams['filter'] = $aParams['filter'] !== false ? bx_process_input($aParams['filter'], BX_DATA_TEXT) : BX_TIMELINE_FILTER_ALL;

        $aParams['modules'] = bx_get('modules');
        $aParams['modules'] = $aParams['modules'] !== false ? bx_process_input($aParams['modules'], BX_DATA_TEXT) : array();

        $aParams['timeline'] = bx_get('timeline');
        $aParams['timeline'] = $aParams['timeline'] !== false ? bx_process_input($aParams['timeline'], BX_DATA_INT) : 0;

        $aParams['active'] = 1;
        $aParams['hidden'] = 0;

        return $aParams;
    }

    protected function _prepareTextForSave($s)
    {
        return bx_process_input($s, BX_DATA_TEXT_MULTILINE);
    }

    protected function _getFieldValue($sField, $iContentId)
    {
        $CNF = &$this->_oConfig->CNF;
        if(empty($CNF[$sField]))
            return false;

        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iContentId));
        if(empty($aEvent) || empty($aEvent[$CNF[$sField]]))
            return false;

        return $aEvent[$CNF[$sField]];
    }
}

/** @} */
