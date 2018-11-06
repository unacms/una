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
        if ($this->oDb->isIndexExists('bx_notifications_settings2users', 'setting'))
            $this->oDb->query("ALTER TABLE `bx_notifications_settings2users` DROP INDEX `setting`");

        $this->oDb->query("ALTER TABLE `bx_notifications_settings2users` ADD UNIQUE `setting` (`setting_id`, `user_id`)");
    	}

    	return parent::actionExecuteSql($sOperation);
    }
}
