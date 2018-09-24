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
            if(!$this->oDb->isFieldExists('bx_market_products', 'notes_purchased'))
                $this->oDb->query("ALTER TABLE `bx_market_products` ADD `notes_purchased` text NOT NULL AFTER `notes`");
        }

    	return parent::actionExecuteSql($sOperation);
    }
}
