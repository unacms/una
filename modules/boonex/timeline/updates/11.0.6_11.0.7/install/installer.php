<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxTimelineUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_timeline_cache', 'important'))
                $this->oDb->query("ALTER TABLE `bx_timeline_cache` ADD `important` tinyint(4) NOT NULL default '0' AFTER `date`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
