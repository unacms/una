<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Photos Photos
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPhotosConfig extends BxBaseModTextConfig
{
    protected $_aHtmlIds;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'edit-photo' => 'checkAllowedEdit',
            'delete-photo' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'camera-retro col-blue1',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'entries',
        	'TABLE_ENTRIES_FULLTEXT' => 'title_text',
            'TABLE_FILES' => $aModule['db_prefix'] . 'photos',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'photo-text',
            'FIELD_CATEGORY' => 'cat',
            'FIELD_DURATION' => 'duration',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
			'FIELD_VIEWS' => 'views',
            'FIELD_COMMENTS' => 'comments',
	        'FIELD_STATUS' => 'status',
        	'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-photo',
            'URI_AUTHOR_ENTRIES' => 'photos-author',
            'URI_ADD_ENTRY' => 'create-photo',
        	'URI_EDIT_ENTRY' => 'edit-photo',
        	'URI_MANAGE_COMMON' => 'photos-manage',

            'URL_HOME' => 'page.php?i=photos-home',
            'URL_POPULAR' => 'page.php?i=photos-popular',
            'URL_UPDATED' => 'page.php?i=photos-updated',
        	'URL_MANAGE_COMMON' => 'page.php?i=photos-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=photos-administration',

            // some params
            'PARAM_CHARS_SUMMARY' => 'bx_photos_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_photos_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_photos_rss_num',
        	'PARAM_SEARCHABLE_FIELDS' => 'bx_photos_searchable_fields',

            // objects
            'OBJECT_STORAGE' => 'bx_photos_photos',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_photos_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_photos_gallery',
        	'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_photos_cover',
        	'OBJECT_REPORTS' => 'bx_photos',
            'OBJECT_VIEWS' => 'bx_photos',
            'OBJECT_VOTES' => 'bx_photos',
            'OBJECT_VOTES_STARS' => 'bx_photos_stars',
        	'OBJECT_FAVORITES' => 'bx_photos',
            'OBJECT_FEATURED' => 'bx_photos',
            'OBJECT_METATAGS' => 'bx_photos',
            'OBJECT_COMMENTS' => 'bx_photos',
            'OBJECT_CATEGORY' => 'bx_photos_cats',
            'OBJECT_PRIVACY_VIEW' => 'bx_photos_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_photos',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_photos_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_photos_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_photos_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_photos_entry_delete',
            'OBJECT_PAGE_VIEW_ENTRY_BRIEF' => 'bx_photos_view_entry_brief', // brief view page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_photos_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_photos_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_photos_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_photos_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'photos-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_SNIPPET_META' => 'bx_photos_snippet_meta', // menu for snippet meta info
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_photos_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_photos_administration',
        	'OBJECT_GRID_COMMON' => 'bx_photos_common',
            'OBJECT_UPLOADERS' => array('sys_simple', 'sys_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_photos_my' => array (
                    'create-photo' => 'checkAllowedAdd',
                ),
                'bx_photos_view' => $aMenuItems2Methods,
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_photos_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_photos_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_photos_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_photos_txt_sample_vote_single',
                'form_field_author' => '_bx_photos_form_entry_input_author',
            	'grid_action_err_delete' => '_bx_photos_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_photos_grid_txt_account_manager',
				'filter_item_active' => '_bx_photos_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_photos_grid_filter_item_title_adm_hidden',
            	'filter_item_select_one_filter1' => '_bx_photos_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_photos_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_photos_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_photos_txt_all_entries_by',
                'txt_all_entries_by_author' => '_bx_photos_page_title_browse_by_author',
            	'txt_pict_use_as_thumb' => '_bx_photos_form_entry_input_picture_use_as_thumb'
            ),
        );

        $this->_aJsClasses = array(
        	'main' => 'BxPhotosMain',
        	'manage_tools' => 'BxPhotosManageTools'
        );

        $this->_aJsObjects = array(
        	'main' => 'oBxPhotosMain',
        	'manage_tools' => 'oBxPhotosManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION']
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
        	'view_entry_brief_popup' => $sHtmlPrefix . '-view-entry-brief-popup'
        );
    }

    public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }
}

/** @} */
