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
            if(!$this->oDb->isFieldExists('bx_organizations_data', 'multicat'))
                $this->oDb->query("ALTER TABLE `bx_organizations_data` ADD `multicat` text NOT NULL AFTER `cmt_replies`");

            if(!$this->oDb->isFieldExists('bx_organizations_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_organizations_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");

            if(!$this->oDb->isFieldExists('bx_organizations_favorites_track', 'list_id'))
                $this->oDb->query("ALTER TABLE `bx_organizations_favorites_track` ADD `list_id` int(11) NOT NULL default '0' AFTER `author_id`");

            if(!$this->oDb->isFieldExists('bx_organizations_admins', 'role'))
                $this->oDb->query("ALTER TABLE `bx_organizations_admins` ADD `role` int(10) unsigned NOT NULL default '0' AFTER `fan_id`");
            if(!$this->oDb->isFieldExists('bx_organizations_admins', 'added'))
                $this->oDb->query("ALTER TABLE `bx_organizations_admins` ADD `added` int(11) unsigned NOT NULL default '0' AFTER `role`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
