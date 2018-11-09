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
            if($this->oDb->isFieldExists('bx_shopify_settings', 'api_key'))
                $this->oDb->query("ALTER TABLE `bx_shopify_settings` DROP `api_key`");
             if($this->oDb->isFieldExists('bx_shopify_settings', 'app_id'))
                $this->oDb->query("ALTER TABLE `bx_shopify_settings` DROP `app_id`");
            if(!$this->oDb->isFieldExists('bx_shopify_settings', 'access_token'))
                $this->oDb->query("ALTER TABLE `bx_shopify_settings` ADD `access_token` varchar(255) NOT NULL AFTER `domain`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
