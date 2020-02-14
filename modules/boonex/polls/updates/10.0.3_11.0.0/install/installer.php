<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPollsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_polls_entries', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_polls_entries` ADD `labels` text NOT NULL AFTER `cat`");
            if(!$this->oDb->isFieldExists('bx_polls_entries', 'location'))
                $this->oDb->query("ALTER TABLE `bx_polls_entries` ADD `location` text NOT NULL AFTER `labels`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
