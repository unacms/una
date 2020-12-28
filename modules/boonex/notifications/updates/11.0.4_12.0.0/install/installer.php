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
            if(!$this->oDb->isFieldExists('bx_notifications_settings', 'value'))
                $this->oDb->query("ALTER TABLE `bx_notifications_settings` ADD `value` tinyint(4) NOT NULL default '1' AFTER `title`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
