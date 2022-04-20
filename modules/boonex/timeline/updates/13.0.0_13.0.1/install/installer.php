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
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'object_cf'))
                $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `object_cf` int(11) NOT NULL default '1' AFTER `object_privacy_view`");
            if(!$this->oDb->isFieldExists('bx_timeline_events', 'status_admin'))
                $this->oDb->query("ALTER TABLE `bx_timeline_events` ADD `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active' AFTER `status`");

            if(!$this->oDb->isFieldExists('bx_timeline_reposts_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_reposts_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_timeline_comments', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_timeline_comments` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_timeline_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_timeline_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_timeline_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_timeline_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_timeline_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_timeline_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_timeline_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_timeline_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
