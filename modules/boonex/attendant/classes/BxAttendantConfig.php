<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Attendant Attendant
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxAttendantConfig extends BxBaseModGeneralConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);
        $this->CNF = array (
            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'events',
            
             // database fields
            'FIELD_ID' => 'id',
            'FIELD_MODULE' => 'module',
            'FIELD_ACTION' => 'action',
            'FIELD_EVENT' => 'event',
            'FIELD_ADDED' => 'added',
            'FIELD_PROCESSED' => 'processed',
            'FIELD_OBJECT_ID' => 'object_id',
            'FIELD_PROFILE_ID' => 'profile_id'
        );
        $this->_aJsClasses = array(
           'main' => 'BxAttendant'
        );
        $this->_aJsObjects = array(
            'main' => 'oBxAttendant'
        );
    }
    
}

/** @} */
