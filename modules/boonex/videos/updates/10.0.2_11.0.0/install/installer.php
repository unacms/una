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
            if(!$this->oDb->isFieldExists('bx_videos_entries', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_videos_entries` ADD `labels` text NOT NULL AFTER `duration`");
            if(!$this->oDb->isFieldExists('bx_videos_entries', 'location'))
                $this->oDb->query("ALTER TABLE `bx_videos_entries` ADD `location` text NOT NULL AFTER `labels`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
