<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPhotosUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_photos_entries', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_photos_entries` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");

            if(!$this->oDb->isFieldExists('bx_photos_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_photos_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_photos_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_svotes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_svotes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_meta_keywords_camera', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_meta_keywords_camera` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_photos_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_photos_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
