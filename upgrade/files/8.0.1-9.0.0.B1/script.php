<?php

    if (!$this->oDb->isFieldExists('sys_objects_auths', 'Name')) {
        $s = $this->oDb->prepare("ALTER TABLE `sys_objects_auths` ADD `Name` varchar(64) NOT NULL AFTER `ID`");
        $this->oDb->res($s);
    }
    if (!$this->oDb->isFieldExists('sys_objects_auths', 'OnClick')) {
        $s = $this->oDb->prepare("ALTER TABLE `sys_objects_auths` ADD `OnClick` varchar(255) NOT NULL AFTER `Link`");
        $this->oDb->res($s);
    }
    if (!$this->oDb->isFieldExists('sys_objects_auths', 'Icon')) {
        $s = $this->oDb->prepare("ALTER TABLE `sys_objects_auths` ADD `Icon` varchar(64) NOT NULL AFTER `OnClick`");
        $this->oDb->res($s);
    }

    
    if (!$this->oDb->isFieldExists('sys_objects_cmts', 'TriggerFieldAuthor')) {
        $s = $this->oDb->prepare("ALTER TABLE `sys_objects_cmts` ADD `TriggerFieldAuthor` varchar(32) NOT NULL AFTER `TriggerFieldId`");
        $this->oDb->res($s);
    }


    if (!$this->oDb->isFieldExists('sys_objects_vote', 'TriggerFieldAuthor')) {
        $s = $this->oDb->prepare("ALTER TABLE `sys_objects_vote` ADD `TriggerFieldAuthor` varchar(32) NOT NULL default '' AFTER `TriggerFieldId`");
        $this->oDb->res($s);
    }


    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'hidden_on')) {
        $s = $this->oDb->prepare("ALTER TABLE `sys_pages_blocks` ADD `hidden_on` varchar(255) NOT NULL DEFAULT '' AFTER `visible_for_levels`");
        $this->oDb->res($s);
    }


    return true;
