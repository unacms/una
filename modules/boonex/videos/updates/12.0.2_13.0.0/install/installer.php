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
            if(!$this->oDb->isFieldExists('bx_videos_entries', 'video_source'))
                $this->oDb->query("ALTER TABLE `bx_videos_entries` ADD `video_source` enum('upload', 'embed') NOT NULL DEFAULT 'upload' AFTER `poster`");
            if(!$this->oDb->isFieldExists('bx_videos_entries', 'video_embed'))
                $this->oDb->query("ALTER TABLE `bx_videos_entries` ADD `video_embed` TEXT AFTER `video`");
            if(!$this->oDb->isFieldExists('bx_videos_entries', 'video_embed_data'))
                $this->oDb->query("ALTER TABLE `bx_videos_entries` ADD `video_embed_data` TEXT AFTER `video_embed`");

            if(!$this->oDb->isFieldExists('bx_videos_reports_track', 'checked_by'))
                $this->oDb->query("ALTER TABLE `bx_videos_reports_track` ADD `checked_by` int(11) NOT NULL default '0' AFTER `date`");
            if(!$this->oDb->isFieldExists('bx_videos_reports_track', 'status'))
                $this->oDb->query("ALTER TABLE `bx_videos_reports_track` ADD `status` tinyint(11) NOT NULL default '0' AFTER `checked_by`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
