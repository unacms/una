<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

class BxForumConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'subscribe-discussion' => 'checkAllowedSubscribe',
            'unsubscribe-discussion' => 'checkAllowedUnsubscribe',
        	'stick-discussion' => 'checkAllowedStickAnyEntry',
        	'unstick-discussion' => 'checkAllowedUnstickAnyEntry',
        	'lock-discussion' => 'checkAllowedLockAnyEntry',
        	'unlock-discussion' => 'checkAllowedUnlockAnyEntry',
        	'hide-discussion' => 'checkAllowedHideAnyEntry',
        	'unhide-discussion' => 'checkAllowedUnhideAnyEntry',
        	'edit-discussion' => 'checkAllowedEdit',
            'delete-discussion' => 'checkAllowedDelete'
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'comments-o col-blue2',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'discussions',
        	'TABLE_ENTRIES_FULLTEXT' => 'title_text',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'discussion-text',
            'FIELD_TEXT_COMMENTS' => 'text_comments',
        	'FIELD_CATEGORY' => 'cat',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_PHOTO' => 'attachments',
            'FIELD_THUMB' => '',
            'FIELD_COMMENTS' => 'comments',
        	'FIELD_STICK' => 'stick',
        	'FIELD_LOCK' => 'lock',
        	'FIELD_STATUS' => 'status',
        	'FIELD_STATUS_ADMIN' => 'status_admin',
	        'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-discussion',
            'URI_AUTHOR_ENTRIES' => 'discussions-author',
        	'URI_CATEGORY_ENTRIES' => 'discussions-category',
        	'URI_KEYWORD_ENTRIES' => 'discussions-keyword',
            'URI_ADD_ENTRY' => 'create-discussion',
        	'URI_EDIT_ENTRY' => 'edit-discussion',
        	'URI_MANAGE_COMMON' => 'discussions-manage',

            'URL_HOME' => 'page.php?i=discussions-home',
        	'URL_UPDATED' => 'page.php?i=discussions-updated',
        	'URL_NEW' => 'page.php?i=discussions-new',
            'URL_TOP' => 'page.php?i=discussions-top',
        	'URL_POPULAR' => 'page.php?i=discussions-popular',
        	'URL_MANAGE_COMMON' => 'page.php?i=discussions-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=discussions-administration',

            // some params
            'PARAM_CHARS_SUMMARY' => 'bx_forum_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_forum_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_forum_rss_num',
        	'PARAM_SEARCHABLE_FIELDS' => 'bx_forum_searchable_fields',
        	'PARAM_AUTOSUBSCRIBE_CREATED' => 'bx_forum_autosubscribe_created',
        	'PARAM_AUTOSUBSCRIBE_REPLIED' => 'bx_forum_autosubscribe_replied',

            // objects
            'OBJECT_GRID' => 'bx_forum',
        	'OBJECT_GRID_FAVORITE' => 'bx_forum_favorite',
            'OBJECT_GRID_FEATURE' => 'bx_forum_feature',
        	'OBJECT_GRID_ADMINISTRATION' => 'bx_forum_administration',
        	'OBJECT_GRID_COMMON' => 'bx_forum_common',
        	'OBJECT_GRID_CATEGORIES' => 'bx_forum_categories',
            'OBJECT_STORAGE' => 'bx_forum_files',
        	'OBJECT_STORAGE_CMTS' => 'bx_forum_files_cmts',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_forum_preview',
        	'OBJECT_IMAGES_TRANSCODER_PREVIEW_CMTS' => 'bx_forum_preview_cmts',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => '',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
            'OBJECT_VIEWS' => 'bx_forum',
            'OBJECT_VOTES' => 'bx_forum',
        	'OBJECT_FAVORITES' => 'bx_forum',
        	'OBJECT_FEATURED' => 'bx_forum',
        	'OBJECT_CATEGORY' => 'bx_forum_cats',
            'OBJECT_COMMENTS' => 'bx_forum',
        	'OBJECT_METATAGS' => 'bx_forum',
            'OBJECT_PRIVACY_VIEW' => 'bx_forum_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_forum',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_forum_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_forum_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_forum_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_forum_entry_delete',
        	'OBJECT_FORM_SEARCH' => 'bx_forum_search',
            'OBJECT_FORM_SEARCH_DISPLAY_FULL' => 'bx_forum_search_full',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_forum_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_MORE' => 'bx_forum_view_more', // more actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_forum_my', // actions menu on my entries page
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_forum_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_MENU_SUBMENU' => 'bx_forum_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => '', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'forum', // first item in view entry submenu from main module submenu
            'OBJECT_UPLOADERS' => array('sys_simple', 'sys_html5'),
            'OBJECT_CONNECTION_SUBSCRIBERS' => 'bx_forum_subscribers',

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_forum_view' => $aMenuItems2Methods,
        		'bx_forum_view_more' => $aMenuItems2Methods,
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_forum_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_forum_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_forum_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_forum_txt_sample_vote_single',
            	'form_field_author' => '_bx_forum_form_entry_input_author',
            	'grid_action_err_delete' => '_bx_forum_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_forum_grid_txt_account_manager',
            	'filter_item_active' => '_bx_forum_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_forum_grid_filter_item_title_adm_hidden',
            	'filter_item_select_one_filter1' => '_bx_forum_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_forum_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_forum_menu_item_title_manage_all',
            	'txt_all_entries_by_author' => '_bx_forum_page_title_browse_by_author',
            ),
        );

        $this->_aJsClasses = array(
        	'main' => 'BxForumMain',
        	'entry' => 'BxForumEntry',
        	'manage_tools' => 'BxForumManageTools',
        	'studio' => 'BxForumStudio'
        );

        $this->_aJsObjects = array(
        	'main' => 'oBxForumMain',
        	'entry' => 'oBxForumEntry',
        	'manage_tools' => 'oBxForumManageTools',
        	'studio' => 'oBxForumStudio'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }
}

/** @} */
