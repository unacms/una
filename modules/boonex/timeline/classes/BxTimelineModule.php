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
            $this->_echoResultJson(array());
            return;
        }

        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $mixedAllowed = $this->isAllowedPost(true);
        if($mixedAllowed !== true) {
            $this->_echoResultJson(array('msg' => strip_tags($mixedAllowed)));
            return;
        }

        $aResult = $this->$sMethod();
        $this->_echoResultJson($aResult);
    }

    function actionDelete()
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iId));

        $mixedAllowed = $this->isAllowedDelete($aEvent, true);
        if($mixedAllowed !== true) {
            $this->_echoResultJson(array('code' => 1, 'msg' => strip_tags($mixedAllowed)));
            return;
        }

        if(!$this->deleteEvent($aEvent))
        	$this->_echoResultJson(array('code' => 2));
        else 
        	$this->_echoResultJson(array('code' => 0, 'id' => $iId));
    }

    public function actionShare()
    {
        $iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);
        $aContent = array(
            'type' => bx_process_input(bx_get('type'), BX_DATA_TEXT),
            'action' => bx_process_input(bx_get('action'), BX_DATA_TEXT),
            'object_id' => bx_process_input(bx_get('object_id'), BX_DATA_INT),
        );

        $aShared = $this->_oDb->getShared($aContent['type'], $aContent['action'], $aContent['object_id']);
        if(empty($aShared) || !is_array($aShared)) {
            $this->_echoResultJson(array('code' => 1, 'msg' => _t('_bx_timeline_txt_err_cannot_share')));
            return;
        }

        $mixedAllowed = $this->isAllowedShare($aShared, true);
        if($mixedAllowed !== true) {
            $this->_echoResultJson(array('code' => 2, 'msg' => strip_tags($mixedAllowed)));
            return;
        }

        $iId = $this->_oDb->insertEvent(array(
            'owner_id' => $iOwnerId,
            'type' => $this->_oConfig->getPrefix('common_post') . 'share',
            'action' => '',
            'object_id' => $this->getUserId(),
            'object_privacy_view' => $this->_oConfig->getPrivacyViewDefault(),
            'content' => serialize($aContent),
            'title' => '',
            'description' => ''
        ));

        if(empty($iId)) {
	        $this->_echoResultJson(array('code' => 3, 'msg' => _t('_bx_timeline_txt_err_cannot_share')));        
	        return;
        }

        $this->onShare($iId, $aShared);

        $aShared = $this->_oDb->getShared($aContent['type'], $aContent['action'], $aContent['object_id']);
		$sCounter = $this->_oTemplate->getShareCounter($aShared);

		$this->_echoResultJson(array(
			'code' => 0, 
			'msg' => _t('_bx_timeline_txt_msg_success_share'), 
			'count' => $aShared['shares'], 
			'counter' => $sCounter
		));
    }

    function actionGetPost()
    {
        $this->_oConfig->setJsMode(true);
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $iEvent = bx_process_input(bx_get('id'), BX_DATA_INT);
        $aEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => $iEvent));

        $this->_echoResultJson(array('item' => $this->_oTemplate->getPost($aEvent, array('type' => 'owner', 'owner_id' => $this->_iOwnerId))));
    }

    function actionGetPosts()
    {
        $this->_oConfig->setJsMode(true);

        $aParams = $this->_prepareParamsGet();
        list($sItems, $sLoadMore, $sBack) = $this->_oTemplate->getPosts($aParams);

        $this->_echoResultJson(array('items' => $sItems, 'load_more' => $sLoadMore, 'back' => $sBack));
    }

    public function actionGetPostForm($sType)
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $sMethod = 'getForm' . ucfirst($sType);
        if(!method_exists($this, $sMethod)) {
            $this->_echoResultJson(array());
            return;
        }
        $aResult = $this->$sMethod();

        $this->_echoResultJson($aResult);
    }

    public function actionGetComments()
    {
        $this->_iOwnerId = bx_process_input(bx_get('owner_id'), BX_DATA_INT);

        $sSystem = bx_process_input(bx_get('system'), BX_DATA_TEXT);
        $iId = bx_process_input(bx_get('id'), BX_DATA_INT);
        $sComments = $this->_oTemplate->getComments($sSystem, $iId);

        $this->_echoResultJson(array('content' => $sComments));
    }

    public function actionGetManageMenuPopup()
    {
        $iItemId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(!$iItemId)
            return;

        echo $this->getManageMenuObject($iItemId)->getCode();
    }

    public function actionAddAttachLink()
    {
        $aResult = $this->getFormAttachLink();

        $this->_echoResultJson($aResult);
    }

    public function actionDeleteAttachLink()
    {
        $iLinkId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(empty($iLinkId)) {
            $this->_echoResultJson(array());
            return;
        }

        $aResult = array();
        if($this->_oDb->deleteUnusedLinks($this->getUserId(), $iLinkId))
            $aResult = array('code' => 0);
        else
            $aResult = array('code' => 1, 'msg' => _t('_bx_timeline_form_post_input_link_err_delete'));

        $this->_echoResultJson($aResult);
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

        bx_import('BxDolRssFactory');
        $oRss = new BxDolRssFactory();

        header('Content-Type: application/xml; charset=utf-8');
        echo $oRss->GenRssByData($aRssData, $sRssCaption, $sRssLink);
    }

    /**
     * SERVICE METHODS
     * 
     * Get Post block for a separate page.
     */
    public function serviceGetBlockPost($sProfileModule = 'bx_persons', $iProfileId = 0)
    {
        return $this->serviceGetBlockPostProfile($sProfileModule, $iProfileId);
    }

    public function serviceGetBlockPostProfile($sProfileModule = 'bx_persons', $iProfileId = 0)
    {
        if(empty($iProfileId) && !empty($sProfileModule) && bx_get('id') !== false) {
            $oProfile = BxDolProfile::getInstanceByContentAndType(bx_process_input(bx_get('id'), BX_DATA_INT), $sProfileModule);
            if(!empty($oProfile))
                $iProfileId = $oProfile->id();
        }

        if(!$iProfileId)
            return array();

        $this->_iOwnerId = $iProfileId;

        if($this->isAllowedPost() !== true)
            return array();

        return array(
            'content' => $this->_oTemplate->getPostBlock($this->_iOwnerId)
        );
    }

    /*
     * Get View block for a separate page. Will return a block with "Empty" message if nothing found.
     */
    public function serviceGetBlockView($sProfileModule = 'bx_persons', $iProfileId = 0)
    {
        $aBlock = $this->serviceGetBlockViewProfile($sProfileModule, $iProfileId);
        if(!empty($aBlock))
            return $aBlock;

        return array('content' => MsgBox(_t('_bx_timeline_txt_msg_no_results')));
    }

    public function serviceGetBlockViewProfile($sProfileModule = 'bx_persons', $iProfileId = 0, $iStart = -1, $iPerPage = -1, $sFilter = '', $aModules = array(), $iTimeline = -1)
    {
        if(empty($iProfileId) && !empty($sProfileModule) && bx_get('id') !== false) {
            $oProfile = BxDolProfile::getInstanceByContentAndType(bx_process_input(bx_get('id'), BX_DATA_INT), $sProfileModule);
            if(!empty($oProfile))
                $iProfileId = $oProfile->id();
        }

        if (!$iProfileId)
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

        bx_import('BxTemplMenuInteractive');
        $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> 'timeline-view-all', 'menu_items' => $aMenu));
        $oMenu->setSelected('', 'timeline-view-all');

        $sContent = $this->_oTemplate->getViewBlock($aParams);
        return array('content' => $sContent, 'menu' => $oMenu);
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

    public function serviceGetMenuItemAddonShare($sType, $sAction, $iObjectId)
    {
        if(empty($sType) || empty($iObjectId))
            return '';

        return $this->serviceGetShareCounter($sType, $sAction, $iObjectId);
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

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance($this->_oConfig->getObject('form_attach_link'), $this->_oConfig->getObject('form_display_attach_link_add'), $this->_oTemplate);
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'add_attach_link/';
        $oForm->aInputs['url']['checker']['params']['preg'] = $this->_oConfig->getPregPattern('url');

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $sLink = $oForm->getCleanValue('url');
            $sLinkContent = bx_file_get_contents($sLink);

            $aMatches = array();
            preg_match($this->_oConfig->getPregPattern('url'), $sLink, $aMatches);
            $sLink = (empty($aMatches[1]) ? 'http://' : '') . $aMatches[0];

            $aMatches = array();
            preg_match($this->_oConfig->getPregPattern('meta_title'), $sLinkContent, $aMatches);
            $sLinkTitle = $aMatches ? $aMatches[1] : '';

            $aMatches = array();
            preg_match($this->_oConfig->getPregPattern('meta_description'), $sLinkContent, $aMatches);
            $sLinkDescription = $aMatches ? $aMatches[1] : '';

            $iId = (int)$oForm->insert(array('profile_id' => $iUserId, 'url' => $sLink, 'title' => $sLinkTitle, 'text' => $sLinkDescription, 'added' => time()));
            if(!empty($iId))
                return array('item' => $this->_oTemplate->getAttachLinkItem($iUserId, $iId));

            return array('msg' => _t('_bx_timeline_txt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

    public function getFormPost()
    {
        $iUserId = $this->getUserId();

        bx_import('BxDolForm');
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
                'object_privacy_view' => $this->_oConfig->getPrivacyViewDefault(),
                'content' => serialize($aContent),
                'title' => $sTitle,
                'description' => $sDescription,
                'date' => time()
            ));

            if(!empty($iId)) {
            	bx_import('BxDolMetatags');
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

        bx_import('BxDolCmts');
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iId);
        if(!$oCmts->isEnabled())
            return false;

        return $oCmts;
    }

    public function getVoteObject($sSystem, $iId)
    {
        if(empty($sSystem) || (int)$iId == 0)
            return false;

        bx_import('BxDolVote');
        $oVote = BxDolVote::getObjectInstance($sSystem, $iId);
        if(!$oVote->isEnabled())
            return false;

        return $oVote;
    }

    public function getAttachmentsMenuObject()
    {
		bx_import('BxDolMenu');
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_post_attachments'), $this->_oTemplate);
        $oMenu->addMarkers(array(
            'js_object' => $this->_oConfig->getJsObject('post'),
        ));

        return $oMenu;
    }

    public function getManageMenuObject($mixedEvent)
    {
    	if(!is_array($mixedEvent))
    		$mixedEvent = $this->_oDb->getEvents(array('browse' => 'id', 'value' => (int)$mixedEvent));

        bx_import('BxDolMenu');
        $oMenu = BxDolMenu::getObjectInstance($this->_oConfig->getObject('menu_item_manage'), $this->_oTemplate);
        $oMenu->setEvent($mixedEvent);
        return $oMenu;
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
        return $aCheckResult[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function isAllowedDelete($aEvent, $bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if((int)$aEvent['owner_id'] == $iUserId && $this->_oConfig->isAllowDelete())
           return true;

        $aCheckResult = checkActionModule($iUserId, 'delete', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
    }

    public function isAllowedComment($aEvent, $bPerform = false)
    {
        $mixedComments = $this->getCommentsData($aEvent['comments']);
        if($mixedComments === false)
            return false;

        list($sSystem, $iObjectId) = $mixedComments;
        $oCmts = $this->getCmtsObject($sSystem, $iObjectId);
        $oCmts->addCssJs();

        $iUserId = (int)$this->getUserId();
        if($iUserId == 0)
            return false;

        if(isAdmin())
            return true;

        return $oCmts->isPostReplyAllowed($bPerform);
    }

    public function isAllowedVote($aEvent, $bPerform = false)
    {
        $mixedVotes = $this->getVotesData($aEvent['votes']);
        if($mixedVotes === false)
            return false;

        list($sSystem, $iObjectId) = $mixedVotes;
        $oVote = $this->getVoteObject($sSystem, $iObjectId);
        $oVote->addCssJs();

        $iUserId = (int)$this->getUserId();
        if($iUserId == 0)
            return false;

        if(isAdmin())
            return true;

        return $oVote->isAllowedVote($bPerform);
    }

    public function isAllowedShare($aEvent, $bPerform = false)
    {
        if(isAdmin())
            return true;

        $iUserId = (int)$this->getUserId();
        if($iUserId == 0)
            return false;

        $aCheckResult = checkActionModule($iUserId, 'share', $this->getName(), $bPerform);
        return $aCheckResult[CHECK_ACTION_RESULT] != CHECK_ACTION_RESULT_ALLOWED ? $aCheckResult[CHECK_ACTION_MESSAGE] : true;
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
        bx_import('BxDolAlerts');
        $oAlert = new BxDolAlerts($this->_oConfig->getObject('alert'), 'post_' . $sPostType, $iId, $iSenderId);
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
        bx_import('BxDolAlerts');
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

	        $this->_oDb->deleteLinks($aEvent['id']);
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
		bx_import('BxDolMetatags');
        $oMetatags = BxDolMetatags::getObjectInstance($this->_oConfig->getObject('metatags'));
        $oMetatags->onDeleteContent($aEvent['id']);

        //--- Event -> Delete for Alerts Engine ---//
        bx_import('BxDolAlerts');
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

    protected function _saveMedia($sType, $iId, $aItemIds)
    {
    	if(empty($aItemIds) || !is_array($aItemIds))
    		return; 

    	$iUserId = $this->getUserId();

		bx_import('BxDolStorage');
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

		bx_import('BxDolStorage');
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
