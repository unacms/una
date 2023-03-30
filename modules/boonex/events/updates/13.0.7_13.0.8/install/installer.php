<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxEventsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_events_pics', 'dimensions'))
                $this->oDb->query("ALTER TABLE `bx_events_pics` ADD `dimensions` varchar(12) NOT NULL AFTER `size`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
