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
            if(!$this->oDb->isFieldExists('bx_events_data', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_events_data` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");
            if(!$this->oDb->isFieldExists('bx_events_data', 'status_admin'))
                $this->oDb->query("ALTER TABLE `bx_events_data` ADD `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active' AFTER `status`");

            if(!$this->oDb->isFieldExists('bx_events_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_events_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_events_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_events_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_events_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_events_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_events_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_events_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_events_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_events_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_events_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_events_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_events_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_events_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_events_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_events_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
