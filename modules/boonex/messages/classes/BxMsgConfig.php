<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextConfig');

class BxMsgConfig extends BxBaseModTextConfig 
{
    function __construct($aModule) 
    {
        parent::__construct($aModule);

        $this->CNF = array (

            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'conversations',

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => '',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'message-text',
            'FIELD_SUMMARY' => '',
            'FIELD_SUMMARY_ID' => '',
            'FIELD_ALLOW_VIEW_TO' => '',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => '',

            // page URIs
            'URI_VIEW_ENTRY' => 'view-message',
            'URI_HOME' => 'messages',
            'URI_AUTHOR_ENTRIES' => 'messages',

            // some params
            'PARAM_CHARS_SUMMARY' => '',
            'PARAM_CHARS_SUMMARY_PLAIN' => '',
            'PARAM_NUM_RSS' => '',

            // objects
            'OBJECT_STORAGE' => 'bx_messages_photos',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_messages_preview',
            'OBJECT_VIEWS' => 'bx_messages',
            'OBJECT_VOTES' => '',
            'OBJECT_COMMENTS' => 'bx_messages',
            'OBJECT_PRIVACY_VIEW' => '',
            'OBJECT_FORM_ENTRY' => 'bx_messages',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_messages_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_messages_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => '',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_messages_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_messages_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_messages_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_messages_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => '', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'messages', // first item in view entry submenu from main module submenu

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_messages_my' => array (
                    'compose-message' => 'checkAllowedAdd',
                ),
                'bx_messages_view' => array (
                    'delete-message' => 'checkAllowedDelete',
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_msg_txt_sample_single',
            ),
        );

    }
}

/** @} */ 
