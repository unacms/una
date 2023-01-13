<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAdsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'single'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `single` tinyint(4) NOT NULL DEFAULT '1' AFTER `quantity`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
