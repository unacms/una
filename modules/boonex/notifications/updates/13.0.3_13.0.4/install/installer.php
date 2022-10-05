<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxNtfsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_notifications_events', 'source'))
                $this->oDb->query("ALTER TABLE `bx_notifications_events` ADD `source` varchar(32) NOT NULL default '' AFTER `content`");

            if(!$this->oDb->isFieldExists('bx_notifications_handlers', 'priority'))
                $this->oDb->query("ALTER TABLE `bx_notifications_handlers` ADD `priority` int(11) NOT NULL default '0' AFTER `privacy`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
