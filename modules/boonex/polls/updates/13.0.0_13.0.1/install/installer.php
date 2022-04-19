<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPollsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_polls_entries', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_polls_entries` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");

            if(!$this->oDb->isFieldExists('bx_polls_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_polls_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_polls_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_polls_votes_subentries', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_votes_subentries` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_polls_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_polls_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_polls_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_polls_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_polls_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_polls_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_polls_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_polls_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
