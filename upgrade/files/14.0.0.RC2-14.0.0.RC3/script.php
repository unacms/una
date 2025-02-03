<?php

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

    return true;
