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
            'download-file' => 'checkAllowedView',
            'edit-file' => 'checkAllowedEdit',
            'delete-file' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'file-o col-red3',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'main',
            'TABLE_ENTRIES_FULLTEXT' => 'search_fields',
            'TABLE_FILES' => $aModule['db_prefix'] . 'files',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_FILE_ID' => 'file_id',
            'FIELD_TITLE' => 'title',
            'FIELD_CATEGORY' => 'cat',
            'FIELD_TEXT' => 'desc',
            'FIELD_TEXT_ID' => 'file-desc',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_PHOTO' => 'attachments',
            'FIELD_COMMENTS' => 'comments',
	        'FIELD_STATUS' => 'status',
        	'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-file',
            'URI_AUTHOR_ENTRIES' => 'files-author',
            'URI_GROUP_ENTRIES' => 'group-files',
            'URI_ADD_ENTRY' => 'create-file',
        	'URI_EDIT_ENTRY' => 'edit-file',
        	'URI_MANAGE_COMMON' => 'files-manage',

            'URL_HOME' => 'page.php?i=files-home',
            'URL_POPULAR' => 'page.php?i=files-popular',
            'URL_UPDATED' => 'page.php?i=files-updated',
        	'URL_MANAGE_COMMON' => 'page.php?i=files-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=files-administration',

            // some params
            'PARAM_CHARS_SUMMARY' => 'bx_files_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_files_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_files_rss_num',
        	'PARAM_SEARCHABLE_FIELDS' => 'bx_files_searchable_fields',

            // objects
            'OBJECT_STORAGE' => 'bx_files_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_files_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_files_gallery',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
        	'OBJECT_REPORTS' => 'bx_files',
            'OBJECT_VIEWS' => 'bx_files',
            'OBJECT_VOTES' => 'bx_files',
        	'OBJECT_FAVORITES' => 'bx_files',
            'OBJECT_METATAGS' => 'bx_files',
            'OBJECT_COMMENTS' => 'bx_files',
            'OBJECT_PRIVACY_VIEW' => 'bx_files_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_files',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_files_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_files_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_files_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_files_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_files_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_files_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_files_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_files_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'files-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_files_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_files_administration',
        	'OBJECT_GRID_COMMON' => 'bx_files_common',
            'OBJECT_UPLOADERS' => array('sys_simple', 'sys_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_files_my' => array (
                    'create-file' => 'checkAllowedAdd',
                ),
                'bx_files_view' => $aMenuItems2Methods,
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_files_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_files_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_files_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_files_txt_sample_vote_single',
            	'grid_action_err_delete' => '_bx_files_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_files_grid_txt_account_manager',
				'filter_item_active' => '_bx_files_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_files_grid_filter_item_title_adm_hidden',
            	'filter_item_select_one_filter1' => '_bx_files_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_files_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_files_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_files_txt_all_entries_by',
                'txt_all_entries_by_author' => '_bx_files_page_title_browse_by_author',
            ),
        );

        $this->_aJsClasses = array(
        	'manage_tools' => 'BxFilesManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxFilesManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }
}

/** @} */
