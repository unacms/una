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
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'sold'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `sold` int(11) NOT NULL AFTER `changed`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'shipped'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `shipped` int(11) NOT NULL AFTER `sold`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'received'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `received` int(11) NOT NULL AFTER `shipped`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'name'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `name` varchar(255) NOT NULL AFTER `thumb`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'auction'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `auction` tinyint(4) NOT NULL DEFAULT '0' AFTER `price`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'quantity'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `quantity` int(11) NOT NULL default '1' AFTER `auction`");
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'notes_purchased'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `notes_purchased` text NOT NULL AFTER `text`");

            if(!$this->oDb->isFieldExists('bx_ads_reports_track', 'checked_by'))
                $this->oDb->query("ALTER TABLE `bx_ads_reports_track` ADD `checked_by` int(11) NOT NULL default '0' AFTER `date`");
            if(!$this->oDb->isFieldExists('bx_ads_reports_track', 'status'))
                $this->oDb->query("ALTER TABLE `bx_ads_reports_track` ADD `status` tinyint(11) NOT NULL default '0' AFTER `checked_by`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
