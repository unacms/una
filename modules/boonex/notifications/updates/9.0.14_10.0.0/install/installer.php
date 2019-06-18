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
            	if(!$this->oDb->isIndexExists('bx_notifications_events', 'object_owner_id'))
            	    $this->oDb->query("ALTER TABLE `bx_notifications_events` ADD INDEX `object_owner_id` (`object_owner_id`)");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
