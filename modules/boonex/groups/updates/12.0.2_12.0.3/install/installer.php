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
            if(!$this->oDb->isFieldExists('bx_groups_data', 'status'))
                $this->oDb->query("ALTER TABLE `bx_groups_data` ADD `status` enum('active','awaiting','hidden') NOT NULL DEFAULT 'active' AFTER `allow_post_to`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
