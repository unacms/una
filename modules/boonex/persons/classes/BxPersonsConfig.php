<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Persons Persons
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxPersonsConfig extends BxDolModuleConfig {

    public static $FIELD_AUTHOR = 'author';
    public static $FIELD_ADDED = 'added';
    public static $FIELD_CHANGED = 'changed';
    public static $FIELD_NAME = 'fullname';
    public static $FIELD_PICTURE = 'picture';

    public static $OBJECT_STORAGE = 'bx_persons_pictures';
    public static $OBJECT_IMAGES_TRANSCODER_THUMB = 'bx_persons_thumb';
    public static $OBJECT_IMAGES_TRANSCODER_ICON = 'bx_persons_icon';
    public static $OBJECT_IMAGES_TRANSCODER_PREVIEW = 'bx_persons_preview';

    /**
     * Constructor
     */
    function __construct($aModule) {
        parent::__construct($aModule);
    }

}

/** @} */ 
