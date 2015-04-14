<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Convos Convos
 * @ingroup     TridentModules
 *
 * @{
 */

class BxCnvConfig extends BxBaseModTextConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $aMenuItems2Methods = array (
            'delete-convos' => 'checkAllowedDelete',
            'mark-unread-convo' => 'checkAllowedView',
        );

        $this->CNF = array (

            // module icon
            'ICON' => 'comments col-red1',

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'conversations',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => '',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'convo-text',
            'FIELD_ALLOW_VIEW_TO' => '',
            'FIELD_PHOTO' => 'attachments',
            'FIELD_THUMB' => '',
            'FIELD_COMMENTS' => 'comments',

            // page URIs
            'URI_VIEW_ENTRY' => 'view-convo',
            'URI_AUTHOR_ENTRIES' => 'convos',
            'URI_ADD_ENTRY' => 'start-convo',

            'URL_HOME' => 'modules/?r=convos/folder/1',

            // some params
            'PARAM_CHARS_SUMMARY' => '',
            'PARAM_CHARS_SUMMARY_PLAIN' => '',
            'PARAM_NUM_RSS' => '',

            // objects
            'OBJECT_GRID' => 'bx_convos',
            'OBJECT_STORAGE' => 'bx_convos_files',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_convos_preview',
            'OBJECT_IMAGES_TRANSCODER_GALLERY' => '',
            'OBJECT_VIDEOS_TRANSCODERS' => array(),
            'OBJECT_VIEWS' => 'bx_convos',
            'OBJECT_VOTES' => '',
            'OBJECT_COMMENTS' => 'bx_convos',
            'OBJECT_PRIVACY_VIEW' => '',
            'OBJECT_FORM_ENTRY' => 'bx_convos',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_convos_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_convos_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => '',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_convos_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_convos_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY_POPUP' => 'bx_convos_view_popup', // actions menu popup on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_convos_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_convos_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => '', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'convos', // first item in view entry submenu from main module submenu
            'OBJECT_UPLOADERS' => array('sys_simple', 'sys_html5'),

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_convos_view' => $aMenuItems2Methods,
                'bx_convos_view_popup' => $aMenuItems2Methods,
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_cnv_txt_sample_single',
            ),
        );

    }
}

/** @} */
