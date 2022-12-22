<?php

    if (!$this->oDb->isFieldExists('sys_accounts', 'active'))
        $this->oDb->query("ALTER TABLE `sys_accounts` ADD `active` int(11) NOT NULL DEFAULT '0' AFTER `password_expired`");


    return true;
