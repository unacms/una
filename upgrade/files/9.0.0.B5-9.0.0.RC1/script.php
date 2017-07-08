<?php

    if (!$this->oDb->isFieldExists('sys_objects_cmts', 'Module'))
        $this->oDb->query("ALTER TABLE `sys_objects_cmts` ADD `Module` varchar(32) NOT NULL AFTER `Name`");

    if (!$this->oDb->isFieldExists('sys_form_inputs', 'unique'))
        $this->oDb->query("ALTER TABLE `sys_form_inputs` ADD `unique` tinyint(4) NOT NULL DEFAULT '0' AFTER `required`");

    if (!$this->oDb->isFieldExists('sys_form_pre_lists', 'extendable')) {
        $this->oDb->query("ALTER TABLE `sys_form_pre_lists` ADD `extendable` tinyint(4) unsigned NOT NULL default '1' AFTER `use_for_sets`");
        $this->oDb->query("UPDATE `sys_form_pre_lists` SET `extendable` = '0' WHERE `key` IN('sys_report_types', 'Country')");
    }

    return true;
