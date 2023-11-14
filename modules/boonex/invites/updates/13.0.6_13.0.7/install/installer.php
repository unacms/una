<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxInvUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_inv_invites', 'redirect'))
                $this->oDb->query("ALTER TABLE `bx_inv_invites` ADD `redirect` varchar(255) NOT NULL default '' AFTER `key`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
