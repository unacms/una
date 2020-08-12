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
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'object_owner_id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `object_owner_id` int(11) NOT NULL default '0' AFTER `object_id`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
