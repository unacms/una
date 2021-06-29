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
            if(!$this->oDb->isFieldExists('bx_events_data', 'published'))
                $this->oDb->query("ALTER TABLE `bx_events_data` ADD `published` int(11) NOT NULL AFTER `changed`");
            if(!$this->oDb->isFieldExists('bx_events_data', 'status'))
                $this->oDb->query("ALTER TABLE `bx_events_data` ADD `status` enum('active','awaiting','hidden') NOT NULL DEFAULT 'active' AFTER `allow_post_to`");

            if(!$this->oDb->isFieldExists('bx_events_admins', 'order'))
                $this->oDb->query("ALTER TABLE `bx_events_admins` ADD `order` varchar(32) NOT NULL default '' AFTER `role`");
            if(!$this->oDb->isFieldExists('bx_events_admins', 'expired'))
                $this->oDb->query("ALTER TABLE `bx_events_admins` ADD `expired` int(11) unsigned NOT NULL default '0' AFTER `added`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
