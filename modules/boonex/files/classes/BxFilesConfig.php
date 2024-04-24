<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Files Files
 * @ingroup     UnaModules
 *
 * @{
 */

class BxFilesConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'approve' => 'checkAllowedApprove',
            'download-file' => 'checkAllowedView',
            'edit-file' => 'checkAllowedEdit',
            'delete-file' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'far file col-red3',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'main',
            'TABLE_ENTRIES_FULLTEXT' => 'search_fields',
            'TABLE_FILES' => $aModule['db_prefix'] . 'files',
            'TABLE_BOOKMARKS' => $aModule['db_prefix'] . 'bookmarks',
            'TABLE_FOLDERS' => $aModule['db_prefix'] . 'folders',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_FILE_ID' => 'file_id',
            'FIELD_FOR_STORING_FILE_ID' => 'file_id',
            'FIELD_TITLE' => 'title',
            'FIELD_CATEGORY' => 'cat',
            'FIELD_TEXT' => 'desc',
            'FIELD_TEXT_ID' => 'file-desc',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_CF' => 'cf',
            'FIELD_PHOTO' => 'attachments',
            'FIELD_VIEWS' => 'views',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_STATUS' => 'status',
            'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LOCATION' => 'location',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELD_LABELS' => 'labels',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified
            'FIELD_BOOKMARKS_ID' => 'object_id',
            'FIELD_BOOKMARKS_PROFILE' => 'profile_id',
            'FIELD_MIME_TYPE' => 'mime_type',
			
            // some params
            'PARAM_PER_PAGE_FOR_FAVORITES_LISTS' => 'bx_files_per_page_for_favorites_lists',
            
            // page URIs
            'URI_VIEW_ENTRY' => 'view-file',
            'URI_AUTHOR_ENTRIES' => 'files-author',
            'URI_ENTRIES_BY_CONTEXT' => 'files-context',
            'URI_ADD_ENTRY' => 'create-file',
            'URI_EDIT_ENTRY' => 'edit-file',
            'URI_MANAGE_COMMON' => 'files-manage',
            'URI_FAVORITES_LIST' => 'files-favorites',

            'URL_HOME' => 'page.php?i=files-home',
            'URL_POPULAR' => 'page.php?i=files-popular',
            'URL_TOP' => 'page.php?i=files-top',
            'URL_UPDATED' => 'page.php?i=files-updated',
            'URL_MANAGE_COMMON' => 'page.php?i=files-manage',
            'URL_MANAGE_ADMINISTRATION' => 'page.php?i=files-administration',

            // some params
            'PARAM_AUTO_APPROVE' => 'bx_files_enable_auto_approve',
            'PARAM_CHARS_SUMMARY' => 'bx_files_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => '',
            'PARAM_NUM_RSS' => 'bx_files_rss_num',
            'PARAM_SEARCHABLE_FIELDS' => 'bx_files_searchable_fields',
            'PARAM_LINK_TO_PREVIEW' => 'bx_files_show_link_to_preview',
            'PARAM_MAX_NESTING_LEVEL' => 'bx_files_max_nesting_level',
            'PARAM_MAX_BULK_DOWNLOAD_SIZE' => 'bx_files_max_bulk_download_size',
            'PARAM_ALLOWED_EXT' => 'bx_files_allowed_ext',
            'PARAM_DEFAULT_LAYOUT_MODE' => 'bx_files_default_layout_mode',

            // objects
            'OBJECT_STORAGE' => 'bx_files_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_files_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_files_gallery',
            'OBJECT_SOUNDS_TRANSCODER' => 'bx_files_sounds_mp3',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
            'OBJECT_REPORTS' => 'bx_files',
            'OBJECT_VIEWS' => 'bx_files',
            'OBJECT_VOTES' => 'bx_files',
            'OBJECT_REACTIONS' => 'bx_files_reactions',
            'OBJECT_SCORES' => 'bx_files',
            'OBJECT_FAVORITES' => 'bx_files',
            'OBJECT_FEATURED' => 'bx_files',
            'OBJECT_METATAGS' => 'bx_files',
            'OBJECT_COMMENTS' => 'bx_files',
            'OBJECT_NOTES' => 'bx_files_notes',
            'OBJECT_CATEGORY' => 'bx_files_cats',
            'OBJECT_PRIVACY_VIEW' => 'bx_files_allow_view_to',
            'OBJECT_PRIVACY_LIST_VIEW' => 'bx_files_allow_view_favorite_list',
            'OBJECT_FORM_ENTRY_UPLOAD' => 'bx_files_upload',
            'OBJECT_FORM_ENTRY_DISPLAY_UPLOAD' => 'bx_files_entry_upload',
            'OBJECT_FORM_ENTRY' => 'bx_files',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_files_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_files_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_files_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_files_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_files_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_ALL' => 'bx_files_view_actions', // all actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_files_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_files_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_files_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'files-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_SNIPPET_META' => 'bx_files_snippet_meta', // menu for snippet meta info
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_files_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_files_administration',
            'OBJECT_GRID_COMMON' => 'bx_files_common',
            'OBJECT_UPLOADERS' => array('sys_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_files_my' => array (
                    'create-file' => 'checkAllowedAdd',
                ),
                'bx_files_view' => $aMenuItems2Methods,
            ),

            // informer messages
            'INFORMERS' => array (
                'approving' => array (
                    'name' => 'bx-files-approving',
                    'map' => array (
                        'pending' => array('msg' => '_bx_files_txt_msg_status_pending', 'type' => BX_INFORMER_ALERT),
                        'hidden' => array('msg' => '_bx_files_txt_msg_status_hidden', 'type' => BX_INFORMER_ERROR),
                    ),
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_files_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_files_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_files_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_files_txt_sample_vote_single',
                'txt_sample_reaction_single' => '_bx_files_txt_sample_reaction_single',
                'txt_sample_score_up_single' => '_bx_files_txt_sample_score_up_single',
                'txt_sample_score_down_single' => '_bx_files_txt_sample_score_down_single',
             	'form_field_author' => '_bx_files_form_entry_input_author',
                'form_entry_upload_single_for_update' => '_bx_files_form_entry_input_pictures_upload',
            	'grid_action_err_delete' => '_bx_files_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_files_grid_txt_account_manager',
                'filter_item_active' => '_bx_files_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_files_grid_filter_item_title_adm_hidden',
                'filter_item_pending' => '_bx_files_grid_filter_item_title_adm_pending',
            	'filter_item_select_one_filter1' => '_bx_files_grid_filter_item_title_adm_select_one_filter1',
                'filter_item_select_one_filter2' => '_bx_files_grid_filter_item_title_adm_select_one_filter2',
            	'menu_item_manage_my' => '_bx_files_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_files_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_files_txt_all_entries_by',
                'txt_all_entries_in' => '_bx_files_txt_all_entries_in',
                'txt_all_entries_by_author' => '_bx_files_page_title_browse_by_author',
                'txt_all_entries_by_context' => '_bx_files_page_title_browse_by_context',
            ),
        );

        $this->_aJsClasses = array(
        	'manage_tools' => 'BxFilesManageTools',
            'toolbar_tools' => 'BxFilesBrowserTools',
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxFilesManageTools',
            'toolbar_tools' => 'oBxFilesBrowserTools',
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }

    public function getJsClass($mixedType)
    {
        if (is_array($mixedType)) {
            return parent::getJsClass($mixedType['type']);
        } else {
            return parent::getJsClass($mixedType);
        }
    }

    public function getJsObject($mixedType)
    {
        if (is_array($mixedType)) {
            return parent::getJsObject($mixedType['type']).$mixedType['uniq'];
        } else {
            return parent::getJsObject($mixedType);
        }
    }
}

/** @} */
