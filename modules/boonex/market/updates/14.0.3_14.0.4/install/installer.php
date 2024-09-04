<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxMarketUpdater extends BxDolStudioUpdater
{
    public function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_market_products', 'cover_data'))
                $this->oDb->query("ALTER TABLE `bx_market_products` ADD `cover_data` varchar(64) NOT NULL default '' AFTER `cover`");
                
            if(!$this->oDb->isFieldExists('bx_market_licenses', 'expired_notif'))
                $this->oDb->query("ALTER TABLE `bx_market_licenses` ADD `expired_notif` int(11) unsigned NOT NULL default '0' AFTER `expired`");

            if(!$this->oDb->isFieldExists('bx_market_licenses_deleted', 'expired_notif'))
                $this->oDb->query("ALTER TABLE `bx_market_licenses_deleted` ADD `expired_notif` int(11) unsigned NOT NULL default '0' AFTER `expired`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
