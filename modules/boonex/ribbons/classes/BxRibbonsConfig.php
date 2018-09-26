<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxRibbonsConfig extends BxBaseModGeneralConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);
        $this->CNF = array (
            'OBJECT_GRID' => 'bx_ribbons_data',
             
            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'data',
            'TABLE_BINDING' => $aModule['db_prefix'] . 'profiles',
            
             // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_CHANGED' => 'changed',
            'FIELD_TITLE' => 'title',
            'FIELD_PHOTO' => 'pictures',
            'FIELD_THUMB' => 'thumb',
            'FIELD_TEXT' => 'text',
            'FIELD_STATUS' => 'status',
            'FIELD_PROFILE_ID' => 'profile_id',
            'FIELD_RIBBON_ID' => 'ribbon_id',
            
             // page URIs
            'URI_ADD_ENTRY' => 'create-ribbon',
            'URI_EDIT_ENTRY' => 'edit-ribbon',
            'URL_MANAGE' => 'page.php?i=ribbons-manage',
            
             // objects
            'OBJECT_FORM_ENTRY' => 'bx_ribbons',
            'OBJECT_FORM_ENTRY_DISPLAY_EDIT' => 'bx_ribbons_entry_edit',
            'OBJECT_FORM_ENTRY_DISPLAY_ADD' => 'bx_ribbons_entry_add',
            'OBJECT_STORAGE' => 'bx_ribbons_pictures',
            'OBJECT_IMAGES_TRANSCODER_PREVIEW' => 'bx_ribbons_pictures'
        );
        
        $this->_aGridObjects = array(
            'common' => $this->CNF['OBJECT_GRID'],
        );
        
        $this->_aJsClasses = array(
           'ribbons' => 'BxRibbons'
        );
        $this->_aJsObjects = array(
            'ribbons' => 'oBxRibbons'
        );
    }
}

/** @} */
