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
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'source'))
                $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `source` varchar(32) NOT NULL default '' AFTER `content`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
