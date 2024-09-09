<?php

    if (!$this->oDb->isFieldExists('sys_options', 'info'))
        $this->oDb->query("ALTER TABLE `sys_options` ADD `info` varchar(255) NOT NULL default '' AFTER `caption`");

    if (!$this->oDb->isFieldExists('sys_agents_models', 'for_asst'))
        $this->oDb->query("ALTER TABLE `sys_agents_models` ADD `for_asst` tinyint(4) NOT NULL DEFAULT '0' AFTER `params`");

    if (!$this->oDb->isFieldExists('sys_agents_models', 'active'))
        $this->oDb->query("ALTER TABLE `sys_agents_models` ADD `active` tinyint(4) NOT NULL DEFAULT '1' AFTER `for_asst`");

    if (!$this->oDb->isFieldExists('sys_agents_models', 'hidden'))
        $this->oDb->query("ALTER TABLE `sys_agents_models` ADD `hidden` tinyint(4) NOT NULL DEFAULT '0' AFTER `active`");
    
    $this->oDb->query("UPDATE `sys_agents_models` SET `for_asst` = 0, `active` = 1, `hidden` = 0 WHERE `name` = 'gpt-3.5-turbo'");
    $this->oDb->query("UPDATE `sys_agents_models` SET `for_asst` = 1, `active` = 1, `hidden` = 0 WHERE `name` = 'gpt-4o'");

    return true;
