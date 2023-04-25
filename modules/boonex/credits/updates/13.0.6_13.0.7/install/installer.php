<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxCreditsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_credits_bundles', 'description'))
                $this->oDb->query("ALTER TABLE `bx_credits_bundles` ADD `description` varchar(255) NOT NULL AFTER `title`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
