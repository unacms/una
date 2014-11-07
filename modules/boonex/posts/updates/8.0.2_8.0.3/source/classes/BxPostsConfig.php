<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Posts Posts
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextConfig');

class BxPostsConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'posts',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'post-text',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
            'FIELD_COMMENTS' => 'comments',
            'FIELD_LOCATION_PREFIX' => 'location',

            // page URIs
            'URI_VIEW_ENTRY' => 'view-post',
            'URI_AUTHOR_ENTRIES' => 'posts-author',
            'URI_ADD_ENTRY' => 'create-post',
        	'URI_EDIT_ENTRY' => 'edit-post',
        	'URI_MANAGE_COMMON' => 'posts-manage',

            'URL_HOME' => 'page.php?i=posts-home',
            'URL_POPULAR' => 'page.php?i=posts-popular',
        	'URL_MANAGE_COMMON' => 'page.php?i=posts-manage',
        	'URL_MANAGE_MODERATION' => 'page.php?i=posts-moderation',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=posts-administration',

            // some params
            'PARAM_CHARS_SUMMARY' => 'bx_posts_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_posts_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_posts_rss_num',

            // objects
            'OBJECT_STORAGE' => 'bx_posts_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_posts_preview',
            'OBJECT_VIEWS' => 'bx_posts',
            'OBJECT_VOTES' => 'bx_posts',
            'OBJECT_METATAGS' => 'bx_posts',
            'OBJECT_COMMENTS' => 'bx_posts',
            'OBJECT_PRIVACY_VIEW' => 'bx_posts_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_posts',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_posts_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_posts_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_posts_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_posts_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_posts_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_posts_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_posts_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_posts_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'posts-home', // first item in view entry submenu from main module submenu
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_posts_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_posts_administration',
        	'OBJECT_GRID_MODERATION' => 'bx_posts_moderation',
        	'OBJECT_GRID_COMMON' => 'bx_posts_common',

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_posts_my' => array (
                    'create-post' => 'checkAllowedAdd',
                ),
                'bx_posts_view' => array (
                    'edit-post' => 'checkAllowedEdit',
                    'delete-post' => 'checkAllowedDelete',
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_posts_txt_sample_single',
            	'txt_sample_comment_single' => '_bx_posts_txt_sample_comment_single',
            	'grid_action_err_delete' => '_bx_posts_grid_action_err_delete',
				'filter_item_active' => '_bx_posts_grid_filter_item_title_adm_active',
            	'filter_item_hidden' => '_bx_posts_grid_filter_item_title_adm_hidden',
            	'filter_item_select_one_filter1' => '_bx_posts_grid_filter_item_title_adm_select_one_filter1',
            	'menu_item_manage_my' => '_bx_posts_menu_item_title_manage_my',
            	'menu_item_manage_all' => '_bx_posts_menu_item_title_manage_all',
            ),
        );

        $this->_aJsClass = array(
        	'manage_tools' => 'BxPostsManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxPostsManageTools'
        );

        $this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID_COMMON'],
        	'moderation' => $this->CNF['OBJECT_GRID_MODERATION'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );
    }
}

/** @} */
