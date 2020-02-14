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
            if(!$this->oDb->isFieldExists('bx_market_products', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_market_products` ADD `labels` text NOT NULL AFTER `trial_recurring`");
            if(!$this->oDb->isFieldExists('bx_market_products', 'location'))
                $this->oDb->query("ALTER TABLE `bx_market_products` ADD `location` text NOT NULL AFTER `labels`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
