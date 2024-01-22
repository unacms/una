<?php

    if (!$this->oDb->isFieldExists('sys_modules', 'subtypes'))
        $this->oDb->query("ALTER TABLE `sys_modules` ADD `subtypes` int(11) unsigned NOT NULL default '0' AFTER `type`");

    return true;
