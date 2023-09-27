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
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `labels` text NOT NULL AFTER `description`");

            if(!$this->oDb->isFieldExists('bx_timeline_events_slice', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_timeline_events_slice` ADD `labels` text NOT NULL AFTER `description`");

            if($this->oDb->isIndexExists('bx_timeline_handlers', 'handler'))
                $this->oDb->query("ALTER TABLE `bx_timeline_handlers` DROP INDEX `handler`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
