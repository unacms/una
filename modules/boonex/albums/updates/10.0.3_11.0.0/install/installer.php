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
            if(!$this->oDb->isFieldExists('bx_albums_albums', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `labels` text NOT NULL AFTER `text`");
            if(!$this->oDb->isFieldExists('bx_albums_albums', 'location'))
                $this->oDb->query("ALTER TABLE `bx_albums_albums` ADD `location` text NOT NULL AFTER `labels`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
