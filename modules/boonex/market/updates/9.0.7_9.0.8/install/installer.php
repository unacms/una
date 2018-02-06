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
			if ($this->oDb->isIndexExists('bx_market_products', 'name'))
				$this->oDb->query("ALTER TABLE `bx_market_products` DROP INDEX `name`");

			$this->oDb->query("ALTER TABLE `bx_market_products` ADD UNIQUE KEY `name` (`name`(191))");
    	}

    	return parent::actionExecuteSql($sOperation);
    }
}
