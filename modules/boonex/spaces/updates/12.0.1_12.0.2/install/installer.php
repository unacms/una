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
            if(!$this->oDb->isFieldExists('bx_spaces_data', 'status'))
                $this->oDb->query("ALTER TABLE `bx_spaces_data` ADD `status` enum('active','awaiting','hidden') NOT NULL DEFAULT 'active' AFTER `allow_post_to`");

            if(!$this->oDb->isFieldExists('bx_spaces_admins', 'order'))
                $this->oDb->query("ALTER TABLE `bx_spaces_admins` ADD `order` varchar(32) NOT NULL default '' AFTER `role`");
            if(!$this->oDb->isFieldExists('bx_spaces_admins', 'expired'))
                $this->oDb->query("ALTER TABLE `bx_spaces_admins` ADD `expired` int(11) unsigned NOT NULL default '0' AFTER `added`");

            if(!$this->oDb->isFieldExists('bx_spaces_favorites_track', 'list_id'))
                $this->oDb->query("ALTER TABLE `bx_spaces_favorites_track` ADD `list_id` int(11) NOT NULL default '0' AFTER `author_id`");
        }

        return parent::actionExecuteSql($sOperation);
    }
}
