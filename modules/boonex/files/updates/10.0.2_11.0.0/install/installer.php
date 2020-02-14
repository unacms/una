<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxFilesUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_files_main', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_files_main` ADD `labels` text NOT NULL AFTER `data_processed`");
            if(!$this->oDb->isFieldExists('bx_files_main', 'location'))
                $this->oDb->query("ALTER TABLE `bx_files_main` ADD `location` text NOT NULL AFTER `labels`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
