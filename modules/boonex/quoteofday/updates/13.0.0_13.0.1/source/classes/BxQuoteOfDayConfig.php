<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    QuoteOfTheDay Quote of the Day
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxQuoteOfDayConfig extends BxDolModuleConfig
{
    public $CNF;
    protected $_aGridObjects;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
            'OBJECT_GRID' => 'bx_quoteofday_internal',
            'CACHEKEY' => 'bx_quoteofday',
             
            // database tables
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'internal',
            
             // database fields
            'FIELD_ID' => 'id',
            'FIELD_AUTHOR' => 'author',
            'FIELD_ADDED' => 'added',
            'FIELD_TEXT' => 'text',
            'FIELD_STATUS' => 'status'
        );
        
        $this->_aGridObjects = array(
            'common' => $this->CNF['OBJECT_GRID'],
        );
    }
}

/** @} */
