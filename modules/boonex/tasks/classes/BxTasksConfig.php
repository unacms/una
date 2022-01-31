<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT 
 * @defgroup    Tasks Tasks
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolInformer');

class BxTasksConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'approve' => 'checkAllowedApprove',
            'edit-task' => 'checkAllowedEdit',
            'delete-task' => 'checkAllowedDelete',
        );

        $this->CNF = array_merge($this->CNF, array (

            // module icon
            'ICON' => 'tasks col-red3',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'tasks',
            'TABLE_LISTS' => $aModule['db_prefix'] . 'lists',
            'TABLE_POLLS' => '',
            'TABLE_ENTRIES_FULLTEXT' => 'title_text',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_PUBLISHED' => 'published',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'post-text',
            'FIELD_CATEGORY' => 'cat',
            'FIELD_MULTICAT' => 'multicat',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_CF' => 'cf',
            'FIELD_COVER' => 'covers',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_VIDEO' => 'videos',
            'FIELD_FILE' => 'files',
            'FIELD_THUMB' => 'thumb',
            'FIELD_ATTACHMENTS' => 'attachments',
            'FIELD_VIEWS' => 'views',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_STATUS' => 'status',
            'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LABELS' => 'labels',
            'FIELD_TASKLIST' => 'tasks_list',
            'FIELD_DUEDATE' => 'due_date',
            'FIELD_EXPIRED' => 'expired',
            'FIELD_COMPLETED' => 'completed',
            'FIELD_ANONYMOUS' => 'anonymous',
            'FIELD_ALLOW_COMMENTS' => 'allow_comments',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified
            'FIELDS_DELAYED_PROCESSING' => 'videos', // can be array of fields or comma separated string of field names

             // some params
            'PARAM_MULTICAT_ENABLED' => true,
            'PARAM_MULTICAT_AUTO_ACTIVATION_FOR_CATEGORIES' => 'bx_tasks_auto_activation_for_categories',
            'PARAM_POLL_ENABLED' => false,

            // page URIs
            'URI_VIEW_ENTRY' => 'view-task',
            'URI_AUTHOR_ENTRIES' => 'tasks-author',
            'URI_ENTRIES_BY_CONTEXT' => 'tasks-context',
            'URI_ADD_ENTRY' => 'create-task',
            'URI_EDIT_ENTRY' => 'edit-task',
            'URI_MANAGE_COMMON' => 'tasks-manage',

            'URL_HOME' => 'page.php?i=tasks-home',
            'URL_POPULAR' => 'page.php?i=tasks-popular',
            'URL_TOP' => 'page.php?i=tasks-top',
            'URL_UPDATED' => 'page.php?i=tasks-updated',
            'URL_MANAGE_COMMON' => 'page.php?i=tasks-manage',
            'URL_MANAGE_ADMINISTRATION' => 'page.php?i=tasks-administration',

            // some params
            'PARAM_AUTO_APPROVE' => 'bx_tasks_enable_auto_approve',
            'PARAM_CHARS_SUMMARY' => 'bx_tasks_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_tasks_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_tasks_rss_num',
            'PARAM_SEARCHABLE_FIELDS' => 'bx_tasks_searchable_fields',
            'PARAM_PER_PAGE_BROWSE_SHOWCASE' => 'bx_tasks_per_page_browse_showcase',

            // objects
            'OBJECT_STORAGE' => 'bx_tasks_covers',
            'OBJECT_STORAGE_FILES' => 'bx_tasks_files',
            'OBJECT_STORAGE_PHOTOS' => 'bx_tasks_photos',
            'OBJECT_STORAGE_VIDEOS' => 'bx_tasks_videos',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_tasks_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_tasks_gallery',
            'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_tasks_cover',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW_FILES' => 'bx_tasks_preview_files',
            'OBJECT_IMAGES_TRANSCODER_GALLERY_FILES' => 'bx_tasks_gallery_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW_PHOTOS' => 'bx_tasks_preview_photos',
            'OBJECT_IMAGES_TRANSCODER_GALLERY_PHOTOS' => 'bx_tasks_gallery_photos',
            'OBJECT_VIDEOS_TRANSCODERS' => array(
                'poster' => 'bx_tasks_videos_poster', 
            	'poster_preview' => 'bx_tasks_videos_poster_preview',
            	'mp4' => 'bx_tasks_videos_mp4', 
            	'mp4_hd' => 'bx_tasks_videos_mp4_hd'
            ),
            'OBJECT_VIDEO_TRANSCODER_HEIGHT' => '480px',
            'OBJECT_REPORTS' => 'bx_tasks',
            'OBJECT_VIEWS' => 'bx_tasks',
            'OBJECT_VOTES' => 'bx_tasks',
            'OBJECT_REACTIONS' => 'bx_tasks_reactions',
            'OBJECT_SCORES' => 'bx_tasks',
            'OBJECT_FAVORITES' => 'bx_tasks',
            'OBJECT_FEATURED' => 'bx_tasks',
            'OBJECT_COMMENTS' => 'bx_tasks',
            'OBJECT_NOTES' => 'bx_tasks_notes',
            'OBJECT_CATEGORY' => 'bx_tasks_cats',
			'OBJECT_CONNECTION' => 'bx_tasks_assignments',
            'OBJECT_PRIVACY_VIEW' => 'bx_tasks_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_tasks',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_tasks_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_tasks_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_tasks_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_tasks_entry_delete',
			
            'OBJECT_FORM_LIST_ENTRY' => 'bx_tasks_list',
            'OBJECT_FORM_LIST_ENTRY_DISPLAY_ADD' => 'bx_tasks_list_entry_add',
            'OBJECT_FORM_LIST_ENTRY_DISPLAY_EDIT' => 'bx_tasks_list_entry_edit',
			
            'OBJECT_MENU_ENTRY_ATTACHMENTS' => 'bx_tasks_entry_attachments', // attachments menu in create/edit forms
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_tasks_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL' => 'bx_tasks_view_actions', // all actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_tasks_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_tasks_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'tasks-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_SNIPPET_META' => 'bx_tasks_snippet_meta', // menu for snippet meta info
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_tasks_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_tasks_administration',
            'OBJECT_GRID_COMMON' => 'bx_tasks_common',
            'OBJECT_GRID_CATEGORIES' => 'bx_tasks_categories',
            'OBJECT_UPLOADERS' => array('bx_tasks_simple', 'bx_tasks_html5'),
            'OBJECT_CONTENT_INFO' => 'bx_tasks',
            'OBJECT_CMTS_CONTENT_INFO' => 'bx_tasks_cmts',
            
            'BADGES_AVALIABLE' => true,
			'COOKIE_SETTING_KEY' => 'bx_tasks_filters',

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_tasks_my' => array (
                    'create-task' => 'checkAllowedAdd',
                ),
                'bx_tasks_view' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'approving' => array (
                    'name' => 'bx-tasks-approving',
                    'map' => array (
                        'pending' => array('msg' => '_bx_tasks_txt_msg_status_pending', 'type' => BX_INFORMER_ALERT),
                        'hidden' => array('msg' => '_bx_tasks_txt_msg_status_hidden', 'type' => BX_INFORMER_ERROR),
                    ),
                ),
                'processing' => array (
                    'name' => 'bx-tasks-processing',
                    'map' => array (
                        'awaiting' => array('msg' => '_bx_tasks_txt_processing_awaiting', 'type' => BX_INFORMER_ALERT),
                        'failed' => array('msg' => '_bx_tasks_txt_processing_failed', 'type' => BX_INFORMER_ERROR)
                    ),
                ),
                'scheduled' => array (
                    'name' => 'bx-tasks-scheduled',
                    'map' => array (
                        'awaiting' => array('msg' => '_bx_tasks_txt_scheduled_awaiting', 'type' => BX_INFORMER_ALERT),
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_tasks_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_tasks_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_tasks_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_tasks_txt_sample_vote_single',
                'txt_sample_reaction_single' => '_bx_tasks_txt_sample_reaction_single',
                'txt_sample_score_up_single' => '_bx_tasks_txt_sample_score_up_single',
                'txt_sample_score_down_single' => '_bx_tasks_txt_sample_score_down_single',
                'form_field_author' => '_bx_tasks_form_entry_input_author',
            	'grid_action_err_delete' => '_bx_tasks_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_tasks_grid_txt_account_manager',
                'filter_item_active' => '_bx_tasks_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_tasks_grid_filter_item_title_adm_hidden',
            	'filter_item_select_one_filter1' => '_bx_tasks_grid_filter_item_title_adm_select_one_filter1',
                'filter_item_select_one_filter2' => '_bx_tasks_grid_filter_item_title_adm_select_one_filter2',
            	'menu_item_manage_my' => '_bx_tasks_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_tasks_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_tasks_txt_all_entries_by',
                'txt_all_entries_in' => '_bx_tasks_txt_all_entries_in',
                'txt_all_entries_by_author' => '_bx_tasks_page_title_browse_by_author',
                'txt_all_entries_by_context' => '_bx_tasks_page_title_browse_by_context',
                'txt_err_cannot_perform_action' => '_bx_tasks_txt_err_cannot_perform_action',
            ),
        ));
        
        $this->_aJsClasses = array_merge($this->_aJsClasses, array(
            'manage_tools' => 'BxTasksManageTools',
            'categories' => 'BxDolCategories',
			'tasks' => 'BxTasksView'
        ));

        $this->_aJsObjects = array_merge($this->_aJsObjects, array(
            'manage_tools' => 'oBxTasksManageTools',
            'categories' => 'oBxDolCategories',
			'tasks' => 'oBxTasksView'
        ));

        $this->_aGridObjects = array(
            'common' => $this->CNF['OBJECT_GRID_COMMON'],
            'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        );

        $this->_bAttachmentsInTimeline = true;
    }
}

/** @} */
