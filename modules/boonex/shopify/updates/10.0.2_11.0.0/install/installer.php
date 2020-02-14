<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxShopifyUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_shopify_entries', 'location'))
                $this->oDb->query("ALTER TABLE `bx_shopify_entries` ADD `location` text NOT NULL AFTER `cat`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
