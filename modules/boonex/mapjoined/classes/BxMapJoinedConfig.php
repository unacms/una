<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MapJoined Display last joined users on map
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxMapJoinedConfig extends BxBaseModGeneralConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);
        $this->CNF = array (
            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'accounts',
            
             // database fields
            'FIELD_ID' => 'id',
            'FIELD_ACCOUNT_ID' => 'account_id',
            'FIELD_JOINED' => 'joined',
            'FIELD_IP' => 'ip',
            'FIELD_LNG' => 'lng',
            'FIELD_LAT' => 'lat'
        );
        
        $this->_aJsClasses = array(
            'map' => 'BxMapJoined'
        );
        $this->_aJsObjects = array(
            'map' => 'oMapJoined'
        );
    }
}

/** @} */
