<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxStrmUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_stream_streams', 'cf'))
                $this->oDb->query("ALTER TABLE `bx_stream_streams` ADD `cf` int(11) NOT NULL default '1' AFTER `featured`");

            if(!$this->oDb->isFieldExists('bx_stream_cmts', 'cmt_cf'))
                $this->oDb->query("ALTER TABLE `bx_stream_cmts` ADD `cmt_cf` int(11) NOT NULL default '1' AFTER `cmt_pinned`");

            if(!$this->oDb->isFieldExists('bx_stream_votes', 'id'))
                $this->oDb->query("ALTER TABLE `bx_stream_votes` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_stream_reactions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_stream_reactions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_stream_views_track', 'id'))
                $this->oDb->query("ALTER TABLE `bx_stream_views_track` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_stream_meta_keywords', 'id'))
                $this->oDb->query("ALTER TABLE `bx_stream_meta_keywords` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_stream_meta_mentions', 'id'))
                $this->oDb->query("ALTER TABLE `bx_stream_meta_mentions` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_stream_reports', 'id'))
                $this->oDb->query("ALTER TABLE `bx_stream_reports` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");

            if(!$this->oDb->isFieldExists('bx_stream_scores', 'id'))
                $this->oDb->query("ALTER TABLE `bx_stream_scores` ADD `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT FIRST");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
