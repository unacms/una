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
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'rrate'))
        	        $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `rrate` float NOT NULL default '0' AFTER `votes`");
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'rvotes'))
        	        $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `rvotes` int(11) NOT NULL default '0' AFTER `rrate`");
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'published'))
        	        $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `published` int(11) NOT NULL default '0' AFTER `date`");
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'status')) {
        	        $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `status` enum ('active', 'awaiting', 'failed', 'hidden', 'deleted') NOT NULL DEFAULT 'active' AFTER `published`");
                $this->oDb->query("UPDATE `bx_timeline_events` set `status`='hidden' WHERE 1");
                $this->oDb->query("UPDATE `bx_timeline_events` set `status`='active' WHERE `active`='1'");
                $this->oDb->query("UPDATE `bx_timeline_events` set `status`='deleted' WHERE `hidden`='1'");
                $this->oDb->query("UPDATE `bx_timeline_events` set `status`='awaiting' WHERE `date`>UNIX_TIMESTAMP()");
                $this->oDb->query("ALTER TABLE `bx_timeline_events` DROP `hidden`");
            }

            if(!$this->oDb->isIndexExists('bx_timeline_events', 'object_id'))
            	    $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD INDEX `object_id` (`object_id`)");

            if(!$this->oDb->isIndexExists('bx_timeline_links', 'profile_id'))
            	    $this->oDb->query("ALTER TABLE `bx_timeline_links` ADD INDEX `profile_id` (`profile_id`)");

            if($this->oDb->isIndexExists('bx_timeline_links2events', 'link'))
                $this->oDb->query("ALTER TABLE `bx_timeline_links2events` DROP INDEX `link`");
            $this->oDb->query("ALTER TABLE `bx_timeline_links2events` ADD UNIQUE KEY `link` (`link_id`, `event_id`)");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
