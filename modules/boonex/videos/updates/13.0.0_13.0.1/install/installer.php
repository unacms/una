<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxVideosUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_videos_entries', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_videos_entries` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");

            if(!$this->oDb->isFieldExists('bx_videos_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_videos_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_videos_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_videos_svotes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_svotes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_videos_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_videos_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_videos_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_videos_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_videos_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_videos_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_videos_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_videos_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
