<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) Vendor
 * 
 * @defgroup    Quote of the Day module
 * @ingroup     VendorModules
 *
 * @{
 */

bx_import ('BxDolModuleTemplate');

class BxQuoteofdayTemplate extends BxDolModuleTemplate 
{    
	function __construct(&$oConfig, &$oDb) 
    {
	    parent::__construct($oConfig, $oDb);
    }    
}

/** @} */
