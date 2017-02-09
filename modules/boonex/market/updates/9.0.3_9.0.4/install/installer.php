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
    		if(!$this->oDb->isFieldExists('bx_market_products', 'trial_recurring'))
        		$this->oDb->query("ALTER TABLE `bx_market_products` ADD `trial_recurring` int(11) NOT NULL default '0' AFTER `duration_recurring`");

    		if(!$this->oDb->isFieldExists('bx_market_products', 'featured'))
        		$this->oDb->query("ALTER TABLE `bx_market_products` ADD `featured` int(11) NOT NULL default '0' AFTER `reports`");
		}

    	return parent::actionExecuteSql($sOperation);
    }
}
