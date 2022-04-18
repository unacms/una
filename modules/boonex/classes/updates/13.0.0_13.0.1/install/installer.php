<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxClssUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_classes_classes', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_classes_classes` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");

            if(!$this->oDb->isFieldExists('bx_classes_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_classes_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_classes_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_classes_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_classes_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_classes_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_classes_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_classes_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_classes_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_classes_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_classes_polls_answers_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_classes_polls_answers_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
