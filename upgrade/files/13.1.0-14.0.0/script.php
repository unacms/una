<?php

    // ------------------ 14.0.0.A2

    if (!$this->oDb->isFieldExists('sys_modules', 'subtypes'))
        $this->oDb->query("ALTER TABLE `sys_modules` ADD `subtypes` int(11) unsigned NOT NULL default '0' AFTER `type`");

    // ------------------ 14.0.0.B1

    if (!$this->oDb->isFieldExists('sys_cmts_images', 'dimensions'))
        $this->oDb->query("ALTER TABLE `sys_cmts_images` ADD `dimensions` varchar(24) NOT NULL AFTER `size`");

    if (!$this->oDb->isFieldExists('sys_objects_menu', 'persistent'))
        $this->oDb->query("ALTER TABLE `sys_objects_menu` ADD `persistent` tinyint(4) NOT NULL DEFAULT '0' AFTER `template_id`");

    // ------------------ 14.0.0.B2

    if (!$this->oDb->isFieldExists('sys_objects_score', 'is_undo'))
        $this->oDb->query("ALTER TABLE `sys_objects_score` ADD `is_undo` tinyint(1) NOT NULL default '0' AFTER `pruning`");

    // ------------------ 14.0.0.RC1

    if (!$this->oDb->isFieldExists('sys_options', 'info'))
        $this->oDb->query("ALTER TABLE `sys_options` ADD `info` varchar(255) NOT NULL default '' AFTER `caption`");

    $aPathInfo = pathinfo(__FILE__);
    $this->oDb->executeSQL($aPathInfo['dirname'] . '/sql_update_info.sql');

    if (!$this->oDb->isFieldExists('sys_agents_models', 'for_asst'))
        $this->oDb->query("ALTER TABLE `sys_agents_models` ADD `for_asst` tinyint(4) NOT NULL DEFAULT '0' AFTER `params`");

    if (!$this->oDb->isFieldExists('sys_agents_models', 'active'))
        $this->oDb->query("ALTER TABLE `sys_agents_models` ADD `active` tinyint(4) NOT NULL DEFAULT '1' AFTER `for_asst`");

    if (!$this->oDb->isFieldExists('sys_agents_models', 'hidden'))
        $this->oDb->query("ALTER TABLE `sys_agents_models` ADD `hidden` tinyint(4) NOT NULL DEFAULT '0' AFTER `active`");
    
    $this->oDb->query("UPDATE `sys_agents_models` SET `for_asst` = 0, `active` = 1, `hidden` = 0 WHERE `name` = 'gpt-3.5-turbo'");
    $this->oDb->query("UPDATE `sys_agents_models` SET `for_asst` = 1, `active` = 1, `hidden` = 0 WHERE `name` = 'gpt-4o'");

    // ------------------ 14.0.0.RC2

    if (!$this->oDb->isFieldExists('sys_accounts', 'password_changed'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `password_changed` int(11) NOT NULL DEFAULT '0' AFTER `password`");

    if ($this->oDb->isFieldExists('sys_accounts', 'password_expired'))
        $this->oDb->query("ALTER TABLE `sys_accounts` DROP `password_expired`");

    // ------------------ 14.0.0.RC3

    if (!$this->oDb->isFieldExists('sys_privacy_groups', 'order')) {
        $this->oDb->query("ALTER TABLE `sys_privacy_groups` ADD `order` int(11) NOT NULL default '0' AFTER `visible`");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 0 WHERE `id` = '1'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 7 WHERE `id` = '2'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 1 WHERE `id` = '3'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 0 WHERE `id` = '4'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 2 WHERE `id` = '5'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 3 WHERE `id` = '6'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 4 WHERE `id` = '7'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 5 WHERE `id` = '8'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 6 WHERE `id` = '9'");
        $this->oDb->query("UPDATE `sys_privacy_groups` SET `order` = 0 WHERE `id` = '99'");
    }

    if (!$this->oDb->isFieldExists('sys_objects_menu', 'config_api'))
        $this->oDb->query("ALTER TABLE `sys_objects_menu` ADD `config_api` text NOT NULL AFTER `template_id`");

    if (!$this->oDb->isFieldExists('sys_menu_items', 'config_api'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `config_api` text NOT NULL AFTER `hidden_on_col`");

    if (!$this->oDb->isFieldExists('sys_objects_page', 'config_api'))
        $this->oDb->query("ALTER TABLE `sys_objects_page` ADD `config_api` text NOT NULL AFTER `inj_footer`");

    if (!$this->oDb->isFieldExists('sys_pages_blocks', 'config_api'))
        $this->oDb->query("ALTER TABLE `sys_pages_blocks` ADD `config_api` text NOT NULL AFTER `cache_lifetime`");

    // ------------------ 14.0.0.RC5

    if (!$this->oDb->isIndexExists('sys_accounts', 'logged'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD KEY `logged` (`logged`)");
    
    return true;
