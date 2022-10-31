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

bx_import('BxDolPrivacy');

class BxTimelineConfig extends BxBaseModNotificationsConfig
{
    protected $_aTypeToFormDisplay;

    protected $_bAllowEdit;
    protected $_bAllowDelete;
    protected $_bShowAll;
    protected $_bCountAllViews;
    protected $_bRepostOwnActions;
    protected $_bHideUponDelete;

    protected $_bCacheItem;
    protected $_sCacheItemEngine;
    protected $_iCacheItemLifetime;

    protected $_bInfScroll;
    protected $_iInfScrollAutoPreloads;

    protected $_iRssLength;
    protected $_iLiveUpdateLength;
    protected $_iCharsDisplayMinTitle;
    protected $_iCharsDisplayMaxTitle;

    protected $_bBriefCards;
    protected $_aBriefCardsTags;
    protected $_sVideosPreload;
    protected $_sVideosAutoplay;
    protected $_iPreloadComments;
    protected $_iPreloadCommentsMax;
    protected $_bJumpTo;
    protected $_bSortByReaction;
    protected $_bSortByUnread;
    protected $_sAttachmentsLayout;

    protected $_bHot;
    protected $_iHotInterval;
    protected $_iHotThresholdContent;
    protected $_iHotThresholdComment;
    protected $_iHotThresholdVote;
    protected $_aHotSources;
    protected $_aHotSourcesList;
    protected $_aHotList;

    protected $_bEditorToolbar;
    protected $_bEditorAutoAttach;
    protected $_iLimitAttachLinks;

    protected $_bUnhideRestored;

    protected $_sStorageObject;
    protected $_sTranscoderObjectPreview;
    protected $_sTranscoderObjectView;
    protected $_aPhotoUploaders;
    protected $_aVideoUploaders;
    protected $_aItemToUploader;

    protected $_sMenuItemManage;
    protected $_sMenuItemActions;

    protected $_aRepostDefaults;

    protected $_iTimelineVisibilityThreshold;
    protected $_aPregPatterns;

    protected $_sSessionKeyType;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
            'ICON' => 'far clock col-green1',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'events',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'object_owner_id',
            'FIELD_OWNER_ID' => 'owner_id',
            'FIELD_SYSTEM' => 'system',
            'FIELD_OBJECT_ID' => 'object_id', //Note. For 'Direct Timeline Posts' ('system' db field == 0) this field contains post's author profile ID.
            'FIELD_OBJECT_OWNER_ID' => 'object_owner_id',
            'FIELD_OBJECT_PRIVACY_VIEW' => 'object_privacy_view',
            'FIELD_CF' => 'object_cf',
            'FIELD_ADDED' => 'date',
            'FIELD_PUBLISHED' => 'published',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'description',
            'FIELD_ATTACHMENTS' => 'attachments',
            'FIELD_LINK' => 'link',
            'FIELD_PHOTO' => 'photo',
            'FIELD_VIDEO' => 'video',
            'FIELD_FILE' => 'file',
            'FIELD_STATUS' => 'status',
            'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_STICKED' => 'sticked',
            'FIELD_DATE' => 'date',
            'FIELD_REACTED' => 'reacted',
            'FIELD_ANONYMOUS' => 'anonymous',
            'FIELD_CONTROLS' => 'controls',
            'FIELD_LOCATION' => 'location',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_DELAYED_PROCESSING' => 'video', // can be array of fields or comma separated string of field names

            // page URIs
            'URI_VIEW_LIST' => 'timeline-view',
            'URI_VIEW_ENTRY' => 'item',

            'URL_HOME' => 'page.php?i=timeline-view-home',

            // some params
            'PARAM_AUTO_APPROVE' => $this->_sName . '_enable_auto_approve',

            // objects
            'OBJECT_STORAGE' => $this->_sName . '_photos',
            'OBJECT_STORAGE_VIDEOS' => $this->_sName . '_videos',
            'OBJECT_STORAGE_FILES' => $this->_sName . '_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => $this->_sName . '_photos_preview',
            'OBJECT_VIDEOS_TRANSCODER_PREVIEW' => $this->_sName . '_proxy_preview',
            'OBJECT_VIDEOS_TRANSCODERS' => array(
                'poster' => 'bx_timeline_videos_poster_view', 
                'poster_preview' => 'bx_timeline_videos_poster_preview', 
            	'mp4' => 'bx_timeline_videos_mp4', 
            	'mp4_hd' => 'bx_timeline_videos_mp4_hd'
            ),
            'OBJECT_UPLOADER_PHOTO_SIMPLE' => $this->_sName . '_simple_photo',
            'OBJECT_UPLOADER_PHOTO_HTML5' => $this->_sName . '_html5_photo',
            'OBJECT_UPLOADER_VIDEO_SIMPLE' => $this->_sName . '_simple_video',
            'OBJECT_UPLOADER_VIDEO_HTML5' => $this->_sName . '_html5_video',
            'OBJECT_UPLOADER_VIDEO_RECORD' => $this->_sName . '_record_video',
            'OBJECT_UPLOADER_FILE_HTML5' => $this->_sName . '_html5_file',
            'OBJECT_UPLOADER_FILE_SIMPLE' => $this->_sName . '_simple_file',
            'OBJECT_GRID_ADMINISTRATION' => $this->_sName . '_administration',
            'OBJECT_GRID_COMMON' => $this->_sName . '_common',
            'OBJECT_GRID_MUTE' => $this->_sName . '_mute',
            'OBJECT_MENU_ENTRY_ATTACHMENTS' => $this->_sName . '_menu_post_attachments',
            'OBJECT_METATAGS' => $this->_sName,
            'OBJECT_COMMENTS' => $this->_sName,
            'OBJECT_NOTES' => $this->_sName . '_notes',
            'OBJECT_VIEWS' => $this->_sName,
            'OBJECT_VOTES' => $this->_sName,
            'OBJECT_REACTIONS' => $this->_sName . '_reactions',
            'OBJECT_SCORES' => $this->_sName,
            'OBJECT_REPORTS' => $this->_sName,
            'OBJECT_PRIVACY_VIEW' => $this->_sName . '_privacy_view',
            'OBJECT_CONNECTIONS_MUTE' => $this->_sName . '_mute',

            // some language keys
            'T' => array (
                'txt_status_deleted' => '_bx_timeline_txt_status_deleted',
                'txt_sample_single' => '_bx_timeline_txt_sample',
                'txt_sample_single_ext' => '_bx_timeline_txt_sample_ext',
            	'txt_sample_comment_single' => '_bx_timeline_txt_sample_comment_single',
                'txt_sample_vote_single' => '_bx_timeline_txt_sample_vote_single',
                'txt_sample_reaction_single' => '_bx_timeline_txt_sample_reaction_single',
                'txt_sample_score_up_single' => '_bx_timeline_txt_sample_score_up_single',
                'txt_sample_score_down_single' => '_bx_timeline_txt_sample_score_down_single',
                'txt_sample_with_link' => '_bx_timeline_txt_sample_with_link',
                'txt_sample_with_image' => '_bx_timeline_txt_sample_with_image',
                'txt_sample_with_video' => '_bx_timeline_txt_sample_with_video',
                'txt_sample_with_file' => '_bx_timeline_txt_sample_with_file',
                'txt_sample_with_media' => '_bx_timeline_txt_sample_with_media',
                'grid_action_err_delete' => '_bx_timeline_grid_action_err_delete', 
                'grid_txt_account_manager' => '_bx_timeline_grid_txt_account_manager',
                'filter_item_active' => '_bx_timeline_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_timeline_grid_filter_item_title_adm_hidden',
                'filter_item_pending' => '_bx_timeline_grid_filter_item_title_adm_pending',
            	'filter_item_select_one_filter1' => '_bx_timeline_grid_filter_item_title_adm_select_one_filter1',
                'form_input_title_object_privacy_view' => '_bx_timeline_form_post_input_object_privacy_view',
                'option_vp_auto' => '_bx_timeline_option_videos_preload_auto',
                'option_vp_metadata' => '_bx_timeline_option_videos_preload_metadata',
                'option_vp_none' => '_bx_timeline_option_videos_preload_none',
                'option_vap_off' => '_bx_timeline_option_videos_autoplay_off',
                'option_vap_on_mute' => '_bx_timeline_option_videos_autoplay_on_mute',
                'option_vap_on' => '_bx_timeline_option_videos_autoplay_on',
                'option_al_gallery' => '_bx_timeline_option_attachments_layout_gallery',
                'option_al_showcase' => '_bx_timeline_option_attachments_layout_showcase',
                'option_hs_content' => '_bx_timeline_option_hot_sources_content',
                'option_hs_comment' => '_bx_timeline_option_hot_sources_comment',
                'option_hs_vote' => '_bx_timeline_option_hot_sources_vote',
            ),
        );

        $this->_aTypeToFormDisplay = array(
            BX_BASE_MOD_NTFS_TYPE_OWNER => 'form_display_post_add_profile',
            BX_BASE_MOD_NTFS_TYPE_PUBLIC => 'form_display_post_add_public',
            BX_TIMELINE_TYPE_CHANNELS => 'form_display_post_add',
            BX_TIMELINE_TYPE_FEED => 'form_display_post_add',
            BX_TIMELINE_TYPE_OWNER_AND_CONNECTIONS => 'form_display_post_add'
        );

        $this->_aPrefixes = array(
            'style' => 'bx-tl',
            'language' => '_bx_timeline',
            'option' => 'bx_timeline_',
            'common_post' => 'timeline_common_',
            'cache_list_hot' => 'bx_timeline_list_hot',
            'cache_item' => 'bx_timeline_item_'
        );

        $this->_aObjects = array_merge($this->_aObjects, array(
            'comment' => $this->CNF['OBJECT_COMMENTS'],
            'view' => $this->CNF['OBJECT_VIEWS'],
            'vote' => $this->CNF['OBJECT_VOTES'],
            'reaction' => $this->CNF['OBJECT_REACTIONS'],
            'score' => $this->CNF['OBJECT_SCORES'],
            'report' => $this->CNF['OBJECT_REPORTS'],
            'privacy_view' => $this->CNF['OBJECT_PRIVACY_VIEW'],
            'metatags' => $this->_sName,

            'storage_photos' => $this->CNF['OBJECT_STORAGE'],
            'storage_videos' => $this->CNF['OBJECT_STORAGE_VIDEOS'],
            'storage_files' => $this->CNF['OBJECT_STORAGE_FILES'],
            'transcoder_photos_preview' => $this->CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW'],
            'transcoder_photos_view' => $this->_sName . '_photos_view',
            'transcoder_photos_medium' => $this->_sName . '_photos_medium',
            'transcoder_photos_big' => $this->_sName . '_photos_big',
            'transcoder_videos_preview' => $this->CNF['OBJECT_VIDEOS_TRANSCODER_PREVIEW'],
            'transcoder_videos_poster' => $this->CNF['OBJECT_VIDEOS_TRANSCODERS']['poster'],
            'transcoder_videos_mp4' => $this->CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4'],
            'transcoder_videos_mp4_hd' => $this->CNF['OBJECT_VIDEOS_TRANSCODERS']['mp4_hd'],
            'transcoder_videos_photo_view' => $this->_sName . '_videos_photo_view',
            'transcoder_videos_photo_big' => $this->_sName . '_videos_photo_big',

            'page_item_brief' => $this->_sName . '_item_brief',

            'menu_view' => $this->_sName . '_menu_view',
            'menu_feeds' => $this->_sName . '_menu_feeds',
            'menu_set_feeds' => $this->_sName . '_menu_feeds',
            'menu_item_manage' => $this->_sName . '_menu_item_manage',
            'menu_item_actions' => $this->_sName . '_menu_item_actions',
            'menu_item_actions_all' => $this->_sName . '_menu_item_actions_all',
            'menu_item_counters' => $this->_sName . '_menu_item_counters',
            'menu_item_meta' => $this->_sName . '_menu_item_meta',
            'menu_post_attachments' => $this->CNF['OBJECT_MENU_ENTRY_ATTACHMENTS'],

            'form_post' => $this->_sName . '_post',
            'form_attach_link' => $this->_sName . '_attach_link',
            'form_repost' => $this->_sName . '_repost',
            'form_display_post_add' => $this->_sName . '_post_add',
            'form_display_post_add_public' => $this->_sName . '_post_add_public',
            'form_display_post_add_profile' => $this->_sName . '_post_add_profile',
            'form_display_post_edit' => $this->_sName . '_post_edit',
            'form_display_post_view' => $this->_sName . '_post_view',
            'form_display_attach_link_add' => $this->_sName . '_attach_link_add',
            'form_display_repost_with' => $this->_sName . '_repost_with',
            'form_display_repost_to' => $this->_sName . '_repost_to',

            'grid_mute' => $this->CNF['OBJECT_GRID_MUTE'],

            'connection_mute' => $this->CNF['OBJECT_CONNECTIONS_MUTE'],
        ));

        $this->_aHandlerDescriptor = array('module_name' => '', 'module_method' => '', 'module_class' => '', 'groupable' => '', 'group_by' => '');
        $this->_sHandlersMethod = 'get_timeline_data';

        $this->_aPhotoUploaders = array($this->CNF['OBJECT_UPLOADER_PHOTO_SIMPLE']);
        $this->_aVideoUploaders = array($this->CNF['OBJECT_UPLOADER_VIDEO_SIMPLE']);
        $this->_aFilesUploaders = array($this->CNF['OBJECT_UPLOADER_FILE_SIMPLE']);

        $this->_aItemToUploader = array(
            'add-photo-simple' => $this->CNF['OBJECT_UPLOADER_PHOTO_SIMPLE'],
            'add-photo-html5' => $this->CNF['OBJECT_UPLOADER_PHOTO_HTML5'],
            'add-video-simple' => $this->CNF['OBJECT_UPLOADER_VIDEO_SIMPLE'],
            'add-video-html5' => $this->CNF['OBJECT_UPLOADER_VIDEO_HTML5'],
            'add-video-record' => $this->CNF['OBJECT_UPLOADER_VIDEO_RECORD'],
            'add-file-simple' => $this->CNF['OBJECT_UPLOADER_FILE_SIMPLE'],
            'add-file-html5' => $this->CNF['OBJECT_UPLOADER_FILE_HTML5'],
        );

        $this->_bJsMode = false;
        $this->_aJsClasses = array(
            'main' => 'BxTimelineMain',
            'view' => 'BxTimelineView',
            'post' => 'BxTimelinePost',
            'repost' => 'BxTimelineRepost',
            'manage_tools' => 'BxTimelineManageTools'
        );
        $this->_aJsObjects = array(
            'view' => 'oTimelineView',
            'post' => 'oTimelinePost',
            'repost' => 'oTimelineRepost',
            'manage_tools' => 'oBxTimelineManageTools'
        );

        $this->_aGridObjects = array(
            'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
            'common' => $this->CNF['OBJECT_GRID_COMMON']
        );

        $sHp = str_replace('_', '-', $this->_sName);
        $sHpT = BX_TIMELINE_VIEW_TIMELINE;
        $sHpO = BX_TIMELINE_VIEW_OUTLINE;
        $this->_aHtmlIds = array(
            'view' => array(
                'edit_form' => $sHp . '-edit-',
                'attach_link_form_field' => $sHp . '-attach-link-form-field-',

                'menu_popup' => $sHp . '-menu-popup-',

                'video_iframe' => $sHp . '-video-iframe-',
                'video' => $sHp . '-video-',

                'live_update_popup' => $sHp . '-live-update-popup-',
            ),
            'post' => array(
                'attach_link_popup' =>  $sHp . '-attach-link-popup',
                'attach_link_form_field' => $sHp . '-attach-link-form-field-',
                'attach_link_item' => $sHp . '-attach-link-item-',
                'textarea' => $sHp . '-textarea-',

                'main_' . $sHpT => $sHp . '-' . $sHpT,
                'main_' . $sHpO => $sHp . '-' . $sHpO,
            ),
            'repost' => array(
                'main' => $sHp . '-repost-',
                'counter' => $sHp . '-repost-counter-',
                'by_popup' => $sHp . '-repost-by-',
                'with_popup' => $sHp . '-repost-with',
                'to_popup' => $sHp . '-repost-to',
            )
        );

        $this->_aRepostDefaults = array(
            'do' => 'repost',

            'show_do_repost_as_button' => false,
            'show_do_repost_as_button_small' => false,
            'show_do_repost_icon' => true,
            'show_do_repost_text' => false,

            //--- Counter
            'show_counter_label_icon' => false,
            'show_counter_label_text' => true,
            'show_counter' => true,

            //--- JS script
            'show_script' => true,

            //--- Icons
            'icon_do_repost' => 'redo',
            'icon_do_repost_with' => 'redo',
            'icon_do_repost_to' => 'redo',

            //--- Texts
            'text_do_repost' => '_bx_timeline_txt_do_repost',
            'text_do_repost_with' => '_bx_timeline_txt_do_repost_with',
            'text_do_repost_to' => '_bx_timeline_txt_do_repost_to',

            //--- Templates
            'template_do_repost_label' => '',
            'template_do_repost_label_name' => 'repost_do_repost_label.html',
        );

        $this->_iTimelineVisibilityThreshold = 0;

        $this->_aPregPatterns = array(
            "meta_title" => "/<title>(.*)<\/title>/",
            "meta_description" => "/<meta[\s]+[^>]*?name[\s]?=[\s\"\']+description[\s\"\']+content[\s]?=[\s\"\']+(.*?)[\"\']+.*?>/",
            "url" => "/(([A-Za-z]{3,9}:(?:\/\/)?)(?:[\-;:&=\+\$,\w]+@)?[A-Za-z0-9\.\-]+|(?:www\.|[\-;:&=\+\$,\w]+@)[A-Za-z0-9\.\-]+)((?:\/[\+~%#\/\.\w\-_!\(\)]*)?\??(?:[\-\+=&;%@\.\w_]*)#?(?:[\.\!\/\\\w]*))?/"
        );

        $this->_aBriefCardsTags = array('a', 'b', 'i');

        $this->_sSessionKeyType = $this->_sName . '_type_';

        $this->_aHotSourcesList = [
            BX_TIMELINE_HFS_CONTENT, 
            BX_TIMELINE_HFS_COMMENT,
            BX_TIMELINE_HFS_VOTE
        ];
    }

    public function init(&$oDb)
    {
    	parent::init($oDb);

    	$sOptionPrefix = $this->getPrefix('option');
    	$this->_bAllowEdit = getParam($sOptionPrefix . 'enable_edit') == 'on';
        $this->_bAllowDelete = getParam($sOptionPrefix . 'enable_delete') == 'on';
        $this->_bShowAll = getParam($sOptionPrefix . 'enable_show_all') == 'on';
        $this->_bCountAllViews = getParam($sOptionPrefix . 'enable_count_all_views') == 'on';
        $this->_bRepostOwnActions = getParam($sOptionPrefix . 'enable_repost_own_actions') == 'on';
        $this->_bHideUponDelete = getParam($sOptionPrefix . 'enable_hide_upon_delete') == 'on';

        $this->_bCacheItem = getParam($sOptionPrefix . 'enable_cache_item') == 'on';
        $this->_sCacheItemEngine = getParam($sOptionPrefix . 'cache_item_engine');
        $this->_iCacheItemLifetime = (int)getParam($sOptionPrefix . 'cache_item_lifetime');

        $this->_aPerPage = array(
            'default' => (int)getParam($sOptionPrefix . 'events_per_page'),
            'profile' => (int)getParam($sOptionPrefix . 'events_per_page_profile'),
            'account' => (int)getParam($sOptionPrefix . 'events_per_page_account'),
            'home' => (int)getParam($sOptionPrefix . 'events_per_page_home'),
            'preload' => (int)getParam($sOptionPrefix . 'events_per_preload')
    	);

        $this->_bInfScroll = getParam($sOptionPrefix . 'enable_infinite_scroll') == 'on';
        $this->_iInfScrollAutoPreloads = (int)getParam($sOptionPrefix . 'auto_preloads');

        $this->_iRssLength = (int)getParam($sOptionPrefix . 'rss_length');       

        $this->_bBriefCards = getParam($sOptionPrefix . 'enable_brief_cards') == 'on';

        $this->_iLiveUpdateLength = 10;
        $this->_iCharsDisplayMinTitle = (int)getParam($sOptionPrefix . 'title_chars_short');
        $this->_iCharsDisplayMaxTitle = (int)getParam($sOptionPrefix . 'title_chars');

        $this->_sVideosPreload = getParam($sOptionPrefix . 'videos_preload');
        $this->_sVideosAutoplay = getParam($sOptionPrefix . 'videos_autoplay');

        $this->_iPreloadCommentsMax = 7;
        $this->_iPreloadComments = (int)getParam($sOptionPrefix . 'preload_comments');
        if($this->_iPreloadComments > $this->_iPreloadCommentsMax)
            $this->_iPreloadComments = $this->_iPreloadCommentsMax;

        $this->_bJumpTo = getParam($sOptionPrefix . 'enable_jump_to_switcher') == 'on';
        $this->_bSortByReaction = getParam($sOptionPrefix . 'enable_sort_by_reaction') == 'on';
        $this->_bSortByUnread = getParam($sOptionPrefix . 'enable_sort_by_unread') == 'on';
        $this->_sAttachmentsLayout = getParam($sOptionPrefix . 'attachments_layout');

        $this->_bHot = getParam($sOptionPrefix . 'enable_hot') == 'on';
        $this->_iHotThresholdContent = (int)getParam($sOptionPrefix . 'hot_threshold_age');
        $this->_iHotThresholdComment = (int)getParam($sOptionPrefix . 'hot_threshold_comment');
        $this->_iHotThresholdVote = (int)getParam($sOptionPrefix . 'hot_threshold_vote');
        $this->_iHotInterval = (int)getParam($sOptionPrefix . 'hot_interval');
        $this->_aHotSources = explode(',', getParam($sOptionPrefix . 'hot_sources'));
        $this->_aHotList = $this->_bHot ? $this->_oDb->getHot() : array();

        $this->_bEditorToolbar = getParam($sOptionPrefix . 'enable_editor_toolbar') == 'on';
        $this->_bEditorAutoAttach = getParam($sOptionPrefix . 'editor_auto_attach_insertion') == 'on';
        $this->_iLimitAttachLinks = (int)getParam($sOptionPrefix . 'limit_attach_links');

        $this->_bUnhideRestored = false;
    }

    /**
     * Generate name from parameters.
     * 
     * @param type $aParams - parameters whose values will be used during generation.
     * @param type $aRules - generation rules.
     * @return string
     */
    protected function getNameView($aParams, $aRules = array())
    {
        $bWithView = !isset($aRules['with_view']) || $aRules['with_view'] === true;
        $bWithType = !isset($aRules['with_type']) || $aRules['with_type'] === true;
        $bWithOwner = isset($aRules['with_owner']) && $aRules['with_owner'] === true;
        $sGlue = !empty($aRules['glue']) ? $aRules['glue'] : '_';

        $aAddons = array();
        if(!empty($aParams['name']))
            $aAddons[] = bx_process_output($aParams['name']);
        else {
            if($bWithView && !empty($aParams['view']))
                $aAddons[] = bx_process_output($aParams['view']);

            if($bWithType && !empty($aParams['type']))
                $aAddons[] = bx_process_output($aParams['type']);
        }

        if($bWithOwner)
            $aAddons[] = (int)$aParams['owner_id'];

        return !empty($aAddons) ? implode($sGlue, $aAddons) : '';
    }

    /**
     * Generates unique JS object name for View events block.
     * 
     * @param array $aParams - an array with browsing params received in View block service method
     * @return string with JS object name
     */
    public function getJsObjectView($aParams = array())
    {
        return parent::getJsObject('view') . bx_gen_method_name($this->getNameView($aParams));
    }

    public function getHtmlIdView($sKey, $aParams, $aRules = array())
    {
        $bWhole = !isset($aRules['whole']) || $aRules['whole'] === true;
        $sGlue = !empty($aRules['glue']) ? $aRules['glue'] : '_';

        return str_replace($sGlue, '-', $this->_sName  . $sGlue . $sKey . $sGlue . $this->getNameView($aParams, $aRules) . (!$bWhole ? $sGlue : ''));
    }

    public function isAllowEdit()
    {
        return $this->_bAllowEdit;
    }

    public function isAllowDelete()
    {
        return $this->_bAllowDelete;
    }

    public function isInfiniteScroll()
    {
        return $this->_bInfScroll;
    }

    public function isShowAll()
    {
        return $this->_bShowAll;
    }

    public function isCountAllViews()
    {
        return $this->_bCountAllViews;
    }

    public function isRepostOwnActions()
    {
        return $this->_bRepostOwnActions;
    }

    public function isHideUponDelete()
    {
        return $this->_bHideUponDelete;
    }

    public function isBriefCards()
    {
        return $this->_bBriefCards;
    }

    public function isJumpTo()
    {
        return $this->_bJumpTo;
    }

    public function isSortByReaction()
    {
        return $this->_bSortByReaction;
    }

    public function isSortByUnread()
    {
        return $this->_bSortByUnread;
    }

    public function isHot()
    {
        return $this->_bHot;
    }

    public function getHotSourcesList()
    {
        return $this->_aHotSourcesList;
    }

    public function getHotSources()
    {
        return $this->_aHotSources;
    }

    public function isHotSource($sName)
    {
        return in_array($sName, $this->_aHotSources);
    }

    public function getHotThreshold($sSource)
    {
        if(!in_array($sSource, [BX_TIMELINE_HFS_CONTENT, BX_TIMELINE_HFS_COMMENT, BX_TIMELINE_HFS_VOTE]))
            return false;

        return $this->{'_iHotThreshold' . ucfirst($sSource)};
    }

    public function isHotEvent($iEventId)
    {
        return in_array($iEventId, $this->_aHotList);
    }

    public function isEmoji()
    {
        $oMenu = BxDolMenu::getObjectInstance($this->getObject('menu_post_attachments'));
        if(!$oMenu)
            return false;

        return $oMenu->isMenuItem('add-emoji');
    }

    public function isEditorToolbar()
    {
    	return $this->_bEditorToolbar;
    }

    public function isEditorAutoAttach()
    {
    	return $this->_bEditorAutoAttach;
    }

    public function isUnhideRestored()
    {
        return $this->_bUnhideRestored;
    }

    public function isCacheItem()
    {
        return $this->_bCacheItem;
    }

    public function getCacheItemEngine()
    {
        return $this->_sCacheItemEngine;
    }

    public function getCacheItemLifetime()
    {
        return $this->_iCacheItemLifetime;
    }

    public function getCacheItemKey($iId, $sPostfix = '')
    {
        return $this->getPrefix('cache_item') . $iId . (bx_is_mobile() ? '_m' : '') . '_r' . bx_get_device_pixel_ratio() . '_' . (!empty($sPostfix) ? '_' . $sPostfix : '') . '.php';
    }

    public function getCacheHotKey()
    {
        return $this->getPrefix('cache_list_hot');
    }

    public function getPostFormDisplay($sType)
    {
        if(empty($sType) || !array_key_exists($sType, $this->_aTypeToFormDisplay))
            $sType = BX_TIMELINE_TYPE_DEFAULT;

        return $this->_aTypeToFormDisplay[$sType];
    }

    public function getUploaders($sField)
    {
        $aResult = array();

        switch($sField) {
            case 'photo':
                $aResult = $this->_aPhotoUploaders;
                break;
            case 'video':
                $aResult = $this->_aVideoUploaders;
                break;
            case 'file':
                $aResult = $this->_aFilesUploaders;
                break;
        }

        return $aResult;
    }

    public function getUploaderByMenuItem($sMenuItem)
    {
        if(!isset($this->_aItemToUploader[$sMenuItem]))
            return false;
    
        return $this->_aItemToUploader[$sMenuItem];
    }

    public function getPerPage($sType = 'default')
    {
        if($this->isInfiniteScroll())
            $sType = 'preload';

        return parent::getPerPage($sType);
    }

    public function getExtenalsEvery($sType = 'default')
    {
        if($this->isInfiniteScroll())
            $sType .= '_preload';

        $iExtenalsEvery = (int)getParam($this->getPrefix('option') . 'extenals_every_' . $sType);
        if(!$iExtenalsEvery)
            return $iExtenalsEvery;

        $iPerPage = $this->getPerPage($sType);
        if(empty($iPerPage))
            $iPerPage = $this->getPerPage();

        if($iExtenalsEvery > $iPerPage)
            $iExtenalsEvery = $iPerPage;

        return $iExtenalsEvery;
    }

    public function getAutoPreloads()
    {
        return $this->_iInfScrollAutoPreloads;
    }

    public function getRssLength()
    {
        return $this->_iRssLength;
    }

    public function getLiveUpdateLength()
    {
        return $this->_iLiveUpdateLength;
    }

    public function getCharsDisplayMinTitle()
    {
        return $this->_iCharsDisplayMinTitle;
    }

    public function getCharsDisplayMaxTitle()
    {
        return $this->_iCharsDisplayMaxTitle;
    }

    public function getBriefCardsTags($bAsString = false)
    {
        return !$bAsString ? $this->_aBriefCardsTags : '<' . implode('><', $this->_aBriefCardsTags) . '>';
    }

    public function getVideosPreload()
    {
        return $this->_sVideosPreload;
    }

    public function getVideosAutoplay()
    {
        return $this->_sVideosAutoplay;
    }

    public function getPreloadComments()
    {
        return $this->_iPreloadComments;
    }

    public function getAttachmentsLayout()
    {
        return $this->_sAttachmentsLayout;
    }

    public function getHotInterval()
    {
        return $this->_iHotInterval;
    }

    public function getLimitAttachLinks()
    {
        return $this->_iLimitAttachLinks;
    }

    public function getRepostDefaults()
    {
        return $this->_aRepostDefaults;
    }

    public function getPregPattern($sType)
    {
        return $this->_aPregPatterns[$sType];
    }

    /**
     * Ancillary functions
     */
    public function getTitle($s, $mixedProfile = false, $sMethodLength = 'getCharsDisplayMaxTitle')
    {
        if(get_mb_substr($s, 0, 1) == '_' && strcmp($s, _t($s)) != 0)
            return $s;

        if($mixedProfile !== false) {
            if(is_numeric($mixedProfile))
                $mixedProfile = BxDolProfile::getInstanceMagic((int)$mixedProfile);

            if($mixedProfile instanceof BxDolProfile)
                $s = bx_replace_markers($s, array(
                    'profile_name' => $mixedProfile->getDisplayName()
                ));
        }

        if(!method_exists($this, $sMethodLength))
            $sMethodLength = 'getCharsDisplayMaxTitle';

        return strmaxtextlen($s, $this->$sMethodLength(), '...');
    }

    public function getTitleShort($s, $mixedProfile = false)
    {
        return $this->getTitle($s, $mixedProfile, 'getCharsDisplayMinTitle');
    }

    public function getTitleDefault($bL, $bP, $bV, $bF)
    {
        $sResult = '';

        if($bL && !$bP && !$bV && !$bF)
            $sResult = 'link';
        else if(!$bL && $bP && !$bV && !$bF)
            $sResult = 'image';
        else if(!$bL && !$bP && $bV && !$bF)
            $sResult = 'video';
        else if(!$bL && !$bP && !$bV && $bF)
            $sResult = 'file';
        else 
            $sResult = 'media';

        return $this->CNF['T']['txt_sample_with_' . $sResult];
    }

    public function getViewUrl($iOwnerId, $bAbsolute = true)
    {
        $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->CNF['URI_VIEW_LIST'], ['id' => $iOwnerId]);
        return $bAbsolute ? bx_absolute_url($sUrl) : $sUrl;
    }

    public function getHomeViewUrl($bAbsolute = true)
    {
        $sUrl = BxDolPermalinks::getInstance()->permalink($this->CNF['URL_HOME']);
        return $bAbsolute ? bx_absolute_url($sUrl) : $sUrl;
    }

    public function getItemViewUrl($aEvent, $bAbsolute = true)
    {
        $sUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->CNF['URI_VIEW_ENTRY'], ['id' => $aEvent['id']]);
        return $bAbsolute ? bx_absolute_url($sUrl) : $sUrl;
    }

    public function getLiveUpdateKey($aParams)
    {
        return $this->getName() . '_live_update_' . $this->getNameView($aParams, array('with_owner' => true));
    }

    public function isCommon($sType, $sAction)
    {
        return !$this->isSystem($sType, $sAction);
    }

    public function isSystem($sType, $sAction)
    {
        $sPrefix = $this->getPrefix('common_post');
        return strpos($sType, $sPrefix) === false && !empty($sAction);
    }

    public function getSystemData(&$aEvent, $aBrowseParams = array())
    {
        $aHandler = $this->getHandler($aEvent);
        if($aHandler === false)
            return false;

        return BxDolService::call($aHandler['module_name'], $aHandler['module_method'], array($aEvent, $aBrowseParams), $aHandler['module_class']);
    }

    public function getSystemDataByDescriptor($sType, $sAction, $iObjectId)
    {
    	$aDescriptor = array(
            'type' => $sType, 
            'action' => $sAction,
            'object_id' => $iObjectId
    	);
    	return $this->getSystemData($aDescriptor);
    }

    public function isEqualUrls($sUrl1, $sUrl2)
    {
        $sUrl1 = trim($sUrl1, "/");
        $sUrl2 = trim($sUrl2, "/");

        return strncmp($sUrl1, $sUrl2, strlen($sUrl1)) === 0;
    }

    public function addBrowseParams($sUrl, $aParams, $sKey = 'bp')
    {
        return bx_append_url_params($sUrl, [$sKey => base64_encode(json_encode($aParams))]);
    }

    public function getBrowseParams($sValue)
    {
        return json_decode(base64_decode(urldecode($sValue)), true);
    }

    public function setUserChoice($aChoices = array())
    {
        if(!isLogged() || empty($aChoices))
            return;

        $iUserId = bx_get_logged_profile_id();

        $oSession = BxDolSession::getInstance();
        foreach($aChoices as $sKey => $mixedValue) {
            $sField = '_sSessionKey' . bx_gen_method_name($sKey);
            if(isset($this->$sField) && !empty($mixedValue))
                $oSession->setValue($this->$sField . $iUserId, $mixedValue);
        }
    }

    public function getUserChoice($sKey, $iUserId = 0)
    {
        $sField = '_sSessionKey' . bx_gen_method_name($sKey);
        if(!isLogged() || !isset($this->$sField))
            return false;

        if(!$iUserId)
            $iUserId = bx_get_logged_profile_id();

        return BxDolSession::getInstance()->getValue($this->$sField . $iUserId);
    }

    public function prepareParam($sName, $sPattern = "/^[\d\w_]+$/")
    {
        return $this->processParam(bx_get($sName), $sPattern);
    }

    public function prepareParamWithDefault($sName, $sDefault, $sPattern = "/^[\d\w_]+$/")
    {
        return ($sValue = $this->prepareParam($sName, $sPattern)) != '' ? $sValue : $sDefault;
    }

    public function processParam($sValue, $sPattern = "/^[\d\w_]+$/")
    {
        return bx_process_url_param($sValue, $sPattern);
    }

    public function processParamWithDefault($sValue, $sDefault, $sPattern = "/^[\d\w_]+$/")
    {
        return ($sValue = $this->processParam($sValue, $sPattern)) != '' ? $sValue : $sDefault;
    }
}

/** @} */
