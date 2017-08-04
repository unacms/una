<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolAcl');

/**
 * Albums module
 */
class BxAlbumsModule extends BxBaseModTextModule
{
    protected $_aContexts = array('popular', 'public', 'author');

    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }

    /**
     * Display form for adding media to the album.
     * @param $iContentId album content id where media will be added, if it's not provided then it's determined from 'id' GET variable
     * @return HTML string with form, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. On error false or empty string is returned.
     */ 
    public function serviceEntityAddFiles ($iContentId = 0)
    {
        return $this->_serviceEntityForm ('editDataForm', $iContentId, 'bx_albums_entry_add_images');
    }

    /**
     * Delete file and album association, also file views, votes, comments, meta data are also deleted
     * @param $iFileId file ID
     * @return true on success of false on error
     */ 
    public function serviceDeleteFileAssociations($iFileId)
    {        
        $CNF = &$this->_oConfig->CNF;

        if (!($aMediaInfo = $this->_oDb->getMediaInfoSimpleByFileId($iFileId))) // file is already deleted
            return true; 
    
        if (!$this->_oDb->deassociateFileWithContent(0, $iFileId))
            return false;

        if (!empty($CNF['OBJECT_VIEWS_MEDIA'])) {
            $o = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS_MEDIA'], $aMediaInfo['id']);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_VOTES_MEDIA'])) {
            $o = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_MEDIA'], $aMediaInfo['id']);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_COMMENTS_MEDIA'])) {
            $o = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS_MEDIA'], $aMediaInfo['id']);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_METATAGS_MEDIA'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA']);
            $oMetatags->onDeleteContent($aMediaInfo['id']);
        }

        if (!empty($CNF['OBJECT_METATAGS_MEDIA_CAMERA'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA_CAMERA']);
            $oMetatags->onDeleteContent($aMediaInfo['id']);
        }

        return true;
    }

    /**
     * Display media EXIF information.
     * @param $iMediaId media ID, if it's omitted then it's taken from 'id' GET variable.
     * @return HTML string with EXIF info. On error empty string is returned.
     */ 
    public function serviceMediaExif ($iMediaId = 0)
    {
        return $this->_serviceTemplateFunc ('mediaExif', $iMediaId, 'getMediaInfoById');
    }

    /**
     * Display media comments block.
     * @param $iMediaId media ID, if it's omitted then it's taken from 'id' GET variable.
     * @return HTML string with comments. On error false or empty string is returned.
     */
    public function serviceMediaComments ($iMediaId = 0)
    {
        return $this->_entityComments($this->_oConfig->CNF['OBJECT_COMMENTS_MEDIA'], $iMediaId);
    }

    /**
     * Display media author block.
     * @param $iMediaId media ID, if it's omitted then it's taken from 'id' GET variable.
     * @return HTML string with block content. On error false or empty string is returned.
     */
    public function serviceMediaAuthor ($iMediaId = 0)
    {
        return $this->_serviceTemplateFunc ('mediaAuthor', $iMediaId, 'getMediaInfoById');
    }

    /**
     * Display media social sharing buttons block.
     * @param $iMediaId media ID, if it's omitted then it's taken from 'id' GET variable.
     * @return HTML string with block content. On error false or empty string is returned.
     */
    public function serviceMediaSocialSharing ($iMediaId = 0, $bEnableCommentsBtn = false, $bEnableSocialSharing = true)
    {
        if (!$iMediaId)
            $iMediaId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iMediaId)
            return false;
        $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        if (!$aMediaInfo)
            return false;

        $CNF = &$this->_oConfig->CNF;
        return $this->_entitySocialSharing ($iMediaId, array(
            'id_timeline' => 0,
        	'id_thumb' => $aMediaInfo['file_id'],
        	'title' => $aMediaInfo['title'],
        	'object_storage' => false,
            'object_transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_BIG'],
        	'object_vote' => $CNF['OBJECT_VOTES_MEDIA'],
        	'object_favorite' => $CNF['OBJECT_FAVORITES_MEDIA'],
        	'object_report' => '',
        	'object_comments' => $bEnableCommentsBtn ? $CNF['OBJECT_COMMENTS_MEDIA'] : '',
        	'object_feature' => $CNF['OBJECT_FEATURED_MEDIA'],
        	'uri_view_entry' => $CNF['URI_VIEW_MEDIA'],
            'social_sharing' => $bEnableSocialSharing
        ));
    }

    /**
     * Display media preview block.
     * @param $iMediaId media ID, if it's omitted then it's taken from 'id' GET variable.
     * @param $mixedContext current context to navigate to next and previous file, possible values: popular, public, author. If omitted then value from 'context' GTE variable is taken. Default context is 'album' - when nothin is specified.
     * @return HTML string with block content. On error false or empty string is returned.
     */
    public function serviceMediaView ($iMediaId = 0, $mixedContext = false)
    {
        if (!$iMediaId)
            $iMediaId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if (!$iMediaId)
            return false;

        if (!$mixedContext) {
            $mixedContext = bx_process_input(bx_get('context'));
            if (!in_array($mixedContext, $this->_aContexts)) // when no context specified, it is assumed that it is an album context
                $mixedContext = bx_process_input($mixedContext, BX_DATA_INT); // numeric context is reserved for future use
        }

        return $this->_oTemplate->entryMediaView ($iMediaId, $mixedContext);
    }

    public function checkAllowedSetThumb ()
    {
        return CHECK_ACTION_RESULT_NOT_ALLOWED;
    }

    /**
     * Display block for browsing recently uploaded media files.
     * @param $sUnitView unit view mode: gallery
     * @param $bDisplayEmptyMsg display 'empty' message when nothing to browse, or return empty string.
     * @param $bAjaxPaginate use AJAX or regular paginate.
     * @return HTML string with block content. On error false or empty string is returned.
     */    
    public function serviceBrowseRecentMedia ($sUnitView = false, $bDisplayEmptyMsg = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('recent', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    /**
     * Display featured media. 
     * For the list of params @see BxAlbumsModule::serviceBrowseRecentMedia
     */ 
    public function serviceBrowseFeaturedMedia ($sUnitView = false, $bDisplayEmptyMsg = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('featured', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    /**
     * Display popular media. 
     * For the list of params @see BxAlbumsModule::serviceBrowseRecentMedia
     */     
    public function serviceBrowsePopularMedia ($sUnitView = false, $bDisplayEmptyMsg = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('popular', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    /**
     * Display favorite media for particular profile. 
     * @param $iProfileId profile ID, if omitted then 'profile_id' GET variable is used 
     * @param $aParams additional params for browsing such as 'unit_view'
     */         
    public function serviceBrowseFavoriteMedia ($iProfileId = 0, $aParams = array())
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

        return $this->_serviceBrowse ('favorite', array_merge(array('user' => $oProfile->id()), $aParams), BX_DB_PADDING_DEF, true, true, 'SearchResultMedia');
    }

    public function serviceGetNotificationsData()
    {
        $sModule = $this->_aModule['name'];

        $sEventPrivacy = $sModule . '_allow_view_event_to';
		if(BxDolPrivacy::getObjectInstance($sEventPrivacy) === false)
			$sEventPrivacy = '';

        $aResult = parent::serviceGetNotificationsData();
        $aResult['handlers'] = array_merge($aResult['handlers'], array(
            array('group' => $sModule . '_comment_media', 'type' => 'insert', 'alert_unit' => $sModule . '_media', 'alert_action' => 'commentPost', 'module_name' => $sModule, 'module_method' => 'get_notifications_comment_media', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_comment_media', 'type' => 'delete', 'alert_unit' => $sModule . '_media', 'alert_action' => 'commentRemoved'),

            array('group' => $sModule . '_vote_media', 'type' => 'insert', 'alert_unit' => $sModule . '_media', 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote_media', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_vote_media', 'type' => 'delete', 'alert_unit' => $sModule . '_media', 'alert_action' => 'undoVote'),
        ));

        $aResult['alerts'] = array_merge($aResult['alerts'], array(
            array('unit' => $sModule . '_media', 'action' => 'commentPost'),
            array('unit' => $sModule . '_media', 'action' => 'commentRemoved'),

            array('unit' => $sModule . '_media', 'action' => 'doVote'),
            array('unit' => $sModule . '_media', 'action' => 'undoVote'),
        ));

        return $aResult; 
    }

    public function serviceGetNotificationsCommentMedia($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iMediaId = (int)$aEvent['object_id'];
    	$aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        if(empty($aMediaInfo) || !is_array($aMediaInfo))
            return array();

		$oComment = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS_MEDIA'], $iMediaId);
        if(!$oComment || !$oComment->isEnabled())
            return array();

        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $aMediaInfo['id']);
        $sEntryCaption = isset($aMediaInfo['title']) ? $aMediaInfo['title'] : _t('_bx_albums_media');

		return array(
			'entry_sample' => $CNF['T']['txt_media_single'],
			'entry_url' => $sEntryUrl,
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aMediaInfo['author'],
			'subentry_sample' => $CNF['T']['txt_media_comment_single'],
			'subentry_url' => $oComment->getViewUrl((int)$aEvent['subobject_id']),
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

    public function serviceGetNotificationsVoteMedia($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iMediaId = (int)$aEvent['object_id'];
    	$aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        if(empty($aMediaInfo) || !is_array($aMediaInfo))
            return array();

		$oVote = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_MEDIA'], $iMediaId);
        if(!$oVote || !$oVote->isEnabled())
            return array();

        $sEntryUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $aMediaInfo['id']);
        $sEntryCaption = isset($aMediaInfo['title']) ? $aMediaInfo['title'] : _t('_bx_albums_media');

		return array(
			'entry_sample' => $CNF['T']['txt_media_single'],
			'entry_url' => $sEntryUrl,
			'entry_caption' => $sEntryCaption,
			'entry_author' => $aMediaInfo['author'],
			'subentry_sample' => $CNF['T']['txt_media_vote_single'],
			'subentry_url' => '',
			'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
		);
    }

    public function actionGetSiblingMedia($iMediaId, $mixedContext)
    {
        $aSiblings = false;
        $sErrorMsg = false;
        if (!($aMediaInfo = $this->_oDb->getMediaInfoById((int)$iMediaId))) 
            $sErrorMsg = _t('_sys_txt_error_occured');

        if (empty($sErrorMsg) && !($aContentInfo = $this->_oDb->getContentInfoById($aMediaInfo['content_id'])))
            $sErrorMsg = _t('_sys_txt_error_occured');

        if (empty($sErrorMsg) && (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedView($aContentInfo))))
            $sErrorMsg = $sMsg;

        if (empty($sErrorMsg)) {
            $aSiblings = array (
                'next' => $this->_oTemplate->getNextPrevMedia($aMediaInfo, true, $mixedContext),
                'prev' => $this->_oTemplate->getNextPrevMedia($aMediaInfo, false, $mixedContext),
            );
        }
    
        $a = $sErrorMsg ? array('error' => $sErrorMsg) : array('next' => $aSiblings['next'], 'prev' => $aSiblings['prev']);

        $s = json_encode($a);

        header('Content-type: text/html; charset=utf-8');
        echo $s;
    }

    public function actionRssMedia ()
    {
        $aArgs = func_get_args();
        $this->_rss($aArgs, 'SearchResultMedia');
    }

    protected function _buildRssParams($sMode, $aArgs)
    {        
        if ($aParams = parent::_buildRssParams($sMode, $aArgs))
            return $aParams;

        $sMode = bx_process_input($sMode);
        switch ($sMode) {
            case 'album':
                $aParams = array('album_id' => isset($aArgs[0]) ? (int)$aArgs[0] : '');
                break;
        }

        return $aParams;
    }

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl)
    {
        $CNF = &$this->_oConfig->CNF;

        if (!($aMediaList = $this->_oDb->getMediaListByContentId($aContentInfo[$CNF['FIELD_ID']])))
            return array();

        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $aMediaList = array_slice($aMediaList, 0, 3);
        $aOutput = array();
        foreach ($aMediaList as $aMedia) {
            $aOutput[] = array (
                'url' => $this->_oTemplate->getViewMediaUrl($CNF, $aMedia['id']), 
                'src' => $oTranscoder->getFileUrl($aMedia['file_id']),
            );
        }

        return $aOutput;
    }
}

/** @} */
