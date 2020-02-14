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
            if(!$this->oDb->isFieldExists('bx_photos_entries', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_photos_entries` ADD `labels` text NOT NULL AFTER `text`");
            if(!$this->oDb->isFieldExists('bx_photos_entries', 'location'))
                $this->oDb->query("ALTER TABLE `bx_photos_entries` ADD `location` text NOT NULL AFTER `labels`");
            if(!$this->oDb->isFieldExists('bx_photos_entries', 'exif'))
                $this->oDb->query("ALTER TABLE `bx_photos_entries` ADD `exif` text NOT NULL AFTER `status_admin`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
