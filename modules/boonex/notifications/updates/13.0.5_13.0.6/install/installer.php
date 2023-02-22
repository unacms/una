<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxNtfsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if($this->oDb->isTableExists('bx_notifications_events2users') && !$this->oDb->isTableExists('bx_notifications_read'))
                $this->oDb->query("RENAME TABLE `bx_notifications_events2users` TO `bx_notifications_read`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
