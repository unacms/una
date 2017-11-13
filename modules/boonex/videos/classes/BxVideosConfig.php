<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

class BxVideosConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'edit-video' => 'checkAllowedEdit',
            'delete-video' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'film col-gray',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'entries',
        	'TABLE_ENTRIES_FULLTEXT' => 'title_text',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'video-text',
            'FIELD_DURATION' => 'duration',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
        	'FIELD_VIDEOS' => 'videos',
            'FIELD_VIDEO' => 'video',
            'FIELD_COMMENTS' => 'comments',
	        'FIELD_STATUS' => 'status',
        	'FIELD_STATUS_ADMIN' => 'status_admin',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-video',
            'URI_AUTHOR_ENTRIES' => 'videos-author',
            'URI_ADD_ENTRY' => 'create-video',
        	'URI_EDIT_ENTRY' => 'edit-video',
        	'URI_MANAGE_COMMON' => 'videos-manage',

            'URL_HOME' => 'page.php?i=videos-home',
            'URL_POPULAR' => 'page.php?i=videos-popular',
            'URL_UPDATED' => 'page.php?i=videos-updated',
        	'URL_MANAGE_COMMON' => 'page.php?i=videos-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=videos-administration',

            // some params
            'PARAM_CHARS_SUMMARY' => 'bx_videos_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_videos_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_videos_rss_num',
        	'PARAM_SEARCHABLE_FIELDS' => 'bx_videos_searchable_fields',

            // objects
            'OBJECT_STORAGE' => 'bx_videos_photos',
        	'OBJECT_STORAGE_VIDEOS' => 'bx_videos_videos',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_videos_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => 'bx_videos_gallery',
        	'OBJECT_IMAGES_TRANSCODER_COVER' => 'bx_videos_cover',
            'OBJECT_VIDEOS_TRANSCODERS' => array(
            	'poster' => 'bx_videos_video_poster_cover', 
            	'poster_preview' => 'bx_videos_video_poster_preview',
                'poster_gallery' => 'bx_videos_video_poster_gallery',  
            	'mp4' => 'bx_videos_video_mp4', 
            	'webm' => 'bx_videos_video_webm'
            ),
        	'OBJECT_VIDEO_TRANSCODER_HEIGHT' => '480px',
        	'OBJECT_REPORTS' => 'bx_videos',
            'OBJECT_VIEWS' => 'bx_videos',
            'OBJECT_VOTES' => 'bx_videos',
        	'OBJECT_FAVORITES' => 'bx_videos',
            'OBJECT_FEATURED' => 'bx_videos',
            'OBJECT_METATAGS' => 'bx_videos',
            'OBJECT_COMMENTS' => 'bx_videos',
            'OBJECT_PRIVACY_VIEW' => 'bx_videos_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_videos',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_videos_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_videos_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_videos_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_videos_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_videos_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_videos_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_videos_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_videos_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'videos-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_videos_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_videos_administration',
        	'OBJECT_GRID_COMMON' => 'bx_videos_common',
            'OBJECT_UPLOADERS' => array('sys_simple', 'sys_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_videos_my' => array (
                    'create-video' => 'checkAllowedAdd',
                ),
                'bx_videos_view' => $aMenuItems2Methods,
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_videos_txt_sample_single',
            	'txt_sample_single_with_article' => '_bx_videos_txt_sample_single_with_article',
            	'txt_sample_comment_single' => '_bx_videos_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_videos_txt_sample_vote_single',
                'form_field_author' => '_bx_videos_form_entry_input_author',
            	'grid_action_err_delete' => '_bx_videos_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_videos_grid_txt_account_manager',
				'filter_item_active' => '_bx_videos_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_videos_grid_filter_item_title_adm_hidden',
            	'filter_item_select_one_filter1' => '_bx_videos_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_videos_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_videos_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_videos_txt_all_entries_by',
                'txt_all_entries_by_author' => '_bx_videos_page_title_browse_by_author',
            	'txt_pict_use_as_thumb' => '_bx_videos_form_entry_input_picture_use_as_thumb'
            ),
        );

        $this->_aJsClasses = array(
        	'manage_tools' => 'BxVideosManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxVideosManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION']
        );
    }
}

/** @} */
