<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxSpacesUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_spaces_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_spaces_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");

            if(!$this->oDb->isFieldExists('bx_spaces_admins', 'role'))
                $this->oDb->query("ALTER TABLE `bx_spaces_admins` ADD `role` int(10) unsigned NOT NULL default '0' AFTER `fan_id`");
            if(!$this->oDb->isFieldExists('bx_spaces_admins', 'added'))
                $this->oDb->query("ALTER TABLE `bx_spaces_admins` ADD `added` int(11) unsigned NOT NULL default '0' AFTER `role`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
