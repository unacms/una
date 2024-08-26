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

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();
        return array_merge($a, array (
            'EntityAddFiles' => '',
            'MediaComments' => '',
            'BrowseRecentMedia' => '',
            'BrowseFeaturedMedia' => '',
            'BrowsePopularMedia' => '',
            'BrowseTopMedia' => '',
            'BrowseFavoriteMedia' => '',
        ));
    }
    
    public function actionEmbed($iContentId, $sUnitTemplate = '', $sAddCode = '')
    {
        return $this->_oTemplate->getJsCode('main') . parent::actionEmbed($iContentId);
    }

    public function actionEmbedMedia($iContentId)
    {
        $oTemplate = BxDolTemplate::getInstance();
        
        $aContentInfo = $this->_oDb->getMediaInfoById($iContentId);
        if(empty($aContentInfo))
            $oTemplate->getEmbed(false);

        if(empty($sUnitTemplate))
            $sUnitTemplate = 'unit_media_gallery.html';

        $oTemplate->getEmbed($this->_oTemplate->unit($aContentInfo, true, $sUnitTemplate));
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
     * @page service Service Calls
     * @section bx_albums Albums
     * @subsection bx_albums-forms Forms
     * @subsubsection bx_albums-entity_add_files entity_add_files
     * 
     * @code bx_srv('bx_albums', 'entity_add_files', [...]); @endcode
     * 
     * Display form for adding media to the album.
     * @param $iContentId album content id where media will be added, if it's not provided then it's determined from 'id' GET variable
     * @return HTML string with form, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. On error false or empty string is returned.
     * 
     * @see BxAlbumsModule::serviceEntityAddFiles
     */
    /** 
     * @ref bx_albums-entity_add_files "entity_add_files"
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
        
        /**
         * @hooks
         * @hookdef hook-bx_albums-media_deleted 'bx_albums', 'media_deleted' - hook on new media deleted from album
         * - $unit_name - equals `bx_albums`
         * - $action - equals `media_deleted` 
         * - $object_id - album_id
         * - $sender_id - author's profile_id
         * - $extra_params - array of additional params with the following array keys:
         *      - `object_author_id` - [int] confirmation type can be none/phone/email/email_and_phone/email_or_phone
         *      - `subobject_id` - [int] id for added media
         *      - `media_id` - [int] id for added media
         *      - `media_info` - [array] media info
         * @hook @ref hook-bx_albums-media_deleted
         */
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

        BxDolPage::deleteSeoLink ($this->getName(), 'bx_albums_media', $aMediaInfo['id']);

        return true;
    }

    /**
     * @page service Service Calls
     * @section bx_albums Albums
     * @subsection bx_albums-page_blocks Page Blocks
     * @subsubsection bx_albums-media_comments media_comments
     * 
     * @code bx_srv('bx_albums', 'media_comments', [...]); @endcode
     * 
     * Display media comments block.
     * @param $iMediaId media ID, if it's omitted then it's taken from 'id' GET variable.
     * @return HTML string with comments. On error false or empty string is returned.
     * 
     * @see BxAlbumsModule::serviceMediaComments
     */
    /** 
     * @ref bx_albums-media_comments "media_comments"
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
            'entry_url' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $iMediaId)),
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
     * @param $aParams array of additional params. 
     * @return HTML string with block content. On error false or empty string is returned.
     */
    public function serviceMediaView ($iMediaId = 0, $mixedContext = false, $aParams = array())
    {
        if(!$iMediaId)
            $iMediaId = bx_process_input(bx_get('id'), BX_DATA_INT);
        if(!$iMediaId)
            return false;

        if(!$mixedContext) {
            $mixedContext = bx_process_input(bx_get('context'));
            if(!in_array($mixedContext, $this->_aContexts)) // when no context specified, it is assumed that it is an album context
                $mixedContext = bx_process_input($mixedContext, BX_DATA_INT); // numeric context is reserved for future use
        }
        $aParams['context'] = $mixedContext;

        $iAutoplay = 0;
        if(!isset($aParams['autoplay']) && ($iAutoplay = bx_get('autoplay')) !== false)
            $aParams['autoplay'] = (int)$iAutoplay;

        return $this->_oTemplate->entryMediaView ($iMediaId, $aParams);
    }

    public function checkAllowedSetThumb ($iContentId = 0)
    {
        return CHECK_ACTION_RESULT_NOT_ALLOWED;
    }

    /**
     * @page service Service Calls
     * @section bx_albums Albums
     * @subsection bx_albums-browse Browse
     * @subsubsection bx_albums-browse_recent_media browse_recent_media
     * 
     * @code bx_srv('bx_albums', 'browse_recent_media', [...]); @endcode
     * 
     * Display block for browsing recently uploaded media files.
     * @param $sUnitView unit view mode: gallery
     * @param $bDisplayEmptyMsg display 'empty' message when nothing to browse, or return empty string.
     * @param $bAjaxPaginate use AJAX or regular paginate.
     * @return HTML string with block content. On error false or empty string is returned.
     * 
     * @see BxAlbumsModule::serviceBrowseRecentMedia
     */
    /** 
     * @ref bx_albums-browse_recent_media "browse_recent_media"
     */
    public function serviceBrowseRecentMedia ($sUnitView = false, $bDisplayEmptyMsg = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('recent', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    /**
     * @page service Service Calls
     * @section bx_albums Albums
     * @subsection bx_albums-browse Browse
     * @subsubsection bx_albums-browse_featured_media browse_featured_media
     * 
     * @code bx_srv('bx_albums', 'browse_featured_media', [...]); @endcode
     * 
     * Display featured media. 
     * @param $sUnitView unit view mode: gallery
     * @param $bDisplayEmptyMsg display 'empty' message when nothing to browse, or return empty string.
     * @param $bAjaxPaginate use AJAX or regular paginate.
     * @return HTML string with block content. On error false or empty string is returned.
     * 
     * @see BxAlbumsModule::serviceBrowseFeaturedMedia
     */
    /** 
     * @ref bx_albums-browse_featured_media "browse_featured_media"
     */
    public function serviceBrowseFeaturedMedia ($sUnitView = false, $bDisplayEmptyMsg = false, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('featured', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    /**
     * @page service Service Calls
     * @section bx_albums Albums
     * @subsection bx_albums-browse Browse
     * @subsubsection bx_albums-browse_popular_media browse_popular_media
     * 
     * @code bx_srv('bx_albums', 'browse_popular_media', [...]); @endcode
     * 
     * Display popular media.
     * @param $sUnitView unit view mode: gallery
     * @param $bDisplayEmptyMsg display 'empty' message when nothing to browse, or return empty string.
     * @param $bAjaxPaginate use AJAX or regular paginate.
     * @return HTML string with block content. On error false or empty string is returned.
     * 
     * @see BxAlbumsModule::serviceBrowsePopularMedia
     */
    /** 
     * @ref bx_albums-browse_popular_media "browse_popular_media"
     */
    public function serviceBrowsePopularMedia ($sUnitView = false, $bDisplayEmptyMsg = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('popular', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    /**
     * @page service Service Calls
     * @section bx_albums Albums
     * @subsection bx_albums-browse Browse
     * @subsubsection bx_albums-browse_top_media browse_top_media
     * 
     * @code bx_srv('bx_albums', 'browse_top_media', [...]); @endcode
     * 
     * Display top media.
     * @param $sUnitView unit view mode: gallery
     * @param $bDisplayEmptyMsg display 'empty' message when nothing to browse, or return empty string.
     * @param $bAjaxPaginate use AJAX or regular paginate.
     * @return HTML string with block content. On error false or empty string is returned.
     * 
     * @see BxAlbumsModule::serviceBrowseTopMedia
     */
    /** 
     * @ref bx_albums-browse_top_media "browse_top_media"
     */
    public function serviceBrowseTopMedia ($sUnitView = false, $bDisplayEmptyMsg = true, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('top', array('unit_view' => $sUnitView), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate, 'SearchResultMedia');
    }

    /**
     * @page service Service Calls
     * @section bx_albums Albums
     * @subsection bx_albums-browse Browse
     * @subsubsection bx_albums-browse_favorite_media browse_favorite_media
     * 
     * @code bx_srv('bx_albums', 'browse_favorite_media', [...]); @endcode
     * 
     * Display favorite media for particular profile. 
     * @param $iProfileId profile ID, if omitted then 'profile_id' GET variable is used 
     * @param $aParams additional params for browsing such as 'unit_view'
     * 
     * @see BxAlbumsModule::serviceBrowseFavoriteMedia
     */
    /** 
     * @ref bx_albums-browse_favorite_media "browse_favorite_media"
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
            '_cache' => true,
            'owner_id' => $aEvent['owner_id'],
            'object_owner_id' => $aContentInfo['author'],
            'icon' => !empty($CNF['ICON']) ? $CNF['ICON'] : '',
            'sample' => isset($CNF['T']['txt_sample_single_with_article']) ? $CNF['T']['txt_sample_single_with_article'] : $CNF['T']['txt_sample_single'],
            'sample_wo_article' => $CNF['T']['txt_sample_single'],
    	    'sample_action' => $CNF['T']['txt_sample_action_changed'],
            'url' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId)),
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
            
            array('group' => $sModule . '_score_up_media', 'type' => 'insert', 'alert_unit' => $sModule . '_media', 'alert_action' => 'doVoteUp', 'module_name' => $sModule, 'module_method' => 'get_notifications_score_up_media', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),

            array('group' => $sModule . '_score_down_media', 'type' => 'insert', 'alert_unit' => $sModule . '_media', 'alert_action' => 'doVoteDown', 'module_name' => $sModule, 'module_method' => 'get_notifications_score_down_media', 'module_class' => 'Module', 'module_event_privacy' => $sEventPrivacy),
        ));

        $aResult['settings'] = array_merge($aResult['settings'], array(
            array('group' => 'content', 'unit' => $sModule, 'action' => 'medias_added', 'types' => array('follow_member', 'follow_context')),
            array('group' => 'comment', 'unit' => $sModule . '_media', 'action' => 'commentPost', 'types' => array('personal', 'follow_member', 'follow_context')),
            array('group' => 'vote', 'unit' => $sModule . '_media', 'action' => 'doVote', 'types' => array('personal', 'follow_member', 'follow_context')),
            array('group' => 'score_up', 'unit' => $sModule . '_media', 'action' => 'doVoteUp', 'types' => array('personal', 'follow_member', 'follow_context')),
            array('group' => 'score_down', 'unit' => $sModule . '_media', 'action' => 'doVoteDown', 'types' => array('personal', 'follow_member', 'follow_context'))
        ));

        $aResult['alerts'] = array_merge($aResult['alerts'], array(
            array('unit' => $sModule, 'action' => 'medias_added'),
            array('unit' => $sModule, 'action' => 'media_deleted'),

            array('unit' => $sModule . '_media', 'action' => 'commentPost'),
            array('unit' => $sModule . '_media', 'action' => 'commentRemoved'),

            array('unit' => $sModule . '_media', 'action' => 'doVote'),
            array('unit' => $sModule . '_media', 'action' => 'undoVote'),

            array('unit' => $sModule . '_media', 'action' => 'doVoteUp'),
            array('unit' => $sModule . '_media', 'action' => 'doVoteDown'),
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
        $sEntryUrl = bx_absolute_url($oPermalinks->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId), '{bx_url_root}');
        $sSubentryUrl = bx_absolute_url($oPermalinks->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $iMediaId), '{bx_url_root}');
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

        $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $aMediaInfo['id']), '{bx_url_root}');
        $sEntryCaption = isset($aMediaInfo['title']) ? $aMediaInfo['title'] : _t('_bx_albums_media');

        return array(
            'object_id' => $aMediaInfo['content_id'],
            'entry_sample' => $CNF['T']['txt_media_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aMediaInfo['author'],
            'subentry_sample' => $CNF['T']['txt_media_comment_single'],
            'subentry_url' => bx_absolute_url($oComment->getViewUrl((int)$aEvent['subobject_id'], false), '{bx_url_root}'),
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

        $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $aMediaInfo['id']), '{bx_url_root}');
        $sEntryCaption = isset($aMediaInfo['title']) ? $aMediaInfo['title'] : _t('_bx_albums_media');

        return array(
            'object_id' => $aMediaInfo['content_id'],
            'entry_sample' => $CNF['T']['txt_media_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aMediaInfo['author'],
            'subentry_sample' => $CNF['T']['txt_media_vote_single'],
            'subentry_url' => '',
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

    public function serviceGetNotificationsScoreUpMedia($aEvent)
    {
    	return $this->_serviceGetNotificationsScoreMedia('up', $aEvent);
    }

    public function serviceGetNotificationsScoreDownMedia($aEvent)
    {
    	return $this->_serviceGetNotificationsScoreMedia('down', $aEvent);
    }

    protected function _serviceGetNotificationsScoreMedia($sType, $aEvent)
    {
        $CNF = &$this->_oConfig->CNF;

    	$iMediaId = (int)$aEvent['object_id'];
    	$aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        if(empty($aMediaInfo) || !is_array($aMediaInfo))
            return array();

        $oVote = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES_MEDIA'], $iMediaId);
        if(!$oVote || !$oVote->isEnabled())
            return array();

        $sEntryUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_MEDIA'] . '&id=' . $aMediaInfo['id']), '{bx_url_root}');
        $sEntryCaption = isset($aMediaInfo['title']) ? $aMediaInfo['title'] : _t('_bx_albums_media');

        return array(
            'object_id' => $aMediaInfo['content_id'],
            'entry_sample' => $CNF['T']['txt_media_single'],
            'entry_url' => $sEntryUrl,
            'entry_caption' => $sEntryCaption,
            'entry_author' => $aMediaInfo['author'],
            'subentry_sample' => $CNF['T']['txt_media_score_' . $sType . '_single'],
            'subentry_url' => '',
            'lang_key' => '', //may be empty or not specified. In this case the default one from Notification module will be used.
        );
    }

    public function actionEditMedia($iMediaId)
    {
        $CNF = &$this->_oConfig->CNF;

        $iMediaId = (int)$iMediaId;
        $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        $aContentInfo = $this->_oDb->getContentInfoById($aMediaInfo['content_id']);
        if(($sMsg = $this->checkAllowedEdit($aContentInfo)) !== CHECK_ACTION_RESULT_ALLOWED)
            return echoJson(['msg' => $sMsg]);

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_MEDIA'], $CNF['OBJECT_FORM_MEDIA_DISPLAY_EDIT']);
        $oForm->initForm('edit', $iMediaId);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iMediaId) !== false) {
                $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
                if(!empty($aMediaInfo) && is_array($aMediaInfo) && !empty($CNF['OBJECT_METATAGS_MEDIA'])) {
                    $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS_MEDIA']);
                    if ($oMetatags->keywordsIsEnabled())
                        $oMetatags->keywordsAdd($aMediaInfo['id'], $aMediaInfo['title']);
                }

                $aRes = array('reload' => 1);
            }
            else
                $aRes = array('msg' => _t('_bx_albums_txt_err_cannot_perform_action'));

            return echoJson($aRes);
        }

        $sContent = BxTemplStudioFunctions::getInstance()->transBox('bx-albums-edit-media-popup', $this->_oTemplate->parseHtmlByName('media-edit.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true)
        )));

        echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
    }
	
    public function actionDeleteMedia($iMediaId)
    {
        $CNF = &$this->_oConfig->CNF;

        $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
        $oUploader = BxDolUploader::getObjectInstance($CNF['OBJECT_UPLOADERS'][0], $CNF['OBJECT_STORAGE'], '');
        $oUploader->deleteGhost($aMediaInfo['file_id'], bx_get_logged_profile_id());
        
        echoJson(['redirect' => bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aMediaInfo['content_id']))]);
    }

    public function actionMoveMedia($iMediaId)
    {
        $CNF = &$this->_oConfig->CNF;

        $iMediaId = (int)$iMediaId;

        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_MEDIA'], $CNF['OBJECT_FORM_MEDIA_DISPLAY_MOVE']);
        $oForm->initForm('move', $iMediaId);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            if($oForm->update($iMediaId) !== false)
                $aRes = array('reload' => 1);
            else
                $aRes = array('msg' => _t('_bx_albums_txt_err_cannot_perform_action'));

            return echoJson($aRes);
        }

        $sContent = BxTemplStudioFunctions::getInstance()->transBox('bx-albums-move-media-popup', $this->_oTemplate->parseHtmlByName('media-edit.html', array(
            'form_id' => $oForm->aFormAttrs['id'],
            'form' => $oForm->getCode(true)
        )));

        echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
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

    public function getMediaDuration($aMediaInfo) 
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aMediaInfo) || !is_array($aMediaInfo))
            return 0;

        $sField = 'duration';
        if(!empty($aMediaInfo[$sField]))
            return (int)$aMediaInfo[$sField];

        $iMedia = $aMediaInfo['id'];
        $sMedia = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4'])->getFileUrl($iMedia);
        if(empty($sMedia))
            return 0;

        $iDuration = (int)BxDolTranscoderVideo::getDuration($sMedia);
        if(!empty($iDuration))
            $this->_oDb->updateMedia(array($sField => $iDuration), array('id' => $iMedia));

        return $iDuration;
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

    protected function _getImagesForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']) || empty($CNF['OBJECT_TRANSCODER_COVER']))
            return array();

        $aMediaList = $this->_oDb->getMediaListByContentId($aContentInfo[$CNF['FIELD_ID']]);
        if(empty($aMediaList) || !is_array($aMediaList))
            return array();

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        $oTcPoster = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']);
        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $oTranscoderCover = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_TRANSCODER_COVER']);
        if(!$oTranscoder || !$oTranscoderCover)
            return array();

        $aOutput = array();
        foreach ($aMediaList as $aMedia) {
            $aFileInfo = $oStorage->getFile($aMedia['file_id']);

            $bVideo = $oTcPoster && strncmp('video/', $aFileInfo['mime_type'], 6) === 0 && $oTcPoster->isMimeTypeSupported($aFileInfo['mime_type']);
            if($bVideo)
                continue;

            $aOutput[$aMedia['id']] = array (
                'id' => $aMedia['id'],
                'url' => $this->_oTemplate->getViewMediaUrl($CNF, $aMedia['id']), 
                'src' => $oTranscoder->getFileUrl($aMedia['file_id']),
                'src_orig' => $oTranscoderCover->getFileUrl($aMedia['file_id'])
            );
        }

        if(empty($aOutput))
            return array();

        $iSlice = 4;
        $iTotal = count($aOutput);
        return array(
            'total' => $iTotal,
            'items' => $iTotal > $iSlice ? array_slice($aOutput, 0, $iSlice) : $aOutput
        );
    }

    protected function _getVideosForTimelinePostAttach($aEvent, $aContentInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($CNF['OBJECT_VIDEOS_TRANSCODERS']))
            return array();

        $aMediaList = $this->_oDb->getMediaListByContentId($aContentInfo[$CNF['FIELD_ID']]);
        if(empty($aMediaList) || !is_array($aMediaList))
            return array();

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        $oTcPoster = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']);
        $oTcMp4 = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']);
        $oTcMp4Hd = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4_hd']);
        if(!$oTcPoster || !$oTcMp4 || !$oTcMp4Hd)
            return array();

        $aOutput = array();
        foreach($aMediaList as $k => $aMedia) {
            $aFileInfo = $oStorage->getFile($aMedia['file_id']);

            $bVideo = $oTcPoster && strncmp('video/', $aFileInfo['mime_type'], 6) === 0 && $oTcPoster->isMimeTypeSupported($aFileInfo['mime_type']);
            if(!$bVideo)
                continue;

            $sVideoUrl = $oStorage->getFileUrlById($aMedia['file_id']);
            $aVideoFile = $oStorage->getFile($aMedia['file_id']);

            $sVideoUrlHd = '';
            if (!empty($aVideoFile['dimensions']) && $oTcMp4Hd->isProcessHD($aVideoFile['dimensions']))
                $sVideoUrlHd = $oTcMp4Hd->getFileUrl($aMedia['file_id']);

            $aOutput[$aMedia['id']] = array(
                'id' => $aMedia['id'],
                'src_poster' => $oTcPoster->getFileUrl($aMedia['file_id']),
                'src_mp4' => $oTcMp4->getFileUrl($aMedia['file_id']),
                'src_mp4_hd' => $sVideoUrlHd,
                'duration' => $aFileInfo['duration'],
            );
        }

        if(empty($aOutput))
            return array();

        $iSlice = 4;
        $iTotal = count($aOutput);
        return array(
            'total' => $iTotal,
            'items' => $iTotal > $iSlice ? array_slice($aOutput, 0, $iSlice) : $aOutput
        );
    }

    protected function _getContentForTimelineMedia($aEvent, $aContentInfo, $aBrowseParams = array())
    {
    	$CNF = &$this->_oConfig->CNF;

        $sUrl = bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]));
        $sTitle = isset($CNF['FIELD_TITLE']) && isset($aContentInfo[$CNF['FIELD_TITLE']]) ? $aContentInfo[$CNF['FIELD_TITLE']] : (isset($CNF['FIELD_TEXT']) && isset($aContentInfo[$CNF['FIELD_TEXT']]) ? strmaxtextlen($aContentInfo[$CNF['FIELD_TEXT']], 20, '...') : '');

        $oStorage = BxDolStorage::getObjectInstance($CNF['OBJECT_STORAGE']);
        $oTcPoster = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['poster']);
        $oTcMp4 = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4']);
        $oTcMp4Hd = BxDolTranscoderVideo::getObjectInstance($CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4_hd']);
        $oTranscoder = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW']);
        $oTranscoderCover = BxDolTranscoderImage::getObjectInstance($CNF['OBJECT_TRANSCODER_COVER']);

    	//--- Image(s) and Video(s)
        $aImages = $aVideos = array();
        foreach($aEvent['content']['medias_added'] as $iMediaId) {
            $aMediaInfo = $this->_oDb->getMediaInfoById($iMediaId);
            if(empty($aMediaInfo) || !is_array($aMediaInfo))
                continue;

            $aFileInfo = $oStorage->getFile($aMediaInfo['file_id']);
            $bVideo = $oTcPoster && strncmp('video/', $aFileInfo['mime_type'], 6) === 0 && $oTcPoster->isMimeTypeSupported($aFileInfo['mime_type']);
            if($bVideo)
                $aVideos[$iMediaId] = array(
                    'id' => $aMediaInfo['id'],
                    'src_poster' => $oTcPoster->getFileUrl($aMediaInfo['file_id']),
                    'src_mp4' => $oTcMp4->getFileUrl($aMediaInfo['file_id']),
                    'src_mp4_hd' => $oTcMp4Hd->getFileUrl($aMediaInfo['file_id']),
                    'duration' => $aFileInfo['duration'],
                );
            else
                $aImages[$iMediaId] = array(
                    'id' => $aMediaInfo['id'],
                    'url' => $this->_oTemplate->getViewMediaUrl($CNF, $aMediaInfo['id']), 
                    'src' => $oTranscoder->getFileUrl($aMediaInfo['file_id']),
                    'src_orig' => $oTranscoderCover->getFileUrl($aMediaInfo['file_id'])
                );
        }

    	return array(
            'sample' => isset($CNF['T']['txt_sample_single_with_article']) ? $CNF['T']['txt_sample_single_with_article'] : $CNF['T']['txt_sample_single'],
            'sample_wo_article' => $CNF['T']['txt_sample_single'],
            'sample_action' => isset($CNF['T']['txt_sample_action_changed']),
            'url' => $sUrl,
            'title' => $sTitle,
            'text' => '',
            'images_attach' => $aImages,
            'videos_attach' => $aVideos
        );
    }

    protected function _getImagesForTimelineMedia($aEvent, $aMediaInfo, $sUrl, $aBrowseParams = array())
    {
        $CNF = &$this->_oConfig->CNF;

        $aImages = array();

        

        return $aImages;
    }
}

/** @} */
