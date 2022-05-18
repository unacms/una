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
            if(!$this->oDb->isFieldExists('bx_credits_history', 'type'))
                $this->oDb->query("ALTER TABLE `bx_credits_history` ADD `type` varchar(16) NOT NULL default '' AFTER `amount`");
            if(!$this->oDb->isFieldExists('bx_credits_history', 'cleared'))
                $this->oDb->query("ALTER TABLE `bx_credits_history` ADD `cleared` int(11) NOT NULL DEFAULT '0' AFTER `date`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
