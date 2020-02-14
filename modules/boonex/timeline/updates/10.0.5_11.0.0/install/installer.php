<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxTimelineUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'system')) {
                $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `system` tinyint(4) NOT NULL default '1' AFTER `owner_id`");
                
                $this->oDb->query("UPDATE `bx_timeline_events` SET `system`=0 WHERE SUBSTRING(type, 1, 16) = 'timeline_common_'");
            }
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'location'))
                $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `location` text NOT NULL AFTER `description`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
