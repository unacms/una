<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

class BxMarketUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
	{
        parent::__construct($aConfig);
    }

	public function actionExecuteSql($sOperation)
    {
    	if($sOperation == 'install') {
    		if(!$this->oDb->isFieldExists('bx_market_products', 'status_admin'))
        		$this->oDb->query("ALTER TABLE `bx_market_products` ADD `status_admin` enum('active','hidden') NOT NULL DEFAULT 'active' AFTER `status`");

			if(!$this->oDb->isFieldExists('bx_market_files2products', 'type'))
	        		$this->oDb->query("ALTER TABLE `bx_market_files2products` ADD `type` enum('version','update') NOT NULL DEFAULT 'version' AFTER `file_id`");
	
			if(!$this->oDb->isFieldExists('bx_market_files2products', 'version_to'))
	        		$this->oDb->query("ALTER TABLE `bx_market_files2products` ADD `version_to` varchar(255) NOT NULL AFTER `version`");

			if(!$this->oDb->isFieldExists('bx_market_meta_locations', 'street'))
	        		$this->oDb->query("ALTER TABLE `bx_market_meta_locations` ADD `street` varchar(255) NOT NULL AFTER `zip`");
	
			if(!$this->oDb->isFieldExists('bx_market_meta_locations', 'street_number'))
	        		$this->oDb->query("ALTER TABLE `bx_market_meta_locations` ADD `street_number` varchar(255) NOT NULL AFTER `street`");
    	}

    	return parent::actionExecuteSql($sOperation);
    }
}
