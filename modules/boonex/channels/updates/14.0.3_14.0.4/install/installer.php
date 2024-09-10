<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxCnlUpdater extends BxDolStudioUpdater
{
    public function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_cnl_data', 'lc_id'))
                $this->oDb->query("ALTER TABLE `bx_cnl_data` ADD `lc_id` int(11) NOT NULL default '0' AFTER `channel_name`");
            if(!$this->oDb->isFieldExists('bx_cnl_data', 'lc_date'))
                $this->oDb->query("ALTER TABLE `bx_cnl_data` ADD `lc_date` int(11) NOT NULL default '0' AFTER `lc_id`");
            if(!$this->oDb->isFieldExists('bx_cnl_data', 'contents'))
                $this->oDb->query("ALTER TABLE `bx_cnl_data` ADD `contents` int(11) NOT NULL default '0' AFTER `lc_date`");

            if(!$this->oDb->isFieldExists('bx_cnl_content', 'date'))
                $this->oDb->query("ALTER TABLE `bx_cnl_content` ADD `date` int(11) NOT NULL default '0' AFTER `module_name`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
