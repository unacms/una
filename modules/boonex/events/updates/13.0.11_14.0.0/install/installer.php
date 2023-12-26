<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxEventsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_events_data', 'hashtag'))
                $this->oDb->query("ALTER TABLE `bx_events_data` ADD `hashtag` varchar(32) NOT NULL AFTER `event_desc`");
            if(!$this->oDb->isFieldExists('bx_events_data', 'threshold'))
                $this->oDb->query("ALTER TABLE `bx_events_data` ADD `threshold` int(11) unsigned NOT NULL default '0' AFTER `location`");
        }

        return parent::actionExecuteSql($sOperation);
    }

}
