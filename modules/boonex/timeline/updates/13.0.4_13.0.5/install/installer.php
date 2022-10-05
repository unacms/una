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
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'reacted'))
                $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `reacted` int(11) NOT NULL default '0' AFTER `published`");

            if(!$this->oDb->isFieldExists('bx_timeline_reposts_track', 'active'))
                $this->oDb->query("ALTER TABLE `bx_timeline_reposts_track` ADD `active` tinyint(4) NOT NULL default '1' AFTER `date`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
