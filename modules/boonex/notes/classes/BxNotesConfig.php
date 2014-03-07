<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Notes Notes
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModTextConfig');

class BxNotesConfig extends BxBaseModTextConfig 
{
    function __construct($aModule) 
    {
        parent::__construct($aModule);

        $this->CNF = array (

            // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_TEXT' => 'text',
            'FIELD_TEXT_ID' => 'note-text',
            'FIELD_SUMMARY' => 'summary',
            'FIELD_SUMMARY_ID' => 'note-summary',
            'FIELD_ALLOW_VIEW_TO' => 'allow_view_to',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',

            // page URIs
            'URI_VIEW_ENTRY' => 'view-note',
            'URI_HOME' => 'notes-home',
            'URI_AUTHOR_ENTRIES' => 'notes-author',

            // some params
            'PARAM_CHARS_SUMMARY' => 'bx_notes_summary_chars',
            'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_notes_plain_summary_chars',
            'PARAM_NUM_RSS' => 'bx_notes_rss_num',

            // objects
            'OBJECT_STORAGE' => 'bx_notes_photos',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_notes_preview',
            'OBJECT_VIEWS' => 'bx_notes',
            'OBJECT_VOTES' => 'bx_notes',
            'OBJECT_COMMENTS' => 'bx_notes',
            'OBJECT_PRIVACY_VIEW' => 'bx_notes_allow_view_to',
            'OBJECT_FORM_ENTRY' => 'bx_notes',
            'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_notes_entry_view',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_notes_entry_add',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_notes_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_notes_entry_delete',
            'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_notes_view', // actions menu on view entry page
            'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_notes_my', // actions menu on my entries page
            'OBJECT_MENU_SUBMENU' => 'bx_notes_submenu', // main module submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_notes_view_submenu', // view entry submenu
            'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'notes-home', // first item in view entry submenu from main module submenu

            // menu items which visibility depends on custom visibility checking
            'MENU_ITEM_TO_METHOD' => array (
                'bx_notes_my' => array (
                    'create-note' => 'checkAllowedAdd',
                ),
                'bx_notes_view' => array (
                    'edit-note' => 'checkAllowedEdit',
                    'delete-note' => 'checkAllowedDelete',
                ),
            ),

            // some language keys
            'T' => array (
                'txt_sample_single' => '_bx_notes_txt_sample_single',
            ),
        );

    }
}

/** @} */ 
