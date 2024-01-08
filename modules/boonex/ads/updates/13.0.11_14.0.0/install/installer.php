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
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'source_type'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `source_type` varchar(32) NOT NULL DEFAULT '' AFTER `received`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'source'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `source` varchar(255) NOT NULL DEFAULT '' AFTER `source_type`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'url'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `url` varchar(255) NOT NULL AFTER `title`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'budget_total'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `budget_total` float NOT NULL default '0' AFTER `location`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'budget_daily'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `budget_daily` float NOT NULL default '0' AFTER `budget_total`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'impressions'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `impressions` int(11) unsigned NOT NULL default '0' AFTER `budget_daily`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'clicks'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `clicks` int(11) unsigned NOT NULL default '0' AFTER `impressions`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'seg'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `seg` tinyint(4) NOT NULL DEFAULT '0' AFTER `featured`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'seg_gender'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `seg_gender` tinyint(4) NOT NULL DEFAULT '0' AFTER `seg`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'seg_age_min'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `seg_age_min` int(11) NOT NULL default '0' AFTER `seg_gender`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'seg_age_max'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `seg_age_max` int(11) NOT NULL default '0' AFTER `seg_age_min`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'seg_country'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `seg_country` varchar(255) NOT NULL DEFAULT '' AFTER `seg_age_max`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
