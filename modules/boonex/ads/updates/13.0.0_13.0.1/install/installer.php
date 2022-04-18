<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAdsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_ads_entries', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_ads_entries` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");

            if(!$this->oDb->isFieldExists('bx_ads_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_ads_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_ads_reviews', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_ads_reviews` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_ads_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_ads_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_ads_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_ads_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_ads_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_ads_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_ads_favorites_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_favorites_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_ads_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_ads_polls_answers_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_ads_polls_answers_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
