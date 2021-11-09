<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

class BxStrmConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'approve' => 'checkAllowedApprove',
            'edit-stream' => 'checkAllowedEdit',
            'delete-stream' => 'checkAllowedDelete',
        );

        $this->CNF = array_merge($this->CNF, array (

            // module icon
            'ICON' => 'rss col-red3',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'streams',
            'TABLE_ENTRIES_FULLTEXT' => 'title_text',
            'TABLE_POLLS' => '',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_PUBLISHED' => 'published',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'stream-text',
            'FIELD_CATEGORY' => 'cat',
            'FIELD_MULTICAT' => 'multicat',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_COVER' => 'covers',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_VIDEO' => 'videos',
            'FIELD_SOUND' => 'sounds',
            'FIELD_FILE' => 'files',
            'FIELD_POLL' => 'polls',
            'FIELD_THUMB' => 'thumb',
            'FIELD_ATTACHMENTS' => 'attachments',
            'FIELD_VIEWS' => 'views',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_STATUS' => 'status',
            'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LOCATION' => 'location',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELD_LABELS' => 'labels',
            'FIELD_ANONYMOUS' => 'anonymous',
            'FIELD_ALLOW_COMMENTS' => 'allow_comments',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified
            'FIELD_KEY' => 'key',
            
            'PARAM_POLL_ENABLED' => false,

            // page URIs
            'URI_VIEW_ENTRY' => 'view-stream',
            'URI_AUTHOR_ENTRIES' => '',
            'URI_ENTRIES_BY_CONTEXT' => '',
            'URI_ADD_ENTRY' => 'create-stream',
            'URI_EDIT_ENTRY' => 'edit-stream',
            'URI_MANAGE_COMMON' => 'streams-manage',
            'URI_FAVORITES_LIST' => '',

            'URL_HOME' => '',
            'URL_POPULAR' => '',
            'URL_TOP' => '',
            'URL_UPDATED' => '',
            'URL_MANAGE_COMMON' => 'page.php?i=streams-manage',
            'URL_MANAGE_ADMINISTRATION' => 'page.php?i=streams-administration',

            // some params
            'PARAM_AUTO_APPROVE' => 'bx_stream_enable_auto_approve',
            'PARAM_CHARS_SUMMARY' => 'bx_stream_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_stream_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_stream_rss_num',
            'PARAM_SEARCHABLE_FIELDS' => 'bx_stream_searchable_fields',
            'PARAM_PER_PAGE_BROWSE_SHOWCASE' => 'bx_stream_per_page_browse_showcase',
            'PARAM_PER_PAGE_FOR_FAVORITES_LISTS' => 'bx_stream_per_page_for_favorites_lists',

            'PARAM_MULTICAT_ENABLED' => true,
            'PARAM_MULTICAT_AUTO_ACTIVATION_FOR_CATEGORIES' => 'bx_stream_auto_activation_for_categories',

            // objects
            'OBJECT_STORAGE' => 'bx_stream_covers',
            'OBJECT_STORAGE_FILES' => '',
            'OBJECT_STORAGE_PHOTOS' => '',
            'OBJECT_STORAGE_VIDEOS' => '',
            'OBJECT_STORAGE_SOUNDS' => '',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_stream_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_stream_gallery',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_stream_cover',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW_FILES' => '',
            'OBJECT_IMAGES_TRANSCODER_GALLERY_FILES' => '',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW_PHOTOS' => '',
            'OBJECT_IMAGES_TRANSCODER_GALLERY_PHOTOS' => '',
            'OBJECT_SOUNDS_TRANSCODER' => '',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
            'OBJECT_VIDEO_TRANSCODER_HEIGHT' => '',
            'OBJECT_REPORTS' => 'bx_stream',
            'OBJECT_VIEWS' => 'bx_stream',
            'OBJECT_VOTES' => 'bx_stream',
            'OBJECT_REACTIONS' => 'bx_stream_reactions',
            'OBJECT_SCORES' => 'bx_stream',
            'OBJECT_FEATURED' => 'bx_stream',
            'OBJECT_METATAGS' => 'bx_stream',
            'OBJECT_COMMENTS' => 'bx_stream',
            'OBJECT_NOTES' => 'bx_stream_notes',
            'OBJECT_CATEGORY' => 'bx_stream_cats',
            'OBJECT_PRIVACY_VIEW' => 'bx_stream_allow_view_to',
            'OBJECT_PRIVACY_LIST_VIEW' => 'bx_stream_allow_view_favorite_list',
            'OBJECT_FORM_ENTRY' => 'bx_stream',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_stream_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_stream_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_stream_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_stream_entry_delete',
            'OBJECT_FORM_POLL' => '',
            'OBJECT_FORM_POLL_DISPLAY_ADD' => '',
            'OBJECT_MENU_ENTRY_ATTACHMENTS' => '', // attachments menu in create/edit forms
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_stream_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL' => 'bx_stream_view_actions', // all actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_stream_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => '', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => '', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => '', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_SNIPPET_META' => 'bx_stream_snippet_meta', // menu for snippet meta info
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_stream_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_stream_administration',
            'OBJECT_GRID_COMMON' => 'bx_stream_common',
            'OBJECT_GRID_CATEGORIES' => 'bx_stream_categories',
            'OBJECT_UPLOADERS' => array('bx_stream_simple', 'bx_stream_html5'),
            'OBJECT_CONTENT_INFO' => 'bx_stream',
            'OBJECT_CMTS_CONTENT_INFO' => 'bx_stream_cmts',
            
            'BADGES_AVALIABLE' => true,

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_stream_my' => array (
                    'create-stream' => 'checkAllowedAdd',
                ),
                'bx_stream_view' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'approving' => array (
                    'name' => 'bx-stream-approving',
                    'map' => array (
                        'pending' => array('msg' => '_bx_stream_txt_msg_status_pending', 'type' => BX_INFORMER_ALERT),
                        'hidden' => array('msg' => '_bx_stream_txt_msg_status_hidden', 'type' => BX_INFORMER_ERROR),
                    ),
                ),
                'processing' => array (
                    'name' => 'bx-stream-processing',
                    'map' => array (
                        'awaiting' => array('msg' => '_bx_stream_txt_processing_awaiting', 'type' => BX_INFORMER_ALERT),
                        'failed' => array('msg' => '_bx_stream_txt_processing_failed', 'type' => BX_INFORMER_ERROR)
                    ),
                ),
                'scheduled' => array (
                    'name' => 'bx-stream-scheduled',
                    'map' => array (
                        'awaiting' => array('msg' => '_bx_stream_txt_scheduled_awaiting', 'type' => BX_INFORMER_ALERT),
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_stream_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_stream_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_stream_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_stream_txt_sample_vote_single',
                'txt_sample_reaction_single' => '_bx_stream_txt_sample_reaction_single',
                'txt_sample_score_up_single' => '_bx_stream_txt_sample_score_up_single',
                'txt_sample_score_down_single' => '_bx_stream_txt_sample_score_down_single',
                'form_field_author' => '_bx_stream_form_entry_input_author',
            	'grid_action_err_delete' => '_bx_stream_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_stream_grid_txt_account_manager',
                'filter_item_active' => '_bx_stream_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_stream_grid_filter_item_title_adm_hidden',
                'filter_item_pending' => '_bx_stream_grid_filter_item_title_adm_pending',
            	'filter_item_select_one_filter1' => '_bx_stream_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_stream_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_stream_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_stream_txt_all_entries_by',
                'txt_all_entries_in' => '_bx_stream_txt_all_entries_in',
                'txt_all_entries_by_author' => '_bx_stream_page_title_browse_by_author',
                'txt_all_entries_by_context' => '_bx_stream_page_title_browse_by_context',
                'txt_err_cannot_perform_action' => '_bx_stream_txt_err_cannot_perform_action',
                'txt_poll_form_answers_add' => '_bx_stream_form_poll_input_answers_add',
                'txt_poll_menu_view_answers' => '_bx_stream_txt_poll_view_answers',
                'txt_poll_menu_view_results' => '_bx_stream_txt_poll_view_results',
                'txt_poll_menu_view_' => '_bx_stream_txt_poll_view_',
                'txt_poll_answer_vote_do_by' => '_bx_stream_txt_poll_answer_vote_do_by',
                'txt_poll_answer_vote_counter' => '_bx_stream_txt_poll_answer_vote_counter',
                'txt_poll_answer_vote_percent' => '_bx_stream_txt_poll_answer_vote_percent'
            ),
        ));
        
        $this->_aJsClasses = array_merge($this->_aJsClasses, array(
            'manage_tools' => 'BxStrmManageTools',
            'categories' => 'BxDolCategories'
        ));

        $this->_aJsObjects = array_merge($this->_aJsObjects, array(
            'manage_tools' => 'oBxStrmManageTools',
             'categories' => 'oBxDolCategories'
        ));

        $this->_aGridObjects = array(
            'common' => $this->CNF['OBJECT_GRID_COMMON'],
            'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        );

        $this->_bAttachmentsInTimeline = true;
    }
}

/** @} */
