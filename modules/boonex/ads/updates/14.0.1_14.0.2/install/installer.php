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
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'tags'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `tags` text NOT NULL AFTER `labels`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'seg_tags'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `seg_tags` tinyint(4) NOT NULL DEFAULT '0' AFTER `seg_age_max`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
