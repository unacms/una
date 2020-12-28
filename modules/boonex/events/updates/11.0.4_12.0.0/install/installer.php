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
            if(!$this->oDb->isFieldExists('bx_events_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_events_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");

            if(!$this->oDb->isFieldExists('bx_events_admins', 'role'))
                $this->oDb->query("ALTER TABLE `bx_events_admins` ADD `role` int(10) unsigned NOT NULL default '0' AFTER `fan_id`");
            if(!$this->oDb->isFieldExists('bx_events_admins', 'added'))
                $this->oDb->query("ALTER TABLE `bx_events_admins` ADD `added` int(11) unsigned NOT NULL default '0' AFTER `role`");

            if(!$this->oDb->isFieldExists('bx_events_favorites_track', 'added'))
                $this->oDb->query("ALTER TABLE `bx_events_favorites_track` ADD `list_id` int(11) NOT NULL default '0' AFTER `author_id`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
