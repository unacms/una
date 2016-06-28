<?php

    if (!$this->oDb->isFieldExists('sys_objects_auths', 'Name'))
        $this->oDb->res("ALTER TABLE `sys_objects_auths` ADD `Name` varchar(64) NOT NULL AFTER `ID`");
    if (!$this->oDb->isFieldExists('sys_objects_auths', 'OnClick'))
        $this->oDb->res("ALTER TABLE `sys_objects_auths` ADD `OnClick` varchar(255) NOT NULL AFTER `Link`");
    if (!$this->oDb->isFieldExists('sys_objects_auths', 'Icon'))
        $this->oDb->res("ALTER TABLE `sys_objects_auths` ADD `Icon` varchar(64) NOT NULL AFTER `OnClick`");

    if (!$this->oDb->isFieldExists('sys_objects_cmts', 'TriggerFieldAuthor'))
        $this->oDb->res("ALTER TABLE `sys_objects_cmts` ADD `TriggerFieldAuthor` varchar(32) NOT NULL AFTER `TriggerFieldId`");
    
    if (!$this->oDb->isFieldExists('sys_objects_vote', 'TriggerFieldAuthor'))
        $this->oDb->res("ALTER TABLE `sys_objects_vote` ADD `TriggerFieldAuthor` varchar(32) NOT NULL default '' AFTER `TriggerFieldId`");

    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'hidden_on'))
        $this->oDb->res("ALTER TABLE `sys_pages_blocks` ADD `hidden_on` varchar(255) NOT NULL DEFAULT '' AFTER `visible_for_levels`");

    return true;
