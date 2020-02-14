<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxPersonsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_persons_data', 'labels'))
                $this->oDb->query("ALTER TABLE `bx_persons_data` ADD `labels` text NOT NULL AFTER `birthday`");
            if(!$this->oDb->isFieldExists('bx_persons_data', 'location'))
                $this->oDb->query("ALTER TABLE `bx_persons_data` ADD `location` text NOT NULL AFTER `labels`");
            if(!$this->oDb->isFieldExists('bx_persons_data', 'allow_contact_to'))
                $this->oDb->query("ALTER TABLE `bx_persons_data` ADD `allow_contact_to` varchar(16) NOT NULL DEFAULT '3' AFTER `allow_post_to`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
