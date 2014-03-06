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

bx_import('BxDolModuleConfig');

class BxNotesConfig extends BxDolModuleConfig 
{
    public $CNF = array (
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

        'URI_VIEW_ENTRY' => 'view-note',
        'URI_HOME' => 'notes-home',
        'URI_AUTHOR_ENTRIES' => 'notes-author',

        'PARAM_CHARS_SUMMARY' => 'bx_notes_summary_chars',
        'PARAM_CHARS_SUMMARY_PLAIN' => 'bx_notes_plain_summary_chars',

        'OBJECT_STORAGE' => 'bx_notes_photos',
        'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_notes_preview',
        'OBJECT_VIEWS' => 'bx_notes',
        'OBJECT_VOTES' => 'bx_notes',
        'OBJECT_COMMENTS' => 'bx_notes',
        'OBJECT_PRIVACY_VIEW' => 'bx_notes_allow_view_to',
        'OBJECT_FORM_ENTRY' => 'bx_notes',
        'OBJECT_FORM_ENTRY_DISPLAY_VIEW' => 'bx_notes_note_view',
        'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_notes_note_add',
        'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_notes_note_edit',
        'OBJECT_FORM_ENTRY_DISPLAY_DELETE' => 'bx_notes_note_delete',
        'OBJECT_MENU_ACTIONS_VIEW_ENTRY' => 'bx_notes_view',
        'OBJECT_MENU_ACTIONS_MY_ENTRIES' => 'bx_notes_my',
        'OBJECT_MENU_SUBMENU' => 'bx_notes_submenu',
        'OBJECT_MENU_SUBMENU_VIEW_ENTRY' => 'bx_notes_view_submenu',
        'OBJECT_MENU_SUBMENU_VIEW_ENTRY_MAIN_SELECTION' => 'notes-home',

        'MENU_ITEM_TO_METHOD' => array (
            'bx_notes_my' => array (
                'create-note' => 'checkAllowedAdd',
            ),
            'bx_notes_view' => array (
                'edit-note' => 'checkAllowedEdit',
                'delete-note' => 'checkAllowedDelete',
            ),
        ),
    );

    /**
     * Constructor
     */
    function __construct($aModule) 
    {
        parent::__construct($aModule);
    }
}

/** @} */ 
