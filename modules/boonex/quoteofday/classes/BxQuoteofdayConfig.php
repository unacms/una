<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) Vendor
 * 
 * @defgroup    Quote of the Day
 * @ingroup     VendorModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxQuoteofdayConfig extends BxDolModuleConfig 
{
	function __construct($aModule) 
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
			'FIELD_STATUS' => 'status',
        	'FIELD_STATUS_ADMIN' => 'status_admin',
		);
		
		$this->_aGridObjects = array(
        	'common' => $this->CNF['OBJECT_GRID'],
        );
		
    }   
}

/** @} */
