<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Base module class for text based modules
 */
class BxBaseModTextModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    // ====== SERVICE METHODS
	public function serviceGetMenuAddonManageTools()
	{
		bx_import('SearchResult', $this->_aModule);
        $sClass = $this->_aModule['class_prefix'] . 'SearchResult';
        $o = new $sClass();
        $o->fillFilters(array(
			'status' => 'hidden'
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
			'author' => bx_get_logged_profile_id()
        ));
        $o->unsetPaginate();

        return $o->getNum();
	}

    /**
     * Display public entries
     * @return HTML string
     */
    public function serviceBrowsePublic ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {   
        return $this->_serviceBrowse ('public', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * Display popular entries
     * @return HTML string
     */
    public function serviceBrowsePopular ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('popular', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * Display recently updated entries
     * @return HTML string
     */
    public function serviceBrowseUpdated ($sUnitView = false, $bEmptyMessage = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('updated', $sUnitView ? array('unit_view' => $sUnitView) : false, BX_DB_PADDING_DEF, $bEmptyMessage, $bAjaxPaginate);
    }

    /**
     * Display entries of the author
     * @return HTML string
     */
    public function serviceBrowseAuthor ($iProfileId = 0, $sUnitView = false)
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId)
            return '';
        return $this->_serviceBrowse ('author', array('author' => $iProfileId, 'unit_view' => $sUnitView ? $sUnitView : ''), BX_DB_PADDING_DEF, true);
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

        return $this->_entitySocialSharing ($iContentId, $iContentId, $aContentInfo[$CNF['FIELD_THUMB']], $aContentInfo[$CNF['FIELD_TITLE']], $CNF['OBJECT_STORAGE'], false, $CNF['OBJECT_VOTES'], $CNF['URI_VIEW_ENTRY']);
    }

    public function serviceEntityAllActions ($iContentId = 0)
    {
        return $this->_oTemplate->entryAllActions($this->serviceEntityActions($iContentId), $this->serviceEntitySocialSharing($iContentId));
    }

    /**
     * Entry text with some additional controls
     */
    public function serviceEntityTextBlock ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('viewDataEntry', $iContentId);
    }

    /**
     * Entry location info
     */
    public function serviceEntityLocation ($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iContentId)
            return false;

        return $this->_oTemplate->entryLocation ($iContentId);
    }

    /**
     * Entry comments
     */
    public function serviceEntityComments ($iContentId = 0)
    {
        return $this->_entityComments($this->_oConfig->CNF['OBJECT_COMMENTS'], $iContentId);
    }

    /**
     * Entry author block
     */
    public function serviceEntityAuthor ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryAuthor', $iContentId);
    }

    /**
     * Entry attachments block
     */
    public function serviceEntityAttachments ($iContentId = 0)
    {
        return $this->_serviceTemplateFunc ('entryAttachments', $iContentId);
    }

    /**
     * My entries actions block
     */
    public function serviceMyEntriesActions ($iProfileId = 0)
    {
        if (!$iProfileId)
            $iProfileId = bx_process_input(bx_get('profile_id'), BX_DATA_INT);
        if (!$iProfileId || $iProfileId != $this->_iProfileId)
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_MY_ENTRIES']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * Delete all content by profile 
     * @param $iProfileId profile id 
     * @return number of deleted items
     */
    public function serviceDeleteEntitiesByAuthor ($iProfileId)
    {
        $a = $this->_oDb->getEntriesByAuthor((int)$iProfileId);
        if (!$a)
            return 0;

        $iCount = 0;
        foreach ($a as $aContentInfo)
            $iCount += ('' == $this->serviceDeleteEntity($aContentInfo[$this->_oConfig->CNF['FIELD_ID']]) ? 1 : 0);

        return $iCount;
    }

	/**
     * Data for Notifications module
     */
    public function serviceGetNotificationsData()
    {
    	$sModule = $this->_aModule['name'];

        return array(
            'handlers' => array(
                array('group' => $sModule . '_object', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'added', 'module_name' => $sModule, 'module_method' => 'get_notifications_post', 'module_class' => 'Module'),
                array('group' => $sModule . '_object', 'type' => 'update', 'alert_unit' => $sModule, 'alert_action' => 'edited'),
                array('group' => $sModule . '_object', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'deleted'),
                array('group' => $sModule . '_comment', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'commentPost', 'module_name' => $sModule, 'module_method' => 'get_notifications_comment', 'module_class' => 'Module'),
                array('group' => $sModule . '_comment', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'commentRemoved'),
                array('group' => $sModule . '_vote', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote', 'module_class' => 'Module'),
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
			'entry_sample' => _t($CNF['T']['txt_sample_single']),
			'entry_url' => $sEntryUrl,
			'entry_caption' => $sEntryCaption,
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
			'entry_sample' => _t($CNF['T']['txt_sample_single']),
			'entry_url' => $sEntryUrl,
			'entry_caption' => $sEntryCaption,
			'subentry_sample' => _t($CNF['T']['txt_sample_comment_single']),
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
			'entry_sample' => _t($CNF['T']['txt_sample_single']),
			'entry_url' => $sEntryUrl,
			'entry_caption' => $sEntryCaption,
			'subentry_sample' => _t($CNF['T']['txt_sample_vote_single']),
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
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

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);

        //--- Image(s)
        $aImages = $this->_getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl);

        //--- Votes
        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $aEvent['object_id']);

        $aVotes = array();
        if ($oVotes && $oVotes->isEnabled())
            $aVotes = array(
                'system' => $CNF['OBJECT_VOTES'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['votes']
            );

        //--- Comments
        $oCmts = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $aEvent['object_id']);

        $aComments = array();
        if($oCmts && $oCmts->isEnabled())
            $aComments = array(
                'system' => $CNF['OBJECT_COMMENTS'],
                'object_id' => $aContentInfo[$CNF['FIELD_ID']],
                'count' => $aContentInfo['comments']
            );

        return array(
            'owner_id' => $aContentInfo[$CNF['FIELD_AUTHOR']],
            'content' => array(
                'sample' => _t($CNF['T']['txt_sample_single']),
                'url' => $sUrl,
                'title' => isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...'),
                'text' => $aContentInfo[$CNF['FIELD_TEXT']],
                'images' => $aImages,
            ), //a string to display or array to parse default template before displaying.
            'date' => $aContentInfo[$CNF['FIELD_ADDED']],
            'votes' => $aVotes,
            'comments' => $aComments,
            'title' => '', //may be empty.
            'description' => '' //may be empty.
        );
    }

    // ====== PERMISSION METHODS

    /**
     * @return CHECK_ACTION_RESULT_ALLOWED if access is granted or error message if access is forbidden. So make sure to make strict(===) checking.
     */
    public function checkAllowedSetThumb ()
    {
        // check ACL
        $aCheck = checkActionModule($this->_iProfileId, 'set thumb', $this->getName(), false);
        if ($aCheck[CHECK_ACTION_RESULT] !== CHECK_ACTION_RESULT_ALLOWED)
            return $aCheck[CHECK_ACTION_MESSAGE];
        return CHECK_ACTION_RESULT_ALLOWED;
    }

    // ====== PROTECTED METHODS

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl)
    {
        $CNF = &$this->_oConfig->CNF;

        $sImage = '';
        if (isset($aContentInfo[$CNF['FIELD_THUMB']]) && $aContentInfo[$CNF['FIELD_THUMB']]) {
        	
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

    protected function _buildRssParams($sMode, $aArgs)
    {
        $aParams = array ();
        $sMode = bx_process_input($sMode);
        switch ($sMode) {
            case 'author':
                $aParams = array('author' => isset($aArgs[0]) ? (int)$aArgs[0] : '');
                break;
        }

        return $aParams;
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

        return $oCmts->getCommentsBlock(0, 0, false);
    }

    protected function _entitySocialSharing ($iId, $iIdForTimeline, $iIdThumb, $sTitle, $sObjectStorage, $sObjectTranscoder, $sObjectVote, $sUriViewEntry, $sCommentsObject = '', $bEnableSocialSharing = true)
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

        $sSocial = $bEnableSocialSharing ? BxTemplSocialSharing::getInstance()->getCode($iId, $this->_aModule['name'], BX_DOL_URL_ROOT . $sUrl, $sTitle, $aCustomParams) : '';

        return $this->_oTemplate->parseHtmlByName('entry-share.html', array(
            'comments' => $sComments,
            'vote' => $sVotes,
            'share' => $sShare,
            'social' => $sSocial,
        ));
        //TODO: Rebuild using menus engine when it will be ready for such elements like Vote, Share, etc.
    }
}

/** @} */
