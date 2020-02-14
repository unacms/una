<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxSpacesUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_spaces_data', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_spaces_data` ADD `labels` text NOT NULL AFTER `space_desc`");
            if(!$this->oDb->isFieldExists('bx_spaces_data', 'location'))
                $this->oDb->query("ALTER TABLE `bx_spaces_data` ADD `location` text NOT NULL AFTER `labels`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
