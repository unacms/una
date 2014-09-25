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

            'URL_HOME' => 'page.php?i=posts-home',

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
            ),
        );

    }
}

/** @} */
