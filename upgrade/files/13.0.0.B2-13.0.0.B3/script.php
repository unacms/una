<?php

    if (!$this->oDb->isFieldExists('sys_acl_levels', 'PasswordExpired'))
        $this->oDb->query("ALTER TABLE `sys_acl_levels` ADD `PasswordExpired` int(11) NOT NULL default '0'");

    if (!$this->oDb->isFieldExists('sys_acl_levels', 'PasswordExpiredNotify'))
        $this->oDb->query("ALTER TABLE `sys_acl_levels` ADD `PasswordExpiredNotify` int(11) NOT NULL default '0'");

    if (!$this->oDb->isFieldExists('sys_accounts', 'password_expired'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `password_expired` int(11) NOT NULL DEFAULT '0'");

    if (!$this->oDb->isFieldExists('sys_menu_items', 'addon_cache'))
        $this->oDb->query("ALTER TABLE `sys_menu_items` ADD `addon_cache` tinyint(4) NOT NULL DEFAULT 0 AFTER `addon`");

    return true;
