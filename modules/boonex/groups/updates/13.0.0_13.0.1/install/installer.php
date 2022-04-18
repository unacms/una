<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxGroupsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_groups_data', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_groups_data` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");
            if(!$this->oDb->isFieldExists('bx_groups_data', 'status_admin'))
                $this->oDb->query("ALTER TABLE `bx_groups_data` ADD `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active' AFTER `status`");

            if(!$this->oDb->isFieldExists('bx_groups_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_groups_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_groups_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_groups_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_groups_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_groups_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_groups_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_groups_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_groups_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_groups_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_groups_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_groups_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_groups_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_groups_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_groups_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_groups_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
