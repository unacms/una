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
     * Entry actions and social sharing block
     */
    public function serviceEntityAllActions ($mixedContent = false, $aParams = array())
    {
        if(!empty($mixedContent)) {
            if(!is_array($mixedContent))
               $mixedContent = array((int)$mixedContent, array());
        }
        else {
            $mixedContent = $this->_getContent();
            if($mixedContent === false)
                return false;
        }
        
        list($iContentId, $aContentInfo) = $mixedContent;

        $aMedias = $this->_oDb->getMediaListByContentId($iContentId);
        if(!empty($aMedias) && is_array($aMedias)) {
            $aMedia = array_shift($aMedias);
            if(!empty($aMedia['file_id']))
                $aParams = array_merge(array(
                    'entry_thumb' => $aMedia['file_id']
                ), $aParams);
        }

        return parent::serviceEntityAllActions(array($iContentId, $aContentInfo), $aParams);
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

        $aContentInfo = $this->_oDb->getContentInfoById($aMediaInfo['content_id']);
        $iSender = isLogged() ? bx_get_logged_profile_id() : $aMediaInfo['author'];
        $iAuthor = isset($aContentInfo[$CNF['FIELD_AUTHOR']]) ? $aContentInfo[$CNF['FIELD_AUTHOR']] : $aMediaInfo['author'];
        bx_alert($this->getName(), 'media_deleted', $aMediaInfo['content_id'], $iSender, array(
            'object_author_id' => $iAuthor,

            'subobject_id' => $aMediaInfo['id'],

            'media_id' => $aMediaInfo['id'], 
            'media_info' => $aMediaInfo,
        ));

        bx_alert($this->getName() . '_media', 'deleted', $aMediaInfo['id'], $iSender, array(
            'object_id' => $aMediaInfo['content_id'],
            'object_author_id' => $iAuthor,

            'media_info' => $aMediaInfo,
        ));        

        if (!empty($CNF['OBJECT_VIEWS_MEDIA'])) {
            $o = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS_MEDIA'], $aMediaInfo['id']);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_VOTES_MEDIA'])) {
            $o = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_MEDIA'], $aMediaInfo['id']);
            if ($o) $o->onObjectDelete();
        }

        if (!empty($CNF['OBJECT_SCORES_MEDIA'])) {
            $o = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES_MEDIA'], $aMediaInfo['id']);
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
     * Entry actions and social sharing block
     */
    public function serviceMediaAllActions ($mixedContent = false, $aParams = array())
    {
        $iMediaId = 0;
        $aMediaInfo = array();

        $bContent = !empty($mixedContent);
        if($bContent && is_array($mixedContent))
            list($iMediaId, $aMediaInfo) = $mixedContent;
        else {
            if($bContent)
                $iMediaId = (int)$mixedContent;
            else
                $iMediaId = bx_process_input(bx_get('id'), BX_DATA_INT);

            if(!$iMediaId)
                return false;

            $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        }

        if(!$iMediaId || !$aMediaInfo)
            return false;

        $CNF = &$this->_oConfig->CNF;

        return parent::serviceEntityAllActions (array($iMediaId, $aMediaInfo), array_merge(array(
            'object_menu' => $CNF['OBJECT_MENU_ACTIONS_VIEW_MEDIA_ALL'],
            'object_transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_BIG'],
            'entry_url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $iMediaId),
            'entry_title' => $aMediaInfo['title'],
            'entry_thumb' => $aMediaInfo['file_id']
        ), $aParams));
    }

    /**
     * Entry actions block
     */
    public function serviceMediaActions ($iContentId = 0)
    {
        $iContentId = $this->_getContent($iContentId, false);
        if($iContentId === false)
            return false;

        $oMenu = BxTemplMenu::getObjectInstance($this->_oConfig->CNF['OBJECT_MENU_ACTIONS_VIEW_MEDIA']);
        return $oMenu ? $oMenu->getCode() : false;
    }

    /**
     * Display media social sharing buttons block.
     * @param $iMediaId media ID, if it's omitted then it's taken from 'id' GET variable.
     * @return HTML string with block content. On error false or empty string is returned.
     */
    public function serviceMediaSocialSharing ($iMediaId = 0)
    {
        if(!$iMediaId)
            $iMediaId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(!$iMediaId)
            return false;

        $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        if(!$aMediaInfo)
            return false;

        $CNF = &$this->_oConfig->CNF;

        return $this->serviceEntitySocialSharing(array($iMediaId, $aMediaInfo), array(
            'uri' => $CNF['URI_VIEW_MEDIA'],
            'title' => $aMediaInfo['title'],
            'id_thumb' => $aMediaInfo['file_id'],
            'object_transcoder' => $CNF['OBJECT_IMAGES_TRANSCODER_BIG']
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

    public function checkAllowedSetThumb ($iContentId = 0)
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
    public function serviceBrowseFeaturedMedia ($sUnitView = false, $bDisplayEmptyMsg = false, $bAjaxPaginate = true)
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

        $bEmptyMessage = false;
        if(isset($aParams['empty_message'])) {
            $bEmptyMessage = (bool)$aParams['empty_message'];
            unset($aParams['empty_message']);
        }

        return $this->_serviceBrowse ('favorite', array_merge(array('user' => $oProfile->id()), $aParams), BX_DB_PADDING_DEF, $bEmptyMessage, true, 'SearchResultMedia');
    }

    public function serviceGetTimelineData()
    {
    	$sModule = $this->_aModule['name'];

        $aResult = parent::serviceGetTimelineData();
        $aResult['handlers'] = array_merge($aResult['handlers'], array(
            array('group' => $sModule . '_object_media', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'medias_added', 'module_name' => $sModule, 'module_method' => 'get_timeline_media', 'module_class' => 'Module',  'groupable' => 0, 'group_by' => ''),
        ));
        $aResult['alerts'] = array_merge($aResult['alerts'], array(
            array('unit' => $sModule, 'action' => 'medias_added')
        ));

        return $aResult;
    }

    public function serviceGetTimelineMedia($aEvent, $aBrowseParams = array())
    {
        if(empty($aEvent['content']))
            return false;

        $aEvent['content'] = unserialize($aEvent['content']);
        if(empty($aEvent['content']['medias_added']) || !is_array($aEvent['content']['medias_added']))
            return false;

        $iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return false;

        $CNF = &$this->_oConfig->CNF;
        
        //--- Views
        $oViews = isset($CNF['OBJECT_VIEWS']) ? BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $iContentId) : null;

        $aViews = array();
        if ($oViews && $oViews->isEnabled())
            $aViews = array(
                'system' => $CNF['OBJECT_VIEWS'],
                'object_id' => $iContentId,
                'count' => $aContentInfo['views']
            );

        //--- Votes
        $oVotes = isset($CNF['OBJECT_VOTES']) ? BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $iContentId) : null;

        $aVotes = array();
        if ($oVotes && $oVotes->isEnabled())
            $aVotes = array(
                'system' => $CNF['OBJECT_VOTES'],
                'object_id' => $iContentId,
                'count' => $aContentInfo['votes']
            );

        //--- Scores
        $oScores = isset($CNF['OBJECT_SCORES']) ? BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], $iContentId) : null;

        $aScores = array();
        if ($oScores && $oScores->isEnabled())
            $aScores = array(
                'system' => $CNF['OBJECT_SCORES'],
                'object_id' => $iContentId,
                'score' => $aContentInfo['score']
            );

        //--- Reports
        $oReports = isset($CNF['OBJECT_REPORTS']) ? BxDolReport::getObjectInstance($CNF['OBJECT_REPORTS'], $aEvent['object_id']) : null;

        $aReports = array();
        if ($oReports && $oReports->isEnabled())
            $aReports = array(
                'system' => $CNF['OBJECT_REPORTS'],
                'object_id' => $iContentId,
                'count' => $aContentInfo['reports']
            );

        //--- Comments
        $oCmts = isset($CNF['OBJECT_COMMENTS']) ? BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId) : null;

        $aComments = array();
        if($oCmts && $oCmts->isEnabled())
            $aComments = array(
                'system' => $CNF['OBJECT_COMMENTS'],
                'object_id' => $iContentId,
                'count' => $aContentInfo['comments']
            );

        //--- Title & Description
        $sTitle = isset($CNF['FIELD_TITLE']) && isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : (isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]) ? strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...') : '');

        return array(
            'owner_id' => $aContentInfo['author'],
            'icon' => !empty($CNF['ICON']) ? $CNF['ICON'] : '',
            'sample' => isset($CNF['T']['txt_sample_single_with_article']) ? $CNF['T']['txt_sample_single_with_article'] : $CNF['T']['txt_sample_single'],
            'sample_wo_article' => $CNF['T']['txt_sample_single'],
    	    'sample_action' => $CNF['T']['txt_sample_action_changed'],
            'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId),
            'content' => $this->_getContentForTimelineMedia($aEvent, $aContentInfo, $aBrowseParams), //a string to display or array to parse default template before displaying.
            'date' => $aContentInfo[$CNF['FIELD_ADDED']],
            'views' => $aViews,
            'votes' => $aVotes,
            'scores' => $aScores,
            'reports' => $aReports,
            'comments' => $aComments,
            'title' => $sTitle, //may be empty.
            'description' => '' //may be empty.
        );
    }

    public function serviceGetNotificationsData()
    {
        $sModule = $this->_aModule['name'];

        $sEventPrivacy = $sModule . '_allow_view_event_to';
        if(BxDolPrivacy::getObjectInstance($sEventPrivacy) === false)
                $sEventPrivacy = '';

        $aResult = parent::serviceGetNotificationsData();
        $aResult['handlers'] = array_merge($aResult['handlers'], array(
            array('group' => $sModule . '_object_media', 'type' => 'insert', 'alert_unit' => $sModule, 'alert_action' => 'medias_added', 'module_name' => $sModule, 'module_method' => 'get_notifications_media', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_object_media', 'type' => 'delete', 'alert_unit' => $sModule, 'alert_action' => 'media_deleted'),

            array('group' => $sModule . '_comment_media', 'type' => 'insert', 'alert_unit' => $sModule . '_media', 'alert_action' => 'commentPost', 'module_name' => $sModule, 'module_method' => 'get_notifications_comment_media', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_comment_media', 'type' => 'delete', 'alert_unit' => $sModule . '_media', 'alert_action' => 'commentRemoved'),

            array('group' => $sModule . '_vote_media', 'type' => 'insert', 'alert_unit' => $sModule . '_media', 'alert_action' => 'doVote', 'module_name' => $sModule, 'module_method' => 'get_notifications_vote_media', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
            array('group' => $sModule . '_vote_media', 'type' => 'delete', 'alert_unit' => $sModule . '_media', 'alert_action' => 'undoVote'),
        ));

        $aResult['settings'] = array_merge($aResult['settings'], array(
            array('group' => 'content', 'unit' => $sModule, 'action' => 'medias_added', 'types' => array('follow_member', 'follow_context')),
            array('group' => 'comment', 'unit' => $sModule . '_media', 'action' => 'commentPost', 'types' => array('personal', 'follow_member', 'follow_context')),
            array('group' => 'vote', 'unit' => $sModule . '_media', 'action' => 'doVote', 'types' => array('personal', 'follow_member', 'follow_context'))
        ));

        $aResult['alerts'] = array_merge($aResult['alerts'], array(
            array('unit' => $sModule, 'action' => 'medias_added'),
            array('unit' => $sModule, 'action' => 'media_deleted'),

            array('unit' => $sModule . '_media', 'action' => 'commentPost'),
            array('unit' => $sModule . '_media', 'action' => 'commentRemoved'),

            array('unit' => $sModule . '_media', 'action' => 'doVote'),
            array('unit' => $sModule . '_media', 'action' => 'undoVote'),
        ));

        return $aResult; 
    }

    public function serviceGetNotificationsMedia($aEvent)
    {
    	$CNF = &$this->_oConfig->CNF;

        $iContentId = (int)$aEvent['object_id'];
        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return array();

    	$iMediaId = (int)$aEvent['subobject_id'];
    	$aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        if(empty($aMediaInfo) || !is_array($aMediaInfo))
            return array();

        $oPermalinks = BxDolPermalinks::getInstance();
        $sEntryUrl = BX_DOL_URL_ROOT . $oPermalinks->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId);
        $sSubentryUrl = BX_DOL_URL_ROOT . $oPermalinks->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $iMediaId);
        $sEntryCaption = isset($aMediaInfo['title']) ? $aMediaInfo['title'] : _t('_bx_albums_media');

        return array(
            'entry_sample' => $CNF['T']['txt_sample_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aMediaInfo['author'],
            'subentry_sample' => $CNF['T']['txt_media_single'],
            'subentry_url' => $sSubentryUrl,
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
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

    protected function _getImagesForTimelinePost($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
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

    protected function _getContentForTimelineMedia($aEvent, $aContentInfo, $aBrowseParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;

        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]);
        $sTitle = isset($CNF['FIELD_TITLE']) && isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : (isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]) ? strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...') : '');

    	//--- Image(s)
        $aImages = $this->_getImagesForTimelineMedia($aEvent, $aContentInfo, $sUrl, $aBrowseParams);

    	return array(
            'sample' => isset($CNF['T']['txt_sample_single_with_article']) ? $CNF['T']['txt_sample_single_with_article'] : $CNF['T']['txt_sample_single'],
            'sample_wo_article' => $CNF['T']['txt_sample_single'],
            'sample_action' => isset($CNF['T']['txt_sample_action_changed']),
            'url' => $sUrl,
            'title' => $sTitle,
            'text' => '',
            'images' => $aImages,
            'videos' => array()
        );
    }

    protected function _getImagesForTimelineMedia($aEvent, $aMediaInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aImages = array();

        foreach($aEvent['content']['medias_added'] as $iMediaId) {
            $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
            if(empty($aMediaInfo) || !is_array($aMediaInfo))
                continue;

            $sImage = $this->_oConfig->getImageUrl($aMediaInfo['file_id'], array('OBJECT_TRANSCODER_BROWSE'));
            if(empty($sImage))
                continue;

            $aImages[] = array(
                'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $aMediaInfo['id']), 
                'src' => $sImage
            );
        }

        return $aImages;
    }
}

/** @} */
