<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Albums Albums
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAlbumsConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'edit-album' => 'checkAllowedEdit',
            'delete-album' => 'checkAllowedDelete',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'picture-o col-blue1',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'albums',
            'TABLE_FILES2ENTRIES' => $aModule['db_prefix'] . 'files2albums',
            'TABLE_FILES' => $aModule['db_prefix'] . 'files',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'album-desc',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_LOCATION_PREFIX' => 'location',
            'FIELDS_WITH_KEYWORDS' => 'auto', // can be 'auto', array of fields or comma separated string of field names, works only when OBJECT_METATAGS is specified

            // page URIs
            'URI_VIEW_ENTRY' => 'view-album',
            'URI_VIEW_MEDIA' => 'view-album-media',
            'URI_AUTHOR_ENTRIES' => 'albums-author',
            'URI_ADD_ENTRY' => 'create-album',
        	'URI_EDIT_ENTRY' => 'edit-album',
        	'URI_MANAGE_COMMON' => 'albums-manage',

            'URL_HOME' => 'page.php?i=albums-home',
            'URL_POPULAR' => 'page.php?i=albums-popular',
            'URL_UPDATED' => 'page.php?i=albums-updated',
            'URL_POPULAR_MEDIA' => 'page.php?i=albums-popular-media',
            'URL_RECENT_MEDIA' => 'page.php?i=albums-recent-media', // TODO: add page
        	'URL_MANAGE_COMMON' => 'page.php?i=albums-manage',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=albums-administration',

            // some params
            'PARAM_CHARS_SUMMARY' => 'bx_albums_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_albums_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_albums_rss_num',

            // objects
            'OBJECT_STORAGE' => 'bx_albums_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_albums_proxy_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => '',
            'OBJECT_IMAGES_TRANSCODER_BIG' => 'bx_albums_big',
            'OBJECT_VIDEOS_TRANSCODERS' => array('poster' => 'bx_albums_video_poster_big', 'poster_preview' => 'bx_albums_video_poster_preview', 'mp4' => 'bx_albums_video_mp4', 'webm' => 'bx_albums_video_webm'),
            'OBJECT_VIDEO_TRANSCODER_HEIGHT' => '480px',
            'OBJECT_TRANSCODER_BROWSE' => 'bx_albums_proxy_browse',
            'OBJECT_VIEWS' => 'bx_albums',
            'OBJECT_VIEWS_MEDIA' => 'bx_albums_media',
            'OBJECT_VOTES' => 'bx_albums',
            'OBJECT_VOTES_MEDIA' => 'bx_albums_media',
            'OBJECT_METATAGS' => 'bx_albums',
            'OBJECT_METATAGS_MEDIA' => 'bx_albums_media',
            'OBJECT_METATAGS_MEDIA_CAMERA' => 'bx_albums_media_camera',
            'OBJECT_COMMENTS' => 'bx_albums',
            'OBJECT_COMMENTS_MEDIA' => 'bx_albums_media',
            'OBJECT_PRIVACY_VIEW' => 'bx_albums_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_albums',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_albums_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_albums_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_albums_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_albums_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_albums_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_POPUP' => 'bx_albums_view_popup', // actions menu popup on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_albums_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_albums_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_albums_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'albums-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_albums_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_albums_administration',
        	'OBJECT_GRID_COMMON' => 'bx_albums_common',
        	'OBJECT_UPLOADERS' => array('bx_albums_simple', 'bx_albums_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_albums_my' => array (
                    'create-album' => 'checkAllowedAdd',
                ),
                'bx_albums_view' => $aMenuItems2Methods,
                'bx_albums_view_popup' => $aMenuItems2Methods,
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_albums_txt_sample_single',
            	'txt_sample_comment_single' => '_bx_albums_txt_sample_comment_single',
            	'txt_sample_vote_single' => '_bx_albums_txt_sample_vote_single',
                'txt_media_single' => '_bx_albums_txt_media_single',
                'txt_media_comment_single' => '_bx_albums_txt_media_comment_single',
            	'grid_action_err_delete' => '_bx_albums_grid_action_err_delete',
            	'grid_txt_account_manager' => '_bx_albums_grid_txt_account_manager',
				'filter_item_active' => '_bx_albums_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_albums_grid_filter_item_title_adm_hidden',
            	'filter_item_select_one_filter1' => '_bx_albums_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_albums_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_albums_menu_item_title_manage_all',
                'txt_all_entries_by' => '_bx_albums_txt_all_entries_by',
            ),
        );

        $this->_aJsClass = array(
        	'manage_tools' => 'BxAlbumsManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxAlbumsManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }
}

/** @} */
