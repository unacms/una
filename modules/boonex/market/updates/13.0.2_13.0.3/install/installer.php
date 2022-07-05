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
            if(!$this->oDb->isFieldExists('bx_market_products', 'cover_raw'))
                $this->oDb->query("ALTER TABLE `bx_market_products` ADD `cover_raw` text NOT NULL AFTER `cover`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
