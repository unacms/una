<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxGroupsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_groups_data', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_groups_data` ADD `labels` text NOT NULL AFTER `group_desc`");
            if(!$this->oDb->isFieldExists('bx_groups_data', 'location'))
                $this->oDb->query("ALTER TABLE `bx_groups_data` ADD `location` text NOT NULL AFTER `labels`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
