<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAlbumsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }
    
    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_albums_albums', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");

            if(!$this->oDb->isFieldExists('bx_albums_files2albums', 'reports'))
                $this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD `reports` int(11) NOT NULL default '0' AFTER `comments`");
            if(!$this->oDb->isFieldExists('bx_albums_files2albums', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_albums_files2albums` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");

            if(!$this->oDb->isFieldExists('bx_albums_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_albums_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_albums_cmts_media', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_albums_cmts_media` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_albums_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_votes_media', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_votes_media` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_views_media_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_views_media_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_meta_keywords_media', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_meta_keywords_media` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_meta_keywords_media_camera', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_meta_keywords_media_camera` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_favorites_media_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_favorites_media_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_albums_scores_media', 'id'))
                $this->oDb->query("ALTER TABLE `bx_albums_scores_media` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
