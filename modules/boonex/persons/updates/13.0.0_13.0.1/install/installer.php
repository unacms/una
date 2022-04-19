<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPersonsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_persons_data', 'last_name'))
                $this->oDb->query("ALTER TABLE `bx_persons_data` ADD `last_name` varchar(255) NOT NULL AFTER `fullname`");

            if(!$this->oDb->isFieldExists('bx_persons_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_persons_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_persons_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_persons_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_persons_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_persons_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_persons_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_persons_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_persons_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_persons_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_persons_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_persons_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_persons_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_persons_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_persons_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_persons_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
