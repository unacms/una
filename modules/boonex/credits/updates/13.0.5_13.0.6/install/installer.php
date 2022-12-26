<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxCreditsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_credits_profiles', 'wdw_clearing'))
                $this->oDb->query("ALTER TABLE `bx_credits_profiles` ADD `wdw_clearing` int(11) unsigned NOT NULL default '0' AFTER `id`");
            if(!$this->oDb->isFieldExists('bx_credits_profiles', 'wdw_minimum'))
                $this->oDb->query("ALTER TABLE `bx_credits_profiles` ADD `wdw_minimum` int(11) unsigned NOT NULL default '0' AFTER `wdw_clearing`");
            if(!$this->oDb->isFieldExists('bx_credits_profiles', 'wdw_remaining'))
                $this->oDb->query("ALTER TABLE `bx_credits_profiles` ADD `wdw_remaining` int(11) unsigned NOT NULL default '0' AFTER `wdw_minimum`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
