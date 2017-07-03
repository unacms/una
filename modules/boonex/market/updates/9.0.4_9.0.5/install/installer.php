<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
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
    		if(!$this->oDb->isFieldExists('bx_market_licenses', 'new'))
        		$this->oDb->query("ALTER TABLE `bx_market_licenses` ADD `new` tinyint(1) NOT NULL default '1' AFTER `expired`");

			if(!$this->oDb->isIndexExists('bx_market_licenses', 'license'))
				$this->oDb->query("ALTER TABLE `bx_market_licenses` ADD INDEX `license` (`license`)");

			if(!$this->oDb->isFieldExists('bx_market_licenses_deleted', 'new'))
        		$this->oDb->query("ALTER TABLE `bx_market_licenses_deleted` ADD `new` tinyint(1) NOT NULL default '1' AFTER `expired`");

			if(!$this->oDb->isIndexExists('bx_market_licenses_deleted', 'license'))
				$this->oDb->query("ALTER TABLE `bx_market_licenses_deleted` ADD INDEX `license` (`license`)");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
