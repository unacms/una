<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxForumUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_forum_discussions', 'resolvable'))
                $this->oDb->query("ALTER TABLE `bx_forum_discussions` ADD `resolvable` tinyint(4) NOT NULL DEFAULT '0' AFTER `lock`");
            if(!$this->oDb->isFieldExists('bx_forum_discussions', 'resolved'))
                $this->oDb->query("ALTER TABLE `bx_forum_discussions` ADD `resolved` tinyint(4) NOT NULL DEFAULT '0' AFTER `resolvable`");
            if(!$this->oDb->isFieldExists('bx_forum_discussions', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_forum_discussions` ADD `cf` int(11) NOT NULL default '1' AFTER `allow_view_to`");

            if(!$this->oDb->isFieldExists('bx_forum_categories', 'icon'))
                $this->oDb->query("ALTER TABLE `bx_forum_categories` ADD `icon` text NOT NULL AFTER `visible_for_levels`");
                
            if(!$this->oDb->isFieldExists('bx_forum_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_forum_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_forum_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_forum_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_forum_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_forum_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_forum_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_forum_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_forum_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_forum_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_forum_polls_answers_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_forum_polls_answers_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
