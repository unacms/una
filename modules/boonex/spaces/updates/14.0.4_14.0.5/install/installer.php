<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxSpacesUpdater extends BxDolStudioUpdater
{
    public function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_spaces_data', 'stg_tabs'))
                $this->oDb->query("ALTER TABLE `bx_spaces_data` ADD `stg_tabs` text NOT NULL AFTER `status_admin`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
