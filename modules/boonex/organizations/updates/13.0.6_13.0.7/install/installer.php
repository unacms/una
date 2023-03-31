<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxOrgsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_organizations_pics', 'dimensions'))
                $this->oDb->query("ALTER TABLE `bx_organizations_pics` ADD `dimensions` varchar(12) NOT NULL AFTER `ext`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
