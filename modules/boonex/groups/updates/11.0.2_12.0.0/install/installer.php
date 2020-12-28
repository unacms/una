<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 */

class BxGroupsUpdater extends BxDolStudioUpdater
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function actionExecuteSql($sOperation)
    {
        if($sOperation == 'install') {
            if(!$this->oDb->isFieldExists('bx_groups_cmts', 'cmt_pinned'))
                $this->oDb->query("ALTER TABLE `bx_groups_cmts` ADD `cmt_pinned` int(11) NOT NULL default '0' AFTER `cmt_replies`");

            if(!$this->oDb->isFieldExists('bx_groups_admins', 'role'))
                $this->oDb->query("ALTER TABLE `bx_groups_admins` ADD `role` int(10) unsigned NOT NULL default '0' AFTER `fan_id`");
            if(!$this->oDb->isFieldExists('bx_groups_admins', 'order'))
                $this->oDb->query("ALTER TABLE `bx_groups_admins` ADD order` varchar(32) NOT NULL default '' AFTER `role`");
            if(!$this->oDb->isFieldExists('bx_groups_admins', 'added'))
                $this->oDb->query("ALTER TABLE `bx_groups_admins` ADD `added` int(11) unsigned NOT NULL default '0' AFTER `order`");
            if(!$this->oDb->isFieldExists('bx_groups_admins', 'expired'))
                $this->oDb->query("ALTER TABLE `bx_groups_admins` ADD `expired` int(11) unsigned NOT NULL default '0' AFTER `added`");

            if(!$this->oDb->isFieldExists('bx_groups_favorites_track', 'list_id'))
                $this->oDb->query("ALTER TABLE `bx_groups_favorites_track` ADD `list_id` int(11) NOT NULL default '0' AFTER `author_id`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
