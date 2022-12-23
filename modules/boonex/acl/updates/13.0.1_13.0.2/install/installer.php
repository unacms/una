<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxAclUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_acl_level_prices', 'immediate'))
                $this->oDb->query("ALTER TABLE `bx_acl_level_prices` ADD `immediate` tinyint(4) NOT NULL default '1' AFTER `price`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
