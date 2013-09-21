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

class BxNotesConfig extends BxDolModuleConfig {

    public static $FIELD_AUTHOR = 'author';
    public static $FIELD_ADDED = 'added';
    public static $FIELD_CHANGED = 'changed';
    public static $FIELD_TITLE = 'title';
    public static $FIELD_TEXT = 'text';
    public static $FIELD_TEXT_ID = 'note-text';
    public static $FIELD_SUMMARY = 'summary';
    public static $FIELD_SUMMARY_ID = 'note-summary';
    public static $FIELD_PHOTO = 'pictures';
    public static $FIELD_THUMB = 'thumb';

    public static $OBJECT_STORAGE = 'bx_notes_photos';
    public static $OBJECT_IMAGES_TRANSCODER_PREVIEW = 'bx_notes_preview';
    public static $OBJECT_COMMENTS = 'bx_notes';

    /**
     * Constructor
     */
    function __construct($aModule) {
        parent::__construct($aModule);
    }

}

/** @} */ 
