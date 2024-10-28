<?php

    if (!$this->oDb->isFieldExists('sys_accounts', 'password_changed'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `password_changed` int(11) NOT NULL DEFAULT '0' AFTER `password`");

    if ($this->oDb->isFieldExists('sys_accounts', 'password_expired'))
        $this->oDb->query("ALTER TABLE `sys_accounts` DROP `password_expired`");

    return true;
